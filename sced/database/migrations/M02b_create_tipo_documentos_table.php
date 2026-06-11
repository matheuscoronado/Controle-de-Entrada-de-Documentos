<?php
// database/migrations/M02b_create_tipo_documentos_table.php — CORRIGIDO
// Correção: adicionada FK explícita para departamento_destino_id → departamentos
// (a migration original não declarava a FK, apenas a coluna)

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tipo_documentos', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->text('descricao')->nullable();
            $table->enum('status', ['ativo', 'inativo'])->default('ativo');
            $table->enum('obrigatoriedade', ['obrigatorio', 'opcional'])->default('opcional');
            $table->unsignedBigInteger('departamento_destino_id')->nullable();
            $table->enum('cargo_responsavel', ['N1', 'N2', 'N3'])->nullable();
            $table->unsignedSmallInteger('sla_horas')->nullable();
            $table->timestamps();

            // CORREÇÃO: FK adicionada (departamentos já existe pois M02 roda antes)
            $table->foreign('departamento_destino_id')
                  ->references('id')
                  ->on('departamentos')
                  ->nullOnDelete();
        });
    }

    public function down(): void { Schema::dropIfExists('tipo_documentos'); }
};
