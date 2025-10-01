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
        $this->command->info('🌐 Configurando sistema básico...');

        $configuracoes = [
            // Configurações básicas da aplicação
            ['chave' => 'app_name', 'valor' => 'CBAV CRM Ministerial', 'tipo' => 'string', 'descricao' => 'Nome da aplicação'],
            ['chave' => 'app_description', 'valor' => 'Sistema de gestão ministerial completo para igrejas', 'tipo' => 'string', 'descricao' => 'Descrição da aplicação'],
            ['chave' => 'app_version', 'valor' => '2.0.0', 'tipo' => 'string', 'descricao' => 'Versão da aplicação'],
            
            // Configurações de contato
            ['chave' => 'contact_email', 'valor' => 'contato@cbav.com', 'tipo' => 'string', 'descricao' => 'Email de contato'],
            ['chave' => 'contact_phone', 'valor' => '(11) 99999-9999', 'tipo' => 'string', 'descricao' => 'Telefone de contato'],
            ['chave' => 'contact_address', 'valor' => 'Rua da Avenida, 123 - Centro, São Paulo - SP', 'tipo' => 'string', 'descricao' => 'Endereço de contato'],
            
            // Configurações de localização
            ['chave' => 'timezone', 'valor' => 'America/Sao_Paulo', 'tipo' => 'string', 'descricao' => 'Fuso horário do sistema'],
            ['chave' => 'locale', 'valor' => 'pt_BR', 'tipo' => 'string', 'descricao' => 'Idioma padrão'],
            ['chave' => 'currency', 'valor' => 'BRL', 'tipo' => 'string', 'descricao' => 'Moeda padrão'],
            
            // Configurações de email
            ['chave' => 'mail_host', 'valor' => config('mail.mailers.smtp.host', 'smtp.gmail.com'), 'tipo' => 'string', 'descricao' => 'Servidor SMTP'],
            ['chave' => 'mail_port', 'valor' => config('mail.mailers.smtp.port', '587'), 'tipo' => 'string', 'descricao' => 'Porta SMTP'],
            ['chave' => 'mail_username', 'valor' => config('mail.mailers.smtp.username', ''), 'tipo' => 'string', 'descricao' => 'Usuário SMTP'],
            ['chave' => 'mail_password', 'valor' => config('mail.mailers.smtp.password', ''), 'tipo' => 'string', 'descricao' => 'Senha SMTP'],
            ['chave' => 'mail_encryption', 'valor' => config('mail.mailers.smtp.encryption', 'tls'), 'tipo' => 'string', 'descricao' => 'Criptografia SMTP'],
            ['chave' => 'mail_from_address', 'valor' => config('mail.from.address', 'noreply@cbav.com'), 'tipo' => 'string', 'descricao' => 'Email remetente'],
            ['chave' => 'mail_from_name', 'valor' => config('mail.from.name', 'CBAV Sistema'), 'tipo' => 'string', 'descricao' => 'Nome remetente'],
            
            // Configurações de segurança
            ['chave' => 'session_lifetime', 'valor' => '120', 'tipo' => 'integer', 'descricao' => 'Tempo de sessão em minutos'],
            ['chave' => 'max_login_attempts', 'valor' => '5', 'tipo' => 'integer', 'descricao' => 'Máximo de tentativas de login'],
            ['chave' => 'force_ssl', 'valor' => 'false', 'tipo' => 'boolean', 'descricao' => 'Forçar SSL'],
            ['chave' => 'enable_2fa', 'valor' => 'false', 'tipo' => 'boolean', 'descricao' => 'Habilitar autenticação de dois fatores'],
            ['chave' => 'password_min_length', 'valor' => '8', 'tipo' => 'integer', 'descricao' => 'Tamanho mínimo da senha'],
            ['chave' => 'password_require_special', 'valor' => 'true', 'tipo' => 'boolean', 'descricao' => 'Exigir caracteres especiais na senha'],
            
            // Configurações de cache
            ['chave' => 'cache_driver', 'valor' => config('cache.default', 'file'), 'tipo' => 'string', 'descricao' => 'Driver de cache'],
            ['chave' => 'session_driver', 'valor' => config('session.driver', 'file'), 'tipo' => 'string', 'descricao' => 'Driver de sessão'],
            ['chave' => 'queue_connection', 'valor' => config('queue.default', 'sync'), 'tipo' => 'string', 'descricao' => 'Conexão de fila'],
            
            // Configurações de backup
            ['chave' => 'backup_enabled', 'valor' => 'true', 'tipo' => 'boolean', 'descricao' => 'Habilitar backup automático'],
            ['chave' => 'backup_frequency', 'valor' => 'daily', 'tipo' => 'string', 'descricao' => 'Frequência de backup'],
            ['chave' => 'backup_retention', 'valor' => '7', 'tipo' => 'integer', 'descricao' => 'Dias de retenção de backup'],
            
            // Configurações de notificação
            ['chave' => 'notification_email_enabled', 'valor' => 'true', 'tipo' => 'boolean', 'descricao' => 'Habilitar notificações por email'],
            ['chave' => 'notification_sms_enabled', 'valor' => 'false', 'tipo' => 'boolean', 'descricao' => 'Habilitar notificações por SMS'],
            ['chave' => 'notification_push_enabled', 'valor' => 'false', 'tipo' => 'boolean', 'descricao' => 'Habilitar notificações push'],
            
            // Configurações de log
            ['chave' => 'log_level', 'valor' => 'error', 'tipo' => 'string', 'descricao' => 'Nível de log'],
            ['chave' => 'log_activity', 'valor' => 'true', 'tipo' => 'boolean', 'descricao' => 'Log de atividades'],
            ['chave' => 'log_audit', 'valor' => 'true', 'tipo' => 'boolean', 'descricao' => 'Log de auditoria'],
            
            // Configurações de manutenção
            ['chave' => 'maintenance_mode', 'valor' => 'false', 'tipo' => 'boolean', 'descricao' => 'Modo de manutenção'],
            ['chave' => 'maintenance_message', 'valor' => 'Sistema em manutenção. Volte em breve.', 'tipo' => 'string', 'descricao' => 'Mensagem de manutenção'],
        ];

        foreach ($configuracoes as $config) {
            Configuracao::updateOrCreate(
                ['chave' => $config['chave']],
                $config
            );
        }

        $this->command->info('✅ Configurações do sistema definidas');
    }
} 