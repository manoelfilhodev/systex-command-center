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
        Schema::create('_tb_proposta_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposta_id')->constrained('_tb_propostas')->cascadeOnDelete();
            $table->foreignId('servico_id')->nullable()->constrained('_tb_servicos')->nullOnDelete();
            $table->string('descricao');
            $table->enum('tipo', ['implantacao', 'mensalidade', 'customizacao', 'suporte', 'integracao', 'consultoria']);
            $table->integer('quantidade')->default(1);
            $table->decimal('valor_unitario', 12, 2)->default(0);
            $table->decimal('valor_total', 12, 2)->default(0);
            $table->boolean('recorrente')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_tb_proposta_itens');
    }
};
