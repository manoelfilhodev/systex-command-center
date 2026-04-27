<?php

namespace Tests\Feature;

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
use App\Models\SuporteChamado;
use App\Models\User;
use App\Services\ContratoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommandCenterFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_dashboard_is_available(): void
    {
        $this->get(route('dashboard.index'))
            ->assertOk()
            ->assertSee('MRR Atual')
            ->assertSee('Leads Ativos');
    }

    public function test_dashboard_shows_executive_health_alerts_and_revenue_snapshot(): void
    {
        $cliente = Cliente::create([
            'razao_social' => 'Cliente Dashboard SA',
            'nome_fantasia' => 'Dashboard Cliente',
            'status' => 'ativo',
        ]);

        $contrato = Contrato::create([
            'cliente_id' => $cliente->id,
            'numero' => 'CONT-DASH-001',
            'tipo' => 'recorrente',
            'status' => 'ativo',
            'valor_mensal' => 4000,
            'data_inicio' => today()->subMonth(),
            'data_fim' => today()->addDays(15),
        ]);

        Lead::create([
            'nome' => 'Lead Convertido',
            'empresa' => 'Empresa Convertida',
            'status' => 'convertido',
        ]);

        Proposta::create([
            'cliente_id' => $cliente->id,
            'numero' => 'PROP-DASH-001',
            'titulo' => 'Proposta Dashboard',
            'status' => 'negociacao',
            'valor_total' => 12000,
        ]);

        Financeiro::create([
            'cliente_id' => $cliente->id,
            'contrato_id' => $contrato->id,
            'tipo' => 'receita',
            'categoria' => 'mensalidade',
            'descricao' => 'Receita dashboard',
            'valor' => 2500,
            'data_vencimento' => today()->subDay(),
            'status' => 'pendente',
            'recorrente' => true,
        ]);

        Financeiro::create([
            'tipo' => 'despesa',
            'categoria' => 'outros',
            'descricao' => 'Despesa dashboard',
            'valor' => 500,
            'data_vencimento' => today()->addDays(5),
            'status' => 'pendente',
            'recorrente' => false,
        ]);

        $implantacao = Implantacao::create([
            'contrato_id' => $contrato->id,
            'status' => 'em_andamento',
            'data_inicio' => today()->subMonth(),
            'data_go_live' => today()->subDay(),
        ]);

        ImplantacaoEtapa::create([
            'implantacao_id' => $implantacao->id,
            'nome' => 'Bloqueio',
            'ordem' => 1,
            'status' => 'bloqueada',
        ]);

        SuporteChamado::create([
            'cliente_id' => $cliente->id,
            'contrato_id' => $contrato->id,
            'titulo' => 'SLA dashboard',
            'categoria' => 'incidente',
            'prioridade' => 'critica',
            'status' => 'em_atendimento',
            'canal' => 'portal',
            'aberto_em' => now()->subDays(2),
            'prazo_sla' => now()->subHour(),
        ]);

        $this->get(route('dashboard.index'))
            ->assertOk()
            ->assertSee('Saúde executiva')
            ->assertSee('Alertas críticos')
            ->assertSee('Snapshot financeiro')
            ->assertSee('SLA Vencido')
            ->assertSee('Contratos vencendo')
            ->assertSee('Financeiro vencido')
            ->assertSee('Implantações em risco')
            ->assertSee('R$ 4.000,00')
            ->assertSee('R$ 48.000,00')
            ->assertSee('R$ 2.500,00')
            ->assertSee('R$ 500,00')
            ->assertSee('R$ 2.000,00');
    }

    public function test_auditoria_tracks_critical_business_events(): void
    {
        $cliente = Cliente::create([
            'razao_social' => 'Cliente Auditoria SA',
            'nome_fantasia' => 'Auditoria Cliente',
            'status' => 'ativo',
        ]);

        $this->post(route('contratos.store'), [
            'cliente_id' => $cliente->id,
            'tipo' => 'recorrente',
            'status' => 'ativo',
            'valor_implantacao' => 2000,
            'valor_mensal' => 900,
            'data_inicio' => today()->format('Y-m-d'),
            'sla' => '8x5',
        ])->assertRedirect(route('contratos.index'));

        $contrato = Contrato::firstOrFail();

        $this->post(route('financeiro.store'), [
            'cliente_id' => $cliente->id,
            'contrato_id' => $contrato->id,
            'tipo' => 'receita',
            'categoria' => 'mensalidade',
            'descricao' => 'Mensalidade auditada',
            'valor' => 900,
            'data_vencimento' => today()->addDays(5)->format('Y-m-d'),
            'status' => 'pendente',
            'recorrente' => 1,
        ])->assertRedirect(route('financeiro.index'));

        $this->assertDatabaseHas('_tb_auditoria_eventos', [
            'modulo' => 'contratos',
            'acao' => 'criado',
            'auditable_id' => $contrato->id,
        ]);

        $this->assertDatabaseHas('_tb_auditoria_eventos', [
            'modulo' => 'financeiro',
            'acao' => 'lancamento_criado',
            'titulo' => 'Mensalidade auditada',
        ]);

        $this->get(route('auditoria.index'))
            ->assertOk()
            ->assertSee('Auditoria')
            ->assertSee('Contratos')
            ->assertSee('Financeiro')
            ->assertSee('Mensalidade auditada')
            ->assertSee($contrato->numero);
    }

    public function test_lead_can_be_created_updated_viewed_and_deleted(): void
    {
        $createResponse = $this->post(route('leads.store'), [
            'nome' => 'Maria Operacoes',
            'empresa' => 'Operadora Logistica Alfa',
            'email' => 'maria@example.com',
            'telefone' => '11999990000',
            'origem' => 'LinkedIn',
            'status' => 'novo',
            'valor_estimado' => 15000,
            'proximo_contato' => '2026-05-10',
            'observacoes' => 'Interesse em WMS.',
        ]);

        $lead = Lead::firstOrFail();

        $createResponse->assertRedirect(route('leads.index'));
        $this->assertSame('novo', $lead->status);

        $this->get(route('leads.show', $lead))
            ->assertOk()
            ->assertSee('Operadora Logistica Alfa');

        $this->put(route('leads.update', $lead), [
            'nome' => 'Maria Operacoes',
            'empresa' => 'Operadora Logistica Alfa',
            'email' => 'maria@example.com',
            'telefone' => '11999990000',
            'origem' => 'LinkedIn',
            'status' => 'diagnostico',
            'valor_estimado' => 18000,
            'proximo_contato' => '2026-05-12',
            'observacoes' => 'Diagnostico agendado.',
        ])->assertRedirect(route('leads.show', $lead));

        $this->assertDatabaseHas('_tb_leads', [
            'id' => $lead->id,
            'status' => 'diagnostico',
            'valor_estimado' => 18000,
        ]);

        $this->delete(route('leads.destroy', $lead))
            ->assertRedirect(route('leads.index'));

        $this->assertDatabaseMissing('_tb_leads', ['id' => $lead->id]);
    }

    public function test_crm_pipeline_groups_leads_by_stage(): void
    {
        Lead::create([
            'nome' => 'Ana Compras',
            'empresa' => 'Industria Alfa',
            'status' => 'novo',
            'valor_estimado' => 10000,
            'proximo_contato' => '2026-05-10',
        ]);

        Lead::create([
            'nome' => 'Bruno Operacoes',
            'empresa' => 'Logistica Beta',
            'status' => 'negociacao',
            'valor_estimado' => 25000,
            'proximo_contato' => '2026-05-12',
        ]);

        $this->get(route('crm.index'))
            ->assertOk()
            ->assertSee('CRM Comercial')
            ->assertSee('Novo')
            ->assertSee('Negociação')
            ->assertSee('Industria Alfa')
            ->assertSee('Logistica Beta')
            ->assertSee('R$ 35.000,00');
    }

    public function test_lead_stage_can_be_updated_from_pipeline(): void
    {
        $lead = Lead::create([
            'nome' => 'Carlos Decisor',
            'empresa' => 'Distribuidora Delta',
            'status' => 'novo',
            'valor_estimado' => 12000,
        ]);

        $this->patch(route('leads.stage.update', $lead), [
            'status' => 'negociacao',
        ])->assertRedirect(route('crm.index'));

        $this->assertDatabaseHas('_tb_leads', [
            'id' => $lead->id,
            'status' => 'negociacao',
        ]);
    }

    public function test_lead_interaction_can_be_created_and_deleted(): void
    {
        $lead = Lead::create([
            'nome' => 'Fernanda Compras',
            'empresa' => 'Atacado Forte',
            'status' => 'diagnostico',
        ]);

        $this->post(route('leads.interacoes.store', $lead), [
            'tipo' => 'reuniao',
            'titulo' => 'Reunião de diagnóstico',
            'descricao' => 'Mapeamento de operação e WMS.',
            'data_interacao' => '2026-05-20 10:30:00',
        ])->assertRedirect(route('leads.show', $lead));

        $interacao = LeadInteracao::firstOrFail();

        $this->assertDatabaseHas('_tb_lead_interacoes', [
            'id' => $interacao->id,
            'lead_id' => $lead->id,
            'user_id' => $this->app['auth']->id(),
            'tipo' => 'reuniao',
            'titulo' => 'Reunião de diagnóstico',
        ]);

        $this->get(route('leads.show', $lead))
            ->assertOk()
            ->assertSee('Reunião de diagnóstico')
            ->assertSee('Mapeamento de operação e WMS.');

        $this->delete(route('leads.interacoes.destroy', $interacao))
            ->assertRedirect(route('leads.show', $lead));

        $this->assertDatabaseMissing('_tb_lead_interacoes', ['id' => $interacao->id]);
    }

    public function test_lead_task_can_be_created_completed_and_deleted(): void
    {
        $lead = Lead::create([
            'nome' => 'Gustavo Followup',
            'empresa' => 'Comercio Gama',
            'status' => 'contato_feito',
        ]);

        $this->post(route('leads.tarefas.store', $lead), [
            'titulo' => 'Retornar com proposta',
            'descricao' => 'Enviar escopo WMS revisado.',
            'prioridade' => 'alta',
            'data_vencimento' => '2026-05-22',
        ])->assertRedirect(route('leads.show', $lead));

        $tarefa = LeadTarefa::firstOrFail();

        $this->assertDatabaseHas('_tb_lead_tarefas', [
            'id' => $tarefa->id,
            'lead_id' => $lead->id,
            'user_id' => $this->app['auth']->id(),
            'status' => 'pendente',
            'prioridade' => 'alta',
        ]);

        $this->patch(route('leads.tarefas.complete', $tarefa))
            ->assertRedirect(route('leads.show', $lead));

        $this->assertDatabaseHas('_tb_lead_tarefas', [
            'id' => $tarefa->id,
            'status' => 'concluida',
        ]);

        $this->delete(route('leads.tarefas.destroy', $tarefa))
            ->assertRedirect(route('leads.show', $lead));

        $this->assertDatabaseMissing('_tb_lead_tarefas', ['id' => $tarefa->id]);
    }

    public function test_crm_shows_overdue_and_today_tasks(): void
    {
        $lead = Lead::create([
            'nome' => 'Helena Rotina',
            'empresa' => 'Transportes Hoje',
            'status' => 'diagnostico',
        ]);

        LeadTarefa::create([
            'lead_id' => $lead->id,
            'user_id' => $this->app['auth']->id(),
            'titulo' => 'Tarefa vencida',
            'prioridade' => 'alta',
            'status' => 'pendente',
            'data_vencimento' => today()->subDay(),
        ]);

        LeadTarefa::create([
            'lead_id' => $lead->id,
            'user_id' => $this->app['auth']->id(),
            'titulo' => 'Tarefa de hoje',
            'prioridade' => 'media',
            'status' => 'pendente',
            'data_vencimento' => today(),
        ]);

        $this->get(route('crm.index'))
            ->assertOk()
            ->assertSee('Tarefas Vencidas')
            ->assertSee('Tarefas Hoje')
            ->assertSee('Tarefa vencida')
            ->assertSee('Tarefa de hoje');
    }

    public function test_proposta_updates_linked_lead_commercial_stage(): void
    {
        $lead = Lead::create([
            'nome' => 'Iara Proposta',
            'empresa' => 'Industria Proposta',
            'status' => 'diagnostico',
            'valor_estimado' => 30000,
        ]);

        $this->post(route('propostas.store'), [
            'lead_id' => $lead->id,
            'titulo' => 'Proposta WMS',
            'status' => 'enviada',
            'valor_implantacao' => 10000,
            'valor_recorrente' => 2000,
            'itens' => [
                [
                    'descricao' => 'Implantação WMS',
                    'tipo' => 'implantacao',
                    'quantidade' => 1,
                    'valor_unitario' => 5000,
                ],
            ],
        ])->assertRedirect();

        $proposta = Proposta::firstOrFail();

        $this->assertDatabaseHas('_tb_leads', [
            'id' => $lead->id,
            'status' => 'proposta_enviada',
        ]);

        $this->assertSame('17000.00', $proposta->valor_total);
    }

    public function test_approved_proposta_converts_lead_and_redirects_to_contract_creation(): void
    {
        $lead = Lead::create([
            'nome' => 'Joao Fechamento',
            'empresa' => 'Fechamento SA',
            'status' => 'negociacao',
        ]);

        $proposta = Proposta::create([
            'lead_id' => $lead->id,
            'numero' => 'PROP-TESTE-APROVA',
            'titulo' => 'Proposta de Fechamento',
            'status' => 'negociacao',
            'valor_total' => 12000,
        ]);

        $this->patch(route('propostas.approve', $proposta))
            ->assertRedirect(route('contratos.create', ['proposta_id' => $proposta->id]));

        $this->assertDatabaseHas('_tb_propostas', [
            'id' => $proposta->id,
            'status' => 'aprovada',
        ]);

        $this->assertDatabaseHas('_tb_leads', [
            'id' => $lead->id,
            'status' => 'convertido',
        ]);
    }

    public function test_contract_creation_prefills_values_from_selected_proposta(): void
    {
        $cliente = Cliente::create([
            'razao_social' => 'Cliente Contrato Proposta SA',
            'nome_fantasia' => 'Contrato Proposta',
            'status' => 'ativo',
        ]);

        $proposta = Proposta::create([
            'cliente_id' => $cliente->id,
            'numero' => 'PROP-CONTRATO-001',
            'titulo' => 'Proposta para Contrato',
            'status' => 'aprovada',
            'valor_implantacao' => 9000,
            'valor_recorrente' => 1800,
            'valor_total' => 10800,
        ]);

        $this->get(route('contratos.create', ['proposta_id' => $proposta->id]))
            ->assertOk()
            ->assertSee('PROP-CONTRATO-001')
            ->assertSee('Proposta para Contrato')
            ->assertSee('value="'.$cliente->id.'" selected', false)
            ->assertSee('value="'.$proposta->id.'" selected', false)
            ->assertSee('value="9000.00"', false)
            ->assertSee('value="1800.00"', false);
    }

    public function test_cliente_accepts_suspended_status(): void
    {
        $this->post(route('clientes.store'), [
            'razao_social' => 'Cliente Suspenso SA',
            'nome_fantasia' => 'Cliente Suspenso',
            'email' => 'financeiro@example.com',
            'estado' => 'SP',
            'status' => 'suspenso',
        ])->assertRedirect(route('clientes.index'));

        $this->assertDatabaseHas('_tb_clientes', [
            'razao_social' => 'Cliente Suspenso SA',
            'status' => 'suspenso',
        ]);
    }

    public function test_active_contract_creates_current_mrr_record(): void
    {
        $cliente = Cliente::create([
            'razao_social' => 'Cliente MRR SA',
            'nome_fantasia' => 'Cliente MRR',
            'status' => 'ativo',
        ]);

        $this->post(route('contratos.store'), [
            'cliente_id' => $cliente->id,
            'tipo' => 'recorrente',
            'status' => 'ativo',
            'valor_implantacao' => 5000,
            'valor_mensal' => 2500,
            'data_inicio' => '2026-05-01',
            'sla' => 'Comercial',
        ])->assertRedirect(route('contratos.index'));

        $contrato = Contrato::firstOrFail();

        $this->assertDatabaseHas('_tb_mrr_historico', [
            'cliente_id' => $cliente->id,
            'contrato_id' => $contrato->id,
            'ano' => now()->year,
            'mes' => now()->month,
            'valor_mrr' => 2500,
            'status' => 'confirmado',
        ]);

        $this->assertSame('2500.00', MrrHistorico::firstOrFail()->valor_mrr);
    }

    public function test_contracts_index_shows_executive_summary_and_renewal_alerts(): void
    {
        $cliente = Cliente::create([
            'razao_social' => 'Cliente Renovacao SA',
            'nome_fantasia' => 'Renovacao Systex',
            'status' => 'ativo',
        ]);

        Contrato::create([
            'cliente_id' => $cliente->id,
            'numero' => 'CONT-RENOVA-001',
            'tipo' => 'recorrente',
            'status' => 'ativo',
            'valor_implantacao' => 10000,
            'valor_mensal' => 3000,
            'data_inicio' => today()->subMonths(11),
            'data_fim' => today()->addDays(15),
        ]);

        Contrato::create([
            'cliente_id' => $cliente->id,
            'numero' => 'CONT-VENCIDO-001',
            'tipo' => 'recorrente',
            'status' => 'ativo',
            'valor_mensal' => 1200,
            'data_inicio' => today()->subYear(),
            'data_fim' => today()->subDay(),
        ]);

        $this->get(route('contratos.index'))
            ->assertOk()
            ->assertSee('Contratos Ativos')
            ->assertSee('MRR Ativo')
            ->assertSee('R$ 4.200,00')
            ->assertSee('R$ 50.400,00')
            ->assertSee('Vencendo em 30 dias')
            ->assertSee('Vencidos Ativos')
            ->assertSee('Renovações próximas')
            ->assertSee('CONT-RENOVA-001');

        $vencendo = app(ContratoService::class)->expiringContracts();

        $this->assertTrue($vencendo->contains('numero', 'CONT-RENOVA-001'));
        $this->assertFalse($vencendo->contains('numero', 'CONT-VENCIDO-001'));
    }

    public function test_financeiro_entry_can_be_linked_to_cliente_and_contrato(): void
    {
        $cliente = Cliente::create([
            'razao_social' => 'Cliente Financeiro SA',
            'nome_fantasia' => 'Financeiro Systex',
            'status' => 'ativo',
        ]);

        $contrato = Contrato::create([
            'cliente_id' => $cliente->id,
            'numero' => 'CONT-FIN-001',
            'tipo' => 'recorrente',
            'status' => 'ativo',
            'valor_mensal' => 2100,
            'data_inicio' => '2026-05-01',
        ]);

        $this->post(route('financeiro.store'), [
            'cliente_id' => $cliente->id,
            'contrato_id' => $contrato->id,
            'tipo' => 'receita',
            'categoria' => 'mensalidade',
            'descricao' => 'Mensalidade WMS',
            'valor' => 2100,
            'data_vencimento' => today()->addDays(5)->format('Y-m-d'),
            'status' => 'pendente',
            'recorrente' => 1,
        ])->assertRedirect(route('financeiro.index'));

        $lancamento = Financeiro::firstOrFail();

        $this->assertDatabaseHas('_tb_financeiro', [
            'id' => $lancamento->id,
            'cliente_id' => $cliente->id,
            'contrato_id' => $contrato->id,
            'descricao' => 'Mensalidade WMS',
            'recorrente' => true,
        ]);

        $this->get(route('financeiro.show', $lancamento))
            ->assertOk()
            ->assertSee('Financeiro Systex')
            ->assertSee('CONT-FIN-001');
    }

    public function test_financeiro_index_shows_cash_summary_and_due_alerts(): void
    {
        $cliente = Cliente::create([
            'razao_social' => 'Cliente Fluxo SA',
            'nome_fantasia' => 'Fluxo Cliente',
            'status' => 'ativo',
        ]);

        Financeiro::create([
            'cliente_id' => $cliente->id,
            'tipo' => 'receita',
            'categoria' => 'mensalidade',
            'descricao' => 'Receita paga',
            'valor' => 5000,
            'data_vencimento' => today()->subDays(10),
            'data_pagamento' => today()->subDays(9),
            'status' => 'pago',
            'recorrente' => true,
        ]);

        Financeiro::create([
            'tipo' => 'despesa',
            'categoria' => 'outros',
            'descricao' => 'Despesa paga',
            'valor' => 1500,
            'data_vencimento' => today()->subDays(3),
            'data_pagamento' => today()->subDays(2),
            'status' => 'pago',
            'recorrente' => false,
        ]);

        Financeiro::create([
            'cliente_id' => $cliente->id,
            'tipo' => 'receita',
            'categoria' => 'suporte',
            'descricao' => 'Receita vencida',
            'valor' => 700,
            'data_vencimento' => today()->subDay(),
            'status' => 'pendente',
            'recorrente' => false,
        ]);

        Financeiro::create([
            'tipo' => 'despesa',
            'categoria' => 'consultoria',
            'descricao' => 'Despesa próxima',
            'valor' => 300,
            'data_vencimento' => today()->addDays(3),
            'status' => 'pendente',
            'recorrente' => false,
        ]);

        $this->get(route('financeiro.index'))
            ->assertOk()
            ->assertSee('Saldo Confirmado')
            ->assertSee('R$ 3.500,00')
            ->assertSee('A Receber')
            ->assertSee('R$ 700,00')
            ->assertSee('A Pagar')
            ->assertSee('R$ 300,00')
            ->assertSee('Vencidos')
            ->assertSee('Fluxo 7 Dias')
            ->assertSee('Pendências vencidas')
            ->assertSee('Receita vencida')
            ->assertSee('Próximos 7 dias')
            ->assertSee('Despesa próxima');
    }

    public function test_project_can_be_created_from_contract_and_inherits_cliente(): void
    {
        $cliente = Cliente::create([
            'razao_social' => 'Cliente Projeto SA',
            'nome_fantasia' => 'Projeto Cliente',
            'status' => 'ativo',
        ]);

        $contrato = Contrato::create([
            'cliente_id' => $cliente->id,
            'numero' => 'CONT-PROJ-001',
            'tipo' => 'hibrido',
            'status' => 'ativo',
            'valor_mensal' => 2500,
            'data_inicio' => '2026-05-01',
        ]);

        $this->post(route('projetos.store'), [
            'contrato_id' => $contrato->id,
            'nome' => 'Implantação WMS Projeto',
            'tipo' => 'wms',
            'status' => 'em_andamento',
            'data_inicio' => '2026-05-10',
            'data_prevista_entrega' => '2026-06-10',
            'responsavel' => 'Equipe Operação',
            'descricao' => 'Projeto de implantação vinculado ao contrato.',
        ])->assertRedirect(route('projetos.index'));

        $projeto = Projeto::firstOrFail();

        $this->assertDatabaseHas('_tb_projetos', [
            'id' => $projeto->id,
            'cliente_id' => $cliente->id,
            'contrato_id' => $contrato->id,
            'status' => 'em_andamento',
        ]);

        $this->get(route('projetos.show', $projeto))
            ->assertOk()
            ->assertSee('Implantação WMS Projeto')
            ->assertSee('Projeto Cliente')
            ->assertSee('CONT-PROJ-001');
    }

    public function test_projects_index_shows_summary_and_delayed_alerts(): void
    {
        $cliente = Cliente::create([
            'razao_social' => 'Cliente Atraso SA',
            'nome_fantasia' => 'Atraso Cliente',
            'status' => 'ativo',
        ]);

        Projeto::create([
            'cliente_id' => $cliente->id,
            'nome' => 'Projeto Atrasado',
            'tipo' => 'erp',
            'status' => 'em_andamento',
            'data_inicio' => today()->subMonths(2),
            'data_prevista_entrega' => today()->subDay(),
            'responsavel' => 'PMO Systex',
        ]);

        Projeto::create([
            'cliente_id' => $cliente->id,
            'nome' => 'Projeto Proximo',
            'tipo' => 'crm',
            'status' => 'homologacao',
            'data_inicio' => today()->subMonth(),
            'data_prevista_entrega' => today()->addDays(10),
        ]);

        $this->get(route('projetos.index'))
            ->assertOk()
            ->assertSee('Projetos Ativos')
            ->assertSee('Em Andamento')
            ->assertSee('Homologação')
            ->assertSee('Atrasados')
            ->assertSee('Entregas 30 Dias')
            ->assertSee('Projetos em atraso')
            ->assertSee('Projeto Atrasado')
            ->assertSee('PMO Systex')
            ->assertSee('Projeto Proximo');
    }

    public function test_project_can_be_updated_and_deleted(): void
    {
        $projeto = Projeto::create([
            'nome' => 'Projeto Mutável',
            'tipo' => 'crm',
            'status' => 'planejado',
        ]);

        $this->put(route('projetos.update', $projeto), [
            'nome' => 'Projeto Atualizado',
            'tipo' => 'crm',
            'status' => 'homologacao',
            'data_inicio' => '2026-05-01',
            'data_prevista_entrega' => '2026-05-20',
            'data_entrega' => '2026-05-21',
            'responsavel' => 'Operação',
            'descricao' => 'Projeto em homologação.',
        ])->assertRedirect(route('projetos.show', $projeto));

        $this->assertDatabaseHas('_tb_projetos', [
            'id' => $projeto->id,
            'nome' => 'Projeto Atualizado',
            'status' => 'homologacao',
        ]);

        $this->delete(route('projetos.destroy', $projeto))
            ->assertRedirect(route('projetos.index'));

        $this->assertDatabaseMissing('_tb_projetos', ['id' => $projeto->id]);
    }

    public function test_implantacoes_index_shows_progress_and_risk_alerts(): void
    {
        $cliente = Cliente::create([
            'razao_social' => 'Cliente Implantacao SA',
            'nome_fantasia' => 'Implantacao Cliente',
            'status' => 'ativo',
        ]);

        $contrato = Contrato::create([
            'cliente_id' => $cliente->id,
            'numero' => 'CONT-IMP-001',
            'tipo' => 'hibrido',
            'status' => 'ativo',
            'valor_mensal' => 3200,
            'data_inicio' => today()->subMonth(),
        ]);

        $implantacao = Implantacao::create([
            'contrato_id' => $contrato->id,
            'status' => 'em_andamento',
            'data_inicio' => today()->subMonth(),
            'data_go_live' => today()->subDay(),
            'responsavel' => 'Operação Implantação',
        ]);

        ImplantacaoEtapa::create([
            'implantacao_id' => $implantacao->id,
            'nome' => 'Kickoff',
            'ordem' => 1,
            'status' => 'concluida',
        ]);

        ImplantacaoEtapa::create([
            'implantacao_id' => $implantacao->id,
            'nome' => 'Integração',
            'ordem' => 2,
            'status' => 'bloqueada',
        ]);

        $this->get(route('implantacoes.index'))
            ->assertOk()
            ->assertSee('Em Risco')
            ->assertSee('Go Live 30 Dias')
            ->assertSee('Implantações em risco')
            ->assertSee('Implantacao Cliente')
            ->assertSee('CONT-IMP-001')
            ->assertSee('50%')
            ->assertSee('Mitigar');
    }

    public function test_implantacao_show_exposes_progress_and_blockers(): void
    {
        $cliente = Cliente::create([
            'razao_social' => 'Cliente Progresso SA',
            'nome_fantasia' => 'Progresso Cliente',
            'status' => 'ativo',
        ]);

        $contrato = Contrato::create([
            'cliente_id' => $cliente->id,
            'numero' => 'CONT-IMP-002',
            'tipo' => 'recorrente',
            'status' => 'ativo',
            'valor_mensal' => 1800,
            'data_inicio' => today()->subMonth(),
        ]);

        $implantacao = Implantacao::create([
            'contrato_id' => $contrato->id,
            'status' => 'homologacao',
            'data_inicio' => today()->subMonth(),
            'data_go_live' => today()->addDays(10),
            'responsavel' => 'PMO Implantação',
        ]);

        foreach (['concluida', 'concluida', 'pendente', 'bloqueada'] as $index => $status) {
            ImplantacaoEtapa::create([
                'implantacao_id' => $implantacao->id,
                'nome' => 'Etapa '.($index + 1),
                'ordem' => $index + 1,
                'status' => $status,
            ]);
        }

        $this->get(route('implantacoes.show', $implantacao))
            ->assertOk()
            ->assertSee('Progresso')
            ->assertSee('50%')
            ->assertSee('2 de 4 etapas concluídas')
            ->assertSee('Atenção')
            ->assertSee('1 etapa(s) bloqueada(s)')
            ->assertSee('Progresso operacional');
    }

    public function test_support_ticket_can_be_created_from_contract_and_inherits_cliente(): void
    {
        $cliente = Cliente::create([
            'razao_social' => 'Cliente Suporte SA',
            'nome_fantasia' => 'Suporte Cliente',
            'status' => 'ativo',
        ]);

        $contrato = Contrato::create([
            'cliente_id' => $cliente->id,
            'numero' => 'CONT-SUP-001',
            'tipo' => 'recorrente',
            'status' => 'ativo',
            'valor_mensal' => 2200,
            'data_inicio' => today()->subMonth(),
            'sla' => '8x5',
        ]);

        $this->post(route('suporte.store'), [
            'contrato_id' => $contrato->id,
            'titulo' => 'Falha na integração WMS',
            'categoria' => 'integracao',
            'prioridade' => 'critica',
            'status' => 'aberto',
            'canal' => 'whatsapp',
            'aberto_em' => now()->subHours(2)->format('Y-m-d H:i:s'),
            'prazo_sla' => now()->addHours(2)->format('Y-m-d H:i:s'),
            'responsavel' => 'Suporte Systex',
            'descricao' => 'Integração com transportadora indisponível.',
        ])->assertRedirect(route('suporte.index'));

        $chamado = SuporteChamado::firstOrFail();

        $this->assertDatabaseHas('_tb_suporte_chamados', [
            'id' => $chamado->id,
            'cliente_id' => $cliente->id,
            'contrato_id' => $contrato->id,
            'prioridade' => 'critica',
            'status' => 'aberto',
        ]);

        $this->get(route('suporte.show', $chamado))
            ->assertOk()
            ->assertSee('Falha na integração WMS')
            ->assertSee('Suporte Cliente')
            ->assertSee('CONT-SUP-001');
    }

    public function test_support_index_shows_sla_summary_and_overdue_alerts(): void
    {
        $cliente = Cliente::create([
            'razao_social' => 'Cliente SLA SA',
            'nome_fantasia' => 'SLA Cliente',
            'status' => 'ativo',
        ]);

        SuporteChamado::create([
            'cliente_id' => $cliente->id,
            'titulo' => 'Chamado vencido',
            'categoria' => 'incidente',
            'prioridade' => 'critica',
            'status' => 'em_atendimento',
            'canal' => 'portal',
            'aberto_em' => now()->subDays(2),
            'prazo_sla' => now()->subHour(),
            'responsavel' => 'NOC',
        ]);

        SuporteChamado::create([
            'cliente_id' => $cliente->id,
            'titulo' => 'Chamado próximo',
            'categoria' => 'duvida',
            'prioridade' => 'media',
            'status' => 'aberto',
            'canal' => 'email',
            'aberto_em' => now()->subHour(),
            'prazo_sla' => now()->addHours(8),
        ]);

        SuporteChamado::create([
            'cliente_id' => $cliente->id,
            'titulo' => 'Chamado resolvido',
            'categoria' => 'melhoria',
            'prioridade' => 'baixa',
            'status' => 'resolvido',
            'canal' => 'interno',
            'aberto_em' => now()->subDays(3),
            'prazo_sla' => now()->subDays(2),
            'resolvido_em' => now(),
        ]);

        $this->get(route('suporte.index'))
            ->assertOk()
            ->assertSee('Chamados Abertos')
            ->assertSee('Críticos')
            ->assertSee('SLA Vencido')
            ->assertSee('SLA 24h')
            ->assertSee('Resolvidos no Mês')
            ->assertSee('Chamado vencido')
            ->assertSee('Atuar')
            ->assertSee('Chamado próximo')
            ->assertSee('Chamado resolvido');
    }

    public function test_support_ticket_can_be_updated_and_deleted(): void
    {
        $chamado = SuporteChamado::create([
            'titulo' => 'Chamado mutável',
            'categoria' => 'incidente',
            'prioridade' => 'media',
            'status' => 'aberto',
            'canal' => 'interno',
            'aberto_em' => now()->subDay(),
            'prazo_sla' => now()->addDay(),
        ]);

        $this->put(route('suporte.update', $chamado), [
            'titulo' => 'Chamado resolvido',
            'categoria' => 'incidente',
            'prioridade' => 'alta',
            'status' => 'resolvido',
            'canal' => 'interno',
            'aberto_em' => now()->subDay()->format('Y-m-d H:i:s'),
            'prazo_sla' => now()->addDay()->format('Y-m-d H:i:s'),
            'resolvido_em' => now()->format('Y-m-d H:i:s'),
            'responsavel' => 'Suporte',
            'resolucao' => 'Ambiente normalizado.',
        ])->assertRedirect(route('suporte.show', $chamado));

        $this->assertDatabaseHas('_tb_suporte_chamados', [
            'id' => $chamado->id,
            'titulo' => 'Chamado resolvido',
            'status' => 'resolvido',
        ]);

        $this->delete(route('suporte.destroy', $chamado))
            ->assertRedirect(route('suporte.index'));

        $this->assertDatabaseMissing('_tb_suporte_chamados', ['id' => $chamado->id]);
    }

    public function test_mrr_exposes_only_index_route(): void
    {
        $this->get(route('mrr.index'))->assertOk();

        $this->assertFalse(\Route::has('mrr.create'));
        $this->assertFalse(\Route::has('mrr.store'));
        $this->assertFalse(\Route::has('mrr.show'));
        $this->assertFalse(\Route::has('mrr.edit'));
        $this->assertFalse(\Route::has('mrr.update'));
        $this->assertFalse(\Route::has('mrr.destroy'));
    }

    public function test_mrr_index_does_not_create_history_records(): void
    {
        $cliente = Cliente::create([
            'razao_social' => 'Cliente Leitura MRR SA',
            'status' => 'ativo',
        ]);

        Contrato::create([
            'cliente_id' => $cliente->id,
            'numero' => 'CONT-MRR-READ',
            'tipo' => 'recorrente',
            'status' => 'ativo',
            'valor_mensal' => 1500,
            'data_inicio' => '2026-05-01',
        ]);

        $this->assertDatabaseCount('_tb_mrr_historico', 0);

        $this->get(route('mrr.index'))->assertOk();

        $this->assertDatabaseCount('_tb_mrr_historico', 0);
    }
}
