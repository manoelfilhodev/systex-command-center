<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = '_tb_clientes';

    protected $fillable = [
        'razao_social',
        'nome_fantasia',
        'cnpj',
        'email',
        'telefone',
        'responsavel',
        'segmento',
        'cidade',
        'estado',
        'status',
        'observacoes',
    ];

    public function propostas()
    {
        return $this->hasMany(Proposta::class, 'cliente_id');
    }

    public function contratos()
    {
        return $this->hasMany(Contrato::class, 'cliente_id');
    }

    public function projetos()
    {
        return $this->hasMany(Projeto::class, 'cliente_id');
    }

    public function financeiros()
    {
        return $this->hasMany(Financeiro::class, 'cliente_id');
    }
}
