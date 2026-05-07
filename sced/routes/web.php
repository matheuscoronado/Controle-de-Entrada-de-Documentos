<?php
// routes/web.php

use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TipoDocumentoController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\DepartamentoController; // Importado o novo controller
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        $total     = \App\Models\Documento::count();
        $porStatus = \App\Models\Documento::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $recentes = \App\Models\Documento::with(['tipoDocumento'])
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return view('dashboard', compact('total', 'porStatus', 'recentes'));
    })->name('dashboard');

    // Documentos
    Route::resource('documentos', DocumentoController::class)->except(['edit', 'update', 'destroy']);
    Route::patch('documentos/{documento}/status', [DocumentoController::class, 'atualizarStatus'])->name('documentos.status');

    // Tipos de Documento
    Route::resource('tipos', TipoDocumentoController::class)->except(['show', 'destroy']);

    // Área Administrativa (Apenas Admin)
    Route::middleware('admin')->group(function () {
        // Departamentos (O que você pediu)
        Route::resource('departamentos', DepartamentoController::class);

        // Usuários
        Route::resource('usuarios', UsuarioController::class);

        // Relatórios
        Route::get('/relatorios', [RelatorioController::class, 'index'])->name('relatorios.index');
        Route::post('/relatorios/gerar', [RelatorioController::class, 'gerar'])->name('relatorios.gerar');
    });

});

require __DIR__ . '/auth.php';