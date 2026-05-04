<?php
// routes/web.php

use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TipoDocumentoController;
use App\Http\Controllers\RelatorioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {

    // FIX 2: Dashboard agora passa $recentes corretamente
    Route::get('/dashboard', function () {
        $total    = \App\Models\Documento::count();
        $porStatus = \App\Models\Documento::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // Busca os 8 documentos mais recentes com relacionamentos
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
