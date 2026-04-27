<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContratoAditivo extends Model
{
    use SoftDeletes;

    protected $table = '_tb_contrato_aditivos';

    protected $fillable = [
        'contrato_id',
        'implantacao_id',
        'titulo',
        'descricao',
        'valor_aditivo',
        'tipo',
        'status',
        'data_inicio',
        'data_fim',
        'responsavel',
        'observacoes',
    ];

    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }

    public function implantacao()
    {
        return $this->belongsTo(Implantacao::class, 'implantacao_id');
    }
}
