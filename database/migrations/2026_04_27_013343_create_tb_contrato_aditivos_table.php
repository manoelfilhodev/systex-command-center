<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('_tb_contrato_aditivos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('contrato_id')
                ->constrained('_tb_contratos')
                ->cascadeOnDelete();

            $table->foreignId('implantacao_id')
                ->nullable()
                ->constrained('_tb_implantacoes')
                ->nullOnDelete();

            $table->string('titulo');
            $table->text('descricao')->nullable();

            $table->decimal('valor_aditivo', 12, 2)->default(0);

            $table->enum('tipo', [
                'expansao_modulo',
                'novo_usuario',
                'implantacao_extra',
                'consultoria',
                'suporte_premium',
                'customizacao',
                'treinamento_extra',
                'outro',
            ])->default('outro');

            $table->enum('status', [
                'pendente',
                'aprovado',
                'em_execucao',
                'concluido',
                'cancelado',
            ])->default('pendente');

            $table->date('data_inicio')->nullable();
            $table->date('data_fim')->nullable();

            $table->string('responsavel')->nullable();
            $table->text('observacoes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['contrato_id', 'status']);
            $table->index(['implantacao_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('_tb_contrato_aditivos');
    }
};
