<?php
// ============================================================
// Migration: Expande log_auditorias para auditoria completa
// Registra: status anterior, novo status, campos alterados,
// uploads realizados e contexto da requisição.
// ============================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('log_auditorias', function (Blueprint $table) {
            // Status antes e depois da alteração
            $table->string('status_anterior')->nullable()->after('acao');
            $table->string('status_novo')->nullable()->after('status_anterior');

            // JSON com campos que foram alterados: {"campo": {"de": "x", "para": "y"}}
            $table->json('campos_alterados')->nullable()->after('status_novo');

            // IDs dos arquivos que foram enviados nesta ação
            $table->json('uploads_realizados')->nullable()->after('campos_alterados');

            // Descrição legível da ação (para exibir na tela sem precisar decodificar JSON)
            $table->text('descricao_legivel')->nullable()->after('uploads_realizados');

            // Módulo do sistema: documentos, usuarios, tipos, etc.
            $table->string('modulo')->nullable()->after('descricao_legivel');

            // User-Agent para rastreabilidade
            $table->string('user_agent', 512)->nullable()->after('ip_origem');
        });
    }

    public function down(): void
    {
        Schema::table('log_auditorias', function (Blueprint $table) {
            $table->dropColumn([
                'status_anterior',
                'status_novo',
                'campos_alterados',
                'uploads_realizados',
                'descricao_legivel',
                'modulo',
                'user_agent',
            ]);
        });
    }
};
