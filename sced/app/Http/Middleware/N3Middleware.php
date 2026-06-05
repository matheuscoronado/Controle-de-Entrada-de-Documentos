<?php
namespace App\Http\Middleware;
use Closure; use Illuminate\Http\Request;
class N3Middleware {
    public function handle(Request $request, Closure $next) {
        if (!auth()->check() || !auth()->user()->podeAcessarAdmin()) abort(403, 'Acesso restrito a Supervisores N3 e Administradores.');
        return $next($request);
    }
}
