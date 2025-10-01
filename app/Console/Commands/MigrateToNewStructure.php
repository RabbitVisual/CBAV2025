<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\{DB, Artisan};
use App\Models\{User, Role, Permission};

class MigrateToNewStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:migrate-structure {--force : Forçar migração sem confirmação}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrar sistema para nova estrutura organizacional';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (app()->runningUnitTests()) {
            $this->info('Ambiente de testes: ignorando MigrateToNewStructure.');
            return 0;
        }
        if (!$this->option('force')) {
            if (!$this->confirm('Esta operação irá migrar o sistema para a nova estrutura. Continuar?')) {
                $this->info('Migração cancelada.');
                return 0;
            }
        }

        $this->info('🔄 Iniciando migração para nova estrutura...');
        $this->newLine();

        try {
            // Backup das permissões antigas
            $this->info('📋 Fazendo backup das permissões antigas...');
            $this->backupOldPermissions();

            // Limpar cache de permissões
            $this->info('🧹 Limpando cache de permissões...');
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            // Executar seeder de permissões simplificadas
            $this->info('🔧 Criando novas permissões...');
            Artisan::call('db:seed', ['--class' => 'PermissoesSimplificadasSeeder']);

            // Migrar usuários existentes
            $this->info('👥 Migrando usuários existentes...');
            $this->migrateExistingUsers();

            // Limpar arquivos antigos
            $this->info('🗑️ Limpando arquivos antigos...');
            $this->cleanupOldFiles();

            // Criar novas views
            $this->info('📁 Criando estrutura de views...');
            $this->createNewViewStructure();

            $this->newLine();
            $this->info('✅ Migração concluída com sucesso!');
            $this->info('📝 Próximos passos:');
            $this->info('   1. Implementar os controllers restantes');
            $this->info('   2. Criar as views correspondentes');
            $this->info('   3. Testar todas as funcionalidades');
            $this->info('   4. Remover arquivos antigos não utilizados');

        } catch (\Exception $e) {
            $this->error('❌ Erro durante a migração: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Fazer backup das permissões antigas
     */
    private function backupOldPermissions()
    {
        $permissions = Permission::all()->toArray();
        $roles = Role::all()->toArray();
        
        $backup = [
            'permissions' => $permissions,
            'roles' => $roles,
            'timestamp' => now()->toISOString()
        ];

        $backupPath = storage_path('backups/permissions_backup_' . now()->format('Y-m-d_H-i-s') . '.json');
        
        if (!is_dir(dirname($backupPath))) {
            mkdir(dirname($backupPath), 0755, true);
        }

        file_put_contents($backupPath, json_encode($backup, JSON_PRETTY_PRINT));
        
        $this->info("   Backup salvo em: {$backupPath}");
    }

    /**
     * Migrar usuários existentes
     */
    private function migrateExistingUsers()
    {
        $users = User::all();
        $membroRole = Role::where('name', 'Membro')->first();

        foreach ($users as $user) {
            // Remover roles antigas
            $user->syncRoles([]);
            
            // Atribuir role padrão de membro
            if ($membroRole) {
                $user->assignRole($membroRole);
            }

            $this->line("   Migrado usuário: {$user->name} ({$user->email})");
        }

        $this->info("   Total de usuários migrados: {$users->count()}");
    }

    /**
     * Limpar arquivos antigos
     */
    private function cleanupOldFiles()
    {
        $oldControllers = [
            'app/Http/Controllers/MembroDashboardController.php',
            'app/Http/Controllers/DoacaoController.php',
            'app/Http/Controllers/Admin/GestaoPessoasController.php',
            'app/Http/Controllers/Admin/GestaoFinanceiraController.php',
            'app/Http/Controllers/Admin/GestaoSistemaController.php',
        ];

        foreach ($oldControllers as $controller) {
            if (file_exists($controller)) {
                rename($controller, $controller . '.backup');
                $this->line("   Backup criado: {$controller}");
            }
        }
    }

    /**
     * Criar estrutura de views
     */
    private function createNewViewStructure()
    {
        $directories = [
            'resources/views/member',
            'resources/views/member/dashboard',
            'resources/views/member/profile',
            'resources/views/member/donations',
            'resources/views/member/ministries',
            'resources/views/member/bible',
            'resources/views/admin',
            'resources/views/admin/dashboard',
            'resources/views/admin/people',
            'resources/views/admin/finance',
            'resources/views/admin/system',
            'resources/views/admin/devotionals',
            'resources/views/admin/council',
        ];

        foreach ($directories as $directory) {
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
                $this->line("   Criado diretório: {$directory}");
            }
        }
    }
} 