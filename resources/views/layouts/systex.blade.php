<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Systex Command Center') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --bg: #0b0b0b;
            --card: #121214;
            --soft: #1a1a1d;
            --border: rgba(255,255,255,0.06);
            --text: #ffffff;
            --muted: #a1a1aa;
            --accent: #ff2a2a;
            --accent-soft: rgba(255, 42, 42, 0.12);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: Inter, sans-serif;
        }

        .app {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 280px;
            background: #0f0f10;
            border-right: 1px solid var(--border);
            padding: 30px;
        }

        .logo {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 40px;
            letter-spacing: 1px;
        }

        .logo span {
            color: var(--accent);
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .menu a {
            text-decoration: none;
            color: var(--muted);
            padding: 14px 16px;
            border-radius: 12px;
            transition: 0.2s;
        }

        .menu a:hover {
            background: var(--accent-soft);
            color: white;
        }

        .content {
            flex: 1;
            padding: 40px;
        }

        .topbar {
            margin-bottom: 30px;
        }

        .topbar h1 {
            font-size: 28px;
            font-weight: 700;
        }

        .topbar p {
            color: var(--muted);
            margin-top: 8px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit,minmax(280px,1fr));
            gap: 20px;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 24px;
        }

        .card-title {
            color: var(--muted);
            font-size: 14px;
            margin-bottom: 12px;
        }

        .card-value {
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .card-subtitle {
            color: var(--muted);
            font-size: 14px;
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