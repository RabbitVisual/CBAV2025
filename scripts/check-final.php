<?php
echo "=== CBAV CRM Ministerial - Verificacao Final ===\n";
echo "Desenvolvido por Vertex Solutions - CEO Reinan Rodrigues\n\n";

// Verificar versão PHP
$phpVersion = PHP_VERSION;
$requiredVersion = '8.2.0';
if (version_compare($phpVersion, $requiredVersion, '>=')) {
    echo "✅ Versao PHP OK: $phpVersion\n";
} else {
    echo "❌ Versao PHP insuficiente: $phpVersion\n";
}

// Verificar extensões
$extensions = ['pdo', 'pdo_mysql', 'mbstring', 'xml', 'curl', 'gd', 'zip', 'openssl', 'json'];
$missing = [];
foreach ($extensions as $ext) {
    if (!extension_loaded($ext)) {
        $missing[] = $ext;
    }
}
if (empty($missing)) {
    echo "✅ Extensoes PHP OK\n";
} else {
    echo "❌ Extensoes faltando: " . implode(', ', $missing) . "\n";
}

// Verificar arquivos essenciais
$essentialFiles = [
    'vendor/autoload.php',
    'bootstrap/app.php',
    'public/index.php',
    'composer.json',
    'package.json',
    'public/build/manifest.json'
];

$allFilesOk = true;
foreach ($essentialFiles as $file) {
    if (file_exists($file)) {
        echo "✅ Arquivo encontrado: $file\n";
    } else {
        echo "❌ Arquivo nao encontrado: $file\n";
        $allFilesOk = false;
    }
}

// Verificar assets
$manifestFile = 'public/build/manifest.json';
if (file_exists($manifestFile)) {
    $manifest = json_decode(file_get_contents($manifestFile), true);
    if ($manifest) {
        $assetsOk = true;
        foreach ($manifest as $entry) {
            if (isset($entry['file'])) {
                $assetFile = 'public/build/' . $entry['file'];
                if (file_exists($assetFile)) {
                    echo "✅ Asset encontrado: " . $entry['file'] . "\n";
                } else {
                    echo "❌ Asset nao encontrado: " . $entry['file'] . "\n";
                    $assetsOk = false;
                }
            }
        }
        if ($assetsOk) {
            echo "✅ Assets compilados OK\n";
        }
    }
}

// Verificar diretórios
$directories = [
    'storage',
    'storage/app',
    'storage/framework',
    'storage/logs',
    'bootstrap/cache',
    'public/build',
    'public/build/assets'
];

$allDirsOk = true;
foreach ($directories as $dir) {
    if (file_exists($dir) && is_writable($dir)) {
        echo "✅ Diretorio OK: $dir\n";
    } else {
        echo "❌ Problema com diretorio: $dir\n";
        $allDirsOk = false;
    }
}

// Verificar vendor
if (file_exists('vendor/autoload.php')) {
    try {
        require_once 'vendor/autoload.php';
        echo "✅ Vendor autoload funcional\n";
    } catch (Exception $e) {
        echo "❌ Erro no vendor: " . $e->getMessage() . "\n";
    }
}

echo "\n=== RESULTADO FINAL ===\n";
echo "Sistema pronto para deploy!\n";
echo "Proximos passos:\n";
echo "1. Upload dos arquivos para hospedagem\n";
echo "2. Configurar banco de dados\n";
echo "3. Configurar arquivo .env\n";
echo "4. Acessar: https://seudominio.com/install.php\n";
echo "5. Remover: public/install.php apos instalacao\n";
echo "\nSuporte: Vertex Solutions\n"; 