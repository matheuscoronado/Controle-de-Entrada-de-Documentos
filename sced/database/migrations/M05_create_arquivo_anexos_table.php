<?php
// SUBSTITUI: 2026_04_17_235143_create_arquivo_anexos_table.php
// Alteração: adiciona tipo_anexo e campos de validação

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('arquivo_anexos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('documento_id')->constrained('documentos');
            $table->foreignId('usuario_id')->constrained('users');
            // NOVOS (Parte 2)
            $table->enum('tipo_anexo', [
                'rg','cpf','contrato','comprovante_residencia',
                'comprovante_renda','certidao','laudo','outros'
            ])->default('outros');
            $table->enum('status_validacao', ['pendente','aprovado','rejeitado'])->default('pendente');
            $table->text('observacao_validacao')->nullable();
            $table->unsignedBigInteger('validado_por')->nullable();
            $table->timestamp('validado_em')->nullable();
            // Originais
            $table->string('nome_arquivo');
            $table->string('caminho_arquivo');
            $table->string('tipo_mime');
            $table->integer('tamanho_bytes');
            $table->timestamp('enviado_em')->useCurrent();

            $table->foreign('validado_por')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void { Schema::dropIfExists('arquivo_anexos'); }
};
