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
        // "Eager Loading": Carrega o departamento junto para não pesar o banco
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
            'perfil'          => 'required|in:administrador,operador',
            // Validamos pelo ID agora
            'departamento_id' => 'required|exists:departamentos,id', 
            'cargo'           => 'required|in:N1,N2,N3',
        ]);

        $user = User::create([
            'nome'            => $request->nome,
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
            'perfil'          => $request->perfil,
            'status'          => 'ativo',
            'departamento_id' => $request->departamento_id, // Salvando o ID
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
            'perfil'          => 'required|in:administrador,operador',
            'status'          => 'required|in:ativo,inativo',
            'departamento_id' => 'required|exists:departamentos,id', // Validando ID
            'cargo'           => 'required|in:N1,N2,N3',
        ]);

        // Atualiza usando o departamento_id
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