@extends('layouts.systex')

@section('content')
    <div class="topbar">
        <div>
            <div class="topbar-kicker">TITAN</div>
            <h1>Editar Chamado</h1>
            <p>Atualização de SLA, prioridade, status e resolução operacional.</p>
        </div>

        <div class="topbar-actions">
            <a href="{{ route('suporte.show', $chamado) }}" class="btn-secondary">Visualizar</a>
            <a href="{{ route('suporte.index') }}" class="btn-secondary">Voltar</a>
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

    <form method="POST" action="{{ route('suporte.update', $chamado) }}">
        @csrf
        @method('PUT')

        @include('suporte.partials.form', ['chamado' => $chamado])

        <div class="form-actions" style="margin-top: 20px;">
            <button type="submit" class="btn-primary">Atualizar Chamado</button>
            <a href="{{ route('suporte.show', $chamado) }}" class="btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
