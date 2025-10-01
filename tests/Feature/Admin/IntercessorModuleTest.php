<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Membro;
use App\Models\PedidoOracao;

class IntercessorModuleTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $pedido;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        // Cria um membro e um pedido de oração para o teste
        $membro = Membro::factory()->create();
        $this->pedido = PedidoOracao::factory()->create(['membro_id' => $membro->id, 'status' => 'pendente']);
    }

    /**
     * Testa se um administrador pode registrar uma intercessão para um pedido.
     */
    public function test_admin_can_register_intercession()
    {
        $this->actingAs($this->admin);

        $intercessaoData = [
            'tipo_oracao' => 'individual',
            'observacoes' => 'Orei pelo pedido durante o meu devocional.',
        ];

        // Rota para registrar intercessão. Assumindo que a rota está definida.
        // Se a rota for aninhada, o nome pode precisar de ajuste.
        $response = $this->post(route('admin.intercessor.registrar-intercessao', $this->pedido), $intercessaoData);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Intercessão registrada com sucesso!');

        // Verifica se a intercessão foi criada no banco de dados
        $this->assertDatabaseHas('intercessoes', [
            'pedido_id' => $this->pedido->id,
            'user_id' => $this->admin->id,
            'observacoes' => 'Orei pelo pedido durante o meu devocional.',
        ]);

        // Verifica se o status do pedido foi atualizado
        $this->assertDatabaseHas('pedido_oracaos', [
            'id' => $this->pedido->id,
            'status' => 'em_oracao',
        ]);
    }
}