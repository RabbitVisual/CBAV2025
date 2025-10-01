<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\EbdService;
use App\Models\Disciplina;
use App\Services\BibleService; // Mocking this dependency

class EbdServiceDisciplinaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa se o EbdService pode criar uma nova disciplina.
     *
     * @return void
     */
    public function test_ebd_service_can_create_disciplina()
    {
        // Mock do BibleService, pois é uma dependência do EbdService
        $bibleServiceMock = $this->createMock(BibleService::class);
        $ebdService = new EbdService($bibleServiceMock);

        // Dados da nova disciplina
        $disciplinaData = [
            'nome' => 'Hermenêutica',
            'descricao' => 'A arte e ciência da interpretação bíblica.',
            'codigo_disciplina' => 'HER201',
            'ativo' => true,
        ];

        // Chama o método do serviço diretamente
        $disciplinaCriada = $ebdService->createDisciplina($disciplinaData);

        // Verifica se o objeto retornado é uma instância de Disciplina
        $this->assertInstanceOf(Disciplina::class, $disciplinaCriada);

        // Verifica se os dados foram salvos corretamente no banco de dados
        $this->assertDatabaseHas('ebd_disciplinas', [
            'codigo_disciplina' => 'HER201',
            'nome' => 'Hermenêutica',
        ]);
    }
}