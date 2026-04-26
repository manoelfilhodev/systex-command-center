<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    protected $table = '_tb_contratos';

    protected $fillable = [
        'cliente_id',
        'proposta_id',
        'numero',
        'tipo',
        'status',
        'valor_implantacao',
        'valor_mensal',
        'data_inicio',
        'data_fim',
        'sla',
        'arquivo_contrato',
        'observacoes',
    ];

    protected $casts = [
        'valor_implantacao' => 'decimal:2',
        'valor_mensal' => 'decimal:2',
        'data_inicio' => 'date',
        'data_fim' => 'date',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function proposta()
    {
        return $this->belongsTo(Proposta::class, 'proposta_id');
    }

    public function projetos()
    {
        return $this->hasMany(Projeto::class, 'contrato_id');
    }

    public function financeiro()
    {
        return $this->hasMany(Financeiro::class, 'contrato_id');
    }

    public function mrrHistorico()
    {
        return $this->hasMany(MrrHistorico::class, 'contrato_id');
    }
}
