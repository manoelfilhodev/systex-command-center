<?php

namespace App\Http\Controllers;

use App\Models\Financeiro;
use Illuminate\Http\Request;

class FinanceiroController extends Controller
{
    public function index()
    {
        $receitas = Financeiro::where('tipo', 'receita')
            ->where('status', 'pago')
            ->sum('valor');

        $despesas = Financeiro::where('tipo', 'despesa')
            ->where('status', 'pago')
            ->sum('valor');

        $pendenteReceber = Financeiro::where('tipo', 'receita')
            ->whereIn('status', ['pendente', 'atrasado'])
            ->sum('valor');

        $pendentePagar = Financeiro::where('tipo', 'despesa')
            ->whereIn('status', ['pendente', 'atrasado'])
            ->sum('valor');

        $lancamentos = Financeiro::with(['cliente', 'contrato'])
            ->latest()
            ->paginate(10);

        return view('financeiro.index', compact(
            'receitas',
            'despesas',
            'pendenteReceber',
            'pendentePagar',
            'lancamentos'
        ));
    }

    public function create()
    {
        return view('financeiro.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tipo' => ['required', 'in:receita,despesa'],
            'categoria' => ['required', 'in:implantacao,mensalidade,suporte,customizacao,consultoria,outros'],
            'descricao' => ['required', 'string', 'max:255'],
            'valor' => ['required', 'numeric', 'min:0'],
            'data_vencimento' => ['required', 'date'],
            'data_pagamento' => ['nullable', 'date'],
            'status' => ['required', 'in:pendente,pago,atrasado,cancelado'],
            'recorrente' => ['required', 'boolean'],
            'observacoes' => ['nullable', 'string'],
        ]);

        Financeiro::create($data);

        return redirect()
            ->route('financeiro.index')
            ->with('success', 'Lançamento financeiro criado com sucesso.');
    }

    public function show(Financeiro $financeiro)
    {
        return view('financeiro.show', compact('financeiro'));
    }

    public function edit(Financeiro $financeiro)
    {
        return view('financeiro.edit', compact('financeiro'));
    }

    public function update(Request $request, Financeiro $financeiro)
    {
        $data = $request->validate([
            'tipo' => ['required', 'in:receita,despesa'],
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
