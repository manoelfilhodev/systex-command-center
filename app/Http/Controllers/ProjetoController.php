<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Contrato;
use App\Models\Projeto;
use App\Services\ProjetoService;
use Illuminate\Http\Request;

class ProjetoController extends Controller
{
    public function __construct(
        private readonly ProjetoService $projetoService
    ) {}

    public function index()
    {
        $projetos = Projeto::with(['cliente', 'contrato', 'implantacao'])
            ->latest()
            ->paginate(10);

        return view('projetos.index', [
            'projetos' => $projetos,
            'summary' => $this->projetoService->summary(),
            'atrasados' => $this->projetoService->delayed(),
        ]);
    }

    public function create()
    {
        return view('projetos.create', $this->formData());
    }

    public function store(Request $request)
    {
        Projeto::create($this->validatedData($request));

        return redirect()
            ->route('projetos.index')
            ->with('success', 'Projeto criado com sucesso.');
    }

    public function show(Projeto $projeto)
    {
        $projeto->load(['cliente', 'contrato', 'implantacao.etapas']);

        return view('projetos.show', compact('projeto'));
    }

    public function edit(Projeto $projeto)
    {
        return view('projetos.edit', array_merge(
            ['projeto' => $projeto],
            $this->formData()
        ));
    }

    public function update(Request $request, Projeto $projeto)
    {
        $projeto->update($this->validatedData($request));

        return redirect()
            ->route('projetos.show', $projeto)
            ->with('success', 'Projeto atualizado com sucesso.');
    }

    public function destroy(Projeto $projeto)
    {
        $projeto->delete();

        return redirect()
            ->route('projetos.index')
            ->with('success', 'Projeto removido com sucesso.');
    }

    private function formData(): array
    {
        return [
            'clientes' => Cliente::orderBy('nome_fantasia')->get(),
            'contratos' => Contrato::with('cliente')->latest()->get(),
        ];
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'cliente_id' => ['nullable', 'exists:_tb_clientes,id'],
            'contrato_id' => ['nullable', 'exists:_tb_contratos,id'],
            'nome' => ['required', 'string', 'max:255'],
            'tipo' => ['required', 'in:wms,erp,crm,desenvolvimento_sob_demanda'],
            'status' => ['required', 'in:planejado,em_andamento,pausado,homologacao,concluido,cancelado'],
            'data_inicio' => ['nullable', 'date'],
            'data_prevista_entrega' => ['nullable', 'date', 'after_or_equal:data_inicio'],
            'data_entrega' => ['nullable', 'date', 'after_or_equal:data_inicio'],
            'responsavel' => ['nullable', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
        ]);

        if (empty($data['cliente_id']) && ! empty($data['contrato_id'])) {
            $data['cliente_id'] = Contrato::find($data['contrato_id'])?->cliente_id;
        }

        return $data;
    }
}
