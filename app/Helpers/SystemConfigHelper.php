<?php

namespace App\Helpers;

use App\Models\Configuracao;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class SystemConfigHelper
{
    /**
     * Obter configuração do sistema
     */
    public static function get($key, $default = null)
    {
        return Configuracao::get($key, $default);
    }

    /**
     * Definir configuração do sistema
     */
    public static function set($key, $value, $type = 'string', $description = null)
    {
        return Configuracao::set($key, $value, $type, $description);
    }

    /**
     * Obter todas as configurações
     */
    public static function getAll()
    {
        return [
            'app_name' => self::get('app_name', config('app.name')),
            'app_description' => self::get('app_description', ''),
            'contact_email' => self::get('contact_email', ''),
            'contact_phone' => self::get('contact_phone', ''),
            'address' => self::get('address', ''),
            'timezone' => self::get('timezone', config('app.timezone')),
            'locale' => self::get('locale', config('app.locale')),
            'social_facebook' => self::get('social_facebook', ''),
            'social_instagram' => self::get('social_instagram', ''),
            'social_youtube' => self::get('social_youtube', ''),
        ];
    }

    /**
     * Aplicar fuso horário no sistema
     */
    public static function applyTimezone($timezone = null)
    {
        if (!$timezone) {
            $timezone = self::get('timezone', config('app.timezone'));
        }

        if ($timezone) {
            // Aplicar no Laravel
            Config::set('app.timezone', $timezone);
            
            // Aplicar no PHP
            date_default_timezone_set($timezone);
            
            \Log::info('Timezone aplicado via helper', [
                'timezone' => $timezone,
                'current_time' => now()->format('Y-m-d H:i:s'),
                'php_timezone' => date_default_timezone_get()
            ]);
        }
    }

    /**
     * Obter fuso horário atual
     */
    public static function getCurrentTimezone()
    {
        return self::get('timezone', config('app.timezone'));
    }

    /**
     * Obter data/hora atual no fuso horário configurado
     */
    public static function getCurrentDateTime($format = 'Y-m-d H:i:s')
    {
        $timezone = self::getCurrentTimezone();
        return Carbon::now($timezone)->format($format);
    }

    /**
     * Converter data/hora para o fuso horário configurado
     */
    public static function convertToSystemTimezone($datetime, $fromTimezone = 'UTC', $format = 'Y-m-d H:i:s')
    {
        $systemTimezone = self::getCurrentTimezone();
        
        if ($datetime instanceof Carbon) {
            return $datetime->setTimezone($systemTimezone)->format($format);
        }
        
        return Carbon::parse($datetime, $fromTimezone)
                    ->setTimezone($systemTimezone)
                    ->format($format);
    }

    /**
     * Obter lista de fusos horários do Brasil
     */
    public static function getBrazilTimezones()
    {
        return [
            'America/Sao_Paulo' => 'America/Sao_Paulo (Brasília, São Paulo, Rio de Janeiro)',
            'America/Manaus' => 'America/Manaus (Manaus, Amazonas)',
            'America/Belem' => 'America/Belem (Belém, Pará)',
            'America/Fortaleza' => 'America/Fortaleza (Fortaleza, Ceará)',
            'America/Recife' => 'America/Recife (Recife, Pernambuco)',
            'America/Maceio' => 'America/Maceio (Maceió, Alagoas)',
            'America/Aracaju' => 'America/Aracaju (Aracaju, Sergipe)',
            'America/Bahia' => 'America/Bahia (Salvador, Bahia)',
            'America/Noronha' => 'America/Noronha (Fernando de Noronha)',
        ];
    }

    /**
     * Obter lista de fusos horários internacionais
     */
    public static function getInternationalTimezones()
    {
        return [
            'UTC' => 'UTC (Tempo Universal Coordenado)',
            'America/New_York' => 'America/New_York (Nova York, EUA)',
            'America/Los_Angeles' => 'America/Los_Angeles (Los Angeles, EUA)',
            'Europe/London' => 'Europe/London (Londres, Reino Unido)',
            'Europe/Paris' => 'Europe/Paris (Paris, França)',
            'Asia/Tokyo' => 'Asia/Tokyo (Tóquio, Japão)',
        ];
    }

    /**
     * Verificar se o fuso horário é válido
     */
    public static function isValidTimezone($timezone)
    {
        return in_array($timezone, timezone_identifiers_list());
    }

    /**
     * Obter informações do fuso horário
     */
    public static function getTimezoneInfo($timezone = null)
    {
        if (!$timezone) {
            $timezone = self::getCurrentTimezone();
        }

        $now = Carbon::now($timezone);
        $offset = $now->format('P'); // +03:00
        $abbreviation = $now->format('T'); // BRT

        return [
            'timezone' => $timezone,
            'current_time' => $now->format('Y-m-d H:i:s'),
            'offset' => $offset,
            'abbreviation' => $abbreviation,
            'is_dst' => $now->dst,
        ];
    }

    /**
     * Configurações do conselho
     */
    public static function shouldLogActivity()
    {
        return self::get('council_log_activity', true);
    }

    public static function shouldNotifyNewMeeting()
    {
        return self::get('council_notify_new_meeting', true);
    }

    public static function shouldNotifyVoting()
    {
        return self::get('council_notify_voting', true);
    }

    public static function shouldNotifyResults()
    {
        return self::get('council_notify_results', true);
    }

    public static function getNotificationAdvance()
    {
        return self::get('council_notification_advance', 24); // horas
    }

    public static function getDefaultQuorum()
    {
        return self::get('council_default_quorum', 50); // percentual
    }

    public static function getDefaultDuration()
    {
        return self::get('council_default_duration', 120); // minutos
    }

    public static function getMaxAgendaItems()
    {
        return self::get('council_max_agenda_items', 10);
    }

    public static function getVotingTime()
    {
        return self::get('council_voting_time', 30); // minutos
    }

    public static function isSecretVoteDefault()
    {
        return self::get('council_secret_vote_default', false);
    }

    public static function allowsAbstention()
    {
        return self::get('council_allow_abstention', true);
    }

    public static function requiresJustification()
    {
        return self::get('council_require_justification', false);
    }

    public static function getQualifiedMajority()
    {
        return self::get('council_qualified_majority', 66); // percentual
    }

    public static function getSessionTime()
    {
        return self::get('council_session_time', 180); // minutos
    }

    public static function getMaxAttempts()
    {
        return self::get('council_max_attempts', 3);
    }

    public static function isAutoBackupEnabled()
    {
        return self::get('council_auto_backup', true);
    }

    public static function clearCache()
    {
        \Artisan::call('config:clear');
        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
    }
} 