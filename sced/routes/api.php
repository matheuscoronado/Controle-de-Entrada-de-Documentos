<?php
// routes/api.php

use App\Http\Controllers\Api\ServicoController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('dashboard/metricas', [DashboardController::class, 'metricas'])->name('dashboard.metricas');
    Route::get('dashboard/analistas', [DashboardController::class, 'analistas'])->name('dashboard.analistas');
    Route::post('processos/{documento}/atribuir', [DashboardController::class, 'atribuir'])->name('dashboard.atribuir');

    // ⭐ Autocomplete de Serviços (CORRIGIDO)
    Route::get('servicos/buscar', [ServicoController::class, 'buscar']);
    Route::get('servicos/{id}/requisitos', [ServicoController::class, 'requisitos']);

    // Buscar todos os documentos cadastrados (para o select)
    // Buscar todos os documentos cadastrados (para o select)
    Route::get('documentos/todos', function () {
        return response()->json(\App\Models\DocumentoTipo::where('status', 'ativo')->orderBy('nome')->get(['id', 'nome', 'tipo']));
    })->middleware('auth');
});
