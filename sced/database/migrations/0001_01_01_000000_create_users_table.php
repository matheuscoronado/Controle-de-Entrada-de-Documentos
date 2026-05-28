<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tabela de Usuários
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('email')->unique();
            $table->string('password');
            
            // ADICIONE ESTAS DUAS LINHAS AQUI:
            $table->unsignedBigInteger('departamento_id')->nullable(); 
            $table->string('cargo')->nullable();

            $table->enum('perfil', ['administrador', 'operador'])->default('operador');
            $table->enum('status', ['ativo', 'inativo'])->default('ativo');
            $table->rememberToken();
            $table->timestamps();
        });

        // Tabela de Resete de Senha
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // TABELA DE SESSÕES 
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};