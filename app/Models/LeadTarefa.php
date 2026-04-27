<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadTarefa extends Model
{
    protected $table = '_tb_lead_tarefas';

    protected $fillable = [
        'lead_id',
        'user_id',
        'titulo',
        'descricao',
        'prioridade',
        'status',
        'data_vencimento',
        'concluida_em',
    ];

    protected $casts = [
        'data_vencimento' => 'date',
        'concluida_em' => 'datetime',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
