<?php

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


use App\Http\Controllers\Api\ServicoController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    // Se não usar Sanctum, troque por 'auth'
    Route::get('/servicos/buscar',           [ServicoController::class, 'buscar']);
    Route::get('/servicos/{id}/requisitos',  [ServicoController::class, 'requisitos']);
});


// ── routes/web.php (substituição das rotas de documentos) ─
// Mantém as URLs /documentos/* para não quebrar links externos.

use App\Http\Controllers\DocumentoController;

Route::middleware(['auth'])->group(function () {

    Route::resource('documentos', DocumentoController::class)
         ->except(['edit', 'update', 'destroy']);

    Route::patch('documentos/{documento}/status',
        [DocumentoController::class, 'atualizarStatus']
    )->name('documentos.status');

});
