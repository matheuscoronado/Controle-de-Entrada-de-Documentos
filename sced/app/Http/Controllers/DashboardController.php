<?php
// app/Http/Controllers/DashboardController.php
// ============================================================
// Parte 4 — Dashboard Operacional
// Serve a view principal + endpoints JSON para os gráficos.
// ============================================================

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\User;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // ── View principal ───────────────────────────────────────

    public function index(): \Illuminate\View\View
    {
        $user = auth()->user();

        // ── KPIs de topo ──────────────────────────────────────
        $baseQuery = $this->baseQuery($user);

        $kpis = [
            'total'      => (clone $baseQuery)->count(),
            'novo'       => (clone $baseQuery)->where('status', 'novo')->count(),
            'em_analise' => (clone $baseQuery)->where('status', 'em_analise')->count(),
            'pendente'   => (clone $baseQuery)->where('status', 'pendente')->count(),
            'finalizado' => (clone $baseQuery)->where('status', 'finalizado')->count(),
            'desativado' => (clone $baseQuery)->where('status', 'desativado')->count(),
        ];

        // ── Fila de processos NOVOS (sem responsável) ─────────
        // Essa é a "home" de atribuição — processos aguardando analista
        $filaAtribuicao = Documento::with(['tipoDocumento', 'usuarioRegistro'])
            ->where('status', 'novo')
            ->whereNull('atribuido_a_id')
            ->when(!$user->podeAcessarAdmin(), fn($q) =>
                $q->where(function ($q2) use ($user) {
                    $q2->where('departamento_destino_id', $user->departamento_id)
                       ->orWhereNull('departamento_destino_id');
                })
            )
            ->orderBy('created_at', 'asc')
            ->limit(8)
            ->get();

        // ── Meus processos (se operador) ──────────────────────
        $meusProcessos = Documento::with(['tipoDocumento'])
            ->where('atribuido_a_id', $user->id)
            ->whereIn('status', ['em_analise', 'pendente'])
            ->orderByRaw("FIELD(status, 'pendente', 'em_analise')")
            ->orderBy('atribuido_em', 'asc')
            ->limit(8)
            ->get();

        // ── Recentes geral ────────────────────────────────────
        $recentes = (clone $baseQuery)
            ->with(['tipoDocumento', 'atribuidoA', 'usuarioRegistro'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // ── Analistas e seus processos ativos ─────────────────
        $analistas = User::withCount([
                'documentosRegistrados as processos_em_analise' => fn($q) =>
                    $q->where('atribuido_a_id', DB::raw('users.id'))
                      ->where('status', 'em_analise'),
            ])
            ->where('status', 'ativo')
            ->where('perfil', '!=', 'administrador')
            ->orderByDesc('processos_em_analise')
            ->limit(6)
            ->get();

        return view('dashboard', compact(
            'kpis', 'filaAtribuicao', 'meusProcessos', 'recentes', 'analistas'
        ));
    }

    // ── API: dados para gráficos (fetch a cada 30s via JS) ───

    public function metricas(): JsonResponse
    {
        $user  = auth()->user();
        $base  = $this->baseQuery($user);

        // Contagem por status
        $porStatus = (clone $base)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // Contagem por setor (top 6)
        $porSetor = (clone $base)
            ->selectRaw('setor_destino, COUNT(*) as total')
            ->groupBy('setor_destino')
            ->orderByDesc('total')
            ->limit(6)
            ->pluck('total', 'setor_destino');

        // Contagem por responsável (top 6)
        $porResponsavel = Documento::join('users', 'documentos.atribuido_a_id', '=', 'users.id')
            ->selectRaw('users.nome, COUNT(*) as total')
            ->whereIn('documentos.status', ['em_analise', 'pendente'])
            ->groupBy('users.id', 'users.nome')
            ->orderByDesc('total')
            ->limit(6)
            ->pluck('total', 'nome');

        // Volume diário (últimos 14 dias)
        $volumeDiario = Documento::selectRaw('DATE(created_at) as dia, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(14))
            ->groupBy('dia')
            ->orderBy('dia')
            ->get()
            ->map(fn($r) => [
                'dia'   => \Carbon\Carbon::parse($r->dia)->format('d/m'),
                'total' => $r->total,
            ]);

        // Pendentes com mais de 24h sem movimentação
        $atrasados = Documento::whereIn('status', ['novo', 'em_analise', 'pendente'])
            ->where('updated_at', '<', now()->subHours(24))
            ->count();

        return response()->json([
            'kpis' => [
                'total'      => Documento::count(),
                'novo'       => Documento::where('status', 'novo')->count(),
                'em_analise' => Documento::where('status', 'em_analise')->count(),
                'pendente'   => Documento::where('status', 'pendente')->count(),
                'finalizado' => Documento::where('status', 'finalizado')->count(),
                'atrasados'  => $atrasados,
            ],
            'por_status'      => $porStatus,
            'por_setor'       => $porSetor,
            'por_responsavel' => $porResponsavel,
            'volume_diario'   => $volumeDiario,
        ]);
    }

    // ── API: atribuir processo a operador ─────────────────────

    public function atribuir(Request $request, Documento $documento): JsonResponse
    {
        $this->authorize('assumir', $documento);

        $request->validate([
            'usuario_id'       => 'required|exists:users,id',
            'cargo_responsavel'=> 'nullable|in:N1,N2,N3',
        ]);

        $analista = User::findOrFail($request->usuario_id);

        // Usa o ProcessoService se disponível
        try {
            app(\App\Services\ProcessoService::class)->assumir($documento, $analista);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json([
            'success'   => true,
            'protocolo' => $documento->numero_protocolo,
            'analista'  => $analista->nome,
        ]);
    }

    // ── API: lista de analistas para o select de atribuição ──

    public function analistas(): JsonResponse
    {
        $analistas = User::where('status', 'ativo')
            ->where('perfil', '!=', 'administrador')
            ->withCount([
                'documentosRegistrados as ativos' => fn($q) =>
                    $q->whereIn('status', ['em_analise', 'pendente']),
            ])
            ->orderBy('nome')
            ->get(['id', 'nome', 'cargo', 'departamento_id']);

        return response()->json($analistas->map(fn($u) => [
            'id'    => $u->id,
            'nome'  => $u->nome,
            'cargo' => $u->cargo,
            'carga' => $u->ativos,   // nº de processos ativos
        ]));
    }

    // ── Helper: query base respeitando escopo do usuário ─────

    private function baseQuery(User $user)
    {
        $q = Documento::query();

        if (!$user->podeAcessarAdmin()) {
            $q->where(function ($q2) use ($user) {
                $q2->where('usuario_registro_id', $user->id)
                   ->orWhere('atribuido_a_id', $user->id);
            });
        }

        return $q;
    }
}
