<?php
/**
 * Script de Backup e Deploy - CBAV CRM Ministerial
 * Desenvolvido por Vertex Solutions - CEO Reinan Rodrigues
 * 
 * Este script cria backup completo e prepara o sistema para deploy na hospedagem
 */

// Definir constantes
define('PROJECT_ROOT', __DIR__ . '/..');
define('STORAGE_PATH', PROJECT_ROOT . '/storage');
define('BACKUP_PATH', STORAGE_PATH . '/backups');
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

// Função para criar backup do banco de dados
function backupDatabase() {
    output("🗄️ Criando backup do banco de dados...", Colors::BLUE);
    
    // Verificar se o arquivo .env existe
    $envFile = PROJECT_ROOT . '/.env';
    if (!file_exists($envFile)) {
        output("❌ Arquivo .env não encontrado", Colors::RED);
        return false;
    }
    
    // Carregar configurações do banco
    $envContent = file_get_contents($envFile);
    preg_match('/DB_HOST=(.*)/', $envContent, $host);
    preg_match('/DB_PORT=(.*)/', $envContent, $port);
    preg_match('/DB_DATABASE=(.*)/', $envContent, $database);
    preg_match('/DB_USERNAME=(.*)/', $envContent, $username);
    preg_match('/DB_PASSWORD=(.*)/', $envContent, $password);
    
    $dbHost = trim($host[1] ?? 'localhost');
    $dbPort = trim($port[1] ?? '3306');
    $dbName = trim($database[1] ?? 'cbav');
    $dbUser = trim($username[1] ?? 'root');
    $dbPass = trim($password[1] ?? '');
    
    // Criar diretório de backup se não existir
    if (!file_exists(BACKUP_PATH)) {
        mkdir(BACKUP_PATH, 0755, true);
    }
    
    // Nome do arquivo de backup
    $timestamp = date('Y-m-d_H-i-s');
    $backupFile = BACKUP_PATH . "/database_backup_{$timestamp}.sql";
    
    // Comando mysqldump
    $command = "mysqldump -h{$dbHost} -P{$dbPort} -u{$dbUser}";
    if (!empty($dbPass)) {
        $command .= " -p{$dbPass}";
    }
    $command .= " {$dbName} > {$backupFile}";
    
    // Executar backup
    $output = [];
    $returnCode = 0;
    exec($command . " 2>&1", $output, $returnCode);
    
    if ($returnCode === 0 && file_exists($backupFile)) {
        $size = filesize($backupFile);
        output("✅ Backup do banco criado: {$backupFile} ({$size} bytes)", Colors::GREEN);
        return $backupFile;
    } else {
        output("❌ Erro ao criar backup do banco", Colors::RED);
        if (!empty($output)) {
            output("Erro: " . implode("\n", $output), Colors::RED);
        }
        return false;
    }
}

// Função para criar backup dos arquivos
function backupFiles() {
    output("📁 Criando backup dos arquivos...", Colors::BLUE);
    
    // Diretórios para incluir no backup
    $includeDirs = [
        'app',
        'bootstrap',
        'config',
        'database',
        'public',
        'resources',
        'routes',
        'storage/app/public',
        'storage/backups',
        'vendor',
        'composer.json',
        'composer.lock',
        'package.json',
        'package-lock.json',
        'artisan',
        'public/build',
        'public/build/assets',
        'public/build/manifest.json'
    ];
    
    // Arquivos para excluir do backup
    $excludeFiles = [
        'storage/logs/*.log',
        'storage/framework/cache/data/*',
        'storage/framework/sessions/*',
        'storage/framework/views/*',
        'bootstrap/cache/*.php',
        'node_modules',
        '.git',
        '.env',
        'storage/installed'
    ];
    
    // Criar diretório de backup se não existir
    if (!file_exists(BACKUP_PATH)) {
        mkdir(BACKUP_PATH, 0755, true);
    }
    
    // Nome do arquivo de backup
    $timestamp = date('Y-m-d_H-i-s');
    $backupFile = BACKUP_PATH . "/files_backup_{$timestamp}.zip";
    
    // Criar arquivo ZIP
    $zip = new ZipArchive();
    $result = $zip->open($backupFile, ZipArchive::CREATE);
    if ($result !== TRUE) {
        output("❌ Erro ao criar arquivo ZIP (código: $result)", Colors::RED);
        return false;
    }
    
    // Adicionar arquivos ao ZIP
    foreach ($includeDirs as $item) {
        $path = PROJECT_ROOT . '/' . $item;
        if (file_exists($path)) {
            if (is_dir($path)) {
                addDirectoryToZip($zip, $path, $item, $excludeFiles);
            } else {
                $zip->addFile($path, $item);
            }
        }
    }
    
    $zip->close();
    
    if (file_exists($backupFile)) {
        $size = filesize($backupFile);
        output("✅ Backup dos arquivos criado: {$backupFile} ({$size} bytes)", Colors::GREEN);
        return $backupFile;
    } else {
        output("❌ Erro ao criar backup dos arquivos", Colors::RED);
        return false;
    }
}

// Função para adicionar diretório ao ZIP
function addDirectoryToZip($zip, $dirPath, $relativePath, $excludeFiles) {
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dirPath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );
    
    foreach ($files as $file) {
        if ($file->isDir()) {
            continue;
        }
        
        $filePath = $file->getRealPath();
        $relativeFilePath = $relativePath . '/' . substr($filePath, strlen($dirPath) + 1);
        
        // Verificar se deve ser excluído
        $exclude = false;
        foreach ($excludeFiles as $excludePattern) {
            if (fnmatch($excludePattern, $relativeFilePath)) {
                $exclude = true;
                break;
            }
        }
        
        if (!$exclude) {
            $zip->addFile($filePath, $relativeFilePath);
        }
    }
}

// Função para criar backup completo
function createCompleteBackup() {
    output("💾 Criando backup completo do sistema...", Colors::BLUE);
    
    $timestamp = date('Y-m-d_H-i-s');
    $backupDir = BACKUP_PATH . "/complete_backup_{$timestamp}";
    
    if (!file_exists($backupDir)) {
        mkdir($backupDir, 0755, true);
    }
    
    // Backup do banco
    $dbBackup = backupDatabase();
    if ($dbBackup) {
        $dbBackupName = basename($dbBackup);
        copy($dbBackup, $backupDir . '/' . $dbBackupName);
    }
    
    // Backup dos arquivos
    $filesBackup = backupFiles();
    if ($filesBackup) {
        $filesBackupName = basename($filesBackup);
        copy($filesBackup, $backupDir . '/' . $filesBackupName);
    }
    
    // Criar arquivo de informações do backup
    $infoFile = $backupDir . '/backup_info.txt';
    $info = "CBAV CRM Ministerial - Backup Completo\n";
    $info .= "Data: " . date('Y-m-d H:i:s') . "\n";
    $info .= "Versão: 1.0.0\n";
    $info .= "Desenvolvido por: Vertex Solutions - CEO Reinan Rodrigues\n\n";
    $info .= "Arquivos incluídos:\n";
    if ($dbBackup) {
        $info .= "- " . basename($dbBackup) . "\n";
    }
    if ($filesBackup) {
        $info .= "- " . basename($filesBackup) . "\n";
    }
    $info .= "\nInstruções para restore:\n";
    $info .= "1. Extraia os arquivos\n";
    $info .= "2. Restaure o banco de dados\n";
    $info .= "3. Configure o arquivo .env\n";
    $info .= "4. Execute: php artisan migrate\n";
    $info .= "5. Execute: php artisan config:cache\n";
    
    file_put_contents($infoFile, $info);
    
    output("✅ Backup completo criado em: {$backupDir}", Colors::GREEN);
    return $backupDir;
}

// Função para criar arquivo de deploy
function createDeployPackage() {
    output("📦 Criando pacote de deploy...", Colors::BLUE);
    
    $timestamp = date('Y-m-d_H-i-s');
    $deployFile = BACKUP_PATH . "/deploy_package_{$timestamp}.zip";
    
    // Arquivos essenciais para deploy
    $essentialFiles = [
        'app',
        'bootstrap',
        'config',
        'database',
        'public',
        'resources',
        'routes',
        'storage/app/public',
        'vendor',
        'composer.json',
        'composer.lock',
        'package.json',
        'artisan',
        'public/build',
        'public/build/assets',
        'public/build/manifest.json',
        'env.example'
    ];
    
    // Criar ZIP de deploy
    $zip = new ZipArchive();
    if ($zip->open($deployFile, ZipArchive::CREATE) !== TRUE) {
        output("❌ Erro ao criar arquivo de deploy", Colors::RED);
        return false;
    }
    
    // Adicionar arquivos essenciais
    foreach ($essentialFiles as $item) {
        $path = PROJECT_ROOT . '/' . $item;
        if (file_exists($path)) {
            if (is_dir($path)) {
                addDirectoryToZip($zip, $path, $item, []);
            } else {
                $zip->addFile($path, $item);
            }
        }
    }
    
    // Adicionar scripts de deploy
    $scripts = [
        'scripts/deploy-check.php',
        'scripts/optimize-production.php'
    ];
    
    foreach ($scripts as $script) {
        $scriptPath = PROJECT_ROOT . '/' . $script;
        if (file_exists($scriptPath)) {
            $zip->addFile($scriptPath, $script);
        }
    }
    
    // Adicionar arquivo de instruções
    $instructions = "INSTRUÇÕES DE DEPLOY - CBAV CRM MINISTERIAL\n\n";
    $instructions .= "1. Faça upload dos arquivos para a hospedagem\n";
    $instructions .= "2. Configure o banco de dados MySQL\n";
    $instructions .= "3. Copie env.example para .env e configure:\n";
    $instructions .= "   - DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD\n";
    $instructions .= "   - APP_URL (URL do seu domínio)\n";
    $instructions .= "   - APP_ENV=production\n";
    $instructions .= "   - APP_DEBUG=false\n";
    $instructions .= "4. Execute: composer install --no-dev --optimize-autoloader\n";
    $instructions .= "5. Execute: php artisan key:generate\n";
    $instructions .= "6. Execute: php artisan migrate\n";
    $instructions .= "7. Execute: php artisan storage:link\n";
    $instructions .= "8. Execute: php artisan config:cache\n";
    $instructions .= "9. Execute: php artisan route:cache\n";
    $instructions .= "10. Execute: php artisan view:cache\n";
    $instructions .= "11. Acesse: https://seudominio.com/install.php\n";
    $instructions .= "12. Após instalação, remova: public/install.php\n\n";
    $instructions .= "Verificações:\n";
    $instructions .= "- Health Check: https://seudominio.com/health.php\n";
    $instructions .= "- Logs: storage/logs/laravel.log\n\n";
    $instructions .= "Suporte: Vertex Solutions - CEO Reinan Rodrigues";
    
    $zip->addFromString('INSTRUCOES_DEPLOY.txt', $instructions);
    
    $zip->close();
    
    if (file_exists($deployFile)) {
        $size = filesize($deployFile);
        output("✅ Pacote de deploy criado: {$deployFile} ({$size} bytes)", Colors::GREEN);
        return $deployFile;
    } else {
        output("❌ Erro ao criar pacote de deploy", Colors::RED);
        return false;
    }
}

// Função para limpar backups antigos
function cleanOldBackups($days = 7) {
    output("🧹 Limpando backups antigos (mais de {$days} dias)...", Colors::BLUE);
    
    if (!file_exists(BACKUP_PATH)) {
        return true;
    }
    
    $files = glob(BACKUP_PATH . '/*');
    $deleted = 0;
    
    foreach ($files as $file) {
        if (is_file($file)) {
            $fileTime = filemtime($file);
            $daysOld = (time() - $fileTime) / (24 * 60 * 60);
            
            if ($daysOld > $days) {
                if (unlink($file)) {
                    output("🗑️ Removido: " . basename($file), Colors::YELLOW);
                    $deleted++;
                }
            }
        }
    }
    
    output("✅ {$deleted} backups antigos removidos", Colors::GREEN);
    return true;
}

// Função para verificar espaço em disco
function checkDiskSpace() {
    output("💾 Verificando espaço em disco...", Colors::BLUE);
    
    $freeSpace = disk_free_space(PROJECT_ROOT);
    $totalSpace = disk_total_space(PROJECT_ROOT);
    $usedSpace = $totalSpace - $freeSpace;
    
    $freeGB = round($freeSpace / 1024 / 1024 / 1024, 2);
    $totalGB = round($totalSpace / 1024 / 1024 / 1024, 2);
    $usedGB = round($usedSpace / 1024 / 1024 / 1024, 2);
    
    output("📊 Espaço em disco:", Colors::RESET);
    output("   Total: {$totalGB} GB", Colors::RESET);
    output("   Usado: {$usedGB} GB", Colors::RESET);
    output("   Livre: {$freeGB} GB", Colors::RESET);
    
    if ($freeGB < 1) {
        output("⚠️ Pouco espaço livre (< 1 GB)", Colors::YELLOW);
        return false;
    }
    
    output("✅ Espaço em disco suficiente", Colors::GREEN);
    return true;
}

// Função principal
function main() {
    output("🚀 CBAV CRM Ministerial - Backup e Deploy", Colors::BOLD . Colors::BLUE);
    output("Desenvolvido por Vertex Solutions - CEO Reinan Rodrigues", Colors::BLUE);
    output("", Colors::RESET);
    
    // Verificar espaço em disco
    if (!checkDiskSpace()) {
        output("❌ Espaço insuficiente para backup", Colors::RED);
        return;
    }
    
    // Limpar backups antigos
    cleanOldBackups();
    
    // Criar backups
    $steps = [
        'Backup do banco de dados' => backupDatabase(),
        'Backup dos arquivos' => backupFiles(),
        'Backup completo' => createCompleteBackup(),
        'Pacote de deploy' => createDeployPackage()
    ];
    
    output("", Colors::RESET);
    output("📊 Resumo dos Backups:", Colors::BOLD);
    
    $passed = 0;
    $total = count($steps);
    
    foreach ($steps as $name => $result) {
        $status = $result ? "✅ OK" : "❌ ERRO";
        $color = $result ? Colors::GREEN : Colors::RED;
        output("$name: $status", $color);
        
        if ($result) $passed++;
    }
    
    output("", Colors::RESET);
    output("📈 Resultado: $passed/$total backups criados", Colors::BOLD);
    
    if ($passed === $total) {
        output("🎉 Sistema pronto para deploy!", Colors::BOLD . Colors::GREEN);
        
        output("", Colors::RESET);
        output("📋 Arquivos criados:", Colors::BOLD);
        output("- Backup do banco: " . BACKUP_PATH . "/database_backup_*.sql", Colors::RESET);
        output("- Backup dos arquivos: " . BACKUP_PATH . "/files_backup_*.zip", Colors::RESET);
        output("- Backup completo: " . BACKUP_PATH . "/complete_backup_*/", Colors::RESET);
        output("- Pacote de deploy: " . BACKUP_PATH . "/deploy_package_*.zip", Colors::RESET);
        
        output("", Colors::RESET);
        output("📋 Próximos passos:", Colors::BOLD);
        output("1. Faça download do pacote de deploy", Colors::RESET);
        output("2. Faça upload para a hospedagem", Colors::RESET);
        output("3. Configure o banco de dados", Colors::RESET);
        output("4. Configure o arquivo .env", Colors::RESET);
        output("5. Execute os comandos de instalação", Colors::RESET);
        output("6. Acesse: https://seudominio.com/install.php", Colors::RESET);
        
    } else {
        output("⚠️ Alguns backups falharam", Colors::BOLD . Colors::YELLOW);
    }
    
    output("", Colors::RESET);
    output("🔗 Health Check: https://seudominio.com/health.php", Colors::BLUE);
    output("📞 Suporte: Vertex Solutions", Colors::BLUE);
}

// Executar backup e deploy
main(); 