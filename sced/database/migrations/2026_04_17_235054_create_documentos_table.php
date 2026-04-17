<?php

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
            $table->string('assunto');
            $table->text('descricao')->nullable();
            $table->string('setor_destino');
            $table->enum('status', ['recebido', 'em_analise', 'encaminhado', 'finalizado'])->default('recebido');
            $table->date('data_recebimento');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};