<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\EbdService;
use App\Models\EbdTurma;
use App\Models\BibleService; // Mocking this dependency

class EbdServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa se o EbdService consegue criar uma nova turma.
     *
     * @return void
     */
    public function test_ebd_service_can_create_turma()
    {
        // O BibleService não é usado para criar turmas, mas o EbdService
        // o espera no construtor. Podemos mocká-lo para isolar o teste.
        $bibleServiceMock = $this->createMock(BibleService::class);
        $ebdService = new EbdService($bibleServiceMock);

        // Dados da nova turma
        $turmaData = [
            'nome' => 'Turma de Teste Unitário',
            'descricao' => 'Descrição da turma de teste.',
            'faixa_etaria' => 'Jovens',
            'ativo' => true,
        ];

        // Chama o método do serviço diretamente
        $turmaCriada = $ebdService->createTurma($turmaData);

        // Verifica se o objeto retornado é uma instância de EbdTurma
        $this->assertInstanceOf(EbdTurma::class, $turmaCriada);

        // Verifica se os dados foram salvos corretamente no banco de dados
        $this->assertDatabaseHas('ebd_turmas', [
            'nome' => 'Turma de Teste Unitário',
            'faixa_etaria' => 'Jovens',
        ]);
    }
}