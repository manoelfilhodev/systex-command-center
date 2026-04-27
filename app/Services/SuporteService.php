<?php

namespace App\Services;

use App\Models\SuporteChamado;
use Illuminate\Database\Eloquent\Collection;

class SuporteService
{
    public function summary(): array
    {
        return [
            'abertos' => SuporteChamado::whereIn('status', ['aberto', 'em_atendimento', 'aguardando_cliente'])->count(),
            'criticos' => SuporteChamado::where('prioridade', 'critica')
                ->whereIn('status', ['aberto', 'em_atendimento', 'aguardando_cliente'])
                ->count(),
            'slaVencido' => $this->overdueQuery()->count(),
            'sla24h' => SuporteChamado::whereIn('status', ['aberto', 'em_atendimento'])
                ->whereBetween('prazo_sla', [now(), now()->addDay()])
                ->count(),
            'resolvidosMes' => SuporteChamado::where('status', 'resolvido')
                ->whereMonth('resolvido_em', now()->month)
                ->whereYear('resolvido_em', now()->year)
                ->count(),
        ];
    }

    public function overdue(): Collection
    {
        return $this->overdueQuery()
            ->with(['cliente', 'contrato'])
            ->orderBy('prazo_sla')
            ->limit(8)
            ->get();
    }

    private function overdueQuery()
    {
        return SuporteChamado::whereIn('status', ['aberto', 'em_atendimento'])
            ->whereNotNull('prazo_sla')
            ->where('prazo_sla', '<', now());
    }
}
