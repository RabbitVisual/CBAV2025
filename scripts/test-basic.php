<?php
echo "Teste Basico - CBAV CRM Ministerial\n";

$manifestFile = __DIR__ . '/../public/build/manifest.json';
echo "Manifest: $manifestFile\n";

if (file_exists($manifestFile)) {
    echo "Manifest encontrado\n";
    
    $manifest = json_decode(file_get_contents($manifestFile), true);
    if ($manifest) {
        echo "Manifest valido\n";
        
        $assetsDir = __DIR__ . '/../public/build/assets';
        echo "Assets dir: $assetsDir\n";
        
        foreach ($manifest as $entry) {
            if (isset($entry['file'])) {
                $assetFile = $assetsDir . '/' . $entry['file'];
                echo "Verificando: " . $entry['file'] . "\n";
                echo "Caminho: $assetFile\n";
                
                if (file_exists($assetFile)) {
                    echo "Asset encontrado\n";
                } else {
                    echo "Asset NAO encontrado\n";
                }
            }
        }
    } else {
        echo "Manifest invalido\n";
    }
} else {
    echo "Manifest nao encontrado\n";
}

echo "Teste concluido!\n"; 