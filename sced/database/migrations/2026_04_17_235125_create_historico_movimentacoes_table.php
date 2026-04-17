<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('historico_movimentacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('documento_id')->constrained('documentos');
            $table->foreignId('usuario_id')->constrained('users');
            $table->string('status_anterior')->nullable();
            $table->string('status_novo');
            $table->text('observacoes')->nullable();
            $table->timestamp('data_hora')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historico_movimentacoes');
    }
};