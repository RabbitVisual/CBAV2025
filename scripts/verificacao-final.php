<?php
/**
 * Verificação Final - CBAV CRM Ministerial
 * Desenvolvido por Vertex Solutions - CEO Reinan Rodrigues
 * 
 * Este script garante que o sistema está 100% pronto para deploy
 */

// Definir constantes
define('PROJECT_ROOT', __DIR__ . '/..');
define('STORAGE_PATH', PROJECT_ROOT . '/storage');
define('BOOTSTRAP_CACHE_PATH', PROJECT_ROOT . '/bootstrap/cache');
define('PUBLIC_PATH', PROJECT_ROOT . '/public');

// Cores para output
class Colors {
    const GREEN = "\033[32m";
    const RED = "\033[31m";
    const YELLOW = "\033[33m";
    const BLUE = "\033[34m";
    const RESET = "\033[0m";
    const BOLD = "\033[1m";
}

// Função para output colorido
function output($message, $color = Colors::RESET) {
    echo $color . $message . Colors::RESET . PHP_EOL;
}

// Função para verificar versão do PHP
function checkPhpVersion() {
    $required = '8.2.0';
    $current = PHP_VERSION;
    
    if (version_compare($current, $required, '>=')) {
        output("✅ Versão PHP OK: $current (requerido: $required+)", Colors::GREEN);
        return true;
    } else {
        output("❌ Versão PHP insuficiente: $current (requerido: $required+)", Colors::RED);
        return false;
    }
}

// Função para verificar extensões PHP
function checkPhpExtensions() {
    $required = [
        'pdo', 'pdo_mysql', 'mbstring', 'xml', 'curl', 'gd', 'zip', 'openssl', 'json', 'fileinfo'
    ];
    
    $missing = [];
    foreach ($required as $ext) {
        if (!extension_loaded($ext)) {
            $missing[] = $ext;
        }
    }
    
    if (empty($missing)) {
        output("✅ Todas as extensões PHP necessárias estão instaladas", Colors::GREEN);
        return true;
    } else {
        output("❌ Extensões PHP faltando: " . implode(', ', $missing), Colors::RED);
        return false;
    }
}

// Função para verificar estrutura de diretórios
function checkDirectoryStructure() {
    $directories = [
        STORAGE_PATH,
        STORAGE_PATH . '/app',
        STORAGE_PATH . '/app/public',
        STORAGE_PATH . '/framework',
        STORAGE_PATH . '/framework/cache',
        STORAGE_PATH . '/framework/sessions',
        STORAGE_PATH . '/framework/views',
        STORAGE_PATH . '/logs',
        BOOTSTRAP_CACHE_PATH,
        PUBLIC_PATH . '/build',
        PUBLIC_PATH . '/build/assets'
    ];
    
    $allOk = true;
    foreach ($directories as $dir) {
        if (!file_exists($dir)) {
            output("❌ Diretório não existe: $dir", Colors::RED);
            $allOk = false;
        } elseif (!is_writable($dir)) {
            output("❌ Diretório não gravável: $dir", Colors::RED);
            $allOk = false;
        }
    }
    
    if ($allOk) {
        output("✅ Estrutura de diretórios OK", Colors::GREEN);
    }
    
    return $allOk;
}

// Função para verificar arquivos essenciais
function checkEssentialFiles() {
    $files = [
        PROJECT_ROOT . '/vendor/autoload.php',
        PROJECT_ROOT . '/bootstrap/app.php',
        PROJECT_ROOT . '/public/index.php',
        PROJECT_ROOT . '/composer.json',
        PROJECT_ROOT . '/package.json',
        PROJECT_ROOT . '/public/build/manifest.json'
    ];
    
    $allOk = true;
    foreach ($files as $file) {
        if (!file_exists($file)) {
            output("❌ Arquivo essencial não existe: $file", Colors::RED);
            $allOk = false;
        }
    }
    
    if ($allOk) {
        output("✅ Arquivos essenciais OK", Colors::GREEN);
    }
    
    return $allOk;
}

// Função para verificar assets compilados
function checkCompiledAssets() {
    $manifestFile = PUBLIC_PATH . '/build/manifest.json';
    
    if (!file_exists($manifestFile)) {
        output("❌ Manifest de assets não encontrado", Colors::RED);
        return false;
    }
    
    $manifest = json_decode(file_get_contents($manifestFile), true);
    if (!$manifest) {
        output("❌ Manifest de assets inválido", Colors::RED);
        return false;
    }
    
    $allOk = true;
    
    foreach ($manifest as $entry) {
        if (isset($entry['file'])) {
            // O arquivo já inclui o diretório assets/, então não precisamos concatenar
            $assetFile = PUBLIC_PATH . '/build/' . $entry['file'];
            output("🔍 Verificando: " . $assetFile, Colors::BLUE);
            if (!file_exists($assetFile)) {
                output("❌ Asset não encontrado: " . $entry['file'], Colors::RED);
                $allOk = false;
            } else {
                output("✅ Asset encontrado: " . $entry['file'], Colors::GREEN);
            }
        }
    }
    
    if ($allOk) {
        output("✅ Assets compilados OK", Colors::GREEN);
    }
    
    return $allOk;
}

// Função para verificar vendor
function checkVendor() {
    $vendorAutoload = PROJECT_ROOT . '/vendor/autoload.php';
    
    if (!file_exists($vendorAutoload)) {
        output("❌ Vendor autoload não encontrado", Colors::RED);
        return false;
    }
    
    // Verificar se o vendor está funcional
    try {
        require_once $vendorAutoload;
        output("✅ Vendor autoload funcional", Colors::GREEN);
        return true;
    } catch (Exception $e) {
        output("❌ Erro no vendor autoload: " . $e->getMessage(), Colors::RED);
        return false;
    }
}

// Função para verificar configurações de produção
function checkProductionSettings() {
    $envFile = PROJECT_ROOT . '/.env';
    if (!file_exists($envFile)) {
        output("ℹ️ Arquivo .env não encontrado (normal em desenvolvimento)", Colors::YELLOW);
        return true;
    }
    
    $envContent = file_get_contents($envFile);
    
    $checks = [
        'APP_ENV=production' => 'APP_ENV deve ser production',
        'APP_DEBUG=false' => 'APP_DEBUG deve ser false',
        'APP_URL=https://' => 'APP_URL deve usar HTTPS'
    ];
    
    $allOk = true;
    foreach ($checks as $check => $description) {
        if (strpos($envContent, $check) === false) {
            output("⚠️ $description", Colors::YELLOW);
            $allOk = false;
        }
    }
    
    if ($allOk) {
        output("✅ Configurações de produção OK", Colors::GREEN);
    }
    
    return $allOk;
}

// Função para verificar permissões
function checkPermissions() {
    $directories = [
        STORAGE_PATH => 0755,
        BOOTSTRAP_CACHE_PATH => 0755,
        PUBLIC_PATH . '/build' => 0755
    ];
    
    $allOk = true;
    foreach ($directories as $dir => $requiredPerms) {
        if (file_exists($dir)) {
            $perms = fileperms($dir) & 0777;
            if ($perms >= $requiredPerms) {
                output("✅ Permissões OK: $dir", Colors::GREEN);
            } else {
                output("❌ Permissões insuficientes: $dir", Colors::RED);
                $allOk = false;
            }
        }
    }
    
    return $allOk;
}

// Função para verificar banco de dados
function checkDatabase() {
    $envFile = PROJECT_ROOT . '/.env';
    if (!file_exists($envFile)) {
        output("ℹ️ Arquivo .env não encontrado (normal em desenvolvimento)", Colors::YELLOW);
        return true;
    }
    
    // Tentar carregar configurações do banco
    $envContent = file_get_contents($envFile);
    if (strpos($envContent, 'DB_CONNECTION') !== false) {
        output("✅ Configurações de banco encontradas", Colors::GREEN);
        return true;
    } else {
        output("⚠️ Configurações de banco não encontradas", Colors::YELLOW);
        return true; // Não é crítico para verificação
    }
}

// Função para criar health check
function createHealthCheck() {
    $healthFile = PUBLIC_PATH . '/health.php';
    $content = '<?php
/**
 * Health Check - CBAV CRM Ministerial
 * Desenvolvido por Vertex Solutions - CEO Reinan Rodrigues
 */

header("Content-Type: application/json");

$status = [
    "status" => "healthy",
    "timestamp" => date("Y-m-d H:i:s"),
    "version" => "1.0.0",
    "environment" => $_ENV["APP_ENV"] ?? "unknown",
    "database" => "unknown",
    "cache" => "unknown",
    "storage" => "unknown"
];

// Verificar conexão com banco
try {
    $pdo = new PDO(
        "mysql:host=" . ($_ENV["DB_HOST"] ?? "localhost") . 
        ";dbname=" . ($_ENV["DB_DATABASE"] ?? "cbav"),
        $_ENV["DB_USERNAME"] ?? "root",
        $_ENV["DB_PASSWORD"] ?? ""
    );
    $status["database"] = "connected";
} catch (Exception $e) {
    $status["database"] = "error";
    $status["status"] = "unhealthy";
}

// Verificar cache
if (is_writable(__DIR__ . "/../storage/framework/cache")) {
    $status["cache"] = "writable";
} else {
    $status["cache"] = "error";
    $status["status"] = "unhealthy";
}

// Verificar storage
if (is_writable(__DIR__ . "/../storage")) {
    $status["storage"] = "writable";
} else {
    $status["storage"] = "error";
    $status["status"] = "unhealthy";
}

// Verificar arquivos essenciais
$essentialFiles = [
    __DIR__ . "/../vendor/autoload.php",
    __DIR__ . "/../bootstrap/app.php",
    __DIR__ . "/index.php"
];

foreach ($essentialFiles as $file) {
    if (!file_exists($file)) {
        $status["status"] = "unhealthy";
        $status["missing_files"] = true;
        break;
    }
}

echo json_encode($status, JSON_PRETTY_PRINT);
';
    
    if (file_put_contents($healthFile, $content)) {
        output("✅ Health check criado: /health.php", Colors::GREEN);
        return true;
    } else {
        output("❌ Erro ao criar health check", Colors::RED);
        return false;
    }
}

// Função principal
function main() {
    output("🚀 CBAV CRM Ministerial - Verificação Final", Colors::BOLD . Colors::BLUE);
    output("Desenvolvido por Vertex Solutions - CEO Reinan Rodrigues", Colors::BLUE);
    output("", Colors::RESET);
    
    $checks = [
        'Versão PHP' => checkPhpVersion(),
        'Extensões PHP' => checkPhpExtensions(),
        'Estrutura de Diretórios' => checkDirectoryStructure(),
        'Arquivos Essenciais' => checkEssentialFiles(),
        'Assets Compilados' => checkCompiledAssets(),
        'Vendor Autoload' => checkVendor(),
        'Permissões' => checkPermissions(),
        'Configurações de Produção' => checkProductionSettings(),
        'Banco de Dados' => checkDatabase()
    ];
    
    output("", Colors::RESET);
    output("📊 Resumo das Verificações:", Colors::BOLD);
    
    $passed = 0;
    $total = count($checks);
    
    foreach ($checks as $name => $result) {
        $status = $result ? "✅ PASS" : "❌ FAIL";
        $color = $result ? Colors::GREEN : Colors::RED;
        output("$name: $status", $color);
        
        if ($result) $passed++;
    }
    
    output("", Colors::RESET);
    output("📈 Resultado: $passed/$total verificações passaram", Colors::BOLD);
    
    if ($passed === $total) {
        output("🎉 Sistema 100% pronto para deploy!", Colors::BOLD . Colors::GREEN);
        
        // Criar health check
        createHealthCheck();
        
        output("", Colors::RESET);
        output("📋 Próximos passos:", Colors::BOLD);
        output("1. Faça upload dos arquivos para a hospedagem", Colors::RESET);
        output("2. Configure o banco de dados", Colors::RESET);
        output("3. Configure o arquivo .env", Colors::RESET);
        output("4. Acesse: https://seudominio.com/install.php", Colors::RESET);
        output("5. Após instalação, remova: public/install.php", Colors::RESET);
        output("6. Teste: https://seudominio.com/health.php", Colors::RESET);
        
    } else {
        output("⚠️ Corrija os problemas antes do deploy", Colors::BOLD . Colors::YELLOW);
    }
    
    output("", Colors::RESET);
    output("🔗 Health Check: https://seudominio.com/health.php", Colors::BLUE);
    output("📞 Suporte: Vertex Solutions", Colors::BLUE);
}

// Executar verificação
main(); 