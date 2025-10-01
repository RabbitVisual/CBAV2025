<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Disciplina;
use App\Models\EbdLicao;
use App\Models\Atividade;

class EbdAtividadeTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $licao;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        // Cria um usuário admin
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        // Cria uma disciplina e uma lição para associar a atividade
        $disciplina = Disciplina::factory()->create();
        $this->licao = EbdLicao::factory()->create(['disciplina_id' => $disciplina->id]);
    }

    /**
     * Testa se um administrador pode criar uma nova atividade para uma lição.
     */
    public function test_admin_can_create_ebd_atividade()
    {
        // Autentica como admin
        $this->actingAs($this->admin);

        // Dados da nova atividade
        $atividadeData = [
            'titulo' => 'Reflexão sobre Gênesis 1',
            'descricao' => 'Escreva uma reflexão pessoal sobre o primeiro capítulo de Gênesis.',
            'tipo' => 'reflexao_pessoal',
            'pontuacao_maxima' => 10,
            'ativo' => true,
        ];

        // Simula uma requisição POST para a rota de criação de atividades
        $response = $this->post(route('admin.ebd.licoes.atividades.store', $this->licao), $atividadeData);

        // Verifica o redirecionamento e a mensagem de sucesso
        $response->assertRedirect(route('admin.ebd.licoes.show', $this->licao));
        $response->assertSessionHas('success', 'Atividade criada com sucesso.');

        // Verifica se a atividade foi criada no banco de dados com a lição correta
        $this->assertDatabaseHas('ebd_atividades', [
            'licao_id' => $this->licao->id,
            'titulo' => 'Reflexão sobre Gênesis 1',
            'tipo' => 'reflexao_pessoal',
        ]);
    }
}