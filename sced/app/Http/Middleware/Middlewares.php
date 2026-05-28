<?php

// ============================================================
// app/Http/Middleware/CheckLevelMiddleware.php
// Controla o acesso às rotas baseado no nível/regra do usuário.
// ============================================================

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLevelMiddleware
{
    /**
     * Trata a requisição antes que ela chegue ao controlador.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$levels  Níveis permitidos (ex: 'admin', 'n3')
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string ...$levels): Response
    {
        // 1. O usuário está autenticado?
        if (! auth()->check()) {
            abort(401, 'Usuário não autenticado.');
        }

        // 2. Recupera o usuário logado
        $user = auth()->user();

        // 3. Verifica se o usuário é Administrador Geral (Admin sempre tem acesso a tudo)
        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return $next($request);
        }

        // 4. Verifica se o nível atual do usuário está na lista de níveis permitidos para a rota
        // Nota: Assumi que você tem um método 'hasRole' ou uma propriedade 'level'/'role' no Model.
        // Ajuste '$user->level' para a coluna correspondente na sua tabela de usuários.
        if (isset($user->level) && in_array($user->level, $levels)) {
            return $next($request);
        }

        // Se não passou em nenhuma validação, bloqueia o acesso
        abort(403, 'Acesso restrito. Você não tem permissão para acessar esta área.');
    }
}