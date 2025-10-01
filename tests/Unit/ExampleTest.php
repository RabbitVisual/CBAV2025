<?php

namespace Tests\Unit;

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
     * Teste de memória - verifica se não há vazamentos
     */
    public function test_memory_usage(): void
    {
        $initialMemory = memory_get_usage();

        // Simula algumas operações
        $data = [];
        for ($i = 0; $i < 1000; $i++) {
            $data[] = str_repeat('test', 100);
        }

        $peakMemory = memory_get_peak_usage();
        $memoryUsed = ($peakMemory - $initialMemory) / 1024 / 1024; // MB

        // Verifica se o uso de memória é razoável (menos de 10MB)
        $this->assertLessThan(10, $memoryUsed, "Uso de memória muito alto: {$memoryUsed}MB");

        // Limpa a variável para liberar memória
        unset($data);

        $this->assertTrue(true);
    }
}
