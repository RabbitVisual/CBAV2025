<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Permission;

class CheckUserPermissions extends Command
{
    protected $signature = 'user:permissions {user_id=1}';
    protected $description = 'Verificar permissões de um usuário específico';

    public function handle()
    {
        $userId = $this->argument('user_id');
        
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("❌ Usuário com ID {$userId} não encontrado!");
            return 1;
        }
        
        $this->info("👤 Verificando permissões do usuário: {$user->name} (ID: {$user->id})");
        
        // Verificar roles
        $roles = $user->roles;
        if ($roles->count() > 0) {
            $this->info('🎭 Roles:');
            foreach ($roles as $role) {
                $this->line("  - {$role->name}");
            }
        } else {
            $this->warn('⚠️ Usuário não tem roles atribuídos');
        }
        
        // Verificar permissões diretas
        $permissions = $user->permissions;
        if ($permissions->count() > 0) {
            $this->info('🔑 Permissões diretas:');
            foreach ($permissions as $permission) {
                $this->line("  - {$permission->name}");
            }
        } else {
            $this->warn('⚠️ Usuário não tem permissões diretas');
        }
        
        // Verificar permissões específicas
        $specificPermissions = [
            'transactions.delete',
            'transactions.access',
            'finance.access',
            'admin.access'
        ];
        
        $this->info('🔍 Verificando permissões específicas:');
        foreach ($specificPermissions as $permission) {
            $hasPermission = $user->hasPermissionTo($permission);
            $status = $hasPermission ? '✅' : '❌';
            $this->line("  {$status} {$permission}");
        }
        
        $this->info('✅ Verificação concluída!');
    }
} 