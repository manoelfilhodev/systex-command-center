<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('_tb_lead_tarefas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('_tb_leads')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->enum('prioridade', ['baixa', 'media', 'alta'])->default('media');
            $table->enum('status', ['pendente', 'concluida', 'cancelada'])->default('pendente');
            $table->date('data_vencimento');
            $table->timestamp('concluida_em')->nullable();
            $table->timestamps();

            $table->index(['lead_id', 'status', 'data_vencimento']);
            $table->index(['user_id', 'status', 'data_vencimento']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('_tb_lead_tarefas');
    }
};
