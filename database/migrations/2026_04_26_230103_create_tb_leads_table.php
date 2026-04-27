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
        Schema::create('_tb_leads', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('empresa')->nullable();
            $table->string('email')->nullable();
            $table->string('telefone', 30)->nullable();
            $table->string('origem')->nullable();
            $table->enum('status', [
                'novo',
                'contato_feito',
                'diagnostico',
                'proposta_enviada',
                'negociacao',
                'convertido',
                'perdido',
            ])->default('novo');
            $table->decimal('valor_estimado', 12, 2)->default(0);
            $table->date('proximo_contato')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_tb_leads');
    }
};
