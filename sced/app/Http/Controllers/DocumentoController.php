<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\TipoDocumento;
use App\Models\HistoricoMovimentacao;
use App\Models\ArquivoAnexo;
use Illuminate\Http\Request;

class DocumentoController extends Controller
{
    public function index(Request $request)
    {
        $query = Documento::with(['tipoDocumento', 'usuarioRegistro']);

        if ($request->protocolo) {
            $query->where('numero_protocolo', 'like', '%' . $request->protocolo . '%');
        }
        if ($request->remetente) {
            $query->where('remetente', 'like', '%' . $request->remetente . '%');
        }
        if ($request->tipo_documento_id) {
            $query->where('tipo_documento_id', $request->tipo_documento_id);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->data_inicio && $request->data_fim) {
            $query->whereBetween('data_recebimento', [$request->data_inicio, $request->data_fim]);
        }

        $documentos = $query->orderBy('created_at', 'desc')->paginate(15);
        $tipos = TipoDocumento::where('status', 'ativo')->get();

        return view('documentos.index', compact('documentos', 'tipos'));
    }

    public function create()
    {
        $tipos = TipoDocumento::where('status', 'ativo')->get();
        return view('documentos.create', compact('tipos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_documento_id' => 'required|exists:tipo_documentos,id',
            'remetente'         => 'required|string|max:255',
            'assunto'           => 'required|string|max:255',
            'descricao'         => 'nullable|string',
            'setor_destino'     => 'required|string|max:255',
            'data_recebimento'  => 'required|date',
            'anexo'             => 'nullable|file|max:10240',
        ]);

        $documento = Documento::create([
            'numero_protocolo'    => Documento::gerarProtocolo(),
            'tipo_documento_id'   => $request->tipo_documento_id,
            'usuario_registro_id' => auth()->id(),
            'remetente'           => $request->remetente,
            'assunto'             => $request->assunto,
            'descricao'           => $request->descricao,
            'setor_destino'       => $request->setor_destino,
            'status'              => 'recebido',
            'data_recebimento'    => $request->data_recebimento,
        ]);

        // Salvar histórico inicial
        HistoricoMovimentacao::create([
            'documento_id'    => $documento->id,
            'usuario_id'      => auth()->id(),
            'status_anterior' => null,
            'status_novo'     => 'recebido',
            'observacoes'     => 'Documento registrado no sistema.',
        ]);

        // Upload de anexo (se houver)
        if ($request->hasFile('anexo')) {
            $file = $request->file('anexo');
            $caminho = $file->store('anexos', 'public');

            ArquivoAnexo::create([
                'documento_id'    => $documento->id,
                'usuario_id'      => auth()->id(),
                'nome_arquivo'    => $file->getClientOriginalName(),
                'caminho_arquivo' => $caminho,
                'tipo_mime'       => $file->getMimeType(),
                'tamanho_bytes'   => $file->getSize(),
            ]);
        }

        return redirect()->route('documentos.show', $documento)->with('success', 'Documento registrado! Protocolo: ' . $documento->numero_protocolo);
    }

    public function show(Documento $documento)
    {
        $documento->load(['tipoDocumento', 'usuarioRegistro', 'historicos.usuario', 'anexos']);
        return view('documentos.show', compact('documento'));
    }

    public function atualizarStatus(Request $request, Documento $documento)
    {
        $request->validate([
            'status'      => 'required|in:recebido,em_analise,encaminhado,finalizado',
            'observacoes' => 'nullable|string',
        ]);

        // Apenas admin pode finalizar ou re-abrir "finalizado"
        if ($documento->status === 'finalizado' && !auth()->user()->isAdmin()) {
            return back()->with('error', 'Apenas administradores podem alterar documentos finalizados.');
        }

        HistoricoMovimentacao::create([
            'documento_id'    => $documento->id,
            'usuario_id'      => auth()->id(),
            'status_anterior' => $documento->status,
            'status_novo'     => $request->status,
            'observacoes'     => $request->observacoes,
        ]);

        $documento->update(['status' => $request->status]);

        return back()->with('success', 'Status atualizado com sucesso!');
    }
}