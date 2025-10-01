<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ChatPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Criar permissão de acesso ao chat
        $chatPermission = Permission::firstOrCreate(['name' => 'chat.access']);

        // Atribuir permissão aos roles de admin
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo($chatPermission);
        }

        $superAdminRole = Role::where('name', 'super_admin')->first();
        if ($superAdminRole) {
            $superAdminRole->givePermissionTo($chatPermission);
        }

        $this->command->info('Permissões do chat criadas com sucesso!');
    }
} 