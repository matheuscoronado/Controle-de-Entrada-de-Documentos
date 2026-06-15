<?php
// app/Http/Controllers/ProcessoController.php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\ArquivoAnexo;
use App\Models\TipoDocumento;
use App\Models\DocumentoTipo;
use App\Models\User;
use App\Models\HistoricoMovimentacao;
use App\Models\LogAuditoria;
use App\Services\ProcessoService;
use App\Exceptions\StatusTransitionException;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessoController extends Controller
{
    public function __construct(private ProcessoService $service) {}

    // ── Listagem com Abas ─────────────────────────────────────

    public function index(Request $request)
    {
        Gate::authorize('viewAny', Documento::class);

        $user = auth()->user();
        $tab = $request->input('tab', 'meus');
        $subtab = $request->input('subtab', 'abri');

        $baseQuery = Documento::with(['tipoDocumento', 'usuarioRegistro', 'atribuidoA'])
            ->withCount('anexos');

        if ($request->protocolo)
            $baseQuery->where('numero_protocolo', 'like', '%' . $request->protocolo . '%');
        if ($request->remetente)
            $baseQuery->where('remetente', 'like', '%' . $request->remetente . '%');
        if ($request->tipo_documento_id)
            $baseQuery->where('tipo_documento_id', $request->tipo_documento_id);
        if ($request->status)
            $baseQuery->where('status', $request->status);
        if ($request->data_inicio && $request->data_fim)
            $baseQuery->whereBetween('created_at', [$request->data_inicio, $request->data_fim]);

        $meusQuery = clone $baseQuery;
        $meusQuery->where(function ($q) use ($user) {
            $q->where('usuario_registro_id', $user->id)
                ->orWhere('atribuido_a_id', $user->id);
        });

        $meusProcessosTotal = $meusQuery->count();
        $meusProcessosAbertosCount = (clone $meusQuery)->where('usuario_registro_id', $user->id)->count();
        $meusProcessosAtribuidosCount = (clone $meusQuery)->where('atribuido_a_id', $user->id)->count();
        $processosAguardandoAcaoCount = (clone $meusQuery)
            ->where('usuario_registro_id', $user->id)
            ->where('status', 'pendente')
            ->count();
        $meusProcessosAtribuidosPendentes = (clone $meusQuery)
            ->where('atribuido_a_id', $user->id)
            ->whereIn('status', ['novo', 'em_analise'])
            ->count();
        $acoesPendentesCount = $processosAguardandoAcaoCount + $meusProcessosAtribuidosPendentes;

        if ($subtab == 'abri') {
            $meusQuery->where('usuario_registro_id', $user->id);
        } elseif ($subtab == 'atribuidos') {
            $meusQuery->where('atribuido_a_id', $user->id);
        }

        $processos = $meusQuery->orderBy('created_at', 'desc')->paginate(15);
        $processos->appends(['tab' => 'meus', 'subtab' => $subtab] + $request->all());

        $setorQuery = clone $baseQuery;
        $setorQuery->where('departamento_destino_id', $user->departamento_id);
        $setorProcessosTotal = $setorQuery->count();
        $setorProcessosNovos = (clone $setorQuery)
            ->where('status', 'novo')
            ->whereNull('atribuido_a_id')
            ->count();
        $setorProcessos = $setorQuery->orderBy('created_at', 'desc')->paginate(15);
        $setorProcessos->appends(['tab' => 'setor'] + $request->all());

        $tipos = TipoDocumento::where('status', 'ativo')->get();
        $processosLista = $tab == 'meus' ? $processos : $setorProcessos;

        return view('processos.index', compact(
            'processosLista',
            'processos',
            'setorProcessos',
            'meusProcessosTotal',
            'meusProcessosAbertosCount',
            'meusProcessosAtribuidosCount',
            'setorProcessosTotal',
            'setorProcessosNovos',
            'tipos',
            'processosAguardandoAcaoCount',
            'meusProcessosAtribuidosPendentes',
            'acoesPendentesCount',
            'tab',
            'subtab'
        ));
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

        try {
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
                'tipos_anexo.*'           => 'nullable|string',
            ]);

            Log::info('Iniciando criação do processo', $data);

            $tiposAnexoMap = [];
            if ($request->has('tipos_anexo')) {
                foreach ($request->tipos_anexo as $index => $value) {
                    if (!empty($value)) {
                        $documento = DocumentoTipo::find($value);
                        if ($documento) {
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
                        } else {
                            $tiposAnexoMap[$index] = 'outros';
                        }
                    } else {
                        $tiposAnexoMap[$index] = 'outros';
                    }
                }
            }

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

            HistoricoMovimentacao::create([
                'documento_id' => $documento->id,
                'usuario_id'   => auth()->id(),
                'tipo'         => 'criacao',
                'status_novo'  => 'novo',
                'observacoes'  => 'Processo aberto no sistema.',
            ]);

            if ($request->hasFile('anexos')) {
                foreach ($request->file('anexos') as $i => $file) {
                    $caminho = $file->store('anexos', 'public');
                    ArquivoAnexo::create([
                        'documento_id'    => $documento->id,
                        'usuario_id'      => auth()->id(),
                        'tipo_anexo'      => $tiposAnexoMap[$i] ?? 'outros',
                        'status_validacao' => 'pendente',
                        'nome_arquivo'    => $file->getClientOriginalName(),
                        'caminho_arquivo' => $caminho,
                        'tipo_mime'       => $file->getMimeType(),
                        'tamanho_bytes'   => $file->getSize(),
                    ]);
                }
            }

            try {
                LogAuditoria::registrar('ABRIR_PROCESSO', 'documentos', $documento->id, [
                    'modulo'           => 'processos',
                    'status_novo'      => 'novo',
                    'descricao_legivel' => "Processo {$documento->numero_protocolo} criado por " . auth()->user()->nome . '.',
                ]);
            } catch (\Exception $e) {
                Log::warning('Não foi possível registrar log de auditoria: ' . $e->getMessage());
            }

            Log::info('Processo finalizado com sucesso', ['protocolo' => $documento->numero_protocolo]);

            return redirect()->route('documentos.show', $documento)
                ->with('success', 'Processo aberto com sucesso! Protocolo: ' . $documento->numero_protocolo);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erro de validação: ' . json_encode($e->errors()));
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Erro ao criar processo: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Ocorreu um erro inesperado ao criar o processo. Tente novamente.')->withInput();
        }
    }

    // ── JSON para Autocomplete ─────────────────────────────────

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

    // ⭐ MÉTODO PARA BUSCAR TODOS OS DOCUMENTOS CADASTRADOS (para o select)
    public function getDocumentosCadastrados(): JsonResponse
    {
        $documentos = DocumentoTipo::where('status', 'ativo')
            ->orderBy('nome')
            ->get(['id', 'nome', 'tipo', 'descricao']);
        
        return response()->json($documentos);
    }

    // ── Detalhe ──────────────────────────────────────────────

    public function show(Documento $documento)
    {
        Gate::authorize('view', $documento);

        $documento->load([
            'tipoDocumento',
            'usuarioRegistro',
            'atribuidoA',
            'historicos.usuario',
            'historicos.usuarioDestino',
            'anexos.usuario',
            'anexos.validadoPor',
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
            'setor_destino' => 'required|string|max:255',
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
            return back()->with('success', 'Processo assumido com sucesso! Status: Em Análise.');
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
            'tipos_anexo.*' => 'nullable|string',
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

    // ── ATRIBUIR PROCESSO ───────────────────────────

    public function atribuir(Request $request, Documento $documento): RedirectResponse
    {
        Gate::authorize('atribuir', $documento);

        $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'observacoes' => 'nullable|string|max:500',
        ]);

        $usuarioDestino = User::findOrFail($request->usuario_id);

        if ($usuarioDestino->departamento_id != $documento->departamento_destino_id) {
            return back()->with('error', 'O usuário selecionado não pertence ao setor deste processo.');
        }

        $user = auth()->user();
        if ($user->cargo == 'N3') {
            if (!in_array($usuarioDestino->cargo, ['N2', 'N1'])) {
                return back()->with('error', 'N3 pode atribuir processos apenas para usuários N2 ou N1 do mesmo setor.');
            }
        } elseif ($user->cargo == 'N2') {
            if ($usuarioDestino->cargo != 'N1') {
                return back()->with('error', 'N2 só pode atribuir processos para usuários N1 do mesmo setor.');
            }
        } elseif (!$user->isAdmin()) {
            return back()->with('error', 'Você não tem permissão para atribuir processos.');
        }

        DB::transaction(function () use ($documento, $usuarioDestino, $request) {
            $statusAnterior = $documento->status;

            $documento->update([
                'atribuido_a_id' => $usuarioDestino->id,
                'atribuido_em' => now(),
                'status' => 'em_analise',
            ]);

            HistoricoMovimentacao::create([
                'documento_id' => $documento->id,
                'usuario_id' => auth()->id(),
                'usuario_destino_id' => $usuarioDestino->id,
                'tipo' => 'atribuicao',
                'status_anterior' => $statusAnterior,
                'status_novo' => 'em_analise',
                'observacoes' => $request->observacoes ?? "Processo atribuído para {$usuarioDestino->nome}",
            ]);

            try {
                LogAuditoria::registrar('ATRIBUIR_PROCESSO', 'documentos', $documento->id, [
                    'modulo' => 'processos',
                    'status_anterior' => $statusAnterior,
                    'status_novo' => 'em_analise',
                    'campos_alterados' => [
                        'atribuido_a_id' => ['de' => $documento->atribuido_a_id, 'para' => $usuarioDestino->id],
                        'status' => ['de' => $statusAnterior, 'para' => 'em_analise'],
                    ],
                    'descricao_legivel' => "Processo atribuído de " . auth()->user()->nome . " para {$usuarioDestino->nome}",
                ]);
            } catch (\Exception $e) {
                Log::warning('Não foi possível registrar log de auditoria: ' . $e->getMessage());
            }
        });

        return back()->with('success', "Processo atribuído para {$usuarioDestino->nome} com sucesso!");
    }

    // API para buscar usuários disponíveis para atribuição
    public function getUsuariosParaAtribuir($processoId): JsonResponse
    {
        try {
            $processo = Documento::findOrFail($processoId);
            $user = auth()->user();

            if (!in_array($user->cargo, ['N3', 'N2', 'administrador', 'admin'])) {
                return response()->json(['error' => 'Sem permissão'], 403);
            }

            $query = User::where('status', 'ativo')
                ->where('id', '!=', $user->id);

            if ($processo->departamento_destino_id) {
                $query->where('departamento_id', $processo->departamento_destino_id);
            }

            if ($user->cargo == 'N3' || $user->cargo == 'administrador' || $user->cargo == 'admin') {
                $query->whereIn('cargo', ['N2', 'N1']);
            } elseif ($user->cargo == 'N2') {
                $query->where('cargo', 'N1');
            } else {
                return response()->json(['error' => 'Cargo não permitido'], 403);
            }

            $usuarios = $query->get(['id', 'nome', 'cargo', 'departamento_id']);

            foreach ($usuarios as $usuario) {
                $departamento = \App\Models\Departamento::find($usuario->departamento_id);
                $usuario->departamento_nome = $departamento ? $departamento->nome : 'Setor não definido';
            }

            return response()->json($usuarios);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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
                $documento,
                auth()->user(),
                $anexo,
                $request->file('arquivo'),
                $request->input('tipo_anexo', 'outros')
            );
            return back()->with('success', 'Arquivo substituído com sucesso.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao substituir arquivo: ' . $e->getMessage());
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