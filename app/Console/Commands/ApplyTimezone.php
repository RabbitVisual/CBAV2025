<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\SystemConfigHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class ApplyTimezone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:apply-timezone {timezone? : Timezone to apply}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aplicar fuso horário em todo o sistema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $timezone = $this->argument('timezone');
        
        if (!$timezone) {
            $timezone = SystemConfigHelper::getCurrentTimezone();
        }

        $this->info("Aplicando fuso horário: {$timezone}");
        
        try {
            // Verificar se o timezone é válido
            if (!SystemConfigHelper::isValidTimezone($timezone)) {
                $this->error("Fuso horário inválido: {$timezone}");
                return 1;
            }

            // Aplicar timezone no sistema
            SystemConfigHelper::applyTimezone($timezone);
            
            // Salvar no banco de dados
            SystemConfigHelper::set('timezone', $timezone);
            
            // Obter informações do timezone
            $timezoneInfo = SystemConfigHelper::getTimezoneInfo($timezone);
            
            $this->info("✅ Fuso horário aplicado com sucesso!");
            $this->info("📅 Data/Hora atual: {$timezoneInfo['current_time']}");
            $this->info("⏰ Offset: {$timezoneInfo['offset']}");
            $this->info("🏷️  Abreviação: {$timezoneInfo['abbreviation']}");
            $this->info("🌞 Horário de Verão: " . ($timezoneInfo['is_dst'] ? 'Sim' : 'Não'));
            
            // Limpar cache
            SystemConfigHelper::clearCache();
            $this->info("🗑️  Cache limpo");
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("❌ Erro ao aplicar fuso horário: " . $e->getMessage());
            return 1;
        }
    }
} 