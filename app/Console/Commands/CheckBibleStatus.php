<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DevocionalService;
use App\Services\BibleService;
use Illuminate\Support\Facades\Storage;

class CheckBibleStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bible:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Bible and devotional system status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Verificando status do sistema de Bíblia e devocionais...');
        $this->newLine();

        // Verificar Bíblia offline
        $this->info('📖 Verificando Bíblia Offline:');
        $bibleService = new BibleService();
        $versions = $bibleService->getVersions();
        
        if (empty($versions)) {
            $this->error('❌ Nenhuma versão da Bíblia encontrada');
        } else {
            $this->info('✅ Versões disponíveis:');
            foreach ($versions as $key => $version) {
                $this->line("   • {$version['name']} ({$version['abbrev']})");
            }
        }

        // Verificar arquivos JSON
        $this->newLine();
        $this->info('📁 Verificando arquivos JSON:');
        $storagePath = 'bible/offline';
        $files = ['almeida_ra.json', 'almeida_rc.json', 'blivre.json'];
        
        foreach ($files as $file) {
            if (Storage::exists($storagePath . '/' . $file)) {
                $size = Storage::size($storagePath . '/' . $file);
                $sizeMB = round($size / 1024 / 1024, 2);
                $this->info("   ✅ {$file} ({$sizeMB} MB)");
            } else {
                $this->error("   ❌ {$file} não encontrado");
            }
        }

        // Verificar estatísticas
        $this->newLine();
        $this->info('📊 Estatísticas do Sistema:');
        
        $devocionalService = new DevocionalService();
        $estatisticas = $devocionalService->getEstatisticas();
        
        if ($estatisticas) {
            $this->info("   • Total de versículos: " . number_format($estatisticas['biblia']['total_verses'] ?? 0));
            $this->info("   • Total de livros: " . ($estatisticas['biblia']['total_books'] ?? 66));
            $this->info("   • Versões disponíveis: " . ($estatisticas['biblia']['available_versions'] ?? 0));
            $this->info("   • Devocionais ativos: " . ($estatisticas['devocional']['devocionais_ativos'] ?? 0));
            $this->info("   • Devocionais hoje: " . ($estatisticas['devocional']['devocionais_hoje'] ?? 0));
        } else {
            $this->error("   ❌ Não foi possível obter estatísticas");
        }

        // Verificar status geral
        $this->newLine();
        $this->info('🔧 Status Geral:');
        $status = $devocionalService->verificarStatus();
        
        if ($status['biblia_offline']) {
            $this->info('   ✅ Bíblia offline disponível');
        } else {
            $this->error('   ❌ Bíblia offline indisponível');
        }
        
        if ($status['devocional_hoje']) {
            $this->info('   ✅ Devocional do dia disponível');
        } else {
            $this->warn('   ⚠️ Usando devocional padrão');
        }

        $this->newLine();
        $this->info('🎉 Verificação concluída!');
        
        return Command::SUCCESS;
    }
} 