<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Adicionando as novas colunas após a coluna 'perfil'
            $table->enum('departamento', ['RH', 'COMERCIAL', 'SUPORTE'])->after('perfil')->nullable();
            $table->enum('cargo', ['N1', 'N2', 'N3'])->after('departamento')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['departamento', 'cargo']);
        });
    }
};