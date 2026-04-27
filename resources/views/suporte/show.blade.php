@extends('layouts.systex')

@section('content')
    <div class="topbar">
        <div>
            <div class="topbar-kicker">TITAN</div>
            <h1>{{ $chamado->titulo }}</h1>
            <p>Visão detalhada do chamado, SLA, prioridade e resolução.</p>
        </div>

        <div class="topbar-actions">
            <a href="{{ route('suporte.edit', $chamado) }}" class="btn-secondary">Editar</a>
            <a href="{{ route('suporte.index') }}" class="btn-secondary">Voltar</a>
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
            <div class="card-value">{{ $chamado->cliente->nome_fantasia ?? $chamado->cliente->razao_social ?? '-' }}</div>
        </div>
        <div class="card">
            <div class="card-title">Contrato</div>
            <div class="card-value">{{ $chamado->contrato->numero ?? '-' }}</div>
        </div>
        <div class="card">
            <div class="card-title">Prioridade</div>
            <div class="card-value">{{ ucfirst($chamado->prioridade) }}</div>
        </div>
        <div class="card">
            <div class="card-title">Status</div>
            <div class="card-value">{{ ucfirst(str_replace('_', ' ', $chamado->status)) }}</div>
        </div>
    </div>

    <div style="height: 20px;"></div>

    <div class="page-panel">
        <div class="form-grid">
            <div class="form-group">
                <label>Categoria</label>
                <input type="text" value="{{ ucfirst($chamado->categoria) }}" readonly>
            </div>

            <div class="form-group">
                <label>Canal</label>
                <input type="text" value="{{ ucfirst($chamado->canal) }}" readonly>
            </div>

            <div class="form-group">
                <label>Aberto em</label>
                <input type="text" value="{{ $chamado->aberto_em->format('d/m/Y H:i') }}" readonly>
            </div>

            <div class="form-group">
                <label>Prazo SLA</label>
                <input type="text" value="{{ $chamado->prazo_sla ? $chamado->prazo_sla->format('d/m/Y H:i') : '-' }}" readonly>
            </div>

            <div class="form-group">
                <label>Resolvido em</label>
                <input type="text" value="{{ $chamado->resolvido_em ? $chamado->resolvido_em->format('d/m/Y H:i') : '-' }}" readonly>
            </div>

            <div class="form-group">
                <label>Responsável</label>
                <input type="text" value="{{ $chamado->responsavel ?? '-' }}" readonly>
            </div>

            <div class="form-group full">
                <label>Descrição</label>
                <textarea rows="5" readonly>{{ $chamado->descricao }}</textarea>
            </div>

            <div class="form-group full">
                <label>Resolução</label>
                <textarea rows="5" readonly>{{ $chamado->resolucao }}</textarea>
            </div>
        </div>
    </div>
@endsection
