<?php
// SUBSTITUI: 2026_04_17_235025_create_tipo_documentos_table.php
// Alteração: adiciona obrigatoriedade, departamento_destino_id, cargo_responsavel, sla_horas

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
            // NOVOS (Parte 1)
            $table->enum('obrigatoriedade', ['obrigatorio', 'opcional'])->default('opcional');
            $table->unsignedBigInteger('departamento_destino_id')->nullable();
            $table->enum('cargo_responsavel', ['N1', 'N2', 'N3'])->nullable();
            $table->unsignedSmallInteger('sla_horas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('tipo_documentos'); }
};
