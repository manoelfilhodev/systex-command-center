@extends('layouts.systex')

@section('content')
    <div class="topbar">
        <div>
            <div class="topbar-kicker">TITAN</div>
            <h1>Implantação #{{ $implantacao->id }}</h1>
            <p>Painel executivo da implantação, operação, entrega e pós-venda.</p>
        </div>

        <div class="topbar-actions">
            <a href="{{ route('implantacoes.edit', $implantacao) }}" class="btn-primary">
                Editar Implantação
            </a>

            <a href="{{ route('implantacoes.index') }}" class="btn-secondary">
                Voltar
            </a>
        </div>
    </div>

    <div class="grid">
        <div class="card">
            <div class="card-title">Cliente</div>
            <div class="card-value">
                {{ $implantacao->contrato->cliente->nome_fantasia ?? ($implantacao->contrato->cliente->razao_social ?? '-') }}
            </div>
            <div class="card-subtitle">Cliente vinculado ao contrato</div>
        </div>

        <div class="card">
            <div class="card-title">Contrato</div>
            <div class="card-value">{{ $implantacao->contrato->numero ?? '-' }}</div>
            <div class="card-subtitle">Contrato base da implantação</div>
        </div>

        <div class="card">
            <div class="card-title">Status</div>
            <div class="card-value">{{ ucfirst(str_replace('_', ' ', $implantacao->status)) }}</div>
            <div class="card-subtitle">Situação atual da implantação</div>
        </div>

        <div class="card">
            <div class="card-title">Responsável</div>
            <div class="card-value">{{ $implantacao->responsavel ?? '-' }}</div>
            <div class="card-subtitle">Responsável operacional</div>
        </div>
    </div>

    <div style="height: 20px;"></div>

    <div class="page-panel">
        <h3>Resumo da Implantação</h3>

        <p>
            <strong>Data de início:</strong>
            {{ $implantacao->data_inicio ? \Carbon\Carbon::parse($implantacao->data_inicio)->format('d/m/Y') : '-' }}
        </p>

        <p>
            <strong>Data Go Live:</strong>
            {{ $implantacao->data_go_live ? \Carbon\Carbon::parse($implantacao->data_go_live)->format('d/m/Y') : '-' }}
        </p>

        <p>
            <strong>Observações:</strong><br>
            {{ $implantacao->observacoes ?? 'Nenhuma observação registrada.' }}
        </p>
    </div>

    <div style="height: 20px;"></div>

    <div class="page-panel">
        <div class="topbar" style="padding: 0; border: 0; margin-bottom: 20px;">
            <div>
                <div class="topbar-kicker">TITAN</div>
                <h2>Etapas da Implantação</h2>
                <p>Acompanhamento operacional por fase da entrega</p>
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Ordem</th>
                        <th>Etapa</th>
                        <th>Status</th>
                        <th>Início</th>
                        <th>Fim</th>
                        <th>Observações</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($implantacao->etapas as $etapa)
                        <tr>
                            <form method="POST" action="{{ route('implantacoes.etapas.update', $etapa) }}">
                                @csrf
                                @method('PUT')

                                <td>
                                    <input type="number" name="ordem" class="form-control" value="{{ $etapa->ordem }}"
                                        min="1">
                                </td>

                                <td>
                                    <input type="text" name="nome" class="form-control" value="{{ $etapa->nome }}"
                                        required>
                                </td>

                                <td>
                                    <select name="status" class="form-control" required>
                                        <option value="pendente" @selected($etapa->status === 'pendente')>Pendente</option>
                                        <option value="em_andamento" @selected($etapa->status === 'em_andamento')>Em andamento</option>
                                        <option value="concluida" @selected($etapa->status === 'concluida')>Concluída</option>
                                        <option value="bloqueada" @selected($etapa->status === 'bloqueada')>Bloqueada</option>
                                    </select>
                                </td>

                                <td>
                                    <input type="date" name="data_inicio" class="form-control"
                                        value="{{ $etapa->data_inicio }}">
                                </td>

                                <td>
                                    <input type="date" name="data_fim" class="form-control"
                                        value="{{ $etapa->data_fim }}">
                                </td>

                                <td>
                                    <input type="text" name="observacoes" class="form-control"
                                        value="{{ $etapa->observacoes }}">
                                </td>

                                <td>
                                    <div class="form-actions">
                                        <button type="submit" class="btn-secondary">
                                            Salvar
                                        </button>
                            </form>

                            <form method="POST" action="{{ route('implantacoes.etapas.destroy', $etapa) }}"
                                onsubmit="return confirm('Deseja remover esta etapa?')">
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
            <td colspan="7">
                Nenhuma etapa cadastrada ainda.
            </td>
        </tr>
        @endforelse
        </tbody>
        </table>
    </div>

    <div style="height: 20px;"></div>

    <form method="POST" action="{{ route('implantacoes.etapas.store', $implantacao) }}">
        @csrf

        <div class="grid">
            <div class="card">
                <div class="card-title">Ordem</div>
                <input type="number" name="ordem" class="form-control" value="1" min="1">
            </div>

            <div class="card">
                <div class="card-title">Nova Etapa</div>
                <input type="text" name="nome" class="form-control" required>
            </div>

            <div class="card">
                <div class="card-title">Status</div>
                <select name="status" class="form-control" required>
                    <option value="pendente">Pendente</option>
                    <option value="em_andamento">Em andamento</option>
                    <option value="concluida">Concluída</option>
                    <option value="bloqueada">Bloqueada</option>
                </select>
            </div>

            <div class="card">
                <div class="card-title">Data Início</div>
                <input type="date" name="data_inicio" class="form-control">
            </div>

            <div class="card">
                <div class="card-title">Data Fim</div>
                <input type="date" name="data_fim" class="form-control">
            </div>
        </div>

        <div style="height: 20px;"></div>

        <div class="card">
            <div class="card-title">Observações</div>
            <textarea name="observacoes" rows="4" class="form-control"></textarea>
        </div>

        <div style="height: 20px;"></div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                Adicionar Etapa
            </button>
        </div>
    </form>
    </div>
@endsection
