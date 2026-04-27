@extends('layouts.systex')

@section('content')

<div class="topbar">
    <div>
        <div class="topbar-kicker">CRONOS + THEMIS</div>
        <h1>Editar Contrato</h1>
        <p>Atualização contratual, vigência e receita recorrente.</p>
    </div>

    <div class="topbar-actions">
        <a href="{{ route('contratos.show', $contrato) }}" class="btn-secondary">Visualizar</a>
        <a href="{{ route('contratos.index') }}" class="btn-secondary">Voltar</a>
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

<form method="POST" action="{{ route('contratos.update', $contrato) }}">
    @csrf
    @method('PUT')

    <div class="page-panel">
        <div class="form-grid">

            <div class="form-group">
                <label>Cliente *</label>
                <select name="cliente_id" required>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}" {{ old('cliente_id', $contrato->cliente_id) == $cliente->id ? 'selected' : '' }}>
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
                        <option value="{{ $proposta->id }}" {{ old('proposta_id', $contrato->proposta_id) == $proposta->id ? 'selected' : '' }}>
                            {{ $proposta->numero }} — {{ $proposta->titulo }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Tipo *</label>
                <select name="tipo" required>
                    @foreach(['hibrido', 'recorrente', 'projeto_unico'] as $tipo)
                        <option value="{{ $tipo }}" {{ old('tipo', $contrato->tipo) === $tipo ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $tipo)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Status *</label>
                <select name="status" required>
                    @foreach(['ativo', 'suspenso', 'encerrado', 'cancelado'] as $status)
                        <option value="{{ $status }}" {{ old('status', $contrato->status) === $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Valor de Implantação</label>
                <input type="number" step="0.01" name="valor_implantacao" value="{{ old('valor_implantacao', $contrato->valor_implantacao) }}">
            </div>

            <div class="form-group">
                <label>Valor Mensal</label>
                <input type="number" step="0.01" name="valor_mensal" value="{{ old('valor_mensal', $contrato->valor_mensal) }}">
            </div>

            <div class="form-group">
                <label>Data de Início *</label>
                <input type="date" name="data_inicio" value="{{ old('data_inicio', $contrato->data_inicio) }}" required>
            </div>

            <div class="form-group">
                <label>Data de Fim</label>
                <input type="date" name="data_fim" value="{{ old('data_fim', $contrato->data_fim) }}">
            </div>

            <div class="form-group full">
                <label>SLA</label>
                <input type="text" name="sla" value="{{ old('sla', $contrato->sla) }}">
            </div>

            <div class="form-group full">
                <label>Observações</label>
                <textarea name="observacoes" rows="4">{{ old('observacoes', $contrato->observacoes) }}</textarea>
            </div>

        </div>
    </div>

    <div style="height: 20px;"></div>

    <div class="form-actions">
        <button type="submit" class="btn-primary">Atualizar Contrato</button>
        <a href="{{ route('contratos.show', $contrato) }}" class="btn-secondary">Cancelar</a>
    </div>
</form>

@endsection
