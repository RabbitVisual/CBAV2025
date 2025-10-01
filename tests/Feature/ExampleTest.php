<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Teste básico de exemplo para verificar se o ambiente está funcionando
     */
    public function test_basic_example(): void
    {
        $this->assertTrue(true);
    }

    /**
     * Teste de aplicação - verifica se a aplicação carrega corretamente
     */
    public function test_application_loads(): void
    {
        $app = $this->createApplication();
        $this->assertNotNull($app);
    }

    /**
     * Teste de configuração - verifica se as configurações de teste estão corretas
     */
    public function test_testing_configuration(): void
    {
        $this->assertEquals('testing', config('app.env'));
        $this->assertEquals('array', config('cache.default'));
        $this->assertEquals('sync', config('queue.default'));
        $this->assertEquals('array', config('session.driver'));
    }
}
