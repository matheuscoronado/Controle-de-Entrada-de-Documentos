<?php
// database/migrations/M07_create_documento_tipos_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tabela principal: tipos de documento (RG, CPF, etc.)
        Schema::create('documento_tipos', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100)->unique();
            $table->text('descricao');
            $table->enum('tipo', ['obrigatorio', 'opcional'])->default('opcional');
            $table->enum('status', ['ativo', 'inativo'])->default('ativo');
            $table->timestamps();
        });

        // Tabela pivot: vincula documentos a serviços (tipo_documentos)
        Schema::create('tipo_documento_documento_tipo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_documento_id')
                  ->constrained('tipo_documentos')
                  ->cascadeOnDelete();
            $table->foreignId('documento_tipo_id')
                  ->constrained('documento_tipos')
                  ->cascadeOnDelete();
            $table->unique(['tipo_documento_id', 'documento_tipo_id'], 'tipo_doc_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_documento_documento_tipo');
        Schema::dropIfExists('documento_tipos');
    }
};