@extends('layouts.systex')

@section('content')
    <div class="topbar">
        <div>
            <div class="topbar-kicker">ATLAS + HADES</div>
            <h1>Auditoria</h1>
            <p>Rastreabilidade executiva dos eventos relevantes do Command Center.</p>
        </div>

        <div class="topbar-actions">
            <span class="system-pill">
                <span class="status-dot"></span>
                Governança
            </span>
        </div>
    </div>

    <section class="grid" style="margin-bottom: 24px;">
        <div class="card">
            <div class="card-title">Eventos</div>
            <div class="card-value">{{ $summary['total'] }}</div>
            <div class="card-subtitle">Registros de auditoria</div>
        </div>

        <div class="card">
            <div class="card-title">Hoje</div>
            <div class="card-value">{{ $summary['hoje'] }}</div>
            <div class="card-subtitle">Eventos no dia atual</div>
        </div>

        <div class="card">
            <div class="card-title">Contratos</div>
            <div class="card-value">{{ $summary['contratos'] }}</div>
            <div class="card-subtitle">Eventos contratuais</div>
        </div>

        <div class="card">
            <div class="card-title">Financeiro</div>
            <div class="card-value">{{ $summary['financeiro'] }}</div>
            <div class="card-subtitle">Eventos financeiros</div>
        </div>

        <div class="card">
            <div class="card-title">Operação</div>
            <div class="card-value">{{ $summary['operacao'] }}</div>
            <div class="card-subtitle">Implantação e suporte</div>
        </div>
    </section>

    <div class="page-panel">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Quando</th>
                        <th>Módulo</th>
                        <th>Ação</th>
                        <th>Evento</th>
                        <th>Usuário</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($eventos as $evento)
                        <tr>
                            <td>{{ $evento->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ ucfirst($evento->modulo) }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $evento->acao)) }}</td>
                            <td>
                                <strong>{{ $evento->titulo ?? '-' }}</strong>
                                @if($evento->metadata)
                                    <div style="color:#a1a1aa; margin-top:4px;">
                                        @foreach($evento->metadata as $key => $value)
                                            {{ ucfirst(str_replace('_', ' ', $key)) }}: {{ is_scalar($value) ? $value : json_encode($value) }}@if(! $loop->last) · @endif
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td>{{ $evento->user->name ?? 'Sistema' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Nenhum evento de auditoria registrado ainda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
