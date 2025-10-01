<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Configuracao;

class CouncilSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Configurações Gerais
        Configuracao::set('council_quorum_padrao', '50', 'integer', 'Quórum padrão para reuniões do conselho');
        Configuracao::set('council_duracao_padrao', '120', 'integer', 'Duração padrão das reuniões em minutos');
        Configuracao::set('council_max_pautas', '10', 'integer', 'Número máximo de pautas por reunião');
        Configuracao::set('council_tempo_votacao', '5', 'integer', 'Tempo padrão para votações em minutos');
        
        // Configurações de Votação
        Configuracao::set('council_voto_secreto_padrao', '0', 'boolean', 'Ativar voto secreto como padrão');
        Configuracao::set('council_permitir_abstencao', '1', 'boolean', 'Permitir que participantes se abstenham');
        Configuracao::set('council_justificativa_obrigatoria', '0', 'boolean', 'Exigir justificativa para votos contrários');
        Configuracao::set('council_maioria_qualificada', '66', 'integer', 'Percentual para maioria qualificada');
        
        // Configurações de Notificação
        Configuracao::set('council_notificar_reuniao', '1', 'boolean', 'Enviar notificação quando nova reunião for criada');
        Configuracao::set('council_notificar_votacao', '1', 'boolean', 'Enviar notificação quando votação for iniciada');
        Configuracao::set('council_notificar_resultado', '1', 'boolean', 'Enviar notificação com resultado da votação');
        Configuracao::set('council_antecedencia_notificacao', '24', 'integer', 'Horas de antecedência para notificar reunião');
        
        // Configurações de Segurança
        Configuracao::set('council_registrar_logs', '1', 'boolean', 'Manter registro de todas as atividades');
        Configuracao::set('council_backup_automatico', '1', 'boolean', 'Fazer backup automático dos dados');
        Configuracao::set('council_tempo_sessao', '30', 'integer', 'Tempo de inatividade para logout');
        Configuracao::set('council_max_tentativas', '3', 'integer', 'Número máximo de tentativas de login');
        
        $this->command->info('Configurações do conselho inicializadas com sucesso!');
    }
} 