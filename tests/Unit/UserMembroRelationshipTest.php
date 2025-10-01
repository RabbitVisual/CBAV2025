<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Membro;

class UserMembroRelationshipTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa a relação um-para-um entre User e Membro.
     *
     * @return void
     */
    public function test_user_has_one_membro_relationship()
    {
        // Cria um usuário
        $user = User::factory()->create([
            'name' => 'João da Silva',
            'email' => 'joao.silva@example.com',
        ]);

        // Cria um perfil de membro associado a este usuário
        $membro = Membro::factory()->create([
            'user_id' => $user->id,
            'telefone' => '11999998888',
        ]);

        // 1. Testa se o usuário tem um membro associado
        $this->assertInstanceOf(Membro::class, $user->membro);
        $this->assertEquals($membro->id, $user->membro->id);

        // 2. Testa a relação inversa (membro pertence a um usuário)
        $this->assertInstanceOf(User::class, $membro->user);
        $this->assertEquals($user->id, $membro->user->id);

        // 3. Verifica se os dados do usuário podem ser acessados a partir do membro
        $this->assertEquals('João da Silva', $membro->user->name);
    }
}