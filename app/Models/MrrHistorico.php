<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MrrHistorico extends Model
{
    protected $table = '_tb_mrr_historico';

    protected $fillable = [
        'cliente_id',
        'contrato_id',
        'ano',
        'mes',
        'valor_mrr',
        'status',
    ];

    protected $casts = [
        'ano' => 'integer',
        'mes' => 'integer',
        'valor_mrr' => 'decimal:2',
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
