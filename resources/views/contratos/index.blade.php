@extends('layouts.systex')

@section('content')
    <div class="topbar">
        <div>
            <div class="topbar-kicker">CRONOS + THEMIS</div>
            <h1>Contratos</h1>
            <p>Gestão contratual, recorrência financeira e vínculo operacional dos clientes ativos.</p>
        </div>

        <div class="topbar-actions">
            <span class="system-pill">
                <span class="status-dot"></span>
                Receita Recorrente
            </span>

            @if(auth()->user()->hasAnyRole(['financeiro']))
                <a href="{{ route('contratos.create') }}" class="btn-primary">
                    + Novo Contrato
                </a>
            @endif
        </div>
    </div>

    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <section class="grid" style="margin-bottom: 24px;">
        <div class="card">
            <div class="card-title">Contratos Ativos</div>
            <div class="card-value">{{ $summary['ativos'] }}</div>
            <div class="card-subtitle">Base contratual vigente</div>
        </div>

        <div class="card">
            <div class="card-title">MRR Ativo</div>
            <div class="card-value">R$ {{ number_format($summary['mrrAtivo'], 2, ',', '.') }}</div>
            <div class="card-subtitle">Receita mensal contratada</div>
        </div>

        <div class="card">
            <div class="card-title">Receita Anualizada</div>
            <div class="card-value">R$ {{ number_format($summary['receitaAnualizada'], 2, ',', '.') }}</div>
            <div class="card-subtitle">MRR atual projetado em 12 meses</div>
        </div>

        <div class="card">
            <div class="card-title">Vencendo em 30 dias</div>
            <div class="card-value">{{ $summary['vencendo30Dias'] }}</div>
            <div class="card-subtitle">Contratos ativos próximos do fim</div>
        </div>

        <div class="card">
            <div class="card-title">Vencidos Ativos</div>
            <div class="card-value">{{ $summary['vencidosAtivos'] }}</div>
            <div class="card-subtitle">Exigem revisão jurídica/operacional</div>
        </div>
    </section>

    @if($vencendo->isNotEmpty())
        <div class="page-panel" style="margin-bottom: 24px;">
            <div class="topbar" style="margin-bottom: 18px;">
                <div>
                    <div class="topbar-kicker">THEMIS</div>
                    <h1 style="font-size: 22px;">Renovações próximas</h1>
                    <p>Contratos ativos com fim nos próximos 30 dias.</p>
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Contrato</th>
                            <th>Cliente</th>
                            <th>Data Fim</th>
                            <th>Valor Mensal</th>
                            <th>Ação</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($vencendo as $contrato)
                            <tr>
                                <td>{{ $contrato->numero }}</td>
                                <td>{{ $contrato->cliente->nome_fantasia ?? $contrato->cliente->razao_social ?? '-' }}</td>
                                <td>{{ $contrato->data_fim->format('d/m/Y') }}</td>
                                <td>R$ {{ number_format($contrato->valor_mensal, 2, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('contratos.show', $contrato) }}" class="btn-secondary">
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
                        <th>Número</th>
                        <th>Cliente</th>
                        <th>Proposta</th>
                        <th>Tipo</th>
                        <th>Status</th>
                        <th>Mensal</th>
                        <th>Início</th>
                        <th>Fim</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($contratos as $contrato)
                        <tr>
                            <td>{{ $contrato->numero }}</td>

                            <td>
                                <strong>{{ $contrato->cliente->nome_fantasia ?? ($contrato->cliente->razao_social ?? '-') }}</strong>
                            </td>

                            <td>
                                {{ $contrato->proposta->titulo ?? 'Sem proposta vinculada' }}
                            </td>

                            <td>{{ ucfirst(str_replace('_', ' ', $contrato->tipo)) }}</td>

                            <td>
                                <span
                                    class="badge
                                @if ($contrato->status === 'ativo') badge-success
                                @elseif($contrato->status === 'suspenso') badge-warning
                                @elseif(in_array($contrato->status, ['cancelado', 'encerrado'])) badge-danger @endif
                            ">
                                    {{ ucfirst($contrato->status) }}
                                </span>
                            </td>

                            <td>
                                <strong>
                                    R$ {{ number_format($contrato->valor_mensal, 2, ',', '.') }}
                                </strong>
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($contrato->data_inicio)->format('d/m/Y') }}
                            </td>

                            <td>
                                {{ $contrato->data_fim ? $contrato->data_fim->format('d/m/Y') : '-' }}
                            </td>

                            <td>
                                <div class="form-actions">

                                    <a href="{{ route('contratos.show', $contrato) }}" class="btn-secondary">
                                        Ver
                                    </a>

                                    <a href="{{ route('contratos.edit', $contrato) }}" class="btn-secondary">
                                        Editar
                                    </a>

                                    <form method="POST" action="{{ route('contratos.destroy', $contrato) }}"
                                        onsubmit="return confirm('Deseja remover este contrato?')">
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
                                <x-empty-state
                                    title="Nenhum contrato cadastrado"
                                    description="Formalize contratos para gerar MRR, renovações, implantação e rastreabilidade jurídica."
                                    :href="auth()->user()->hasAnyRole(['financeiro']) ? route('contratos.create') : null"
                                    action="Criar contrato"
                                />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $contratos->links() }}
        </div>

    </div>
@endsection
