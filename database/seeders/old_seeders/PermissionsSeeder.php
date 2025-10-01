<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        // Criar permissões
        $permissions = [
            // Membros
            'ver membros',
            'criar membros',
            'editar membros',
            'excluir membros',
            'acesso membro',
            
            // Ministérios
            'ver ministerios',
            'criar ministerios',
            'editar ministerios',
            'excluir ministerios',
            
            // Campanhas
            'ver campanhas',
            'criar campanhas',
            'editar campanhas',
            'excluir campanhas',
            
            // Transações
            'ver transacoes',
            'criar transacoes',
            'editar transacoes',
            'excluir transacoes',
            
            // Tesouraria
            'ver tesouraria',
            'gerenciar tesouraria',
            'exportar relatorios',
            
            // Relatórios
            'ver relatorios',
            'gerar relatorios',
            
            // Administração
            'admin master',
            'gerenciar usuarios',
            'gerenciar configuracoes',
            'ver logs',
            'fazer backup',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Criar roles
        $roles = [
            'Super Admin' => $permissions,
            'Pastor' => [
                'ver membros', 'criar membros', 'editar membros',
                'ver ministerios', 'criar ministerios', 'editar ministerios',
                'ver campanhas', 'criar campanhas', 'editar campanhas',
                'ver transacoes', 'criar transacoes', 'editar transacoes',
                'ver tesouraria', 'ver relatorios', 'gerar relatorios',
            ],
            'Tesoureiro' => [
                'ver membros',
                'ver ministerios',
                'ver campanhas', 'criar campanhas', 'editar campanhas',
                'ver transacoes', 'criar transacoes', 'editar transacoes', 'excluir transacoes',
                'ver tesouraria', 'gerenciar tesouraria', 'exportar relatorios',
                'ver relatorios', 'gerar relatorios',
            ],
            'Líder' => [
                'ver membros', 'criar membros', 'editar membros',
                'ver ministerios',
                'ver campanhas',
                'ver transacoes',
                'ver relatorios',
            ],
            'Membro' => [
                'acesso membro',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }

        // Atribuir role Super Admin ao usuário admin
        $admin = \App\Models\User::where('email', 'admin@cbav.com')->first();
        if ($admin) {
            $admin->assignRole('Super Admin');
        }
    }
} 