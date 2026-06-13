<?php
// database/migrations/M02b_create_tipo_documentos_table.php
// VERSÃO CORRETA - SEM campos obsoletos (obrigatoriedade, sla_horas, cargo_responsavel)

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
            $table->unsignedBigInteger('departamento_destino_id')->nullable();
            
            // ✅ NOVO: múltiplos cargos como JSON
            $table->json('cargos_responsaveis')->nullable();
            
            $table->timestamps();

            // FK para departamentos
            $table->foreign('departamento_destino_id')
                  ->references('id')
                  ->on('departamentos')
                  ->nullOnDelete();
        });
    }

    public function down(): void 
    { 
        Schema::dropIfExists('tipo_documentos'); 
    }
};