<?php

namespace App\Services;

use App\Models\AuditoriaEvento;
use Illuminate\Database\Eloquent\Model;

class AuditoriaService
{
    public function registrar(
        string $modulo,
        string $acao,
        ?Model $auditable = null,
        ?string $titulo = null,
        array $metadata = []
    ): AuditoriaEvento {
        return AuditoriaEvento::create([
            'user_id' => auth()->id(),
            'modulo' => $modulo,
            'acao' => $acao,
            'auditable_type' => $auditable ? $auditable::class : null,
            'auditable_id' => $auditable?->getKey(),
            'titulo' => $titulo,
            'metadata' => $metadata,
        ]);
    }

    public function recentes(int $limit = 30)
    {
        return AuditoriaEvento::with('user')
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function summary(): array
    {
        return [
            'total' => AuditoriaEvento::count(),
            'hoje' => AuditoriaEvento::whereDate('created_at', today())->count(),
            'contratos' => AuditoriaEvento::where('modulo', 'contratos')->count(),
            'financeiro' => AuditoriaEvento::where('modulo', 'financeiro')->count(),
            'operacao' => AuditoriaEvento::whereIn('modulo', ['implantacoes', 'suporte'])->count(),
        ];
    }
}
