<?php

namespace App\Services;

use App\Models\Configuracao;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class SystemService
{
    /**
     * Get data for the system dashboard.
     *
     * @return array
     */
    public function getDashboardData(): array
    {
        try {
            return [
                'estatisticas' => [
                    'total_usuarios' => User::count(),
                    'usuarios_ativos' => User::where('ativo', true)->count(),
                    'espaco_disco' => $this->getDiskSpaceInfo(),
                    'ultimo_backup' => $this->getLatestBackupTimestamp(),
                ],
                'logsRecentes' => $this->getRecentLogs(),
                'sistemaStatus' => $this->getSystemStatus(),
            ];
        } catch (\Exception $e) {
            Log::error('Erro ao buscar dados para o dashboard do sistema: ' . $e->getMessage());
            return [
                'estatisticas' => [
                    'total_usuarios' => 0,
                    'usuarios_ativos' => 0,
                    'espaco_disco' => $this->getDiskSpaceInfo(),
                    'ultimo_backup' => null,
                ],
                'logsRecentes' => [],
                'sistemaStatus' => [
                    'cache' => false,
                    'storage' => false,
                    'database' => false,
                ],
            ];
        }
    }

    /**
     * Get all system settings for the settings page.
     *
     * @return array
     */
    public function getSystemSettings(): array
    {
        try {
            $settings = [
                // General
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

                // Mail
                'mail_host' => Configuracao::get('mail_host', config('mail.mailers.smtp.host')),
                'mail_port' => Configuracao::get('mail_port', config('mail.mailers.smtp.port')),
                'mail_username' => Configuracao::get('mail_username', config('mail.mailers.smtp.username')),
                'mail_password' => Configuracao::get('mail_password', config('mail.mailers.smtp.password')),
                'mail_encryption' => Configuracao::get('mail_encryption', config('mail.mailers.smtp.encryption')),
                'mail_from_address' => Configuracao::get('mail_from_address', config('mail.from.address')),
                'mail_from_name' => Configuracao::get('mail_from_name', config('mail.from.name')),

                // Security
                'session_lifetime' => Configuracao::get('session_lifetime', config('session.lifetime')),
                'max_login_attempts' => Configuracao::get('max_login_attempts', 5),
                'force_ssl' => Configuracao::get('force_ssl', false),
                'password_min_length' => Configuracao::get('password_min_length', 8),
                'password_require_special' => Configuracao::get('password_require_special', true),

                // Payment (prioritize .env)
                'stripe_key' => env('STRIPE_KEY', Configuracao::get('stripe_key', '')),
                'stripe_secret' => env('STRIPE_SECRET', Configuracao::get('stripe_secret', '')),
                'stripe_enabled' => filter_var(env('STRIPE_ENABLED', Configuracao::get('stripe_enabled', false)), FILTER_VALIDATE_BOOLEAN),
                'mercadopago_key' => env('MERCADOPAGO_PUBLIC_KEY', Configuracao::get('mercadopago_key', '')),
                'mercadopago_token' => env('MERCADOPAGO_ACCESS_TOKEN', Configuracao::get('mercadopago_token', '')),
                'mercadopago_enabled' => filter_var(env('MERCADOPAGO_ENABLED', Configuracao::get('mercadopago_enabled', false)), FILTER_VALIDATE_BOOLEAN),
                'pix_chave' => env('PIX_CHAVE', Configuracao::get('pix_chave', '')),
                'pix_beneficiario' => env('PIX_BENEFICIARIO', Configuracao::get('pix_beneficiario', '')),
                'pix_enabled' => filter_var(env('PIX_ENABLED', Configuracao::get('pix_enabled', false)), FILTER_VALIDATE_BOOLEAN),

                // Donation
                'doacao_valor_minimo' => Configuracao::get('doacao_valor_minimo', 1.00),
                'doacao_valor_maximo' => Configuracao::get('doacao_valor_maximo', 10000.00),
                'doacao_sem_login' => Configuracao::get('doacao_sem_login', true),
                'doacao_ativa' => Configuracao::get('doacao_ativa', true),

                // Cache
                'cache_driver' => Configuracao::get('cache_driver', config('cache.default')),
                'session_driver' => Configuracao::get('session_driver', config('session.driver')),
                'queue_connection' => Configuracao::get('queue_connection', config('queue.default')),

                // Backup
                'backup_enabled' => Configuracao::get('backup_enabled', true),
                'backup_frequency' => Configuracao::get('backup_frequency', 'daily'),
                'backup_retention' => Configuracao::get('backup_retention', 7),

                // Notification
                'notification_email_enabled' => Configuracao::get('notification_email_enabled', true),
                'notification_sms_enabled' => Configuracao::get('notification_sms_enabled', false),
                'notification_push_enabled' => Configuracao::get('notification_push_enabled', false),
            ];
            Log::info('Configurações do sistema carregadas para a view.', ['total_configs' => count($settings)]);
            return $settings;
        } catch (\Exception $e) {
            Log::error('Erro ao carregar as configurações do sistema: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return []; // Return empty array on error
        }
    }

    /**
     * Update system settings.
     *
     * @param Request $request
     * @return void
     * @throws ValidationException
     */
    public function updateSystemSettings(Request $request): void
    {
        $this->validateSettingsRequest($request);

        DB::transaction(function () use ($request) {
            $allSettings = $request->except(['_token', '_method', 'app_logo', 'app_favicon', 'active_tab']);

            foreach ($allSettings as $key => $value) {
                // Handle boolean values from checkboxes
                if (in_array($key, [
                    'force_ssl', 'password_require_special', 'stripe_enabled',
                    'mercadopago_enabled', 'pix_enabled', 'doacao_sem_login', 'doacao_ativa',
                    'backup_enabled', 'notification_email_enabled', 'notification_sms_enabled',
                    'notification_push_enabled'
                ])) {
                    $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                }
                Configuracao::set($key, $value);
            }

            if ($request->hasFile('app_logo')) {
                $logoPath = $request->file('app_logo')->store('config', 'public');
                Configuracao::set('app_logo', $logoPath);
                Log::info('Logo do app atualizada.', ['path' => $logoPath]);
            }

            if ($request->hasFile('app_favicon')) {
                $faviconPath = $request->file('app_favicon')->store('config', 'public');
                Configuracao::set('app_favicon', $faviconPath);
                Log::info('Favicon do app atualizado.', ['path' => $faviconPath]);
            }

            $this->updateEnvGatewaySettings($request);
        });

        $this->clearApplicationCache();
        Log::info('Configurações do sistema atualizadas com sucesso.', ['user_id' => auth()->id()]);
    }

    /**
     * Get all homepage settings.
     *
     * @return array
     */
    public function getHomeConfig(): array
    {
        try {
            $configKeys = [
                'igreja_nome', 'igreja_slogan', 'igreja_endereco', 'igreja_telefone', 'igreja_email', 'igreja_website', 'igreja_facebook', 'igreja_instagram', 'igreja_youtube', 'igreja_whatsapp',
                'cor_primaria', 'cor_secundaria', 'cor_destaque', 'cor_texto', 'logo',
                'hero_titulo', 'hero_subtitulo', 'home_descricao', 'home_botao_texto', 'home_botao_link',
                'secao_sobre_ativa', 'secao_ministerios_ativa', 'secao_eventos_ativa', 'secao_contato_ativa', 'secao_doacao_ativa', 'secao_aniversariantes_ativa',
                'meta_title', 'meta_description', 'meta_keywords',
                'aniversariantes_titulo', 'aniversariantes_subtitulo', 'aniversariantes_mostrar',
                'escola_dominical_titulo', 'escola_dominical_horario', 'escola_dominical_descricao', 'escola_dominical_classe1', 'escola_dominical_classe2', 'escola_dominical_classe3', 'escola_dominical_ativa',
                'header_logo_ativa', 'header_nome_igreja_ativa', 'header_slogan_ativa', 'header_menu_ativa', 'header_area_usuario_ativa', 'header_texto_area_membro', 'header_link_sobre', 'header_link_ministerios', 'header_link_cultos', 'header_link_aniversariantes', 'header_link_eventos', 'header_link_doacao', 'header_link_contato',
                'footer_ativa', 'footer_descricao', 'footer_links_titulo', 'footer_contato_titulo', 'footer_horarios_titulo', 'footer_copyright_texto', 'footer_link_sobre', 'footer_link_ministerios', 'footer_link_cultos', 'footer_link_eventos', 'footer_link_aniversariantes', 'footer_link_doacao', 'footer_redes_sociais_ativa', 'footer_link_creditos_ativa', 'footer_link_creditos_texto', 'footer_link_vertex_ativa', 'footer_link_vertex_texto',
                'contato_titulo', 'contato_subtitulo', 'contato_ativa',
                'doacao_titulo', 'doacao_subtitulo', 'doacao_dica_seguranca', 'doacao_dica_comprovante', 'doacao_dica_transparencia',
                'culto_domingo_manha_titulo', 'culto_domingo_manha_horario', 'culto_domingo_manha_descricao', 'culto_domingo_manha_item1', 'culto_domingo_manha_item2', 'culto_domingo_manha_item3',
                'culto_domingo_noite_titulo', 'culto_domingo_noite_horario', 'culto_domingo_noite_descricao', 'culto_domingo_noite_item1', 'culto_domingo_noite_item2', 'culto_domingo_noite_item3',
                'culto_quarta_titulo', 'culto_quarta_horario', 'culto_quarta_descricao', 'culto_quarta_item1', 'culto_quarta_item2', 'culto_quarta_item3',
            ];

            $configuracoes = [];
            foreach ($configKeys as $key) {
                // Remap some keys for consistency
                $dbKey = str_replace(['hero_titulo', 'hero_subtitulo'], ['titulo_principal', 'subtitulo'], $key);
                $configuracoes[$key] = Configuracao::get($dbKey, '');
            }

            // Handle sections array for the view
            $configuracoes['secoes'] = [
                'sobre' => (bool) Configuracao::get('secao_sobre_ativa', '1'),
                'servicos' => (bool) Configuracao::get('secao_ministerios_ativa', '1'),
                'eventos' => (bool) Configuracao::get('secao_eventos_ativa', '1'),
                'contato' => (bool) Configuracao::get('secao_contato_ativa', '1'),
                'doacao' => (bool) Configuracao::get('secao_doacao_ativa', '1'),
                'aniversariantes' => (bool) Configuracao::get('secao_aniversariantes_ativa', '1'),
            ];

            return $configuracoes;
        } catch (\Exception $e) {
            Log::error('Erro ao carregar configurações da homepage: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Update homepage settings.
     *
     * @param Request $request
     * @return void
     * @throws ValidationException
     */
    public function updateHomeConfig(Request $request): void
    {
        $this->validateHomeConfigRequest($request);

        DB::transaction(function () use ($request) {
            $allSettings = $request->except(['_token', '_method', 'logo', 'secoes', 'active_tab']);

            foreach ($allSettings as $key => $value) {
                $dbKey = str_replace(['titulo_principal', 'subtitulo'], ['hero_titulo', 'hero_subtitulo'], $key);
                Configuracao::set($dbKey, $value);
            }

            // Handle checkboxes for sections
            $sections = ['sobre', 'ministerios', 'eventos', 'contato', 'doacao', 'aniversariantes'];
            $activeSections = $request->input('secoes', []);
            foreach ($sections as $section) {
                $keyName = "secao_{$section}_ativa";
                $isActive = in_array($section, $activeSections);
                Configuracao::set($keyName, $isActive ? '1' : '0');
            }

            // Handle other boolean checkboxes
            $checkboxes = [
                'aniversariantes_mostrar', 'escola_dominical_ativa', 'header_logo_ativa', 'header_nome_igreja_ativa',
                'header_slogan_ativa', 'header_menu_ativa', 'header_area_usuario_ativa', 'footer_ativa',
                'footer_redes_sociais_ativa', 'footer_link_creditos_ativa', 'footer_link_vertex_ativa', 'contato_ativa'
            ];
            foreach ($checkboxes as $checkbox) {
                Configuracao::set($checkbox, $request->has($checkbox) ? '1' : '0');
            }

            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('config', 'public');
                Configuracao::set('logo', $logoPath);
                Log::info('Logo da homepage atualizada.', ['path' => $logoPath]);
            }
        });

        $this->clearApplicationCache();
        Log::info('Configurações da homepage atualizadas com sucesso.', ['user_id' => auth()->id()]);
    }

    /**
     * Reset homepage settings to default values.
     *
     * @return void
     */
    public function resetHomeConfig(): void
    {
        try {
            $seeder = new \Database\Seeders\HomeConfigSeeder();
            $seeder->run();
            $this->clearApplicationCache();
            Log::info('Configurações da homepage resetadas para o padrão.');
        } catch (\Exception $e) {
            Log::error('Erro ao resetar configurações da homepage: ' . $e->getMessage());
            throw $e; // Re-throw to be caught by the controller
        }
    }

    /**
     * Get data for the logs page.
     *
     * @param Request $request
     * @return array
     */
    public function getLogData(Request $request): array
    {
        $logFiles = ['laravel.log' => storage_path('logs/laravel.log')];
        $logs = [];
        $totalSize = 0;

        $selectedFile = $request->get('arquivo', 'laravel.log');
        $level = $request->get('nivel');
        $search = $request->get('search');
        $date = $request->get('data');
        $linesCount = $request->get('linhas', 100);
        $linesCount = ($linesCount === 'all') ? PHP_INT_MAX : (int) $linesCount;

        foreach ($logFiles as $name => $path) {
            if (file_exists($path)) {
                $size = filesize($path);
                $totalSize += $size;
                $processedLines = [];

                if ($name === $selectedFile) {
                    $lines = $this->getLastLines($path, $linesCount);
                    foreach ($lines as $line) {
                        $parsedLine = $this->parseLogLine($line);
                        if ($parsedLine && $this->shouldIncludeLog($parsedLine, $level, $search, $date)) {
                            $processedLines[] = $parsedLine;
                        }
                    }
                }

                $logs[$name] = [
                    'size' => $size,
                    'last_modified' => filemtime($path),
                    'lines' => $processedLines,
                ];
            }
        }

        return [
            'logs' => $logs,
            'estatisticas' => ['tamanho' => $this->formatBytes($totalSize)],
            'arquivos' => array_keys($logFiles),
            'selectedFile' => $selectedFile,
        ];
    }

    /**
     * Clear all log files.
     *
     * @return int
     */
    public function clearLogs(): int
    {
        $logFiles = [storage_path('logs/laravel.log')];
        $clearedCount = 0;
        foreach ($logFiles as $path) {
            if (file_exists($path)) {
                file_put_contents($path, '');
                $clearedCount++;
            }
        }
        Log::info("Arquivos de log limpos.", ['count' => $clearedCount, 'user_id' => auth()->id()]);
        return $clearedCount;
    }

    /**
     * Get the path for a log file export.
     *
     * @param Request $request
     * @return string|null
     */
    public function exportLogs(Request $request): ?string
    {
        $logFiles = ['laravel.log' => storage_path('logs/laravel.log')];
        $fileToExport = $request->get('arquivo', 'laravel.log');
        $path = $logFiles[$fileToExport] ?? null;

        return file_exists($path) ? $path : null;
    }

    /**
     * Get details for a specific log entry.
     *
     * @param Request $request
     * @return array|null
     */
    public function getLogDetails(Request $request): ?array
    {
        $logId = $request->get('id');
        $arquivo = $request->get('arquivo', 'laravel.log');
        $path = storage_path('logs/' . $arquivo);

        if (!file_exists($path) || !$logId) {
            return null;
        }

        // This is a simplified version. A real implementation might need a more robust way to find a log by ID.
        // For now, we assume the ID is the line number or a unique string in the line.
        $lines = file($path, FILE_IGNORE_NEW_LINES);
        $logLine = null;

        if (is_numeric($logId) && isset($lines[$logId])) {
            $logLine = $lines[$logId];
        } else {
            foreach ($lines as $line) {
                if (strpos($line, $logId) !== false) {
                    $logLine = $line;
                    break;
                }
            }
        }

        return $logLine ? $this->parseLogLine($logLine) : null;
    }

    /**
     * Run database backup.
     *
     * @return array
     */
    public function runBackup(): array
    {
        $backupPath = storage_path('app/backups');
        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $filepath = $backupPath . '/' . $filename;

        $command = sprintf(
            'mysqldump -u%s -p%s %s > %s',
            config('database.connections.mysql.username'),
            escapeshellarg(config('database.connections.mysql.password')), // Use escapeshellarg for password
            config('database.connections.mysql.database'),
            $filepath
        );

        exec($command, $output, $returnCode);

        if ($returnCode === 0 && file_exists($filepath)) {
            Log::info('Backup do banco de dados criado com sucesso.', ['file' => $filename]);
            return [
                'success' => true,
                'message' => 'Backup criado com sucesso!',
                'filename' => $filename,
                'size' => $this->formatBytes(filesize($filepath))
            ];
        } else {
            Log::error('Falha ao criar backup do banco de dados.', ['return_code' => $returnCode, 'output' => $output]);
            return ['success' => false, 'message' => 'Erro ao criar backup. Verifique os logs.'];
        }
    }

    /**
     * Clear all application caches.
     *
     * @return void
     */
    public function clearApplicationCache(): void
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Log::info('Cache da aplicação limpo.', ['user_id' => auth()->id()]);
    }

    /**
     * Get system maintenance status.
     *
     * @return array
     */
    public function getMaintenanceStatus(): array
    {
        return [
            'is_down' => app()->isDownForMaintenance(),
            'cache_status' => $this->checkCacheStatus(),
            'storage_status' => $this->checkStorageStatus(),
            'database_status' => $this->checkDatabaseStatus(),
        ];
    }

    /**
     * Enable maintenance mode.
     *
     * @return void
     */
    public function enableMaintenanceMode(): void
    {
        Artisan::call('down', ['--message' => 'O sistema está em manutenção. Voltamos em breve.']);
        Log::info('Modo de manutenção ATIVADO.', ['user_id' => auth()->id()]);
    }

    /**
     * Disable maintenance mode.
     *
     * @return void
     */
    public function disableMaintenanceMode(): void
    {
        Artisan::call('up');
        Log::info('Modo de manutenção DESATIVADO.', ['user_id' => auth()->id()]);
    }

    // Private Helper Methods

    private function validateSettingsRequest(Request $request)
    {
        $rules = [
            'app_name' => 'required|string|max:255',
            'app_description' => 'nullable|string|max:1000',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'app_favicon' => 'nullable|image|mimes:ico,png|max:1024',
            'contact_email' => 'nullable|email',
            'timezone' => 'required|string',
            'locale' => 'required|string',
            'mail_host' => 'nullable|string|max:255',
            'mail_port' => 'nullable|integer',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'session_lifetime' => 'nullable|integer|min:30',
            'doacao_valor_minimo' => 'nullable|numeric|min:0.01',
            'backup_retention' => 'nullable|integer|min:1',
        ];
        $request->validate($rules);
    }

    private function validateHomeConfigRequest(Request $request)
    {
        $rules = [
            'igreja_nome' => 'nullable|string|max:255',
            'igreja_slogan' => 'nullable|string|max:255',
            'cor_primaria' => 'nullable|string|max:7',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
        $request->validate($rules);
    }

    private function updateEnvGatewaySettings(Request $request)
    {
        try {
            $envPath = base_path('.env');
            if (!file_exists($envPath) || !is_writable($envPath)) {
                Log::warning('.env file not found or not writable.');
                return;
            }
            $envContent = file_get_contents($envPath);

            $mappings = [
                'stripe_enabled' => 'STRIPE_ENABLED', 'mercadopago_enabled' => 'MERCADOPAGO_ENABLED',
                'pix_enabled' => 'PIX_ENABLED', 'stripe_key' => 'STRIPE_KEY', 'stripe_secret' => 'STRIPE_SECRET',
                'mercadopago_key' => 'MERCADOPAGO_PUBLIC_KEY', 'mercadopago_token' => 'MERCADOPAGO_ACCESS_TOKEN',
                'pix_chave' => 'PIX_CHAVE', 'pix_beneficiario' => 'PIX_BENEFICIARIO',
            ];

            foreach ($mappings as $requestKey => $envKey) {
                if ($request->has($requestKey)) {
                    $value = $request->input($requestKey);
                    $value = is_bool($value) ? ($value ? 'true' : 'false') : $value;
                    $value = (is_string($value) && str_contains($value, ' ')) ? "\"$value\"" : $value;

                    $pattern = '/^' . preg_quote($envKey, '/') . '=.*$/m';
                    $replacement = $envKey . '=' . $value;

                    if (preg_match($pattern, $envContent)) {
                        $envContent = preg_replace($pattern, $replacement, $envContent);
                    } else {
                        $envContent .= "\n" . $replacement;
                    }
                }
            }
            file_put_contents($envPath, $envContent);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar o arquivo .env: ' . $e->getMessage());
        }
    }

    private function getDiskSpaceInfo(): array
    {
        try {
            $total = disk_total_space(storage_path());
            $free = disk_free_space(storage_path());
            $used = $total - $free;
            $percent = $total > 0 ? round(($used / $total) * 100, 2) : 0;
            return [
                'total' => $this->formatBytes($total),
                'used' => $this->formatBytes($used),
                'free' => $this->formatBytes($free),
                'percent' => $percent,
            ];
        } catch (\Exception $e) {
            Log::warning("Não foi possível obter informações de espaço em disco: " . $e->getMessage());
            return ['total' => 'N/A', 'used' => 'N/A', 'free' => 'N/A', 'percent' => 0];
        }
    }

    private function getLatestBackupTimestamp(): ?string
    {
        $backupPath = storage_path('app/backups');
        if (!is_dir($backupPath)) return null;

        $files = glob($backupPath . '/*.sql');
        if (empty($files)) return null;

        $latestFileTime = max(array_map('filemtime', $files));
        return date('d/m/Y H:i:s', $latestFileTime);
    }

    private function getRecentLogs(int $lines = 20): array
    {
        $logFile = storage_path('logs/laravel.log');
        if (!file_exists($logFile)) return [];
        return $this->getLastLines($logFile, $lines);
    }

    private function getSystemStatus(): array
    {
        return [
            'cache' => $this->checkCacheStatus(),
            'storage' => $this->checkStorageStatus(),
            'database' => $this->checkDatabaseStatus(),
        ];
    }

    private function checkCacheStatus(): bool
    {
        try {
            Cache::put('system_check', 'ok', 1);
            return Cache::get('system_check') === 'ok';
        } catch (\Exception $e) {
            Log::warning('Verificação de status do cache falhou: ' . $e->getMessage());
            return false;
        }
    }

    private function checkStorageStatus(): bool
    {
        try {
            Storage::disk('local')->put('system_check.txt', 'ok');
            $status = Storage::disk('local')->get('system_check.txt') === 'ok';
            Storage::disk('local')->delete('system_check.txt');
            return $status;
        } catch (\Exception $e) {
            Log::warning('Verificação de status do storage falhou: ' . $e->getMessage());
            return false;
        }
    }

    private function checkDatabaseStatus(): bool
    {
        try {
            DB::connection()->getPdo();
            return true;
        } catch (\Exception $e) {
            Log::warning('Verificação de status do banco de dados falhou: ' . $e->getMessage());
            return false;
        }
    }

    private function getLastLines(string $file, int $lines): array
    {
        if (!file_exists($file)) return [];
        $f = fopen($file, 'rb');
        fseek($f, -1, SEEK_END);
        if (fread($f, 1) != "\n") $lines -= 1;
        $output = '';
        while (ftell($f) > 0 && $lines >= 0) {
            fseek($f, -2, SEEK_CUR);
            $char = fread($f, 1);
            if ($char == "\n") {
                $lines--;
            }
            $output = $char . $output;
        }
        fclose($f);
        return array_filter(explode("\n", $output));
    }

    private function parseLogLine(string $line): ?array
    {
        if (empty(trim($line))) return null;
        $pattern = '/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (?<env>\w+)\.(?<level>\w+): (?<message>.*?)(?=\{|$)/';
        if (preg_match($pattern, $line, $matches)) {
            $context = null;
            if (preg_match('/(\{.*\})/', $line, $contextMatches)) {
                $context = json_decode($contextMatches[1], true);
            }
            return [
                'id' => uniqid(),
                'datetime' => $matches[1],
                'environment' => $matches['env'],
                'level' => strtolower($matches['level']),
                'message' => trim($matches['message']),
                'context' => $context,
            ];
        }
        return ['id' => uniqid(), 'datetime' => '', 'level' => 'info', 'message' => $line, 'context' => null];
    }

    private function shouldIncludeLog(array $log, ?string $level, ?string $search, ?string $date): bool
    {
        if ($level && strtolower($log['level']) !== strtolower($level)) return false;
        if ($date && strpos($log['datetime'], $date) !== 0) return false;
        if ($search && stripos($log['message'], $search) === false && stripos(json_encode($log['context']), $search) === false) return false;
        return true;
    }

    private function formatBytes(int $bytes, int $precision = 2): string
    {
        if ($bytes <= 0) return '0 B';
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = floor(log($bytes, 1024));
        return round($bytes / (1024 ** $i), $precision) . ' ' . $units[$i];
    }
}