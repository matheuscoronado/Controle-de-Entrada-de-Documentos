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

});