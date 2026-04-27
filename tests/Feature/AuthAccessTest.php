<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\Proposta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_dashboard(): void
    {
        $this->get(route('dashboard.index'))
            ->assertRedirect(route('login'));
    }

    public function test_user_can_login_and_logout(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@systex.com.br',
            'password' => Hash::make('senha-segura'),
        ]);

        $this->post(route('login.store'), [
            'email' => 'admin@systex.com.br',
            'password' => 'senha-segura',
        ])->assertRedirect(route('dashboard.index'));

        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_invalid_login_is_rejected(): void
    {
        User::factory()->create([
            'email' => 'admin@systex.com.br',
            'password' => Hash::make('senha-segura'),
        ]);

        $this->from(route('login'))
            ->post(route('login.store'), [
                'email' => 'admin@systex.com.br',
                'password' => 'senha-errada',
            ])
            ->assertSessionHasErrors('email')
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_comercial_user_cannot_access_financeiro_module(): void
    {
        $user = User::factory()->create(['role' => 'comercial']);

        $this->actingAs($user)
            ->get(route('financeiro.index'))
            ->assertForbidden();

        $this->actingAs($user)
            ->get(route('leads.index'))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('crm.index'))
            ->assertOk();
    }

    public function test_financeiro_user_cannot_access_commercial_module(): void
    {
        $user = User::factory()->create(['role' => 'financeiro']);
        $lead = Lead::create([
            'nome' => 'Lead Bloqueado',
            'status' => 'novo',
        ]);

        $this->actingAs($user)
            ->get(route('leads.index'))
            ->assertForbidden();

        $this->actingAs($user)
            ->get(route('crm.index'))
            ->assertForbidden();

        $this->actingAs($user)
            ->patch(route('leads.stage.update', $lead), ['status' => 'negociacao'])
            ->assertForbidden();

        $proposta = Proposta::create([
            'lead_id' => $lead->id,
            'numero' => 'PROP-BLOCKED',
            'titulo' => 'Proposta bloqueada',
            'status' => 'negociacao',
        ]);

        $this->actingAs($user)
            ->patch(route('propostas.approve', $proposta))
            ->assertForbidden();

        $this->actingAs($user)
            ->post(route('leads.interacoes.store', $lead), [
                'tipo' => 'contato',
                'titulo' => 'Contato bloqueado',
                'data_interacao' => '2026-05-10 09:00:00',
            ])
            ->assertForbidden();

        $this->actingAs($user)
            ->post(route('leads.tarefas.store', $lead), [
                'titulo' => 'Tarefa bloqueada',
                'prioridade' => 'media',
                'data_vencimento' => '2026-05-10',
            ])
            ->assertForbidden();

        $this->actingAs($user)
            ->get(route('financeiro.index'))
            ->assertOk();
    }

    public function test_admin_user_can_access_protected_modules(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->get(route('dashboard.index'))->assertOk();
        $this->actingAs($user)->get(route('leads.index'))->assertOk();
        $this->actingAs($user)->get(route('financeiro.index'))->assertOk();
        $this->actingAs($user)->get(route('implantacoes.index'))->assertOk();
    }

    public function test_diretoria_has_read_only_access_to_executive_modules(): void
    {
        $user = User::factory()->create(['role' => 'diretoria']);

        $this->actingAs($user)->get(route('dashboard.index'))->assertOk();
        $this->actingAs($user)->get(route('auditoria.index'))->assertOk();
        $this->actingAs($user)->get(route('crm.index'))->assertOk();
        $this->actingAs($user)->get(route('leads.index'))->assertOk();
        $this->actingAs($user)->get(route('propostas.index'))->assertOk();
        $this->actingAs($user)->get(route('contratos.index'))->assertOk();
        $this->actingAs($user)->get(route('financeiro.index'))->assertOk();
        $this->actingAs($user)->get(route('projetos.index'))->assertOk();
        $this->actingAs($user)->get(route('implantacoes.index'))->assertOk();
        $this->actingAs($user)->get(route('suporte.index'))->assertOk();

        $this->actingAs($user)->get(route('leads.create'))->assertForbidden();
        $this->actingAs($user)->get(route('contratos.create'))->assertForbidden();
        $this->actingAs($user)->get(route('financeiro.create'))->assertForbidden();
        $this->actingAs($user)->get(route('projetos.create'))->assertForbidden();
        $this->actingAs($user)->get(route('implantacoes.create'))->assertForbidden();
        $this->actingAs($user)->get(route('suporte.create'))->assertForbidden();
    }

    public function test_operacao_and_financeiro_cannot_access_auditoria(): void
    {
        $operacao = User::factory()->create(['role' => 'operacao']);
        $financeiro = User::factory()->create(['role' => 'financeiro']);

        $this->actingAs($operacao)->get(route('auditoria.index'))->assertForbidden();
        $this->actingAs($financeiro)->get(route('auditoria.index'))->assertForbidden();
    }

    public function test_index_actions_match_user_profile(): void
    {
        $diretoria = User::factory()->create(['role' => 'diretoria']);
        $comercial = User::factory()->create(['role' => 'comercial']);
        $financeiro = User::factory()->create(['role' => 'financeiro']);
        $operacao = User::factory()->create(['role' => 'operacao']);

        $this->actingAs($diretoria)
            ->get(route('leads.index'))
            ->assertOk()
            ->assertDontSee('+ Novo Lead');

        $this->actingAs($comercial)
            ->get(route('leads.index'))
            ->assertOk()
            ->assertSee('+ Novo Lead');

        $this->actingAs($diretoria)
            ->get(route('contratos.index'))
            ->assertOk()
            ->assertDontSee('+ Novo Contrato');

        $this->actingAs($financeiro)
            ->get(route('contratos.index'))
            ->assertOk()
            ->assertSee('+ Novo Contrato');

        $this->actingAs($diretoria)
            ->get(route('suporte.index'))
            ->assertOk()
            ->assertDontSee('+ Novo Chamado');

        $this->actingAs($operacao)
            ->get(route('suporte.index'))
            ->assertOk()
            ->assertSee('+ Novo Chamado');
    }

    public function test_sidebar_navigation_matches_comercial_profile(): void
    {
        $user = User::factory()->create(['role' => 'comercial']);

        $this->actingAs($user)
            ->get(route('dashboard.index'))
            ->assertOk()
            ->assertSee('href="'.route('crm.index').'"', false)
            ->assertSee('href="'.route('clientes.index').'"', false)
            ->assertSee('href="'.route('leads.index').'"', false)
            ->assertSee('href="'.route('propostas.index').'"', false)
            ->assertDontSee('href="'.route('contratos.index').'"', false)
            ->assertDontSee('href="'.route('financeiro.index').'"', false)
            ->assertDontSee('href="'.route('mrr.index').'"', false)
            ->assertDontSee('href="'.route('projetos.index').'"', false)
            ->assertDontSee('href="'.route('implantacoes.index').'"', false)
            ->assertDontSee('href="'.route('suporte.index').'"', false)
            ->assertDontSee('href="'.route('auditoria.index').'"', false);
    }

    public function test_sidebar_navigation_matches_financeiro_profile(): void
    {
        $user = User::factory()->create(['role' => 'financeiro']);

        $this->actingAs($user)
            ->get(route('dashboard.index'))
            ->assertOk()
            ->assertSee('href="'.route('clientes.index').'"', false)
            ->assertSee('href="'.route('contratos.index').'"', false)
            ->assertSee('href="'.route('financeiro.index').'"', false)
            ->assertSee('href="'.route('mrr.index').'"', false)
            ->assertDontSee('href="'.route('crm.index').'"', false)
            ->assertDontSee('href="'.route('leads.index').'"', false)
            ->assertDontSee('href="'.route('propostas.index').'"', false)
            ->assertDontSee('href="'.route('projetos.index').'"', false)
            ->assertDontSee('href="'.route('implantacoes.index').'"', false)
            ->assertDontSee('href="'.route('suporte.index').'"', false)
            ->assertDontSee('href="'.route('auditoria.index').'"', false);
    }

    public function test_sidebar_navigation_matches_operacao_profile(): void
    {
        $user = User::factory()->create(['role' => 'operacao']);

        $this->actingAs($user)
            ->get(route('dashboard.index'))
            ->assertOk()
            ->assertSee('href="'.route('clientes.index').'"', false)
            ->assertSee('href="'.route('projetos.index').'"', false)
            ->assertSee('href="'.route('implantacoes.index').'"', false)
            ->assertSee('href="'.route('suporte.index').'"', false)
            ->assertDontSee('href="'.route('crm.index').'"', false)
            ->assertDontSee('href="'.route('leads.index').'"', false)
            ->assertDontSee('href="'.route('propostas.index').'"', false)
            ->assertDontSee('href="'.route('contratos.index').'"', false)
            ->assertDontSee('href="'.route('financeiro.index').'"', false)
            ->assertDontSee('href="'.route('mrr.index').'"', false)
            ->assertDontSee('href="'.route('auditoria.index').'"', false);
    }

    public function test_sidebar_navigation_matches_diretoria_profile(): void
    {
        $user = User::factory()->create(['role' => 'diretoria']);

        $this->actingAs($user)
            ->get(route('dashboard.index'))
            ->assertOk()
            ->assertSee('href="'.route('crm.index').'"', false)
            ->assertSee('href="'.route('clientes.index').'"', false)
            ->assertSee('href="'.route('leads.index').'"', false)
            ->assertSee('href="'.route('propostas.index').'"', false)
            ->assertSee('href="'.route('contratos.index').'"', false)
            ->assertSee('href="'.route('financeiro.index').'"', false)
            ->assertSee('href="'.route('mrr.index').'"', false)
            ->assertSee('href="'.route('projetos.index').'"', false)
            ->assertSee('href="'.route('implantacoes.index').'"', false)
            ->assertSee('href="'.route('suporte.index').'"', false)
            ->assertSee('href="'.route('auditoria.index').'"', false);
    }

    public function test_sidebar_navigation_matches_admin_profile(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->get(route('dashboard.index'))
            ->assertOk()
            ->assertSee('href="'.route('crm.index').'"', false)
            ->assertSee('href="'.route('clientes.index').'"', false)
            ->assertSee('href="'.route('leads.index').'"', false)
            ->assertSee('href="'.route('propostas.index').'"', false)
            ->assertSee('href="'.route('contratos.index').'"', false)
            ->assertSee('href="'.route('financeiro.index').'"', false)
            ->assertSee('href="'.route('mrr.index').'"', false)
            ->assertSee('href="'.route('projetos.index').'"', false)
            ->assertSee('href="'.route('implantacoes.index').'"', false)
            ->assertSee('href="'.route('suporte.index').'"', false)
            ->assertSee('href="'.route('auditoria.index').'"', false)
            ->assertSee('href="'.route('usuarios.index').'"', false);
    }
}
