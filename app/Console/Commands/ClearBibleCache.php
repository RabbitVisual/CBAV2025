<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DevocionalService;
use Illuminate\Support\Facades\Cache;

class ClearBibleCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bible:clear-cache {--all : Clear all cache including Bible data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear Bible and devotional cache';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧹 Limpando cache da Bíblia e devocionais...');

        $devocionalService = new DevocionalService();
        
        // Limpar cache dos devocionais
        $devocionalService->limparCache();
        $this->info('✅ Cache dos devocionais limpo');

        // Limpar cache da Bíblia se solicitado
        if ($this->option('all')) {
            $this->info('🗂️ Limpando cache da Bíblia...');
            
            // Limpar cache de todas as versões
            $versions = ['almeida_ra', 'almeida_rc', 'blivre'];
            foreach ($versions as $version) {
                Cache::forget("bible_data_{$version}");
            }
            
            $this->info('✅ Cache da Bíblia limpo');
        }

        $this->info('🎉 Cache limpo com sucesso!');
        
        return Command::SUCCESS;
    }
} 