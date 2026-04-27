@extends('layouts.systex')

@section('content')

<section class="auth-page">
    <div class="auth-hero">
        <div class="auth-brand-mark">
            <span></span>
            SYSTEX
        </div>

        <div class="auth-hero-content">
            <div class="auth-hero-kicker">Command Center</div>
            <h1>Controle executivo para operação real.</h1>
            <p>
                Ambiente central de gestão comercial, financeira, operacional e estratégica da SYSTEX Sistemas Inteligentes.
            </p>

            <div class="auth-proof-grid">
                <div class="auth-proof">
                    <strong>CRM</strong>
                    <span>Leads, propostas, contratos e pipeline comercial.</span>
                </div>

                <div class="auth-proof">
                    <strong>MRR</strong>
                    <span>Receita recorrente, financeiro e visão executiva.</span>
                </div>

                <div class="auth-proof">
                    <strong>SLA</strong>
                    <span>Implantação, suporte e governança operacional.</span>
                </div>
            </div>
        </div>

        <div class="auth-footer-note">
            Acesso restrito a usuários autorizados. Todas as ações sensíveis devem preservar rastreabilidade, segurança e responsabilidade operacional.
        </div>
    </div>

    <div class="auth-panel-wrap">
        <div class="auth-panel">
            <div class="auth-panel-header">
                <div class="topbar-kicker">HADES</div>
                <h2>Acessar painel</h2>
                <p>Entre com seu usuário SYSTEX para continuar.</p>
            </div>

            @if($errors->any())
                <div class="alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <div class="form-grid auth-form-grid">
                    <div class="form-group">
                        <label>Email</label>
                        <input class="auth-input" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
                    </div>

                    <div class="form-group">
                        <label>Senha</label>
                        <input class="auth-input" type="password" name="password" autocomplete="current-password" required>
                    </div>

                    <label class="auth-remember">
                        <input type="checkbox" name="remember" value="1">
                        Manter sessão ativa
                    </label>
                </div>

                <div class="form-actions" style="margin-top: clamp(20px, 2.8vh, 24px);">
                    <button type="submit" class="btn-primary auth-submit">
                        Entrar no Command Center
                    </button>
                </div>

                <div class="auth-security">
                    <span class="menu-icon">●</span>
                    <span>Autenticação protegida por sessão Laravel, perfis de acesso e validação de permissões por módulo.</span>
                </div>
            </form>
        </div>

        <footer class="auth-panel-footer">
            <strong>SYSTEX Sistemas Inteligentes</strong><br>
            Tecnologia aplicada à operação real.
        </footer>
    </div>
</section>

@endsection
