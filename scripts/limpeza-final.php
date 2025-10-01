<?php
echo "=== CBAV CRM Ministerial - Limpeza Final ===\n";
echo "Desenvolvido por Vertex Solutions - CEO Reinan Rodrigues\n\n";

// Limpar cache do Laravel
echo "🧹 Limpando cache do Laravel...\n";
$cacheDirs = [
    'storage/framework/cache/data',
    'storage/framework/sessions',
    'storage/framework/views',
    'bootstrap/cache'
];

foreach ($cacheDirs as $dir) {
    if (file_exists($dir)) {
        $files = glob($dir . '/*');
        $deleted = 0;
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
                $deleted++;
            }
        }
        echo "✅ Limpo: $dir ($deleted arquivos removidos)\n";
    }
}

// Limpar logs antigos
echo "📝 Limpando logs antigos...\n";
$logDir = 'storage/logs';
if (file_exists($logDir)) {
    $files = glob($logDir . '/*.log');
    $deleted = 0;
    foreach ($files as $file) {
        if (is_file($file) && filesize($file) > 1024 * 1024) { // Logs maiores que 1MB
            unlink($file);
            $deleted++;
        }
    }
    echo "✅ Logs limpos: $deleted arquivos removidos\n";
}

// Verificar e corrigir permissões
echo "🔐 Verificando permissões...\n";
$directories = [
    'storage' => 0755,
    'storage/app' => 0755,
    'storage/framework' => 0755,
    'storage/logs' => 0755,
    'bootstrap/cache' => 0755,
    'public/build' => 0755
];

foreach ($directories as $dir => $perms) {
    if (file_exists($dir)) {
        if (chmod($dir, $perms)) {
            echo "✅ Permissões OK: $dir\n";
        } else {
            echo "⚠️ Problema com permissões: $dir\n";
        }
    }
}

// Verificar arquivos essenciais
echo "📁 Verificando arquivos essenciais...\n";
$essentialFiles = [
    'vendor/autoload.php',
    'bootstrap/app.php',
    'public/index.php',
    'composer.json',
    'package.json',
    'public/build/manifest.json',
    'public/build/assets/app-BB7ZGgJm.css',
    'public/build/assets/app-C0G0cght.js'
];

$allFilesOk = true;
foreach ($essentialFiles as $file) {
    if (file_exists($file)) {
        echo "✅ Arquivo OK: $file\n";
    } else {
        echo "❌ Arquivo faltando: $file\n";
        $allFilesOk = false;
    }
}

// Verificar vendor
echo "📦 Verificando vendor...\n";
if (file_exists('vendor/autoload.php')) {
    try {
        require_once 'vendor/autoload.php';
        echo "✅ Vendor autoload funcional\n";
    } catch (Exception $e) {
        echo "❌ Erro no vendor: " . $e->getMessage() . "\n";
        $allFilesOk = false;
    }
}

// Criar arquivo de status final
$statusFile = 'storage/system_status.txt';
$status = "CBAV CRM Ministerial - Status Final\n";
$status .= "Data: " . date('Y-m-d H:i:s') . "\n";
$status .= "Versao: 1.0.0\n";
$status .= "Desenvolvido por: Vertex Solutions - CEO Reinan Rodrigues\n\n";
$status .= "Status do Sistema:\n";
$status .= "- PHP Version: " . PHP_VERSION . "\n";
$status .= "- OS: " . PHP_OS . "\n";
$status .= "- Arquivos essenciais: " . ($allFilesOk ? "OK" : "PROBLEMAS") . "\n";
$status .= "- Vendor: " . (file_exists('vendor/autoload.php') ? "OK" : "PROBLEMAS") . "\n";
$status .= "- Assets: " . (file_exists('public/build/manifest.json') ? "OK" : "PROBLEMAS") . "\n";
$status .= "- Cache: Limpo\n";
$status .= "- Permissões: Configuradas\n\n";
$status .= "Sistema pronto para deploy!\n";

file_put_contents($statusFile, $status);
echo "✅ Status do sistema salvo: $statusFile\n";

// Verificar espaço em disco
$freeSpace = disk_free_space('.');
$totalSpace = disk_total_space('.');
$usedSpace = $totalSpace - $freeSpace;

$freeGB = round($freeSpace / 1024 / 1024 / 1024, 2);
$totalGB = round($totalSpace / 1024 / 1024 / 1024, 2);
$usedGB = round($usedSpace / 1024 / 1024 / 1024, 2);

echo "\n💾 Espaço em disco:\n";
echo "   Total: {$totalGB} GB\n";
echo "   Usado: {$usedGB} GB\n";
echo "   Livre: {$freeGB} GB\n";

if ($freeGB > 1) {
    echo "✅ Espaço em disco suficiente\n";
} else {
    echo "⚠️ Pouco espaço livre\n";
}

echo "\n=== LIMPEZA FINAL CONCLUÍDA ===\n";
echo "Sistema limpo e pronto para deploy!\n";
echo "Status: " . ($allFilesOk ? "✅ PRONTO" : "❌ PROBLEMAS") . "\n";
echo "\nPróximos passos:\n";
echo "1. Upload dos arquivos para hospedagem\n";
echo "2. Configurar banco de dados\n";
echo "3. Configurar arquivo .env\n";
echo "4. Acessar: https://seudominio.com/install.php\n";
echo "5. Remover: public/install.php após instalação\n";
echo "\nSuporte: Vertex Solutions\n"; 