<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    public function index()
    {
        $departamentos = Departamento::withCount('usuarios')
            ->orderBy('nome', 'asc')
            ->get();

        return view('departamentos.index', compact('departamentos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:100|unique:departamentos,nome',
        ], [
            'nome.unique' => 'Este departamento já está cadastrado.',
            'nome.required' => 'O nome do departamento é obrigatório.'
        ]);

        Departamento::create([
            'nome' => mb_strtoupper($request->nome, 'UTF-8'),
        ]);

        return redirect()->route('departamentos.index')->with('success', 'Departamento criado com sucesso!');
    }

    public function destroy(Departamento $departamento)
    {
        if ($departamento->usuarios()->count() > 0) {
            return redirect()->back()->with('error', 'Não é possível excluir: existem usuários neste departamento.');
        }

        $departamento->delete();
        return redirect()->route('departamentos.index')->with('success', 'Departamento removido!');
    }
}