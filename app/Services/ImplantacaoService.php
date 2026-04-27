<?php

namespace App\Services;

use App\Models\Implantacao;
use Illuminate\Database\Eloquent\Collection;

class ImplantacaoService
{
    public function summary(): array
    {
        return [
            'total' => Implantacao::count(),
            'emAndamento' => Implantacao::where('status', 'em_andamento')->count(),
            'goLive' => Implantacao::where('status', 'go_live')->count(),
            'concluidas' => Implantacao::where('status', 'concluida')->count(),
            'emRisco' => $this->riskQuery()->count(),
            'goLive30Dias' => Implantacao::whereIn('status', ['pendente', 'em_andamento', 'homologacao', 'go_live'])
                ->whereBetween('data_go_live', [today(), today()->addDays(30)])
                ->count(),
        ];
    }

    public function riskList(): Collection
    {
        return $this->riskQuery()
            ->with(['contrato.cliente', 'etapas'])
            ->latest()
            ->limit(8)
            ->get();
    }

    public function progressFor(Implantacao $implantacao): array
    {
        $total = $implantacao->etapas->count();
        $concluidas = $implantacao->etapas->where('status', 'concluida')->count();
        $bloqueadas = $implantacao->etapas->where('status', 'bloqueada')->count();
        $percentual = $total > 0 ? (int) round(($concluidas / $total) * 100) : 0;

        return [
            'total' => $total,
            'concluidas' => $concluidas,
            'bloqueadas' => $bloqueadas,
            'percentual' => $percentual,
            'emRisco' => $bloqueadas > 0 || $this->isGoLiveLate($implantacao),
        ];
    }

    private function riskQuery()
    {
        return Implantacao::whereNotIn('status', ['concluida', 'cancelada'])
            ->where(function ($query) {
                $query->whereDate('data_go_live', '<', today())
                    ->orWhereHas('etapas', fn ($etapas) => $etapas->where('status', 'bloqueada'));
            });
    }

    private function isGoLiveLate(Implantacao $implantacao): bool
    {
        return $implantacao->data_go_live
            && $implantacao->data_go_live->isPast()
            && ! in_array($implantacao->status, ['concluida', 'cancelada'], true);
    }
}
