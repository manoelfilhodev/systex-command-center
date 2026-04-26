<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Implantacao extends Model
{
    protected $table = '_tb_implantacoes';

    protected $fillable = [
        'projeto_id',
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

    public function projeto()
    {
        return $this->belongsTo(Projeto::class, 'projeto_id');
    }

    public function etapas()
    {
        return $this->hasMany(ImplantacaoEtapa::class, 'implantacao_id');
    }
}
