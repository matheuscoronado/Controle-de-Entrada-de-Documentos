<?php
// Migration: Adiciona permissão granular de assumir processos
// Requisito: "O usuário possuir permissão de acesso habilitada para assumir processos"
// Independente do cargo/setor, o admin pode habilitar ou bloquear esta permissão por usuário.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // true  = usuário habilitado a assumir processos do seu setor
            // false = usuário do setor, mas SEM permissão para assumir
            $table->boolean('pode_assumir')->default(false)->after('cargo')
                  ->comment('Permissão habilitada para assumir processos');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('pode_assumir');
        });
    }
};
