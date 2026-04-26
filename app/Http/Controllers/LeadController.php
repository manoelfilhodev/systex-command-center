<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

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
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'empresa' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'telefone' => ['nullable', 'string', 'max:30'],
            'origem' => ['nullable', 'string', 'max:255'],
            'valor_estimado' => ['nullable', 'numeric'],
            'proximo_contato' => ['nullable', 'date'],
            'observacoes' => ['nullable', 'string'],
        ]);

        $validated['status'] = 'novo';

        Lead::create($validated);

        return redirect()
            ->route('leads.index')
            ->with('success', 'Lead cadastrado com sucesso.');
    }
}
