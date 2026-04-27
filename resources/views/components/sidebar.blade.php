<aside class="sidebar">

    <div class="brand">
        <div class="brand-mark">
            SYSTEX
        </div>

        <div class="logo">
            Command <span>Center</span>
        </div>

        <div class="brand-subtitle">
            Executive Operations Platform
        </div>
    </div>

    <nav class="menu">

        <div class="menu-section">
            Gestão Executiva
        </div>

        <a href="{{ route('dashboard.index') }}" class="{{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
            <span class="menu-icon">◉</span>
            <span>Dashboard</span>
        </a>

        @if(auth()->user()->hasAnyRole(['comercial', 'diretoria']))
            <a href="{{ route('crm.index') }}" class="{{ request()->routeIs('crm.*') ? 'active' : '' }}">
                <span class="menu-icon">◎</span>
                <span>CRM</span>
            </a>
        @endif

        @if(auth()->user()->hasAnyRole(['comercial', 'financeiro', 'operacao', 'diretoria']))
            <a href="{{ route('clientes.index') }}" class="{{ request()->routeIs('clientes.*') ? 'active' : '' }}">
                <span class="menu-icon">◎</span>
                <span>Clientes</span>
            </a>
        @endif

        @if(auth()->user()->hasAnyRole(['comercial', 'diretoria']))
            <a href="{{ route('leads.index') }}" class="{{ request()->routeIs('leads.*') ? 'active' : '' }}">
                <span class="menu-icon">◎</span>
                <span>Leads</span>
            </a>
        @endif

        @if(auth()->user()->hasAnyRole(['comercial', 'diretoria']))
            <a href="{{ route('propostas.index') }}" class="{{ request()->routeIs('propostas.*') ? 'active' : '' }}">
                <span class="menu-icon">◎</span>
                <span>Propostas</span>
            </a>
        @endif

        @if(auth()->user()->hasAnyRole(['financeiro', 'diretoria']))
            <div class="menu-section">
                Operação Financeira
            </div>

            <a href="{{ route('contratos.index') }}" class="{{ request()->routeIs('contratos.*') ? 'active' : '' }}">
                <span class="menu-icon">◎</span>
                <span>Contratos</span>
            </a>

            <a href="{{ route('financeiro.index') }}" class="{{ request()->routeIs('financeiro.*') ? 'active' : '' }}">
                <span class="menu-icon">◎</span>
                <span>Financeiro</span>
            </a>

            <a href="{{ route('mrr.index') }}" class="{{ request()->routeIs('mrr.*') ? 'active' : '' }}">
                <span class="menu-icon">◎</span>
                <span>MRR</span>
            </a>
        @endif

        @if(auth()->user()->hasAnyRole(['operacao', 'diretoria']))
            <div class="menu-section">
                Entrega & Suporte
            </div>

            <a href="{{ route('projetos.index') }}" class="{{ request()->routeIs('projetos.*') ? 'active' : '' }}">
                <span class="menu-icon">◎</span>
                <span>Projetos</span>
            </a>

            <a href="{{ route('implantacoes.index') }}"
                class="{{ request()->routeIs('implantacoes.*') ? 'active' : '' }}">
                <span class="menu-icon">◎</span>
                <span>Implantações</span>
            </a>

            <a href="{{ route('suporte.index') }}" class="{{ request()->routeIs('suporte.*') ? 'active' : '' }}">
                <span class="menu-icon">◎</span>
                <span>Suporte</span>
            </a>
        @endif

        @if(auth()->user()->hasAnyRole(['diretoria']))
            <div class="menu-section">
                Governança
            </div>

            <a href="{{ route('auditoria.index') }}" class="{{ request()->routeIs('auditoria.*') ? 'active' : '' }}">
                <span class="menu-icon">◎</span>
                <span>Auditoria</span>
            </a>
        @endif

        <div class="menu-section">
            Sistema
        </div>

        @if(auth()->user()->hasAnyRole(['admin']))
            <a href="{{ route('usuarios.index') }}" class="{{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                <span class="menu-icon">◎</span>
                <span>Usuários</span>
            </a>
        @else
            <span class="menu-disabled">
                <span class="menu-icon">◎</span>
                <span>Configurações</span>
            </span>
        @endif

    </nav>

    <div class="sidebar-footer">
        <div>
            {{ auth()->user()->name ?? 'SYSTEX Systems Intelligence' }}
        </div>

        <div class="sidebar-status">
            <span class="status-dot"></span>
            <span>Operational</span>
        </div>

        <form method="POST" action="{{ route('logout') }}" style="margin-top: 14px;">
            @csrf
            <button type="submit" class="btn-link" style="color: #a1a1aa; font-size: 12px;">
                Sair do Command Center
            </button>
        </form>
    </div>

</aside>
