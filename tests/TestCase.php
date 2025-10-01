<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // Otimizações para reduzir uso de memória
        $this->optimizeForTesting();
    }

    protected function optimizeForTesting(): void
    {
        // Desabilita serviços pesados durante testes
        config([
            'telescope.enabled' => false,
            'pulse.enabled' => false,
            'nightwatch.enabled' => false,
            'logging.default' => 'single',
            'logging.level' => 'error',
            'cache.default' => 'array',
            'queue.default' => 'sync',
            'session.driver' => 'array',
            'mail.default' => 'array',
        ]);

        // Limpa cache antes de cada teste
        $this->clearCaches();
    }

    protected function clearCaches(): void
    {
        // Limpa apenas o cache principal para evitar problemas de compatibilidade
        try {
            app('cache')->flush();
        } catch (\Exception $e) {
            // Ignora erros de cache
        }
    }

    protected function tearDown(): void
    {
        // Limpa memória após cada teste
        $this->clearCaches();

        // Força garbage collection
        if (function_exists('gc_collect_cycles')) {
            gc_collect_cycles();
        }

        parent::tearDown();
    }
}
