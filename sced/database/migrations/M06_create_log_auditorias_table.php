<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('log_auditorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('acao');
            $table->string('modulo')->nullable();
            $table->string('tabela_afetada')->nullable();
            $table->unsignedBigInteger('registro_id')->nullable();
            $table->text('status_anterior')->nullable();
            $table->text('status_novo')->nullable();
            $table->json('campos_alterados')->nullable();
            $table->json('uploads_realizados')->nullable();
            $table->text('descricao_legivel')->nullable();
            $table->string('ip_origem', 45)->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->timestamp('data_hora')->useCurrent();
            $table->timestamps(); // ⭐ ADICIONADO: created_at e updated_at
            
            // Índices para melhor performance
            $table->index(['modulo', 'registro_id']);
            $table->index('created_at');
            $table->index('usuario_id');
            $table->index('acao');
        });
    }

    public function down(): void 
    { 
        Schema::dropIfExists('log_auditorias');
    }
};