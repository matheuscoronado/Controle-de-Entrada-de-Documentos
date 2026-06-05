<?php
// ============================================================
// Migration: Expande tabela users para suportar os 3 níveis
// de acesso: Administrador, N3 (supervisor) e Operadores (N1/N2)
// ============================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Altera o enum de 'perfil' para incluir o nível N3
        // No MySQL precisamos recriar a coluna.
        Schema::table('users', function (Blueprint $table) {
            // Substitui 'perfil' ENUM para incluir 'n3'
            $table->enum('perfil', ['administrador', 'n3', 'operador'])
                  ->default('operador')
                  ->change();

            // Garante que 'cargo' também esteja como N1/N2/N3
            $table->enum('cargo', ['N1', 'N2', 'N3'])
                  ->nullable()
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('perfil', ['administrador', 'operador'])
                  ->default('operador')
                  ->change();

            $table->string('cargo')->nullable()->change();
        });
    }
};
