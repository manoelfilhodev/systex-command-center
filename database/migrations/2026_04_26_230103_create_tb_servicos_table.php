<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('_tb_servicos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->enum('categoria', [
                'wms',
                'erp',
                'crm',
                'desenvolvimento_sob_demanda',
                'implantacao',
                'suporte',
                'consultoria',
                'integracao'
            ]);
            $table->enum('tipo_receita', ['unica', 'recorrente', 'hibrida'])->default('hibrida');
            $table->decimal('valor_base', 12, 2)->default(0);
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_servicos');
    }
};
