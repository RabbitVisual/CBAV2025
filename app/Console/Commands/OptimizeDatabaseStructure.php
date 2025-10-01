<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OptimizeDatabaseStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:optimize-structure {--force : Forçar a execução sem confirmação}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otimiza a estrutura do banco de dados do projeto CBAV';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (app()->runningUnitTests()) {
            $this->info('Ambiente de testes: ignorando OptimizeDatabaseStructure.');
            return 0;
        }
        $this->info('🚀 Iniciando otimização da estrutura do banco de dados...');

        // Verificar se há backup
        if (!$this->option('force')) {
            if (!$this->confirm('⚠️  É altamente recomendado fazer backup antes de continuar. Deseja prosseguir?')) {
                $this->info('❌ Operação cancelada.');
                return 0;
            }
        }

        try {
            $this->info('📋 Executando migrações pendentes...');
            
            // Executar migrações
            $this->call('migrate', ['--force' => true]);
            
            $this->info('✅ Migrações executadas com sucesso!');
            
            // Verificar estrutura após otimização
            $this->info('🔍 Verificando estrutura otimizada...');
            $this->call('db:analyze-structure');
            
            $this->info('🎉 Otimização concluída com sucesso!');
            
        } catch (\Exception $e) {
            $this->error('❌ Erro durante a otimização: ' . $e->getMessage());
            $this->error('💡 Verifique os logs em storage/logs/laravel.log');
        }
    }
} 