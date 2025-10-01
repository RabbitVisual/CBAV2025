<?php

namespace App\Helpers;

use App\Models\Configuracao;
use Illuminate\Support\Facades\Cache;

class CouncilSettingsHelper
{
    /**
     * Obter configuração do conselho
     */
    public static function get($key, $default = null)
    {
        $cacheKey = "council_setting_{$key}";
        
        return Cache::remember($cacheKey, 3600, function () use ($key, $default) {
            return Configuracao::get("council_{$key}", $default);
        });
    }

    /**
     * Definir configuração do conselho
     */
    public static function set($key, $value, $type = 'string', $description = null)
    {
        $result = Configuracao::set("council_{$key}", $value, $type, $description);
        
        // Limpar cache
        Cache::forget("council_setting_{$key}");
        Cache::forget('council_settings');
        
        return $result;
    }

    /**
     * Obter todas as configurações do conselho
     */
    public static function getAll()
    {
        return Cache::remember('council_settings', 3600, function () {
            return [
                'quorum_padrao' => (int) self::get('quorum_padrao', 50),
                'duracao_padrao' => (int) self::get('duracao_padrao', 120),
                'max_pautas' => (int) self::get('max_pautas', 10),
                'tempo_votacao' => (int) self::get('tempo_votacao', 5),
                'voto_secreto_padrao' => (bool) self::get('voto_secreto_padrao', false),
                'permitir_abstencao' => (bool) self::get('permitir_abstencao', true),
                'justificativa_obrigatoria' => (bool) self::get('justificativa_obrigatoria', false),
                'maioria_qualificada' => (int) self::get('maioria_qualificada', 66),
                'notificar_reuniao' => (bool) self::get('notificar_reuniao', true),
                'notificar_votacao' => (bool) self::get('notificar_votacao', true),
                'notificar_resultado' => (bool) self::get('notificar_resultado', true),
                'antecedencia_notificacao' => (int) self::get('antecedencia_notificacao', 24),
                'registrar_logs' => (bool) self::get('registrar_logs', true),
                'backup_automatico' => (bool) self::get('backup_automatico', true),
                'tempo_sessao' => (int) self::get('tempo_sessao', 30),
                'max_tentativas' => (int) self::get('max_tentativas', 3),
            ];
        });
    }

    /**
     * Verificar se deve registrar logs
     */
    public static function shouldLogActivity()
    {
        return (bool) self::get('registrar_logs', true);
    }

    /**
     * Verificar se deve notificar nova reunião
     */
    public static function shouldNotifyNewMeeting()
    {
        return (bool) self::get('notificar_reuniao', true);
    }

    /**
     * Verificar se deve notificar votações
     */
    public static function shouldNotifyVoting()
    {
        return (bool) self::get('notificar_votacao', true);
    }

    /**
     * Verificar se deve notificar resultados
     */
    public static function shouldNotifyResults()
    {
        return (bool) self::get('notificar_resultado', true);
    }

    /**
     * Obter antecedência para notificação
     */
    public static function getNotificationAdvance()
    {
        return (int) self::get('antecedencia_notificacao', 24);
    }

    /**
     * Obter quórum padrão
     */
    public static function getDefaultQuorum()
    {
        return (int) self::get('quorum_padrao', 50);
    }

    /**
     * Obter duração padrão
     */
    public static function getDefaultDuration()
    {
        return (int) self::get('duracao_padrao', 120);
    }

    /**
     * Obter máximo de pautas
     */
    public static function getMaxAgendaItems()
    {
        return (int) self::get('max_pautas', 10);
    }

    /**
     * Obter tempo de votação
     */
    public static function getVotingTime()
    {
        return (int) self::get('tempo_votacao', 5);
    }

    /**
     * Verificar se voto secreto é padrão
     */
    public static function isSecretVoteDefault()
    {
        return (bool) self::get('voto_secreto_padrao', false);
    }

    /**
     * Verificar se permite abstenção
     */
    public static function allowsAbstention()
    {
        return (bool) self::get('permitir_abstencao', true);
    }

    /**
     * Verificar se justificativa é obrigatória
     */
    public static function requiresJustification()
    {
        return (bool) self::get('justificativa_obrigatoria', false);
    }

    /**
     * Obter maioria qualificada
     */
    public static function getQualifiedMajority()
    {
        return (int) self::get('maioria_qualificada', 66);
    }

    /**
     * Obter tempo de sessão
     */
    public static function getSessionTime()
    {
        return (int) self::get('tempo_sessao', 30);
    }

    /**
     * Obter máximo de tentativas
     */
    public static function getMaxAttempts()
    {
        return (int) self::get('max_tentativas', 3);
    }

    /**
     * Verificar se backup automático está ativo
     */
    public static function isAutoBackupEnabled()
    {
        return (bool) self::get('backup_automatico', true);
    }

    /**
     * Limpar cache de configurações
     */
    public static function clearCache()
    {
        Cache::forget('council_settings');
        
        // Limpar cache de configurações individuais
        $keys = [
            'quorum_padrao', 'duracao_padrao', 'max_pautas', 'tempo_votacao',
            'voto_secreto_padrao', 'permitir_abstencao', 'justificativa_obrigatoria',
            'maioria_qualificada', 'notificar_reuniao', 'notificar_votacao',
            'notificar_resultado', 'antecedencia_notificacao', 'registrar_logs',
            'backup_automatico', 'tempo_sessao', 'max_tentativas'
        ];
        
        foreach ($keys as $key) {
            Cache::forget("council_setting_{$key}");
        }
    }
} 