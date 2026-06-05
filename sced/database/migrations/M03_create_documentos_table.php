<?php
// SUBSTITUI: 2026_04_17_235054_create_documentos_table.php
// Alterações: status ENUM expandido, assunto nullable, novos campos de atribuição

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->string('numero_protocolo')->unique();
            $table->foreignId('tipo_documento_id')->constrained('tipo_documentos');
            $table->foreignId('usuario_registro_id')->constrained('users');
            $table->string('remetente');
            $table->string('assunto')->nullable();  // nullable — removido do novo fluxo
            $table->text('descricao')->nullable();
            $table->string('setor_destino');
            $table->unsignedBigInteger('departamento_destino_id')->nullable();
            // ALTERADO: enum expandido com novos status
            $table->enum('status', ['novo','em_analise','pendente','finalizado','desativado'])->default('novo');
            $table->date('data_recebimento');
            // NOVOS (Parte 3): atribuição e contexto
            $table->unsignedBigInteger('atribuido_a_id')->nullable();
            $table->timestamp('atribuido_em')->nullable();
            $table->text('motivo_pendencia')->nullable();
            $table->text('motivo_desativacao')->nullable();
            $table->timestamp('reaberto_em')->nullable();
            $table->timestamps();

            $table->foreign('departamento_destino_id')->references('id')->on('departamentos')->nullOnDelete();
            $table->foreign('atribuido_a_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void { Schema::dropIfExists('documentos'); }
};
