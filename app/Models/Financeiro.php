<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Financeiro extends Model
{
    protected $table = '_tb_financeiro';

    protected $fillable = [
        'cliente_id',
        'contrato_id',
        'tipo',
        'categoria',
        'descricao',
        'valor',
        'data_vencimento',
        'data_pagamento',
        'status',
        'recorrente',
        'observacoes',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data_vencimento' => 'date',
        'data_pagamento' => 'date',
        'recorrente' => 'boolean',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }
}
