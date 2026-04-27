<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('_tb_suporte_chamados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->nullable()->constrained('_tb_clientes')->nullOnDelete();
            $table->foreignId('contrato_id')->nullable()->constrained('_tb_contratos')->nullOnDelete();
            $table->string('titulo');
            $table->enum('categoria', ['incidente', 'duvida', 'melhoria', 'integracao', 'infraestrutura', 'outros'])->default('incidente');
            $table->enum('prioridade', ['baixa', 'media', 'alta', 'critica'])->default('media');
            $table->enum('status', ['aberto', 'em_atendimento', 'aguardando_cliente', 'resolvido', 'cancelado'])->default('aberto');
            $table->enum('canal', ['email', 'whatsapp', 'telefone', 'portal', 'interno'])->default('interno');
            $table->dateTime('aberto_em');
            $table->dateTime('prazo_sla')->nullable();
            $table->dateTime('resolvido_em')->nullable();
            $table->string('responsavel')->nullable();
            $table->text('descricao')->nullable();
            $table->text('resolucao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('_tb_suporte_chamados');
    }
};
