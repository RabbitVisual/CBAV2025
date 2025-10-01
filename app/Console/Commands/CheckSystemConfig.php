<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Configuracao;

class CheckSystemConfig extends Command
{
    protected $signature = 'system:check-config';
    protected $description = 'Verificar configurações do sistema';

    public function handle()
    {
        $this->info('=== VERIFICAÇÃO DE CONFIGURAÇÕES DO SISTEMA ===');
        
        // Verificar configurações básicas da igreja
        $this->info('Configurações da Igreja:');
        $igrejaConfigs = Configuracao::where('chave', 'like', 'igreja_%')->get();
        foreach ($igrejaConfigs as $config) {
            $this->line("  {$config->chave}: {$config->valor}");
        }
        
        $this->info('');
        
        // Verificar configurações de cores
        $this->info('Configurações de Cores:');
        $corConfigs = Configuracao::where('chave', 'like', 'cor_%')->get();
        foreach ($corConfigs as $config) {
            $this->line("  {$config->chave}: {$config->valor}");
        }
        
        $this->info('');
        
        // Verificar configurações de seções
        $this->info('Configurações de Seções:');
        $secaoConfigs = Configuracao::where('chave', 'like', 'secao_%')->get();
        foreach ($secaoConfigs as $config) {
            $this->line("  {$config->chave}: {$config->valor}");
        }
        
        $this->info('');
        
        // Verificar configurações de doação
        $this->info('Configurações de Doação:');
        $doacaoConfigs = Configuracao::where('chave', 'like', 'doacao_%')->get();
        foreach ($doacaoConfigs as $config) {
            $this->line("  {$config->chave}: {$config->valor}");
        }
        
        $this->info('');
        $this->info('Total de configurações: ' . Configuracao::count());
        
        return 0;
    }
} 