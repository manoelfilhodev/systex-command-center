@extends('layouts.systex')

@section('content')

<div class="topbar">
    <div>
        <div class="topbar-kicker">HADES + ATLAS</div>
        <h1>Usuários</h1>
        <p>Gestão de acessos, perfis e responsabilidades dentro do Command Center.</p>
    </div>

    <div class="topbar-actions">
        <span class="system-pill">
            <span class="status-dot"></span>
            Controle de Acesso
        </span>

        <a href="{{ route('usuarios.create') }}" class="btn-primary">
            + Novo Usuário
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert-error">
        <strong>Existem erros na operação:</strong>
        <ul style="margin-top:10px; padding-left:18px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="page-panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Perfil</th>
                    <th>Criado em</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                @forelse($usuarios as $usuario)
                    <tr>
                        <td>
                            <strong>{{ $usuario->name }}</strong>
                        </td>
                        <td>{{ $usuario->email }}</td>
                        <td>
                            <span class="badge {{ $usuario->role === 'admin' ? 'badge-danger' : 'badge-success' }}">
                                {{ $roles[$usuario->role] ?? ucfirst($usuario->role) }}
                            </span>
                        </td>
                        <td>{{ $usuario->created_at?->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="form-actions">
                                <a href="{{ route('usuarios.edit', $usuario) }}" class="btn-secondary">
                                    Editar
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route('usuarios.destroy', $usuario) }}"
                                    onsubmit="return confirm('Deseja remover este usuário?')"
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
                        <td colspan="5">
                            <x-empty-state
                                title="Nenhum usuário cadastrado"
                                description="Crie usuários por perfil para manter acesso rastreável e seguro."
                                :href="route('usuarios.create')"
                                action="Cadastrar usuário"
                            />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $usuarios->links() }}
    </div>
</div>

@endsection
