<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Implantacao;
use Illuminate\Http\Request;

class ImplantacaoController extends Controller
{
    public function index()
    {
        $implantacoes = Implantacao::with(['contrato.cliente', 'etapas', 'aditivos'])
            ->latest()
            ->get();

        return view('implantacoes.index', compact('implantacoes'));
    }

    public function show(Implantacao $implantacao)
    {
        $implantacao->load(['contrato.cliente', 'etapas', 'aditivos']);

        return view('implantacoes.show', compact('implantacao'));
    }

    public function create()
    {
        $contratos = Contrato::with('cliente')
            ->whereDoesntHave('implantacao')
            ->latest()
            ->get();

        return view('implantacoes.create', compact('contratos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'contrato_id' => ['required', 'exists:_tb_contratos,id', 'unique:_tb_implantacoes,contrato_id'],
            'status' => ['required', 'in:pendente,em_andamento,homologacao,go_live,concluida,pausada,cancelada'],
            'data_inicio' => ['nullable', 'date'],
            'data_go_live' => ['nullable', 'date', 'after_or_equal:data_inicio'],
            'responsavel' => ['nullable', 'string', 'max:255'],
            'observacoes' => ['nullable', 'string'],
        ]);

        Implantacao::create($data);

        return redirect()
            ->route('implantacoes.index')
            ->with('success', 'Implantação criada com sucesso.');
    }

    public function edit(Implantacao $implantacao)
    {
        $contratos = Contrato::with('cliente')
            ->where(function ($query) use ($implantacao) {
                $query->whereDoesntHave('implantacao')
                    ->orWhere('id', $implantacao->contrato_id);
            })
            ->latest()
            ->get();

        return view('implantacoes.edit', compact('implantacao', 'contratos'));
    }

    public function update(Request $request, Implantacao $implantacao)
    {
        $data = $request->validate([
            'contrato_id' => ['required', 'exists:_tb_contratos,id', 'unique:_tb_implantacoes,contrato_id,' . $implantacao->id],
            'status' => ['required', 'in:pendente,em_andamento,homologacao,go_live,concluida,pausada,cancelada'],
            'data_inicio' => ['nullable', 'date'],
            'data_go_live' => ['nullable', 'date', 'after_or_equal:data_inicio'],
            'responsavel' => ['nullable', 'string', 'max:255'],
            'observacoes' => ['nullable', 'string'],
        ]);

        $implantacao->update($data);

        return redirect()
            ->route('implantacoes.show', $implantacao)
            ->with('success', 'Implantação atualizada com sucesso.');
    }

    public function destroy(Implantacao $implantacao)
    {
        $implantacao->delete();

        return redirect()
            ->route('implantacoes.index')
            ->with('success', 'Implantação removida com sucesso.');
    }
}
