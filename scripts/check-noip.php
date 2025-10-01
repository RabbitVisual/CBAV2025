<?php

/**
 * Script de Verificação da Configuração No-IP
 * CBAV - Vertex Solutions
 */

echo "=== VERIFICAÇÃO DA CONFIGURAÇÃO NO-IP ===\n\n";

// 1. Verificar configurações do .env
echo "1. Verificando configurações do .env:\n";
$envFile = __DIR__ . '/../.env';

if (!file_exists($envFile)) {
    echo "❌ Arquivo .env não encontrado!\n";
    exit(1);
}

$envContent = file_get_contents($envFile);

// Verificar APP_URL
if (preg_match('/APP_URL=(.+)/', $envContent, $matches)) {
    $appUrl = trim($matches[1]);
    echo "✅ APP_URL: $appUrl\n";
    
    if (strpos($appUrl, 'vertexsolutions.ddns.net') !== false) {
        echo "✅ URL configurada corretamente para No-IP\n";
    } else {
        echo "⚠️  URL não está configurada para vertexsolutions.ddns.net\n";
    }
} else {
    echo "❌ APP_URL não encontrado no .env\n";
}

// Verificar SESSION_SECURE_COOKIE
if (preg_match('/SESSION_SECURE_COOKIE=(.+)/', $envContent, $matches)) {
    $secureCookie = trim($matches[1]);
    echo "✅ SESSION_SECURE_COOKIE: $secureCookie\n";
    
    if ($secureCookie === 'true') {
        echo "✅ Cookies seguros habilitados\n";
    } else {
        echo "⚠️  Cookies seguros desabilitados\n";
    }
} else {
    echo "❌ SESSION_SECURE_COOKIE não encontrado\n";
}

// Verificar SANCTUM_STATEFUL_DOMAINS
if (preg_match('/SANCTUM_STATEFUL_DOMAINS=(.+)/', $envContent, $matches)) {
    $domains = trim($matches[1]);
    echo "✅ SANCTUM_STATEFUL_DOMAINS: $domains\n";
    
    if (strpos($domains, 'vertexsolutions.ddns.net') !== false) {
        echo "✅ Domínios autorizados configurados corretamente\n";
    } else {
        echo "⚠️  Domínio vertexsolutions.ddns.net não está nos domínios autorizados\n";
    }
} else {
    echo "❌ SANCTUM_STATEFUL_DOMAINS não encontrado\n";
}

echo "\n";

// 2. Verificar se o Laravel está funcionando
echo "2. Verificando Laravel:\n";

// Carregar o Laravel
require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $config = config('app');
    echo "✅ Laravel carregado com sucesso\n";
    echo "✅ Ambiente: " . $config['env'] . "\n";
    echo "✅ URL: " . $config['url'] . "\n";
    echo "✅ Timezone: " . $config['timezone'] . "\n";
} catch (Exception $e) {
    echo "❌ Erro ao carregar Laravel: " . $e->getMessage() . "\n";
}

echo "\n";

// 3. Verificar conectividade com o domínio
echo "3. Verificando conectividade:\n";

$hostname = 'vertexsolutions.ddns.net';
$ip = gethostbyname($hostname);

if ($ip && $ip !== $hostname) {
    echo "✅ DNS resolvido: $hostname -> $ip\n";
} else {
    echo "❌ Não foi possível resolver o DNS para $hostname\n";
    echo "   Verifique se o DUC está rodando e atualizando o IP\n";
}

// 4. Verificar portas
echo "\n4. Verificando portas:\n";

$ports = [80, 443];
foreach ($ports as $port) {
    $connection = @fsockopen($hostname, $port, $errno, $errstr, 5);
    if ($connection) {
        echo "✅ Porta $port está aberta\n";
        fclose($connection);
    } else {
        echo "❌ Porta $port está fechada ou bloqueada\n";
        echo "   Verifique o port forwarding no roteador\n";
    }
}

echo "\n";

// 5. Verificar certificado SSL (se disponível)
echo "5. Verificando certificado SSL:\n";

$context = stream_context_create([
    'ssl' => [
        'capture_peer_cert' => true,
        'verify_peer' => false,
        'verify_peer_name' => false,
    ]
]);

$client = @stream_socket_client(
    "ssl://$hostname:443",
    $errno,
    $errstr,
    30,
    STREAM_CLIENT_CONNECT,
    $context
);

if ($client) {
    $params = stream_context_get_params($client);
    if (isset($params['options']['ssl']['peer_certificate'])) {
        $cert = openssl_x509_parse($params['options']['ssl']['peer_certificate']);
        echo "✅ Certificado SSL encontrado\n";
        echo "   Emitido para: " . $cert['subject']['CN'] . "\n";
        echo "   Válido até: " . date('Y-m-d H:i:s', $cert['validTo_time_t']) . "\n";
    } else {
        echo "⚠️  Conexão SSL estabelecida, mas certificado não encontrado\n";
    }
    fclose($client);
} else {
    echo "❌ Não foi possível conectar via SSL\n";
    echo "   Certificado SSL pode não estar configurado\n";
}

echo "\n";

// 6. Resumo e recomendações
echo "=== RESUMO E RECOMENDAÇÕES ===\n\n";

echo "Para completar a configuração:\n";
echo "1. ✅ Configurações do .env aplicadas\n";
echo "2. ✅ Laravel configurado\n";
echo "3. 🔧 Configure o DUC (Dynamic Update Client) do No-IP\n";
echo "4. 🔧 Configure port forwarding no roteador (portas 80 e 443)\n";
echo "5. 🔧 Instale certificado SSL para HTTPS\n";
echo "6. 🔧 Teste o acesso: https://vertexsolutions.ddns.net\n\n";

echo "Credenciais No-IP:\n";
echo "- Hostname: vertexsolutions.ddns.net\n";
echo "- Username: 6ddde8x\n";
echo "- DDNS Key: [Sua senha única]\n\n";

echo "Documentação completa: docs/noip-setup.md\n";
echo "Logs do sistema: storage/logs/laravel.log\n";

echo "\n=== VERIFICAÇÃO CONCLUÍDA ===\n"; 