<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Configuracao;
use App\Helpers\SystemConfigHelper;

class TimezoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('⏰ Configurando fuso horário...');
        
        // Configurar timezone padrão
        Configuracao::updateOrCreate(
            ['chave' => 'timezone'],
            [
                'valor' => 'America/Sao_Paulo',
                'tipo' => 'string',
                'descricao' => 'Fuso horário do sistema'
            ]
        );
        
        // Aplicar timezone no sistema
        if (class_exists('App\Helpers\SystemConfigHelper')) {
            SystemConfigHelper::applyTimezone('America/Sao_Paulo');
        }
        
        $this->command->info('✅ Fuso horário configurado: America/Sao_Paulo');
        
        // Mostrar informações do timezone
        if (class_exists('App\Helpers\SystemConfigHelper')) {
            $timezoneInfo = SystemConfigHelper::getTimezoneInfo('America/Sao_Paulo');
            $this->command->info("📅 Data/Hora atual: {$timezoneInfo['current_time']}");
            $this->command->info("⏰ Offset: {$timezoneInfo['offset']}");
            $this->command->info("🏷️  Abreviação: {$timezoneInfo['abbreviation']}");
        }
    }
} 