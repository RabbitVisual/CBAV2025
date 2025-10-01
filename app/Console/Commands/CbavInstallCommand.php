<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Configuracao;
use App\Models\Module;

class CbavInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cbav:install {--force : Forçar instalação mesmo se já instalado}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Instalar sistema CBAV CRM Ministerial';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Iniciando instalação do CBAV CRM Ministerial...');
        
        // Verificar se já está instalado
        if (file_exists(storage_path('installed')) && !$this->option('force')) {
            $this->error('❌ Sistema já está instalado! Use --force para reinstalar.');
            return 1;
        }
        
        try {
            // 1. Verificar conexão com banco
            $this->info('📊 Verificando conexão com banco de dados...');
            DB::connection()->getPdo();
            $this->info('✅ Conexão com banco estabelecida');
            
            // 2. Executar migrações
            $this->info('🔄 Executando migrações...');
            Artisan::call('migrate:fresh', ['--force' => true]);
            $this->info('✅ Migrações executadas');
            
            // 3. Executar seeders
            $this->info('🌱 Executando seeders...');
            $this->runSeeders();
            $this->info('✅ Seeders executados');
            
            // 4. Configurar ambiente
            $this->info('⚙️ Configurando ambiente...');
            $this->configureEnvironment();
            $this->info('✅ Ambiente configurado');
            
            // 5. Limpar cache
            $this->info('🧹 Limpando cache...');
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            $this->info('✅ Cache limpo');
            
            // 6. Otimizar
            $this->info('⚡ Otimizando aplicação...');
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
            $this->info('✅ Aplicação otimizada');
            
            // 7. Marcar como instalado
            file_put_contents(storage_path('installed'), date('Y-m-d H:i:s'));
            
            $this->info('🎉 Instalação concluída com sucesso!');
            $this->info('');
            $this->info('📋 Próximos passos:');
            $this->info('1. Acesse o sistema com as credenciais do administrador');
            $this->info('2. Configure os gateways de pagamento');
            $this->info('3. Personalize as informações da igreja');
            $this->info('4. Configure ministérios e cargos');
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ Erro durante instalação: ' . $e->getMessage());
            return 1;
        }
    }
    
    /**
     * Executar seeders
     */
    private function runSeeders()
    {
        $seeders = [
            'DatabaseSeeder',
            'RolesAndPermissionsSeeder',
            'InitialDataSeeder',
            'HomeConfigSeeder',
            'CargosSistemaSeeder',
            'MinisterioColorsSeeder',
            'CouncilSettingsSeeder'
        ];
        
        foreach ($seeders as $seeder) {
            try {
                $this->info("   Executando {$seeder}...");
                Artisan::call('db:seed', ['--class' => $seeder, '--force' => true]);
            } catch (\Exception $e) {
                $this->warn("   Aviso: Erro ao executar {$seeder}: " . $e->getMessage());
            }
        }
    }
    
    /**
     * Configurar ambiente
     */
    private function configureEnvironment()
    {
        // Configurações básicas do sistema
        $configuracoes = [
            'app_name' => 'CBAV CRM Ministerial',
            'app_url' => config('app.url'),
            'app_timezone' => 'America/Sao_Paulo',
            'app_locale' => 'pt_BR',
            'mail_driver' => 'smtp',
            'cache_driver' => 'file',
            'session_driver' => 'file',
            'queue_connection' => 'sync',
            'log_channel' => 'stack',
            'log_level' => 'error',
            'bible_api_enabled' => 'true',
            'bible_api_url' => 'https://bibleapi.co/api',
            'bible_default_version' => 'NVI',
            'payment_stripe_enabled' => 'false',
            'payment_mercadopago_enabled' => 'false',
            'payment_pix_enabled' => 'false',
            'notification_email_enabled' => 'false',
            'backup_enabled' => 'true',
            'maintenance_mode' => 'false'
        ];
        
        foreach ($configuracoes as $key => $value) {
            Configuracao::set($key, $value);
        }
        
        // Configurar módulos ativos
        $modules = [
            'people' => ['name' => 'Pessoas', 'active' => true, 'order' => 1],
            'finance' => ['name' => 'Financeiro', 'active' => true, 'order' => 2],
            'devotionals' => ['name' => 'Devocionais', 'active' => true, 'order' => 3],
            'council' => ['name' => 'Conselho', 'active' => true, 'order' => 4],
            'system' => ['name' => 'Sistema', 'active' => true, 'order' => 5]
        ];
        
        foreach ($modules as $key => $module) {
            Module::updateOrCreate(
                ['key' => $key],
                [
                    'name' => $module['name'],
                    'active' => $module['active'],
                    'order' => $module['order'],
                    'icon' => 'fas fa-cog',
                    'color' => '#2563eb'
                ]
            );
        }
    }
} 