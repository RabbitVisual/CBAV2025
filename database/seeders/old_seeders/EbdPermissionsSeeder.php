<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EbdPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar permissões EBD
        $ebdPermissions = [
            'ebd.access',
            'ebd.turmas.access',
            'ebd.turmas.create',
            'ebd.turmas.edit',
            'ebd.turmas.delete',
            'ebd.professores.access',
            'ebd.professores.create',
            'ebd.professores.edit',
            'ebd.professores.delete',
            'ebd.alunos.access',
            'ebd.alunos.create',
            'ebd.alunos.edit',
            'ebd.alunos.delete',
            'ebd.licoes.access',
            'ebd.licoes.create',
            'ebd.licoes.edit',
            'ebd.licoes.delete',
            'ebd.aulas.access',
            'ebd.aulas.create',
            'ebd.aulas.edit',
            'ebd.aulas.delete',
            'ebd.avaliacoes.access',
            'ebd.avaliacoes.create',
            'ebd.avaliacoes.edit',
            'ebd.avaliacoes.delete',
            'ebd.relatorios.access',
            'ebd.relatorios.export',
        ];

        foreach ($ebdPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Atribuir permissões EBD ao role de admin
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo($ebdPermissions);
        }

        // Atribuir permissões EBD ao role de super admin
        $superAdminRole = Role::where('name', 'super admin')->first();
        if ($superAdminRole) {
            $superAdminRole->givePermissionTo($ebdPermissions);
        }

        // Atribuir permissões EBD ao role de pastor
        $pastorRole = Role::where('name', 'pastor')->first();
        if ($pastorRole) {
            $pastorRole->givePermissionTo($ebdPermissions);
        }

        // Atribuir permissões EBD ao role de líder
        $liderRole = Role::where('name', 'lider')->first();
        if ($liderRole) {
            $liderRole->givePermissionTo([
                'ebd.access',
                'ebd.turmas.access',
                'ebd.professores.access',
                'ebd.alunos.access',
                'ebd.licoes.access',
                'ebd.aulas.access',
                'ebd.avaliacoes.access',
                'ebd.relatorios.access',
            ]);
        }

        $this->command->info('Permissões EBD criadas e atribuídas com sucesso!');
    }
} 