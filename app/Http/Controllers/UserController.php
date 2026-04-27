<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private const ROLES = [
        'admin' => 'Administrador',
        'diretoria' => 'Diretoria',
        'comercial' => 'Comercial',
        'financeiro' => 'Financeiro',
        'operacao' => 'Operação',
    ];

    public function index()
    {
        $usuarios = User::orderBy('name')->paginate(10);
        $roles = self::ROLES;

        return view('usuarios.index', compact('usuarios', 'roles'));
    }

    public function create()
    {
        $roles = self::ROLES;

        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(array_keys(self::ROLES))],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create($data);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuário cadastrado com sucesso.');
    }

    public function edit(User $usuario)
    {
        $roles = self::ROLES;

        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, User $usuario)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($usuario->id)],
            'role' => ['required', Rule::in(array_keys(self::ROLES))],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        if ($usuario->role === 'admin' && $data['role'] !== 'admin' && $this->isLastAdmin($usuario)) {
            return back()
                ->withErrors(['role' => 'Não é possível remover o perfil do último administrador.'])
                ->withInput();
        }

        if (blank($data['password'])) {
            unset($data['password']);
        }

        $usuario->update($data);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuário atualizado com sucesso.');
    }

    public function destroy(Request $request, User $usuario)
    {
        if ($request->user()->is($usuario)) {
            return back()->withErrors(['usuario' => 'Você não pode excluir o próprio usuário logado.']);
        }

        if ($usuario->role === 'admin' && $this->isLastAdmin($usuario)) {
            return back()->withErrors(['usuario' => 'Não é possível excluir o último administrador.']);
        }

        $usuario->delete();

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuário removido com sucesso.');
    }

    private function isLastAdmin(User $usuario): bool
    {
        return User::where('role', 'admin')
            ->whereKeyNot($usuario->id)
            ->doesntExist();
    }
}
