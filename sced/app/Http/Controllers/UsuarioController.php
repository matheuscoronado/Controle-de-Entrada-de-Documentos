<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LogAuditoria;
use App\Models\Departamento; // IMPORTANTE: Importar o novo Model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::all();
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        // Busca todos os departamentos para o select na View
        $departamentos = Departamento::orderBy('nome', 'asc')->get();
        return view('usuarios.create', compact('departamentos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users',
            'password'     => 'required|min:6|confirmed',
            'perfil'       => 'required|in:administrador,operador',
            // Agora validamos se o departamento existe na tabela de departamentos
            'departamento' => 'required|exists:departamentos,nome',
            'cargo'        => 'required|in:N1,N2,N3',
        ]);

        $user = User::create([
            'nome'         => $request->nome,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'perfil'       => $request->perfil,
            'status'       => 'ativo',
            'departamento' => $request->departamento,
            'cargo'        => $request->cargo,
        ]);

        LogAuditoria::create([
            'usuario_id'     => auth()->id(),
            'acao'           => 'CADASTRO_USUARIO',
            'tabela_afetada' => 'users',
            'registro_id'    => $user->id,
            'ip_origem'      => $request->ip(),
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuário cadastrado com sucesso!');
    }

    public function edit(User $usuario)
    {
        // Busca os departamentos para permitir a troca na edição
        $departamentos = Departamento::orderBy('nome', 'asc')->get();
        return view('usuarios.edit', compact('usuario', 'departamentos'));
    }

    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'nome'         => 'required|string|max:255',
            'perfil'       => 'required|in:administrador,operador',
            'status'       => 'required|in:ativo,inativo',
            'departamento' => 'required|exists:departamentos,nome',
            'cargo'        => 'required|in:N1,N2,N3',
        ]);

        $usuario->update($request->only('nome', 'perfil', 'status', 'departamento', 'cargo'));

        return redirect()->route('usuarios.index')->with('success', 'Usuário atualizado!');
    }

    public function destroy(User $usuario)
    {
        $usuario->update(['status' => 'inativo']);
        return redirect()->route('usuarios.index')->with('success', 'Usuário desativado!');
    }
}