<?php

namespace App\Services;

use App\Models\Financeiro;
use Illuminate\Database\Eloquent\Collection;

class FinanceiroService
{
    public function summary(): array
    {
        $receitasPagas = Financeiro::where('tipo', 'receita')
            ->where('status', 'pago')
            ->sum('valor');

        $despesasPagas = Financeiro::where('tipo', 'despesa')
            ->where('status', 'pago')
            ->sum('valor');

        $pendenteReceber = Financeiro::where('tipo', 'receita')
            ->whereIn('status', ['pendente', 'atrasado'])
            ->sum('valor');

        $pendentePagar = Financeiro::where('tipo', 'despesa')
            ->whereIn('status', ['pendente', 'atrasado'])
            ->sum('valor');

        return [
            'receitasPagas' => $receitasPagas,
            'despesasPagas' => $despesasPagas,
            'saldoConfirmado' => $receitasPagas - $despesasPagas,
            'pendenteReceber' => $pendenteReceber,
            'pendentePagar' => $pendentePagar,
            'vencidos' => Financeiro::whereIn('status', ['pendente', 'atrasado'])
                ->whereDate('data_vencimento', '<', today())
                ->count(),
            'fluxo7Dias' => Financeiro::whereIn('status', ['pendente', 'atrasado'])
                ->whereBetween('data_vencimento', [today(), today()->addDays(7)])
                ->sum('valor'),
        ];
    }

    public function overdue(): Collection
    {
        return Financeiro::with(['cliente', 'contrato'])
            ->whereIn('status', ['pendente', 'atrasado'])
            ->whereDate('data_vencimento', '<', today())
            ->orderBy('data_vencimento')
            ->limit(8)
            ->get();
    }

    public function upcoming(int $days = 7): Collection
    {
        return Financeiro::with(['cliente', 'contrato'])
            ->whereIn('status', ['pendente', 'atrasado'])
            ->whereBetween('data_vencimento', [today(), today()->addDays($days)])
            ->orderBy('data_vencimento')
            ->limit(8)
            ->get();
    }
}
