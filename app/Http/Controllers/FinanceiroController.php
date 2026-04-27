<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Contrato;
use App\Models\Financeiro;
use App\Services\AuditoriaService;
use App\Services\FinanceiroService;
use Illuminate\Http\Request;

class FinanceiroController extends Controller
{
    public function __construct(
        private readonly FinanceiroService $financeiroService,
        private readonly AuditoriaService $auditoriaService
    ) {}

    public function index()
    {
        $lancamentos = Financeiro::with(['cliente', 'contrato'])
            ->latest()
            ->paginate(10);

        return view('financeiro.index', [
            'summary' => $this->financeiroService->summary(),
            'vencidos' => $this->financeiroService->overdue(),
            'proximos' => $this->financeiroService->upcoming(),
            'lancamentos' => $lancamentos,
        ]);
    }

    public function create()
    {
        $clientes = Cliente::orderBy('nome_fantasia')->get();
        $contratos = Contrato::with('cliente')->latest()->get();

        return view('financeiro.create', compact('clientes', 'contratos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tipo' => ['required', 'in:receita,despesa'],
            'cliente_id' => ['nullable', 'exists:_tb_clientes,id'],
            'contrato_id' => ['nullable', 'exists:_tb_contratos,id'],
            'categoria' => ['required', 'in:implantacao,mensalidade,suporte,customizacao,consultoria,outros'],
            'descricao' => ['required', 'string', 'max:255'],
            'valor' => ['required', 'numeric', 'min:0'],
            'data_vencimento' => ['required', 'date'],
            'data_pagamento' => ['nullable', 'date'],
            'status' => ['required', 'in:pendente,pago,atrasado,cancelado'],
            'recorrente' => ['required', 'boolean'],
            'observacoes' => ['nullable', 'string'],
        ]);

        $financeiro = Financeiro::create($data);
        $this->auditoriaService->registrar('financeiro', 'lancamento_criado', $financeiro, $financeiro->descricao, [
            'tipo' => $financeiro->tipo,
            'status' => $financeiro->status,
            'valor' => $financeiro->valor,
        ]);

        return redirect()
            ->route('financeiro.index')
            ->with('success', 'Lançamento financeiro criado com sucesso.');
    }

    public function show(Financeiro $financeiro)
    {
        $financeiro->load(['cliente', 'contrato']);

        return view('financeiro.show', compact('financeiro'));
    }

    public function edit(Financeiro $financeiro)
    {
        $clientes = Cliente::orderBy('nome_fantasia')->get();
        $contratos = Contrato::with('cliente')->latest()->get();

        return view('financeiro.edit', compact('financeiro', 'clientes', 'contratos'));
    }

    public function update(Request $request, Financeiro $financeiro)
    {
        $data = $request->validate([
            'tipo' => ['required', 'in:receita,despesa'],
            'cliente_id' => ['nullable', 'exists:_tb_clientes,id'],
            'contrato_id' => ['nullable', 'exists:_tb_contratos,id'],
            'categoria' => ['required', 'in:implantacao,mensalidade,suporte,customizacao,consultoria,outros'],
            'descricao' => ['required', 'string', 'max:255'],
            'valor' => ['required', 'numeric', 'min:0'],
            'data_vencimento' => ['required', 'date'],
            'data_pagamento' => ['nullable', 'date'],
            'status' => ['required', 'in:pendente,pago,atrasado,cancelado'],
            'recorrente' => ['required', 'boolean'],
            'observacoes' => ['nullable', 'string'],
        ]);

        $financeiro->update($data);
        $this->auditoriaService->registrar('financeiro', 'lancamento_atualizado', $financeiro, $financeiro->descricao, [
            'tipo' => $financeiro->tipo,
            'status' => $financeiro->status,
            'valor' => $financeiro->valor,
        ]);

        return redirect()
            ->route('financeiro.index')
            ->with('success', 'Lançamento financeiro atualizado com sucesso.');
    }

    public function destroy(Financeiro $financeiro)
    {
        $financeiro->delete();

        return redirect()
            ->route('financeiro.index')
            ->with('success', 'Lançamento financeiro removido com sucesso.');
    }
}
