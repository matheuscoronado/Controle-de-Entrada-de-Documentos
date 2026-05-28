<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    /**
     * Exibe a lista de departamentos com a contagem de usuários.
     */
    public function index()
    {
        // O withCount('usuarios') cria o atributo virtual 'usuarios_count'
        $departamentos = Departamento::withCount('usuarios')
            ->orderBy('nome', 'asc')
            ->get();

        return view('departamentos.index', compact('departamentos'));
    }

    /**
     * Salva um novo departamento no banco de dados.
     */
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

    /**
     * Remove um departamento (se não houver usuários vinculados).
     */
    public function destroy(Departamento $departamento)
    {
        // Usamos o relacionamento para verificar a existência de usuários
        if ($departamento->usuarios()->count() > 0) {
            return redirect()->back()->with('error', 'Não é possível excluir: existem usuários neste departamento.');
        }

        $departamento->delete();
        return redirect()->route('departamentos.index')->with('success', 'Departamento removido!');
    }
}