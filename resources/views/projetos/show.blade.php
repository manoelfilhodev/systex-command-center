@extends('layouts.systex')

@section('content')
    <div class="topbar">
        <div>
            <div class="topbar-kicker">TITAN</div>
            <h1>{{ $projeto->nome }}</h1>
            <p>Visão operacional do projeto, vínculo comercial e situação da implantação.</p>
        </div>

        <div class="topbar-actions">
            <a href="{{ route('projetos.edit', $projeto) }}" class="btn-secondary">Editar</a>
            <a href="{{ route('projetos.index') }}" class="btn-secondary">Voltar</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid">
        <div class="card">
            <div class="card-title">Cliente</div>
            <div class="card-value">{{ $projeto->cliente->nome_fantasia ?? $projeto->cliente->razao_social ?? '-' }}</div>
        </div>

        <div class="card">
            <div class="card-title">Contrato</div>
            <div class="card-value">{{ $projeto->contrato->numero ?? '-' }}</div>
        </div>

        <div class="card">
            <div class="card-title">Status</div>
            <div class="card-value">{{ ucfirst(str_replace('_', ' ', $projeto->status)) }}</div>
        </div>

        <div class="card">
            <div class="card-title">Implantação</div>
            <div class="card-value">{{ $projeto->implantacao ? ucfirst(str_replace('_', ' ', $projeto->implantacao->status)) : 'Não iniciada' }}</div>
        </div>
    </div>

    <div style="height: 20px;"></div>

    <div class="page-panel">
        <div class="form-grid">
            <div class="form-group">
                <label>Tipo</label>
                <input type="text" value="{{ ucfirst(str_replace('_', ' ', $projeto->tipo)) }}" readonly>
            </div>

            <div class="form-group">
                <label>Responsável</label>
                <input type="text" value="{{ $projeto->responsavel ?? '-' }}" readonly>
            </div>

            <div class="form-group">
                <label>Início</label>
                <input type="text" value="{{ $projeto->data_inicio ? $projeto->data_inicio->format('d/m/Y') : '-' }}" readonly>
            </div>

            <div class="form-group">
                <label>Prazo Previsto</label>
                <input type="text" value="{{ $projeto->data_prevista_entrega ? $projeto->data_prevista_entrega->format('d/m/Y') : '-' }}" readonly>
            </div>

            <div class="form-group">
                <label>Entrega Real</label>
                <input type="text" value="{{ $projeto->data_entrega ? $projeto->data_entrega->format('d/m/Y') : '-' }}" readonly>
            </div>

            <div class="form-group full">
                <label>Descrição</label>
                <textarea rows="5" readonly>{{ $projeto->descricao }}</textarea>
            </div>
        </div>
    </div>
@endsection
