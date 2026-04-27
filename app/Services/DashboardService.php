<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\Contrato;
use App\Models\Financeiro;
use App\Models\Implantacao;
use App\Models\Lead;
use App\Models\Projeto;
use App\Models\Proposta;
use App\Models\SuporteChamado;

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
                'trend' => Lead::where('status', 'novo')->count().' novos',
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
                'trend' => Cliente::where('status', 'ativo')->count().' clientes ativos',
            ],
            [
                'label' => 'Renovações Próximas',
                'value' => Contrato::where('status', 'ativo')
                    ->whereNotNull('data_fim')
                    ->whereBetween('data_fim', [today(), today()->addDays(30)])
                    ->count(),
                'description' => 'Contratos vencendo em até 30 dias',
                'trend' => 'Atenção THEMIS',
            ],
            [
                'label' => 'Projetos em Implantação',
                'value' => Implantacao::whereIn('status', ['pendente', 'em_andamento', 'homologacao', 'go_live'])->count(),
                'description' => 'Implantações em curso',
                'trend' => Projeto::whereIn('status', ['planejado', 'em_andamento', 'homologacao'])->count().' projetos',
            ],
            [
                'label' => 'SLA Vencido',
                'value' => SuporteChamado::whereIn('status', ['aberto', 'em_atendimento'])
                    ->whereNotNull('prazo_sla')
                    ->where('prazo_sla', '<', now())
                    ->count(),
                'description' => 'Chamados com prazo ultrapassado',
                'trend' => SuporteChamado::where('prioridade', 'critica')
                    ->whereIn('status', ['aberto', 'em_atendimento', 'aguardando_cliente'])
                    ->count().' críticos',
            ],
        ];
    }

    public function getExecutiveHealth(): array
    {
        $leadTotal = Lead::count();
        $leadConvertidos = Lead::where('status', 'convertido')->count();

        $propostasAbertas = Proposta::whereIn('status', ['rascunho', 'enviada', 'negociacao'])->count();
        $propostasGanhas = Proposta::where('status', 'aprovada')->count();

        $financeiroTotal = Financeiro::whereIn('status', ['pendente', 'atrasado', 'pago'])->count();
        $financeiroPago = Financeiro::where('status', 'pago')->count();

        $implantacoesTotal = Implantacao::whereNotIn('status', ['cancelada'])->count();
        $implantacoesConcluidas = Implantacao::where('status', 'concluida')->count();

        $suporteTotal = SuporteChamado::whereNotIn('status', ['cancelado'])->count();
        $suporteResolvido = SuporteChamado::where('status', 'resolvido')->count();

        return [
            [
                'area' => 'Comercial',
                'score' => $this->percentage($leadConvertidos, $leadTotal),
                'signal' => $propostasAbertas.' propostas abertas',
                'description' => 'Conversão de leads e avanço de propostas',
            ],
            [
                'area' => 'Financeiro',
                'score' => $this->percentage($financeiroPago, $financeiroTotal),
                'signal' => $this->formatCurrency(Financeiro::where('tipo', 'receita')->whereIn('status', ['pendente', 'atrasado'])->sum('valor')).' a receber',
                'description' => 'Liquidação financeira e previsibilidade de caixa',
            ],
            [
                'area' => 'Operação',
                'score' => $this->percentage($implantacoesConcluidas, $implantacoesTotal),
                'signal' => Projeto::whereIn('status', ['planejado', 'em_andamento', 'homologacao'])->count().' projetos ativos',
                'description' => 'Entrega, implantação e go-live',
            ],
            [
                'area' => 'Suporte',
                'score' => $this->percentage($suporteResolvido, $suporteTotal),
                'signal' => SuporteChamado::whereIn('status', ['aberto', 'em_atendimento', 'aguardando_cliente'])->count().' chamados abertos',
                'description' => 'Sustentação, SLA e atendimento',
            ],
            [
                'area' => 'Receita Recorrente',
                'score' => Contrato::where('status', 'ativo')->count() > 0 ? 100 : 0,
                'signal' => $this->formatCurrency(Contrato::where('status', 'ativo')->sum('valor_mensal')).' MRR',
                'description' => 'Base ativa e contratos recorrentes',
            ],
        ];
    }

    public function getExecutiveAlerts(): array
    {
        return [
            [
                'label' => 'Contratos vencendo',
                'value' => Contrato::where('status', 'ativo')
                    ->whereNotNull('data_fim')
                    ->whereBetween('data_fim', [today(), today()->addDays(30)])
                    ->count(),
                'detail' => 'Renovações nos próximos 30 dias',
                'agent' => 'THEMIS',
            ],
            [
                'label' => 'Financeiro vencido',
                'value' => Financeiro::whereIn('status', ['pendente', 'atrasado'])
                    ->whereDate('data_vencimento', '<', today())
                    ->count(),
                'detail' => 'Receitas ou despesas com prazo ultrapassado',
                'agent' => 'CRONOS',
            ],
            [
                'label' => 'Implantações em risco',
                'value' => Implantacao::whereNotIn('status', ['concluida', 'cancelada'])
                    ->where(function ($query) {
                        $query->whereDate('data_go_live', '<', today())
                            ->orWhereHas('etapas', fn ($etapas) => $etapas->where('status', 'bloqueada'));
                    })
                    ->count(),
                'detail' => 'Go-live vencido ou etapa bloqueada',
                'agent' => 'ORION',
            ],
            [
                'label' => 'SLA vencido',
                'value' => SuporteChamado::whereIn('status', ['aberto', 'em_atendimento'])
                    ->whereNotNull('prazo_sla')
                    ->where('prazo_sla', '<', now())
                    ->count(),
                'detail' => 'Chamados abertos fora do prazo',
                'agent' => 'TITAN',
            ],
        ];
    }

    public function getRevenueSnapshot(): array
    {
        $mrr = Contrato::where('status', 'ativo')->sum('valor_mensal');
        $receitaPendente = Financeiro::where('tipo', 'receita')
            ->whereIn('status', ['pendente', 'atrasado'])
            ->sum('valor');
        $despesaPendente = Financeiro::where('tipo', 'despesa')
            ->whereIn('status', ['pendente', 'atrasado'])
            ->sum('valor');

        return [
            'mrr' => $this->formatCurrency($mrr),
            'arr' => $this->formatCurrency($mrr * 12),
            'receitaPendente' => $this->formatCurrency($receitaPendente),
            'despesaPendente' => $this->formatCurrency($despesaPendente),
            'saldoPrevisto' => $this->formatCurrency($receitaPendente - $despesaPendente),
        ];
    }

    private function formatCurrency(float|int|string|null $value): string
    {
        return 'R$ '.number_format((float) $value, 2, ',', '.');
    }

    private function percentage(int|float $part, int|float $total): int
    {
        if ((float) $total <= 0) {
            return 0;
        }

        return (int) round(((float) $part / (float) $total) * 100);
    }
}
