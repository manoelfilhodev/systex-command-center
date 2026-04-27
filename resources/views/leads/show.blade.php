@extends('layouts.systex')

@section('content')

<div class="topbar">
    <div>
        <div class="topbar-kicker">MERCURIUS</div>
        <h1>{{ $lead->empresa ?? $lead->nome }}</h1>
        <p>Visualização completa da oportunidade comercial.</p>
    </div>

    <div class="topbar-actions">
        <a href="{{ route('leads.edit', $lead) }}" class="btn-secondary">Editar</a>
        <a href="{{ route('leads.index') }}" class="btn-secondary">Voltar</a>
    </div>
</div>

@if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="page-panel">
    <div class="form-grid">
        <div class="form-group">
            <label>Nome</label>
            <input type="text" value="{{ $lead->nome }}" readonly>
        </div>

        <div class="form-group">
            <label>Empresa</label>
            <input type="text" value="{{ $lead->empresa ?? '-' }}" readonly>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="text" value="{{ $lead->email ?? '-' }}" readonly>
        </div>

        <div class="form-group">
            <label>Telefone</label>
            <input type="text" value="{{ $lead->telefone ?? '-' }}" readonly>
        </div>

        <div class="form-group">
            <label>Origem</label>
            <input type="text" value="{{ $lead->origem ?? '-' }}" readonly>
        </div>

        <div class="form-group">
            <label>Status</label>
            <input type="text" value="{{ ucfirst(str_replace('_', ' ', $lead->status)) }}" readonly>
        </div>

        <div class="form-group">
            <label>Valor Estimado</label>
            <input type="text" value="R$ {{ number_format($lead->valor_estimado ?? 0, 2, ',', '.') }}" readonly>
        </div>

        <div class="form-group">
            <label>Próximo Contato</label>
            <input type="text" value="{{ $lead->proximo_contato ? $lead->proximo_contato->format('d/m/Y') : '-' }}" readonly>
        </div>

        <div class="form-group full">
            <label>Observações</label>
            <textarea rows="4" readonly>{{ $lead->observacoes }}</textarea>
        </div>
    </div>
</div>

<div style="height: 24px;"></div>

<div class="page-panel">
    <div class="topbar" style="margin-bottom: 20px;">
        <div>
            <div class="topbar-kicker">Rotina Comercial</div>
            <h1 style="font-size: 22px;">Tarefas</h1>
            <p>Ações de follow-up, cobrança de retorno e próximos passos.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('leads.tarefas.store', $lead) }}">
        @csrf

        <div class="form-grid">
            <div class="form-group full">
                <label>Título *</label>
                <input type="text" name="titulo" value="{{ old('titulo') }}" required>
            </div>

            <div class="form-group">
                <label>Prioridade *</label>
                <select name="prioridade" required>
                    <option value="media">Média</option>
                    <option value="alta">Alta</option>
                    <option value="baixa">Baixa</option>
                </select>
            </div>

            <div class="form-group">
                <label>Vencimento *</label>
                <input type="date" name="data_vencimento" value="{{ old('data_vencimento', now()->format('Y-m-d')) }}" required>
            </div>

            <div class="form-group full">
                <label>Descrição</label>
                <textarea name="descricao" rows="3">{{ old('descricao') }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                Criar Tarefa
            </button>
        </div>
    </form>

    <div style="height: 24px;"></div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Vencimento</th>
                    <th>Tarefa</th>
                    <th>Prioridade</th>
                    <th>Status</th>
                    <th>Responsável</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                @forelse($lead->tarefas as $tarefa)
                    <tr>
                        <td>{{ $tarefa->data_vencimento->format('d/m/Y') }}</td>
                        <td>
                            <strong>{{ $tarefa->titulo }}</strong>
                            <div style="color: var(--muted); margin-top: 6px;">
                                {{ $tarefa->descricao ?? '-' }}
                            </div>
                        </td>
                        <td>{{ ucfirst($tarefa->prioridade) }}</td>
                        <td>
                            <span class="badge
                                @if($tarefa->status === 'concluida') badge-success
                                @elseif($tarefa->status === 'cancelada') badge-danger
                                @elseif($tarefa->data_vencimento->isPast() && ! $tarefa->data_vencimento->isToday()) badge-danger
                                @else badge-warning
                                @endif
                            ">
                                {{ ucfirst($tarefa->status) }}
                            </span>
                        </td>
                        <td>{{ $tarefa->user->name ?? '-' }}</td>
                        <td>
                            <div class="form-actions">
                                @if($tarefa->status === 'pendente')
                                    <form method="POST" action="{{ route('leads.tarefas.complete', $tarefa) }}">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit" class="btn-primary">
                                            Concluir
                                        </button>
                                    </form>
                                @endif

                                <form method="POST"
                                      action="{{ route('leads.tarefas.destroy', $tarefa) }}"
                                      onsubmit="return confirm('Deseja remover esta tarefa?')">
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
                        <td colspan="6">Nenhuma tarefa comercial registrada para este lead.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="height: 24px;"></div>

<div class="page-panel">
    <div class="topbar" style="margin-bottom: 20px;">
        <div>
            <div class="topbar-kicker">Histórico Comercial</div>
            <h1 style="font-size: 22px;">Interações</h1>
            <p>Registro de contatos, reuniões, diagnósticos e próximos passos.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('leads.interacoes.store', $lead) }}">
        @csrf

        <div class="form-grid">
            <div class="form-group">
                <label>Tipo *</label>
                <select name="tipo" required>
                    <option value="contato">Contato</option>
                    <option value="reuniao">Reunião</option>
                    <option value="diagnostico">Diagnóstico</option>
                    <option value="proposta">Proposta</option>
                    <option value="negociacao">Negociação</option>
                    <option value="observacao">Observação</option>
                </select>
            </div>

            <div class="form-group">
                <label>Data *</label>
                <input type="datetime-local" name="data_interacao" value="{{ now()->format('Y-m-d\TH:i') }}" required>
            </div>

            <div class="form-group full">
                <label>Título *</label>
                <input type="text" name="titulo" value="{{ old('titulo') }}" required>
            </div>

            <div class="form-group full">
                <label>Descrição</label>
                <textarea name="descricao" rows="4">{{ old('descricao') }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                Registrar Interação
            </button>
        </div>
    </form>

    <div style="height: 24px;"></div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Tipo</th>
                    <th>Título</th>
                    <th>Responsável</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                @forelse($lead->interacoes as $interacao)
                    <tr>
                        <td>{{ $interacao->data_interacao->format('d/m/Y H:i') }}</td>
                        <td>{{ ucfirst($interacao->tipo) }}</td>
                        <td>
                            <strong>{{ $interacao->titulo }}</strong>
                            <div style="color: var(--muted); margin-top: 6px;">
                                {{ $interacao->descricao ?? '-' }}
                            </div>
                        </td>
                        <td>{{ $interacao->user->name ?? '-' }}</td>
                        <td>
                            <form method="POST"
                                  action="{{ route('leads.interacoes.destroy', $interacao) }}"
                                  onsubmit="return confirm('Deseja remover esta interação?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn-danger">
                                    Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Nenhuma interação registrada para este lead.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
