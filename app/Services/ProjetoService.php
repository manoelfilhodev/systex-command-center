<?php

namespace App\Services;

use App\Models\Projeto;
use Illuminate\Database\Eloquent\Collection;

class ProjetoService
{
    public function summary(): array
    {
        return [
            'ativos' => Projeto::whereIn('status', ['planejado', 'em_andamento', 'homologacao'])->count(),
            'emAndamento' => Projeto::where('status', 'em_andamento')->count(),
            'homologacao' => Projeto::where('status', 'homologacao')->count(),
            'atrasados' => Projeto::whereIn('status', ['planejado', 'em_andamento', 'homologacao'])
                ->whereNotNull('data_prevista_entrega')
                ->whereDate('data_prevista_entrega', '<', today())
                ->count(),
            'entregas30Dias' => Projeto::whereIn('status', ['planejado', 'em_andamento', 'homologacao'])
                ->whereBetween('data_prevista_entrega', [today(), today()->addDays(30)])
                ->count(),
        ];
    }

    public function delayed(): Collection
    {
        return Projeto::with(['cliente', 'contrato'])
            ->whereIn('status', ['planejado', 'em_andamento', 'homologacao'])
            ->whereNotNull('data_prevista_entrega')
            ->whereDate('data_prevista_entrega', '<', today())
            ->orderBy('data_prevista_entrega')
            ->limit(8)
            ->get();
    }
}
