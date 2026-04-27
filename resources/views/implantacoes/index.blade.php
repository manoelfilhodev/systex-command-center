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

            <a href="{{ route('implantacoes.create') }}" class="btn-primary">
                + Nova Implantação
            </a>
        </div>
    </div>

    <div class="grid">
        <div class="card">
            <div class="card-title">Total de Implantações</div>
            <div class="card-value">{{ $implantacoes->count() }}</div>
            <div class="card-subtitle">Operações em acompanhamento</div>
        </div>

        <div class="card">
            <div class="card-title">Em Andamento</div>
            <div class="card-value">
                {{ $implantacoes->where('status', 'em_andamento')->count() }}
            </div>
            <div class="card-subtitle">Implantações ativas</div>
        </div>

        <div class="card">
            <div class="card-title">Go Live</div>
            <div class="card-value">
                {{ $implantacoes->where('status', 'go_live')->count() }}
            </div>
            <div class="card-subtitle">Próximas entradas em produção</div>
        </div>

        <div class="card">
            <div class="card-title">Concluídas</div>
            <div class="card-value">
                {{ $implantacoes->where('status', 'concluida')->count() }}
            </div>
            <div class="card-subtitle">Projetos estabilizados</div>
        </div>
    </div>

    <div style="height: 20px;"></div>

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
                            <td colspan="9">
                                Nenhuma implantação cadastrada ainda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
