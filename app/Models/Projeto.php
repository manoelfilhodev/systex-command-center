<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Projeto extends Model
{
    protected $table = '_tb_projetos';

    protected $fillable = [
        'cliente_id',
        'contrato_id',
        'nome',
        'tipo',
        'status',
        'data_inicio',
        'data_prevista_entrega',
        'data_entrega',
        'responsavel',
        'descricao',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_prevista_entrega' => 'date',
        'data_entrega' => 'date',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }

    public function implantacao()
    {
        return $this->hasOne(Implantacao::class, 'projeto_id');
    }
}
