<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProcessoController;
use App\Http\Controllers\TipoDocumentoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\LogAuditoriaController;
use App\Http\Controllers\DocumentoTipoController;
use Illuminate\Support\Facades\Route;

// Redirecionamento da raiz para o login
Route::get('/', fn() => redirect()->route('login'));

Route::middleware(['auth'])->group(function () {

    // ── Dashboard ───────────────────────────────────────────
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Processos: Rotas Estáticas (Devem vir ANTES das rotas com {documento}) ──
    Route::get ('documentos',                  [ProcessoController::class, 'index'])         ->name('documentos.index');
    Route::get ('documentos/novo',             [ProcessoController::class, 'create'])        ->name('documentos.create');
    Route::post('documentos',                  [ProcessoController::class, 'store'])         ->name('documentos.store');
    
    // 💡 Endpoints JSON para o Autocomplete Nativo (Sessão Protegida)
    Route::get ('documentos/tipos-json',       [ProcessoController::class, 'tiposJson'])     ->name('documentos.tipos_json');
    Route::get ('documentos/{id}/requisitos',  [ProcessoController::class, 'requisitosJson'])->name('documentos.requisitos_json');

    // ── Processos: Transições de Status e Fluxo ──
    Route::post ('documentos/{documento}/assumir',       [ProcessoController::class, 'assumir'])       ->name('documentos.assumir');
    Route::post ('documentos/{documento}/devolver',      [ProcessoController::class, 'devolver'])      ->name('documentos.devolver');
    Route::post ('documentos/{documento}/retornar',      [ProcessoController::class, 'retornar'])      ->name('documentos.retornar');
    Route::post ('documentos/{documento}/finalizar',     [ProcessoController::class, 'finalizar'])     ->name('documentos.finalizar');
    Route::post ('documentos/{documento}/desativar',     [ProcessoController::class, 'desativar'])     ->name('documentos.desativar');
    Route::post ('documentos/{documento}/reabrir',       [ProcessoController::class, 'reabrir'])       ->name('documentos.reabrir');
    Route::patch('documentos/{documento}/status-manual', [ProcessoController::class, 'statusManual'])  ->name('documentos.status-manual');

    // ── Processos: Gerenciamento de Anexos do Fluxo ──
    Route::post('documentos/{documento}/anexos/{anexo}/substituir', [ProcessoController::class, 'substituirAnexo'])->name('documentos.anexo.substituir');
    Route::post('documentos/{documento}/anexos/{anexo}/validar',    [ProcessoController::class, 'validarAnexo'])   ->name('documentos.anexo.validar');

    // ── Processos: CRUD Padrão com Parâmetros Dinâmicos (Por último para evitar conflitos) ──
    Route::get ('documentos/{documento}',        [ProcessoController::class, 'show'])          ->name('documentos.show');
    Route::get ('documentos/{documento}/editar', [ProcessoController::class, 'edit'])          ->name('documentos.edit');
    Route::put ('documentos/{documento}',        [ProcessoController::class, 'update'])        ->name('documentos.update');

    // ── Serviços (Tipos de Documento) ───────────────────────
    Route::resource('tipos', TipoDocumentoController::class)->except(['show', 'destroy']);

    // ── Admin + N3 ──────────────────────────────────────────
    Route::middleware('n3')->group(function () {
        Route::get('/logs',       [LogAuditoriaController::class, 'index'])->name('logs.index');
        Route::get('/logs/{log}', [LogAuditoriaController::class, 'show']) ->name('logs.show');
    });

    // ── Somente Admin ───────────────────────────────────────
    Route::middleware('admin')->group(function () {
        Route::resource('usuarios',       UsuarioController::class);
        Route::resource('departamentos', DepartamentoController::class);
        Route::resource('documentos-tipo', DocumentoTipoController::class)->except(['show', 'destroy']);
        Route::get ('/relatorios',       [RelatorioController::class, 'index'])->name('relatorios.index');
        Route::post('/relatorios/gerar', [RelatorioController::class, 'gerar']) ->name('relatorios.gerar');
    });

});

require __DIR__.'/auth.php';