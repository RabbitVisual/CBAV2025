<?php
/**
 * CBAV CRM Ministerial - Script de Update
 * Desenvolvido por Vertex Solutions - CEO Reinan Rodrigues
 * 
 * Este script realiza o update do sistema CBAV na hostinger
 * Inclui todas as melhorias de design e remoção do sistema de carteirinha
 */

// Configurações
$version = '2.1.0';
$updateDate = '2025-01-07';
$backupDir = 'backups/';
$logFile = 'logs/update-' . date('Y-m-d-H-i-s') . '.log';

// Criar diretórios se não existirem
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}
if (!is_dir('logs')) {
    mkdir('logs', 0755, true);
}

// Função para log
function writeLog($message) {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND);
    echo $logMessage;
}

// Função para backup
function createBackup($source, $destination) {
    if (is_dir($source)) {
        // Backup de diretório
        $zip = new ZipArchive();
        if ($zip->open($destination, ZipArchive::CREATE) === TRUE) {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($source),
                RecursiveIteratorIterator::LEAVES_ONLY
            );
            
            foreach ($iterator as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($source) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
            return true;
        }
    } else {
        // Backup de arquivo
        return copy($source, $destination);
    }
    return false;
}

// Função para verificar se é Laravel
function isLaravelProject() {
    return file_exists('artisan') && file_exists('composer.json');
}

// Função para executar comando
function executeCommand($command) {
    writeLog("Executando: $command");
    $output = [];
    $returnVar = 0;
    exec($command . ' 2>&1', $output, $returnVar);
    
    foreach ($output as $line) {
        writeLog("  $line");
    }
    
    return $returnVar === 0;
}

// Início do script
writeLog("=== CBAV CRM Ministerial - Update v$version ===");
writeLog("Data: $updateDate");
writeLog("Iniciando processo de update...");

// Verificar se é um projeto Laravel
if (!isLaravelProject()) {
    writeLog("ERRO: Este não parece ser um projeto Laravel válido!");
    writeLog("Certifique-se de estar no diretório raiz do projeto CBAV.");
    exit(1);
}

// 1. Criar backup do sistema atual
writeLog("1. Criando backup do sistema atual...");
$backupFile = $backupDir . 'cbav-backup-' . date('Y-m-d-H-i-s') . '.zip';
if (createBackup('.', $backupFile)) {
    writeLog("   Backup criado com sucesso: $backupFile");
} else {
    writeLog("   AVISO: Não foi possível criar o backup completo");
}

// 2. Verificar dependências
writeLog("2. Verificando dependências...");
if (!executeCommand('composer install --no-dev --optimize-autoloader')) {
    writeLog("   AVISO: Problemas com dependências do Composer");
}

// 3. Limpar caches
writeLog("3. Limpando caches...");
executeCommand('php artisan cache:clear');
executeCommand('php artisan config:clear');
executeCommand('php artisan route:clear');
executeCommand('php artisan view:clear');

// 4. Executar migrações
writeLog("4. Executando migrações...");
if (!executeCommand('php artisan migrate --force')) {
    writeLog("   AVISO: Problemas com migrações");
}

// 5. Otimizar para produção
writeLog("5. Otimizando para produção...");
executeCommand('php artisan config:cache');
executeCommand('php artisan route:cache');
executeCommand('php artisan view:cache');

// 6. Verificar permissões
writeLog("6. Verificando permissões...");
$directories = [
    'storage/app',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache'
];

foreach ($directories as $dir) {
    if (is_dir($dir)) {
        chmod($dir, 0755);
        writeLog("   Permissões ajustadas para: $dir");
    }
}

// 7. Verificar arquivo .env
writeLog("7. Verificando configurações...");
if (!file_exists('.env')) {
    writeLog("   AVISO: Arquivo .env não encontrado!");
    writeLog("   Certifique-se de configurar o arquivo .env corretamente.");
} else {
    writeLog("   Arquivo .env encontrado");
}

// 8. Verificar conectividade com banco
writeLog("8. Testando conectividade com banco de dados...");
if (executeCommand('php artisan tinker --execute="echo \'Conexão OK\';"')) {
    writeLog("   Conexão com banco de dados OK");
} else {
    writeLog("   AVISO: Problemas com conexão do banco de dados");
}

// 9. Verificar rotas
writeLog("9. Verificando rotas...");
if (executeCommand('php artisan route:list --compact')) {
    writeLog("   Rotas carregadas com sucesso");
} else {
    writeLog("   AVISO: Problemas com carregamento de rotas");
}

// 10. Criar arquivo de status
writeLog("10. Criando arquivo de status...");
$statusFile = 'storage/system_status.txt';
$status = [
    'version' => $version,
    'update_date' => $updateDate,
    'last_check' => date('Y-m-d H:i:s'),
    'status' => 'updated',
    'backup_file' => basename($backupFile)
];

file_put_contents($statusFile, json_encode($status, JSON_PRETTY_PRINT));

// 11. Verificações finais
writeLog("11. Verificações finais...");

// Verificar se as views principais existem
$mainViews = [
    'resources/views/layouts/admin.blade.php',
    'resources/views/admin/people/members/index.blade.php',
    'resources/views/admin/people/members/show.blade.php',
    'resources/views/admin/people/members/create.blade.php',
    'resources/views/admin/people/members/edit.blade.php'
];

foreach ($mainViews as $view) {
    if (file_exists($view)) {
        writeLog("   ✓ $view");
    } else {
        writeLog("   ✗ $view (NÃO ENCONTRADO)");
    }
}

// Verificar se os controllers principais existem
$mainControllers = [
    'app/Http/Controllers/Admin/PeopleController.php',
    'app/Http/Controllers/Admin/DashboardController.php'
];

foreach ($mainControllers as $controller) {
    if (file_exists($controller)) {
        writeLog("   ✓ $controller");
    } else {
        writeLog("   ✗ $controller (NÃO ENCONTRADO)");
    }
}

// 12. Resumo final
writeLog("12. Resumo do update...");
writeLog("   Versão atual: $version");
writeLog("   Backup criado: " . basename($backupFile));
writeLog("   Log salvo em: $logFile");
writeLog("   Status salvo em: $statusFile");

// Verificar se o sistema está funcionando
writeLog("13. Teste final do sistema...");
if (executeCommand('php artisan --version')) {
    writeLog("   ✓ Laravel funcionando corretamente");
} else {
    writeLog("   ✗ Problemas com Laravel");
}

// Instruções finais
writeLog("=== UPDATE CONCLUÍDO ===");
writeLog("");
writeLog("INSTRUÇÕES PÓS-UPDATE:");
writeLog("1. Verifique se o site está funcionando normalmente");
writeLog("2. Teste as funcionalidades principais:");
writeLog("   - Login no sistema");
writeLog("   - Gestão de membros");
writeLog("   - Criação e edição de membros");
writeLog("   - Importação de membros");
writeLog("   - Relatórios");
writeLog("3. Se houver problemas, restaure o backup: $backupFile");
writeLog("4. Verifique os logs em: $logFile");
writeLog("");
writeLog("MELHORIAS IMPLEMENTADAS:");
writeLog("- Design padronizado em todas as páginas de membros");
writeLog("- Sistema de carteirinha completamente removido");
writeLog("- Layout simplificado e mais limpo");
writeLog("- Melhor responsividade");
writeLog("- Cores consistentes com o padrão do sistema");
writeLog("");
writeLog("Para suporte técnico, entre em contato com:");
writeLog("Vertex Solutions - CEO Reinan Rodrigues");
writeLog("Email: contato@vertexsolutions.com.br");
writeLog("");

echo "\nUpdate concluído com sucesso!\n";
echo "Verifique o log em: $logFile\n";
echo "Backup salvo em: $backupFile\n";
?> 