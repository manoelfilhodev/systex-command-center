@extends('layouts.systex')

@section('content')

<div class="topbar">
    <div>
        <div class="topbar-kicker">CRONOS</div>
        <h1>{{ $financeiro->descricao }}</h1>
        <p>Visualização completa do lançamento financeiro.</p>
    </div>

    <div class="topbar-actions">
        <a href="{{ route('financeiro.edit', $financeiro) }}" class="btn-secondary">Editar</a>
        <a href="{{ route('financeiro.index') }}" class="btn-secondary">Voltar</a>
    </div>
</div>

<div class="grid">
    <div class="card">
        <div class="card-title">Tipo</div>
        <div class="card-value">{{ ucfirst($financeiro->tipo) }}</div>
    </div>

    <div class="card">
        <div class="card-title">Valor</div>
        <div class="card-value">R$ {{ number_format($financeiro->valor, 2, ',', '.') }}</div>
    </div>

    <div class="card">
        <div class="card-title">Status</div>
        <div class="card-value">{{ ucfirst($financeiro->status) }}</div>
    </div>
</div>

<div style="height: 20px;"></div>

<div class="page-panel">
    <div class="form-grid">

        <div class="form-group">
            <label>Categoria</label>
            <input type="text" value="{{ ucfirst($financeiro->categoria) }}" readonly>
        </div>

        <div class="form-group">
            <label>Data de Vencimento</label>
            <input type="text" value="{{ \Carbon\Carbon::parse($financeiro->data_vencimento)->format('d/m/Y') }}" readonly>
        </div>

        <div class="form-group">
            <label>Data de Pagamento</label>
            <input type="text" value="{{ $financeiro->data_pagamento ? \Carbon\Carbon::parse($financeiro->data_pagamento)->format('d/m/Y') : '-' }}" readonly>
        </div>

        <div class="form-group">
            <label>Recorrente</label>
            <input type="text" value="{{ $financeiro->recorrente ? 'Sim' : 'Não' }}" readonly>
        </div>

        <div class="form-group full">
            <label>Observações</label>
            <textarea rows="4" readonly>{{ $financeiro->observacoes }}</textarea>
        </div>

    </div>
</div>

@endsection
