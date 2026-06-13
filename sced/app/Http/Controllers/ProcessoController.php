<?php
// app/Http/Controllers/ProcessoController.php
// VERSÃO CORRIGIDA COM VALIDAÇÃO DE ANEXOS

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\ArquivoAnexo;
use App\Models\TipoDocumento;
use App\Models\DocumentoTipo;
use App\Services\ProcessoService;
use App\Exceptions\StatusTransitionException;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

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

    /**
     * Store a newly created process in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Documento::class);

        try {
            // Validação básica
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
                // 🔧 CORREÇÃO: Aceita qualquer string (IDs dos documentos)
                'tipos_anexo.*'           => 'nullable|string',
            ]);

            Log::info('Iniciando criação do processo', $data);

            // 🔧 MAPEAMENTO: Converte ID do documento para o tipo_anexo correto
            $tiposAnexoMap = [];
            if ($request->has('tipos_anexo')) {
                foreach ($request->tipos_anexo as $index => $value) {
                    if (!empty($value)) {
                        // Tenta encontrar o documento pelo ID
                        $documento = DocumentoTipo::find($value);
                        if ($documento) {
                            // Mapeia nome do documento para o tipo_anexo
                            $mapa = [
                                'RG' => 'rg',
                                'CPF' => 'cpf',
                                'CNH' => 'cnh',
                                'Contrato' => 'contrato',
                                'Comprovante de Residência' => 'comprovante_residencia',
                                'Comprovante de Renda' => 'comprovante_renda',
                                'Certidão' => 'certidao',
                                'Laudo' => 'laudo',
                            ];
                            $tiposAnexoMap[$index] = $mapa[$documento->nome] ?? 'outros';
                            Log::info("Mapeado documento '{$documento->nome}' para tipo '{$tiposAnexoMap[$index]}'");
                        } else {
                            $tiposAnexoMap[$index] = 'outros';
                        }
                    } else {
                        $tiposAnexoMap[$index] = 'outros';
                    }
                }
            }

            // Cria o processo
            $documento = Documento::create([
                'numero_protocolo'        => Documento::gerarProtocolo(),
                'tipo_documento_id'       => $data['tipo_documento_id'],
                'usuario_registro_id'     => auth()->id(),
                'remetente'               => $data['remetente'],
                'assunto'                 => null,
                'descricao'               => $data['descricao'] ?? null,
                'setor_destino'           => $data['setor_destino'],
                'departamento_destino_id' => $data['departamento_destino_id'] ?? null,
                'status'                  => 'novo',
                'data_recebimento'        => $data['data_recebimento'],
            ]);

            Log::info('Processo criado', ['id' => $documento->id, 'protocolo' => $documento->numero_protocolo]);

            // Registra histórico
            \App\Models\HistoricoMovimentacao::create([
                'documento_id' => $documento->id,
                'usuario_id'   => auth()->id(),
                'tipo'         => 'criacao',
                'status_novo'  => 'novo',
                'observacoes'  => 'Processo aberto no sistema.',
            ]);

            // Salva os anexos
            if ($request->hasFile('anexos')) {
                foreach ($request->file('anexos') as $i => $file) {
                    $caminho = $file->store('anexos', 'public');
                    $tipoAnexo = $tiposAnexoMap[$i] ?? 'outros';
                    
                    \App\Models\ArquivoAnexo::create([
                        'documento_id'    => $documento->id,
                        'usuario_id'      => auth()->id(),
                        'tipo_anexo'      => $tipoAnexo,
                        'status_validacao'=> 'pendente',
                        'nome_arquivo'    => $file->getClientOriginalName(),
                        'caminho_arquivo' => $caminho,
                        'tipo_mime'       => $file->getMimeType(),
                        'tamanho_bytes'   => $file->getSize(),
                    ]);
                    
                    Log::info("Anexo salvo: {$file->getClientOriginalName()} como tipo '{$tipoAnexo}'");
                }
            }

            // Registra log de auditoria
            \App\Models\LogAuditoria::registrar('ABRIR_PROCESSO', 'documentos', $documento->id, [
                'modulo'           => 'processos',
                'status_novo'      => 'novo',
                'descricao_legivel'=> "Processo {$documento->numero_protocolo} criado por ".auth()->user()->nome.'.',
            ]);

            Log::info('Processo finalizado com sucesso', ['protocolo' => $documento->numero_protocolo]);

            return redirect()->route('documentos.show', $documento)
                ->with('success', 'Processo aberto! Protocolo: '.$documento->numero_protocolo);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erro de validação: ' . json_encode($e->errors()));
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            Log::error('Erro ao criar processo: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Erro ao criar processo: ' . $e->getMessage())->withInput();
        }
    }

    // ── JSON para Autocomplete (CORRIGIDO) ───────────────────

    public function tiposJson(Request $request): JsonResponse
    {
        $search = $request->input('q');

        $tipos = TipoDocumento::where('status', 'ativo')
            ->when($search, function ($query, $search) {
                return $query->where('nome', 'like', '%' . $search . '%');
            })
            ->with('departamentoDestino:id,nome')
            ->select(['id', 'nome', 'descricao', 'departamento_destino_id', 'cargos_responsaveis'])
            ->orderBy('nome')
            ->limit(10)
            ->get();

        return response()->json($tipos->map(fn($s) => [
            'id'                    => $s->id,
            'nome'                  => $s->nome,
            'descricao'             => $s->descricao,
            'setor_nome'            => $s->departamentoDestino?->nome ?? '',
            'setor_id'              => $s->departamento_destino_id,
            'cargos_responsaveis'   => $s->cargos_responsaveis ?? [],
        ]));
    }

    public function requisitosJson(int $id): JsonResponse
    {
        $servico = TipoDocumento::with([
            'departamentoDestino:id,nome',
            'documentosTipo',
        ])->findOrFail($id);

        $documentosVinculados = $servico->documentosTipo->map(fn($doc) => [
            'id'          => $doc->id,
            'nome'        => $doc->nome,
            'descricao'   => $doc->descricao,
            'tipo'        => $doc->tipo,
            'obrigatorio' => $doc->tipo === 'obrigatorio',
        ])->values();

        return response()->json([
            'servico' => [
                'id'                    => $servico->id,
                'nome'                  => $servico->nome,
                'descricao'             => $servico->descricao,
                'setor'                 => $servico->departamentoDestino?->nome,
                'setor_id'              => $servico->departamento_destino_id,
                'cargos_responsaveis'   => $servico->cargos_responsaveis ?? [],
            ],
            'documentos_obrigatorios' => $documentosVinculados->filter(fn($doc) => $doc['obrigatorio'])->values(),
            'documentos_opcionais'    => $documentosVinculados->filter(fn($doc) => !$doc['obrigatorio'])->values(),
            'todos_documentos'        => $documentosVinculados,
        ]);
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
            'remetente'    => 'required|string|max:255',
            'descricao'    => 'nullable|string|max:2000',
            'setor_destino'=> 'required|string|max:255',
        ]);

        try {
            $this->service->editarDados($documento, auth()->user(), $data);
            return back()->with('success', 'Dados do processo atualizados com sucesso.');
        } catch (StatusTransitionException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // ── Transições de Status ─────────────────────────────────

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

    public function retornar(Request $request, Documento $documento): RedirectResponse
    {
        Gate::authorize('retornar', $documento);
        $request->validate([
            'observacoes'  => 'nullable|string|max:1000',
            'anexos'       => 'nullable|array',
            'anexos.*'     => 'file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
            'tipos_anexo'  => 'nullable|array',
            'tipos_anexo.*'=> 'nullable|string',
        ]);
        try {
            $this->service->retornar(
                $documento, auth()->user(),
                $request->observacoes,
                $request->file('anexos', []),
                $request->input('tipos_anexo', [])
            );
            return back()->with('success', 'Processo reenviado ao analista.');
        } catch (StatusTransitionException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

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

    public function statusManual(Request $request, Documento $documento): RedirectResponse
    {
        Gate::authorize('alterarStatusManual', $documento);
        $request->validate([
            'status'      => 'required|in:novo,em_analise,pendente,finalizado,desativado',
            'observacoes' => 'nullable|string|max:1000',
        ]);
        try {
            $this->service->alterarStatusManual(
                $documento, auth()->user(),
                $request->status, $request->observacoes
            );
            return back()->with('success', 'Status alterado manualmente.');
        } catch (StatusTransitionException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function substituirAnexo(Request $request, Documento $documento, ArquivoAnexo $anexo): RedirectResponse
    {
        Gate::authorize('substituirAnexo', $documento);
        $request->validate([
            'arquivo'    => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
            'tipo_anexo' => 'nullable|string|in:rg,cpf,contrato,comprovante_residencia,comprovante_renda,certidao,laudo,outros',
        ]);
        try {
            $this->service->substituirAnexo(
                $documento, auth()->user(), $anexo,
                $request->file('arquivo'),
                $request->input('tipo_anexo', 'outros')
            );
            return back()->with('success', 'Arquivo substituído com sucesso.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao substituir arquivo: '.$e->getMessage());
        }
    }

    public function validarAnexo(Request $request, Documento $documento, ArquivoAnexo $anexo): RedirectResponse
    {
        Gate::authorize('validarAnexo', $documento);
        $request->validate([
            'status_validacao' => 'required|in:aprovado,rejeitado',
            'observacao'       => 'nullable|string|max:500',
        ]);
        try {
            $this->service->validarAnexo(
                $documento, auth()->user(), $anexo,
                $request->status_validacao, $request->observacao
            );
            return back()->with('success', 'Validação do anexo registrada.');
        } catch (StatusTransitionException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}