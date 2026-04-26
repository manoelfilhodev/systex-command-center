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
        Schema::create('_tb_financeiro', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->nullable()->constrained('_tb_clientes')->nullOnDelete();
            $table->foreignId('contrato_id')->nullable()->constrained('_tb_contratos')->nullOnDelete();
            $table->enum('tipo', ['receita', 'despesa'])->default('receita');
            $table->enum('categoria', ['implantacao', 'mensalidade', 'suporte', 'customizacao', 'consultoria', 'outros'])->default('outros');
            $table->string('descricao');
            $table->decimal('valor', 12, 2);
            $table->date('data_vencimento');
            $table->date('data_pagamento')->nullable();
            $table->enum('status', ['pendente', 'pago', 'atrasado', 'cancelado'])->default('pendente');
            $table->boolean('recorrente')->default(false);
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_tb_financeiro');
    }
};
