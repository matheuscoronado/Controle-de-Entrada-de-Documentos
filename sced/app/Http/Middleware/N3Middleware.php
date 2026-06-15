<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class N3Middleware 
{
    public function handle(Request $request, Closure $next) 
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        $user = auth()->user();
        
        // Admin tem acesso total
        if ($user->perfil === 'administrador' || $user->perfil === 'admin') {
            return $next($request);
        }
        
        // Verifica se é N3 (supervisor)
        if ($user->cargo === 'N3' || $user->perfil === 'n3') {
            return $next($request);
        }
        
        abort(403, 'Acesso restrito a Supervisores N3 e Administradores.');
    }
}