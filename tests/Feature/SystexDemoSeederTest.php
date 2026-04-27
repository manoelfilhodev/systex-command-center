<?php

namespace Tests\Feature;

use App\Models\AuditoriaEvento;
use App\Models\Cliente;
use App\Models\Contrato;
use App\Models\Financeiro;
use App\Models\Implantacao;
use App\Models\ImplantacaoEtapa;
use App\Models\Lead;
use App\Models\MrrHistorico;
use App\Models\Projeto;
use App\Models\Proposta;
use App\Models\SuporteChamado;
use Database\Seeders\SystexDemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystexDemoSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_seeder_creates_executive_flow_once(): void
    {
        $this->seed();
        $this->seed(SystexDemoSeeder::class);

        $this->assertSame(1, Cliente::where('cnpj', '12345678000190')->count());
        $this->assertSame(1, Lead::where('email', 'operacoes@logalpha.com.br')->count());
        $this->assertSame(1, Proposta::where('numero', 'PROP-DEMO-001')->count());
        $this->assertSame(1, Contrato::where('numero', 'CONT-DEMO-001')->count());

        $contrato = Contrato::where('numero', 'CONT-DEMO-001')->firstOrFail();

        $this->assertSame(1, MrrHistorico::where('contrato_id', $contrato->id)->count());
        $this->assertSame(3, Financeiro::where('contrato_id', $contrato->id)->count());
        $this->assertSame(1, Projeto::where('contrato_id', $contrato->id)->count());
        $this->assertSame(1, Implantacao::where('contrato_id', $contrato->id)->count());
        $this->assertSame(5, ImplantacaoEtapa::count());
        $this->assertSame(1, SuporteChamado::where('contrato_id', $contrato->id)->count());
        $this->assertSame(1, AuditoriaEvento::where('modulo', 'demo')->count());
    }
}
