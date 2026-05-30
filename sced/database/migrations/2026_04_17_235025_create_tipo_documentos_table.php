<?php
// ============================================================
// Migration: Expande a tabela tipo_documentos para suportar
// a nova arquitetura de Help Desk (Parte 1)
// ============================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tipo_documentos', function (Blueprint $table) {
            // Indicador obrigatório/opcional
            $table->enum('obrigatoriedade', ['obrigatorio', 'opcional'])
                  ->default('opcional')
                  ->after('descricao');

            // Vínculo com departamento de destino
            $table->unsignedBigInteger('departamento_destino_id')
                  ->nullable()
                  ->after('obrigatoriedade');

            // Cargo responsável pelo processamento
            $table->enum('cargo_responsavel', ['N1', 'N2', 'N3'])
                  ->nullable()
                  ->after('departamento_destino_id');

            // Prazo SLA em horas (base para a Parte 2)
            $table->unsignedSmallInteger('sla_horas')
                  ->nullable()
                  ->comment('Prazo máximo em horas para resolução')
                  ->after('cargo_responsavel');

            $table->foreign('departamento_destino_id')
                  ->references('id')
                  ->on('departamentos')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tipo_documentos', function (Blueprint $table) {
            $table->dropForeign(['departamento_destino_id']);
            $table->dropColumn([
                'obrigatoriedade',
                'departamento_destino_id',
                'cargo_responsavel',
                'sla_horas',
            ]);
        });
    }
};
