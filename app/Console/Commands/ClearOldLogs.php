<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class ClearOldLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:clear-old {--days=30 : Número de dias para manter logs} {--force : Forçar limpeza sem confirmação}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpar logs antigos do sistema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $force = $this->option('force');
        
        if (app()->runningUnitTests()) {
            $force = true;
        }
        
        if (!$force) {
            if (!$this->confirm("Deseja limpar logs mais antigos que {$days} dias?")) {
                $this->info('Operação cancelada.');
                return 0;
            }
        }

        $logPath = storage_path('logs');
        $cutoffDate = Carbon::now()->subDays($days);
        
        $this->info("Limpando logs mais antigos que {$days} dias...");
        
        $clearedFiles = 0;
        $clearedSize = 0;
        
        if (File::exists($logPath)) {
            $files = File::files($logPath);
            
            foreach ($files as $file) {
                $filePath = $file->getPathname();
                $lastModified = Carbon::createFromTimestamp(File::lastModified($filePath));
                
                if ($lastModified->lt($cutoffDate)) {
                    $size = File::size($filePath);
                    File::delete($filePath);
                    
                    $clearedFiles++;
                    $clearedSize += $size;
                    
                    $this->line("Removido: " . basename($filePath) . " (" . $this->formatBytes($size) . ")");
                }
            }
        }
        
        if ($clearedFiles > 0) {
            $this->info("Limpeza concluída! {$clearedFiles} arquivos removidos, {$this->formatBytes($clearedSize)} liberados.");
        } else {
            $this->info("Nenhum arquivo antigo encontrado para remoção.");
        }
    }
    
    /**
     * Formatar bytes para leitura humana
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
} 