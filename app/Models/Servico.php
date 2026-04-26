<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    protected $table = '_tb_servicos';

    protected $fillable = [
        'nome',
        'categoria',
        'tipo_receita',
        'valor_base',
        'descricao',
        'ativo',
    ];

    protected $casts = [
        'valor_base' => 'decimal:2',
        'ativo' => 'boolean',
    ];

    public function propostaItens()
    {
        return $this->hasMany(PropostaItem::class, 'servico_id');
    }
}
