@extends('layouts.systex')

@section('content')

<div class="topbar">
    <div>
        <div class="topbar-kicker">AURORA + MERCURIUS</div>
        <h1>Editar Cliente</h1>
        <p>Atualização dos dados comerciais e contratuais do cliente.</p>
    </div>

    <div class="topbar-actions">
        <a href="{{ route('clientes.show', $cliente) }}" class="btn-secondary">Visualizar</a>
        <a href="{{ route('clientes.index') }}" class="btn-secondary">Voltar</a>
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

<form method="POST" action="{{ route('clientes.update', $cliente) }}">
    @csrf
    @method('PUT')

    <div class="page-panel">
        <div class="form-grid">

            <div class="form-group">
                <label>Razão Social *</label>
                <input type="text" name="razao_social" value="{{ old('razao_social', $cliente->razao_social) }}" required>
            </div>

            <div class="form-group">
                <label>Nome Fantasia</label>
                <input type="text" name="nome_fantasia" value="{{ old('nome_fantasia', $cliente->nome_fantasia) }}">
            </div>

            <div class="form-group">
                <label>CNPJ</label>
                <input type="text" name="cnpj" value="{{ old('cnpj', $cliente->cnpj) }}">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email', $cliente->email) }}">
            </div>

            <div class="form-group">
                <label>Telefone</label>
                <input type="text" name="telefone" value="{{ old('telefone', $cliente->telefone) }}">
            </div>

            <div class="form-group">
                <label>Responsável</label>
                <input type="text" name="responsavel" value="{{ old('responsavel', $cliente->responsavel) }}">
            </div>

            <div class="form-group">
                <label>Segmento</label>
                <input type="text" name="segmento" value="{{ old('segmento', $cliente->segmento) }}">
            </div>

            <div class="form-group">
                <label>Status *</label>
                <select name="status" required>
                    @foreach(['prospect', 'ativo', 'inativo', 'suspenso'] as $status)
                        <option value="{{ $status }}" {{ old('status', $cliente->status) === $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Cidade</label>
                <input type="text" name="cidade" value="{{ old('cidade', $cliente->cidade) }}">
            </div>

            <div class="form-group">
                <label>Estado</label>
                <input type="text" name="estado" maxlength="2" value="{{ old('estado', $cliente->estado) }}">
            </div>

            <div class="form-group full">
                <label>Observações</label>
                <textarea name="observacoes" rows="4">{{ old('observacoes', $cliente->observacoes) }}</textarea>
            </div>

        </div>
    </div>

    <div style="height: 20px;"></div>

    <div class="form-actions">
        <button type="submit" class="btn-primary">Atualizar Cliente</button>
        <a href="{{ route('clientes.show', $cliente) }}" class="btn-secondary">Cancelar</a>
    </div>

</form>

@endsection
