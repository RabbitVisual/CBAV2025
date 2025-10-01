<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\EbdTurma;

class EbdTurmaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Criar o role de admin se não existir
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    }

    /**
     * Testa se um administrador pode criar uma nova turma da EBD.
     *
     * @return void
     */
    public function test_admin_can_create_ebd_turma()
    {
        // Cria um usuário com o papel de administrador
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // Autentica o administrador
        $this->actingAs($admin);

        // Dados da nova turma
        $turmaData = [
            'nome' => 'Nova Turma de Adultos',
            'descricao' => 'Estudo aprofundado do livro de Romanos.',
            'faixa_etaria' => 'Adultos',
            'ativo' => true,
        ];

        // Simula uma requisição POST para a rota de armazenamento
        $response = $this->post(route('admin.ebd.turmas.store'), $turmaData);

        // Verifica se o usuário foi redirecionado para a página de listagem
        $response->assertRedirect(route('admin.ebd.turmas.index'));
        $response->assertSessionHas('success', 'Turma criada com sucesso!');

        // Verifica se a turma foi realmente criada no banco de dados
        $this->assertDatabaseHas('ebd_turmas', [
            'nome' => 'Nova Turma de Adultos',
            'descricao' => 'Estudo aprofundado do livro de Romanos.',
        ]);
    }
}