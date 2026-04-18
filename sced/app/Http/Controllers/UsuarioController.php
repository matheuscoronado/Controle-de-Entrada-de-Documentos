<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LogAuditoria;
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
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'perfil' => 'required|in:administrador,operador',
        ]);

        $user = User::create([
            'nome'     => $request->nome,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'perfil'   => $request->perfil,
            'status'   => 'ativo',
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
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'nome'   => 'required|string|max:255',
            'perfil' => 'required|in:administrador,operador',
            'status' => 'required|in:ativo,inativo',
        ]);

        $usuario->update($request->only('nome', 'perfil', 'status'));

        return redirect()->route('usuarios.index')->with('success', 'Usuário atualizado!');
    }

    public function destroy(User $usuario)
    {
        $usuario->update(['status' => 'inativo']);
        return redirect()->route('usuarios.index')->with('success', 'Usuário desativado!');
    }
}