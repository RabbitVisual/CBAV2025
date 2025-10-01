<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class AddMemberAccessPermission extends Command
{
    protected $signature = 'permission:add-member-access {email?}';
    protected $description = 'Adicionar permissão member-access ao usuário';

    public function handle()
    {
        $email = $this->argument('email') ?? 'admin@cbav.com';
        
        // Verificar se a permissão existe
        $permission = Permission::where('name', 'member-access')->first();
        if (!$permission) {
            $permission = Permission::create([
                'name' => 'member-access',
                'guard_name' => 'web'
            ]);
            $this->info('Permissão member-access criada.');
        } else {
            $this->info('Permissão member-access já existe.');
        }

        // Encontrar o usuário
        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error('Usuário não encontrado!');
            return 1;
        }

        // Adicionar permissão ao usuário
        $user->givePermissionTo('member-access');
        $this->info("Permissão member-access adicionada ao usuário: {$user->name} ({$user->email})");

        // Mostrar permissões atuais
        $this->info('Permissões atuais do usuário:');
        $permissions = $user->getAllPermissions()->pluck('name')->toArray();
        foreach ($permissions as $perm) {
            $this->line("  - {$perm}");
        }

        return 0;
    }
} 