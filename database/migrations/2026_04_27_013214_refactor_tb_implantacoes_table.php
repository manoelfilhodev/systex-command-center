<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('_tb_implantacoes', function (Blueprint $table) {
            // Remove FK antiga
            $table->dropForeign(['projeto_id']);

            // Remove coluna antiga
            $table->dropColumn('projeto_id');
        });

        Schema::table('_tb_implantacoes', function (Blueprint $table) {
            // Nova relação correta
            $table->unsignedBigInteger('contrato_id')->after('id');

            $table->foreign('contrato_id')
                ->references('id')
                ->on('_tb_contratos')
                ->onDelete('cascade');
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("
                ALTER TABLE _tb_implantacoes
                MODIFY COLUMN status ENUM(
                    'pendente',
                    'em_andamento',
                    'homologacao',
                    'go_live',
                    'concluida',
                    'pausada',
                    'cancelada'
                ) NOT NULL DEFAULT 'pendente'
            ");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("
                ALTER TABLE _tb_implantacoes
                MODIFY COLUMN status ENUM(
                    'nao_iniciada',
                    'em_andamento',
                    'em_risco',
                    'concluida',
                    'cancelada'
                ) NOT NULL DEFAULT 'nao_iniciada'
            ");
        }

        Schema::table('_tb_implantacoes', function (Blueprint $table) {
            $table->dropForeign(['contrato_id']);
            $table->dropColumn('contrato_id');
        });

        Schema::table('_tb_implantacoes', function (Blueprint $table) {
            $table->unsignedBigInteger('projeto_id')->after('id');

            $table->foreign('projeto_id')
                ->references('id')
                ->on('_tb_projetos')
                ->onDelete('cascade');
        });
    }
};
