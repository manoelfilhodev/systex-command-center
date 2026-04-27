<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Implantacao extends Model
{
    protected $table = '_tb_implantacoes';

    protected $fillable = [
        'contrato_id',
        'status',
        'data_inicio',
        'data_go_live',
        'responsavel',
        'observacoes',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_go_live' => 'date',
    ];

    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }

    public function etapas()
    {
        return $this->hasMany(ImplantacaoEtapa::class, 'implantacao_id')
            ->orderBy('ordem');
    }

    public function aditivos()
    {
        return $this->hasMany(ContratoAditivo::class, 'implantacao_id');
    }
}
