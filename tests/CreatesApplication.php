<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        // Otimizações específicas para testes
        $this->optimizeApplicationForTesting($app);

        return $app;
    }

    /**
     * Otimiza a aplicação para testes
     */
    protected function optimizeApplicationForTesting($app): void
    {
        // Configurações específicas para reduzir uso de memória
        $app['config']->set([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'cache.default' => 'array',
            'queue.default' => 'sync',
            'session.driver' => 'array',
            'mail.default' => 'array',
            'logging.default' => 'single',
            'logging.level' => 'error',
            'telescope.enabled' => false,
            'pulse.enabled' => false,
            'nightwatch.enabled' => false,
        ]);

        // Desabilita serviços desnecessários
        $app->instance('telescope', null);
        $app->instance('pulse', null);
        $app->instance('nightwatch', null);
    }
}
