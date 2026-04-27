<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadTarefa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LeadTarefaController extends Controller
{
    public function store(Request $request, Lead $lead)
    {
        $data = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'prioridade' => ['required', Rule::in(['baixa', 'media', 'alta'])],
            'data_vencimento' => ['required', 'date'],
        ]);

        $lead->tarefas()->create([
            ...$data,
            'user_id' => $request->user()->id,
            'status' => 'pendente',
        ]);

        return redirect()
            ->route('leads.show', $lead)
            ->with('success', 'Tarefa comercial criada com sucesso.');
    }

    public function complete(LeadTarefa $tarefa)
    {
        $tarefa->update([
            'status' => 'concluida',
            'concluida_em' => now(),
        ]);

        return redirect()
            ->route('leads.show', $tarefa->lead)
            ->with('success', 'Tarefa comercial concluída com sucesso.');
    }

    public function destroy(LeadTarefa $tarefa)
    {
        $lead = $tarefa->lead;

        $tarefa->delete();

        return redirect()
            ->route('leads.show', $lead)
            ->with('success', 'Tarefa comercial removida com sucesso.');
    }
}
