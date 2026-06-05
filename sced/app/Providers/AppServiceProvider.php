<?php

namespace App\Providers;

use App\Models\Documento;              // <-- Importação da Model adicionada
use App\Policies\ProcessoPolicy;        // <-- Importação da Policy adicionada
use Illuminate\Support\Facades\Gate;   // <-- Importação do Gate adicionada
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Vincula explicitamente a model Documento à ProcessoPolicy para o Laravel mapear as permissões
        Gate::policy(Documento::class, ProcessoPolicy::class);
    }
}