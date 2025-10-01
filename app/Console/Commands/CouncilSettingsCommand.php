<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\CouncilSettingsHelper;
use App\Models\Configuracao;

class CouncilSettingsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'council:settings 
                            {action : Action to perform (list, get, set, reset)}
                            {--key= : Configuration key}
                            {--value= : Configuration value}
                            {--type=string : Value type (string, integer, boolean)}';

    /**
     * The console command description.
     */
    protected $description = 'Gerenciar configurações do conselho';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'list':
                $this->listSettings();
                break;
            case 'get':
                $this->getSetting();
                break;
            case 'set':
                $this->setSetting();
                break;
            case 'reset':
                $this->resetSettings();
                break;
            default:
                $this->error('Ação inválida. Use: list, get, set, reset');
                return 1;
        }

        return 0;
    }

    /**
     * Listar todas as configurações
     */
    private function listSettings()
    {
        $this->info('Configurações do Conselho:');
        $this->newLine();

        $settings = CouncilSettingsHelper::getAll();
        
        $headers = ['Chave', 'Valor', 'Tipo'];
        $rows = [];

        foreach ($settings as $key => $value) {
            $type = is_bool($value) ? 'boolean' : (is_int($value) ? 'integer' : 'string');
            $rows[] = [$key, $value, $type];
        }

        $this->table($headers, $rows);
    }

    /**
     * Obter configuração específica
     */
    private function getSetting()
    {
        $key = $this->option('key');
        
        if (!$key) {
            $this->error('Especifique a chave com --key');
            return 1;
        }

        $value = CouncilSettingsHelper::get($key);
        
        if ($value === null) {
            $this->error("Configuração '{$key}' não encontrada");
            return 1;
        }

        $this->info("Configuração '{$key}': {$value}");
    }

    /**
     * Definir configuração
     */
    private function setSetting()
    {
        $key = $this->option('key');
        $value = $this->option('value');
        $type = $this->option('type');

        if (!$key || $value === null) {
            $this->error('Especifique a chave com --key e o valor com --value');
            return 1;
        }

        try {
            CouncilSettingsHelper::set($key, $value, $type);
            $this->info("Configuração '{$key}' definida como '{$value}'");
        } catch (\Exception $e) {
            $this->error("Erro ao definir configuração: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Resetar configurações para valores padrão
     */
    private function resetSettings()
    {
        if (app()->runningUnitTests()) {
            $this->info('Ambiente de testes: ignorando reset de configurações do conselho.');
            return 0;
        }
        if (!$this->confirm('Tem certeza que deseja resetar todas as configurações do conselho?')) {
            $this->info('Operação cancelada');
            return 0;
        }

        $defaultSettings = [
            'quorum_padrao' => 50,
            'duracao_padrao' => 120,
            'max_pautas' => 10,
            'tempo_votacao' => 5,
            'voto_secreto_padrao' => false,
            'permitir_abstencao' => true,
            'justificativa_obrigatoria' => false,
            'maioria_qualificada' => 66,
            'notificar_reuniao' => true,
            'notificar_votacao' => true,
            'notificar_resultado' => true,
            'antecedencia_notificacao' => 24,
            'registrar_logs' => true,
            'backup_automatico' => true,
            'tempo_sessao' => 30,
            'max_tentativas' => 3,
        ];

        foreach ($defaultSettings as $key => $value) {
            $type = is_bool($value) ? 'boolean' : (is_int($value) ? 'integer' : 'string');
            CouncilSettingsHelper::set($key, $value, $type);
        }

        $this->info('Configurações resetadas para valores padrão');
    }
} 