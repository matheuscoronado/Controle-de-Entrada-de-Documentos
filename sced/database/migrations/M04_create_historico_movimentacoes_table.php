<?php
// SUBSTITUI: 2026_04_17_235125_create_historico_movimentacoes_table.php
// Alteração: adiciona tipo e usuario_destino_id

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
            $table->string('status_novo')->nullable();
            $table->text('observacoes')->nullable();
            // NOVOS (Parte 3)
            $table->enum('tipo', [
                'criacao','atribuicao','devolucao','retorno','finalizacao',
                'edicao_dados','substituicao_anexo','desativacao','reabertura','alteracao_manual'
            ])->default('alteracao_manual');
            $table->unsignedBigInteger('usuario_destino_id')->nullable();
            $table->timestamp('data_hora')->useCurrent();

            $table->foreign('usuario_destino_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void { Schema::dropIfExists('historico_movimentacoes'); }
};
