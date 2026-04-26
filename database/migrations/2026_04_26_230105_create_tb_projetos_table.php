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
        Schema::create('_tb_projetos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->nullable()->constrained('_tb_clientes')->nullOnDelete();
            $table->foreignId('contrato_id')->nullable()->constrained('_tb_contratos')->nullOnDelete();
            $table->string('nome');
            $table->enum('tipo', ['wms', 'erp', 'crm', 'desenvolvimento_sob_demanda']);
            $table->enum('status', ['planejado', 'em_andamento', 'pausado', 'homologacao', 'concluido', 'cancelado'])->default('planejado');
            $table->date('data_inicio')->nullable();
            $table->date('data_prevista_entrega')->nullable();
            $table->date('data_entrega')->nullable();
            $table->string('responsavel')->nullable();
            $table->text('descricao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_tb_projetos');
    }
};
