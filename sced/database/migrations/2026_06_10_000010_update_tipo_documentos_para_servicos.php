<?php
// database/migrations/2026_06_10_000010_update_tipo_documentos_para_servicos.php
//
// O que faz:
//  1. Adiciona coluna 'cargos_responsaveis' (JSON) para suportar múltiplos cargos
//  2. Remove 'obrigatoriedade' (substituída pela relação com documento_tipos)
//  3. Remove 'sla_horas'      (removida completamente da regra de negócio)
//  4. Remove 'cargo_responsavel' (substituída pelo JSON cargos_responsaveis)
//
// IMPORTANTE: rode DEPOIS de ter executado M07_create_documento_tipos_table
// que já criou a tabela pivot 'tipo_documento_documento_tipo'.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tipo_documentos', function (Blueprint $table) {
            // Novo: múltiplos cargos como JSON ['N1','N2','N3']
            $table->json('cargos_responsaveis')->nullable()->after('departamento_destino_id');
        });

        // Remove campos descontinuados (com segurança, ignorando se não existirem)
        Schema::table('tipo_documentos', function (Blueprint $table) {
            if (Schema::hasColumn('tipo_documentos', 'obrigatoriedade')) {
                $table->dropColumn('obrigatoriedade');
            }
            if (Schema::hasColumn('tipo_documentos', 'sla_horas')) {
                $table->dropColumn('sla_horas');
            }
            if (Schema::hasColumn('tipo_documentos', 'cargo_responsavel')) {
                $table->dropColumn('cargo_responsavel');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tipo_documentos', function (Blueprint $table) {
            $table->dropColumn('cargos_responsaveis');
            $table->enum('obrigatoriedade', ['obrigatorio', 'opcional'])->default('opcional')->nullable();
            $table->integer('sla_horas')->nullable();
            $table->string('cargo_responsavel', 10)->nullable();
        });
    }
};
