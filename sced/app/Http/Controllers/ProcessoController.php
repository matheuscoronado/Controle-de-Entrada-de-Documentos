<?php
// app/Http/Controllers/ProcessoController.php — PARTE 3
// ============================================================
// Controller slim: delega TODA lógica de negócio ao
// ProcessoService e autorização à ProcessoPolicy.
// Mantém o nome de rota 'documentos.*' para compatibilidade.
// ============================================================

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\ArquivoAnexo;
use App\Models\TipoDocumento;
use App\Services\ProcessoService;
use App\Exceptions\StatusTransitionException;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class ProcessoController extends Controller
{
    public function __construct(private ProcessoService $service) {}

    // ── Listagem ─────────────────────────────────────────────

    public function index(Request $request)
    {
        Gate::authorize('viewAny', Documento::class);

        $query = Documento::with(['tipoDocumento', 'usuarioRegistro', 'atribuidoA'])
            ->withCount('anexos');

        if ($request->protocolo)
            $query->where('numero_protocolo', 'like', '%'.$request->protocolo.'%');
        if ($request->remetente)
            $query->where('remetente', 'like', '%'.$request->remetente.'%');
        if ($request->tipo_documento_id)
            $query->where('tipo_documento_id', $request->tipo_documento_id);
        if ($request->status)
            $query->where('status', $request->status);
        if ($request->data_inicio && $request->data_fim)
            $query->whereBetween('data_recebimento', [$request->data_inicio, $request->data_fim]);

        // Operadores só vêem seus próprios processos (criados ou atribuídos a eles)
        // Ajustado para usar isAdmin() com segurança evitando falhas de métodos inexistentes
        $user = auth()->user();
        if (!(method_exists($user, 'isAdmin') && $user->isAdmin())) {
            $query->where(function ($q) {
                $q->where('usuario_registro_id', auth()->id())
                  ->orWhere('atribuido_a_id', auth()->id());
            });
        }

        $documentos = $query->orderBy('created_at', 'desc')->paginate(15);
        $tipos      = TipoDocumento::where('status', 'ativo')->get();

        return view('processos.index', compact('documentos', 'tipos'));
    }

    // ── Criar ────────────────────────────────────────────────

    public function create()
    {
        Gate::authorize('create', Documento::class);
        return view('processos.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Documento::class);

        $data = $request->validate([
            'tipo_documento_id'       => 'required|exists:tipo_documentos,id',
            'remetente'               => 'required|string|max:255',
            'descricao'               => 'nullable|string|max:2000',
            'departamento_destino_id' => 'nullable|exists:departamentos,id',
            'setor_destino'           => 'required|string|max:255',
            'data_recebimento'        => 'required|date|before_or_equal:today',
            'anexos'                  => 'nullable|array',
            'anexos.*'                => 'file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
            'tipos_anexo'             => 'nullable|array',
            'tipos_anexo.*'           => 'nullable|string|in:rg,cpf,contrato,comprovante_residencia,comprovante_renda,certidao,laudo,outros',
        ]);

        // Cria o processo pelo service (status inicial: 'novo')
        $documento = \App\Models\Documento::create([
            'numero_protocolo'        => \App\Models\Documento::gerarProtocolo(),
            'tipo_documento_id'       => $data['tipo_documento_id'],
            'usuario_registro_id'     => auth()->id(),
            'remetente'               => $data['remetente'],
            'assunto'                 => null,
            'descricao'               => $data['descricao'] ?? null,
            'setor_destino'           => $data['setor_destino'],
            'departamento_destino_id' => $data['departamento_destino_id'] ?? null,
            'status'                  => 'novo',
            'data_recebimento'        => today(),
        ]);

        \App\Models\HistoricoMovimentacao::create([
            'documento_id' => $documento->id,
            'usuario_id'   => auth()->id(),
            'tipo'         => 'criacao',
            'status_novo'  => 'novo',
            'observacoes'  => 'Processo aberto no sistema.',
        ]);

        // Processa uploads iniciais
        if ($request->hasFile('anexos')) {
            foreach ($request->file('anexos') as $i => $file) {
                $caminho = $file->store('anexos', 'public');
                \App\Models\ArquivoAnexo::create([
                    'documento_id'    => $documento->id,
                    'usuario_id'      => auth()->id(),
                    'tipo_anexo'      => $request->input("tipos_anexo.{$i}", 'outros'),
                    'status_validacao'=> 'pendente',
                    'nome_arquivo'    => $file->getClientOriginalName(),
                    'caminho_arquivo' => $caminho,
                    'tipo_mime'       => $file->getMimeType(),
                    'tamanho_bytes'   => $file->getSize(),
                ]);
            }
        }

        \App\Models\LogAuditoria::registrar('ABRIR_PROCESSO', 'documentos', $documento->id, [
            'modulo'           => 'processos',
            'status_novo'      => 'novo',
            'descricao_legivel'=> "Processo {$documento->numero_protocolo} criado por ".auth()->user()->nome.'.',
        ]);

        return redirect()->route('documentos.show', $documento)
            ->with('success', 'Processo aberto! Protocolo: '.$documento->numero_protocolo);
    }

    // ── API Autocomplete JSON para os Serviços ────────────────

    /**
     * Retorna os tipos de documento ativos em formato JSON para a busca assíncrona.
     */
    public function tiposJson(Request $request): JsonResponse
    {
        $search = $request->input('q');

        $tipos = TipoDocumento::where('status', 'ativo')
            ->when($search, function ($query, $search) {
                return $query->where('nome', 'like', '%' . $search . '%');
            })
            ->get(['id', 'nome', 'setor_destino']);

        return response()->json($tipos);
    }

    // ── Detalhe ──────────────────────────────────────────────

    public function show(Documento $documento)
    {
        Gate::authorize('view', $documento);

        $documento->load([
            'tipoDocumento', 'usuarioRegistro', 'atribuidoA',
            'historicos.usuario', 'historicos.usuarioDestino',
            'anexos.usuario', 'anexos.validadoPor',
        ]);

        $acoes = $this->service->acoesDisponiveis($documento, auth()->user());

        return view('processos.show', compact('documento', 'acoes'));
    }

    // ── Editar dados ─────────────────────────────────────────

    public function edit(Documento $documento)
    {
        Gate::authorize('update', $documento);
        return view('processos.edit', compact('documento'));
    }

    public function update(Request $request, Documento $documento): RedirectResponse
    {
        Gate::authorize('update', $documento);

        $data = $request->validate([
            'remetente'  => 'required|string|max:255',
            'descricao'  => 'nullable|string|max:2000',
            'setor_destino' => 'required|string|max:255',
        ]);

        try {
            $this->service->editarDados($documento, auth()->user(), $data);
            return back()->with('success', 'Dados do processo updated.');
        } catch (StatusTransitionException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // ═══════════════════════════════════════════════════════
    // AÇÕES DE STATUS
    // ═══════════════════════════════════════════════════════

    /** POST /documentos/{doc}/assumir */
    public function assumir(Request $request, Documento $documento): RedirectResponse
    {
        Gate::authorize('assumir', $documento);
        $request->validate(['observacoes' => 'nullable|string|max:500']);

        try {
            $this->service->assumir($documento, auth()->user(), $request->observacoes);
            return back()->with('success', 'Processo assumido! Status: Em Análise.');
        } catch (StatusTransitionException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /** POST /documentos/{doc}/devolver */
    public function devolver(Request $request, Documento $documento): RedirectResponse
    {
        Gate::authorize('devolver', $documento);
        $request->validate(['motivo' => 'required|string|max:1000']);

        try {
            $this->service->devolver($documento, auth()->user(), $request->motivo);
            return back()->with('success', 'Processo devolvido ao solicitante.');
        } catch (StatusTransitionException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /** POST /documentos/{doc}/retornar */
    public function retornar(Request $request, Documento $documento): RedirectResponse
    {
        Gate::authorize('retornar', $documento);
        $request->validate([
            'observacoes' => 'nullable|string|max:1000',
            'anexos'      => 'nullable|array',
            'anexos.*'    => 'file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
            'tipos_anexo' => 'nullable|array',
            'tipos_anexo.*'=> 'nullable|string',
        ]);

        try {
            $this->service->retornar(
                $documento,
                auth()->user(),
                $request->observacoes,
                $request->file('anexos', []),
                $request->input('tipos_anexo', [])
            );
            return back()->with('success', 'Processo reenviado ao analista.');
        } catch (StatusTransitionException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /** POST /documentos/{doc}/finalizar */
    public function finalizar(Request $request, Documento $documento): RedirectResponse
    {
        Gate::authorize('finalizar', $documento);
        $request->validate(['observacoes' => 'nullable|string|max:1000']);

        try {
            $this->service->finalizar($documento, auth()->user(), $request->observacoes);
            return back()->with('success', 'Processo finalizado com sucesso.');
        } catch (StatusTransitionException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /** POST /documentos/{doc}/desativar — admin/N3 */
    public function desativar(Request $request, Documento $documento): RedirectResponse
    {
        Gate::authorize('desativar', $documento);
        $request->validate(['motivo' => 'required|string|max:1000']);

        try {
            $this->service->desativar($documento, auth()->user(), $request->motivo);
            return back()->with('success', 'Processo desativado.');
        } catch (StatusTransitionException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /** POST /documentos/{doc}/reabrir — admin/N3 */
    public function reabrir(Request $request, Documento $documento): RedirectResponse
    {
        Gate::authorize('reabrir', $documento);
        $request->validate(['observacoes' => 'nullable|string|max:500']);

        try {
            $this->service->reabrir($documento, auth()->user(), $request->observacoes);
            return back()->with('success', 'Processo reaberto.');
        } catch (StatusTransitionException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /** PATCH /documentos/{doc}/status-manual — admin/N3 */
    public function statusManual(Request $request, Documento $documento): RedirectResponse
    {
        Gate::authorize('alterarStatusManual', $documento);
        $request->validate([
            'status'      => 'required|in:novo,em_analise,pendente,finalizado,desativado',
            'observacoes' => 'nullable|string|max:1000',
        ]);

        try {
            $this->service->alterarStatusManual(
                $documento,
                auth()->user(),
                $request->status,
                $request->observacoes
            );
            return back()->with('success', 'Status alterado manualmente.');
        } catch (StatusTransitionException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // ── Substituição de Anexo ────────────────────────────────

    /** POST /documentos/{doc}/anexos/{anexo}/substituir */
    public function substituirAnexo(Request $request, Documento $documento, ArquivoAnexo $anexo): RedirectResponse
    {
        Gate::authorize('substituirAnexo', $documento);
        $request->validate([
            'arquivo'     => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
            'tipo_anexo'  => 'nullable|string|in:rg,cpf,contrato,comprovante_residencia,comprovante_renda,certidao,laudo,outros',
        ]);

        try {
            $this->service->substituirAnexo(
                $documento,
                auth()->user(),
                $anexo,
                $request->file('arquivo'),
                $request->input('tipo_anexo', 'outros')
            );
            return back()->with('success', 'Arquivo substituído com sucesso.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao substituir arquivo: '.$e->getMessage());
        }
    }

    /** POST /documentos/{doc}/anexos/{anexo}/validar — admin/N3 */
    public function validarAnexo(Request $request, Documento $documento, ArquivoAnexo $anexo): RedirectResponse
    {
        Gate::authorize('validarAnexo', $documento);
        $request->validate([
            'status_validacao' => 'required|in:aprovado,rejeitado',
            'observacao'       => 'nullable|string|max:500',
        ]);

        try {
            $this->service->validarAnexo(
                $documento,
                auth()->user(),
                $anexo,
                $request->status_validacao,
                $request->observacao
            );
            return back()->with('success', 'Validação do anexo registrada.');
        } catch (StatusTransitionException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}