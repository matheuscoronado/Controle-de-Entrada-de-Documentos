<?php
// app/Http/Controllers/DocumentoController.php — PARTE 2
// Principais mudanças:
//   - store() sem 'assunto', aceita tipos_anexo[] por arquivo
//   - setor_destino preenchido automaticamente via serviço selecionado
//   - data_recebimento fixada em today() no backend
//   - views renomeadas para processos.*

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\TipoDocumento;
use App\Models\HistoricoMovimentacao;
use App\Models\ArquivoAnexo;
use App\Models\LogAuditoria;
use Illuminate\Http\Request;

class DocumentoController extends Controller
{
    public function index(Request $request)
    {
        $query = Documento::with(['tipoDocumento', 'usuarioRegistro']);

        if ($request->protocolo)         $query->where('numero_protocolo', 'like', '%'.$request->protocolo.'%');
        if ($request->remetente)         $query->where('remetente', 'like', '%'.$request->remetente.'%');
        if ($request->tipo_documento_id) $query->where('tipo_documento_id', $request->tipo_documento_id);
        if ($request->status)            $query->where('status', $request->status);
        if ($request->data_inicio && $request->data_fim)
            $query->whereBetween('data_recebimento', [$request->data_inicio, $request->data_fim]);

        $documentos = $query->orderBy('created_at', 'desc')->paginate(15);
        $tipos      = TipoDocumento::where('status', 'ativo')->get();

        return view('processos.index', compact('documentos', 'tipos'));
    }

    public function create()
    {
        // Tipos não são mais carregados aqui — o autocomplete usa a API
        return view('processos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_documento_id'       => 'required|exists:tipo_documentos,id',
            'remetente'               => 'required|string|max:255',
            'descricao'               => 'nullable|string|max:2000',
            'departamento_destino_id' => 'nullable|exists:departamentos,id',
            'setor_destino'           => 'required|string|max:255',
            // Data bloqueada no frontend; validamos no backend também
            'data_recebimento'        => 'required|date|before_or_equal:today',
            'anexos'                  => 'nullable|array',
            'anexos.*'                => 'file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
            'tipos_anexo'             => 'nullable|array',
            'tipos_anexo.*'           => 'nullable|string|in:rg,cpf,contrato,comprovante_residencia,comprovante_renda,certidao,laudo,outros',
        ], [
            'tipo_documento_id.required' => 'Selecione o serviço.',
            'remetente.required'         => 'O campo solicitante é obrigatório.',
            'setor_destino.required'     => 'O setor de destino é obrigatório.',
        ]);

        $documento = Documento::create([
            'numero_protocolo'        => Documento::gerarProtocolo(),
            'tipo_documento_id'       => $request->tipo_documento_id,
            'usuario_registro_id'     => auth()->id(),
            'remetente'               => $request->remetente,
            'assunto'                 => null, // removido no novo fluxo
            'descricao'               => $request->descricao,
            'setor_destino'           => $request->setor_destino,
            'departamento_destino_id' => $request->departamento_destino_id,
            'status'                  => 'recebido',
            'data_recebimento'        => today(),
        ]);

        HistoricoMovimentacao::create([
            'documento_id'    => $documento->id,
            'usuario_id'      => auth()->id(),
            'status_anterior' => null,
            'status_novo'     => 'recebido',
            'observacoes'     => 'Processo aberto no sistema.',
        ]);

        // ── Processa uploads com tipo_anexo por índice ───────
        $uploadados = [];
        if ($request->hasFile('anexos')) {
            foreach ($request->file('anexos') as $index => $file) {
                $caminho   = $file->store('anexos', 'public');
                $tipoAnexo = $request->input("tipos_anexo.{$index}", 'outros');

                ArquivoAnexo::create([
                    'documento_id'    => $documento->id,
                    'usuario_id'      => auth()->id(),
                    'tipo_anexo'      => $tipoAnexo,
                    'status_validacao'=> 'pendente',
                    'nome_arquivo'    => $file->getClientOriginalName(),
                    'caminho_arquivo' => $caminho,
                    'tipo_mime'       => $file->getMimeType(),
                    'tamanho_bytes'   => $file->getSize(),
                ]);

                $uploadados[] = $file->getClientOriginalName();
            }
        }

        LogAuditoria::registrar('ABRIR_PROCESSO', 'documentos', $documento->id, [
            'modulo'             => 'processos',
            'status_novo'        => 'recebido',
            'uploads_realizados' => $uploadados,
            'descricao_legivel'  => "Processo {$documento->numero_protocolo} aberto por ".auth()->user()->nome.'.',
        ]);

        return redirect()->route('documentos.show', $documento)
            ->with('success', 'Processo aberto com sucesso! Protocolo: '.$documento->numero_protocolo);
    }

    public function show(Documento $documento)
    {
        $documento->load([
            'tipoDocumento',
            'usuarioRegistro',
            'historicos.usuario',
            'anexos.usuario',
            'anexos.validadoPor',
        ]);

        return view('processos.show', compact('documento'));
    }

    public function atualizarStatus(Request $request, Documento $documento)
    {
        $request->validate([
            'status'      => 'required|in:recebido,em_analise,encaminhado,finalizado',
            'observacoes' => 'nullable|string',
        ]);

        if ($documento->status === 'finalizado' && !auth()->user()->isAdmin()) {
            return back()->with('error', 'Apenas administradores podem alterar processos finalizados.');
        }

        $anterior = $documento->status;

        HistoricoMovimentacao::create([
            'documento_id'    => $documento->id,
            'usuario_id'      => auth()->id(),
            'status_anterior' => $anterior,
            'status_novo'     => $request->status,
            'observacoes'     => $request->observacoes,
        ]);

        $documento->update(['status' => $request->status]);

        LogAuditoria::registrar('ATUALIZAR_STATUS', 'documentos', $documento->id, [
            'modulo'           => 'processos',
            'status_anterior'  => $anterior,
            'status_novo'      => $request->status,
            'descricao_legivel'=> "Status do processo {$documento->numero_protocolo}: {$anterior} → {$request->status}.",
            'campos_alterados' => ['status' => ['de' => $anterior, 'para' => $request->status]],
        ]);

        return back()->with('success', 'Status atualizado com sucesso!');
    }
}
