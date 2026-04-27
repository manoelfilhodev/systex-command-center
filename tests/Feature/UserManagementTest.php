<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_update_and_delete_users(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('usuarios.index'))
            ->assertOk()
            ->assertSee('Usuários')
            ->assertSee('Novo Usuário');

        $this->actingAs($admin)
            ->post(route('usuarios.store'), [
                'name' => 'Isabela Souredo',
                'email' => 'isabela.teste@systex.com.br',
                'role' => 'comercial',
                'password' => 'senha-segura',
            ])
            ->assertRedirect(route('usuarios.index'));

        $usuario = User::where('email', 'isabela.teste@systex.com.br')->firstOrFail();

        $this->assertSame('comercial', $usuario->role);
        $this->assertTrue(Hash::check('senha-segura', $usuario->password));

        $this->actingAs($admin)
            ->put(route('usuarios.update', $usuario), [
                'name' => 'Isabela Souredo',
                'email' => 'isabela.teste@systex.com.br',
                'role' => 'financeiro',
                'password' => 'nova-senha-segura',
            ])
            ->assertRedirect(route('usuarios.index'));

        $usuario->refresh();

        $this->assertSame('financeiro', $usuario->role);
        $this->assertTrue(Hash::check('nova-senha-segura', $usuario->password));

        $this->actingAs($admin)
            ->delete(route('usuarios.destroy', $usuario))
            ->assertRedirect(route('usuarios.index'));

        $this->assertDatabaseMissing('users', ['email' => 'isabela.teste@systex.com.br']);
    }

    public function test_non_admin_users_cannot_manage_users(): void
    {
        foreach (['diretoria', 'comercial', 'financeiro', 'operacao'] as $role) {
            $user = User::factory()->create(['role' => $role]);

            $this->actingAs($user)
                ->get(route('usuarios.index'))
                ->assertForbidden();
        }
    }

    public function test_admin_cannot_delete_own_user_or_last_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->delete(route('usuarios.destroy', $admin))
            ->assertSessionHasErrors('usuario');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);

        $secondAdmin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->delete(route('usuarios.destroy', $secondAdmin))
            ->assertRedirect(route('usuarios.index'));

        $this->actingAs($admin)
            ->put(route('usuarios.update', $admin), [
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => 'comercial',
                'password' => null,
            ])
            ->assertSessionHasErrors('role');
    }

    public function test_admin_sidebar_exposes_user_management(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('dashboard.index'))
            ->assertOk()
            ->assertSee('href="'.route('usuarios.index').'"', false)
            ->assertSee('Usuários');
    }
}
