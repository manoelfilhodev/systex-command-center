<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\LeadTarefa;
use Illuminate\Support\Collection;

class CommercialPipelineService
{
    public const STAGES = [
        'novo' => 'Novo',
        'contato_feito' => 'Contato feito',
        'diagnostico' => 'Diagnóstico',
        'proposta_enviada' => 'Proposta enviada',
        'negociacao' => 'Negociação',
        'convertido' => 'Convertido',
        'perdido' => 'Perdido',
    ];

    public static function statusKeys(): array
    {
        return array_keys(self::STAGES);
    }

    public function pipeline(): array
    {
        $leadsByStatus = Lead::query()
            ->orderByRaw('proximo_contato is null')
            ->orderBy('proximo_contato')
            ->latest()
            ->get()
            ->groupBy('status');

        return collect(self::STAGES)
            ->map(fn (string $label, string $status) => $this->stagePayload(
                $status,
                $label,
                $leadsByStatus->get($status, collect())
            ))
            ->values()
            ->all();
    }

    public function summary(): array
    {
        return [
            'totalLeads' => Lead::count(),
            'leadsAbertos' => Lead::whereNotIn('status', ['convertido', 'perdido'])->count(),
            'valorAberto' => Lead::whereNotIn('status', ['convertido', 'perdido'])->sum('valor_estimado'),
            'proximosContatos' => Lead::whereNotNull('proximo_contato')
                ->whereNotIn('status', ['convertido', 'perdido'])
                ->whereDate('proximo_contato', '>=', today())
                ->count(),
            'tarefasVencidas' => LeadTarefa::where('status', 'pendente')
                ->whereDate('data_vencimento', '<', today())
                ->count(),
            'tarefasHoje' => LeadTarefa::where('status', 'pendente')
                ->whereDate('data_vencimento', today())
                ->count(),
        ];
    }

    public function taskAlerts(): array
    {
        return [
            'vencidas' => LeadTarefa::with('lead')
                ->where('status', 'pendente')
                ->whereDate('data_vencimento', '<', today())
                ->orderBy('data_vencimento')
                ->limit(8)
                ->get(),
            'hoje' => LeadTarefa::with('lead')
                ->where('status', 'pendente')
                ->whereDate('data_vencimento', today())
                ->orderBy('prioridade')
                ->limit(8)
                ->get(),
        ];
    }

    private function stagePayload(string $status, string $label, Collection $leads): array
    {
        return [
            'status' => $status,
            'label' => $label,
            'count' => $leads->count(),
            'value' => $leads->sum('valor_estimado'),
            'leads' => $leads,
        ];
    }
}
