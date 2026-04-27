<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditoriaEvento extends Model
{
    protected $table = '_tb_auditoria_eventos';

    protected $fillable = [
        'user_id',
        'modulo',
        'acao',
        'auditable_type',
        'auditable_id',
        'titulo',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
