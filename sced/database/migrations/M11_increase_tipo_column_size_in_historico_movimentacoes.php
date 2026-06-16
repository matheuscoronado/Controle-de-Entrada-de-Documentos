<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('historico_movimentacoes', function (Blueprint $table) {
            // Aumentar o tamanho da coluna 'tipo' de 20 para 50 ou mais
            $table->string('tipo', 50)->change();
        });
    }

    public function down()
    {
        Schema::table('historico_movimentacoes', function (Blueprint $table) {
            $table->string('tipo', 20)->change();
        });
    }
};