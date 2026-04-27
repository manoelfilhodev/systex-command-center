@extends('layouts.systex')

@section('content')
    <div class="topbar">
        <div>
            <div class="topbar-kicker">TITAN + VULCAN</div>
            <h1>Projetos</h1>
            <p>Gestão operacional de entregas, responsáveis, prazos e evolução dos projetos SYSTEX.</p>
        </div>

        <div class="topbar-actions">
            <span class="system-pill">
                <span class="status-dot"></span>
                Entrega Operacional
            </span>

            @if(auth()->user()->hasAnyRole(['operacao']))
                <a href="{{ route('projetos.create') }}" class="btn-primary">
                    + Novo Projeto
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
            <div class="card-title">Projetos Ativos</div>
            <div class="card-value">{{ $summary['ativos'] }}</div>
            <div class="card-subtitle">Planejados, em andamento ou homologação</div>
        </div>

        <div class="card">
            <div class="card-title">Em Andamento</div>
            <div class="card-value">{{ $summary['emAndamento'] }}</div>
            <div class="card-subtitle">Execução operacional ativa</div>
        </div>

        <div class="card">
            <div class="card-title">Homologação</div>
            <div class="card-value">{{ $summary['homologacao'] }}</div>
            <div class="card-subtitle">Projetos em validação final</div>
        </div>

        <div class="card">
            <div class="card-title">Atrasados</div>
            <div class="card-value">{{ $summary['atrasados'] }}</div>
            <div class="card-subtitle">Entregas com prazo ultrapassado</div>
        </div>

        <div class="card">
            <div class="card-title">Entregas 30 Dias</div>
            <div class="card-value">{{ $summary['entregas30Dias'] }}</div>
            <div class="card-subtitle">Prazos próximos no calendário</div>
        </div>
    </section>

    @if($atrasados->isNotEmpty())
        <div class="page-panel" style="margin-bottom: 24px;">
            <div class="topbar" style="margin-bottom: 18px;">
                <div>
                    <div class="topbar-kicker">ORION</div>
                    <h1 style="font-size: 22px;">Projetos em atraso</h1>
                    <p>Entregas que exigem mitigação de risco e replanejamento.</p>
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Projeto</th>
                            <th>Cliente</th>
                            <th>Responsável</th>
                            <th>Prazo</th>
                            <th>Ação</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($atrasados as $projeto)
                            <tr>
                                <td>{{ $projeto->nome }}</td>
                                <td>{{ $projeto->cliente->nome_fantasia ?? $projeto->cliente->razao_social ?? '-' }}</td>
                                <td>{{ $projeto->responsavel ?? '-' }}</td>
                                <td>{{ $projeto->data_prevista_entrega->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('projetos.show', $projeto) }}" class="btn-secondary">
                                        Revisar
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
                        <th>Projeto</th>
                        <th>Cliente</th>
                        <th>Contrato</th>
                        <th>Tipo</th>
                        <th>Status</th>
                        <th>Responsável</th>
                        <th>Prazo</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($projetos as $projeto)
                        <tr>
                            <td><strong>{{ $projeto->nome }}</strong></td>
                            <td>{{ $projeto->cliente->nome_fantasia ?? $projeto->cliente->razao_social ?? '-' }}</td>
                            <td>{{ $projeto->contrato->numero ?? '-' }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $projeto->tipo)) }}</td>
                            <td>
                                <span class="badge
                                    @if($projeto->status === 'concluido') badge-success
                                    @elseif(in_array($projeto->status, ['planejado', 'em_andamento', 'homologacao'])) badge-warning
                                    @elseif(in_array($projeto->status, ['pausado', 'cancelado'])) badge-danger
                                    @endif
                                ">
                                    {{ ucfirst(str_replace('_', ' ', $projeto->status)) }}
                                </span>
                            </td>
                            <td>{{ $projeto->responsavel ?? '-' }}</td>
                            <td>{{ $projeto->data_prevista_entrega ? $projeto->data_prevista_entrega->format('d/m/Y') : '-' }}</td>
                            <td>
                                <div class="form-actions">
                                    <a href="{{ route('projetos.show', $projeto) }}" class="btn-secondary">Ver</a>
                                    <a href="{{ route('projetos.edit', $projeto) }}" class="btn-secondary">Editar</a>

                                    <form method="POST" action="{{ route('projetos.destroy', $projeto) }}" onsubmit="return confirm('Deseja remover este projeto?')">
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
                                    title="Nenhum projeto cadastrado"
                                    description="Crie projetos para acompanhar responsáveis, prazos, atrasos e vínculo operacional com contratos."
                                    :href="auth()->user()->hasAnyRole(['operacao']) ? route('projetos.create') : null"
                                    action="Criar projeto"
                                />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $projetos->links() }}
        </div>
    </div>
@endsection
