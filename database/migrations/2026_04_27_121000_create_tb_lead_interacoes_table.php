<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('_tb_lead_interacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('_tb_leads')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('tipo', ['contato', 'reuniao', 'diagnostico', 'proposta', 'negociacao', 'observacao'])->default('observacao');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->dateTime('data_interacao');
            $table->timestamps();

            $table->index(['lead_id', 'data_interacao']);
            $table->index(['tipo', 'data_interacao']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('_tb_lead_interacoes');
    }
};
