@extends('layouts.systex')

@section('content')

<div class="topbar">
    <div>
        <div class="topbar-kicker">HADES + ATLAS</div>
        <h1>Editar Usuário</h1>
        <p>Atualização de dados, perfil e senha de acesso.</p>
    </div>

    <div class="topbar-actions">
        <a href="{{ route('usuarios.index') }}" class="btn-secondary">
            Voltar
        </a>
    </div>
</div>

@if($errors->any())
    <div class="alert-error">
        <strong>Existem erros no formulário:</strong>
        <ul style="margin-top:10px; padding-left:18px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('usuarios.update', $usuario) }}">
    @csrf
    @method('PUT')

    <div class="page-panel">
        <div class="form-grid">
            <div class="form-group">
                <label>Nome *</label>
                <input type="text" name="name" value="{{ old('name', $usuario->name) }}" required>
            </div>

            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" value="{{ old('email', $usuario->email) }}" required>
            </div>

            <div class="form-group">
                <label>Perfil *</label>
                <select name="role" required>
                    @foreach($roles as $role => $label)
                        <option value="{{ $role }}" {{ old('role', $usuario->role) === $role ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Nova senha</label>
                <input type="password" name="password" minlength="8">
            </div>
        </div>
    </div>

    <div style="height: 20px;"></div>

    <div class="form-actions">
        <button type="submit" class="btn-primary">
            Atualizar Usuário
        </button>

        <a href="{{ route('usuarios.index') }}" class="btn-secondary">
            Cancelar
        </a>
    </div>
</form>

@endsection
