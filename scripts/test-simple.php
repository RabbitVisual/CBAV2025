<?php
/**
 * Script de Teste Simples - CBAV CRM Ministerial
 */

echo "🚀 Teste Simples - CBAV CRM Ministerial\n";
echo "Desenvolvido por Vertex Solutions - CEO Reinan Rodrigues\n\n";

// Verificar assets
$manifestFile = __DIR__ . '/../public/build/manifest.json';
echo "📁 Verificando manifest: $manifestFile\n";

if (file_exists($manifestFile)) {
    echo "✅ Manifest encontrado\n";
    
    $manifest = json_decode(file_get_contents($manifestFile), true);
    if ($manifest) {
        echo "✅ Manifest válido\n";
        
        $assetsDir = __DIR__ . '/../public/build/assets';
        echo "📁 Verificando assets em: $assetsDir\n";
        
        foreach ($manifest as $entry) {
            if (isset($entry['file'])) {
                $assetFile = $assetsDir . '/' . $entry['file'];
                echo "🔍 Verificando: " . $entry['file'] . "\n";
                echo "   Caminho completo: $assetFile\n";
                
                if (file_exists($assetFile)) {
                    echo "   ✅ Asset encontrado\n";
                } else {
                    echo "   ❌ Asset não encontrado\n";
                }
            }
        }
    } else {
        echo "❌ Manifest inválido\n";
    }
} else {
    echo "❌ Manifest não encontrado\n";
}

echo "\n🎉 Teste concluído!\n"; 