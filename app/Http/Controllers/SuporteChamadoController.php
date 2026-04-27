<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Contrato;
use App\Models\SuporteChamado;
use App\Services\AuditoriaService;
use App\Services\SuporteService;
use Illuminate\Http\Request;

class SuporteChamadoController extends Controller
{
    public function __construct(
        private readonly SuporteService $suporteService,
        private readonly AuditoriaService $auditoriaService
    ) {}

    public function index()
    {
        $chamados = SuporteChamado::with(['cliente', 'contrato'])
            ->latest()
            ->paginate(10);

        return view('suporte.index', [
            'chamados' => $chamados,
            'summary' => $this->suporteService->summary(),
            'vencidos' => $this->suporteService->overdue(),
        ]);
    }

    public function create()
    {
        return view('suporte.create', $this->formData());
    }

    public function store(Request $request)
    {
        $chamado = SuporteChamado::create($this->validatedData($request));
        $this->auditoriaService->registrar('suporte', 'chamado_criado', $chamado, $chamado->titulo, [
            'prioridade' => $chamado->prioridade,
            'status' => $chamado->status,
        ]);

        return redirect()
            ->route('suporte.index')
            ->with('success', 'Chamado de suporte criado com sucesso.');
    }

    public function show(SuporteChamado $suporte)
    {
        $suporte->load(['cliente', 'contrato']);

        return view('suporte.show', ['chamado' => $suporte]);
    }

    public function edit(SuporteChamado $suporte)
    {
        return view('suporte.edit', array_merge(
            ['chamado' => $suporte],
            $this->formData()
        ));
    }

    public function update(Request $request, SuporteChamado $suporte)
    {
        $suporte->update($this->validatedData($request));
        $this->auditoriaService->registrar('suporte', 'chamado_atualizado', $suporte, $suporte->titulo, [
            'prioridade' => $suporte->prioridade,
            'status' => $suporte->status,
        ]);

        return redirect()
            ->route('suporte.show', $suporte)
            ->with('success', 'Chamado de suporte atualizado com sucesso.');
    }

    public function destroy(SuporteChamado $suporte)
    {
        $suporte->delete();

        return redirect()
            ->route('suporte.index')
            ->with('success', 'Chamado de suporte removido com sucesso.');
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
            'titulo' => ['required', 'string', 'max:255'],
            'categoria' => ['required', 'in:incidente,duvida,melhoria,integracao,infraestrutura,outros'],
            'prioridade' => ['required', 'in:baixa,media,alta,critica'],
            'status' => ['required', 'in:aberto,em_atendimento,aguardando_cliente,resolvido,cancelado'],
            'canal' => ['required', 'in:email,whatsapp,telefone,portal,interno'],
            'aberto_em' => ['required', 'date'],
            'prazo_sla' => ['nullable', 'date', 'after_or_equal:aberto_em'],
            'resolvido_em' => ['nullable', 'date', 'after_or_equal:aberto_em'],
            'responsavel' => ['nullable', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'resolucao' => ['nullable', 'string'],
        ]);

        if (empty($data['cliente_id']) && ! empty($data['contrato_id'])) {
            $data['cliente_id'] = Contrato::find($data['contrato_id'])?->cliente_id;
        }

        return $data;
    }
}
