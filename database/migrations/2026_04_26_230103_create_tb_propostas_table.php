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
        Schema::create('_tb_propostas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->nullable()->constrained('_tb_leads')->nullOnDelete();
            $table->foreignId('cliente_id')->nullable()->constrained('_tb_clientes')->nullOnDelete();
            $table->string('numero')->unique();
            $table->string('titulo');
            $table->enum('status', ['rascunho', 'enviada', 'negociacao', 'aprovada', 'recusada', 'cancelada'])->default('rascunho');
            $table->decimal('valor_implantacao', 12, 2)->default(0);
            $table->decimal('valor_recorrente', 12, 2)->default(0);
            $table->decimal('valor_total', 12, 2)->default(0);
            $table->date('data_envio')->nullable();
            $table->date('data_validade')->nullable();
            $table->text('escopo')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_tb_propostas');
    }
};
