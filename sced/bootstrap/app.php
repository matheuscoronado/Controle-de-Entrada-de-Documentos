<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Exceptions\StatusTransitionException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web:      __DIR__.'/../routes/web.php',
        api:      __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health:   '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'n3'    => \App\Http\Middleware\N3Middleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // Converte StatusTransitionException em resposta HTTP adequada
        $exceptions->render(function (StatusTransitionException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $e->getMessage()], $e->httpCode);
            }
            return back()->with('error', $e->getMessage());
        });

        // Converte AuthorizationException (policy) em 403 limpo
        $exceptions->render(function (AuthorizationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Acesso negado.'], 403);
            }
            abort(403, $e->getMessage() ?: 'Acesso negado.');
        });
    })
    ->create();
