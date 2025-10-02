<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;

class UserProfileRelationshipTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa a relação um-para-um entre User e Profile.
     *
     * @return void
     */
    public function test_user_has_one_profile_relationship()
    {
        // Cria um usuário
        $user = User::factory()->create([
            'name' => 'Joana D\'arc',
            'email' => 'joana.darc@example.com',
        ]);

        // Cria um perfil associado a este usuário
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'telefone' => '21987654321',
        ]);

        // 1. Testa se o usuário tem um perfil associado
        $this->assertInstanceOf(Profile::class, $user->profile);
        $this->assertEquals($profile->id, $user->profile->id);

        // 2. Testa a relação inversa (perfil pertence a um usuário)
        $this->assertInstanceOf(User::class, $profile->user);
        $this->assertEquals($user->id, $profile->user->id);

        // 3. Verifica se os dados do perfil podem ser acessados a partir do usuário
        $this->assertEquals('21987654321', $user->profile->telefone);
    }
}