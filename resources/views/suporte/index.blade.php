@extends('layouts.systex')

@section('content')
    <div class="topbar">
        <div>
            <div class="topbar-kicker">TITAN + HADES</div>
            <h1>Suporte</h1>
            <p>Gestão de chamados, SLA, prioridade e sustentação operacional dos clientes ativos.</p>
        </div>

        <div class="topbar-actions">
            <span class="system-pill">
                <span class="status-dot"></span>
                Sustentação
            </span>

            @if(auth()->user()->hasAnyRole(['operacao']))
                <a href="{{ route('suporte.create') }}" class="btn-primary">
                    + Novo Chamado
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <section class="grid" style="margin-bottom: 24px;">
        <div class="card">
            <div class="card-title">Chamados Abertos</div>
            <div class="card-value">{{ $summary['abertos'] }}</div>
            <div class="card-subtitle">Fila operacional ativa</div>
        </div>

        <div class="card">
            <div class="card-title">Críticos</div>
            <div class="card-value">{{ $summary['criticos'] }}</div>
            <div class="card-subtitle">Prioridade máxima em aberto</div>
        </div>

        <div class="card">
            <div class="card-title">SLA Vencido</div>
            <div class="card-value">{{ $summary['slaVencido'] }}</div>
            <div class="card-subtitle">Chamados que exigem mitigação</div>
        </div>

        <div class="card">
            <div class="card-title">SLA 24h</div>
            <div class="card-value">{{ $summary['sla24h'] }}</div>
            <div class="card-subtitle">Prazo vencendo nas próximas 24h</div>
        </div>

        <div class="card">
            <div class="card-title">Resolvidos no Mês</div>
            <div class="card-value">{{ $summary['resolvidosMes'] }}</div>
            <div class="card-subtitle">Entregas de suporte concluídas</div>
        </div>
    </section>

    @if($vencidos->isNotEmpty())
        <div class="page-panel" style="margin-bottom: 24px;">
            <div class="topbar" style="margin-bottom: 18px;">
                <div>
                    <div class="topbar-kicker">ORION</div>
                    <h1 style="font-size: 22px;">SLA vencido</h1>
                    <p>Chamados abertos ou em atendimento com prazo ultrapassado.</p>
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Chamado</th>
                            <th>Cliente</th>
                            <th>Prioridade</th>
                            <th>Prazo SLA</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vencidos as $chamado)
                            <tr>
                                <td>{{ $chamado->titulo }}</td>
                                <td>{{ $chamado->cliente->nome_fantasia ?? $chamado->cliente->razao_social ?? '-' }}</td>
                                <td>{{ ucfirst($chamado->prioridade) }}</td>
                                <td>{{ $chamado->prazo_sla ? $chamado->prazo_sla->format('d/m/Y H:i') : '-' }}</td>
                                <td>
                                    <a href="{{ route('suporte.show', $chamado) }}" class="btn-secondary">
                                        Atuar
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="page-panel">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Chamado</th>
                        <th>Cliente</th>
                        <th>Contrato</th>
                        <th>Prioridade</th>
                        <th>Status</th>
                        <th>SLA</th>
                        <th>Responsável</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($chamados as $chamado)
                        <tr>
                            <td><strong>{{ $chamado->titulo }}</strong></td>
                            <td>{{ $chamado->cliente->nome_fantasia ?? $chamado->cliente->razao_social ?? '-' }}</td>
                            <td>{{ $chamado->contrato->numero ?? '-' }}</td>
                            <td>{{ ucfirst($chamado->prioridade) }}</td>
                            <td>
                                <span class="badge
                                    @if($chamado->status === 'resolvido') badge-success
                                    @elseif($chamado->status === 'cancelado') badge-danger
                                    @else badge-warning
                                    @endif
                                ">
                                    {{ ucfirst(str_replace('_', ' ', $chamado->status)) }}
                                </span>
                            </td>
                            <td>{{ $chamado->prazo_sla ? $chamado->prazo_sla->format('d/m/Y H:i') : '-' }}</td>
                            <td>{{ $chamado->responsavel ?? '-' }}</td>
                            <td>
                                <div class="form-actions">
                                    <a href="{{ route('suporte.show', $chamado) }}" class="btn-secondary">Ver</a>
                                    <a href="{{ route('suporte.edit', $chamado) }}" class="btn-secondary">Editar</a>
                                    <form method="POST" action="{{ route('suporte.destroy', $chamado) }}" onsubmit="return confirm('Deseja remover este chamado?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <x-empty-state
                                    title="Nenhum chamado de suporte"
                                    description="Registre atendimentos para controlar SLA, criticidade e sustentação dos clientes ativos."
                                    :href="auth()->user()->hasAnyRole(['operacao']) ? route('suporte.create') : null"
                                    action="Abrir chamado"
                                />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $chamados->links() }}
        </div>
    </div>
@endsection
