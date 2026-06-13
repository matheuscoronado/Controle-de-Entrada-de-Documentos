<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Verifica se o usuário está logado
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        // Verifica se o perfil é admin (ajuste conforme seu sistema)
        $user = auth()->user();
        
        // Seu sistema usa 'perfil' ou 'tipo' ou 'role'?
        if ($user->perfil !== 'administrador' && $user->perfil !== 'admin') {
            abort(403, 'Acesso negado. Apenas administradores.');
        }
        
        return $next($request);
    }
}