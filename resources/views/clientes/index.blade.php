@extends('layouts.systex')

@section('content')

<div class="topbar">
    <div>
        <div class="topbar-kicker">AURORA + MERCURIUS</div>
        <h1>Clientes</h1>
        <p>Gestão da base ativa de clientes e contratos da SYSTEX.</p>
    </div>

    <div class="topbar-actions">
        <span class="system-pill">
            <span class="status-dot"></span>
            Base Comercial
        </span>

        <a href="{{ route('clientes.create') }}" class="btn-primary">
            + Novo Cliente
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
                    <th>Nome Fantasia</th>
                    <th>Razão Social</th>
                    <th>CNPJ</th>
                    <th>Responsável</th>
                    <th>Status</th>
                    <th>Cidade</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                @forelse($clientes as $cliente)
                    <tr>
                        <td>
                            <strong>
                                {{ $cliente->nome_fantasia ?? '-' }}
                            </strong>
                        </td>

                        <td>{{ $cliente->razao_social }}</td>

                        <td>{{ $cliente->cnpj ?? '-' }}</td>

                        <td>{{ $cliente->responsavel ?? '-' }}</td>

                        <td>
                            <span class="badge
                                @if($cliente->status === 'ativo') badge-success
                                @elseif(in_array($cliente->status, ['prospect'])) badge-warning
                                @elseif(in_array($cliente->status, ['inativo', 'suspenso'])) badge-danger
                                @endif
                            ">
                                {{ ucfirst($cliente->status) }}
                            </span>
                        </td>

                        <td>
                            {{ $cliente->cidade ?? '-' }}/{{ $cliente->estado ?? '-' }}
                        </td>

                        <td>
                            <div class="form-actions">

                                <a href="{{ route('clientes.show', $cliente) }}" class="btn-secondary">
                                    Ver
                                </a>

                                <a href="{{ route('clientes.edit', $cliente) }}" class="btn-secondary">
                                    Editar
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route('clientes.destroy', $cliente) }}"
                                    onsubmit="return confirm('Deseja remover este cliente?')"
                                >
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
                            Nenhum cliente cadastrado ainda.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $clientes->links() }}
    </div>

</div>

@endsection
