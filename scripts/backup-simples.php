<?php
/**
 * CBAV CRM Ministerial - Backup Simples
 * Script para criar backup antes do update
 */

$backupDir = '../backups/';
$timestamp = date('Y-m-d-H-i-s');
$backupFile = $backupDir . 'cbav-backup-' . $timestamp . '.zip';

// Criar diretório de backup se não existir
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

echo "=== CBAV CRM Ministerial - Backup Simples ===\n";
echo "Criando backup em: $backupFile\n";

// Criar backup
$zip = new ZipArchive();
if ($zip->open($backupFile, ZipArchive::CREATE) === TRUE) {
    
    // Adicionar arquivos importantes
    $files = [
        'app/',
        'resources/',
        'routes/',
        'config/',
        'database/',
        'public/',
        'composer.json',
        'composer.lock',
        'artisan',
        '.env.example'
    ];
    
    foreach ($files as $file) {
        if (is_dir($file)) {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($file),
                RecursiveIteratorIterator::LEAVES_ONLY
            );
            
            foreach ($iterator as $item) {
                if (!$item->isDir()) {
                    $filePath = $item->getRealPath();
                    $relativePath = substr($filePath, strlen('./') + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
        } elseif (file_exists($file)) {
            $zip->addFile($file, $file);
        }
    }
    
    $zip->close();
    
    echo "✓ Backup criado com sucesso!\n";
    echo "Arquivo: $backupFile\n";
    echo "Tamanho: " . number_format(filesize($backupFile) / 1024 / 1024, 2) . " MB\n";
    
} else {
    echo "✗ Erro ao criar backup!\n";
    exit(1);
}

echo "\nBackup concluído! Agora você pode executar o update com segurança.\n";
?> 