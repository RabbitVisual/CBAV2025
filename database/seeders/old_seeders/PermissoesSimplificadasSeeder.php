<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\{Permission, Role};
use App\Models\User;

class PermissoesSimplificadasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpar permissões e roles existentes
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Criar permissões principais
        $permissions = [
            // Permissões de área
            'people.access',
            'finance.access',
            'system.access',
            'devotionals.access',
            'council.access',
            
            // Permissões de membros
            'members.access',
            'members.create',
            'members.edit',
            'members.delete',
            
            // Permissões de usuários
            'users.access',
            'users.create',
            'users.edit',
            'users.delete',
            
            // Permissões de ministérios
            'ministries.access',
            'ministries.create',
            'ministries.edit',
            'ministries.delete',
            
            // Permissões de departamentos
            'departments.access',
            'departments.create',
            'departments.edit',
            'departments.delete',
            
            // Permissões de transações
            'transactions.access',
            'transactions.create',
            'transactions.edit',
            'transactions.delete',
            
            // Permissões de campanhas
            'campaigns.access',
            'campaigns.create',
            'campaigns.edit',
            'campaigns.delete',
            
            // Permissões de relatórios
            'reports.access',
            'reports.export',
            
            // Permissões de configurações
            'settings.access',
            'settings.edit',
            
            // Permissões de notificações
            'notifications.access',
            'notifications.create',
            'notifications.edit',
            
            // Permissões de logs
            'logs.access',
            'logs.edit',
            
            // Permissão master
            'admin master',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Criar roles
        $roles = [
            'Super Admin' => $permissions,
            'Admin' => [
                'people.access', 'finance.access', 'system.access', 'devotionals.access', 'council.access',
                'members.access', 'members.create', 'members.edit',
                'users.access', 'users.create', 'users.edit',
                'ministries.access', 'ministries.create', 'ministries.edit',
                'departments.access', 'departments.create', 'departments.edit',
                'transactions.access', 'transactions.create', 'transactions.edit',
                'campaigns.access', 'campaigns.create', 'campaigns.edit',
                'reports.access', 'reports.export',
                'settings.access', 'settings.edit',
                'notifications.access', 'notifications.create', 'notifications.edit',
                'logs.access',
            ],
            'Pastor' => [
                'people.access',
                'members.access', 'members.create', 'members.edit',
                'users.access', 'users.create', 'users.edit',
                'ministries.access', 'ministries.create', 'ministries.edit',
                'departments.access', 'departments.create', 'departments.edit',
                'reports.access',
                'settings.access',
                'notifications.access', 'notifications.create',
            ],
            'Tesoureiro' => [
                'finance.access',
                'transactions.access', 'transactions.create', 'transactions.edit',
                'campaigns.access', 'campaigns.create', 'campaigns.edit',
                'reports.access', 'reports.export',
                'settings.access',
                'notifications.access',
            ],
            'Líder' => [
                'people.access',
                'members.access', 'members.edit',
                'ministries.access', 'ministries.edit',
                'departments.access', 'departments.edit',
                'reports.access',
                'notifications.access', 'notifications.create',
            ],
            'Membro' => [
                // Membros têm acesso apenas à área de membros
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->givePermissionTo($rolePermissions);
        }

        // Atribuir role Super Admin ao primeiro usuário (se existir)
        $firstUser = User::first();
        if ($firstUser) {
            $firstUser->assignRole('Super Admin');
        }

        $this->command->info('Permissões e roles simplificadas criadas com sucesso!');
    }
} 