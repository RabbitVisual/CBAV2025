<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Disciplina;

class EbdDisciplinaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    }

    /**
     * Testa se um administrador pode criar uma nova disciplina da EBD.
     */
    public function test_admin_can_create_ebd_disciplina()
    {
        // Cria e autentica um usuário admin
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        // Dados da nova disciplina
        $disciplinaData = [
            'nome' => 'Teologia Sistemática I',
            'descricao' => 'Estudo sobre a doutrina de Deus e da Bíblia.',
            'codigo_disciplina' => 'TEO101',
            'ativo' => true,
        ];

        // Simula uma requisição POST para a rota de criação
        $response = $this->post(route('admin.ebd.disciplinas.store'), $disciplinaData);

        // Verifica o redirecionamento e a mensagem de sucesso
        $response->assertRedirect(route('admin.ebd.disciplinas.index'));
        $response->assertSessionHas('success', 'Disciplina criada com sucesso.');

        // Verifica se a disciplina foi criada no banco de dados
        $this->assertDatabaseHas('ebd_disciplinas', [
            'codigo_disciplina' => 'TEO101',
            'nome' => 'Teologia Sistemática I',
        ]);
    }
}