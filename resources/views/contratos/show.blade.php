@extends('layouts.systex')

@section('content')

<div class="topbar">
    <div>
        <div class="topbar-kicker">CRONOS + THEMIS</div>
        <h1>{{ $contrato->numero }}</h1>
        <p>Visualização completa do contrato.</p>
    </div>

    <div class="topbar-actions">
        <a href="{{ route('contratos.edit', $contrato) }}" class="btn-secondary">Editar</a>
        <a href="{{ route('contratos.index') }}" class="btn-secondary">Voltar</a>
    </div>
</div>

<div class="grid">
    <div class="card">
        <div class="card-title">Cliente</div>
        <div class="card-value">{{ $contrato->cliente->nome_fantasia ?? $contrato->cliente->razao_social ?? '-' }}</div>
    </div>

    <div class="card">
        <div class="card-title">Valor Mensal</div>
        <div class="card-value">R$ {{ number_format($contrato->valor_mensal, 2, ',', '.') }}</div>
    </div>

    <div class="card">
        <div class="card-title">Status</div>
        <div class="card-value">{{ ucfirst($contrato->status) }}</div>
    </div>
</div>

<div style="height: 20px;"></div>

<div class="page-panel">
    <div class="form-grid">
        <div class="form-group">
            <label>Proposta</label>
            <input type="text" value="{{ $contrato->proposta->titulo ?? 'Sem proposta vinculada' }}" readonly>
        </div>

        <div class="form-group">
            <label>Tipo</label>
            <input type="text" value="{{ ucfirst(str_replace('_', ' ', $contrato->tipo)) }}" readonly>
        </div>

        <div class="form-group">
            <label>Valor Implantação</label>
            <input type="text" value="R$ {{ number_format($contrato->valor_implantacao, 2, ',', '.') }}" readonly>
        </div>

        <div class="form-group">
            <label>Data Início</label>
            <input type="text" value="{{ \Carbon\Carbon::parse($contrato->data_inicio)->format('d/m/Y') }}" readonly>
        </div>

        <div class="form-group">
            <label>Data Fim</label>
            <input type="text" value="{{ $contrato->data_fim ? \Carbon\Carbon::parse($contrato->data_fim)->format('d/m/Y') : '-' }}" readonly>
        </div>

        <div class="form-group">
            <label>SLA</label>
            <input type="text" value="{{ $contrato->sla ?? '-' }}" readonly>
        </div>

        <div class="form-group full">
            <label>Observações</label>
            <textarea rows="4" readonly>{{ $contrato->observacoes }}</textarea>
        </div>
    </div>
</div>

@endsection
