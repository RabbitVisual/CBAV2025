<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Evento;

class EventoModuleTest extends TestCase
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
     * Testa se um administrador pode criar um novo evento.
     */
    public function test_admin_can_create_event()
    {
        $this->actingAs($this->admin);

        $eventoData = [
            'titulo' => 'Conferência de Missões 2025',
            'descricao' => 'Um evento para despertar o chamado missionário.',
            'data_inicio' => now()->addMonth()->toDateString(),
            'gratuito' => true,
            'inscricao_obrigatoria' => true,
            'status' => 'ativo',
            'tipo_publico' => 'ambos',
            'tipo_evento' => 'conferencia',
            'ativo' => true,
        ];

        $response = $this->post(route('admin.eventos.store'), $eventoData);

        $response->assertRedirect(route('admin.eventos.index'));
        $response->assertSessionHas('success', 'Evento criado com sucesso!');

        $this->assertDatabaseHas('eventos', [
            'titulo' => 'Conferência de Missões 2025',
            'status' => 'ativo',
        ]);
    }
}