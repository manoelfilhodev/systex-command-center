<?php

namespace App\Services;

use App\Models\Contrato;
use Illuminate\Support\Collection;

class ContratoService
{
    public function summary(): array
    {
        $contratosAtivos = Contrato::where('status', 'ativo');

        return [
            'ativos' => (clone $contratosAtivos)->count(),
            'mrrAtivo' => (clone $contratosAtivos)->sum('valor_mensal'),
            'receitaAnualizada' => (clone $contratosAtivos)->sum('valor_mensal') * 12,
            'vencidosAtivos' => Contrato::where('status', 'ativo')
                ->whereNotNull('data_fim')
                ->whereDate('data_fim', '<', today())
                ->count(),
            'vencendo30Dias' => Contrato::where('status', 'ativo')
                ->whereNotNull('data_fim')
                ->whereBetween('data_fim', [today(), today()->addDays(30)])
                ->count(),
        ];
    }

    public function expiringContracts(int $days = 30): Collection
    {
        return Contrato::with(['cliente', 'proposta'])
            ->where('status', 'ativo')
            ->whereNotNull('data_fim')
            ->whereBetween('data_fim', [today(), today()->addDays($days)])
            ->orderBy('data_fim')
            ->get();
    }
}
