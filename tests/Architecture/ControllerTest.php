<?php

namespace Tests\Architecture;

use Tests\TestCase;

/**
 * Este teste arquitetural garante que as convenções de nomenclatura
 * para controladores sejam seguidas.
 */
class ControllerTest extends TestCase
{
    public function test_admin_controllers_have_correct_suffix(): void
    {
        $adminControllersPath = app_path('Http/Controllers/Admin');

        if (!is_dir($adminControllersPath)) {
            $this->markTestSkipped('Diretório Admin não encontrado');
            return;
        }

        $files = glob($adminControllersPath . '/*.php');

        foreach ($files as $file) {
            $className = basename($file, '.php');
            $this->assertStringEndsWith('Controller', $className,
                "Controller {$className} deve ter sufixo 'Controller'");
        }
    }
}
