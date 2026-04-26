@extends('layouts.systex')

@section('content')
    <x-topbar
        title="Novo Lead"
        subtitle="Cadastro inicial de oportunidade comercial"
    />

    @if ($errors->any())
        <div class="alert-error">
            Revise os campos obrigatórios antes de continuar.
        </div>
    @endif

    <form method="POST" action="{{ route('leads.store') }}">
        @csrf

        <div class="card">
            <div class="form-grid">
                <div class="form-group">
                    <label>Nome *</label>
                    <input type="text" name="nome" value="{{ old('nome') }}" required>
                </div>

                <div class="form-group">
                    <label>Empresa</label>
                    <input type="text" name="empresa" value="{{ old('empresa') }}">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}">
                </div>

                <div class="form-group">
                    <label>Telefone</label>
                    <input type="text" name="telefone" value="{{ old('telefone') }}">
                </div>

                <div class="form-group">
                    <label>Origem</label>
                    <input type="text" name="origem" value="{{ old('origem') }}" placeholder="Indicação, site, LinkedIn...">
                </div>

                <div class="form-group">
                    <label>Valor Estimado</label>
                    <input type="number" step="0.01" name="valor_estimado" value="{{ old('valor_estimado') }}">
                </div>

                <div class="form-group">
                    <label>Próximo Contato</label>
                    <input type="date" name="proximo_contato" value="{{ old('proximo_contato') }}">
                </div>

                <div class="form-group full">
                    <label>Observações</label>
                    <textarea name="observacoes" rows="5">{{ old('observacoes') }}</textarea>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    Salvar Lead
                </button>

                <a href="{{ route('leads.index') }}" class="btn-secondary">
                    Cancelar
                </a>
            </div>
        </div>
    </form>
@endsection