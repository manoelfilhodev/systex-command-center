<?php

namespace App\Services;

use App\Models\Contrato;
use App\Models\MrrHistorico;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MrrService
{
    public function syncContratoForCurrentMonth(Contrato $contrato): void
    {
        if ($contrato->status !== 'ativo' || (float) $contrato->valor_mensal <= 0) {
            MrrHistorico::where('contrato_id', $contrato->id)
                ->where('ano', now()->year)
                ->where('mes', now()->month)
                ->update(['status' => 'cancelado']);

            return;
        }

        MrrHistorico::updateOrCreate(
            [
                'cliente_id' => $contrato->cliente_id,
                'contrato_id' => $contrato->id,
                'ano' => now()->year,
                'mes' => now()->month,
            ],
            [
                'valor_mrr' => $contrato->valor_mensal,
                'status' => 'confirmado',
            ]
        );
    }

    public function currentSummary(): array
    {
        $anoAtual = now()->year;
        $mesAtual = now()->month;

        return [
            'anoAtual' => $anoAtual,
            'mesAtual' => $mesAtual,
            'mrrAtual' => MrrHistorico::where('ano', $anoAtual)
                ->where('mes', $mesAtual)
                ->where('status', 'confirmado')
                ->sum('valor_mrr'),
            'mrrPrevisto' => MrrHistorico::where('ano', $anoAtual)
                ->where('mes', $mesAtual)
                ->whereIn('status', ['previsto', 'confirmado'])
                ->sum('valor_mrr'),
            'contratosAtivos' => Contrato::where('status', 'ativo')->count(),
            'receitaMensalContratada' => Contrato::where('status', 'ativo')->sum('valor_mensal'),
        ];
    }

    public function paginatedHistory(int $perPage = 10): LengthAwarePaginator
    {
        return MrrHistorico::with(['cliente', 'contrato'])
            ->latest()
            ->paginate($perPage);
    }
}
