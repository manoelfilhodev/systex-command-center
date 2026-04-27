<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Contrato;
use App\Models\Proposta;
use App\Services\AuditoriaService;
use App\Services\ContratoService;
use App\Services\MrrService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContratoController extends Controller
{
    public function __construct(
        private readonly ContratoService $contratoService,
        private readonly MrrService $mrrService,
        private readonly AuditoriaService $auditoriaService
    ) {}

    public function index()
    {
        $contratos = Contrato::with(['cliente', 'proposta'])
            ->latest()
            ->paginate(10);

        return view('contratos.index', [
            'contratos' => $contratos,
            'summary' => $this->contratoService->summary(),
            'vencendo' => $this->contratoService->expiringContracts(),
        ]);
    }

    public function create(Request $request)
    {
        $clientes = Cliente::orderBy('nome_fantasia')->get();
        $propostas = Proposta::orderBy('titulo')->get();
        $propostaSelecionada = $request->get('proposta_id');
        $propostaBase = $propostaSelecionada
            ? Proposta::find($propostaSelecionada)
            : null;

        return view('contratos.create', compact('clientes', 'propostas', 'propostaSelecionada', 'propostaBase'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente_id' => ['required', 'exists:_tb_clientes,id'],
            'proposta_id' => ['nullable', 'exists:_tb_propostas,id'],
            'tipo' => ['required', 'in:projeto_unico,recorrente,hibrido'],
            'status' => ['required', 'in:ativo,suspenso,encerrado,cancelado'],
            'valor_implantacao' => ['nullable', 'numeric', 'min:0'],
            'valor_mensal' => ['nullable', 'numeric', 'min:0'],
            'data_inicio' => ['required', 'date'],
            'data_fim' => ['nullable', 'date'],
            'sla' => ['nullable', 'string', 'max:255'],
            'observacoes' => ['nullable', 'string'],
        ]);

        $contrato = Contrato::create([
            'cliente_id' => $data['cliente_id'],
            'proposta_id' => $data['proposta_id'] ?? null,
            'numero' => $this->gerarNumeroContrato(),
            'tipo' => $data['tipo'],
            'status' => $data['status'],
            'valor_implantacao' => $data['valor_implantacao'] ?? 0,
            'valor_mensal' => $data['valor_mensal'] ?? 0,
            'data_inicio' => $data['data_inicio'],
            'data_fim' => $data['data_fim'] ?? null,
            'sla' => $data['sla'] ?? null,
            'observacoes' => $data['observacoes'] ?? null,
        ]);

        $this->mrrService->syncContratoForCurrentMonth($contrato);
        $this->auditoriaService->registrar('contratos', 'criado', $contrato, $contrato->numero, [
            'status' => $contrato->status,
            'valor_mensal' => $contrato->valor_mensal,
        ]);

        return redirect()
            ->route('contratos.index')
            ->with('success', 'Contrato criado com sucesso.');
    }

    public function show(Contrato $contrato)
    {
        $contrato->load(['cliente', 'proposta']);

        return view('contratos.show', compact('contrato'));
    }

    public function edit(Contrato $contrato)
    {
        $clientes = Cliente::orderBy('nome_fantasia')->get();
        $propostas = Proposta::orderBy('titulo')->get();

        return view('contratos.edit', compact('contrato', 'clientes', 'propostas'));
    }

    public function update(Request $request, Contrato $contrato)
    {
        $data = $request->validate([
            'cliente_id' => ['required', 'exists:_tb_clientes,id'],
            'proposta_id' => ['nullable', 'exists:_tb_propostas,id'],
            'tipo' => ['required', 'in:projeto_unico,recorrente,hibrido'],
            'status' => ['required', 'in:ativo,suspenso,encerrado,cancelado'],
            'valor_implantacao' => ['nullable', 'numeric', 'min:0'],
            'valor_mensal' => ['nullable', 'numeric', 'min:0'],
            'data_inicio' => ['required', 'date'],
            'data_fim' => ['nullable', 'date'],
            'sla' => ['nullable', 'string', 'max:255'],
            'observacoes' => ['nullable', 'string'],
        ]);

        $contrato->update([
            'cliente_id' => $data['cliente_id'],
            'proposta_id' => $data['proposta_id'] ?? null,
            'tipo' => $data['tipo'],
            'status' => $data['status'],
            'valor_implantacao' => $data['valor_implantacao'] ?? 0,
            'valor_mensal' => $data['valor_mensal'] ?? 0,
            'data_inicio' => $data['data_inicio'],
            'data_fim' => $data['data_fim'] ?? null,
            'sla' => $data['sla'] ?? null,
            'observacoes' => $data['observacoes'] ?? null,
        ]);

        $this->mrrService->syncContratoForCurrentMonth($contrato->refresh());
        $this->auditoriaService->registrar('contratos', 'atualizado', $contrato, $contrato->numero, [
            'status' => $contrato->status,
            'valor_mensal' => $contrato->valor_mensal,
        ]);

        return redirect()
            ->route('contratos.index')
            ->with('success', 'Contrato atualizado com sucesso.');
    }

    public function destroy(Contrato $contrato)
    {
        $contrato->delete();

        return redirect()
            ->route('contratos.index')
            ->with('success', 'Contrato removido com sucesso.');
    }

    private function gerarNumeroContrato(): string
    {
        do {
            $numero = 'CONT-'.now()->format('Ymd').'-'.strtoupper(Str::random(5));
        } while (Contrato::where('numero', $numero)->exists());

        return $numero;
    }
}
