<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropostaItem extends Model
{
    protected $table = '_tb_proposta_itens';

    protected $fillable = [
        'proposta_id',
        'servico_id',
        'descricao',
        'tipo',
        'quantidade',
        'valor_unitario',
        'valor_total',
        'recorrente',
    ];

    protected $casts = [
        'quantidade' => 'integer',
        'valor_unitario' => 'decimal:2',
        'valor_total' => 'decimal:2',
        'recorrente' => 'boolean',
    ];

    public function proposta()
    {
        return $this->belongsTo(Proposta::class, 'proposta_id');
    }

    public function servico()
    {
        return $this->belongsTo(Servico::class, 'servico_id');
    }
}
