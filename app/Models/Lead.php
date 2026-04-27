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

    public function interacoes()
    {
        return $this->hasMany(LeadInteracao::class, 'lead_id')
            ->latest('data_interacao');
    }

    public function tarefas()
    {
        return $this->hasMany(LeadTarefa::class, 'lead_id')
            ->orderByRaw("case when status = 'pendente' then 0 else 1 end")
            ->orderBy('data_vencimento');
    }
}
