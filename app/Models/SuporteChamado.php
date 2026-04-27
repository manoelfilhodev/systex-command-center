<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuporteChamado extends Model
{
    protected $table = '_tb_suporte_chamados';

    protected $fillable = [
        'cliente_id',
        'contrato_id',
        'titulo',
        'categoria',
        'prioridade',
        'status',
        'canal',
        'aberto_em',
        'prazo_sla',
        'resolvido_em',
        'responsavel',
        'descricao',
        'resolucao',
    ];

    protected $casts = [
        'aberto_em' => 'datetime',
        'prazo_sla' => 'datetime',
        'resolvido_em' => 'datetime',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }
}
