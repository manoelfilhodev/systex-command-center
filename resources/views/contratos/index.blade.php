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

            <a href="{{ route('contratos.create') }}" class="btn-primary">
                + Novo Contrato
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
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
                            <td colspan="8">
                                Nenhum contrato cadastrado ainda.
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
