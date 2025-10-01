<?php
/**
 * Script de Otimização para Produção - CBAV CRM Ministerial
 * Desenvolvido por Vertex Solutions - CEO Reinan Rodrigues
 * 
 * Este script otimiza o sistema para funcionamento perfeito na hospedagem
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

// Função para executar comandos Artisan
function runArtisanCommand($command) {
    $output = [];
    $returnCode = 0;
    
    exec("cd " . PROJECT_ROOT . " && php artisan $command 2>&1", $output, $returnCode);
    
    return [
        'success' => $returnCode === 0,
        'output' => $output,
        'return_code' => $returnCode
    ];
}

// Função para otimizar autoloader do Composer
function optimizeComposerAutoloader() {
    output("📦 Otimizando autoloader do Composer...", Colors::BLUE);
    
    $command = "cd " . PROJECT_ROOT . " && composer install --no-dev --optimize-autoloader --no-interaction";
    $result = exec($command, $output, $returnCode);
    
    if ($returnCode === 0) {
        output("✅ Autoloader otimizado", Colors::GREEN);
        return true;
    } else {
        output("❌ Erro ao otimizar autoloader", Colors::RED);
        return false;
    }
}

// Função para compilar assets
function compileAssets() {
    output("🎨 Compilando assets...", Colors::BLUE);
    
    $command = "cd " . PROJECT_ROOT . " && npm run build";
    $result = exec($command, $output, $returnCode);
    
    if ($returnCode === 0) {
        output("✅ Assets compilados", Colors::GREEN);
        return true;
    } else {
        output("❌ Erro ao compilar assets", Colors::RED);
        return false;
    }
}

// Função para limpar cache
function clearCache() {
    output("🧹 Limpando cache...", Colors::BLUE);
    
    $commands = [
        'cache:clear',
        'config:clear',
        'route:clear',
        'view:clear'
    ];
    
    foreach ($commands as $command) {
        $result = runArtisanCommand($command);
        if (!$result['success']) {
            output("❌ Erro ao executar: $command", Colors::RED);
            return false;
        }
    }
    
    output("✅ Cache limpo", Colors::GREEN);
    return true;
}

// Função para otimizar cache
function optimizeCache() {
    output("⚡ Otimizando cache...", Colors::BLUE);
    
    $commands = [
        'config:cache',
        'route:cache',
        'view:cache'
    ];
    
    foreach ($commands as $command) {
        $result = runArtisanCommand($command);
        if (!$result['success']) {
            output("❌ Erro ao executar: $command", Colors::RED);
            return false;
        }
    }
    
    output("✅ Cache otimizado", Colors::GREEN);
    return true;
}

// Função para configurar permissões
function setPermissions() {
    output("🔐 Configurando permissões...", Colors::BLUE);
    
    $directories = [
        STORAGE_PATH => 0755,
        STORAGE_PATH . '/app' => 0755,
        STORAGE_PATH . '/app/public' => 0755,
        STORAGE_PATH . '/framework' => 0755,
        STORAGE_PATH . '/framework/cache' => 0755,
        STORAGE_PATH . '/framework/sessions' => 0755,
        STORAGE_PATH . '/framework/views' => 0755,
        STORAGE_PATH . '/logs' => 0755,
        BOOTSTRAP_CACHE_PATH => 0755,
        PUBLIC_PATH . '/build' => 0755
    ];
    
    foreach ($directories as $dir => $perms) {
        if (file_exists($dir)) {
            if (chmod($dir, $perms)) {
                output("✅ Permissões configuradas: $dir", Colors::GREEN);
            } else {
                output("❌ Erro ao configurar permissões: $dir", Colors::RED);
            }
        }
    }
    
    return true;
}

// Função para criar diretórios necessários
function createDirectories() {
    output("📁 Criando diretórios necessários...", Colors::BLUE);
    
    $directories = [
        STORAGE_PATH . '/app/public/campanhas',
        STORAGE_PATH . '/app/public/membros',
        STORAGE_PATH . '/app/public/config',
        STORAGE_PATH . '/app/public/profiles',
        STORAGE_PATH . '/backups',
        STORAGE_PATH . '/framework/cache/data',
        STORAGE_PATH . '/framework/sessions',
        STORAGE_PATH . '/framework/views',
        STORAGE_PATH . '/logs'
    ];
    
    foreach ($directories as $dir) {
        if (!file_exists($dir)) {
            if (mkdir($dir, 0755, true)) {
                output("✅ Diretório criado: $dir", Colors::GREEN);
            } else {
                output("❌ Erro ao criar diretório: $dir", Colors::RED);
            }
        }
    }
    
    return true;
}

// Função para configurar arquivo .env para produção
function configureProductionEnv() {
    output("⚙️ Configurando .env para produção...", Colors::BLUE);
    
    $envFile = PROJECT_ROOT . '/.env';
    if (!file_exists($envFile)) {
        output("❌ Arquivo .env não encontrado", Colors::RED);
        return false;
    }
    
    $envContent = file_get_contents($envFile);
    
    // Configurações de produção
    $replacements = [
        'APP_ENV=local' => 'APP_ENV=production',
        'APP_DEBUG=true' => 'APP_DEBUG=false',
        'LOG_LEVEL=debug' => 'LOG_LEVEL=error',
        'CACHE_DRIVER=file' => 'CACHE_DRIVER=file',
        'SESSION_DRIVER=database' => 'SESSION_DRIVER=database',
        'QUEUE_CONNECTION=sync' => 'QUEUE_CONNECTION=database'
    ];
    
    foreach ($replacements as $search => $replace) {
        $envContent = str_replace($search, $replace, $envContent);
    }
    
    if (file_put_contents($envFile, $envContent)) {
        output("✅ .env configurado para produção", Colors::GREEN);
        return true;
    } else {
        output("❌ Erro ao configurar .env", Colors::RED);
        return false;
    }
}

// Função para criar arquivo de configuração de produção
function createProductionConfig() {
    output("🔧 Criando configuração de produção...", Colors::BLUE);
    
    $configFile = PROJECT_ROOT . '/config/production.php';
    $content = '<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configurações de Produção - CBAV CRM Ministerial
    |--------------------------------------------------------------------------
    |
    | Configurações otimizadas para ambiente de produção
    | Desenvolvido por Vertex Solutions - CEO Reinan Rodrigues
    |
    */

    // Configurações de aplicação
    "app" => [
        "env" => "production",
        "debug" => false,
        "url" => env("APP_URL", "https://seudominio.com"),
        "timezone" => "America/Sao_Paulo",
        "locale" => "pt_BR",
        "fallback_locale" => "pt_BR",
        "faker_locale" => "pt_BR",
    ],

    // Configurações de log
    "logging" => [
        "default" => "stack",
        "channels" => [
            "stack" => [
                "driver" => "stack",
                "channels" => ["single"],
                "ignore_exceptions" => false,
            ],
            "single" => [
                "driver" => "single",
                "path" => base_path("storage/logs/laravel.log"),
                "level" => "error",
            ],
        ],
        "level" => "error",
        "deprecations" => null,
    ],

    // Configurações de cache
    "cache" => [
        "default" => "file",
        "stores" => [
            "file" => [
                "driver" => "file",
                "path" => base_path("storage/framework/cache/data"),
            ],
        ],
    ],

    // Configurações de sessão
    "session" => [
        "driver" => "database",
        "lifetime" => 120,
        "expire_on_close" => false,
        "encrypt" => false,
        "secure" => true,
        "http_only" => true,
        "same_site" => "lax",
    ],

    // Configurações de filas
    "queue" => [
        "default" => "database",
        "connections" => [
            "database" => [
                "driver" => "database",
                "table" => "jobs",
                "queue" => "default",
                "retry_after" => 90,
                "after_commit" => false,
            ],
        ],
        "failed" => [
            "driver" => "database-uuids",
            "database" => env("DB_CONNECTION", "mysql"),
            "table" => "failed_jobs",
        ],
    ],

    // Configurações de segurança
    "security" => [
        "bcrypt_rounds" => 12,
        "session_secure" => true,
        "session_http_only" => true,
        "session_same_site" => "lax",
        "csrf_token_lifetime" => 120,
    ],

    // Configurações de performance
    "performance" => [
        "cache_views" => true,
        "cache_config" => true,
        "cache_routes" => true,
        "optimize_autoloader" => true,
        "compress_output" => true,
    ],

    // Configurações de backup
    "backup" => [
        "enabled" => true,
        "disk" => "local",
        "retention_days" => 30,
        "compress" => true,
        "notify_on_failure" => true,
    ],

    // Configurações de monitoramento
    "monitoring" => [
        "enabled" => true,
        "log_errors" => true,
        "log_queries" => false,
        "log_requests" => false,
        "performance_monitoring" => true,
    ],

    // Configurações de manutenção
    "maintenance" => [
        "enabled" => false,
        "allowed_ips" => [],
        "secret" => env("APP_MAINTENANCE_SECRET"),
    ],
];
';
    
    if (file_put_contents($configFile, $content)) {
        output("✅ Configuração de produção criada", Colors::GREEN);
        return true;
    } else {
        output("❌ Erro ao criar configuração de produção", Colors::RED);
        return false;
    }
}

// Função para criar arquivo .htaccess otimizado
function createOptimizedHtaccess() {
    output("🔒 Criando .htaccess otimizado...", Colors::BLUE);
    
    $htaccessFile = PUBLIC_PATH . '/.htaccess';
    $content = '# =============================================================================
# CBAV CRM MINISTERIAL - .HTACCESS OTIMIZADO PARA PRODUÇÃO
# Desenvolvido por Vertex Solutions - CEO Reinan Rodrigues
# =============================================================================

# Habilitar rewrite engine
RewriteEngine On

# Forçar HTTPS (descomente se necessário)
# RewriteCond %{HTTPS} off
# RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Redirecionar www para non-www (opcional)
# RewriteCond %{HTTP_HOST} ^www\.(.*)$ [NC]
# RewriteRule ^(.*)$ https://%1/$1 [R=301,L]

# Proteger arquivos sensíveis
<Files ".env">
    Order allow,deny
    Deny from all
</Files>

<Files "composer.json">
    Order allow,deny
    Deny from all
</Files>

<Files "composer.lock">
    Order allow,deny
    Deny from all
</Files>

<Files "package.json">
    Order allow,deny
    Deny from all
</Files>

<Files "package-lock.json">
    Order allow,deny
    Deny from all
</Files>

# Bloquear acesso a diretórios sensíveis
RedirectMatch 403 ^/app/?$
RedirectMatch 403 ^/bootstrap/?$
RedirectMatch 403 ^/config/?$
RedirectMatch 403 ^/database/?$
RedirectMatch 403 ^/resources/?$
RedirectMatch 403 ^/routes/?$
RedirectMatch 403 ^/storage/?$
RedirectMatch 403 ^/tests/?$
RedirectMatch 403 ^/vendor/?$

# Headers de segurança
<IfModule mod_headers.c>
    # Proteger contra clickjacking
    Header always append X-Frame-Options SAMEORIGIN
    
    # Proteger contra MIME type sniffing
    Header always set X-Content-Type-Options nosniff
    
    # Proteger contra XSS
    Header always set X-XSS-Protection "1; mode=block"
    
    # Referrer Policy
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
    
    # Content Security Policy
    Header always set Content-Security-Policy "default-src \'self\' \'unsafe-inline\' \'unsafe-eval\'; script-src \'self\' \'unsafe-inline\' \'unsafe-eval\' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com; style-src \'self\' \'unsafe-inline\' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com; font-src \'self\' https://cdnjs.cloudflare.com; img-src \'self\' data: https:; connect-src \'self\'; frame-ancestors \'self\';"
    
    # Permissions Policy
    Header always set Permissions-Policy "geolocation=(), microphone=(), camera=()"
    
    # Cache Control para arquivos estáticos
    <FilesMatch "\.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$">
        Header always set Cache-Control "public, max-age=31536000"
    </FilesMatch>
    
    # Cache Control para arquivos sensíveis
    <FilesMatch "\.(env|config|ini|log|sql|json|xml|key|pem|crt|bak|backup|old|orig|save|swp|tmp|temp|cache|php)$">
        Header always set Cache-Control "no-store, no-cache, must-revalidate, max-age=0"
        Header always set Pragma "no-cache"
        Header always set Expires "Thu, 1 Jan 1970 00:00:00 GMT"
    </FilesMatch>
</IfModule>

# Compressão GZIP
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
    AddOutputFilterByType DEFLATE application/json
</IfModule>

# Cache de navegador
<IfModule mod_expires.c>
    ExpiresActive on
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/ico "access plus 1 year"
    ExpiresByType image/icon "access plus 1 year"
    ExpiresByType text/plain "access plus 1 month"
    ExpiresByType application/pdf "access plus 1 month"
    ExpiresByType application/x-shockwave-flash "access plus 1 month"
    ExpiresByType image/x-icon "access plus 1 year"
    ExpiresDefault "access plus 2 days"
</IfModule>

# Regras de rewrite para Laravel
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [L]

# Personalizar mensagens de erro
ErrorDocument 403 "Acesso negado. Este recurso não está disponível."
ErrorDocument 404 "Página não encontrada. Verifique a URL e tente novamente."
ErrorDocument 500 "Erro interno do servidor. Tente novamente mais tarde."
';
    
    if (file_put_contents($htaccessFile, $content)) {
        output("✅ .htaccess otimizado criado", Colors::GREEN);
        return true;
    } else {
        output("❌ Erro ao criar .htaccess", Colors::RED);
        return false;
    }
}

// Função para criar arquivo de verificação de saúde
function createHealthCheck() {
    output("🏥 Criando health check...", Colors::BLUE);
    
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
        output("✅ Health check criado", Colors::GREEN);
        return true;
    } else {
        output("❌ Erro ao criar health check", Colors::RED);
        return false;
    }
}

// Função principal
function main() {
    output("🚀 CBAV CRM Ministerial - Otimização para Produção", Colors::BOLD . Colors::BLUE);
    output("Desenvolvido por Vertex Solutions - CEO Reinan Rodrigues", Colors::BLUE);
    output("", Colors::RESET);
    
    $steps = [
        'Criar diretórios necessários' => createDirectories(),
        'Configurar permissões' => setPermissions(),
        'Otimizar autoloader do Composer' => optimizeComposerAutoloader(),
        'Compilar assets' => compileAssets(),
        'Limpar cache' => clearCache(),
        'Otimizar cache' => optimizeCache(),
        'Configurar .env para produção' => configureProductionEnv(),
        'Criar configuração de produção' => createProductionConfig(),
        'Criar .htaccess otimizado' => createOptimizedHtaccess(),
        'Criar health check' => createHealthCheck()
    ];
    
    output("", Colors::RESET);
    output("📊 Resumo da Otimização:", Colors::BOLD);
    
    $passed = 0;
    $total = count($steps);
    
    foreach ($steps as $name => $result) {
        $status = $result ? "✅ OK" : "❌ ERRO";
        $color = $result ? Colors::GREEN : Colors::RED;
        output("$name: $status", $color);
        
        if ($result) $passed++;
    }
    
    output("", Colors::RESET);
    output("📈 Resultado: $passed/$total etapas concluídas", Colors::BOLD);
    
    if ($passed === $total) {
        output("🎉 Sistema otimizado para produção!", Colors::BOLD . Colors::GREEN);
        
        output("", Colors::RESET);
        output("📋 Próximos passos:", Colors::BOLD);
        output("1. Faça upload dos arquivos para a hospedagem", Colors::RESET);
        output("2. Configure o banco de dados", Colors::RESET);
        output("3. Configure o arquivo .env", Colors::RESET);
        output("4. Acesse: https://seudominio.com/install.php", Colors::RESET);
        output("5. Após instalação, remova: public/install.php", Colors::RESET);
        output("6. Teste o health check: https://seudominio.com/health.php", Colors::RESET);
        
    } else {
        output("⚠️ Corrija os erros antes do deploy", Colors::BOLD . Colors::YELLOW);
    }
    
    output("", Colors::RESET);
    output("🔗 Health Check: https://seudominio.com/health.php", Colors::BLUE);
    output("📞 Suporte: Vertex Solutions", Colors::BLUE);
}

// Executar otimização
main(); 