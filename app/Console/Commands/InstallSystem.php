<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Configuracao;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class InstallSystem extends Command
{
    protected $signature = 'cbav:install {--force : Forçar instalação mesmo se já instalado}';
    protected $description = 'Instalar o sistema CBAV CRM Ministerial';

    public function handle()
    {
        $this->info('🏛️  CBAV CRM Ministerial - Instalação');
        $this->info('=====================================');
        
        // Verificar se já está instalado
        if (!$this->option('force') && $this->isInstalled()) {
            $this->error('❌ Sistema já está instalado! Use --force para reinstalar.');
            return 1;
        }

        $this->info('🚀 Iniciando instalação...');
        
        try {
            // Passo 1: Verificar requisitos
            $this->checkRequirements();
            
            // Passo 2: Configurar banco de dados
            $this->configureDatabase();
            
            // Passo 3: Executar migrações
            $this->runMigrations();
            
            // Passo 4: Executar seeders
            $this->runSeeders();
            
            // Passo 5: Configurar storage
            $this->setupStorage();
            
            // Passo 6: Configurar cache
            $this->setupCache();
            
            // Passo 7: Criar super admin
            $this->createSuperAdmin();
            
            // Passo 8: Configurações finais
            $this->finalSetup();
            
            $this->info('✅ Instalação concluída com sucesso!');
            $this->showFinalInfo();
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ Erro durante a instalação: ' . $e->getMessage());
            return 1;
        }
    }

    private function isInstalled()
    {
        try {
            return DB::table('users')->count() > 0;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function checkRequirements()
    {
        $this->info('📋 Verificando requisitos...');
        
        // Verificar PHP
        if (version_compare(PHP_VERSION, '8.2.0', '<')) {
            throw new \Exception('PHP 8.2+ é necessário. Versão atual: ' . PHP_VERSION);
        }
        
        // Verificar extensões
        $requiredExtensions = ['pdo', 'pdo_mysql', 'mbstring', 'xml', 'curl', 'gd', 'zip'];
        foreach ($requiredExtensions as $ext) {
            if (!extension_loaded($ext)) {
                throw new \Exception("Extensão PHP '$ext' não está instalada.");
            }
        }
        
        // Verificar permissões
        $writablePaths = ['storage', 'bootstrap/cache'];
        foreach ($writablePaths as $path) {
            if (!is_writable($path)) {
                throw new \Exception("Diretório '$path' não tem permissão de escrita.");
            }
        }
        
        $this->info('✅ Requisitos atendidos!');
    }

    private function configureDatabase()
    {
        $this->info('🗄️  Configurando banco de dados...');
        
        // Verificar conexão
        try {
            DB::connection()->getPdo();
            $this->info('✅ Conexão com banco de dados estabelecida.');
        } catch (\Exception $e) {
            throw new \Exception('Não foi possível conectar ao banco de dados. Verifique as configurações no .env');
        }
    }

    private function runMigrations()
    {
        $this->info('🔄 Executando migrações...');
        
        Artisan::call('migrate:fresh', ['--force' => true]);
        $this->info('✅ Migrações executadas com sucesso!');
    }

    private function runSeeders()
    {
        $this->info('🌱 Executando seeders...');
        
        $seeders = [
            'RolesAndPermissionsSeeder',
            'InitialDataSeeder',
            'HomeConfigSeeder',
            'CargosSistemaSeeder',
            'MinisterioColorsSeeder',
        ];
        
        foreach ($seeders as $seeder) {
            $this->info("Executando $seeder...");
            Artisan::call('db:seed', ['--class' => $seeder, '--force' => true]);
        }
        
        $this->info('✅ Seeders executados com sucesso!');
    }

    private function setupStorage()
    {
        $this->info('📁 Configurando storage...');
        
        // Criar link simbólico
        if (!file_exists(public_path('storage'))) {
            Artisan::call('storage:link', ['--force' => true]);
        }
        
        // Criar diretórios necessários
        $directories = [
            'storage/app/public/campanhas',
            'storage/app/public/membros',
            'storage/app/public/config',
            'storage/app/public/profiles',
            'storage/backups',
        ];
        
        foreach ($directories as $dir) {
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }
        }
        
        $this->info('✅ Storage configurado!');
    }

    private function setupCache()
    {
        $this->info('⚡ Configurando cache...');
        
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');
        
        $this->info('✅ Cache configurado!');
    }

    private function createSuperAdmin()
    {
        $this->info('👑 Criando Super Administrador...');
        
        $name = $this->ask('Nome do Super Administrador:', 'Administrador');
        $email = $this->ask('Email do Super Administrador:', 'admin@cbav.com');
        $password = $this->secret('Senha do Super Administrador (mínimo 8 caracteres):');
        
        // Validar senha
        if (strlen($password) < 8) {
            throw new \Exception('A senha deve ter pelo menos 8 caracteres.');
        }
        
        // Criar usuário
        $admin = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
        ]);
        
        // Atribuir role Super Admin
        $admin->assignRole('Super Admin');
        
        $this->info('✅ Super Administrador criado com sucesso!');
        
        // Salvar credenciais para exibição final
        $this->adminCredentials = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ];
    }

    private function finalSetup()
    {
        $this->info('🔧 Configurações finais...');
        
        // Configurar ambiente de produção
        if (app()->environment('production')) {
            $this->info('Configurando para produção...');
            
            // Desabilitar debug
            $this->updateEnv('APP_DEBUG', 'false');
            $this->updateEnv('APP_ENV', 'production');
            
            // Configurar cache
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
        }
        
        // Criar arquivo de instalação
        file_put_contents(storage_path('installed'), date('Y-m-d H:i:s'));
        
        $this->info('✅ Configurações finais concluídas!');
    }

    private function updateEnv($key, $value)
    {
        $envFile = base_path('.env');
        $envContent = file_get_contents($envFile);
        
        if (strpos($envContent, $key . '=') !== false) {
            $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $envContent);
        } else {
            $envContent .= "\n{$key}={$value}";
        }
        
        file_put_contents($envFile, $envContent);
    }

    private function showFinalInfo()
    {
        $this->info('');
        $this->info('🎉 INSTALAÇÃO CONCLUÍDA COM SUCESSO!');
        $this->info('=====================================');
        $this->info('');
        $this->info('📋 INFORMAÇÕES DE ACESSO:');
        $this->info('URL: ' . config('app.url'));
        $this->info('Email: ' . $this->adminCredentials['email']);
        $this->info('Senha: ' . $this->adminCredentials['password']);
        $this->info('');
        $this->info('🔐 PRÓXIMOS PASSOS:');
        $this->info('1. Acesse o sistema com as credenciais acima');
        $this->info('2. Configure os gateways de pagamento em Admin > Configurações');
        $this->info('3. Personalize as informações da igreja');
        $this->info('4. Configure os ministérios e cargos');
        $this->info('');
        $this->info('📚 DOCUMENTAÇÃO:');
        $this->info('Leia o README.md para mais informações sobre o sistema.');
        $this->info('');
        $this->info('🆘 SUPORTE:');
        $this->info('Desenvolvido por Vertex Solutions - CEO Reinan Rodrigues');
        $this->info('');
    }
} 