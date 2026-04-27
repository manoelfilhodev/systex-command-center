<?php

namespace App\Http\Controllers;

use App\Models\Implantacao;
use App\Models\ImplantacaoEtapa;
use Illuminate\Http\Request;

class ImplantacaoEtapaController extends Controller
{
    public function store(Request $request, Implantacao $implantacao)
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'ordem' => ['nullable', 'integer'],
            'status' => ['required', 'in:pendente,em_andamento,concluida,bloqueada'],
            'data_inicio' => ['nullable', 'date'],
            'data_fim' => ['nullable', 'date'],
            'observacoes' => ['nullable', 'string'],
        ]);

        $data['implantacao_id'] = $implantacao->id;
        $data['ordem'] = $data['ordem'] ?? 1;

        ImplantacaoEtapa::create($data);

        return redirect()
            ->route('implantacoes.show', $implantacao)
            ->with('success', 'Etapa adicionada com sucesso.');
    }

    public function update(Request $request, ImplantacaoEtapa $etapa)
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'ordem' => ['nullable', 'integer'],
            'status' => ['required', 'in:pendente,em_andamento,concluida,bloqueada'],
            'data_inicio' => ['nullable', 'date'],
            'data_fim' => ['nullable', 'date'],
            'observacoes' => ['nullable', 'string'],
        ]);

        $etapa->update($data);

        return back()->with('success', 'Etapa atualizada com sucesso.');
    }

    public function destroy(ImplantacaoEtapa $etapa)
    {
        $implantacao = $etapa->implantacao;

        $etapa->delete();

        return redirect()
            ->route('implantacoes.show', $implantacao)
            ->with('success', 'Etapa removida com sucesso.');
    }
}
