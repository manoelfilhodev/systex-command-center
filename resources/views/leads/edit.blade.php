@extends('layouts.systex')

@section('content')

<div class="topbar">
    <div>
        <div class="topbar-kicker">MERCURIUS</div>
        <h1>Editar Lead</h1>
        <p>Atualização da oportunidade comercial e avanço do funil.</p>
    </div>

    <div class="topbar-actions">
        <a href="{{ route('leads.show', $lead) }}" class="btn-secondary">Visualizar</a>
        <a href="{{ route('leads.index') }}" class="btn-secondary">Voltar</a>
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

<form method="POST" action="{{ route('leads.update', $lead) }}">
    @csrf
    @method('PUT')

    <div class="page-panel">
        <div class="form-grid">
            <div class="form-group">
                <label>Nome *</label>
                <input type="text" name="nome" value="{{ old('nome', $lead->nome) }}" required>
            </div>

            <div class="form-group">
                <label>Empresa</label>
                <input type="text" name="empresa" value="{{ old('empresa', $lead->empresa) }}">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email', $lead->email) }}">
            </div>

            <div class="form-group">
                <label>Telefone</label>
                <input type="text" name="telefone" value="{{ old('telefone', $lead->telefone) }}">
            </div>

            <div class="form-group">
                <label>Origem</label>
                <input type="text" name="origem" value="{{ old('origem', $lead->origem) }}">
            </div>

            <div class="form-group">
                <label>Status *</label>
                <select name="status" required>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" @selected(old('status', $lead->status) === $status)>
                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Valor Estimado</label>
                <input type="number" step="0.01" name="valor_estimado" value="{{ old('valor_estimado', $lead->valor_estimado) }}">
            </div>

            <div class="form-group">
                <label>Próximo Contato</label>
                <input type="date" name="proximo_contato" value="{{ old('proximo_contato', optional($lead->proximo_contato)->format('Y-m-d')) }}">
            </div>

            <div class="form-group full">
                <label>Observações</label>
                <textarea name="observacoes" rows="5">{{ old('observacoes', $lead->observacoes) }}</textarea>
            </div>
        </div>
    </div>

    <div style="height: 20px;"></div>

    <div class="form-actions">
        <button type="submit" class="btn-primary">Atualizar Lead</button>
        <a href="{{ route('leads.show', $lead) }}" class="btn-secondary">Cancelar</a>
    </div>
</form>

@endsection
