@extends('layouts.systex')

@section('content')

<div class="topbar">
    <div>
        <div class="topbar-kicker">CRONOS + CEO VIEW</div>
        <h1>MRR — Receita Recorrente</h1>
        <p>Painel executivo da receita recorrente mensal e visão financeira da operação.</p>
    </div>

    <div class="topbar-actions">
        <span class="system-pill">
            <span class="status-dot"></span>
            Receita Ativa
        </span>
    </div>
</div>

<div class="grid">

    <div class="card">
        <div class="card-title">MRR Confirmado</div>
        <div class="card-value">
            R$ {{ number_format($mrrAtual, 2, ',', '.') }}
        </div>
        <div class="card-subtitle">
            Receita recorrente confirmada do mês atual
        </div>
    </div>

    <div class="card">
        <div class="card-title">MRR Previsto</div>
        <div class="card-value">
            R$ {{ number_format($mrrPrevisto, 2, ',', '.') }}
        </div>
        <div class="card-subtitle">
            Receita prevista incluindo pipeline confirmado
        </div>
    </div>

    <div class="card">
        <div class="card-title">Contratos Ativos</div>
        <div class="card-value">
            {{ $contratosAtivos }}
        </div>
        <div class="card-subtitle">
            Contratos ativos em operação
        </div>
    </div>

    <div class="card">
        <div class="card-title">Receita Contratada</div>
        <div class="card-value">
            R$ {{ number_format($receitaMensalContratada, 2, ',', '.') }}
        </div>
        <div class="card-subtitle">
            Soma mensal de contratos ativos
        </div>
    </div>

</div>

<div style="height: 20px;"></div>

<div class="page-panel">

    <h3 style="margin-bottom: 20px;">
        Histórico de Receita — {{ str_pad($mesAtual, 2, '0', STR_PAD_LEFT) }}/{{ $anoAtual }}
    </h3>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Contrato</th>
                    <th>Mês</th>
                    <th>Ano</th>
                    <th>Valor MRR</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($registros as $registro)
                    <tr>
                        <td>
                            {{ $registro->cliente->nome_fantasia ?? $registro->cliente->razao_social ?? '-' }}
                        </td>

                        <td>
                            {{ $registro->contrato->numero ?? '-' }}
                        </td>

                        <td>{{ str_pad($registro->mes, 2, '0', STR_PAD_LEFT) }}</td>

                        <td>{{ $registro->ano }}</td>

                        <td>
                            <strong>
                                R$ {{ number_format($registro->valor_mrr, 2, ',', '.') }}
                            </strong>
                        </td>

                        <td>
                            <span class="badge
                                @if($registro->status === 'confirmado') badge-success
                                @elseif($registro->status === 'previsto') badge-warning
                                @elseif($registro->status === 'cancelado') badge-danger
                                @endif
                            ">
                                {{ ucfirst($registro->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            Nenhum registro de MRR encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $registros->links() }}
    </div>

</div>

@endsection
