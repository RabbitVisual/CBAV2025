<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Configuracao;

class SystemConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌐 Inicializando configurações do sistema...');

        // Configurações básicas
        $configuracoesBasicas = [
            'app_name' => 'CBAV CRM Ministerial',
            'app_description' => 'Sistema de gestão ministerial completo para igrejas',
            'contact_email' => 'contato@igreja.com',
            'contact_phone' => '(11) 99999-9999',
            'address' => 'Endereço da igreja',
            'timezone' => 'America/Sao_Paulo',
            'locale' => 'pt_BR',
        ];

        // Configurações de email
        $configuracoesEmail = [
            'mail_host' => config('mail.mailers.smtp.host'),
            'mail_port' => config('mail.mailers.smtp.port'),
            'mail_username' => config('mail.mailers.smtp.username'),
            'mail_password' => config('mail.mailers.smtp.password'),
            'mail_encryption' => config('mail.mailers.smtp.encryption'),
            'mail_from_address' => config('mail.from.address'),
            'mail_from_name' => config('mail.from.name'),
        ];

        // Configurações de segurança
        $configuracoesSeguranca = [
            'session_lifetime' => config('session.lifetime'),
            'max_login_attempts' => 5,
            'force_ssl' => false,
            'enable_2fa' => false,
            'password_min_length' => 8,
            'password_require_special' => true,
        ];

        // Configurações de pagamento
        $configuracoesPagamento = [
            'stripe_key' => '',
            'stripe_secret' => '',
            'stripe_enabled' => false,
            'mercadopago_key' => '',
            'mercadopago_token' => '',
            'mercadopago_enabled' => false,
            'pix_chave' => '',
            'pix_beneficiario' => '',
            'pix_enabled' => false,
        ];

        // Configurações de doação
        $configuracoesDoacao = [
            'doacao_valor_minimo' => 1.00,
            'doacao_valor_maximo' => 10000.00,
            'doacao_sem_login' => true,
            'doacao_ativa' => true,
        ];

        // Configurações de cache
        $configuracoesCache = [
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_connection' => config('queue.default'),
        ];

        // Configurações de backup
        $configuracoesBackup = [
            'backup_enabled' => true,
            'backup_frequency' => 'daily',
            'backup_retention' => 7,
        ];

        // Configurações de notificação
        $configuracoesNotificacao = [
            'notification_email_enabled' => true,
            'notification_sms_enabled' => false,
            'notification_push_enabled' => false,
        ];

        // Combinar todas as configurações
        $todasConfiguracoes = array_merge(
            $configuracoesBasicas,
            $configuracoesEmail,
            $configuracoesSeguranca,
            $configuracoesPagamento,
            $configuracoesDoacao,
            $configuracoesCache,
            $configuracoesBackup,
            $configuracoesNotificacao
        );

        // Salvar configurações
        foreach ($todasConfiguracoes as $chave => $valor) {
            Configuracao::set($chave, $valor);
            $this->command->line("✅ {$chave}: {$valor}");
        }

        $this->command->info('🎉 Configurações do sistema inicializadas com sucesso!');
    }
} 