<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proposta extends Model
{
    protected $table = '_tb_propostas';

    protected $fillable = [
        'lead_id',
        'cliente_id',
        'numero',
        'titulo',
        'status',
        'valor_implantacao',
        'valor_recorrente',
        'valor_total',
        'data_envio',
        'data_validade',
        'escopo',
        'observacoes',
    ];

    protected $casts = [
        'valor_implantacao' => 'decimal:2',
        'valor_recorrente' => 'decimal:2',
        'valor_total' => 'decimal:2',
        'data_envio' => 'date',
        'data_validade' => 'date',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function itens()
    {
        return $this->hasMany(PropostaItem::class, 'proposta_id');
    }

    public function contrato()
    {
        return $this->hasOne(Contrato::class, 'proposta_id');
    }
}
