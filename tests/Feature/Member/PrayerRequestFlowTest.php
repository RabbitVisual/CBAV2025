<?php

namespace Tests\Feature\Member;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;

class PrayerRequestFlowTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        // Cria um usuário com perfil para o teste
        $this->user = User::factory()->create();
        $this->user->profile()->save(Profile::factory()->make());
    }

    /**
     * Testa se um membro pode criar um novo pedido de oração.
     */
    public function test_member_can_create_a_prayer_request()
    {
        // Autentica como o membro
        $this->actingAs($this->user);

        // Dados do novo pedido de oração
        $prayerRequestData = [
            'titulo' => 'Oração por sabedoria',
            'descricao' => 'Peço orações por sabedoria e discernimento em uma decisão importante.',
            'categoria' => 'espiritual',
            'prioridade' => 'alta',
            'anonimo' => false,
            'pode_compartilhar' => true,
        ];

        // Simula uma requisição POST para a rota de criação de pedidos
        // Assumindo que a rota foi renomeada para 'member.prayer-requests.store'
        $response = $this->post(route('member.prayer-requests.store'), $prayerRequestData);

        // Verifica o redirecionamento e a mensagem de sucesso
        $response->assertRedirect(route('member.prayer-requests.index'));
        $response->assertSessionHas('success', 'Pedido de oração criado com sucesso!');

        // Verifica se o pedido foi criado no banco de dados e associado ao usuário correto
        $this->assertDatabaseHas('prayer_requests', [
            'user_id' => $this->user->id,
            'titulo' => 'Oração por sabedoria',
            'categoria' => 'espiritual',
        ]);
    }
}