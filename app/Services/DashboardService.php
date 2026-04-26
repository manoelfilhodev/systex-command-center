<?php

namespace App\Services;

class DashboardService
{
    public function getExecutiveCards(): array
    {
        return [
            [
                'label' => 'MRR Atual',
                'value' => 'R$ 18.500',
                'description' => 'Receita recorrente mensal',
                'trend' => '+8,4%',
            ],
            [
                'label' => 'Leads Ativos',
                'value' => '24',
                'description' => 'Oportunidades em andamento',
                'trend' => '+6',
            ],
            [
                'label' => 'Propostas em Aberto',
                'value' => '9',
                'description' => 'Aguardando retorno comercial',
                'trend' => 'R$ 42.000',
            ],
            [
                'label' => 'Contratos Ativos',
                'value' => '5',
                'description' => 'Clientes com contrato vigente',
                'trend' => '100%',
            ],
            [
                'label' => 'Projetos em Implantação',
                'value' => '3',
                'description' => 'Projetos em fase inicial',
                'trend' => '2 críticos',
            ],
            [
                'label' => 'Chamados Abertos',
                'value' => '14',
                'description' => 'Suporte e pós-venda',
                'trend' => '2 urgentes',
            ],
        ];
    }
}
