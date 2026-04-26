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
        Schema::create('_tb_implantacao_etapas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('implantacao_id')->constrained('_tb_implantacoes')->cascadeOnDelete();
            $table->string('nome');
            $table->integer('ordem')->default(1);
            $table->enum('status', ['pendente', 'em_andamento', 'concluida', 'bloqueada'])->default('pendente');
            $table->date('data_inicio')->nullable();
            $table->date('data_fim')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_tb_implantacao_etapas');
    }
};
