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
        Schema::create('_tb_mrr_historico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->nullable()->constrained('_tb_clientes')->nullOnDelete();
            $table->foreignId('contrato_id')->nullable()->constrained('_tb_contratos')->nullOnDelete();
            $table->year('ano');
            $table->unsignedTinyInteger('mes');
            $table->decimal('valor_mrr', 12, 2)->default(0);
            $table->enum('status', ['previsto', 'confirmado', 'cancelado'])->default('previsto');
            $table->timestamps();

            $table->unique(['cliente_id', 'contrato_id', 'ano', 'mes'], 'mrr_cliente_contrato_mes_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_tb_mrr_historico');
    }
};
