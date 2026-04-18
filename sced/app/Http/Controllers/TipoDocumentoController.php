<?php

namespace App\Http\Controllers;

use App\Models\TipoDocumento;
use Illuminate\Http\Request;

class TipoDocumentoController extends Controller
{
    public function index()
    {
        $tipos = TipoDocumento::all();
        return view('tipos.index', compact('tipos'));
    }

    public function create()
    {
        return view('tipos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'      => 'required|string|unique:tipo_documentos,nome',
            'descricao' => 'nullable|string',
        ]);

        TipoDocumento::create($request->only('nome', 'descricao'));

        return redirect()->route('tipos.index')->with('success', 'Tipo cadastrado!');
    }

    public function edit(TipoDocumento $tipo)
    {
        return view('tipos.edit', compact('tipo'));
    }

    public function update(Request $request, TipoDocumento $tipo)
    {
        $request->validate([
            'nome'   => 'required|string|unique:tipo_documentos,nome,' . $tipo->id,
            'status' => 'required|in:ativo,inativo',
        ]);

        // Não permite desativar se houver documentos vinculados
        if ($request->status === 'inativo' && $tipo->documentos()->exists()) {
            return back()->with('error', 'Não é possível desativar um tipo com documentos vinculados.');
        }

        $tipo->update($request->only('nome', 'descricao', 'status'));

        return redirect()->route('tipos.index')->with('success', 'Tipo atualizado!');
    }
}