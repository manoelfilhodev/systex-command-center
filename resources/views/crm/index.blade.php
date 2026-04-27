@extends('layouts.systex')

@section('content')

<div class="topbar">
    <div>
        <div class="topbar-kicker">MERCURIUS</div>
        <h1>CRM Comercial</h1>
        <p>Pipeline de oportunidades, valor aberto e próximos contatos.</p>
    </div>

    <div class="topbar-actions">
        <span class="system-pill">
            <span class="status-dot"></span>
            Funil rastreável
        </span>

        @if(auth()->user()->hasAnyRole(['comercial']))
            <a href="{{ route('leads.create') }}" class="btn-primary">
                + Novo Lead
            </a>
        @endif
    </div>
</div>

<section class="grid" style="margin-bottom: 24px;">
    <div class="card">
        <div class="card-title">Leads Totais</div>
        <div class="card-value">{{ $summary['totalLeads'] }}</div>
        <div class="card-subtitle">Base comercial cadastrada</div>
    </div>

    <div class="card">
        <div class="card-title">Oportunidades Abertas</div>
        <div class="card-value">{{ $summary['leadsAbertos'] }}</div>
        <div class="card-subtitle">Leads ainda não convertidos ou perdidos</div>
    </div>

    <div class="card">
        <div class="card-title">Valor Aberto</div>
        <div class="card-value">R$ {{ number_format($summary['valorAberto'], 2, ',', '.') }}</div>
        <div class="card-subtitle">Estimativa comercial em andamento</div>
    </div>

    <div class="card">
        <div class="card-title">Próximos Contatos</div>
        <div class="card-value">{{ $summary['proximosContatos'] }}</div>
        <div class="card-subtitle">Contatos futuros em oportunidades abertas</div>
    </div>

    <div class="card">
        <div class="card-title">Tarefas Vencidas</div>
        <div class="card-value">{{ $summary['tarefasVencidas'] }}</div>
        <div class="card-subtitle">Pendências comerciais em atraso</div>
    </div>

    <div class="card">
        <div class="card-title">Tarefas Hoje</div>
        <div class="card-value">{{ $summary['tarefasHoje'] }}</div>
        <div class="card-subtitle">Ações comerciais para o dia</div>
    </div>
</section>

<div class="page-panel">
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid" style="margin-bottom: 22px;">
        <div class="card">
            <div class="card-title">Vencidas</div>

            @forelse($taskAlerts['vencidas'] as $tarefa)
                <a href="{{ route('leads.show', $tarefa->lead) }}" class="pipeline-lead">
                    <strong>{{ $tarefa->titulo }}</strong>
                    <span>{{ $tarefa->lead->empresa ?? $tarefa->lead->nome }}</span>
                    <span>Venceu em {{ $tarefa->data_vencimento->format('d/m/Y') }}</span>
                </a>
            @empty
                <div class="card-subtitle">Nenhuma tarefa vencida.</div>
            @endforelse
        </div>

        <div class="card">
            <div class="card-title">Para Hoje</div>

            @forelse($taskAlerts['hoje'] as $tarefa)
                <a href="{{ route('leads.show', $tarefa->lead) }}" class="pipeline-lead">
                    <strong>{{ $tarefa->titulo }}</strong>
                    <span>{{ $tarefa->lead->empresa ?? $tarefa->lead->nome }}</span>
                    <span>Prioridade {{ ucfirst($tarefa->prioridade) }}</span>
                </a>
            @empty
                <div class="card-subtitle">Nenhuma tarefa para hoje.</div>
            @endforelse
        </div>
    </div>

    <div class="pipeline-board">
        @foreach($stages as $stage)
            <section class="pipeline-column">
                <div class="pipeline-column-header">
                    <div>
                        <div class="pipeline-title">{{ $stage['label'] }}</div>
                        <div class="pipeline-meta">
                            {{ $stage['count'] }} leads · R$ {{ number_format($stage['value'], 2, ',', '.') }}
                        </div>
                    </div>

                    <span class="badge">{{ $stage['count'] }}</span>
                </div>

                @forelse($stage['leads'] as $lead)
                    <div class="pipeline-lead">
                        <strong>{{ $lead->empresa ?? $lead->nome }}</strong>
                        <span>{{ $lead->nome }}</span>
                        <span>R$ {{ number_format($lead->valor_estimado ?? 0, 2, ',', '.') }}</span>
                        <span>
                            Próximo contato:
                            {{ $lead->proximo_contato ? $lead->proximo_contato->format('d/m/Y') : '-' }}
                        </span>

                        <div class="form-actions" style="margin-top: 12px; gap: 8px;">
                            <a href="{{ route('leads.show', $lead) }}" class="btn-secondary" style="padding: 9px 12px; font-size: 12px;">
                                Ver
                            </a>

                            <form method="POST" action="{{ route('leads.stage.update', $lead) }}" style="display: flex; gap: 8px; align-items: center; flex: 1;">
                                @csrf
                                @method('PATCH')

                                <select name="status" style="padding: 9px 10px; border-radius: 10px; font-size: 12px;">
                                    @foreach(\App\Services\CommercialPipelineService::STAGES as $status => $label)
                                        <option value="{{ $status }}" @selected($lead->status === $status)>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>

                                <button type="submit" class="btn-primary" style="padding: 9px 12px; font-size: 12px;">
                                    Mover
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="pipeline-meta">
                        Nenhuma oportunidade nesta etapa.
                    </div>
                @endforelse
            </section>
        @endforeach
    </div>
</div>

@endsection
