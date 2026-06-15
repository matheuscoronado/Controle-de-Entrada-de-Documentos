<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LogAuditoria;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Exibe a lista de usuários
     */
    public function index()
    {
        $usuarios = User::with('departamentoRelacionado')
            ->orderBy('nome')
            ->paginate(15);
        
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Exibe o formulário de criação de usuário
     */
    public function create()
    {
        $departamentos = Departamento::orderBy('nome', 'asc')->get();
        return view('usuarios.create', compact('departamentos'));
    }

    /**
     * Armazena um novo usuário no banco de dados
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nome'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email',
            'password'        => 'required|min:6|confirmed',
            'perfil'          => 'required|in:administrador,operador',
            'departamento_id' => 'required|exists:departamentos,id',
            'cargo'           => 'required|in:N1,N2,N3',
            'pode_assumir'    => 'nullable|boolean',
        ]);

        $user = User::create([
            'nome'            => $request->nome,
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
            'perfil'          => $request->perfil,
            'status'          => 'ativo',
            'departamento_id' => $request->departamento_id,
            'cargo'           => $request->cargo,
            'pode_assumir'    => $request->boolean('pode_assumir'),
        ]);

        return redirect()->route('usuarios.index')
            ->with('success', "Usuário {$user->nome} cadastrado com sucesso!");
    }

    /**
     * Exibe o formulário de edição de usuário
     */
    public function edit(User $usuario)
    {
        $departamentos = Departamento::orderBy('nome', 'asc')->get();
        return view('usuarios.edit', compact('usuario', 'departamentos'));
    }

    /**
     * Atualiza um usuário no banco de dados
     */
    public function update(Request $request, User $usuario): RedirectResponse
    {
        $rules = [
            'nome'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email,' . $usuario->id,
            'perfil'          => 'required|in:administrador,operador',
            'status'          => 'required|in:ativo,inativo',
            'departamento_id' => 'required|exists:departamentos,id',
            'cargo'           => 'required|in:N1,N2,N3',
            'pode_assumir'    => 'nullable|boolean',
        ];
        
        if ($request->filled('password')) {
            $rules['password'] = 'min:6|confirmed';
        }
        
        $request->validate($rules);
        
        $dadosAtualizacao = [
            'nome'            => $request->nome,
            'email'           => $request->email,
            'perfil'          => $request->perfil,
            'status'          => $request->status,
            'departamento_id' => $request->departamento_id,
            'cargo'           => $request->cargo,
            'pode_assumir'    => $request->boolean('pode_assumir'),
        ];
        
        if ($request->filled('password')) {
            $dadosAtualizacao['password'] = Hash::make($request->password);
        }
        
        $usuario->update($dadosAtualizacao);

        return redirect()->route('usuarios.index')
            ->with('success', "Usuário {$usuario->nome} atualizado com sucesso!");
    }

    /**
     * Remove (desativa) um usuário do sistema
     */
    public function destroy(User $usuario): RedirectResponse
    {
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'Você não pode desativar seu próprio usuário.');
        }
        
        $nome = $usuario->nome;
        $usuario->update(['status' => 'inativo']);

        return redirect()->route('usuarios.index')
            ->with('success', "Usuário {$nome} desativado com sucesso!");
    }
    
    /**
     * Alterna o status do usuário entre ativo e inativo
     */
    public function toggleStatus(User $usuario): RedirectResponse
    {
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'Você não pode alterar o status do seu próprio usuário.');
        }
        
        $novoStatus = $usuario->status === 'ativo' ? 'inativo' : 'ativo';
        $usuario->update(['status' => $novoStatus]);
        
        $mensagem = $novoStatus === 'ativo' 
            ? "Usuário {$usuario->nome} ativado com sucesso!" 
            : "Usuário {$usuario->nome} desativado com sucesso!";

        return back()->with('success', $mensagem);
    }
    
    /**
     * Exibe os logs de auditoria de um usuário específico
     */
    public function logs(User $usuario)
    {
        $logs = LogAuditoria::where('usuario_id', $usuario->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('usuarios.logs', compact('usuario', 'logs'));
    }
}