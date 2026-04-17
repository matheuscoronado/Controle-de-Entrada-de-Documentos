<?php

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
            $table->string('nome_arquivo');
            $table->string('caminho_arquivo');
            $table->string('tipo_mime');
            $table->integer('tamanho_bytes');
            $table->timestamp('enviado_em')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arquivo_anexos');
    }
};