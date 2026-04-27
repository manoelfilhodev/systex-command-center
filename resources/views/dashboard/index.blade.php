@extends('layouts.systex')

@section('content')
    <x-topbar
        title="Command Center"
        subtitle="Visão executiva da SYSTEX Sistemas Inteligentes"
    />

    <section class="grid">
        @foreach ($cards as $card)
            <x-stat-card
                :label="$card['label']"
                :value="$card['value']"
                :description="$card['description']"
                :trend="$card['trend']"
            />
        @endforeach
    </section>

    <div style="height: 24px;"></div>

    <section class="grid">
        <div class="page-panel">
            <div class="topbar" style="margin-bottom: 18px;">
                <div>
                    <div class="topbar-kicker">ATLAS</div>
                    <h1 style="font-size: 22px;">Saúde executiva</h1>
                    <p>Leitura consolidada por área operacional.</p>
                </div>
            </div>

            @foreach($health as $item)
                <div style="margin-bottom: 18px;">
                    <div style="display:flex; justify-content:space-between; gap:16px; align-items:flex-start;">
                        <div>
                            <strong>{{ $item['area'] }}</strong>
                            <p style="color:#a1a1aa; margin-top:4px;">{{ $item['description'] }}</p>
                        </div>
                        <div style="text-align:right;">
                            <strong>{{ $item['score'] }}%</strong>
                            <p style="color:#a1a1aa; margin-top:4px;">{{ $item['signal'] }}</p>
                        </div>
                    </div>

                    <div style="height: 8px; background:#27272a; border-radius:999px; overflow:hidden; margin-top:10px;">
                        <div style="height:100%; width:{{ $item['score'] }}%; background:#dc2626;"></div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="page-panel">
            <div class="topbar" style="margin-bottom: 18px;">
                <div>
                    <div class="topbar-kicker">ORION</div>
                    <h1 style="font-size: 22px;">Alertas críticos</h1>
                    <p>Riscos que exigem atenção executiva.</p>
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Alerta</th>
                            <th>Agente</th>
                            <th>Qtd</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alerts as $alert)
                            <tr>
                                <td>
                                    <strong>{{ $alert['label'] }}</strong>
                                    <div style="color:#a1a1aa; margin-top:4px;">{{ $alert['detail'] }}</div>
                                </td>
                                <td>{{ $alert['agent'] }}</td>
                                <td><strong>{{ $alert['value'] }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <div style="height: 24px;"></div>

    <section class="page-panel">
        <div class="topbar" style="margin-bottom: 18px;">
            <div>
                <div class="topbar-kicker">CRONOS</div>
                <h1 style="font-size: 22px;">Snapshot financeiro</h1>
                <p>MRR, ARR e previsão de caixa operacional.</p>
            </div>
        </div>

        <div class="grid">
            <div class="card">
                <div class="card-title">MRR</div>
                <div class="card-value">{{ $revenue['mrr'] }}</div>
                <div class="card-subtitle">Receita recorrente mensal</div>
            </div>
            <div class="card">
                <div class="card-title">ARR</div>
                <div class="card-value">{{ $revenue['arr'] }}</div>
                <div class="card-subtitle">Receita recorrente anualizada</div>
            </div>
            <div class="card">
                <div class="card-title">Receita Pendente</div>
                <div class="card-value">{{ $revenue['receitaPendente'] }}</div>
                <div class="card-subtitle">Entradas previstas</div>
            </div>
            <div class="card">
                <div class="card-title">Despesa Pendente</div>
                <div class="card-value">{{ $revenue['despesaPendente'] }}</div>
                <div class="card-subtitle">Saídas previstas</div>
            </div>
            <div class="card">
                <div class="card-title">Saldo Previsto</div>
                <div class="card-value">{{ $revenue['saldoPrevisto'] }}</div>
                <div class="card-subtitle">Entradas menos saídas pendentes</div>
            </div>
        </div>
    </section>
@endsection
