@extends('layouts.systex')

@section('content')
    <div class="topbar">
        <div>
            <div class="topbar-kicker">TITAN</div>
            <h1>Novo Chamado</h1>
            <p>Registro de suporte com prioridade, SLA, canal e vínculo com cliente ou contrato.</p>
        </div>

        <div class="topbar-actions">
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

    <form method="POST" action="{{ route('suporte.store') }}">
        @csrf

        @include('suporte.partials.form', ['chamado' => null])

        <div class="form-actions" style="margin-top: 20px;">
            <button type="submit" class="btn-primary">Salvar Chamado</button>
            <a href="{{ route('suporte.index') }}" class="btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
