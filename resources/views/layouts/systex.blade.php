<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Systex Command Center') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --bg: #080808;
            --surface: #0f0f10;
            --surface-2: #131316;
            --surface-3: #19191d;
            --border: rgba(255,255,255,0.075);
            --border-strong: rgba(255,255,255,0.12);
            --text: #ffffff;
            --muted: #a1a1aa;
            --muted-2: #71717a;
            --accent: #ff2a2a;
            --accent-soft: rgba(255,42,42,0.12);
            --accent-border: rgba(255,42,42,0.32);
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
            --radius-lg: 22px;
            --radius-md: 16px;
            --sidebar-width: 282px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            background: var(--bg);
        }

        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at top right, rgba(255,42,42,0.08), transparent 28%),
                radial-gradient(circle at top left, rgba(255,255,255,0.04), transparent 24%),
                var(--bg);
            color: var(--text);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        a {
            color: inherit;
        }

        .app {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            position: sticky;
            top: 0;
            background:
                linear-gradient(180deg, rgba(255,255,255,0.035), rgba(255,255,255,0.01)),
                #0d0d0f;
            border-right: 1px solid var(--border);
            padding: 22px 18px;
            display: flex;
            flex-direction: column;
        }

        .brand {
            padding: 8px 10px 22px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 20px;
        }

        .brand-mark {
            font-size: 12px;
            color: var(--muted-2);
            letter-spacing: 0.22em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .logo {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 0.03em;
            line-height: 1.1;
        }

        .logo span {
            color: var(--accent);
        }

        .brand-subtitle {
            color: var(--muted);
            font-size: 12px;
            margin-top: 8px;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1;
        }

        .menu-section {
            color: var(--muted-2);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            margin: 18px 10px 8px;
        }

        .menu a {
            text-decoration: none;
            color: #c4c4cc;
            padding: 12px 13px;
            border-radius: 14px;
            transition: 0.18s ease;
            display: flex;
            align-items: center;
            gap: 11px;
            border: 1px solid transparent;
            font-size: 14px;
        }

        .menu a:hover {
            background: rgba(255,255,255,0.045);
            color: white;
            border-color: var(--border);
        }

        .menu a.active {
            background: linear-gradient(135deg, rgba(255,42,42,0.18), rgba(255,255,255,0.035));
            color: white;
            border-color: var(--accent-border);
            box-shadow: 0 0 24px rgba(255,42,42,0.08);
        }

        .menu-icon {
            width: 28px;
            height: 28px;
            display: grid;
            place-items: center;
            border-radius: 10px;
            background: rgba(255,255,255,0.04);
            color: var(--muted);
            font-size: 13px;
            flex: 0 0 auto;
        }

        .menu a.active .menu-icon {
            background: var(--accent-soft);
            color: var(--accent);
        }

        .sidebar-footer {
            border-top: 1px solid var(--border);
            padding: 18px 10px 4px;
            color: var(--muted-2);
            font-size: 12px;
        }

        .sidebar-status {
            margin-top: 10px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #d4d4d8;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: var(--success);
            box-shadow: 0 0 12px rgba(34,197,94,0.65);
        }

        .content {
            flex: 1;
            padding: 32px 40px 48px;
            max-width: calc(100vw - var(--sidebar-width));
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 24px;
            margin-bottom: 28px;
        }

        .topbar-kicker {
            color: var(--accent);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.16em;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .topbar h1 {
            font-size: 30px;
            font-weight: 800;
            letter-spacing: -0.04em;
        }

        .topbar p {
            color: var(--muted);
            margin-top: 8px;
            font-size: 15px;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .system-pill {
            border: 1px solid var(--border);
            background: rgba(255,255,255,0.035);
            color: #d4d4d8;
            border-radius: 999px;
            padding: 10px 14px;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .page-panel {
            background: linear-gradient(180deg, rgba(255,255,255,0.035), rgba(255,255,255,0.015));
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 24px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
        }

        .card {
            position: relative;
            overflow: hidden;
            background:
                linear-gradient(180deg, rgba(255,255,255,0.035), rgba(255,255,255,0.012)),
                var(--surface-2);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.24);
            transition: 0.18s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            border-color: var(--border-strong);
            box-shadow: 0 24px 70px rgba(0,0,0,0.32);
        }

        .card::after {
            content: "";
            position: absolute;
            inset: auto 20px 0 20px;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,42,42,0.35), transparent);
            opacity: 0.55;
        }

        .card-title {
            color: #c7c7d1;
            font-size: 14px;
            margin-bottom: 14px;
        }

        .card-value {
            font-size: 32px;
            font-weight: 850;
            margin-bottom: 10px;
            letter-spacing: -0.04em;
        }

        .card-subtitle {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.5;
        }

        .card-trend {
            margin-top: 16px;
            color: var(--accent);
            font-size: 13px;
            font-weight: 800;
        }

        .table-wrap {
            overflow-x: auto;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            background: var(--surface);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 760px;
        }

        th {
            text-align: left;
            color: var(--muted);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 800;
            background: rgba(255,255,255,0.025);
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
        }

        td {
            padding: 15px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.045);
            color: #e4e4e7;
            font-size: 14px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 6px 10px;
            font-size: 12px;
            font-weight: 800;
            border: 1px solid var(--border);
            color: #d4d4d8;
            background: rgba(255,255,255,0.04);
        }

        .badge-danger,
        .badge-hot {
            color: #fecaca;
            background: rgba(239,68,68,0.12);
            border-color: rgba(239,68,68,0.28);
        }

        .badge-success {
            color: #bbf7d0;
            background: rgba(34,197,94,0.12);
            border-color: rgba(34,197,94,0.28);
        }

        .badge-warning {
            color: #fde68a;
            background: rgba(245,158,11,0.12);
            border-color: rgba(245,158,11,0.28);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        label {
            color: #d4d4d8;
            font-size: 14px;
            font-weight: 700;
        }

        input,
        textarea,
        select {
            width: 100%;
            background: #0b0b0c;
            border: 1px solid rgba(255,255,255,0.09);
            color: #ffffff;
            border-radius: 14px;
            padding: 14px 16px;
            outline: none;
            transition: 0.18s ease;
            font: inherit;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(255,42,42,0.12);
        }

        .form-actions {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-top: 8px;
            flex-wrap: wrap;
        }

        .btn-primary,
        .btn-secondary,
        .btn-danger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border-radius: 14px;
            padding: 13px 18px;
            font-weight: 800;
            cursor: pointer;
            text-decoration: none;
            transition: 0.18s ease;
            border: 1px solid transparent;
            font-size: 14px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #ff2a2a, #b91c1c);
            color: white;
            box-shadow: 0 14px 34px rgba(255,42,42,0.16);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 42px rgba(255,42,42,0.22);
        }

        .btn-secondary {
            color: #d4d4d8;
            background: rgba(255,255,255,0.04);
            border-color: var(--border);
        }

        .btn-secondary:hover {
            background: rgba(255,255,255,0.075);
            color: white;
        }

        .btn-danger {
            color: #fecaca;
            background: rgba(239,68,68,0.1);
            border-color: rgba(239,68,68,0.28);
        }

        .alert-error,
        .alert-success {
            padding: 16px;
            border-radius: 14px;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .alert-error {
            background: rgba(255,42,42,0.12);
            border: 1px solid rgba(255,42,42,0.35);
            color: #fecaca;
        }

        .alert-success {
            background: rgba(34,197,94,0.1);
            border: 1px solid rgba(34,197,94,0.3);
            color: #bbf7d0;
        }

        @media (max-width: 980px) {
            .app {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                min-height: auto;
                position: relative;
            }

            .content {
                max-width: 100%;
                padding: 24px;
            }

            .topbar {
                flex-direction: column;
            }

            .topbar-actions {
                justify-content: flex-start;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="app">

    <x-sidebar />

    <main class="content">
        @yield('content')
    </main>

</div>

</body>
</html>