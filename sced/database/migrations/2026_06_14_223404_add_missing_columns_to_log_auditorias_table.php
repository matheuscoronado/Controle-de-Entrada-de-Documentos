<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('log_auditorias', function (Blueprint $table) {
            // Verificar e adicionar colunas que NÃO EXISTEM
            
            // Coluna created_at
            if (!Schema::hasColumn('log_auditorias', 'created_at')) {
                $table->timestamp('created_at')->nullable()->after('data_hora');
            }
            
            // Coluna updated_at (verificar se não existe)
            if (!Schema::hasColumn('log_auditorias', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            }
        });
    }

    public function down()
    {
        Schema::table('log_auditorias', function (Blueprint $table) {
            if (Schema::hasColumn('log_auditorias', 'created_at')) {
                $table->dropColumn('created_at');
            }
            if (Schema::hasColumn('log_auditorias', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
        });
    }
};