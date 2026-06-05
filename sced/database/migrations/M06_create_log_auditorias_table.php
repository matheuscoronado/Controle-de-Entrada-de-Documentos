<?php
// SUBSTITUI: 2026_04_17_235202_create_log_auditorias_table.php
// Alteração: adiciona todos os campos de auditoria da Parte 1

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('log_auditorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users');
            $table->string('acao');
            $table->string('modulo')->nullable();
            $table->string('tabela_afetada')->nullable();
            $table->unsignedBigInteger('registro_id')->nullable();
            $table->string('status_anterior')->nullable();
            $table->string('status_novo')->nullable();
            $table->json('campos_alterados')->nullable();
            $table->json('uploads_realizados')->nullable();
            $table->text('descricao_legivel')->nullable();
            $table->string('ip_origem')->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->timestamp('data_hora')->useCurrent();
        });
    }

    public function down(): void { Schema::dropIfExists('log_auditorias'); }
};
