<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Configuracao, Notification, User};
use Illuminate\Support\Facades\{Artisan, Storage, DB, Cache};
use Illuminate\Support\Facades\Log;

class SystemController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:system.access');
    }

    /**
     * Dashboard da gestão do sistema
     */
    public function index()
    {
        try {
            $estatisticas = [
                'total_usuarios' => User::count(),
                'usuarios_ativos' => User::where('ativo', true)->count(),
                'total_notificacoes' => \App\Models\Notification::count(),
                'notificacoes_nao_lidas' => \App\Models\Notification::where('status', 'sent')->count(),
                'espaco_disco' => $this->getEspacoDisco(),
                'ultimo_backup' => $this->getUltimoBackup(),
            ];

            $logsRecentes = $this->getUltimosLogs();
            $sistemaStatus = $this->getSistemaStatus();

            return view('admin.system.dashboard', compact('estatisticas', 'logsRecentes', 'sistemaStatus'));
        } catch (\Exception $e) {
            // Log do erro
            Log::error('Erro no dashboard do sistema: ' . $e->getMessage());

            // Retornar dados padrão em caso de erro
            $estatisticas = [
                'total_usuarios' => 0,
                'usuarios_ativos' => 0,
                'total_notificacoes' => 0,
                'notificacoes_nao_lidas' => 0,
                'espaco_disco' => $this->getEspacoDisco(),
                'ultimo_backup' => null,
            ];

            $logsRecentes = [];
            $sistemaStatus = [
                'cache' => false,
                'storage' => false,
                'database' => false,
            ];

            return view('admin.system.dashboard', compact('estatisticas', 'logsRecentes', 'sistemaStatus'));
        }
    }

    /**
     * Configurações gerais
     */
    public function settings(Request $request)
    {
        try {
            // Determinar aba ativa (da sessão, request ou padrão)
            $activeTab = session('active_tab', $request->get('tab', 'geral'));
            
            // Carregar todas as configurações do sistema
            $configuracoes = [
                // Configurações básicas
                'app_name' => Configuracao::get('app_name', config('app.name', 'CBAV')),
                'app_description' => Configuracao::get('app_description', ''),
                'app_logo' => Configuracao::get('app_logo', ''),
                'app_favicon' => Configuracao::get('app_favicon', ''),
                'contact_email' => Configuracao::get('contact_email', ''),
                'contact_phone' => Configuracao::get('contact_phone', ''),
                'address' => Configuracao::get('address', ''),
                'social_facebook' => Configuracao::get('social_facebook', ''),
                'social_instagram' => Configuracao::get('social_instagram', ''),
                'social_youtube' => Configuracao::get('social_youtube', ''),
                'timezone' => Configuracao::get('timezone', config('app.timezone', 'America/Sao_Paulo')),
                'locale' => Configuracao::get('locale', config('app.locale', 'pt_BR')),

                // Configurações de email
                'mail_host' => Configuracao::get('mail_host', config('mail.mailers.smtp.host')),
                'mail_port' => Configuracao::get('mail_port', config('mail.mailers.smtp.port')),
                'mail_username' => Configuracao::get('mail_username', config('mail.mailers.smtp.username')),
                'mail_password' => Configuracao::get('mail_password', config('mail.mailers.smtp.password')),
                'mail_encryption' => Configuracao::get('mail_encryption', config('mail.mailers.smtp.encryption')),
                'mail_from_address' => Configuracao::get('mail_from_address', config('mail.from.address')),
                'mail_from_name' => Configuracao::get('mail_from_name', config('mail.from.name')),

                // Configurações de segurança
                'session_lifetime' => Configuracao::get('session_lifetime', config('session.lifetime')),
                'max_login_attempts' => Configuracao::get('max_login_attempts', 5),
                'force_ssl' => Configuracao::get('force_ssl', false),
                'enable_2fa' => Configuracao::get('enable_2fa', false),
                '2fa_method' => Configuracao::get('2fa_method', 'totp'),
                '2fa_grace_period' => Configuracao::get('2fa_grace_period', 7),
                '2fa_remember_device' => Configuracao::get('2fa_remember_device', true),
                '2fa_backup_codes' => Configuracao::get('2fa_backup_codes', true),
                'password_min_length' => Configuracao::get('password_min_length', 8),
                'password_require_special' => Configuracao::get('password_require_special', true),

                // Configurações de pagamento (priorizar .env sobre banco)
                'stripe_key' => env('STRIPE_KEY', Configuracao::get('stripe_key', '')),
                'stripe_secret' => env('STRIPE_SECRET', Configuracao::get('stripe_secret', '')),
                'stripe_enabled' => filter_var(env('STRIPE_ENABLED', Configuracao::get('stripe_enabled', false)), FILTER_VALIDATE_BOOLEAN),
                'mercadopago_key' => env('MERCADOPAGO_PUBLIC_KEY', Configuracao::get('mercadopago_key', '')),
                'mercadopago_token' => env('MERCADOPAGO_ACCESS_TOKEN', Configuracao::get('mercadopago_token', '')),
                'mercadopago_enabled' => filter_var(env('MERCADOPAGO_ENABLED', Configuracao::get('mercadopago_enabled', false)), FILTER_VALIDATE_BOOLEAN),
                'pix_chave' => env('PIX_CHAVE', Configuracao::get('pix_chave', '')),
                'pix_beneficiario' => env('PIX_BENEFICIARIO', Configuracao::get('pix_beneficiario', '')),
                'pix_enabled' => filter_var(env('PIX_ENABLED', Configuracao::get('pix_enabled', false)), FILTER_VALIDATE_BOOLEAN),

                // Configurações de doação
                'doacao_valor_minimo' => Configuracao::get('doacao_valor_minimo', 1.00),
                'doacao_valor_maximo' => Configuracao::get('doacao_valor_maximo', 10000.00),
                'doacao_sem_login' => Configuracao::get('doacao_sem_login', true),
                'doacao_ativa' => Configuracao::get('doacao_ativa', true),

                // Configurações de cache
                'cache_driver' => Configuracao::get('cache_driver', config('cache.default')),
                'session_driver' => Configuracao::get('session_driver', config('session.driver')),
                'queue_connection' => Configuracao::get('queue_connection', config('queue.default')),

                // Configurações de backup
                'backup_enabled' => Configuracao::get('backup_enabled', true),
                'backup_frequency' => Configuracao::get('backup_frequency', 'daily'),
                'backup_retention' => Configuracao::get('backup_retention', 7),

                // Configurações de notificação
                'notification_email_enabled' => Configuracao::get('notification_email_enabled', true),
                'notification_sms_enabled' => Configuracao::get('notification_sms_enabled', false),
                'notification_push_enabled' => Configuracao::get('notification_push_enabled', false),
            ];

            // Log para debug
            Log::info('Configurações carregadas', [
                'total_configs' => count($configuracoes),
                'app_name' => $configuracoes['app_name']
            ]);

            return view('admin.system.settings.index', compact('configuracoes', 'activeTab'));
        } catch (\Exception $e) {
            // Log do erro
            Log::error('Erro nas configurações gerais: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            // Retornar dados padrão em caso de erro
            $configuracoes = [
                'app_name' => config('app.name'),
                'app_description' => '',
                'app_logo' => '',
                'app_favicon' => '',
                'contact_email' => '',
                'contact_phone' => '',
                'address' => '',
                'social_facebook' => '',
                'social_instagram' => '',
                'social_youtube' => '',
                'timezone' => config('app.timezone'),
                'locale' => config('app.locale'),
                'mail_host' => config('mail.mailers.smtp.host'),
                'mail_port' => config('mail.mailers.smtp.port'),
                'mail_username' => config('mail.mailers.smtp.username'),
                'mail_password' => '',
                'mail_encryption' => config('mail.mailers.smtp.encryption'),
                'mail_from_address' => config('mail.from.address'),
                'mail_from_name' => config('mail.from.name'),
                'session_lifetime' => config('session.lifetime'),
                'max_login_attempts' => 5,
                'force_ssl' => false,
                'enable_2fa' => false,
                '2fa_method' => 'totp',
                '2fa_grace_period' => 7,
                '2fa_remember_device' => true,
                '2fa_backup_codes' => true,
                'password_min_length' => 8,
                'password_require_special' => true,
                'stripe_key' => '',
                'stripe_secret' => '',
                'stripe_enabled' => false,
                'mercadopago_key' => '',
                'mercadopago_token' => '',
                'mercadopago_enabled' => false,
                'pix_chave' => '',
                'pix_beneficiario' => '',
                'pix_enabled' => false,
                'doacao_valor_minimo' => 1.00,
                'doacao_valor_maximo' => 10000.00,
                'doacao_sem_login' => true,
                'doacao_ativa' => true,
                'cache_driver' => config('cache.default'),
                'session_driver' => config('session.driver'),
                'queue_connection' => config('queue.default'),
                'backup_enabled' => true,
                'backup_frequency' => 'daily',
                'backup_retention' => 7,
                'notification_email_enabled' => true,
                'notification_sms_enabled' => false,
                'notification_push_enabled' => false,
            ];

            return view('admin.system.settings.index', compact('configuracoes'));
        }
    }

    /**
     * Salvar configurações
     */
    public function updateSettings(Request $request)
    {
        try {
            // Log para debug
            Log::info('Atualizando configurações do sistema', [
                'user_id' => auth()->id(),
                'data_keys' => array_keys($request->except(['_token', '_method']))
            ]);

            // Log dos dados recebidos para debug
            Log::info('Dados recebidos no updateSettings', [
                'app_name' => $request->input('app_name'),
                'timezone' => $request->input('timezone'),
                'locale' => $request->input('locale'),
                'all_data' => $request->except(['_token', '_method'])
            ]);

            // Validação condicional baseada na aba ativa
            $activeTab = $request->input('active_tab', 'geral');

            Log::info('Processando configurações', [
                'active_tab' => $activeTab,
                'app_name_present' => $request->has('app_name'),
                'timezone_present' => $request->has('timezone'),
                'locale_present' => $request->has('locale'),
            ]);

            $validationRules = [];

            // Regras para aba geral
            if ($activeTab === 'geral') {
                $validationRules = [
                    'app_name' => 'required|string|max:255',
                    'app_description' => 'nullable|string|max:1000',
                    'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'app_favicon' => 'nullable|image|mimes:ico,png|max:1024',
                    'contact_email' => 'nullable|email',
                    'contact_phone' => 'nullable|string|max:20',
                    'address' => 'nullable|string|max:500',
                    'social_facebook' => 'nullable|url',
                    'social_instagram' => 'nullable|url',
                    'social_youtube' => 'nullable|url',
                    'timezone' => 'required|string',
                    'locale' => 'required|string',
                ];
            }

            // Regras para outras abas (não incluem campos obrigatórios da aba geral)
            if ($activeTab !== 'geral') {
                $validationRules = [
                    'app_description' => 'nullable|string|max:1000',
                    'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'app_favicon' => 'nullable|image|mimes:ico,png|max:1024',
                    'contact_email' => 'nullable|email',
                    'contact_phone' => 'nullable|string|max:20',
                    'address' => 'nullable|string|max:500',
                    'social_facebook' => 'nullable|url',
                    'social_instagram' => 'nullable|url',
                    'social_youtube' => 'nullable|url',
                ];

                // Adicionar regras específicas para cada aba
                if ($activeTab === 'email') {
                    $validationRules = array_merge($validationRules, [
                        'mail_host' => 'nullable|string|max:255',
                        'mail_port' => 'nullable|integer|min:1|max:65535',
                        'mail_username' => 'nullable|email|max:255',
                        'mail_password' => 'nullable|string|max:255',
                        'mail_encryption' => 'nullable|string|max:10',
                        'mail_from_address' => 'nullable|email|max:255',
                        'mail_from_name' => 'nullable|string|max:255',
                    ]);
                }

                if ($activeTab === 'seguranca') {
                    $validationRules = array_merge($validationRules, [
                        'session_lifetime' => 'nullable|integer|min:30|max:1440',
                        'max_login_attempts' => 'nullable|integer|min:3|max:10',
                        'force_ssl' => 'nullable|boolean',
                        'enable_2fa' => 'nullable|boolean',
                        '2fa_method' => 'nullable|in:totp,sms,email',
                        '2fa_grace_period' => 'nullable|integer|min:0|max:30',
                        '2fa_remember_device' => 'nullable|boolean',
                        '2fa_backup_codes' => 'nullable|boolean',
                        'password_min_length' => 'nullable|integer|min:6|max:20',
                        'password_require_special' => 'nullable|boolean',
                    ]);
                }

                if ($activeTab === 'pagamento') {
                    $validationRules = array_merge($validationRules, [
                        'stripe_key' => 'nullable|string|max:255',
                        'stripe_secret' => 'nullable|string|max:255',
                        'stripe_enabled' => 'nullable|boolean',
                        'mercadopago_key' => 'nullable|string|max:255',
                        'mercadopago_token' => 'nullable|string|max:255',
                        'mercadopago_enabled' => 'nullable|boolean',
                        'pix_chave' => 'nullable|string|max:255',
                        'pix_beneficiario' => 'nullable|string|max:255',
                        'pix_enabled' => 'nullable|boolean',
                        'doacao_valor_minimo' => 'nullable|numeric|min:0.01',
                        'doacao_valor_maximo' => 'nullable|numeric|min:0.01',
                        'doacao_sem_login' => 'nullable|boolean',
                        'doacao_ativa' => 'nullable|boolean',
                    ]);
                }

                if ($activeTab === 'cache') {
                    $validationRules = array_merge($validationRules, [
                        'cache_driver' => 'nullable|string|max:50',
                        'session_driver' => 'nullable|string|max:50',
                        'queue_connection' => 'nullable|string|max:50',
                        'backup_enabled' => 'nullable|boolean',
                        'backup_frequency' => 'nullable|string|max:50',
                        'backup_retention' => 'nullable|integer|min:1|max:365',
                        'notification_email_enabled' => 'nullable|boolean',
                        'notification_sms_enabled' => 'nullable|boolean',
                        'notification_push_enabled' => 'nullable|boolean',
                    ]);
                }
            }

            $request->validate($validationRules, [

                // Configurações de email
                'mail_host' => 'nullable|string|max:255',
                'mail_port' => 'nullable|integer|min:1|max:65535',
                'mail_username' => 'nullable|email|max:255',
                'mail_password' => 'nullable|string|max:255',
                'mail_encryption' => 'nullable|string|max:10',
                'mail_from_address' => 'nullable|email|max:255',
                'mail_from_name' => 'nullable|string|max:255',

                // Configurações de segurança
                'session_lifetime' => 'nullable|integer|min:30|max:1440',
                'max_login_attempts' => 'nullable|integer|min:3|max:10',
                'force_ssl' => 'nullable|boolean',
                'enable_2fa' => 'nullable|boolean',
                '2fa_method' => 'nullable|in:totp,sms,email',
                '2fa_grace_period' => 'nullable|integer|min:0|max:30',
                '2fa_remember_device' => 'nullable|boolean',
                '2fa_backup_codes' => 'nullable|boolean',
                'password_min_length' => 'nullable|integer|min:6|max:20',
                'password_require_special' => 'nullable|boolean',

                // Configurações de pagamento
                'stripe_key' => 'nullable|string|max:255',
                'stripe_secret' => 'nullable|string|max:255',
                'stripe_enabled' => 'nullable|boolean',
                'mercadopago_key' => 'nullable|string|max:255',
                'mercadopago_token' => 'nullable|string|max:255',
                'mercadopago_enabled' => 'nullable|boolean',
                'pix_chave' => 'nullable|string|max:255',
                'pix_beneficiario' => 'nullable|string|max:255',
                'pix_enabled' => 'nullable|boolean',

                // Configurações de doação
                'doacao_valor_minimo' => 'nullable|numeric|min:0.01',
                'doacao_valor_maximo' => 'nullable|numeric|min:0.01',
                'doacao_sem_login' => 'nullable|boolean',
                'doacao_ativa' => 'nullable|boolean',

                // Configurações de cache
                'cache_driver' => 'nullable|string|max:50',
                'session_driver' => 'nullable|string|max:50',
                'queue_connection' => 'nullable|string|max:50',

                // Configurações de backup
                'backup_enabled' => 'nullable|boolean',
                'backup_frequency' => 'nullable|string|max:50',
                'backup_retention' => 'nullable|integer|min:1|max:365',

                // Configurações de notificação
                'notification_email_enabled' => 'nullable|boolean',
                'notification_sms_enabled' => 'nullable|boolean',
                'notification_push_enabled' => 'nullable|boolean',
            ], [
                'app_name.required' => 'O nome da aplicação é obrigatório.',
                'app_name.string' => 'O nome da aplicação deve ser um texto.',
                'app_name.max' => 'O nome da aplicação não pode ter mais de 255 caracteres.',
                'app_description.string' => 'A descrição deve ser um texto.',
                'app_description.max' => 'A descrição não pode ter mais de 1000 caracteres.',
                'app_logo.image' => 'O arquivo deve ser uma imagem.',
                'app_logo.mimes' => 'A imagem deve ser do tipo: jpeg, png, jpg, gif.',
                'app_logo.max' => 'A imagem não pode ter mais de 2MB.',
                'app_favicon.image' => 'O arquivo deve ser uma imagem.',
                'app_favicon.mimes' => 'A imagem deve ser do tipo: ico, png.',
                'app_favicon.max' => 'A imagem não pode ter mais de 1MB.',
                'contact_email.email' => 'O email de contato deve ser um email válido.',
                'contact_phone.string' => 'O telefone deve ser um texto.',
                'contact_phone.max' => 'O telefone não pode ter mais de 20 caracteres.',
                'address.string' => 'O endereço deve ser um texto.',
                'address.max' => 'O endereço não pode ter mais de 500 caracteres.',
                'social_facebook.url' => 'O link do Facebook deve ser uma URL válida.',
                'social_instagram.url' => 'O link do Instagram deve ser uma URL válida.',
                'social_youtube.url' => 'O link do YouTube deve ser uma URL válida.',
                'timezone.required' => 'O fuso horário é obrigatório.',
                'timezone.string' => 'O fuso horário deve ser um texto.',
                'locale.required' => 'O idioma é obrigatório.',
                'locale.string' => 'O idioma deve ser um texto.',
                'mail_host.string' => 'O servidor SMTP deve ser um texto.',
                'mail_port.integer' => 'A porta deve ser um número inteiro.',
                'mail_port.min' => 'A porta deve ser no mínimo 1.',
                'mail_port.max' => 'A porta deve ser no máximo 65535.',
                'mail_username.email' => 'O usuário deve ser um email válido.',
                'mail_encryption.string' => 'A criptografia deve ser um texto.',
                'mail_from_address.email' => 'O email de origem deve ser um email válido.',
                'mail_from_name.string' => 'O nome de origem deve ser um texto.',
                'session_lifetime.integer' => 'O tempo de sessão deve ser um número inteiro.',
                'session_lifetime.min' => 'O tempo de sessão deve ser no mínimo 30 minutos.',
                'session_lifetime.max' => 'O tempo de sessão deve ser no máximo 1440 minutos.',
                'max_login_attempts.integer' => 'O máximo de tentativas deve ser um número inteiro.',
                'max_login_attempts.min' => 'O máximo de tentativas deve ser no mínimo 3.',
                'max_login_attempts.max' => 'O máximo de tentativas deve ser no máximo 10.',
                'password_min_length.integer' => 'O comprimento mínimo da senha deve ser um número inteiro.',
                'password_min_length.min' => 'O comprimento mínimo da senha deve ser no mínimo 6.',
                'password_min_length.max' => 'O comprimento mínimo da senha deve ser no máximo 20.',
                'stripe_key.string' => 'A chave do Stripe deve ser um texto.',
                'stripe_secret.string' => 'A chave secreta do Stripe deve ser um texto.',
                'mercadopago_key.string' => 'A chave do Mercado Pago deve ser um texto.',
                'mercadopago_token.string' => 'O token do Mercado Pago deve ser um texto.',
                'pix_chave.string' => 'A chave PIX deve ser um texto.',
                'pix_beneficiario.string' => 'O nome do beneficiário deve ser um texto.',
                'doacao_valor_minimo.numeric' => 'O valor mínimo deve ser um número.',
                'doacao_valor_minimo.min' => 'O valor mínimo deve ser no mínimo R$ 0,01.',
                'doacao_valor_maximo.numeric' => 'O valor máximo deve ser um número.',
                'doacao_valor_maximo.min' => 'O valor máximo deve ser no mínimo R$ 0,01.',
                'cache_driver.string' => 'O driver de cache deve ser um texto.',
                'session_driver.string' => 'O driver de sessão deve ser um texto.',
                'queue_connection.string' => 'A conexão de fila deve ser um texto.',
                'backup_frequency.string' => 'A frequência de backup deve ser um texto.',
                'backup_retention.integer' => 'A retenção de backup deve ser um número inteiro.',
                'backup_retention.min' => 'A retenção de backup deve ser no mínimo 1 dia.',
                'backup_retention.max' => 'A retenção de backup deve ser no máximo 365 dias.',
            ]);

            // Salvar configurações básicas
            $configuracoesBasicas = [
                'app_name',
                'app_description',
                'contact_email',
                'contact_phone',
                'address',
                'social_facebook',
                'social_instagram',
                'social_youtube',
                'timezone',
                'locale'
            ];

            foreach ($configuracoesBasicas as $key) {
                if ($request->has($key)) {
                    Configuracao::set($key, $request->input($key));
                }
            }

            // Upload de imagens
            if ($request->hasFile('app_logo')) {
                $logoPath = $request->file('app_logo')->store('config', 'public');
                Configuracao::set('app_logo', $logoPath);

                Log::info('Logo atualizada', ['path' => $logoPath]);
            }

            if ($request->hasFile('app_favicon')) {
                $faviconPath = $request->file('app_favicon')->store('config', 'public');
                Configuracao::set('app_favicon', $faviconPath);

                Log::info('Favicon atualizado', ['path' => $faviconPath]);
            }

            // Salvar configurações de email
            $configuracoesEmail = [
                'mail_host',
                'mail_port',
                'mail_username',
                'mail_password',
                'mail_encryption',
                'mail_from_address',
                'mail_from_name'
            ];

            foreach ($configuracoesEmail as $key) {
                if ($request->has($key)) {
                    Configuracao::set($key, $request->input($key));
                }
            }

            // Salvar configurações de segurança
            $configuracoesSeguranca = [
                'session_lifetime',
                'max_login_attempts',
                'force_ssl',
                'enable_2fa',
                'password_min_length',
                'password_require_special'
            ];

            // Salvar configurações de 2FA
            $configuracoes2FA = [
                '2fa_method',
                '2fa_grace_period',
                '2fa_remember_device',
                '2fa_backup_codes'
            ];

            foreach ($configuracoesSeguranca as $key) {
                if ($request->has($key)) {
                    Configuracao::set($key, $request->input($key));
                }
            }

            foreach ($configuracoes2FA as $key) {
                if ($request->has($key)) {
                    Configuracao::set($key, $request->input($key));
                }
            }

            // Salvar configurações de pagamento
            $configuracoesPagamento = [
                'stripe_key',
                'stripe_secret',
                'stripe_enabled',
                'mercadopago_key',
                'mercadopago_token',
                'mercadopago_enabled',
                'pix_chave',
                'pix_beneficiario',
                'pix_enabled'
            ];

            foreach ($configuracoesPagamento as $key) {
                if ($request->has($key)) {
                    Configuracao::set($key, $request->input($key));
                }
            }

            // Atualizar arquivo .env com configurações de gateways
            $this->updateEnvGatewaySettings($request);

            // Salvar configurações de doação
            $configuracoesDoacao = [
                'doacao_valor_minimo',
                'doacao_valor_maximo',
                'doacao_sem_login',
                'doacao_ativa'
            ];

            foreach ($configuracoesDoacao as $key) {
                if ($request->has($key)) {
                    Configuracao::set($key, $request->input($key));
                }
            }

            // Salvar configurações de cache
            $configuracoesCache = [
                'cache_driver',
                'session_driver',
                'queue_connection'
            ];

            foreach ($configuracoesCache as $key) {
                if ($request->has($key)) {
                    Configuracao::set($key, $request->input($key));
                }
            }

            // Salvar configurações de backup
            $configuracoesBackup = [
                'backup_enabled',
                'backup_frequency',
                'backup_retention'
            ];

            foreach ($configuracoesBackup as $key) {
                if ($request->has($key)) {
                    Configuracao::set($key, $request->input($key));
                }
            }

            // Salvar configurações de notificação
            $configuracoesNotificacao = [
                'notification_email_enabled',
                'notification_sms_enabled',
                'notification_push_enabled'
            ];

            foreach ($configuracoesNotificacao as $key) {
                if ($request->has($key)) {
                    Configuracao::set($key, $request->input($key));
                }
            }

            // Aplicar fuso horário se foi alterado
            if ($request->has('timezone')) {
                $newTimezone = $request->input('timezone');
                $currentTimezone = Configuracao::get('timezone', config('app.timezone'));

                if ($newTimezone !== $currentTimezone) {
                    Log::info('Timezone alterado', [
                        'old' => $currentTimezone,
                        'new' => $newTimezone
                    ]);

                    // Aplicar novo timezone
                    \App\Helpers\SystemConfigHelper::applyTimezone($newTimezone);
                }
            }

            // Limpar cache para aplicar mudanças
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');

            Log::info('Configurações atualizadas com sucesso', [
                'user_id' => auth()->id(),
                'total_configs_saved' => count($request->except(['_token', '_method', 'app_logo', 'app_favicon']))
            ]);

            // Determinar qual aba estava ativa
            $activeTab = $request->input('active_tab', 'geral');

            return redirect()->route('admin.system.settings.index')
                ->with('success', 'Configurações atualizadas com sucesso! O sistema foi atualizado e o cache foi limpo.')
                ->with('active_tab', $activeTab);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erro de validação nas configurações', [
                'user_id' => auth()->id(),
                'errors' => $e->errors()
            ]);

            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Erro ao salvar configurações', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Erro ao salvar configurações: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Teste simples para verificar se o controller está funcionando
     */
    public function test()
    {
        file_put_contents(storage_path('logs/debug.log'), 'Método test chamado em: ' . now() . "\n", FILE_APPEND);
        return response()->json(['message' => 'Controller funcionando!', 'user' => auth()->user()->name ?? 'N/A']);
    }

    /**
     * Configurações da homepage
     */
    public function homeConfig()
    {
        try {
            $configuracoes = [
                // Informações da Igreja
                'igreja_nome' => Configuracao::get('igreja_nome', 'Congregação Batista Avenida'),
                'igreja_slogan' => Configuracao::get('igreja_slogan', 'Uma igreja comprometida com o amor de Cristo'),
                'igreja_endereco' => Configuracao::get('igreja_endereco', 'Endereço da igreja'),
                'igreja_telefone' => Configuracao::get('igreja_telefone', '(11) 99999-9999'),
                'igreja_email' => Configuracao::get('igreja_email', 'contato@igreja.com'),
                'igreja_website' => Configuracao::get('igreja_website', ''),
                'igreja_facebook' => Configuracao::get('igreja_facebook', ''),

                // Cores e Design
                'cor_primaria' => Configuracao::get('cor_primaria', '#1e40af'),
                'cor_secundaria' => Configuracao::get('cor_secundaria', '#3b82f6'),
                'cor_destaque' => Configuracao::get('cor_destaque', '#10b981'),
                'cor_texto' => Configuracao::get('cor_texto', '#1f2937'),

                // Logo
                'logo' => Configuracao::get('logo', ''),

                // Conteúdo da Página Inicial (Hero)
                'titulo_principal' => Configuracao::get('hero_titulo', 'Bem-vindo à Nossa Igreja'),
                'subtitulo' => Configuracao::get('hero_subtitulo', 'Uma comunidade de fé, amor e esperança. Venha fazer parte da nossa família!'),
                'descricao' => Configuracao::get('home_descricao', ''),
                'texto_botao' => Configuracao::get('home_botao_texto', 'Conheça Nossa Igreja'),
                'url_botao' => Configuracao::get('home_botao_link', '#sobre'),

                // Seções da Página - Carregar como array para compatibilidade com a view
                'secoes' => [
                    'sobre' => Configuracao::get('secao_sobre_ativa', '1') == '1',
                    'servicos' => Configuracao::get('secao_ministerios_ativa', '1') == '1',
                    'eventos' => Configuracao::get('secao_eventos_ativa', '1') == '1',
                    'contato' => Configuracao::get('secao_contato_ativa', '1') == '1',
                    'doacao' => Configuracao::get('secao_doacao_ativa', '1') == '1',
                    'aniversariantes' => Configuracao::get('secao_aniversariantes_ativa', '1') == '1',
                ],

                // Configurações individuais das seções (para compatibilidade)
                'secao_sobre_ativa' => Configuracao::get('secao_sobre_ativa', '1'),
                'secao_ministerios_ativa' => Configuracao::get('secao_ministerios_ativa', '1'),
                'secao_cultos_ativa' => Configuracao::get('secao_cultos_ativa', '1'),
                'secao_contato_ativa' => Configuracao::get('secao_contato_ativa', '1'),
                'secao_doacao_ativa' => Configuracao::get('secao_doacao_ativa', '1'),
                'secao_eventos_ativa' => Configuracao::get('secao_eventos_ativa', '1'),
                'secao_aniversariantes_ativa' => Configuracao::get('secao_aniversariantes_ativa', '1'),
                'aniversariantes_mostrar' => Configuracao::get('aniversariantes_mostrar', '1'),

                // SEO
                'meta_title' => Configuracao::get('meta_title', ''),
                'meta_description' => Configuracao::get('meta_description', ''),
                'meta_keywords' => Configuracao::get('meta_keywords', ''),

                // Aniversariantes
                'aniversariantes_titulo' => Configuracao::get('aniversariantes_titulo', 'Aniversariantes do Mês'),
                'aniversariantes_subtitulo' => Configuracao::get('aniversariantes_subtitulo', 'Celebrando a vida dos nossos membros'),

                // Escola Dominical
                'escola_dominical_titulo' => Configuracao::get('escola_dominical_titulo', 'Escola Dominical'),
                'escola_dominical_horario' => Configuracao::get('escola_dominical_horario', 'Domingo às 08:00h'),
                'escola_dominical_descricao' => Configuracao::get('escola_dominical_descricao', 'Venha estudar a Bíblia conosco! A Escola Dominical é um momento especial para aprender mais sobre a Palavra de Deus, crescer na fé e fortalecer nossa comunhão.'),
                'escola_dominical_classe1' => Configuracao::get('escola_dominical_classe1', 'Classes Infantis'),
                'escola_dominical_classe2' => Configuracao::get('escola_dominical_classe2', 'Classes de Jovens'),
                'escola_dominical_classe3' => Configuracao::get('escola_dominical_classe3', 'Classes de Adultos'),
                'escola_dominical_ativa' => Configuracao::get('escola_dominical_ativa', '1'),

                // Configurações do Header
                'header_logo_ativa' => Configuracao::get('header_logo_ativa', '1'),
                'header_nome_igreja_ativa' => Configuracao::get('header_nome_igreja_ativa', '1'),
                'header_slogan_ativa' => Configuracao::get('header_slogan_ativa', '1'),
                'header_menu_ativa' => Configuracao::get('header_menu_ativa', '1'),
                'header_area_usuario_ativa' => Configuracao::get('header_area_usuario_ativa', '1'),
                'header_texto_area_membro' => Configuracao::get('header_texto_area_membro', 'Área do Membro'),
                'header_link_sobre' => Configuracao::get('header_link_sobre', 'Sobre'),
                'header_link_ministerios' => Configuracao::get('header_link_ministerios', 'Ministérios'),
                'header_link_cultos' => Configuracao::get('header_link_cultos', 'Cultos'),
                'header_link_aniversariantes' => Configuracao::get('header_link_aniversariantes', 'Aniversariantes'),
                'header_link_eventos' => Configuracao::get('header_link_eventos', 'Eventos'),
                'header_link_doacao' => Configuracao::get('header_link_doacao', 'Doação'),
                'header_link_contato' => Configuracao::get('header_link_contato', 'Contato'),

                // Configurações do Footer
                'footer_ativa' => Configuracao::get('footer_ativa', '1'),
                'footer_descricao' => Configuracao::get('footer_descricao', 'Uma comunidade de fé dedicada ao amor de Cristo e ao serviço ao próximo. Venha fazer parte da nossa família!'),
                'footer_links_titulo' => Configuracao::get('footer_links_titulo', 'Links Rápidos'),
                'footer_contato_titulo' => Configuracao::get('footer_contato_titulo', 'Contato'),
                'footer_horarios_titulo' => Configuracao::get('footer_horarios_titulo', 'Horários'),
                'footer_copyright_texto' => Configuracao::get('footer_copyright_texto', 'Todos os direitos reservados.'),
                'footer_link_sobre' => Configuracao::get('footer_link_sobre', 'Sobre'),
                'footer_link_ministerios' => Configuracao::get('footer_link_ministerios', 'Ministérios'),
                'footer_link_cultos' => Configuracao::get('footer_link_cultos', 'Cultos'),
                'footer_link_eventos' => Configuracao::get('footer_link_eventos', 'Eventos'),
                'footer_link_aniversariantes' => Configuracao::get('footer_link_aniversariantes', 'Aniversariantes'),
                'footer_link_doacao' => Configuracao::get('footer_link_doacao', 'Doação'),
                'footer_redes_sociais_ativa' => Configuracao::get('footer_redes_sociais_ativa', '1'),
                'igreja_instagram' => Configuracao::get('igreja_instagram', ''),
                'igreja_youtube' => Configuracao::get('igreja_youtube', ''),
                'igreja_whatsapp' => Configuracao::get('igreja_whatsapp', ''),
                'footer_link_creditos_ativa' => Configuracao::get('footer_link_creditos_ativa', '1'),
                'footer_link_creditos_texto' => Configuracao::get('footer_link_creditos_texto', 'Reinan Rodrigues'),
                'footer_link_vertex_ativa' => Configuracao::get('footer_link_vertex_ativa', '1'),
                'footer_link_vertex_texto' => Configuracao::get('footer_link_vertex_texto', 'Vertex Solutions'),

                // Configurações de Contato
                'contato_titulo' => Configuracao::get('contato_titulo', ''),
                'contato_subtitulo' => Configuracao::get('contato_subtitulo', ''),
                'contato_ativa' => Configuracao::get('contato_ativa', '1'),

                // Configurações de Doação
                'doacao_titulo' => Configuracao::get('doacao_titulo', ''),
                'doacao_subtitulo' => Configuracao::get('doacao_subtitulo', ''),
                'doacao_dica_seguranca' => Configuracao::get('doacao_dica_seguranca', ''),
                'doacao_dica_comprovante' => Configuracao::get('doacao_dica_comprovante', ''),
                'doacao_dica_transparencia' => Configuracao::get('doacao_dica_transparencia', ''),

                // Horários dos Cultos
                'culto_domingo_manha_titulo' => Configuracao::get('culto_domingo_manha_titulo', 'Culto de Domingo - Manhã'),
                'culto_domingo_manha_horario' => Configuracao::get('culto_domingo_manha_horario', '09:00h'),
                'culto_domingo_manha_descricao' => Configuracao::get('culto_domingo_manha_descricao', 'Culto de adoração e pregação da Palavra de Deus'),
                'culto_domingo_manha_item1' => Configuracao::get('culto_domingo_manha_item1', 'Louvor e Adoração'),
                'culto_domingo_manha_item2' => Configuracao::get('culto_domingo_manha_item2', 'Pregação da Palavra'),
                'culto_domingo_manha_item3' => Configuracao::get('culto_domingo_manha_item3', 'Oração e Intercessão'),

                'culto_domingo_noite_titulo' => Configuracao::get('culto_domingo_noite_titulo', 'Culto de Domingo - Noite'),
                'culto_domingo_noite_horario' => Configuracao::get('culto_domingo_noite_horario', '18:00h'),
                'culto_domingo_noite_descricao' => Configuracao::get('culto_domingo_noite_descricao', 'Culto de celebração e edificação espiritual'),
                'culto_domingo_noite_item1' => Configuracao::get('culto_domingo_noite_item1', 'Louvor e Adoração'),
                'culto_domingo_noite_item2' => Configuracao::get('culto_domingo_noite_item2', 'Pregação da Palavra'),
                'culto_domingo_noite_item3' => Configuracao::get('culto_domingo_noite_item3', 'Oração e Intercessão'),

                'culto_quarta_titulo' => Configuracao::get('culto_quarta_titulo', 'Culto de Quarta-feira'),
                'culto_quarta_horario' => Configuracao::get('culto_quarta_horario', '19:30h'),
                'culto_quarta_descricao' => Configuracao::get('culto_quarta_descricao', 'Culto de oração e estudo bíblico'),
                'culto_quarta_item1' => Configuracao::get('culto_quarta_item1', 'Oração e Intercessão'),
                'culto_quarta_item2' => Configuracao::get('culto_quarta_item2', 'Estudo Bíblico'),
                'culto_quarta_item3' => Configuracao::get('culto_quarta_item3', 'Comunhão'),
            ];

            return view('admin.system.home-config.index', compact('configuracoes'));
        } catch (\Exception $e) {
            return view('admin.system.home-config.index', ['configuracoes' => []])
                ->with('error', 'Erro ao carregar configurações: ' . $e->getMessage());
        }
    }

    /**
     * Atualizar configurações da homepage
     */
    public function updateHomeConfig(Request $request)
    {
        Log::info('Método updateHomeConfig chamado');
        Log::info('Dados recebidos:', $request->all());

        try {
            $request->validate([
                'igreja_nome' => 'nullable|string|max:255',
                'igreja_slogan' => 'nullable|string|max:255',
                'igreja_endereco' => 'nullable|string|max:500',
                'igreja_telefone' => 'nullable|string|max:20',
                'igreja_email' => 'nullable|email|max:255',
                'igreja_website' => 'nullable|string|max:255',
                'igreja_facebook' => 'nullable|string|max:255',
                'cor_primaria' => 'nullable|string|max:7',
                'cor_secundaria' => 'nullable|string|max:7',
                'cor_destaque' => 'nullable|string|max:7',
                'cor_texto' => 'nullable|string|max:7',
                'titulo_principal' => 'nullable|string|max:255',
                'subtitulo' => 'nullable|string|max:255',
                'descricao' => 'nullable|string|max:1000',
                'texto_botao' => 'nullable|string|max:100',
                'url_botao' => 'nullable|string|max:255',
                'secoes' => 'nullable|array',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'meta_keywords' => 'nullable|string|max:500',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

                // Campos de texto da doação
                'doacao_titulo' => 'nullable|string|max:255',
                'doacao_subtitulo' => 'nullable|string|max:255',
                'doacao_dica_seguranca' => 'nullable|string|max:255',
                'doacao_dica_comprovante' => 'nullable|string|max:255',
                'doacao_dica_transparencia' => 'nullable|string|max:255',

                // Campos da Escola Dominical
                'escola_dominical_titulo' => 'nullable|string|max:255',
                'escola_dominical_horario' => 'nullable|string|max:255',
                'escola_dominical_descricao' => 'nullable|string|max:1000',
                'escola_dominical_classe1' => 'nullable|string|max:255',
                'escola_dominical_classe2' => 'nullable|string|max:255',
                'escola_dominical_classe3' => 'nullable|string|max:255',
                'escola_dominical_ativa' => 'nullable|string|max:1',

                // Configurações de Aniversariantes
                'aniversariantes_titulo' => 'nullable|string|max:255',
                'aniversariantes_subtitulo' => 'nullable|string|max:255',
                'secao_aniversariantes_ativa' => 'nullable|string|max:1',
                'aniversariantes_mostrar' => 'nullable|string|max:1',

                // Configurações do Header
                'header_logo_ativa' => 'nullable|string|max:1',
                'header_nome_igreja_ativa' => 'nullable|string|max:1',
                'header_slogan_ativa' => 'nullable|string|max:1',
                'header_menu_ativa' => 'nullable|string|max:1',
                'header_area_usuario_ativa' => 'nullable|string|max:1',
                'header_texto_area_membro' => 'nullable|string|max:255',
                'header_link_sobre' => 'nullable|string|max:255',
                'header_link_ministerios' => 'nullable|string|max:255',
                'header_link_cultos' => 'nullable|string|max:255',
                'header_link_aniversariantes' => 'nullable|string|max:255',
                'header_link_eventos' => 'nullable|string|max:255',
                'header_link_doacao' => 'nullable|string|max:255',
                'header_link_contato' => 'nullable|string|max:255',

                // Configurações do Footer
                'footer_ativa' => 'nullable|string|max:1',
                'footer_descricao' => 'nullable|string|max:1000',
                'footer_links_titulo' => 'nullable|string|max:255',
                'footer_contato_titulo' => 'nullable|string|max:255',
                'footer_horarios_titulo' => 'nullable|string|max:255',
                'footer_copyright_texto' => 'nullable|string|max:255',
                'footer_link_sobre' => 'nullable|string|max:255',
                'footer_link_ministerios' => 'nullable|string|max:255',
                'footer_link_cultos' => 'nullable|string|max:255',
                'footer_link_eventos' => 'nullable|string|max:255',
                'footer_link_aniversariantes' => 'nullable|string|max:255',
                'footer_link_doacao' => 'nullable|string|max:255',
                'footer_redes_sociais_ativa' => 'nullable|string|max:1',
                'footer_link_creditos_ativa' => 'nullable|string|max:1',
                'footer_link_creditos_texto' => 'nullable|string|max:255',
                'footer_link_vertex_ativa' => 'nullable|string|max:1',
                'footer_link_vertex_texto' => 'nullable|string|max:255',
                'footer_link_privacidade_ativa' => 'nullable|string|max:1',
                'footer_link_privacidade_texto' => 'nullable|string|max:255',
                'footer_link_termos_ativa' => 'nullable|string|max:1',
                'footer_link_termos_texto' => 'nullable|string|max:255',
                'footer_horario_domingo_manha' => 'nullable|string|max:255',
                'footer_horario_domingo_noite' => 'nullable|string|max:255',
                'footer_horario_quarta' => 'nullable|string|max:255',
                'footer_horario_escola_dominical' => 'nullable|string|max:255',

                // Campos de horários dos cultos
                'culto_domingo_manha_titulo' => 'nullable|string|max:255',
                'culto_domingo_manha_horario' => 'nullable|string|max:255',
                'culto_domingo_manha_descricao' => 'nullable|string|max:500',
                'culto_domingo_manha_item1' => 'nullable|string|max:255',
                'culto_domingo_manha_item2' => 'nullable|string|max:255',
                'culto_domingo_manha_item3' => 'nullable|string|max:255',
                'culto_domingo_noite_titulo' => 'nullable|string|max:255',
                'culto_domingo_noite_horario' => 'nullable|string|max:255',
                'culto_domingo_noite_descricao' => 'nullable|string|max:500',
                'culto_domingo_noite_item1' => 'nullable|string|max:255',
                'culto_domingo_noite_item2' => 'nullable|string|max:255',
                'culto_domingo_noite_item3' => 'nullable|string|max:255',
                'culto_quarta_titulo' => 'nullable|string|max:255',
                'culto_quarta_horario' => 'nullable|string|max:255',
                'culto_quarta_descricao' => 'nullable|string|max:500',
                'culto_quarta_item1' => 'nullable|string|max:255',
                'culto_quarta_item2' => 'nullable|string|max:255',
                'culto_quarta_item3' => 'nullable|string|max:255',

                // Configurações de contato
                'contato_titulo' => 'nullable|string|max:255',
                'contato_subtitulo' => 'nullable|string|max:255',
                'contato_ativa' => 'nullable|string|max:1',

                // Redes sociais
                'igreja_instagram' => 'nullable|string|max:255',
                'igreja_youtube' => 'nullable|string|max:255',
                'igreja_whatsapp' => 'nullable|string|max:255',
            ]);

            // Básico
            foreach (
                [
                    'igreja_nome',
                    'igreja_slogan',
                    'igreja_endereco',
                    'igreja_telefone',
                    'igreja_email',
                    'igreja_website',
                    'igreja_facebook'
                ] as $key
            ) {
                if ($request->filled($key)) Configuracao::set($key, $request->input($key));
            }

            // Cores
            foreach (['cor_primaria', 'cor_secundaria', 'cor_destaque', 'cor_texto'] as $key) {
                if ($request->filled($key)) Configuracao::set($key, $request->input($key));
            }

            // Conteúdo
            if ($request->filled('titulo_principal')) Configuracao::set('hero_titulo', $request->titulo_principal);
            if ($request->filled('subtitulo')) Configuracao::set('hero_subtitulo', $request->subtitulo);
            if ($request->filled('descricao')) Configuracao::set('home_descricao', $request->descricao);
            if ($request->filled('texto_botao')) Configuracao::set('home_botao_texto', $request->texto_botao);
            if ($request->filled('url_botao')) Configuracao::set('home_botao_link', $request->url_botao);

            // Seções ativas - Processar checkboxes individuais
            Configuracao::set('secao_sobre_ativa', $request->has('secoes') && in_array('sobre', $request->secoes) ? '1' : '0');
            Configuracao::set('secao_ministerios_ativa', $request->has('secoes') && in_array('servicos', $request->secoes) ? '1' : '0');
            Configuracao::set('secao_eventos_ativa', $request->has('secoes') && in_array('eventos', $request->secoes) ? '1' : '0');
            Configuracao::set('secao_contato_ativa', $request->has('secoes') && in_array('contato', $request->secoes) ? '1' : '0');
            Configuracao::set('secao_doacao_ativa', $request->has('secoes') && in_array('doacao', $request->secoes) ? '1' : '0');
            Configuracao::set('secao_aniversariantes_ativa', $request->has('secoes') && in_array('aniversariantes', $request->secoes) ? '1' : '0');

            // Configurações individuais de aniversariantes
            Configuracao::set('aniversariantes_mostrar', $request->has('aniversariantes_mostrar') ? '1' : '0');

            // SEO
            foreach (['meta_title', 'meta_description', 'meta_keywords'] as $key) {
                if ($request->filled($key)) Configuracao::set($key, $request->input($key));
            }



            // Logo
            if ($request->hasFile('logo')) {
                $imagePath = $request->file('logo')->store('config', 'public');
                Configuracao::set('logo', $imagePath);
            }

            // Textos da seção de doação
            foreach (['doacao_titulo', 'doacao_subtitulo', 'doacao_dica_seguranca', 'doacao_dica_comprovante', 'doacao_dica_transparencia'] as $key) {
                if ($request->filled($key)) Configuracao::set($key, $request->input($key));
            }

            // Configurações dos cards de doação
            foreach (
                [
                    'doacao_dizimo_titulo',
                    'doacao_dizimo_descricao',
                    'doacao_dizimo_botao',
                    'doacao_oferta_titulo',
                    'doacao_oferta_descricao',
                    'doacao_oferta_botao',
                    'doacao_campanhas_titulo',
                    'doacao_campanhas_descricao',
                    'doacao_campanhas_botao'
                ] as $key
            ) {
                if ($request->filled($key)) Configuracao::set($key, $request->input($key));
            }

            // Campos da Escola Dominical
            foreach (
                [
                    'escola_dominical_titulo',
                    'escola_dominical_horario',
                    'escola_dominical_descricao',
                    'escola_dominical_classe1',
                    'escola_dominical_classe2',
                    'escola_dominical_classe3'
                ] as $key
            ) {
                if ($request->filled($key)) Configuracao::set($key, $request->input($key));
            }

            // Configurações de Aniversariantes
            foreach (
                [
                    'aniversariantes_titulo',
                    'aniversariantes_subtitulo',
                    'secao_aniversariantes_ativa',
                    'aniversariantes_mostrar'
                ] as $key
            ) {
                if ($request->filled($key)) Configuracao::set($key, $request->input($key));
            }

            // Ativação da Escola Dominical
            Configuracao::set('escola_dominical_ativa', $request->has('escola_dominical_ativa') ? '1' : '0');

            // Campos de horários dos cultos
            foreach (
                [
                    'culto_domingo_manha_titulo',
                    'culto_domingo_manha_horario',
                    'culto_domingo_manha_descricao',
                    'culto_domingo_manha_item1',
                    'culto_domingo_manha_item2',
                    'culto_domingo_manha_item3',
                    'culto_domingo_noite_titulo',
                    'culto_domingo_noite_horario',
                    'culto_domingo_noite_descricao',
                    'culto_domingo_noite_item1',
                    'culto_domingo_noite_item2',
                    'culto_domingo_noite_item3',
                    'culto_quarta_titulo',
                    'culto_quarta_horario',
                    'culto_quarta_descricao',
                    'culto_quarta_item1',
                    'culto_quarta_item2',
                    'culto_quarta_item3'
                ] as $key
            ) {
                if ($request->filled($key)) Configuracao::set($key, $request->input($key));
            }

            // Configurações de contato
            foreach (['contato_titulo', 'contato_subtitulo'] as $key) {
                if ($request->filled($key)) Configuracao::set($key, $request->input($key));
            }
            Configuracao::set('contato_ativa', $request->has('contato_ativa') ? '1' : '0');

            // Redes sociais
            foreach (['igreja_instagram', 'igreja_youtube', 'igreja_whatsapp'] as $key) {
                if ($request->filled($key)) Configuracao::set($key, $request->input($key));
            }

            // Configurações do Header
            foreach (
                [
                    'header_texto_area_membro',
                    'header_link_sobre',
                    'header_link_ministerios',
                    'header_link_cultos',
                    'header_link_aniversariantes',
                    'header_link_eventos',
                    'header_link_doacao',
                    'header_link_contato'
                ] as $key
            ) {
                if ($request->filled($key)) Configuracao::set($key, $request->input($key));
            }

            // Checkboxes do Header
            Configuracao::set('header_logo_ativa', $request->has('header_logo_ativa') ? '1' : '0');
            Configuracao::set('header_nome_igreja_ativa', $request->has('header_nome_igreja_ativa') ? '1' : '0');
            Configuracao::set('header_slogan_ativa', $request->has('header_slogan_ativa') ? '1' : '0');
            Configuracao::set('header_menu_ativa', $request->has('header_menu_ativa') ? '1' : '0');
            Configuracao::set('header_area_usuario_ativa', $request->has('header_area_usuario_ativa') ? '1' : '0');

            // Configurações do Footer
            foreach (
                [
                    'footer_descricao',
                    'footer_links_titulo',
                    'footer_contato_titulo',
                    'footer_horarios_titulo',
                    'footer_copyright_texto',
                    'footer_link_sobre',
                    'footer_link_ministerios',
                    'footer_link_cultos',
                    'footer_link_eventos',
                    'footer_link_aniversariantes',
                    'footer_link_doacao',
                    'footer_link_creditos_texto',
                    'footer_link_vertex_texto',
                    'footer_horario_domingo_manha',
                    'footer_horario_domingo_noite',
                    'footer_horario_quarta',
                    'footer_horario_escola_dominical'
                ] as $key
            ) {
                if ($request->filled($key)) Configuracao::set($key, $request->input($key));
            }

            // Checkboxes do Footer
            Configuracao::set('footer_ativa', $request->has('footer_ativa') ? '1' : '0');
            Configuracao::set('footer_redes_sociais_ativa', $request->has('footer_redes_sociais_ativa') ? '1' : '0');
            Configuracao::set('footer_link_creditos_ativa', $request->has('footer_link_creditos_ativa') ? '1' : '0');
            Configuracao::set('footer_link_vertex_ativa', $request->has('footer_link_vertex_ativa') ? '1' : '0');

            // Ativação da Escola Dominical
            Configuracao::set('escola_dominical_ativa', $request->has('escola_dominical_ativa') ? '1' : '0');

            Log::info('Configurações da homepage atualizadas com sucesso');
            $activeTab = $request->input('active_tab', 'basico');
            return redirect()->route('admin.system.home-config.index')
                ->with('success', 'Configurações da homepage atualizadas com sucesso!')
                ->with('active_tab', $activeTab);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar configurações: ' . $e->getMessage());
            return redirect()->route('admin.system.home-config.index')
                ->with('error', 'Erro ao salvar configurações: ' . $e->getMessage());
        }
    }

    /**
     * Resetar configurações da homepage para valores padrão
     */
    public function resetHomeConfig()
    {
        try {
            // Executar o seeder para resetar as configurações
            $seeder = new \Database\Seeders\HomeConfigSeeder();
            $seeder->run();

            return redirect()->route('admin.system.home-config.index')
                ->with('success', 'Configurações resetadas para os valores padrão com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao resetar configurações: ' . $e->getMessage());

            return redirect()->route('admin.system.home-config.index')
                ->with('error', 'Erro ao resetar configurações. Tente novamente.');
        }
    }

    /**
     * Gestão de notificações
     */
    public function notifications(Request $request)
    {
        $query = Notificacao::with(['user', 'destinatario']);

        if ($request->filled('search')) {
            $query->where('titulo', 'like', '%' . $request->search . '%')
                ->orWhere('mensagem', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('status')) {
            if ($request->status === 'enviada') {
                $query->whereNotNull('enviada_em');
            } elseif ($request->status === 'pendente') {
                $query->whereNull('enviada_em');
            } elseif ($request->status === 'falha') {
                $query->where('lida', false)->whereNotNull('enviada_em');
            }
        }

        if ($request->filled('periodo')) {
            switch ($request->periodo) {
                case 'hoje':
                    $query->whereDate('created_at', today());
                    break;
                case 'semana':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'mes':
                    $query->whereMonth('created_at', now()->month);
                    break;
            }
        }

        $sort = $request->get('sort', 'created_desc');
        switch ($sort) {
            case 'created_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'priority_desc':
                $query->orderBy('prioridade', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $notificacoes = $query->paginate(20);

        // Estatísticas das notificações
        $estatisticas = [
            'total' => Notificacao::count(),
            'pendentes' => Notificacao::whereNull('enviada_em')->count(),
            'enviadas' => Notificacao::whereNotNull('enviada_em')->count(),
            'usuarios_ativos' => User::where('ativo', true)->count(),
        ];

        return view('admin.system.notifications.index', compact('notificacoes', 'estatisticas'));
    }

    /**
     * Criar notificação
     */
    public function createNotification()
    {
        $usuarios = User::where('ativo', true)->get();
        $membros = \App\Models\Membro::all();
        $ministerios = \App\Models\Ministerio::all();

        return view('admin.system.notifications.create', compact('usuarios', 'membros', 'ministerios'));
    }

    /**
     * Salvar notificação
     */
    public function storeNotification(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'mensagem' => 'required|string|max:1000',
            'tipo' => 'required|in:info,warning,success,error',
            'prioridade' => 'required|in:baixa,normal,alta,urgente',
            'destinatario_tipo' => 'required|in:usuario,membro,ministerio,todos',
            'destinatario_id' => 'nullable|integer',
            'agendada_para' => 'nullable|date|after:now',
        ]);

        // Preparar dados da notificação
        $dadosNotificacao = [
            'titulo' => $request->titulo,
            'mensagem' => $request->mensagem,
            'tipo' => $request->tipo,
            'prioridade' => $request->prioridade,
            'destinatario_tipo' => $request->destinatario_tipo,
            'destinatario_id' => $request->destinatario_id,
            'enviada_por' => auth()->id(),
            'agendada_para' => $request->agendada_para,
        ];

        // Se for para um usuário específico, associar o user_id
        if ($request->destinatario_tipo === 'usuario' && $request->destinatario_id) {
            $dadosNotificacao['user_id'] = $request->destinatario_id;
        }

        // Se for para um membro específico, buscar o usuário associado
        if ($request->destinatario_tipo === 'membro' && $request->destinatario_id) {
            $membro = \App\Models\Membro::find($request->destinatario_id);
            if ($membro && $membro->email) {
                $user = \App\Models\User::where('email', $membro->email)->first();
                if ($user) {
                    $dadosNotificacao['user_id'] = $user->id;
                }
            }
            // Adicionar dados extras para membro
            $dadosNotificacao['dados_extras'] = ['membro_id' => $request->destinatario_id];
        }

        $notificacao = Notificacao::create($dadosNotificacao);

        return redirect()->route('admin.system.notifications.index')
            ->with('success', 'Notificação criada com sucesso!');
    }

    /**
     * Mostrar notificação
     */
    public function showNotification(Notificacao $notificacao)
    {
        $notificacao->load(['user', 'remetente']);
        return view('admin.system.notifications.show', compact('notificacao'));
    }

    /**
     * Editar notificação
     */
    public function editNotification(Notificacao $notificacao)
    {
        $usuarios = User::where('ativo', true)->get();
        $membros = \App\Models\Membro::all();
        $ministerios = \App\Models\Ministerio::all();

        return view('admin.system.notifications.edit', compact('notificacao', 'usuarios', 'membros', 'ministerios'));
    }

    /**
     * Atualizar notificação
     */
    public function updateNotification(Request $request, Notificacao $notificacao)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'mensagem' => 'required|string|max:1000',
            'tipo' => 'required|in:info,warning,success,error',
            'prioridade' => 'required|in:baixa,normal,alta,urgente',
            'destinatario_tipo' => 'required|in:usuario,membro,ministerio,todos',
            'destinatario_id' => 'nullable|integer',
            'agendada_para' => 'nullable|date|after:now',
        ]);

        // Preparar dados da notificação
        $dadosNotificacao = [
            'titulo' => $request->titulo,
            'mensagem' => $request->mensagem,
            'tipo' => $request->tipo,
            'prioridade' => $request->prioridade,
            'destinatario_tipo' => $request->destinatario_tipo,
            'destinatario_id' => $request->destinatario_id,
            'agendada_para' => $request->agendada_para,
        ];

        // Se for para um usuário específico, associar o user_id
        if ($request->destinatario_tipo === 'usuario' && $request->destinatario_id) {
            $dadosNotificacao['user_id'] = $request->destinatario_id;
        }

        // Se for para um membro específico, buscar o usuário associado
        if ($request->destinatario_tipo === 'membro' && $request->destinatario_id) {
            $membro = \App\Models\Membro::find($request->destinatario_id);
            if ($membro && $membro->email) {
                $user = \App\Models\User::where('email', $membro->email)->first();
                if ($user) {
                    $dadosNotificacao['user_id'] = $user->id;
                }
            }
            // Adicionar dados extras para membro
            $dadosNotificacao['dados_extras'] = ['membro_id' => $request->destinatario_id];
        }

        $notificacao->update($dadosNotificacao);

        return redirect()->route('admin.system.notifications.index')
            ->with('success', 'Notificação atualizada com sucesso!');
    }

    /**
     * Excluir notificação
     */
    public function deleteNotification($id)
    {
        try {
            Log::info("=== INÍCIO deleteNotification ===");
            Log::info("ID recebido: " . $id);
            Log::info("Tipo do ID: " . gettype($id));

            // Buscar a notificação manualmente
            $notificacao = Notificacao::find($id);

            if (!$notificacao) {
                Log::error("Notificação com ID {$id} não encontrada");
                return redirect()->route('admin.system.notifications.index')
                    ->with('error', 'Notificação não encontrada!');
            }

            Log::info("Notificação encontrada: ID {$notificacao->id} - {$notificacao->titulo}");

            $notificacao->delete();

            Log::info("Notificação excluída com sucesso");

            return redirect()->route('admin.system.notifications.index')
                ->with('success', 'Notificação excluída com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao excluir notificação: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());

            return redirect()->route('admin.system.notifications.index')
                ->with('error', 'Erro ao excluir notificação: ' . $e->getMessage());
        }
    }

    /**
     * Enviar notificação
     */
    public function sendNotification(Notificacao $notificacao)
    {
        try {
            // Aqui você implementaria a lógica de envio real
            // Por enquanto, apenas marcamos como enviada
            $notificacao->update([
                'enviada_em' => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Notificação enviada com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao enviar notificação: ' . $e->getMessage()]);
        }
    }

    /**
     * Enviar notificação de teste
     */
    public function testNotification()
    {
        try {
            // Criar notificação de teste
            $notificacao = Notificacao::create([
                'titulo' => 'Notificação de Teste',
                'mensagem' => 'Esta é uma notificação de teste do sistema.',
                'tipo' => 'info',
                'prioridade' => 'normal',
                'destinatario_tipo' => 'todos',
                'enviada_por' => auth()->id(),
                'enviada_em' => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Notificação de teste enviada com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao enviar notificação de teste: ' . $e->getMessage()]);
        }
    }

    /**
     * Logs do sistema
     */
    public function logs()
    {
        $logFiles = [
            'laravel.log' => storage_path('logs/laravel.log'),
            'error.log' => storage_path('logs/error.log'),
        ];

        $logs = [];
        $totalSize = 0;
        $totalErrors = 0;
        $totalWarnings = 0;

        // Obter filtros da requisição
        $request = request();
        $arquivo = $request->get('arquivo', 'laravel.log');
        $nivel = $request->get('nivel');
        $search = $request->get('search');
        $data = $request->get('data');
        $linhas = $request->get('linhas', 100);

        // Converter linhas para número
        if ($linhas === 'all') {
            $linhas = PHP_INT_MAX;
        } else {
            $linhas = (int) $linhas;
        }

        foreach ($logFiles as $name => $path) {
            if (file_exists($path)) {
                $size = filesize($path);
                $totalSize += $size;

                $lines = $this->getLastLines($path, $linhas);
                $processedLines = [];

                // Processar cada linha do log
                foreach ($lines as $index => $line) {
                    $processedLine = $this->parseLogLine($line);
                    if ($processedLine) {
                        // Aplicar filtros
                        if ($this->shouldIncludeLog($processedLine, $nivel, $search, $data)) {
                            $processedLines[] = $processedLine;

                            // Contar erros e warnings
                            if (strpos($line, '.ERROR') !== false) {
                                $totalErrors++;
                            }
                            if (strpos($line, '.WARNING') !== false) {
                                $totalWarnings++;
                            }
                        }
                    }
                }

                $logs[$name] = [
                    'size' => $size,
                    'last_modified' => filemtime($path),
                    'lines' => $processedLines
                ];
            }
        }

        // Estatísticas para a view
        $estatisticas = [
            'total' => count($logs),
            'warnings' => $totalWarnings,
            'errors' => $totalErrors,
            'tamanho' => $this->formatBytes($totalSize)
        ];

        // Lista de arquivos disponíveis
        $arquivos = array_keys($logFiles);

        return view('admin.system.logs.index', compact('logs', 'estatisticas', 'arquivos'));
    }

    /**
     * Verificar se o log deve ser incluído baseado nos filtros
     */
    private function shouldIncludeLog($log, $nivel = null, $search = null, $data = null)
    {
        // Filtrar por nível
        if ($nivel && $log['level'] !== $nivel) {
            return false;
        }

        // Filtrar por busca
        if ($search) {
            $searchLower = strtolower($search);
            $messageLower = strtolower($log['message']);
            $contextLower = strtolower($log['context'] ?? '');

            if (
                strpos($messageLower, $searchLower) === false &&
                strpos($contextLower, $searchLower) === false
            ) {
                return false;
            }
        }

        // Filtrar por data
        if ($data) {
            $logDate = date('Y-m-d', strtotime($log['datetime']));
            if ($logDate !== $data) {
                return false;
            }
        }

        // Filtrar logs desnecessários
        $excludedPatterns = [
            'Timezone aplicado via middleware',
            'Timezone aplicado',
            'Debug:',
            'DEBUG:',
            'Info:',
            'INFO:'
        ];

        foreach ($excludedPatterns as $pattern) {
            if (strpos($log['message'], $pattern) !== false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Limpar logs
     */
    public function clearLogs()
    {
        try {
            $logFiles = [
                storage_path('logs/laravel.log'),
                storage_path('logs/error.log'),
            ];

            $clearedCount = 0;
            foreach ($logFiles as $path) {
                if (file_exists($path)) {
                    file_put_contents($path, '');
                    $clearedCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Logs limpos com sucesso! ({$clearedCount} arquivos)",
                'cleared_count' => $clearedCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao limpar logs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar logs
     */
    public function exportLogs(Request $request)
    {
        $logFiles = [
            'laravel.log' => storage_path('logs/laravel.log'),
            'error.log' => storage_path('logs/error.log'),
        ];

        $arquivo = $request->get('arquivo', 'laravel.log');
        $path = $logFiles[$arquivo] ?? storage_path('logs/laravel.log');

        if (!file_exists($path)) {
            return response()->json(['error' => 'Arquivo de log não encontrado'], 404);
        }

        $filename = 'logs_' . $arquivo . '_' . now()->format('Y-m-d_H-i-s') . '.txt';

        return response()->download($path, $filename, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }

    /**
     * Mostrar detalhes do log
     */
    public function showLog(Request $request)
    {
        try {
            $logId = $request->get('id');
            $arquivo = $request->get('arquivo', 'laravel.log');
            $path = storage_path('logs/' . $arquivo);

            if (!file_exists($path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Arquivo de log não encontrado'
                ], 404);
            }

            // Buscar linha específica do log
            $lines = file($path, FILE_IGNORE_NEW_LINES);
            $logLine = null;
            $logIndex = null;

            // Se o ID for numérico, usar como índice
            if (is_numeric($logId) && isset($lines[$logId])) {
                $logLine = $lines[$logId];
                $logIndex = $logId;
            } else {
                // Se não for numérico, procurar por uma linha que contenha o ID
                foreach ($lines as $index => $line) {
                    if (strpos($line, $logId) !== false) {
                        $logLine = $line;
                        $logIndex = $index;
                        break;
                    }
                }
            }

            if (!$logLine) {
                return response()->json([
                    'success' => false,
                    'message' => 'Log não encontrado'
                ], 404);
            }

            $parsedLog = $this->parseLogLine($logLine);

            // Gerar HTML para o modal
            $html = view('admin.system.logs.detail', [
                'log' => $parsedLog,
                'raw' => $logLine,
                'id' => $logIndex
            ])->render();

            return response()->json([
                'success' => true,
                'content' => $html
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar log: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Backup do sistema
     */
    public function backup()
    {
        $backupPath = storage_path('backups');
        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        $filename = 'backup_' . now()->format('Y-m-d_H-i-s') . '.sql';
        $filepath = $backupPath . '/' . $filename;

        // Backup do banco de dados
        $command = sprintf(
            'mysqldump -u%s -p%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            $filepath
        );

        exec($command);

        return redirect()->route('admin.system.index')
            ->with('success', 'Backup criado com sucesso!');
    }

    /**
     * Limpar cache
     */
    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');

            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Cache limpo com sucesso!']);
            }

            return redirect()->route('admin.system.index')
                ->with('success', 'Cache limpo com sucesso!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Erro ao limpar cache: ' . $e->getMessage()]);
            }

            return redirect()->route('admin.system.index')
                ->with('error', 'Erro ao limpar cache: ' . $e->getMessage());
        }
    }

    /**
     * Testar cache
     */
    public function testCache()
    {
        try {
            $startTime = microtime(true);
            
            // Testar escrita no cache
            $testKey = 'cache_test_' . time();
            $testValue = 'test_value_' . rand(1000, 9999);
            
            Cache::put($testKey, $testValue, 60);
            
            // Testar leitura do cache
            $retrievedValue = Cache::get($testKey);
            
            // Limpar teste
            Cache::forget($testKey);
            
            $endTime = microtime(true);
            $responseTime = round(($endTime - $startTime) * 1000, 2);
            
            if ($retrievedValue === $testValue) {
                return response()->json([
                    'success' => true,
                    'message' => 'Teste de cache bem-sucedido!',
                    'driver' => config('cache.default'),
                    'status' => 'Funcionando',
                    'response_time' => $responseTime
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Falha na verificação do cache'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao testar cache: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Limpar backups antigos
     */
    public function cleanOldBackups()
    {
        try {
            $backupPath = storage_path('backups');
            $retentionDays = Configuracao::get('backup_retention', 7);
            
            if (!is_dir($backupPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Diretório de backup não encontrado'
                ]);
            }
            
            $files = glob($backupPath . '/*.sql');
            $removedCount = 0;
            $freedSpace = 0;
            $cutoffTime = time() - ($retentionDays * 24 * 60 * 60);
            
            foreach ($files as $file) {
                if (filemtime($file) < $cutoffTime) {
                    $fileSize = filesize($file);
                    if (unlink($file)) {
                        $removedCount++;
                        $freedSpace += $fileSize;
                    }
                }
            }
            
            $freedSpaceFormatted = $this->formatBytes($freedSpace);
            
            return response()->json([
                'success' => true,
                'message' => 'Limpeza de backups concluída com sucesso!',
                'removed_count' => $removedCount,
                'freed_space' => $freedSpaceFormatted
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao limpar backups: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Executar backup do banco de dados
     */
    public function runBackup()
    {
        try {
            $backupPath = storage_path('app/backups');
            
            // Criar diretório se não existir
            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0755, true);
            }
            
            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $filepath = $backupPath . '/' . $filename;
            
            // Configurações do banco
            $host = config('database.connections.mysql.host');
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            
            // Comando mysqldump
            $command = "mysqldump --host={$host} --user={$username} --password={$password} {$database} > {$filepath}";
            
            // Executar backup
            exec($command, $output, $returnCode);
            
            if ($returnCode === 0 && file_exists($filepath)) {
                $fileSize = $this->formatBytes(filesize($filepath));
                
                return response()->json([
                    'success' => true,
                    'message' => 'Backup criado com sucesso!',
                    'filename' => $filename,
                    'size' => $fileSize
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao criar backup do banco de dados.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao executar backup: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Listar backups disponíveis
     */
    public function listBackups()
    {
        try {
            $backupPath = storage_path('app/backups');
            
            if (!is_dir($backupPath)) {
                return response()->json([
                    'success' => true,
                    'backups' => [],
                    'message' => 'Diretório de backup não encontrado.'
                ]);
            }
            
            $files = glob($backupPath . '/*.sql');
            $backups = [];
            
            foreach ($files as $file) {
                $filename = basename($file);
                $size = $this->formatBytes(filesize($file));
                $date = date('d/m/Y H:i:s', filemtime($file));
                
                $backups[] = [
                    'name' => $filename,
                    'size' => $size,
                    'date' => $date,
                    'path' => $file
                ];
            }
            
            // Ordenar por data (mais recente primeiro)
            usort($backups, function($a, $b) {
                return filemtime($b['path']) - filemtime($a['path']);
            });
            
            return response()->json([
                'success' => true,
                'backups' => $backups
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao listar backups: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Testar configuração de backup
     */
    public function testBackup()
    {
        try {
            $backupPath = storage_path('app/backups');
            
            // Verificar se o diretório existe
            if (!is_dir($backupPath)) {
                mkdir($backupPath, 0755, true);
            }
            
            // Verificar permissões de escrita
            if (!is_writable($backupPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Diretório de backup não tem permissão de escrita.'
                ]);
            }
            
            // Verificar espaço disponível
            $freeSpace = disk_free_space($backupPath);
            $freeSpaceFormatted = $this->formatBytes($freeSpace);
            
            // Verificar configuração do banco de dados
            $dbConfig = config('database.connections.mysql');
            if (!$dbConfig['host'] || !$dbConfig['database']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Configuração do banco de dados incompleta.'
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Configuração de backup válida!',
                'path' => $backupPath,
                'permissions' => 'Escrita permitida',
                'free_space' => $freeSpaceFormatted,
                'database' => $dbConfig['database']
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao testar configuração: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Formatar bytes em formato legível
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Manutenção do sistema
     */
    public function maintenance()
    {
        $status = [
            'cache_status' => $this->verificarCacheStatus(),
            'storage_status' => $this->verificarStorageStatus(),
            'database_status' => $this->verificarDatabaseStatus(),
        ];

        return view('admin.system.maintenance.index', compact('status'));
    }

    /**
     * Ativar modo manutenção
     */
    public function enableMaintenance()
    {
        Artisan::call('down', ['--message' => 'Sistema em manutenção']);

        return redirect()->route('admin.system.maintenance.index')
            ->with('success', 'Modo manutenção ativado!');
    }

    /**
     * Desativar modo manutenção
     */
    public function disableMaintenance()
    {
        Artisan::call('up');

        return redirect()->route('admin.system.maintenance.index')
            ->with('success', 'Modo manutenção desativado!');
    }

    /**
     * Obter últimas linhas do arquivo
     */
    private function getLastLines($file, $lines = 100)
    {
        if (!file_exists($file)) {
            return [];
        }

        $file = new \SplFileObject($file);
        $file->seek(PHP_INT_MAX);
        $totalLines = $file->key();

        $start = max(0, $totalLines - $lines);
        $file->seek($start);

        $lines = [];
        while (!$file->eof()) {
            $lines[] = $file->current();
            $file->next();
        }

        return array_filter($lines);
    }

    /**
     * Processar linha do log
     */
    private function parseLogLine($line)
    {
        if (empty(trim($line))) {
            return null;
        }

        // Padrão para logs do Laravel: [2024-01-01 12:00:00] local.ERROR: Mensagem
        if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+)\.(\w+): (.+)$/', $line, $matches)) {
            $datetime = $matches[1];
            $environment = $matches[2];
            $level = strtolower($matches[3]);
            $message = $matches[4];

            // Extrair contexto JSON se existir
            $context = null;
            if (preg_match('/^(.+?)(\{.+?\})?$/', $message, $contextMatches)) {
                $message = trim($contextMatches[1]);
                if (isset($contextMatches[2])) {
                    $context = json_decode($contextMatches[2], true);
                }
            }

            return [
                'id' => uniqid(),
                'datetime' => $datetime,
                'environment' => $environment,
                'level' => $level,
                'message' => $message,
                'level_color' => $this->getLevelColor($level),
                'level_icon' => $this->getLevelIcon($level),
                'context' => $context,
                'raw' => $line
            ];
        }

        // Padrão alternativo para outros tipos de log
        if (preg_match('/^(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}) (\w+): (.+)$/', $line, $matches)) {
            $datetime = $matches[1];
            $level = strtolower($matches[2]);
            $message = $matches[3];

            return [
                'id' => uniqid(),
                'datetime' => $datetime,
                'environment' => 'unknown',
                'level' => $level,
                'message' => $message,
                'level_color' => $this->getLevelColor($level),
                'level_icon' => $this->getLevelIcon($level),
                'context' => 'unknown',
                'raw' => $line
            ];
        }

        // Padrão para logs com stack trace
        if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+)\.(\w+): (.+?)(\n.*)?$/s', $line, $matches)) {
            $datetime = $matches[1];
            $environment = $matches[2];
            $level = strtolower($matches[3]);
            $message = $matches[4];
            $stackTrace = isset($matches[5]) ? trim($matches[5]) : null;

            return [
                'id' => uniqid(),
                'datetime' => $datetime,
                'environment' => $environment,
                'level' => $level,
                'message' => $message,
                'level_color' => $this->getLevelColor($level),
                'level_icon' => $this->getLevelIcon($level),
                'context' => $environment,
                'stack_trace' => $stackTrace,
                'raw' => $line
            ];
        }

        // Se não conseguir fazer parse, retornar como linha simples
        return [
            'id' => uniqid(),
            'datetime' => now()->format('Y-m-d H:i:s'),
            'environment' => 'unknown',
            'level' => 'info',
            'message' => trim($line),
            'level_color' => 'bg-blue-100 text-blue-800',
            'level_icon' => 'fas fa-info-circle',
            'context' => 'raw',
            'raw' => $line
        ];
    }

    /**
     * Obter cor do nível do log
     */
    private function getLevelColor($level)
    {
        return [
            'emergency' => 'bg-red-100 text-red-800',
            'alert' => 'bg-red-100 text-red-800',
            'critical' => 'bg-red-100 text-red-800',
            'error' => 'bg-red-100 text-red-800',
            'warning' => 'bg-yellow-100 text-yellow-800',
            'notice' => 'bg-blue-100 text-blue-800',
            'info' => 'bg-blue-100 text-blue-800',
            'debug' => 'bg-gray-100 text-gray-800',
        ][$level] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Obter ícone do nível do log
     */
    private function getLevelIcon($level)
    {
        return [
            'emergency' => 'fas fa-exclamation-circle',
            'alert' => 'fas fa-exclamation-triangle',
            'critical' => 'fas fa-times-circle',
            'error' => 'fas fa-times-circle',
            'warning' => 'fas fa-exclamation-triangle',
            'notice' => 'fas fa-info-circle',
            'info' => 'fas fa-info-circle',
            'debug' => 'fas fa-bug',
        ][$level] ?? 'fas fa-info-circle';
    }

    /**
     * Obter espaço em disco
     */
    private function getEspacoDisco()
    {
        $total = disk_total_space(storage_path());
        $free = disk_free_space(storage_path());
        $used = $total - $free;
        $percent = round(($used / $total) * 100, 2);

        return [
            'total' => $this->formatBytes($total),
            'used' => $this->formatBytes($used),
            'free' => $this->formatBytes($free),
            'percent' => $percent
        ];
    }

    /**
     * Obter último backup
     */
    private function getUltimoBackup()
    {
        $backupPath = storage_path('backups');
        if (!is_dir($backupPath)) {
            return null;
        }

        $files = glob($backupPath . '/*.sql');
        if (empty($files)) {
            return null;
        }

        $latest = max(array_map('filemtime', $files));
        return date('Y-m-d H:i:s', $latest);
    }

    /**
     * Obter logs recentes
     */
    private function getUltimosLogs()
    {
        $logFile = storage_path('logs/laravel.log');
        if (!file_exists($logFile)) {
            return [];
        }

        return $this->getLastLines($logFile, 20);
    }

    /**
     * Obter status do sistema
     */
    private function getSistemaStatus()
    {
        return [
            'cache' => $this->verificarCacheStatus(),
            'storage' => $this->verificarStorageStatus(),
            'database' => $this->verificarDatabaseStatus(),
        ];
    }

    /**
     * Verificar status do cache
     */
    private function verificarCacheStatus()
    {
        try {
            \Cache::put('test', 'test', 1);
            \Cache::forget('test');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Verificar status do storage
     */
    private function verificarStorageStatus()
    {
        try {
            Storage::disk('public')->put('test.txt', 'test');
            Storage::disk('public')->delete('test.txt');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Verificar status do banco de dados
     */
    private function verificarDatabaseStatus()
    {
        try {
            DB::connection()->getPdo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Obter seções ativas da configuração
     */
    private function getSecoesAtivas()
    {
        return [
            'sobre' => Configuracao::get('secao_sobre_ativa', '0') == '1',
            'servicos' => Configuracao::get('secao_ministerios_ativa', '0') == '1',
            'eventos' => Configuracao::get('secao_cultos_ativa', '0') == '1',
            'contato' => Configuracao::get('secao_contato_ativa', '0') == '1',
            'doacao' => Configuracao::get('secao_doacao_ativa', '0') == '1',
        ];
    }

    /**
     * Limpar logs antigos
     */
    public function clearOldLogs(Request $request)
    {
        try {
            $days = $request->get('days', 30);

            // Executar comando via Artisan
            \Artisan::call('logs:clear-old', [
                '--days' => $days,
                '--force' => true
            ]);

            $output = \Artisan::output();

            return response()->json([
                'success' => true,
                'message' => 'Logs antigos limpos com sucesso!',
                'output' => $output
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao limpar logs antigos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Atualizar configurações de gateways no arquivo .env
     */
    private function updateEnvGatewaySettings(Request $request)
    {
        try {
            $envPath = base_path('.env');

            if (!file_exists($envPath)) {
                Log::warning('Arquivo .env não encontrado');
                return;
            }

            $envContent = file_get_contents($envPath);

            // Mapeamento de configurações para variáveis .env
            $envMappings = [
                'stripe_enabled' => 'STRIPE_ENABLED',
                'mercadopago_enabled' => 'MERCADOPAGO_ENABLED',
                'pix_enabled' => 'PIX_ENABLED',
                'stripe_key' => 'STRIPE_KEY',
                'stripe_secret' => 'STRIPE_SECRET',
                'mercadopago_key' => 'MERCADOPAGO_PUBLIC_KEY',
                'mercadopago_token' => 'MERCADOPAGO_ACCESS_TOKEN',
                'pix_chave' => 'PIX_CHAVE',
                'pix_beneficiario' => 'PIX_BENEFICIARIO'
            ];

            foreach ($envMappings as $requestKey => $envKey) {
                if ($request->has($requestKey)) {
                    $value = $request->input($requestKey);

                    // Converter boolean para string
                    if (is_bool($value)) {
                        $value = $value ? 'true' : 'false';
                    }

                    // Escapar aspas se necessário
                    if (is_string($value) && (strpos($value, ' ') !== false || strpos($value, '=') !== false)) {
                        $value = '"' . str_replace('"', '\\"', $value) . '"';
                    }

                    // Atualizar ou adicionar a variável no .env
                    $pattern = '/^' . preg_quote($envKey, '/') . '=.*$/m';
                    $replacement = $envKey . '=' . $value;

                    if (preg_match($pattern, $envContent)) {
                        $envContent = preg_replace($pattern, $replacement, $envContent);
                    } else {
                        // Se não existe, adicionar no final
                        $envContent .= "\n" . $replacement;
                    }
                }
            }

            // Salvar o arquivo .env atualizado
            file_put_contents($envPath, $envContent);

            // Limpar cache de configuração
            \Artisan::call('config:clear');

            Log::info('Configurações de gateways atualizadas no .env', [
                'user_id' => auth()->id(),
                'updated_keys' => array_keys($envMappings)
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar .env: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Testar configurações de email
     */
    public function testEmail(Request $request)
    {
        try {
            $testEmail = $request->input('test_email');
            
            if (!$testEmail) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email para teste é obrigatório.'
                ]);
            }

            if (!filter_var($testEmail, FILTER_VALIDATE_EMAIL)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email inválido.'
                ]);
            }

            // Verificar se as configurações SMTP estão definidas
            $requiredConfigs = [
                'mail_host' => 'Servidor SMTP',
                'mail_port' => 'Porta SMTP',
                'mail_username' => 'Usuário SMTP',
                'mail_password' => 'Senha SMTP'
            ];

            $missingConfigs = [];
            foreach ($requiredConfigs as $config => $label) {
                $value = Configuracao::get($config);
                if (empty($value)) {
                    $missingConfigs[] = $label;
                }
            }

            if (!empty($missingConfigs)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Configurações SMTP incompletas: ' . implode(', ', $missingConfigs)
                ]);
            }

            // Configurar temporariamente as configurações de email
            config([
                'mail.mailers.smtp.host' => Configuracao::get('mail_host'),
                'mail.mailers.smtp.port' => Configuracao::get('mail_port'),
                'mail.mailers.smtp.username' => Configuracao::get('mail_username'),
                'mail.mailers.smtp.password' => Configuracao::get('mail_password'),
                'mail.mailers.smtp.encryption' => Configuracao::get('mail_encryption', 'tls'),
                'mail.from.address' => Configuracao::get('mail_from_address'),
                'mail.from.name' => Configuracao::get('mail_from_name', config('app.name'))
            ]);

            // Enviar email de teste
            \Mail::raw('Este é um email de teste do sistema ' . config('app.name') . '. Se você recebeu esta mensagem, as configurações de email estão funcionando corretamente.', function ($message) use ($testEmail) {
                $message->to($testEmail)
                        ->subject('Teste de Configuração de Email - ' . config('app.name'));
            });

            return response()->json([
                'success' => true,
                'message' => 'Email de teste enviado com sucesso para ' . $testEmail
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao enviar email de teste: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar email: ' . $e->getMessage()
            ]);
        }
    }
}
