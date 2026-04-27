@extends('layouts.systex')

@section('content')

<div class="topbar">
    <div>
        <div class="topbar-kicker">MERCURIUS + CRONOS</div>
        <h1>Editar Proposta</h1>
        <p>Atualização comercial da proposta e seus valores estratégicos.</p>
    </div>

    <div class="topbar-actions">
        <a href="{{ route('propostas.show', $proposta) }}" class="btn-secondary">
            Visualizar
        </a>

        <a href="{{ route('propostas.index') }}" class="btn-secondary">
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

<form method="POST" action="{{ route('propostas.update', $proposta) }}">
    @csrf
    @method('PUT')

    <div class="page-panel">
        <div class="form-grid">

            <div class="form-group">
                <label>Lead Vinculado</label>
                <select name="lead_id">
                    <option value="">Selecione</option>
                    @foreach($leads as $lead)
                        <option
                            value="{{ $lead->id }}"
                            {{ $proposta->lead_id == $lead->id ? 'selected' : '' }}
                        >
                            {{ $lead->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Status Comercial *</label>
                <select name="status" required>
                    @foreach([
                        'rascunho',
                        'enviada',
                        'negociacao',
                        'aprovada',
                        'recusada',
                        'cancelada'
                    ] as $status)
                        <option
                            value="{{ $status }}"
                            {{ $proposta->status == $status ? 'selected' : '' }}
                        >
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group full">
                <label>Título da Proposta *</label>
                <input
                    type="text"
                    name="titulo"
                    required
                    value="{{ old('titulo', $proposta->titulo) }}"
                >
            </div>

            <div class="form-group">
                <label>Valor Implantação</label>
                <input
                    type="number"
                    step="0.01"
                    name="valor_implantacao"
                    value="{{ old('valor_implantacao', $proposta->valor_implantacao) }}"
                >
            </div>

            <div class="form-group">
                <label>Valor Recorrente</label>
                <input
                    type="number"
                    step="0.01"
                    name="valor_recorrente"
                    value="{{ old('valor_recorrente', $proposta->valor_recorrente) }}"
                >
            </div>

            <div class="form-group full">
                <label>Escopo</label>
                <textarea
                    name="escopo"
                    rows="4"
                >{{ old('escopo', $proposta->escopo) }}</textarea>
            </div>

            <div class="form-group full">
                <label>Observações</label>
                <textarea
                    name="observacoes"
                    rows="4"
                >{{ old('observacoes', $proposta->observacoes) }}</textarea>
            </div>

        </div>
    </div>

    <div style="height: 20px;"></div>

    <div class="form-actions">
        <button type="submit" class="btn-primary">
            Atualizar Proposta
        </button>

        <a href="{{ route('propostas.show', $proposta) }}" class="btn-secondary">
            Cancelar
        </a>
    </div>

</form>

@endsection
