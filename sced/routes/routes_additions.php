<?php
// ============================================================
// routes/web.php — ADIÇÕES DA PARTE 1
// Cole este trecho dentro do grupo middleware(['auth']) existente,
// dentro da área administrativa (middleware 'admin').
// ============================================================

// Registrar os dois middlewares em bootstrap/app.php:
//
// ->withMiddleware(function (Middleware $middleware) {
//     $middleware->alias([
//         'admin' => \App\Http\Middleware\AdminMiddleware::class,
//         'n3'    => \App\Http\Middleware\N3Middleware::class,
//     ]);
// })

use App\Http\Controllers\LogAuditoriaController;
use App\Http\Controllers\TipoDocumentoController;

Route::middleware(['auth'])->group(function () {

    // ── TIPOS DE DOCUMENTO (com novos campos) ──────────────
    // Substitui o resource anterior de 'tipos'
    Route::resource('tipos', TipoDocumentoController::class)
         ->except(['show', 'destroy']);

    // ── ÁREA ADMINISTRATIVA EXCLUSIVA DO ADMIN ─────────────
    Route::middleware('admin')->group(function () {

        // Logs de Auditoria
        Route::get('/logs', [LogAuditoriaController::class, 'index'])
             ->name('logs.index');
        Route::get('/logs/{log}', [LogAuditoriaController::class, 'show'])
             ->name('logs.show');

        // ... demais rotas admin já existentes (usuarios, departamentos, relatorios)
    });

    // ── ÁREA COMPARTILHADA ADMIN + N3 ──────────────────────
    // (Para as próximas partes: painéis de supervisão, relatórios N3)
    Route::middleware('n3')->group(function () {
        // Ex: Route::get('/supervisao', ...) — reservado para Parte 2
    });
});
