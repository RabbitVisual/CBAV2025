<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\{Role, Permission};
use App\Models\User;

class SetupPedidosOracao extends Command
{
    protected $signature = 'setup:pedidos-oracao';
    protected $description = 'Configurar sistema de pedidos de oração e permissões';

    public function handle()
    {
        $this->info('🚀 Configurando Sistema de Pedidos de Oração e Permissões');
        $this->newLine();

        // 1. Importar permissões padrão
        $this->importDefaultPermissions();
        
        // 2. Criar roles padrão
        $this->createDefaultRoles();
        
        // 3. Atribuir permissões aos roles
        $this->assignPermissionsToRoles();
        
        // 4. Limpar cache
        $this->clearCache();
        
        $this->info('✅ Sistema configurado com sucesso!');
        $this->newLine();
        $this->info('📋 Próximos passos:');
        $this->info('1. Acesse: /admin/permissions');
        $this->info('2. Configure roles e permissões específicas');
        $this->info('3. Atribua roles aos usuários');
    }

    private function importDefaultPermissions()
    {
        $this->info('🔐 Importando permissões padrão...');
        
        $permissions = [
            // Sistema
            'system.access' => 'Acesso ao sistema',
            'system.settings' => 'Configurações do sistema',
            'system.logs' => 'Visualizar logs',
            'system.backup' => 'Backup do sistema',
            
            // Pessoas
            'people.access' => 'Acesso à gestão de pessoas',
            'people.members' => 'Gerenciar membros',
            'people.users' => 'Gerenciar usuários',
            'people.ministries' => 'Gerenciar ministérios',
            'people.departments' => 'Gerenciar departamentos',
            
            // Financeiro
            'finance.access' => 'Acesso à gestão financeira',
            'finance.transactions' => 'Gerenciar transações',
            'finance.campaigns' => 'Gerenciar campanhas',
            'finance.reports' => 'Relatórios financeiros',
            
            // EBD
            'ebd.access' => 'Acesso à EBD',
            'ebd.turmas' => 'Gerenciar turmas EBD',
            'ebd.professores' => 'Gerenciar professores EBD',
            'ebd.alunos' => 'Gerenciar alunos EBD',
            'ebd.licoes' => 'Gerenciar lições EBD',
            'ebd.aulas' => 'Gerenciar aulas EBD',
            'ebd.avaliacoes' => 'Gerenciar avaliações EBD',
            'ebd.relatorios' => 'Relatórios EBD',
            
            // Devocionais
            'devotionals.access' => 'Acesso aos devocionais',
            'devotionals.create' => 'Criar devocionais',
            'devotionals.edit' => 'Editar devocionais',
            'devotionals.delete' => 'Excluir devocionais',
            
            // Conselho
            'council.access' => 'Acesso ao conselho',
            'council.meetings' => 'Gerenciar reuniões',
            'council.voting' => 'Gerenciar votações',
            'council.agenda' => 'Gerenciar agenda',
            
            // Intercessores
            'intercessor.access' => 'Acesso aos intercessores',
            'intercessor.dashboard' => 'Dashboard de intercessor',
            'intercessor.view_pedidos' => 'Visualizar pedidos',
            'intercessor.registrar_intercessao' => 'Registrar intercessão',
            'intercessor.atualizar_status' => 'Atualizar status',
            'intercessor.view_relatorios' => 'Visualizar relatórios',
            
            // Notificações
            'notifications.access' => 'Acesso às notificações',
            'notifications.create' => 'Criar notificações',
            'notifications.edit' => 'Editar notificações',
            'notifications.delete' => 'Excluir notificações',
            
            // Configurações
            'settings.access' => 'Acesso às configurações',
            'settings.edit' => 'Editar configurações',
            
            // Logs
            'logs.access' => 'Acesso aos logs',
            'logs.edit' => 'Editar logs',
        ];

        $created = 0;
        foreach ($permissions as $name => $description) {
            $permission = Permission::firstOrCreate(
                ['name' => $name],
                [
                    'name' => $name,
                    'description' => $description,
                    'guard_name' => 'web'
                ]
            );
            
            if ($permission->wasRecentlyCreated) {
                $created++;
                $this->line("  ✅ Criada: {$name}");
            }
        }
        
        $this->info("  📊 {$created} permissões criadas");
    }

    private function createDefaultRoles()
    {
        $this->info('👥 Criando roles padrão...');
        
        $roles = [
            'super admin' => 'Acesso total ao sistema',
            'admin' => 'Administrador do sistema',
            'membro' => 'Membro da igreja',
            'intercessor' => 'Intercessor de oração',
            'tesoureiro' => 'Tesoureiro da igreja',
            'lider' => 'Líder ministerial'
        ];

        $created = 0;
        foreach ($roles as $name => $description) {
            $role = Role::firstOrCreate(
                ['name' => $name],
                [
                    'name' => $name,
                    'description' => $description
                ]
            );
            
            if ($role->wasRecentlyCreated) {
                $created++;
                $this->line("  ✅ Criada: {$name}");
            }
        }
        
        $this->info("  📊 {$created} roles criadas");
    }

    private function assignPermissionsToRoles()
    {
        $this->info('🔗 Atribuindo permissões aos roles...');
        
        // Super Admin - todas as permissões
        $superAdmin = Role::where('name', 'super admin')->first();
        if ($superAdmin) {
            $permissions = Permission::all();
            $superAdmin->syncPermissions($permissions);
            $this->line("  ✅ Super Admin: {$permissions->count()} permissões");
        }
        
        // Admin - permissões administrativas
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            $adminPermissions = Permission::whereIn('name', [
                'system.access', 'system.settings', 'system.logs',
                'people.access', 'people.members', 'people.users', 'people.ministries', 'people.departments',
                'finance.access', 'finance.transactions', 'finance.campaigns', 'finance.reports',
                'ebd.access', 'ebd.turmas', 'ebd.professores', 'ebd.alunos', 'ebd.licoes', 'ebd.aulas', 'ebd.avaliacoes', 'ebd.relatorios',
                'devotionals.access', 'devotionals.create', 'devotionals.edit', 'devotionals.delete',
                'council.access', 'council.meetings', 'council.voting', 'council.agenda',
                'notifications.access', 'notifications.create', 'notifications.edit',
                'settings.access', 'settings.edit',
                'logs.access'
            ])->get();
            $admin->syncPermissions($adminPermissions);
            $this->line("  ✅ Admin: {$adminPermissions->count()} permissões");
        }
        
        // Intercessor - permissões de intercessão
        $intercessor = Role::where('name', 'intercessor')->first();
        if ($intercessor) {
            $intercessorPermissions = Permission::whereIn('name', [
                'intercessor.access', 'intercessor.dashboard', 'intercessor.view_pedidos',
                'intercessor.registrar_intercessao', 'intercessor.atualizar_status', 'intercessor.view_relatorios'
            ])->get();
            $intercessor->syncPermissions($intercessorPermissions);
            $this->line("  ✅ Intercessor: {$intercessorPermissions->count()} permissões");
        }
        
        // Tesoureiro - permissões financeiras
        $tesoureiro = Role::where('name', 'tesoureiro')->first();
        if ($tesoureiro) {
            $tesoureiroPermissions = Permission::whereIn('name', [
                'finance.access', 'finance.transactions', 'finance.campaigns', 'finance.reports'
            ])->get();
            $tesoureiro->syncPermissions($tesoureiroPermissions);
            $this->line("  ✅ Tesoureiro: {$tesoureiroPermissions->count()} permissões");
        }
        
        // Líder - permissões de liderança
        $lider = Role::where('name', 'lider')->first();
        if ($lider) {
            $liderPermissions = Permission::whereIn('name', [
                'people.access', 'people.members', 'people.ministries',
                'ebd.access', 'ebd.turmas', 'ebd.professores', 'ebd.alunos',
                'devotionals.access', 'devotionals.create', 'devotionals.edit'
            ])->get();
            $lider->syncPermissions($liderPermissions);
            $this->line("  ✅ Líder: {$liderPermissions->count()} permissões");
        }
    }

    private function clearCache()
    {
        $this->info('🧹 Limpando cache...');
        
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('route:clear');
        \Artisan::call('view:clear');
        
        // Limpar cache de permissões
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        $this->line("  ✅ Cache limpo");
    }
} 