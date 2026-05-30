<?php
// ============================================================
// Migration: Parte 2 — Fluxo do Novo Processo
//   arquivo_anexos: adiciona tipo_anexo + status_validacao
//   documentos: setor_destino vira FK opcional p/ departamentos
//               assunto torna-se nullable (removido do fluxo)
// ============================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        // ── 1. Expande arquivo_anexos ────────────────────────
        Schema::table('arquivo_anexos', function (Blueprint $table) {

            $table->enum('tipo_anexo', [
                'rg', 'cpf', 'contrato',
                'comprovante_residencia', 'comprovante_renda',
                'certidao', 'laudo', 'outros',
            ])->default('outros')->after('usuario_id');

            $table->enum('status_validacao', ['pendente', 'aprovado', 'rejeitado'])
                  ->default('pendente')->after('tipo_anexo');

            $table->text('observacao_validacao')->nullable()->after('status_validacao');
            $table->unsignedBigInteger('validado_por')->nullable()->after('observacao_validacao');
            $table->timestamp('validado_em')->nullable()->after('validado_por');

            $table->foreign('validado_por')->references('id')->on('users')->nullOnDelete();
        });

        // ── 2. Expande documentos ────────────────────────────
        Schema::table('documentos', function (Blueprint $table) {
            $table->unsignedBigInteger('departamento_destino_id')->nullable()->after('setor_destino');
            $table->foreign('departamento_destino_id')->references('id')->on('departamentos')->nullOnDelete();
            // Assunto removido do novo fluxo — nullable para não perder dados históricos
            $table->string('assunto')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('arquivo_anexos', function (Blueprint $table) {
            $table->dropForeign(['validado_por']);
            $table->dropColumn(['tipo_anexo', 'status_validacao', 'observacao_validacao', 'validado_por', 'validado_em']);
        });
        Schema::table('documentos', function (Blueprint $table) {
            $table->dropForeign(['departamento_destino_id']);
            $table->dropColumn('departamento_destino_id');
            $table->string('assunto')->nullable(false)->change();
        });
    }
};
