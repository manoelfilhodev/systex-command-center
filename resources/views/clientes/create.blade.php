@extends('layouts.systex')

@section('content')

<div class="topbar">
    <div>
        <div class="topbar-kicker">AURORA + MERCURIUS</div>
        <h1>Novo Cliente</h1>
        <p>Cadastro oficial da base comercial e contratual da SYSTEX.</p>
    </div>

    <div class="topbar-actions">
        <a href="{{ route('clientes.index') }}" class="btn-secondary">
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

<form method="POST" action="{{ route('clientes.store') }}">
    @csrf

    <div class="page-panel">

        <div class="form-grid">

            <div class="form-group">
                <label>Razão Social *</label>
                <input
                    type="text"
                    name="razao_social"
                    value="{{ old('razao_social') }}"
                    required
                >
            </div>

            <div class="form-group">
                <label>Nome Fantasia</label>
                <input
                    type="text"
                    name="nome_fantasia"
                    value="{{ old('nome_fantasia') }}"
                >
            </div>

            <div class="form-group">
                <label>CNPJ</label>
                <input
                    type="text"
                    name="cnpj"
                    value="{{ old('cnpj') }}"
                >
            </div>

            <div class="form-group">
                <label>Email</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                >
            </div>

            <div class="form-group">
                <label>Telefone</label>
                <input
                    type="text"
                    name="telefone"
                    value="{{ old('telefone') }}"
                >
            </div>

            <div class="form-group">
                <label>Responsável</label>
                <input
                    type="text"
                    name="responsavel"
                    value="{{ old('responsavel') }}"
                >
            </div>

            <div class="form-group">
                <label>Segmento</label>
                <input
                    type="text"
                    name="segmento"
                    value="{{ old('segmento') }}"
                >
            </div>

            <div class="form-group">
                <label>Status *</label>
                <select name="status" required>
                    <option value="prospect">Prospect</option>
                    <option value="ativo">Ativo</option>
                    <option value="inativo">Inativo</option>
                    <option value="suspenso">Suspenso</option>
                </select>
            </div>

            <div class="form-group">
                <label>Cidade</label>
                <input
                    type="text"
                    name="cidade"
                    value="{{ old('cidade') }}"
                >
            </div>

            <div class="form-group">
                <label>Estado</label>
                <input
                    type="text"
                    name="estado"
                    maxlength="2"
                    value="{{ old('estado') }}"
                >
            </div>

            <div class="form-group full">
                <label>Observações</label>
                <textarea
                    name="observacoes"
                    rows="4"
                >{{ old('observacoes') }}</textarea>
            </div>

        </div>

    </div>

    <div style="height: 20px;"></div>

    <div class="form-actions">
        <button type="submit" class="btn-primary">
            Salvar Cliente
        </button>

        <a href="{{ route('clientes.index') }}" class="btn-secondary">
            Cancelar
        </a>
    </div>

</form>

@endsection
