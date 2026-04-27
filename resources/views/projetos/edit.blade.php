@extends('layouts.systex')

@section('content')
    <div class="topbar">
        <div>
            <div class="topbar-kicker">TITAN</div>
            <h1>Editar Projeto</h1>
            <p>Atualização operacional de status, prazo, responsável e vínculo contratual.</p>
        </div>

        <div class="topbar-actions">
            <a href="{{ route('projetos.show', $projeto) }}" class="btn-secondary">Visualizar</a>
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

    <form method="POST" action="{{ route('projetos.update', $projeto) }}">
        @csrf
        @method('PUT')

        @include('projetos.partials.form', ['projeto' => $projeto])

        <div class="form-actions" style="margin-top: 20px;">
            <button type="submit" class="btn-primary">Atualizar Projeto</button>
            <a href="{{ route('projetos.show', $projeto) }}" class="btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
