@extends('layouts.systex')

@section('content')

<div class="topbar">
    <div>
        <div class="topbar-kicker">MERCURIUS</div>
        <h1>Leads</h1>
        <p>Gestão inicial do funil comercial da SYSTEX.</p>
    </div>

    <div class="topbar-actions">
        <span class="system-pill">
            <span class="status-dot"></span>
            Funil Comercial
        </span>

        <a href="{{ route('leads.create') }}" class="btn-primary">
            + Novo Lead
        </a>
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
                    <th>Nome</th>
                    <th>Empresa</th>
                    <th>Telefone</th>
                    <th>Status</th>
                    <th>Valor Estimado</th>
                    <th>Próximo Contato</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                @forelse($leads as $lead)
                    <tr>
                        <td>
                            <strong>{{ $lead->nome }}</strong>
                        </td>

                        <td>{{ $lead->empresa ?? '-' }}</td>

                        <td>{{ $lead->telefone ?? '-' }}</td>

                        <td>
                            <span class="badge
                                @if($lead->status === 'novo') badge-success
                                @elseif(in_array($lead->status, ['diagnostico', 'negociacao'])) badge-warning
                                @elseif(in_array($lead->status, ['perdido', 'cancelado'])) badge-danger
                                @endif
                            ">
                                {{ ucfirst($lead->status) }}
                            </span>
                        </td>

                        <td>
                            R$ {{ number_format($lead->valor_estimado ?? 0, 2, ',', '.') }}
                        </td>

                        <td>
                            {{ $lead->proximo_contato ? \Carbon\Carbon::parse($lead->proximo_contato)->format('d/m/Y') : '-' }}
                        </td>

                        <td>
                            
                            <div class="form-actions">

    @if(Route::has('leads.show'))
        <a href="{{ route('leads.show', $lead) }}" class="btn-secondary">
            Ver
        </a>
    @endif

    <a
        href="{{ route('propostas.create', ['lead_id' => $lead->id]) }}"
        class="btn-primary"
        style="padding: 10px 14px; font-size: 13px;"
    >
        Converter
    </a>

    @if(Route::has('leads.edit'))
        <a href="{{ route('leads.edit', $lead) }}" class="btn-secondary">
            Editar
        </a>
    @endif

    @if(Route::has('leads.destroy'))
        <form method="POST"
              action="{{ route('leads.destroy', $lead) }}"
              onsubmit="return confirm('Deseja remover este lead?')">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn-danger">
                Excluir
            </button>
        </form>
    @endif

</div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            Nenhum lead cadastrado ainda.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($leads, 'links'))
        <div style="margin-top: 20px;">
            {{ $leads->links() }}
        </div>
    @endif

</div>

@endsection
