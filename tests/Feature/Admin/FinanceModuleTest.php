<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Transacao;

class FinanceModuleTest extends TestCase
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
     * Testa se um administrador pode criar uma nova transação financeira.
     */
    public function test_admin_can_create_transaction()
    {
        $this->actingAs($this->admin);

        $transactionData = [
            'valor' => 150.75,
            'tipo' => 'entrada',
            'descricao' => 'Dízimo referente ao mês de Outubro',
            'data_transacao' => now()->toDateString(),
            'status' => 'confirmado',
        ];

        $response = $this->post(route('admin.finance.transactions.store'), $transactionData);

        $response->assertRedirect(route('admin.finance.transactions.index'));
        $response->assertSessionHas('success', 'Transação criada com sucesso!');

        $this->assertDatabaseHas('transacoes', [
            'descricao' => 'Dízimo referente ao mês de Outubro',
            'valor' => 150.75,
            'status' => 'confirmado',
        ]);
    }
}