<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $table = '_tb_leads';

    protected $fillable = [
        'nome',
        'empresa',
        'email',
        'telefone',
        'origem',
        'status',
        'valor_estimado',
        'proximo_contato',
        'observacoes',
    ];

    protected $casts = [
        'valor_estimado' => 'decimal:2',
        'proximo_contato' => 'date',
    ];

    public function propostas()
    {
        return $this->hasMany(Proposta::class, 'lead_id');
    }
}
