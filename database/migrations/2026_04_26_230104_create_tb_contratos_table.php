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
        Schema::create('_tb_contratos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('_tb_clientes')->cascadeOnDelete();
            $table->foreignId('proposta_id')->nullable()->constrained('_tb_propostas')->nullOnDelete();
            $table->string('numero')->unique();
            $table->enum('tipo', ['projeto_unico', 'recorrente', 'hibrido'])->default('hibrido');
            $table->enum('status', ['ativo', 'suspenso', 'encerrado', 'cancelado'])->default('ativo');
            $table->decimal('valor_implantacao', 12, 2)->default(0);
            $table->decimal('valor_mensal', 12, 2)->default(0);
            $table->date('data_inicio');
            $table->date('data_fim')->nullable();
            $table->string('sla')->nullable();
            $table->string('arquivo_contrato')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_tb_contratos');
    }
};
