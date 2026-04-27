@extends('layouts.systex')

@section('content')
    <div class="topbar">
        <div>
            <div class="topbar-kicker">TITAN</div>
            <h1>Implantações</h1>
            <p>Gestão executiva de onboarding, implantação, homologação e go-live dos contratos da SYSTEX.</p>
        </div>

        <div class="topbar-actions">
            <span class="system-pill">
                <span class="status-dot"></span>
                Pós-venda Executivo
            </span>

            @if(auth()->user()->hasAnyRole(['operacao']))
                <a href="{{ route('implantacoes.create') }}" class="btn-primary">
                    + Nova Implantação
                </a>
            @endif
        </div>
    </div>

    <div class="grid">
        <div class="card">
            <div class="card-title">Total de Implantações</div>
            <div class="card-value">{{ $summary['total'] }}</div>
            <div class="card-subtitle">Operações em acompanhamento</div>
        </div>

        <div class="card">
            <div class="card-title">Em Andamento</div>
            <div class="card-value">{{ $summary['emAndamento'] }}</div>
            <div class="card-subtitle">Implantações ativas</div>
        </div>

        <div class="card">
            <div class="card-title">Go Live</div>
            <div class="card-value">{{ $summary['goLive'] }}</div>
            <div class="card-subtitle">Próximas entradas em produção</div>
        </div>

        <div class="card">
            <div class="card-title">Concluídas</div>
            <div class="card-value">{{ $summary['concluidas'] }}</div>
            <div class="card-subtitle">Projetos estabilizados</div>
        </div>

        <div class="card">
            <div class="card-title">Em Risco</div>
            <div class="card-value">{{ $summary['emRisco'] }}</div>
            <div class="card-subtitle">Go-live vencido ou etapa bloqueada</div>
        </div>

        <div class="card">
            <div class="card-title">Go Live 30 Dias</div>
            <div class="card-value">{{ $summary['goLive30Dias'] }}</div>
            <div class="card-subtitle">Implantações próximas da produção</div>
        </div>
    </div>

    <div style="height: 20px;"></div>

    @if($emRisco->isNotEmpty())
        <div class="page-panel" style="margin-bottom: 20px;">
            <div class="topbar" style="margin-bottom: 18px;">
                <div>
                    <div class="topbar-kicker">ORION + TITAN</div>
                    <h1 style="font-size: 22px;">Implantações em risco</h1>
                    <p>Operações com go-live vencido ou etapas bloqueadas.</p>
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Contrato</th>
                            <th>Status</th>
                            <th>Go Live</th>
                            <th>Bloqueios</th>
                            <th>Ação</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($emRisco as $implantacaoRisco)
                            <tr>
                                <td>{{ $implantacaoRisco->contrato->cliente->nome_fantasia ?? $implantacaoRisco->contrato->cliente->razao_social ?? '-' }}</td>
                                <td>{{ $implantacaoRisco->contrato->numero ?? '-' }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $implantacaoRisco->status)) }}</td>
                                <td>{{ $implantacaoRisco->data_go_live ? $implantacaoRisco->data_go_live->format('d/m/Y') : '-' }}</td>
                                <td>{{ $implantacaoRisco->etapas->where('status', 'bloqueada')->count() }}</td>
                                <td>
                                    <a href="{{ route('implantacoes.show', $implantacaoRisco) }}" class="btn-secondary">
                                        Mitigar
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
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Contrato</th>
                        <th>Status</th>
                        <th>Responsável</th>
                        <th>Progresso</th>
                        <th>Início</th>
                        <th>Go Live</th>
                        <th>Aditivos</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($implantacoes as $implantacao)
                        <tr>
                            <td><strong>#{{ $implantacao->id }}</strong></td>

                            <td>
                                {{ $implantacao->contrato->cliente->nome_fantasia ?? ($implantacao->contrato->cliente->razao_social ?? '-') }}
                            </td>

                            <td>
                                {{ $implantacao->contrato->numero ?? '-' }}
                            </td>

                            <td>
                                <span
                                    class="badge
                                @if ($implantacao->status === 'concluida') badge-success
                                @elseif($implantacao->status === 'cancelada') badge-danger
                                @elseif($implantacao->status === 'go_live') badge-info
                                @elseif($implantacao->status === 'em_andamento') badge-warning
                                @else badge-secondary @endif
                            ">
                                    {{ ucfirst(str_replace('_', ' ', $implantacao->status)) }}
                                </span>
                            </td>

                            <td>
                                {{ $implantacao->responsavel ?? '-' }}
                            </td>

                            <td>
                                @php($progresso = $progressos[$implantacao->id])
                                <strong>{{ $progresso['percentual'] }}%</strong>
                                <div style="height: 6px; background: #27272a; border-radius: 999px; margin-top: 6px; overflow: hidden;">
                                    <div style="height: 100%; width: {{ $progresso['percentual'] }}%; background: {{ $progresso['emRisco'] ? '#ef4444' : '#dc2626' }};"></div>
                                </div>
                            </td>

                            <td>
                                {{ $implantacao->data_inicio ? \Carbon\Carbon::parse($implantacao->data_inicio)->format('d/m/Y') : '-' }}
                            </td>

                            <td>
                                {{ $implantacao->data_go_live ? \Carbon\Carbon::parse($implantacao->data_go_live)->format('d/m/Y') : '-' }}
                            </td>

                            <td>
                                {{ $implantacao->aditivos->count() }}
                            </td>

                            <td>
                                <div class="form-actions">
                                    <a href="{{ route('implantacoes.show', $implantacao) }}" class="btn-secondary">
                                        Ver
                                    </a>

                                    <a href="{{ route('implantacoes.edit', $implantacao) }}" class="btn-secondary">
                                        Editar
                                    </a>

                                    <form method="POST" action="{{ route('implantacoes.destroy', $implantacao) }}"
                                        onsubmit="return confirm('Deseja remover esta implantação?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn-danger">
                                            Excluir
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                Nenhuma implantação cadastrada ainda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
