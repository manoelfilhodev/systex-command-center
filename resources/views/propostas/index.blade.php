@extends('layouts.systex')

@section('content')

<div class="topbar">
    <div>
        <div class="topbar-kicker">MERCURIUS + CRONOS</div>
        <h1>Propostas Comerciais</h1>
        <p>Controle executivo das propostas, valores de implantação, recorrência e status comercial.</p>
    </div>

    <div class="topbar-actions">
        <span class="system-pill">
            <span class="status-dot"></span>
            Pipeline Comercial
        </span>

        @if(auth()->user()->hasAnyRole(['comercial']))
            <a href="{{ route('propostas.create') }}" class="btn-primary">
                + Nova Proposta
            </a>
        @endif
    </div>
</div>

@if(session('success'))
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
                    <th>Título</th>
                    <th>Lead / Cliente</th>
                    <th>Status</th>
                    <th>Implantação</th>
                    <th>Recorrente</th>
                    <th>Total</th>
                    <th>Validade</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                @forelse($propostas as $proposta)
                    <tr>
                        <td>{{ $proposta->numero }}</td>

                        <td>
                            <strong>{{ $proposta->titulo }}</strong>
                        </td>

                        <td>
                            {{ $proposta->lead->nome ?? $proposta->cliente->nome ?? 'Não vinculado' }}
                        </td>

                        <td>
                            <span class="badge
                                @if($proposta->status === 'aprovada') badge-success
                                @elseif($proposta->status === 'negociacao') badge-warning
                                @elseif(in_array($proposta->status, ['recusada', 'cancelada'])) badge-danger
                                @endif
                            ">
                                {{ ucfirst($proposta->status) }}
                            </span>
                        </td>

                        <td>R$ {{ number_format($proposta->valor_implantacao, 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($proposta->valor_recorrente, 2, ',', '.') }}</td>
                        <td><strong>R$ {{ number_format($proposta->valor_total, 2, ',', '.') }}</strong></td>

                        <td>
                            {{ $proposta->data_validade ? \Carbon\Carbon::parse($proposta->data_validade)->format('d/m/Y') : '-' }}
                        </td>

                        <td>
                            <div class="form-actions">
                                <a href="{{ route('propostas.show', $proposta) }}" class="btn-secondary">Ver</a>
                                <a href="{{ route('propostas.edit', $proposta) }}" class="btn-secondary">Editar</a>

                                <form method="POST" action="{{ route('propostas.destroy', $proposta) }}" onsubmit="return confirm('Deseja remover esta proposta?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger">Excluir</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">
                            Nenhuma proposta cadastrada ainda.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $propostas->links() }}
    </div>

</div>

@endsection
