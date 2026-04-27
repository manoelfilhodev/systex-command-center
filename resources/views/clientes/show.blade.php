@extends('layouts.systex')

@section('content')

<div class="topbar">
    <div>
        <div class="topbar-kicker">AURORA + MERCURIUS</div>
        <h1>{{ $cliente->nome_fantasia ?? $cliente->razao_social }}</h1>
        <p>Visualização completa do cliente.</p>
    </div>

    <div class="topbar-actions">
        <a href="{{ route('clientes.edit', $cliente) }}" class="btn-secondary">Editar</a>
        <a href="{{ route('clientes.index') }}" class="btn-secondary">Voltar</a>
    </div>
</div>

<div class="page-panel">
    <div class="form-grid">

        <div class="form-group">
            <label>Razão Social</label>
            <input type="text" value="{{ $cliente->razao_social }}" readonly>
        </div>

        <div class="form-group">
            <label>Nome Fantasia</label>
            <input type="text" value="{{ $cliente->nome_fantasia ?? '-' }}" readonly>
        </div>

        <div class="form-group">
            <label>CNPJ</label>
            <input type="text" value="{{ $cliente->cnpj ?? '-' }}" readonly>
        </div>

        <div class="form-group">
            <label>Status</label>
            <input type="text" value="{{ ucfirst($cliente->status) }}" readonly>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="text" value="{{ $cliente->email ?? '-' }}" readonly>
        </div>

        <div class="form-group">
            <label>Telefone</label>
            <input type="text" value="{{ $cliente->telefone ?? '-' }}" readonly>
        </div>

        <div class="form-group">
            <label>Responsável</label>
            <input type="text" value="{{ $cliente->responsavel ?? '-' }}" readonly>
        </div>

        <div class="form-group">
            <label>Segmento</label>
            <input type="text" value="{{ $cliente->segmento ?? '-' }}" readonly>
        </div>

        <div class="form-group">
            <label>Cidade</label>
            <input type="text" value="{{ $cliente->cidade ?? '-' }}" readonly>
        </div>

        <div class="form-group">
            <label>Estado</label>
            <input type="text" value="{{ $cliente->estado ?? '-' }}" readonly>
        </div>

        <div class="form-group full">
            <label>Observações</label>
            <textarea rows="4" readonly>{{ $cliente->observacoes }}</textarea>
        </div>

    </div>
</div>

@endsection
