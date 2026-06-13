<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\ArquivoAnexo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // ─────────────────────────────────────────────────────────
        // KPIS BASEADOS NO PERFIL DO USUÁRIO
        // ─────────────────────────────────────────────────────────
        
        if ($user->isAdmin()) {
            // Admin vê todos os processos
            $baseQuery = Documento::query();
        } elseif ($user->isN3()) {
            // N3 vê processos do seu setor e processos atribuídos a ele
            $baseQuery = Documento::where(function($q) use ($user) {
                $q->where('departamento_destino_id', $user->departamento_id)
                  ->orWhere('atribuido_a_id', $user->id)
                  ->orWhere('usuario_registro_id', $user->id);
            });
        } else {
            // N1 e N2 vêem:
            // 1. Processos que criou
            // 2. Processos atribuídos a ele
            // 3. Processos do seu setor (se tiver permissão para assumir)
            $baseQuery = Documento::where(function($q) use ($user) {
                $q->where('usuario_registro_id', $user->id)
                  ->orWhere('atribuido_a_id', $user->id);
                
                // Se o usuário pode assumir processos, mostra também processos do seu setor
                if ($user->podeAssumirProcesso()) {
                    $q->orWhere('departamento_destino_id', $user->departamento_id);
                }
            });
        }
        
        $kpis = [
            'total' => (clone $baseQuery)->count(),
            'novo' => (clone $baseQuery)->where('status', 'novo')->count(),
            'em_analise' => (clone $baseQuery)->where('status', 'em_analise')->count(),
            'pendente' => (clone $baseQuery)->where('status', 'pendente')->count(),
            'finalizado' => (clone $baseQuery)->where('status', 'finalizado')->count(),
            'desativado' => (clone $baseQuery)->where('status', 'desativado')->count(),
        ];
        
        // Processos recentes (limitado ao perfil do usuário)
        $processosRecentes = (clone $baseQuery)
            ->with(['tipoDocumento'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Dados para gráfico de processos por mês (apenas do usuário)
        $processosPorMes = [];
        
        if ($user->isAdmin()) {
            // Admin vê todos
            $processosPorMes = Documento::select(
                    DB::raw('MONTH(created_at) as mes'),
                    DB::raw('COUNT(*) as total')
                )
                ->whereYear('created_at', date('Y'))
                ->groupBy('mes')
                ->orderBy('mes')
                ->get()
                ->pluck('total', 'mes')
                ->toArray();
        } else {
            // Usuários comuns vêem apenas seus processos
            $processosPorMes = (clone $baseQuery)
                ->select(DB::raw('MONTH(created_at) as mes'), DB::raw('COUNT(*) as total'))
                ->whereYear('created_at', date('Y'))
                ->groupBy('mes')
                ->orderBy('mes')
                ->get()
                ->pluck('total', 'mes')
                ->toArray();
        }
        
        $meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        $processosPorMesArray = [];
        for ($i = 1; $i <= 12; $i++) {
            $processosPorMesArray[] = $processosPorMes[$i] ?? 0;
        }
        
        return view('dashboard', compact(
            'kpis', 
            'processosRecentes',
            'meses',
            'processosPorMesArray'
        ));
    }
}