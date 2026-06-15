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
        
        $user = auth()->user();
        
        // Verifica se o usuário é administrador
        // Aceita: perfil 'administrador' ou cargo 'admin'
        if ($user->perfil === 'administrador' || $user->perfil === 'admin' || $user->cargo === 'admin') {
            return $next($request);
        }
        
        // Se não for admin, bloqueia
        abort(403, 'Acesso negado. Apenas administradores podem acessar esta área.');
    }
}