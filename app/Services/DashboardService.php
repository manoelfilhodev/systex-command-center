<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\Contrato;
use App\Models\Financeiro;
use App\Models\Implantacao;
use App\Models\Lead;
use App\Models\Proposta;
use App\Models\Projeto;

class DashboardService
{
    public function getExecutiveCards(): array
    {
        $mrrAtual = Contrato::where('status', 'ativo')->sum('valor_mensal');

        $receitaPrevista = Financeiro::where('tipo', 'receita')
            ->whereIn('status', ['pendente', 'atrasado'])
            ->sum('valor');

        return [
            [
                'label' => 'MRR Atual',
                'value' => $this->formatCurrency($mrrAtual),
                'description' => 'Contratos ativos com mensalidade',
                'trend' => 'Receita recorrente',
            ],
            [
                'label' => 'Receita Prevista',
                'value' => $this->formatCurrency($receitaPrevista),
                'description' => 'Receitas pendentes e atrasadas',
                'trend' => 'Pipeline financeiro',
            ],
            [
                'label' => 'Leads Ativos',
                'value' => Lead::whereNotIn('status', ['convertido', 'perdido'])->count(),
                'description' => 'Oportunidades em andamento',
                'trend' => Lead::where('status', 'novo')->count() . ' novos',
            ],
            [
                'label' => 'Propostas em Aberto',
                'value' => Proposta::whereIn('status', ['rascunho', 'enviada', 'negociacao'])->count(),
                'description' => 'Aguardando avanço comercial',
                'trend' => $this->formatCurrency(
                    Proposta::whereIn('status', ['enviada', 'negociacao'])->sum('valor_total')
                ),
            ],
            [
                'label' => 'Contratos Ativos',
                'value' => Contrato::where('status', 'ativo')->count(),
                'description' => 'Contratos vigentes',
                'trend' => Cliente::where('status', 'ativo')->count() . ' clientes ativos',
            ],
            [
                'label' => 'Projetos em Implantação',
                'value' => Implantacao::whereIn('status', ['nao_iniciada', 'em_andamento', 'em_risco'])->count(),
                'description' => 'Implantações em curso',
                'trend' => Projeto::whereIn('status', ['planejado', 'em_andamento', 'homologacao'])->count() . ' projetos',
            ],
        ];
    }

    private function formatCurrency(float|int|string|null $value): string
    {
        return 'R$ ' . number_format((float) $value, 2, ',', '.');
    }
}
