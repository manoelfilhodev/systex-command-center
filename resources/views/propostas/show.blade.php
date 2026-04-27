@extends('layouts.systex')

@section('content')

<div class="topbar">
    <div>
        <div class="topbar-kicker">MERCURIUS + CRONOS</div>
        <h1>{{ $proposta->titulo }}</h1>
        <p>Visualização completa da proposta comercial.</p>
    </div>

    <div class="topbar-actions">
        <a href="{{ route('propostas.edit', $proposta) }}" class="btn-secondary">
            Editar
        </a>

        <a href="{{ route('propostas.index') }}" class="btn-secondary">
            Voltar
        </a>
    </div>
</div>

<div class="grid">

    <div class="card">
        <div class="card-title">Número da Proposta</div>
        <div class="card-value">{{ $proposta->numero }}</div>
        <div class="card-subtitle">Identificador comercial oficial</div>
    </div>

    <div class="card">
        <div class="card-title">Status Comercial</div>
        <div class="card-value">{{ ucfirst($proposta->status) }}</div>
        <div class="card-subtitle">Pipeline atual da negociação</div>
    </div>

    <div class="card">
        <div class="card-title">Valor Total</div>
        <div class="card-value">
            R$ {{ number_format($proposta->valor_total, 2, ',', '.') }}
        </div>
        <div class="card-subtitle">Implantação + recorrência + itens</div>
    </div>

</div>

<div style="height: 20px;"></div>

<div class="page-panel">

    <h3 style="margin-bottom: 20px;">Informações Gerais</h3>

    <div class="form-grid">

        <div class="form-group">
            <label>Lead Vinculado</label>
            <input
                type="text"
                value="{{ $proposta->lead->nome ?? 'Não vinculado' }}"
                readonly
            >
        </div>

        <div class="form-group">
            <label>Cliente</label>
            <input
                type="text"
                value="{{ $proposta->cliente->nome ?? 'Não vinculado' }}"
                readonly
            >
        </div>

        <div class="form-group">
            <label>Valor Implantação</label>
            <input
                type="text"
                value="R$ {{ number_format($proposta->valor_implantacao, 2, ',', '.') }}"
                readonly
            >
        </div>

        <div class="form-group">
            <label>Valor Recorrente</label>
            <input
                type="text"
                value="R$ {{ number_format($proposta->valor_recorrente, 2, ',', '.') }}"
                readonly
            >
        </div>

        <div class="form-group full">
            <label>Escopo</label>
            <textarea rows="4" readonly>{{ $proposta->escopo }}</textarea>
        </div>

        <div class="form-group full">
            <label>Observações</label>
            <textarea rows="4" readonly>{{ $proposta->observacoes }}</textarea>
        </div>

    </div>

</div>

<div style="height: 20px;"></div>

<div class="page-panel">

    <h3 style="margin-bottom: 20px;">Itens da Proposta</h3>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Tipo</th>
                    <th>Qtd</th>
                    <th>Valor Unitário</th>
                    <th>Total</th>
                </tr>
            </thead>

            <tbody>
                @forelse($proposta->itens as $item)
                    <tr>
                        <td>{{ $item->descricao }}</td>
                        <td>{{ ucfirst($item->tipo) }}</td>
                        <td>{{ $item->quantidade }}</td>
                        <td>R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                        <td>
                            <strong>
                                R$ {{ number_format($item->valor_total, 2, ',', '.') }}
                            </strong>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            Nenhum item cadastrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
