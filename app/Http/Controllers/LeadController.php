<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Services\CommercialPipelineService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LeadController extends Controller
{
    public function index()
    {
        $leads = Lead::latest()->paginate(15);

        return view('leads.index', compact('leads'));
    }

    public function create()
    {
        return view('leads.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validatedData($request);

        $validated['status'] = $validated['status'] ?? 'novo';

        Lead::create($validated);

        return redirect()
            ->route('leads.index')
            ->with('success', 'Lead cadastrado com sucesso.');
    }

    public function show(Lead $lead)
    {
        $lead->load(['interacoes.user', 'tarefas.user']);

        return view('leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        return view('leads.edit', [
            'lead' => $lead,
            'statuses' => CommercialPipelineService::statusKeys(),
        ]);
    }

    public function update(Request $request, Lead $lead)
    {
        $lead->update($this->validatedData($request, requireStatus: true));

        return redirect()
            ->route('leads.show', $lead)
            ->with('success', 'Lead atualizado com sucesso.');
    }

    public function advanceStage(Request $request, Lead $lead)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(CommercialPipelineService::statusKeys())],
        ]);

        $lead->update(['status' => $data['status']]);

        return redirect()
            ->route('crm.index')
            ->with('success', 'Etapa do lead atualizada com sucesso.');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()
            ->route('leads.index')
            ->with('success', 'Lead removido com sucesso.');
    }

    private function validatedData(Request $request, bool $requireStatus = false): array
    {
        return $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'empresa' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'telefone' => ['nullable', 'string', 'max:30'],
            'origem' => ['nullable', 'string', 'max:255'],
            'status' => [$requireStatus ? 'required' : 'nullable', Rule::in(CommercialPipelineService::statusKeys())],
            'valor_estimado' => ['nullable', 'numeric', 'min:0'],
            'proximo_contato' => ['nullable', 'date'],
            'observacoes' => ['nullable', 'string'],
        ]);
    }
}
