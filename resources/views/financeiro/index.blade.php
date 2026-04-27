@extends('layouts.systex')

@section('content')

<div class="topbar">
    <div>
        <div class="topbar-kicker">CRONOS</div>
        <h1>Financeiro</h1>
        <p>Controle executivo de receitas, despesas, pendências e fluxo financeiro da SYSTEX.</p>
    </div>

    <div class="topbar-actions">
        <span class="system-pill">
            <span class="status-dot"></span>
            Fluxo Financeiro
        </span>

        @if(auth()->user()->hasAnyRole(['financeiro']))
            <a href="{{ route('financeiro.create') }}" class="btn-primary">
                + Novo Lançamento
            </a>
        @endif
    </div>
</div>

<div class="grid">
    <div class="card">
        <div class="card-title">Receitas Pagas</div>
        <div class="card-value">R$ {{ number_format($summary['receitasPagas'], 2, ',', '.') }}</div>
        <div class="card-subtitle">Entradas financeiras confirmadas</div>
    </div>

    <div class="card">
        <div class="card-title">Despesas Pagas</div>
        <div class="card-value">R$ {{ number_format($summary['despesasPagas'], 2, ',', '.') }}</div>
        <div class="card-subtitle">Saídas financeiras confirmadas</div>
    </div>

    <div class="card">
        <div class="card-title">Saldo Confirmado</div>
        <div class="card-value">R$ {{ number_format($summary['saldoConfirmado'], 2, ',', '.') }}</div>
        <div class="card-subtitle">Receitas pagas menos despesas pagas</div>
    </div>

    <div class="card">
        <div class="card-title">A Receber</div>
        <div class="card-value">R$ {{ number_format($summary['pendenteReceber'], 2, ',', '.') }}</div>
        <div class="card-subtitle">Receitas pendentes ou atrasadas</div>
    </div>

    <div class="card">
        <div class="card-title">A Pagar</div>
        <div class="card-value">R$ {{ number_format($summary['pendentePagar'], 2, ',', '.') }}</div>
        <div class="card-subtitle">Despesas pendentes ou atrasadas</div>
    </div>

    <div class="card">
        <div class="card-title">Vencidos</div>
        <div class="card-value">{{ $summary['vencidos'] }}</div>
        <div class="card-subtitle">Pendências com vencimento ultrapassado</div>
    </div>

    <div class="card">
        <div class="card-title">Fluxo 7 Dias</div>
        <div class="card-value">R$ {{ number_format($summary['fluxo7Dias'], 2, ',', '.') }}</div>
        <div class="card-subtitle">Valores pendentes com vencimento próximo</div>
    </div>
</div>

<div style="height: 20px;"></div>

@if($vencidos->isNotEmpty() || $proximos->isNotEmpty())
    <div class="grid">
        <div class="page-panel">
            <div class="topbar" style="margin-bottom: 18px;">
                <div>
                    <div class="topbar-kicker">CRONOS</div>
                    <h1 style="font-size: 22px;">Pendências vencidas</h1>
                    <p>Lançamentos que exigem ação financeira imediata.</p>
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            <th>Cliente</th>
                            <th>Vencimento</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vencidos as $lancamento)
                            <tr>
                                <td>{{ $lancamento->descricao }}</td>
                                <td>{{ $lancamento->cliente->nome_fantasia ?? $lancamento->cliente->razao_social ?? '-' }}</td>
                                <td>{{ $lancamento->data_vencimento->format('d/m/Y') }}</td>
                                <td>R$ {{ number_format($lancamento->valor, 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">Nenhuma pendência vencida.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="page-panel">
            <div class="topbar" style="margin-bottom: 18px;">
                <div>
                    <div class="topbar-kicker">CRONOS</div>
                    <h1 style="font-size: 22px;">Próximos 7 dias</h1>
                    <p>Entradas e saídas pendentes no curto prazo.</p>
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            <th>Tipo</th>
                            <th>Vencimento</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($proximos as $lancamento)
                            <tr>
                                <td>{{ $lancamento->descricao }}</td>
                                <td>{{ ucfirst($lancamento->tipo) }}</td>
                                <td>{{ $lancamento->data_vencimento->format('d/m/Y') }}</td>
                                <td>R$ {{ number_format($lancamento->valor, 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">Nenhum vencimento nos próximos 7 dias.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div style="height: 20px;"></div>
@endif

<div class="page-panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Cliente</th>
                    <th>Tipo</th>
                    <th>Categoria</th>
                    <th>Valor</th>
                    <th>Vencimento</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                @forelse($lancamentos as $lancamento)
                    <tr>
                        <td><strong>{{ $lancamento->descricao }}</strong></td>

                        <td>
                            {{ $lancamento->cliente->nome_fantasia ?? $lancamento->cliente->razao_social ?? '-' }}
                        </td>

                        <td>{{ ucfirst($lancamento->tipo) }}</td>

                        <td>{{ ucfirst($lancamento->categoria) }}</td>

                        <td>
                            <strong>R$ {{ number_format($lancamento->valor, 2, ',', '.') }}</strong>
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($lancamento->data_vencimento)->format('d/m/Y') }}
                        </td>

                        <td>
                            <span class="badge
                                @if($lancamento->status === 'pago') badge-success
                                @elseif($lancamento->status === 'pendente') badge-warning
                                @elseif($lancamento->status === 'atrasado') badge-danger
                                @endif
                            ">
                                {{ ucfirst($lancamento->status) }}
                            </span>
                        </td>

                        <td>
                            <div class="form-actions">
                                <a href="{{ route('financeiro.show', $lancamento) }}" class="btn-secondary">Ver</a>
                                <a href="{{ route('financeiro.edit', $lancamento) }}" class="btn-secondary">Editar</a>

                                <form method="POST" action="{{ route('financeiro.destroy', $lancamento) }}" onsubmit="return confirm('Deseja remover este lançamento?')">
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
                                title="Nenhum lançamento financeiro"
                                description="Registre receitas, despesas, vencimentos e recorrências para alimentar a visão executiva de caixa."
                                :href="auth()->user()->hasAnyRole(['financeiro']) ? route('financeiro.create') : null"
                                action="Criar lançamento"
                            />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $lancamentos->links() }}
    </div>
</div>

@endsection
