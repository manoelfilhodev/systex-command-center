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

        <a
            href="{{ route('dashboard.index') }}"
            class="{{ request()->routeIs('dashboard.*') ? 'active' : '' }}"
        >
            <span class="menu-icon">◉</span>
            <span>Dashboard</span>
        </a>

        <a href="#">
            <span class="menu-icon">◎</span>
            <span>CRM</span>
        </a>

        <a href="#">
            <span class="menu-icon">◎</span>
            <span>Clientes</span>
        </a>

        <a
            href="{{ route('leads.index') }}"
            class="{{ request()->routeIs('leads.*') ? 'active' : '' }}"
        >
            <span class="menu-icon">◎</span>
            <span>Leads</span>
        </a>

        <a href="#">
            <span class="menu-icon">◎</span>
            <span>Propostas</span>
        </a>

        <div class="menu-section">
            Operação Financeira
        </div>

        <a href="#">
            <span class="menu-icon">◎</span>
            <span>Contratos</span>
        </a>

        <a href="#">
            <span class="menu-icon">◎</span>
            <span>Financeiro</span>
        </a>

        <a href="#">
            <span class="menu-icon">◎</span>
            <span>MRR</span>
        </a>

        <div class="menu-section">
            Entrega & Suporte
        </div>

        <a href="#">
            <span class="menu-icon">◎</span>
            <span>Projetos</span>
        </a>

        <a href="#">
            <span class="menu-icon">◎</span>
            <span>Implantação</span>
        </a>

        <a href="#">
            <span class="menu-icon">◎</span>
            <span>Suporte</span>
        </a>

        <div class="menu-section">
            Sistema
        </div>

        <a href="#">
            <span class="menu-icon">◎</span>
            <span>Configurações</span>
        </a>

    </nav>

    <div class="sidebar-footer">
        <div>
            SYSTEX Systems Intelligence
        </div>

        <div class="sidebar-status">
            <span class="status-dot"></span>
            <span>Operational</span>
        </div>
    </div>

</aside>