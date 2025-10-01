<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EventoPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar permissões para eventos
        $permissions = [
            'eventos.access',
            'eventos.create',
            'eventos.edit',
            'eventos.delete',
            'eventos.view',
            'eventos.manage',
            'eventos.inscricoes.view',
            'eventos.inscricoes.manage',
            'eventos.pagamentos.view',
            'eventos.pagamentos.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Atribuir permissões ao role Super Admin
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        if ($superAdminRole) {
            $superAdminRole->givePermissionTo($permissions);
        }

        // Atribuir permissões ao role Admin
        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo($permissions);
        }

        // Atribuir permissões básicas ao role Moderator
        $moderatorRole = Role::where('name', 'Moderator')->first();
        if ($moderatorRole) {
            $moderatorRole->givePermissionTo([
                'eventos.access',
                'eventos.view',
                'eventos.inscricoes.view',
                'eventos.pagamentos.view',
            ]);
        }

        $this->command->info('Permissões de eventos criadas e atribuídas com sucesso!');
    }
} 