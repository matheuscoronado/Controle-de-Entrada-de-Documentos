<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('log_auditorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users');
            $table->string('acao');
            $table->string('tabela_afetada')->nullable();
            $table->unsignedBigInteger('registro_id')->nullable();
            $table->string('ip_origem')->nullable();
            $table->timestamp('data_hora')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_auditorias');
    }
};