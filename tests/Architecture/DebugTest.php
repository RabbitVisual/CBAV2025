<?php

namespace Tests\Architecture;

use Tests\TestCase;

/**
 * Este teste arquitetural garante que funções de depuração não sejam
 * deixadas no código-fonte da aplicação.
 */
class DebugTest extends TestCase
{
    public function test_no_debug_functions_in_app_code(): void
    {
        $appPath = app_path();
        $debugFunctions = ['dd(', 'dump(', 'var_dump('];

        $this->checkForDebugFunctions($appPath, $debugFunctions);
    }

    private function checkForDebugFunctions(string $path, array $functions): void
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $content = file_get_contents($file->getPathname());

                foreach ($functions as $function) {
                    $this->assertStringNotContainsString($function, $content,
                        "Função de debug '{$function}' encontrada em: " . $file->getPathname());
                }
            }
        }
    }
}
