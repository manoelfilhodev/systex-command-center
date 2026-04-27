<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImplantacaoEtapa extends Model
{
    protected $table = '_tb_implantacao_etapas';

    protected $fillable = [
        'implantacao_id',
        'nome',
        'ordem',
        'status',
        'data_inicio',
        'data_fim',
        'observacoes',
    ];

    public function implantacao()
    {
        return $this->belongsTo(Implantacao::class, 'implantacao_id');
    }
}
