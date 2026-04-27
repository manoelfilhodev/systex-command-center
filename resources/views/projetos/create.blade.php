@extends('layouts.systex')

@section('content')
    <div class="topbar">
        <div>
            <div class="topbar-kicker">TITAN</div>
            <h1>Novo Projeto</h1>
            <p>Cadastro operacional para controlar escopo, prazo, responsável e contrato vinculado.</p>
        </div>

        <div class="topbar-actions">
            <a href="{{ route('projetos.index') }}" class="btn-secondary">Voltar</a>
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

    <form method="POST" action="{{ route('projetos.store') }}">
        @csrf

        @include('projetos.partials.form', ['projeto' => null])

        <div class="form-actions" style="margin-top: 20px;">
            <button type="submit" class="btn-primary">Salvar Projeto</button>
            <a href="{{ route('projetos.index') }}" class="btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
