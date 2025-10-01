<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class CheckMemberAccess extends Command
{
    protected $signature = 'member:check-access';
    protected $description = 'Verificar e criar permissão member-access';

    public function handle()
    {
        $this->info('=== VERIFICAÇÃO DE PERMISSÃO MEMBER-ACCESS ===');
        
        $permission = Permission::where('name', 'member-access')->first();
        
        if (!$permission) {
            $this->warn('Permissão member-access não existe. Criando...');
            $permission = Permission::create([
                'name' => 'member-access',
                'guard_name' => 'web'
            ]);
            $this->info('✓ Permissão member-access criada com sucesso!');
        } else {
            $this->info('✓ Permissão member-access já existe.');
        }
        
        $this->info('=== ATRIBUINDO PERMISSÃO A TODOS OS USUÁRIOS ===');
        $users = User::all();
        
        foreach ($users as $user) {
            if (!$user->hasPermissionTo('member-access')) {
                $user->givePermissionTo('member-access');
                $this->info("✓ Permissão atribuída ao usuário: {$user->email}");
            } else {
                $this->info("✓ Usuário {$user->email} já tem a permissão");
            }
        }
        
        $this->info('=== VERIFICAÇÃO FINAL ===');
        foreach ($users as $user) {
            $hasPermission = $user->hasPermissionTo('member-access');
            $status = $hasPermission ? '✓' : '✗';
            $this->line("{$status} {$user->email}: " . ($hasPermission ? 'Tem permissão' : 'Sem permissão'));
        }
        
        return 0;
    }
} 