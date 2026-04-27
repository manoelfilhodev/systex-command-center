<?php

namespace Database\Seeders;

use App\Models\AuditoriaEvento;
use App\Models\Cliente;
use App\Models\Contrato;
use App\Models\Financeiro;
use App\Models\Implantacao;
use App\Models\ImplantacaoEtapa;
use App\Models\Lead;
use App\Models\LeadInteracao;
use App\Models\LeadTarefa;
use App\Models\MrrHistorico;
use App\Models\Projeto;
use App\Models\Proposta;
use App\Models\PropostaItem;
use App\Models\Servico;
use App\Models\SuporteChamado;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SystexDemoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'demo.admin@systex.com.br'],
            [
                'name' => 'Admin Demo Systex',
                'role' => 'admin',
                'password' => Hash::make(env('SYSTEX_DEMO_PASSWORD', 'systex-demo')),
            ]
        );

        $lead = Lead::updateOrCreate(
            ['email' => 'operacoes@logalpha.com.br'],
            [
                'nome' => 'Carla Menezes',
                'empresa' => 'LogAlpha Operacoes Integradas',
                'telefone' => '11988887777',
                'origem' => 'Indicacao',
                'status' => 'negociacao',
                'valor_estimado' => 84000,
                'proximo_contato' => today()->addDays(2),
                'observacoes' => 'Oportunidade WMS com implantacao e sustentacao mensal.',
            ]
        );

        LeadInteracao::updateOrCreate(
            ['lead_id' => $lead->id, 'titulo' => 'Diagnostico operacional realizado'],
            [
                'user_id' => $admin->id,
                'tipo' => 'diagnostico',
                'data_interacao' => now()->subDays(3),
            ]
        );

        LeadTarefa::updateOrCreate(
            ['lead_id' => $lead->id, 'titulo' => 'Validar proposta com diretoria'],
            [
                'user_id' => $admin->id,
                'prioridade' => 'alta',
                'status' => 'pendente',
                'data_vencimento' => today()->addDay(),
            ]
        );

        $cliente = Cliente::updateOrCreate(
            ['cnpj' => '12345678000190'],
            [
                'razao_social' => 'LogAlpha Operacoes Integradas Ltda',
                'nome_fantasia' => 'LogAlpha',
                'email' => 'financeiro@logalpha.com.br',
                'telefone' => '1133334444',
                'responsavel' => 'Carla Menezes',
                'segmento' => 'Logistica',
                'cidade' => 'Sao Paulo',
                'estado' => 'SP',
                'status' => 'ativo',
                'observacoes' => 'Cliente demo para validacao executiva do Command Center.',
            ]
        );

        $proposta = Proposta::updateOrCreate(
            ['numero' => 'PROP-DEMO-001'],
            [
                'lead_id' => $lead->id,
                'cliente_id' => $cliente->id,
                'titulo' => 'Implantacao WMS LogAlpha',
                'status' => 'aprovada',
                'valor_implantacao' => 48000,
                'valor_recorrente' => 6500,
                'valor_total' => 126000,
                'data_envio' => today()->subDays(10),
                'data_validade' => today()->addDays(20),
                'escopo' => 'WMS, implantacao, treinamento, suporte mensal e sustentacao.',
                'observacoes' => 'Cenario demo aprovado para alimentar contrato, MRR e operacao.',
            ]
        );

        $wms = Servico::where('nome', 'WMS')->first();
        $implantacaoServico = Servico::where('nome', 'Implantação')->first();
        $suporte = Servico::where('nome', 'Suporte Mensal')->first();

        $this->upsertPropostaItem($proposta, $wms, 'Licenca e parametrizacao WMS', 'customizacao', 1, 32000, false);
        $this->upsertPropostaItem($proposta, $implantacaoServico, 'Implantacao e treinamento', 'implantacao', 1, 16000, false);
        $this->upsertPropostaItem($proposta, $suporte, 'Sustentacao mensal', 'suporte', 12, 6500, true);

        $contrato = Contrato::updateOrCreate(
            ['numero' => 'CONT-DEMO-001'],
            [
                'cliente_id' => $cliente->id,
                'proposta_id' => $proposta->id,
                'tipo' => 'hibrido',
                'status' => 'ativo',
                'valor_implantacao' => 48000,
                'valor_mensal' => 6500,
                'data_inicio' => today()->subDays(20),
                'data_fim' => today()->addYear(),
                'sla' => '8x5',
                'observacoes' => 'Contrato demo ativo para validacao de MRR e operacao.',
            ]
        );

        MrrHistorico::updateOrCreate(
            [
                'cliente_id' => $cliente->id,
                'contrato_id' => $contrato->id,
                'ano' => now()->year,
                'mes' => now()->month,
            ],
            [
                'valor_mrr' => 6500,
                'status' => 'confirmado',
            ]
        );

        Financeiro::updateOrCreate(
            ['contrato_id' => $contrato->id, 'descricao' => 'Mensalidade WMS Demo'],
            [
                'cliente_id' => $cliente->id,
                'tipo' => 'receita',
                'categoria' => 'mensalidade',
                'valor' => 6500,
                'data_vencimento' => today()->addDays(5),
                'status' => 'pendente',
                'recorrente' => true,
                'observacoes' => 'Receita recorrente demo.',
            ]
        );

        Financeiro::updateOrCreate(
            ['contrato_id' => $contrato->id, 'descricao' => 'Parcela Implantacao WMS Demo'],
            [
                'cliente_id' => $cliente->id,
                'tipo' => 'receita',
                'categoria' => 'implantacao',
                'valor' => 24000,
                'data_vencimento' => today()->subDays(5),
                'data_pagamento' => today()->subDays(2),
                'status' => 'pago',
                'recorrente' => false,
                'observacoes' => 'Receita de implantacao demo.',
            ]
        );

        Financeiro::updateOrCreate(
            ['contrato_id' => $contrato->id, 'descricao' => 'Custo cloud demo'],
            [
                'cliente_id' => $cliente->id,
                'tipo' => 'despesa',
                'categoria' => 'outros',
                'valor' => 1200,
                'data_vencimento' => today()->addDays(3),
                'status' => 'pendente',
                'recorrente' => true,
                'observacoes' => 'Despesa operacional demo.',
            ]
        );

        Projeto::updateOrCreate(
            ['contrato_id' => $contrato->id, 'nome' => 'Projeto WMS LogAlpha'],
            [
                'cliente_id' => $cliente->id,
                'tipo' => 'wms',
                'status' => 'em_andamento',
                'data_inicio' => today()->subDays(18),
                'data_prevista_entrega' => today()->addDays(25),
                'responsavel' => 'TITAN',
                'descricao' => 'Projeto demo de implantacao WMS com go-live acompanhado.',
            ]
        );

        $implantacao = Implantacao::updateOrCreate(
            ['contrato_id' => $contrato->id],
            [
                'status' => 'em_andamento',
                'data_inicio' => today()->subDays(18),
                'data_go_live' => today()->addDays(25),
                'responsavel' => 'TITAN',
                'observacoes' => 'Implantacao demo em andamento para validar progresso e riscos.',
            ]
        );

        foreach ([
            1 => ['Kickoff', 'concluida'],
            2 => ['Mapeamento operacional', 'concluida'],
            3 => ['Parametrizacao WMS', 'em_andamento'],
            4 => ['Integracao ERP', 'bloqueada'],
            5 => ['Treinamento e go-live', 'pendente'],
        ] as $ordem => [$nome, $status]) {
            ImplantacaoEtapa::updateOrCreate(
                ['implantacao_id' => $implantacao->id, 'ordem' => $ordem],
                [
                    'nome' => $nome,
                    'status' => $status,
                    'data_inicio' => today()->subDays(max(0, 20 - ($ordem * 4))),
                    'data_fim' => $status === 'concluida' ? today()->subDays(max(1, 16 - ($ordem * 4))) : null,
                    'observacoes' => $status === 'bloqueada' ? 'Aguardando credenciais do ERP do cliente.' : null,
                ]
            );
        }

        SuporteChamado::updateOrCreate(
            ['contrato_id' => $contrato->id, 'titulo' => 'Ajuste de regra de separacao'],
            [
                'cliente_id' => $cliente->id,
                'categoria' => 'melhoria',
                'prioridade' => 'alta',
                'status' => 'em_atendimento',
                'canal' => 'portal',
                'aberto_em' => now()->subHours(8),
                'prazo_sla' => now()->addHours(4),
                'responsavel' => 'TITAN',
                'descricao' => 'Chamado demo para validar painel de suporte e SLA.',
            ]
        );

        AuditoriaEvento::updateOrCreate(
            ['modulo' => 'demo', 'acao' => 'massa_demo_atualizada', 'titulo' => 'Massa demo executiva'],
            [
                'user_id' => $admin->id,
                'auditable_type' => Cliente::class,
                'auditable_id' => $cliente->id,
                'metadata' => [
                    'cliente' => $cliente->nome_fantasia,
                    'contrato' => $contrato->numero,
                    'mrr' => 6500,
                ],
            ]
        );
    }

    private function upsertPropostaItem(
        Proposta $proposta,
        ?Servico $servico,
        string $descricao,
        string $tipo,
        int $quantidade,
        float $valorUnitario,
        bool $recorrente
    ): void {
        PropostaItem::updateOrCreate(
            ['proposta_id' => $proposta->id, 'descricao' => $descricao],
            [
                'servico_id' => $servico?->id,
                'tipo' => $tipo,
                'quantidade' => $quantidade,
                'valor_unitario' => $valorUnitario,
                'valor_total' => $quantidade * $valorUnitario,
                'recorrente' => $recorrente,
            ]
        );
    }
}
