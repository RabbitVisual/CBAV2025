<?php
echo "<h2>🔍 Diagnóstico CBAV CRM</h2>";
echo "<hr>";

// 1. Verificar se o arquivo .env existe
echo "<h3>1. Verificação do arquivo .env</h3>";
$envPath = __DIR__ . '/../.env';
if (file_exists($envPath)) {
    echo "✅ Arquivo .env existe<br>";
    echo "📁 Caminho: " . $envPath . "<br>";
    echo "📏 Tamanho: " . filesize($envPath) . " bytes<br>";
} else {
    echo "❌ Arquivo .env NÃO existe<br>";
    echo "📁 Caminho esperado: " . $envPath . "<br>";
}

// 2. Verificar se o vendor/autoload.php existe
echo "<h3>2. Verificação do vendor/autoload.php</h3>";
$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoloadPath)) {
    echo "✅ vendor/autoload.php existe<br>";
} else {
    echo "❌ vendor/autoload.php NÃO existe<br>";
}

// 3. Verificar se o bootstrap/app.php existe
echo "<h3>3. Verificação do bootstrap/app.php</h3>";
$appPath = __DIR__ . '/../bootstrap/app.php';
if (file_exists($appPath)) {
    echo "✅ bootstrap/app.php existe<br>";
} else {
    echo "❌ bootstrap/app.php NÃO existe<br>";
}

// 4. Verificar permissões de pastas
echo "<h3>4. Verificação de permissões</h3>";
$storagePath = __DIR__ . '/../storage';
$bootstrapCachePath = __DIR__ . '/../bootstrap/cache';

if (is_writable($storagePath)) {
    echo "✅ Pasta storage é gravável<br>";
} else {
    echo "❌ Pasta storage NÃO é gravável<br>";
}

if (is_writable($bootstrapCachePath)) {
    echo "✅ Pasta bootstrap/cache é gravável<br>";
} else {
    echo "❌ Pasta bootstrap/cache NÃO é gravável<br>";
}

// 5. Verificar se o composer.json existe
echo "<h3>5. Verificação do composer.json</h3>";
$composerPath = __DIR__ . '/../composer.json';
if (file_exists($composerPath)) {
    echo "✅ composer.json existe<br>";
} else {
    echo "❌ composer.json NÃO existe<br>";
}

// 6. Informações do servidor
echo "<h3>6. Informações do servidor</h3>";
echo "🌐 Servidor: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'N/A') . "<br>";
echo "📁 Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "<br>";
echo "🔧 PHP Version: " . phpversion() . "<br>";
echo "📂 Script Path: " . __FILE__ . "<br>";

// 7. Testar carregamento do autoloader
echo "<h3>7. Teste do autoloader</h3>";
if (file_exists($autoloadPath)) {
    try {
        require_once $autoloadPath;
        echo "✅ Autoloader carregado com sucesso<br>";
    } catch (Exception $e) {
        echo "❌ Erro ao carregar autoloader: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ Autoloader não encontrado<br>";
}

// 8. Verificar se há arquivo de log de erro
echo "<h3>8. Verificação de logs</h3>";
$logPath = __DIR__ . '/../storage/logs/laravel.log';
if (file_exists($logPath)) {
    echo "✅ Arquivo de log existe<br>";
    echo "📏 Tamanho: " . filesize($logPath) . " bytes<br>";
    
    // Mostrar últimas linhas do log
    $lines = file($logPath);
    $lastLines = array_slice($lines, -10);
    echo "<h4>Últimas 10 linhas do log:</h4>";
    echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ddd;'>";
    foreach ($lastLines as $line) {
        echo htmlspecialchars($line);
    }
    echo "</pre>";
} else {
    echo "❌ Arquivo de log não existe<br>";
}

echo "<hr>";
echo "<p><strong>🔧 Próximos passos:</strong></p>";
echo "<ul>";
echo "<li>Se .env não existe: copie env.example para .env</li>";
echo "<li>Se vendor não existe: execute composer install</li>";
echo "<li>Se permissões estão erradas: ajuste as permissões</li>";
echo "<li>Se há erros no log: verifique as configurações</li>";
echo "</ul>";
?> 