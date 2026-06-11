<?php
// app/Http/Controllers/DocumentoTipoController.php

namespace App\Http\Controllers;

use App\Models\DocumentoTipo;
use App\Models\LogAuditoria;
use Illuminate\Http\Request;

class DocumentoTipoController extends Controller
{
    public function index()
    {
        $documentos = DocumentoTipo::orderBy('nome')->get();
        return view('admin.documentos.index', compact('documentos'));
    }

    public function create()
    {
        return view('admin.documentos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'     => 'required|string|max:100|unique:documento_tipos,nome',
            'descricao'=> 'required|string|max:500',
            'tipo'     => 'required|in:obrigatorio,opcional',
        ]);

        $data['status'] = 'ativo';
        $doc = DocumentoTipo::create($data);

        LogAuditoria::registrar('CADASTRO_DOCUMENTO_TIPO', 'documento_tipos', $doc->id, [
            'modulo'            => 'documentos',
            'descricao_legivel' => "Documento '{$doc->nome}' cadastrado.",
        ]);

        return redirect()->route('documentos-tipo.index')
            ->with('success', "Documento \"{$doc->nome}\" cadastrado com sucesso!");
    }

    public function edit(DocumentoTipo $documentoTipo)
    {
        return view('admin.documentos.edit', compact('documentoTipo'));
    }

    public function update(Request $request, DocumentoTipo $documentoTipo)
    {
        $data = $request->validate([
            'nome'     => 'required|string|max:100|unique:documento_tipos,nome,' . $documentoTipo->id,
            'descricao'=> 'required|string|max:500',
            'tipo'     => 'required|in:obrigatorio,opcional',
            'status'   => 'required|in:ativo,inativo',
        ]);

        $documentoTipo->update($data);

        return redirect()->route('documentos-tipo.index')
            ->with('success', "Documento \"{$documentoTipo->nome}\" atualizado!");
    }
}