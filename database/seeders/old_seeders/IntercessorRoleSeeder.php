<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class IntercessorRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar role de intercessor
        $intercessorRole = Role::firstOrCreate(['name' => 'intercessor']);

        // Criar permissões para intercessor
        $permissions = [
            'intercessor.access',
            'intercessor.dashboard',
            'intercessor.view_pedidos',
            'intercessor.registrar_intercessao',
            'intercessor.atualizar_status',
            'intercessor.view_relatorios',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Atribuir permissões ao role
        $intercessorRole->givePermissionTo($permissions);

        $this->command->info('Role e permissões de intercessor criadas com sucesso!');
    }
} 