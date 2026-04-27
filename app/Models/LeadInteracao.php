<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadInteracao extends Model
{
    protected $table = '_tb_lead_interacoes';

    protected $fillable = [
        'lead_id',
        'user_id',
        'tipo',
        'titulo',
        'descricao',
        'data_interacao',
    ];

    protected $casts = [
        'data_interacao' => 'datetime',
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
