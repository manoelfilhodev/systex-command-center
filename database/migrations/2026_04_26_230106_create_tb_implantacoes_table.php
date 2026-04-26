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
        Schema::create('_tb_implantacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projeto_id')->constrained('_tb_projetos')->cascadeOnDelete();
            $table->enum('status', ['nao_iniciada', 'em_andamento', 'em_risco', 'concluida', 'cancelada'])->default('nao_iniciada');
            $table->date('data_inicio')->nullable();
            $table->date('data_go_live')->nullable();
            $table->string('responsavel')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_tb_implantacoes');
    }
};
