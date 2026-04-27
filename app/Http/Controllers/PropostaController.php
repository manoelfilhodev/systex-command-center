<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Proposta;
use App\Models\PropostaItem;
use App\Models\Servico;
use App\Services\AuditoriaService;
use App\Services\PropostaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PropostaController extends Controller
{
    public function __construct(
        private readonly PropostaService $propostaService,
        private readonly AuditoriaService $auditoriaService
    ) {}

    public function index()
    {
        $propostas = Proposta::with(['lead', 'cliente'])
            ->latest()
            ->paginate(10);

        return view('propostas.index', compact('propostas'));
    }

    public function create(Request $request)
    {
        $leads = Lead::orderBy('nome')->get();
        $servicos = Servico::orderBy('nome')->get();

        $leadSelecionado = $request->get('lead_id');

        return view('propostas.create', compact('leads', 'servicos', 'leadSelecionado'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'lead_id' => ['nullable', 'exists:_tb_leads,id'],
            'cliente_id' => ['nullable', 'exists:_tb_clientes,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:rascunho,enviada,negociacao,aprovada,recusada,cancelada'],
            'valor_implantacao' => ['nullable', 'numeric', 'min:0'],
            'valor_recorrente' => ['nullable', 'numeric', 'min:0'],
            'data_envio' => ['nullable', 'date'],
            'data_validade' => ['nullable', 'date'],
            'escopo' => ['nullable', 'string'],
            'observacoes' => ['nullable', 'string'],

            'itens' => ['nullable', 'array'],
            'itens.*.servico_id' => ['nullable', 'exists:_tb_servicos,id'],
            'itens.*.descricao' => ['nullable', 'string', 'max:255'],
            'itens.*.tipo' => ['nullable', 'in:implantacao,mensalidade,customizacao,suporte,integracao,consultoria'],
            'itens.*.quantidade' => ['nullable', 'integer', 'min:1'],
            'itens.*.valor_unitario' => ['nullable', 'numeric', 'min:0'],
            'itens.*.recorrente' => ['nullable'],
        ]);

        $proposta = DB::transaction(function () use ($data, $request) {
            $valorImplantacao = $data['valor_implantacao'] ?? 0;
            $valorRecorrente = $data['valor_recorrente'] ?? 0;

            $proposta = Proposta::create([
                'lead_id' => $data['lead_id'] ?? null,
                'cliente_id' => $data['cliente_id'] ?? null,
                'numero' => $this->gerarNumeroProposta(),
                'titulo' => $data['titulo'],
                'status' => $data['status'],
                'valor_implantacao' => $valorImplantacao,
                'valor_recorrente' => $valorRecorrente,
                'valor_total' => 0,
                'data_envio' => $data['data_envio'] ?? null,
                'data_validade' => $data['data_validade'] ?? null,
                'escopo' => $data['escopo'] ?? null,
                'observacoes' => $data['observacoes'] ?? null,
            ]);

            $totalItens = $this->salvarItens($proposta, $request->input('itens', []));

            $proposta->update([
                'valor_total' => $valorImplantacao + $valorRecorrente + $totalItens,
            ]);

            return $proposta->refresh();
        });

        $this->propostaService->syncLeadStatus($proposta);

        return redirect()
            ->route('propostas.show', $proposta)
            ->with('success', 'Proposta criada com sucesso.');
    }

    public function show(Proposta $proposta)
    {
        $proposta->load(['lead', 'cliente', 'itens.servico']);

        return view('propostas.show', compact('proposta'));
    }

    public function edit(Proposta $proposta)
    {
        $proposta->load('itens');

        $leads = Lead::orderBy('nome')->get();
        $servicos = Servico::orderBy('nome')->get();

        return view('propostas.edit', compact('proposta', 'leads', 'servicos'));
    }

    public function update(Request $request, Proposta $proposta)
    {
        $data = $request->validate([
            'lead_id' => ['nullable', 'exists:_tb_leads,id'],
            'cliente_id' => ['nullable', 'exists:_tb_clientes,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:rascunho,enviada,negociacao,aprovada,recusada,cancelada'],
            'valor_implantacao' => ['nullable', 'numeric', 'min:0'],
            'valor_recorrente' => ['nullable', 'numeric', 'min:0'],
            'data_envio' => ['nullable', 'date'],
            'data_validade' => ['nullable', 'date'],
            'escopo' => ['nullable', 'string'],
            'observacoes' => ['nullable', 'string'],

            'itens' => ['nullable', 'array'],
            'itens.*.servico_id' => ['nullable', 'exists:_tb_servicos,id'],
            'itens.*.descricao' => ['nullable', 'string', 'max:255'],
            'itens.*.tipo' => ['nullable', 'in:implantacao,mensalidade,customizacao,suporte,integracao,consultoria'],
            'itens.*.quantidade' => ['nullable', 'integer', 'min:1'],
            'itens.*.valor_unitario' => ['nullable', 'numeric', 'min:0'],
            'itens.*.recorrente' => ['nullable'],
        ]);

        $proposta = DB::transaction(function () use ($data, $request, $proposta) {
            $valorImplantacao = $data['valor_implantacao'] ?? 0;
            $valorRecorrente = $data['valor_recorrente'] ?? 0;

            $proposta->update([
                'lead_id' => $data['lead_id'] ?? null,
                'cliente_id' => $data['cliente_id'] ?? null,
                'titulo' => $data['titulo'],
                'status' => $data['status'],
                'valor_implantacao' => $valorImplantacao,
                'valor_recorrente' => $valorRecorrente,
                'data_envio' => $data['data_envio'] ?? null,
                'data_validade' => $data['data_validade'] ?? null,
                'escopo' => $data['escopo'] ?? null,
                'observacoes' => $data['observacoes'] ?? null,
            ]);

            $proposta->itens()->delete();

            $totalItens = $this->salvarItens($proposta, $request->input('itens', []));

            $proposta->update([
                'valor_total' => $valorImplantacao + $valorRecorrente + $totalItens,
            ]);

            return $proposta->refresh();
        });

        $this->propostaService->syncLeadStatus($proposta);

        return redirect()
            ->route('propostas.show', $proposta)
            ->with('success', 'Proposta atualizada com sucesso.');
    }

    public function approve(Proposta $proposta)
    {
        $proposta->update(['status' => 'aprovada']);

        $this->propostaService->syncLeadStatus($proposta->refresh());
        $this->auditoriaService->registrar('propostas', 'aprovada', $proposta, $proposta->numero, [
            'valor_total' => $proposta->valor_total,
        ]);

        return redirect()
            ->route('contratos.create', ['proposta_id' => $proposta->id])
            ->with('success', 'Proposta aprovada. Formalize o contrato para concluir o fechamento.');
    }

    public function destroy(Proposta $proposta)
    {
        $proposta->delete();

        return redirect()
            ->route('propostas.index')
            ->with('success', 'Proposta removida com sucesso.');
    }

    private function salvarItens(Proposta $proposta, array $itens): float
    {
        $totalItens = 0;

        foreach ($itens as $item) {
            if (empty($item['descricao']) && empty($item['servico_id'])) {
                continue;
            }

            $quantidade = (int) ($item['quantidade'] ?? 1);
            $valorUnitario = (float) ($item['valor_unitario'] ?? 0);
            $valorTotal = $quantidade * $valorUnitario;

            PropostaItem::create([
                'proposta_id' => $proposta->id,
                'servico_id' => $item['servico_id'] ?? null,
                'descricao' => $item['descricao'] ?? 'Item da proposta',
                'tipo' => $item['tipo'] ?? 'implantacao',
                'quantidade' => $quantidade,
                'valor_unitario' => $valorUnitario,
                'valor_total' => $valorTotal,
                'recorrente' => isset($item['recorrente']),
            ]);

            $totalItens += $valorTotal;
        }

        return $totalItens;
    }

    private function gerarNumeroProposta(): string
    {
        do {
            $numero = 'PROP-'.now()->format('Ymd').'-'.strtoupper(Str::random(5));
        } while (Proposta::where('numero', $numero)->exists());

        return $numero;
    }
}
