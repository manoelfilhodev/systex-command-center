@extends('layouts.systex')

@section('content')

<div class="topbar">
    <div>
        <div class="topbar-kicker">CRONOS</div>
        <h1>Editar Lançamento</h1>
        <p>Atualização de receita, despesa, vencimento e status financeiro.</p>
    </div>

    <div class="topbar-actions">
        <a href="{{ route('financeiro.show', $financeiro) }}" class="btn-secondary">Visualizar</a>
        <a href="{{ route('financeiro.index') }}" class="btn-secondary">Voltar</a>
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

<form method="POST" action="{{ route('financeiro.update', $financeiro) }}">
    @csrf
    @method('PUT')

    <div class="page-panel">
        <div class="form-grid">

            <div class="form-group">
                <label>Tipo *</label>
                <select name="tipo" required>
                    @foreach(['receita', 'despesa'] as $tipo)
                        <option value="{{ $tipo }}" {{ old('tipo', $financeiro->tipo) === $tipo ? 'selected' : '' }}>
                            {{ ucfirst($tipo) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Categoria *</label>
                <select name="categoria" required>
                    @foreach(['implantacao', 'mensalidade', 'suporte', 'customizacao', 'consultoria', 'outros'] as $categoria)
                        <option value="{{ $categoria }}" {{ old('categoria', $financeiro->categoria) === $categoria ? 'selected' : '' }}>
                            {{ ucfirst($categoria) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group full">
                <label>Descrição *</label>
                <input type="text" name="descricao" value="{{ old('descricao', $financeiro->descricao) }}" required>
            </div>

            <div class="form-group">
                <label>Valor *</label>
                <input type="number" step="0.01" name="valor" value="{{ old('valor', $financeiro->valor) }}" required>
            </div>

            <div class="form-group">
                <label>Status *</label>
                <select name="status" required>
                    @foreach(['pendente', 'pago', 'atrasado', 'cancelado'] as $status)
                        <option value="{{ $status }}" {{ old('status', $financeiro->status) === $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Data de Vencimento *</label>
                <input type="date" name="data_vencimento" value="{{ old('data_vencimento', $financeiro->data_vencimento) }}" required>
            </div>

            <div class="form-group">
                <label>Data de Pagamento</label>
                <input type="date" name="data_pagamento" value="{{ old('data_pagamento', $financeiro->data_pagamento) }}">
            </div>

            <div class="form-group">
                <label>Recorrente?</label>
                <select name="recorrente">
                    <option value="0" {{ old('recorrente', $financeiro->recorrente) == 0 ? 'selected' : '' }}>Não</option>
                    <option value="1" {{ old('recorrente', $financeiro->recorrente) == 1 ? 'selected' : '' }}>Sim</option>
                </select>
            </div>

            <div class="form-group full">
                <label>Observações</label>
                <textarea name="observacoes" rows="4">{{ old('observacoes', $financeiro->observacoes) }}</textarea>
            </div>

        </div>
    </div>

    <div style="height: 20px;"></div>

    <div class="form-actions">
        <button type="submit" class="btn-primary">Atualizar Lançamento</button>
        <a href="{{ route('financeiro.show', $financeiro) }}" class="btn-secondary">Cancelar</a>
    </div>
</form>

@endsection
