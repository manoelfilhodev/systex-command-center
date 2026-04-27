<?php

namespace Tests\Feature;

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
use App\Models\Proposta;
use App\Models\PropostaItem;
use App\Models\Servico;
use App\Models\SuporteChamado;
use App\Models\User;
use Database\Seeders\ServicosSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseIntegrityTest extends TestCase
{
    use RefreshDatabase;

    public function test_core_tables_and_columns_exist(): void
    {
        foreach ([
            '_tb_clientes',
            '_tb_leads',
            '_tb_propostas',
            '_tb_proposta_itens',
            '_tb_servicos',
            '_tb_contratos',
            '_tb_financeiro',
            '_tb_mrr_historico',
            '_tb_projetos',
            '_tb_implantacoes',
            '_tb_implantacao_etapas',
            '_tb_contrato_aditivos',
            '_tb_lead_interacoes',
            '_tb_lead_tarefas',
            '_tb_suporte_chamados',
            '_tb_auditoria_eventos',
        ] as $table) {
            $this->assertTrue(Schema::hasTable($table), "Tabela {$table} nao encontrada.");
        }

        $this->assertTrue(Schema::hasColumns('_tb_contratos', [
            'cliente_id',
            'proposta_id',
            'numero',
            'valor_implantacao',
            'valor_mensal',
        ]));

        $this->assertTrue(Schema::hasColumns('_tb_implantacoes', [
            'contrato_id',
            'status',
            'data_inicio',
            'data_go_live',
        ]));

        $this->assertTrue(Schema::hasColumn('users', 'role'));
        $this->assertTrue(Schema::hasColumns('_tb_lead_interacoes', [
            'lead_id',
            'user_id',
            'tipo',
            'titulo',
            'data_interacao',
        ]));
        $this->assertTrue(Schema::hasColumns('_tb_lead_tarefas', [
            'lead_id',
            'user_id',
            'titulo',
            'prioridade',
            'status',
            'data_vencimento',
        ]));
        $this->assertTrue(Schema::hasColumns('_tb_suporte_chamados', [
            'cliente_id',
            'contrato_id',
            'titulo',
            'prioridade',
            'status',
            'prazo_sla',
        ]));
        $this->assertTrue(Schema::hasColumns('_tb_auditoria_eventos', [
            'user_id',
            'modulo',
            'acao',
            'auditable_type',
            'auditable_id',
            'metadata',
        ]));
    }

    public function test_servicos_seeder_is_idempotent(): void
    {
        $this->seed(ServicosSeeder::class);
        $this->seed(ServicosSeeder::class);

        $this->assertSame(6, Servico::count());
        $this->assertDatabaseHas('_tb_servicos', [
            'nome' => 'WMS',
            'categoria' => 'wms',
            'tipo_receita' => 'hibrida',
            'ativo' => true,
        ]);
    }

    public function test_proposta_relationships_enforce_expected_delete_behavior(): void
    {
        $lead = Lead::create([
            'nome' => 'Lead Relacional',
            'status' => 'novo',
        ]);

        $cliente = Cliente::create([
            'razao_social' => 'Cliente Relacional SA',
            'status' => 'ativo',
        ]);

        $servico = Servico::create([
            'nome' => 'Integracao GAIA',
            'categoria' => 'integracao',
            'tipo_receita' => 'unica',
        ]);

        $proposta = Proposta::create([
            'lead_id' => $lead->id,
            'cliente_id' => $cliente->id,
            'numero' => 'PROP-GAIA-001',
            'titulo' => 'Proposta Relacional',
            'status' => 'enviada',
        ]);

        $item = PropostaItem::create([
            'proposta_id' => $proposta->id,
            'servico_id' => $servico->id,
            'descricao' => 'Item relacional',
            'tipo' => 'integracao',
            'quantidade' => 2,
            'valor_unitario' => 1000,
            'valor_total' => 2000,
        ]);

        $servico->delete();

        $this->assertDatabaseHas('_tb_proposta_itens', [
            'id' => $item->id,
            'servico_id' => null,
        ]);

        $lead->delete();
        $cliente->delete();

        $this->assertDatabaseHas('_tb_propostas', [
            'id' => $proposta->id,
            'lead_id' => null,
            'cliente_id' => null,
        ]);

        $proposta->delete();

        $this->assertDatabaseMissing('_tb_proposta_itens', ['id' => $item->id]);
    }

    public function test_lead_interactions_are_removed_with_lead_and_keep_history_when_user_is_deleted(): void
    {
        $lead = Lead::create([
            'nome' => 'Lead com Histórico',
            'status' => 'novo',
        ]);

        $user = User::factory()->create();

        $interacao = LeadInteracao::create([
            'lead_id' => $lead->id,
            'user_id' => $user->id,
            'tipo' => 'contato',
            'titulo' => 'Contato inicial',
            'data_interacao' => '2026-05-10 09:00:00',
        ]);

        $user->delete();

        $this->assertDatabaseHas('_tb_lead_interacoes', [
            'id' => $interacao->id,
            'user_id' => null,
        ]);

        $lead->delete();

        $this->assertDatabaseMissing('_tb_lead_interacoes', ['id' => $interacao->id]);
    }

    public function test_lead_tasks_are_removed_with_lead_and_keep_task_when_user_is_deleted(): void
    {
        $lead = Lead::create([
            'nome' => 'Lead com Tarefa',
            'status' => 'novo',
        ]);

        $user = User::factory()->create();

        $tarefa = LeadTarefa::create([
            'lead_id' => $lead->id,
            'user_id' => $user->id,
            'titulo' => 'Follow-up',
            'prioridade' => 'media',
            'status' => 'pendente',
            'data_vencimento' => '2026-05-10',
        ]);

        $user->delete();

        $this->assertDatabaseHas('_tb_lead_tarefas', [
            'id' => $tarefa->id,
            'user_id' => null,
        ]);

        $lead->delete();

        $this->assertDatabaseMissing('_tb_lead_tarefas', ['id' => $tarefa->id]);
    }

    public function test_contrato_operational_records_follow_delete_rules(): void
    {
        $cliente = Cliente::create([
            'razao_social' => 'Cliente Operacional SA',
            'status' => 'ativo',
        ]);

        $contrato = Contrato::create([
            'cliente_id' => $cliente->id,
            'numero' => 'CONT-GAIA-001',
            'tipo' => 'recorrente',
            'status' => 'ativo',
            'valor_mensal' => 1200,
            'data_inicio' => '2026-05-01',
        ]);

        $financeiro = Financeiro::create([
            'cliente_id' => $cliente->id,
            'contrato_id' => $contrato->id,
            'tipo' => 'receita',
            'categoria' => 'mensalidade',
            'descricao' => 'Mensalidade GAIA',
            'valor' => 1200,
            'data_vencimento' => '2026-05-10',
            'status' => 'pendente',
            'recorrente' => true,
        ]);

        $mrr = MrrHistorico::create([
            'cliente_id' => $cliente->id,
            'contrato_id' => $contrato->id,
            'ano' => 2026,
            'mes' => 5,
            'valor_mrr' => 1200,
            'status' => 'confirmado',
        ]);

        $implantacao = Implantacao::create([
            'contrato_id' => $contrato->id,
            'status' => 'em_andamento',
            'data_inicio' => '2026-05-02',
        ]);

        $etapa = ImplantacaoEtapa::create([
            'implantacao_id' => $implantacao->id,
            'nome' => 'Kickoff',
            'ordem' => 1,
            'status' => 'pendente',
        ]);

        $contrato->delete();

        $this->assertDatabaseHas('_tb_financeiro', [
            'id' => $financeiro->id,
            'contrato_id' => null,
        ]);

        $this->assertDatabaseHas('_tb_mrr_historico', [
            'id' => $mrr->id,
            'contrato_id' => null,
        ]);

        $this->assertDatabaseMissing('_tb_implantacoes', ['id' => $implantacao->id]);
        $this->assertDatabaseMissing('_tb_implantacao_etapas', ['id' => $etapa->id]);
    }

    public function test_model_casts_match_database_semantics(): void
    {
        $cliente = Cliente::create([
            'razao_social' => 'Cliente Cast SA',
            'status' => 'ativo',
        ]);

        $contrato = Contrato::create([
            'cliente_id' => $cliente->id,
            'numero' => 'CONT-GAIA-CAST',
            'tipo' => 'hibrido',
            'status' => 'ativo',
            'valor_implantacao' => 1000,
            'valor_mensal' => 500,
            'data_inicio' => '2026-05-01',
        ]);

        $implantacao = Implantacao::create([
            'contrato_id' => $contrato->id,
            'status' => 'pendente',
            'data_inicio' => '2026-05-02',
            'data_go_live' => '2026-06-01',
        ]);

        $etapa = ImplantacaoEtapa::create([
            'implantacao_id' => $implantacao->id,
            'nome' => 'Homologacao',
            'ordem' => 2,
            'status' => 'em_andamento',
            'data_inicio' => '2026-05-15',
        ]);

        $this->assertSame('1000.00', $contrato->valor_implantacao);
        $this->assertSame('2026-05-01', $contrato->data_inicio->format('Y-m-d'));
        $this->assertSame('2026-06-01', $implantacao->data_go_live->format('Y-m-d'));
        $this->assertSame(2, $etapa->ordem);
        $this->assertSame('2026-05-15', $etapa->data_inicio->format('Y-m-d'));

        $chamado = SuporteChamado::create([
            'contrato_id' => $contrato->id,
            'titulo' => 'Chamado SLA',
            'categoria' => 'incidente',
            'prioridade' => 'alta',
            'status' => 'aberto',
            'canal' => 'interno',
            'aberto_em' => '2026-06-01 08:30:00',
            'prazo_sla' => '2026-06-01 12:30:00',
        ]);

        $this->assertSame('2026-06-01 08:30', $chamado->aberto_em->format('Y-m-d H:i'));
        $this->assertSame('2026-06-01 12:30', $chamado->prazo_sla->format('Y-m-d H:i'));

        $evento = AuditoriaEvento::create([
            'modulo' => 'gaia',
            'acao' => 'cast_validado',
            'auditable_type' => Contrato::class,
            'auditable_id' => $contrato->id,
            'titulo' => 'Evento de teste',
            'metadata' => ['status' => 'ok'],
        ]);

        $this->assertSame(['status' => 'ok'], $evento->metadata);
    }

    public function test_user_role_defaults_to_admin(): void
    {
        $user = User::factory()->create();

        $this->assertSame('admin', $user->role);
        $this->assertTrue($user->hasAnyRole(['financeiro']));
    }
}
