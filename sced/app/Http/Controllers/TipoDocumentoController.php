<?php
// app/Http/Controllers/TipoDocumentoController.php

namespace App\Http\Controllers;

use App\Models\TipoDocumento;
use App\Models\DocumentoTipo;
use App\Models\Departamento;
use App\Models\LogAuditoria;
use Illuminate\Http\Request;

class TipoDocumentoController extends Controller
{
    public function index()
    {
        $tipos = TipoDocumento::with(['departamentoDestino', 'documentosTipo'])
            ->withCount('documentos')
            ->orderBy('nome')
            ->get();

        return view('admin.tipos.index', compact('tipos'));  // ← Está passando 'tipos'
    }

    public function create()
    {
        $departamentos        = Departamento::orderBy('nome')->get();
        $documentosDisponiveis = DocumentoTipo::where('status', 'ativo')->orderBy('nome')->get();

        return view('admin.tipos.create', compact('departamentos', 'documentosDisponiveis'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'                    => 'required|string|max:100|unique:tipo_documentos,nome',
            'descricao'               => 'nullable|string|max:500',
            'departamento_destino_id' => 'nullable|exists:departamentos,id',
            'cargos_responsaveis'     => 'nullable|array',
            'cargos_responsaveis.*'   => 'in:N1,N2,N3',
            'documentos_necessarios'  => 'nullable|array',
            'documentos_necessarios.*' => 'exists:documento_tipos,id',
        ]);

        $tipo = TipoDocumento::create([
            'nome'                    => $data['nome'],
            'descricao'               => $data['descricao'] ?? null,
            'departamento_destino_id' => $data['departamento_destino_id'] ?? null,
            'cargos_responsaveis'     => $data['cargos_responsaveis'] ?? [],
            'status'                  => 'ativo',
        ]);

        // Vincula os documentos selecionados
        if (!empty($data['documentos_necessarios'])) {
            $tipo->documentosTipo()->sync($data['documentos_necessarios']);
        }

        return redirect()->route('tipos.index')
            ->with('success', "Serviço \"{$tipo->nome}\" cadastrado com sucesso!");
    }

    public function edit(TipoDocumento $tipo)
    {
        $departamentos         = Departamento::orderBy('nome')->get();
        $documentosDisponiveis = DocumentoTipo::where('status', 'ativo')->orderBy('nome')->get();
        $documentosSelecionados = $tipo->documentosTipo->pluck('id')->toArray();

        return view('admin.tipos.edit', compact(
            'tipo',
            'departamentos',
            'documentosDisponiveis',
            'documentosSelecionados'
        ));
    }

    public function update(Request $request, TipoDocumento $tipo)
    {
        $data = $request->validate([
            'nome'                    => 'required|string|max:100|unique:tipo_documentos,nome,' . $tipo->id,
            'descricao'               => 'nullable|string|max:500',
            'departamento_destino_id' => 'nullable|exists:departamentos,id',
            'cargos_responsaveis'     => 'nullable|array',
            'cargos_responsaveis.*'   => 'in:N1,N2,N3',
            'documentos_necessarios'  => 'nullable|array',
            'documentos_necessarios.*' => 'exists:documento_tipos,id',
            'status'                  => 'required|in:ativo,inativo',
        ]);

        if ($data['status'] === 'inativo' && $tipo->documentos()->exists()) {
            return back()->with('error', 'Não é possível desativar um serviço com processos vinculados.');
        }

        $tipo->update([
            'nome'                    => $data['nome'],
            'descricao'               => $data['descricao'] ?? null,
            'departamento_destino_id' => $data['departamento_destino_id'] ?? null,
            'cargos_responsaveis'     => $data['cargos_responsaveis'] ?? [],
            'status'                  => $data['status'],
        ]);

        // Sincroniza documentos
        $tipo->documentosTipo()->sync($data['documentos_necessarios'] ?? []);

        return redirect()->route('tipos.index')
            ->with('success', "Serviço \"{$tipo->nome}\" atualizado!");
    }
}
