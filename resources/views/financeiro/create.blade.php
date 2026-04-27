@extends('layouts.systex')

@section('content')

<div class="topbar">
    <div>
        <div class="topbar-kicker">CRONOS</div>
        <h1>Novo Lançamento Financeiro</h1>
        <p>Cadastro de receitas, despesas, vencimentos e status financeiro.</p>
    </div>

    <div class="topbar-actions">
        <a href="{{ route('financeiro.index') }}" class="btn-secondary">
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

<form method="POST" action="{{ route('financeiro.store') }}">
    @csrf

    <div class="page-panel">
        <div class="form-grid">

            <div class="form-group">
                <label>Tipo *</label>
                <select name="tipo" required>
                    <option value="receita">Receita</option>
                    <option value="despesa">Despesa</option>
                </select>
            </div>

            <div class="form-group">
                <label>Categoria *</label>
                <select name="categoria" required>
                    <option value="implantacao">Implantação</option>
                    <option value="mensalidade">Mensalidade</option>
                    <option value="suporte">Suporte</option>
                    <option value="customizacao">Customização</option>
                    <option value="consultoria">Consultoria</option>
                    <option value="outros">Outros</option>
                </select>
            </div>

            <div class="form-group full">
                <label>Descrição *</label>
                <input
                    type="text"
                    name="descricao"
                    value="{{ old('descricao') }}"
                    required
                    placeholder="Ex.: Mensalidade contrato Systex WMS"
                >
            </div>

            <div class="form-group">
                <label>Valor *</label>
                <input
                    type="number"
                    step="0.01"
                    name="valor"
                    value="{{ old('valor') }}"
                    required
                    placeholder="0.00"
                >
            </div>

            <div class="form-group">
                <label>Status *</label>
                <select name="status" required>
                    <option value="pendente">Pendente</option>
                    <option value="pago">Pago</option>
                    <option value="atrasado">Atrasado</option>
                    <option value="cancelado">Cancelado</option>
                </select>
            </div>

            <div class="form-group">
                <label>Data de Vencimento *</label>
                <input
                    type="date"
                    name="data_vencimento"
                    value="{{ old('data_vencimento', now()->format('Y-m-d')) }}"
                    required
                >
            </div>

            <div class="form-group">
                <label>Data de Pagamento</label>
                <input
                    type="date"
                    name="data_pagamento"
                    value="{{ old('data_pagamento') }}"
                >
            </div>

            <div class="form-group">
                <label>Recorrente?</label>
                <select name="recorrente">
                    <option value="0">Não</option>
                    <option value="1">Sim</option>
                </select>
            </div>

            <div class="form-group full">
                <label>Observações</label>
                <textarea
                    name="observacoes"
                    rows="4"
                    placeholder="Observações internas do lançamento..."
                >{{ old('observacoes') }}</textarea>
            </div>

        </div>
    </div>

    <div style="height: 20px;"></div>

    <div class="form-actions">
        <button type="submit" class="btn-primary">
            Salvar Lançamento
        </button>

        <a href="{{ route('financeiro.index') }}" class="btn-secondary">
            Cancelar
        </a>
    </div>

</form>

@endsection
