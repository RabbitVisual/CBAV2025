<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Configuracao;

class FixConfigurations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:configurations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar e corrigir configurações com valores nulos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Verificando configurações com valores nulos...');
        
        $configs = Configuracao::whereNull('valor')->get();
        $this->info('Configurações com valor nulo: ' . $configs->count());
        
        if ($configs->count() > 0) {
            foreach ($configs as $config) {
                $this->warn($config->chave . ' - ' . $config->valor);
                $config->valor = '';
                $config->save();
                $this->info('Corrigido: ' . $config->chave);
            }
        }
        
        $this->info('Verificação concluída!');
    }
} 