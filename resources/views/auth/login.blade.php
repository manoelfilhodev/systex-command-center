@extends('layouts.systex')

@section('content')

<div style="min-height: calc(100vh - 96px); display: grid; place-items: center;">
    <div class="page-panel" style="width: min(100%, 460px);">
        <div style="margin-bottom: 26px;">
            <div class="topbar-kicker">HADES</div>
            <h1 style="font-size: 28px; margin-bottom: 8px;">Systex Command Center</h1>
            <p style="color: var(--muted); line-height: 1.5;">
                Acesso restrito ao painel executivo da SYSTEX.
            </p>
        </div>

        @if($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <div class="form-grid" style="grid-template-columns: 1fr;">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label>Senha</label>
                    <input type="password" name="password" required>
                </div>

                <label style="display: inline-flex; align-items: center; gap: 10px; color: var(--muted);">
                    <input type="checkbox" name="remember" value="1" style="width: auto;">
                    Manter sessão ativa
                </label>
            </div>

            <div class="form-actions" style="margin-top: 24px;">
                <button type="submit" class="btn-primary" style="width: 100%;">
                    Entrar
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
