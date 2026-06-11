<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LogAuditoria;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::with('departamentoRelacionado')->get();
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $departamentos = Departamento::orderBy('nome', 'asc')->get();
        return view('usuarios.create', compact('departamentos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users',
            'password'        => 'required|min:6|confirmed',
            // CORREÇÃO: adicionado 'n3' ao enum de perfis permitidos
            'perfil'          => 'required|in:administrador,n3,operador',
            'departamento_id' => 'required|exists:departamentos,id',
            'cargo'           => 'required|in:N1,N2,N3',
        ]);

        $user = User::create([
            'nome'            => $request->nome,
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
            'perfil'          => $request->perfil,
            'status'          => 'ativo',
            'departamento_id' => $request->departamento_id,
            'cargo'           => $request->cargo,
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
        $departamentos = Departamento::orderBy('nome', 'asc')->get();
        return view('usuarios.edit', compact('usuario', 'departamentos'));
    }

    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'nome'            => 'required|string|max:255',
            // CORREÇÃO: adicionado 'n3' ao enum de perfis permitidos
            'perfil'          => 'required|in:administrador,n3,operador',
            'status'          => 'required|in:ativo,inativo',
            'departamento_id' => 'required|exists:departamentos,id',
            'cargo'           => 'required|in:N1,N2,N3',
        ]);

        $usuario->update([
            'nome'            => $request->nome,
            'perfil'          => $request->perfil,
            'status'          => $request->status,
            'departamento_id' => $request->departamento_id,
            'cargo'           => $request->cargo,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuário atualizado!');
    }

    public function destroy(User $usuario)
    {
        $usuario->update(['status' => 'inativo']);
        return redirect()->route('usuarios.index')->with('success', 'Usuário desativado!');
    }
}
