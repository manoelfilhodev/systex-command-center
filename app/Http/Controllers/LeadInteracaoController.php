<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadInteracao;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LeadInteracaoController extends Controller
{
    private const TIPOS = [
        'contato',
        'reuniao',
        'diagnostico',
        'proposta',
        'negociacao',
        'observacao',
    ];

    public function store(Request $request, Lead $lead)
    {
        $data = $request->validate([
            'tipo' => ['required', Rule::in(self::TIPOS)],
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'data_interacao' => ['required', 'date'],
        ]);

        $lead->interacoes()->create([
            ...$data,
            'user_id' => $request->user()->id,
        ]);

        return redirect()
            ->route('leads.show', $lead)
            ->with('success', 'Interação comercial registrada com sucesso.');
    }

    public function destroy(LeadInteracao $interacao)
    {
        $lead = $interacao->lead;

        $interacao->delete();

        return redirect()
            ->route('leads.show', $lead)
            ->with('success', 'Interação comercial removida com sucesso.');
    }
}
