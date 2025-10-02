<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;

class MemberManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    /**
     * Testa se um administrador pode criar um novo membro (User e Profile).
     */
    public function test_admin_can_create_a_new_member_with_profile()
    {
        $this->actingAs($this->admin);

        $memberData = [
            'name' => 'Novo Membro Teste',
            'email' => 'novo.membro@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'ativo' => true,
            'telefone' => '11988887777',
            'data_nascimento' => '1990-01-15',
            'endereco' => 'Rua dos Testes, 123',
        ];

        $response = $this->post(route('admin.members.store'), $memberData);

        $response->assertRedirect(route('admin.members.index'));
        $response->assertSessionHas('success', 'Membro criado com sucesso!');

        // Verifica se o User foi criado
        $this->assertDatabaseHas('users', [
            'email' => 'novo.membro@example.com',
            'name' => 'Novo Membro Teste',
        ]);

        // Encontra o usuário criado para verificar o perfil associado
        $newUser = User::where('email', 'novo.membro@example.com')->first();

        // Verifica se o Profile foi criado e associado corretamente
        $this->assertDatabaseHas('profiles', [
            'user_id' => $newUser->id,
            'telefone' => '11988887777',
            'endereco' => 'Rua dos Testes, 123',
        ]);
    }
}