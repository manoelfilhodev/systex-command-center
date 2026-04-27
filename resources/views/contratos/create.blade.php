@extends('layouts.systex')

@section('content')

<div class="topbar">
    <div>
        <div class="topbar-kicker">CRONOS + THEMIS</div>
        <h1>Novo Contrato</h1>
        <p>Formalização comercial vinculando cliente, proposta, vigência e receita recorrente.</p>
    </div>

    <div class="topbar-actions">
        <a href="{{ route('contratos.index') }}" class="btn-secondary">
            Voltar
        </a>
    </div>
</div>

@if($errors->any())
    <div class="alert-error">
        <strong>Existem erros no formulário:</strong>
        <ul style="margin-top:10px; padding-left:18px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('contratos.store') }}">
    @csrf

    <div class="page-panel">
        <div class="form-grid">

            <div class="form-group">
                <label>Cliente *</label>
                <select name="cliente_id" required>
                    <option value="">Selecione</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                            {{ $cliente->nome_fantasia ?? $cliente->razao_social }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Proposta Vinculada</label>
                <select name="proposta_id">
                    <option value="">Sem proposta vinculada</option>
                    @foreach($propostas as $proposta)
                        <option value="{{ $proposta->id }}" {{ old('proposta_id') == $proposta->id ? 'selected' : '' }}>
                            {{ $proposta->numero }} — {{ $proposta->titulo }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Tipo de Contrato *</label>
                <select name="tipo" required>
                    <option value="hibrido" {{ old('tipo') === 'hibrido' ? 'selected' : '' }}>Híbrido</option>
                    <option value="recorrente" {{ old('tipo') === 'recorrente' ? 'selected' : '' }}>Recorrente</option>
                    <option value="projeto_unico" {{ old('tipo') === 'projeto_unico' ? 'selected' : '' }}>Projeto Único</option>
                </select>
            </div>

            <div class="form-group">
                <label>Status *</label>
                <select name="status" required>
                    <option value="ativo" {{ old('status') === 'ativo' ? 'selected' : '' }}>Ativo</option>
                    <option value="suspenso" {{ old('status') === 'suspenso' ? 'selected' : '' }}>Suspenso</option>
                    <option value="encerrado" {{ old('status') === 'encerrado' ? 'selected' : '' }}>Encerrado</option>
                    <option value="cancelado" {{ old('status') === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>

            <div class="form-group">
                <label>Valor de Implantação</label>
                <input
                    type="number"
                    step="0.01"
                    name="valor_implantacao"
                    value="{{ old('valor_implantacao') }}"
                    placeholder="0.00"
                >
            </div>

            <div class="form-group">
                <label>Valor Mensal</label>
                <input
                    type="number"
                    step="0.01"
                    name="valor_mensal"
                    value="{{ old('valor_mensal') }}"
                    placeholder="0.00"
                >
            </div>

            <div class="form-group">
                <label>Data de Início *</label>
                <input
                    type="date"
                    name="data_inicio"
                    value="{{ old('data_inicio', now()->format('Y-m-d')) }}"
                    required
                >
            </div>

            <div class="form-group">
                <label>Data de Fim</label>
                <input
                    type="date"
                    name="data_fim"
                    value="{{ old('data_fim') }}"
                >
            </div>

            <div class="form-group full">
                <label>SLA</label>
                <input
                    type="text"
                    name="sla"
                    value="{{ old('sla') }}"
                    placeholder="Ex.: Atendimento em até 24h úteis"
                >
            </div>

            <div class="form-group full">
                <label>Observações</label>
                <textarea
                    name="observacoes"
                    rows="4"
                    placeholder="Condições comerciais, cláusulas relevantes, observações internas..."
                >{{ old('observacoes') }}</textarea>
            </div>

        </div>
    </div>

    <div style="height: 20px;"></div>

    <div class="page-panel">
        <div style="display:grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap:20px;">
            <div class="card">
                <div class="card-title">Implantação</div>
                <div class="card-value" id="summary-implantacao">R$ 0,00</div>
            </div>

            <div class="card">
                <div class="card-title">Receita Mensal</div>
                <div class="card-value" id="summary-mensal">R$ 0,00</div>
            </div>

            <div class="card">
                <div class="card-title">Receita Anualizada</div>
                <div class="card-value" id="summary-anual">R$ 0,00</div>
            </div>
        </div>
    </div>

    <div style="height: 20px;"></div>

    <div class="form-actions">
        <button type="submit" class="btn-primary">
            Salvar Contrato
        </button>

        <a href="{{ route('contratos.index') }}" class="btn-secondary">
            Cancelar
        </a>
    </div>

</form>

<script>
    const moneyContrato = new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    });

    function calcularResumoContrato() {
        const implantacao = parseFloat(document.querySelector('[name="valor_implantacao"]').value || 0);
        const mensal = parseFloat(document.querySelector('[name="valor_mensal"]').value || 0);
        const anual = mensal * 12;

        document.getElementById('summary-implantacao').innerText = moneyContrato.format(implantacao);
        document.getElementById('summary-mensal').innerText = moneyContrato.format(mensal);
        document.getElementById('summary-anual').innerText = moneyContrato.format(anual);
    }

    document.addEventListener('input', function (event) {
        if (
            event.target.name === 'valor_implantacao' ||
            event.target.name === 'valor_mensal'
        ) {
            calcularResumoContrato();
        }
    });

    calcularResumoContrato();
</script>

@endsection
