<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use App\Helpers\SystemConfigHelper;
use App\Models\Configuracao;

class ApplySystemConfig
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Aplicar configurações do sistema
        $this->applyBasicConfig();
        $this->applyEmailConfig();
        $this->applySecurityConfig();
        $this->applyPaymentConfig();
        $this->applyCacheConfig();
        
        return $next($request);
    }
    
    /**
     * Aplicar configurações básicas
     */
    private function applyBasicConfig()
    {
        // Verificar se a classe existe antes de usar
        if (!class_exists('App\Helpers\SystemConfigHelper')) {
            // Fallback para Configuracao direto
            $timezone = Configuracao::get('timezone', 'America/Sao_Paulo');
            $locale = Configuracao::get('locale', 'pt-BR');
            $sessionLifetime = Configuracao::get('session_lifetime', 120);
            $cacheDriver = Configuracao::get('cache_driver', 'file');
            $logLevel = Configuracao::get('log_level', 'error');
        } else {
            // Usar SystemConfigHelper
            $timezone = SystemConfigHelper::get('timezone', 'America/Sao_Paulo');
            $locale = SystemConfigHelper::get('locale', 'pt-BR');
            $sessionLifetime = SystemConfigHelper::get('session_lifetime', 120);
            $cacheDriver = SystemConfigHelper::get('cache_driver', 'file');
            $logLevel = SystemConfigHelper::get('log_level', 'error');
        }

        // Aplicar timezone
        if ($timezone && $this->isValidTimezone($timezone)) {
            date_default_timezone_set($timezone);
            config(['app.timezone' => $timezone]);
        }

        // Aplicar configurações de idioma
        if ($locale) {
            app()->setLocale($locale);
        }

        // Aplicar configurações de sessão
        config(['session.lifetime' => $sessionLifetime]);

        // Aplicar configurações de cache
        config(['cache.default' => $cacheDriver]);

        // Aplicar configurações de log
        config(['logging.channels.single.level' => $logLevel]);
        
        // Configurar nível de log padrão para evitar logs desnecessários
        config(['logging.channels.daily.level' => 'error']);
        config(['logging.channels.syslog.level' => 'error']);
        config(['logging.channels.errorlog.level' => 'error']);
    }
    
    /**
     * Aplicar configurações de email
     */
    private function applyEmailConfig()
    {
        $mailHost = Configuracao::get('mail_host');
        if ($mailHost) {
            Config::set('mail.mailers.smtp.host', $mailHost);
        }
        
        $mailPort = Configuracao::get('mail_port');
        if ($mailPort) {
            Config::set('mail.mailers.smtp.port', $mailPort);
        }
        
        $mailUsername = Configuracao::get('mail_username');
        if ($mailUsername) {
            Config::set('mail.mailers.smtp.username', $mailUsername);
        }
        
        $mailPassword = Configuracao::get('mail_password');
        if ($mailPassword) {
            Config::set('mail.mailers.smtp.password', $mailPassword);
        }
        
        $mailEncryption = Configuracao::get('mail_encryption');
        if ($mailEncryption) {
            Config::set('mail.mailers.smtp.encryption', $mailEncryption);
        }
        
        $mailFromAddress = Configuracao::get('mail_from_address');
        if ($mailFromAddress) {
            Config::set('mail.from.address', $mailFromAddress);
        }
        
        $mailFromName = Configuracao::get('mail_from_name');
        if ($mailFromName) {
            Config::set('mail.from.name', $mailFromName);
        }
    }
    
    /**
     * Aplicar configurações de segurança
     */
    private function applySecurityConfig()
    {
        $sessionLifetime = Configuracao::get('session_lifetime');
        if ($sessionLifetime) {
            Config::set('session.lifetime', $sessionLifetime);
        }
        
        $maxLoginAttempts = Configuracao::get('max_login_attempts');
        if ($maxLoginAttempts) {
            Config::set('auth.max_attempts', $maxLoginAttempts);
        }
        
        $forceSSL = Configuracao::get('force_ssl');
        if ($forceSSL && $this->isSecure()) {
            Config::set('app.url', str_replace('http://', 'https://', config('app.url')));
        }
    }
    
    /**
     * Aplicar configurações de pagamento
     */
    private function applyPaymentConfig()
    {
        // Stripe
        $stripeKey = Configuracao::get('stripe_key');
        if ($stripeKey) {
            Config::set('services.stripe.key', $stripeKey);
        }
        
        $stripeSecret = Configuracao::get('stripe_secret');
        if ($stripeSecret) {
            Config::set('services.stripe.secret', $stripeSecret);
        }
        
        // Mercado Pago
        $mercadopagoKey = Configuracao::get('mercadopago_key');
        if ($mercadopagoKey) {
            Config::set('services.mercadopago.key', $mercadopagoKey);
        }
        
        $mercadopagoToken = Configuracao::get('mercadopago_token');
        if ($mercadopagoToken) {
            Config::set('services.mercadopago.token', $mercadopagoToken);
        }
    }
    
    /**
     * Aplicar configurações de cache
     */
    private function applyCacheConfig()
    {
        $cacheDriver = Configuracao::get('cache_driver');
        if ($cacheDriver) {
            Config::set('cache.default', $cacheDriver);
        }
        
        $sessionDriver = Configuracao::get('session_driver');
        if ($sessionDriver) {
            Config::set('session.driver', $sessionDriver);
        }
        
        $queueConnection = Configuracao::get('queue_connection');
        if ($queueConnection) {
            Config::set('queue.default', $queueConnection);
        }
    }
    
    /**
     * Verificar se a conexão é segura
     */
    private function isSecure()
    {
        return !$this->isLocalhost() && 
               (request()->isSecure() || 
                request()->header('X-Forwarded-Proto') === 'https');
    }
    
    /**
     * Verificar se é localhost
     */
    private function isLocalhost()
    {
        $host = request()->getHost();
        return in_array($host, ['localhost', '127.0.0.1', '::1']) || 
               str_starts_with($host, '192.168.') ||
               str_starts_with($host, '10.') ||
               str_starts_with($host, '172.');
    }

    /**
     * Verificar se o timezone é válido
     */
    private function isValidTimezone($timezone)
    {
        return in_array($timezone, timezone_identifiers_list());
    }
} 