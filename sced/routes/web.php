<?php

use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TipoDocumentoController;
use App\Http\Controllers\RelatorioController;
use Illuminate\Support\Facades\Route;

// Página inicial redireciona para login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rotas autenticadas
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        $total = \App\Models\Documento::count();
        $porStatus = \App\Models\Documento::selectRaw('status, count(*) as total')
            ->groupBy('status')->pluck('total', 'status');
        return view('dashboard', compact('total', 'porStatus'));
    })->name('dashboard');

    // Documentos
    Route::resource('documentos', DocumentoController::class)->except(['edit', 'update', 'destroy']);
    Route::patch('documentos/{documento}/status', [DocumentoController::class, 'atualizarStatus'])->name('documentos.status');

    // Tipos de Documento
    Route::resource('tipos', TipoDocumentoController::class)->except(['show', 'destroy']);

    // Relatórios (só admin)
    Route::middleware('admin')->group(function () {
        Route::get('/relatorios', [RelatorioController::class, 'index'])->name('relatorios.index');
        Route::post('/relatorios/gerar', [RelatorioController::class, 'gerar'])->name('relatorios.gerar');
    });

    // Usuários (só admin)
    Route::middleware('admin')->group(function () {
        Route::resource('usuarios', UsuarioController::class);
    });
});

require __DIR__ . '/auth.php';