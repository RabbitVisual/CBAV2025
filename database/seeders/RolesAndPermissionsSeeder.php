<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔐 Configurando sistema de permissões...');

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Limpar roles e permissions existentes
        Role::query()->delete();
        Permission::query()->delete();

        // Criar permissões
        $permissions = [
            // Permissões de acesso geral
            'admin master' => 'Acesso master ao sistema',
            'acesso admin' => 'Acesso total ao painel administrativo',
            'acesso membro' => 'Acesso à área de membros',
            'acesso tesoureiro' => 'Acesso à tesouraria',
            'acesso lider' => 'Acesso de líder ministerial',
            
            // Permissões de acesso por seção
            'people.access' => 'Acesso à gestão de pessoas',
            'finance.access' => 'Acesso à gestão financeira',
            'system.access' => 'Acesso ao sistema',
            'devotionals.access' => 'Acesso aos devocionais',
            'council.access' => 'Acesso ao conselho',
            'ebd.access' => 'Acesso à EBD',
            'ebd.turmas.access' => 'Acesso às turmas EBD',
            'ebd.professores.access' => 'Acesso aos professores EBD',
            'ebd.alunos.access' => 'Acesso aos alunos EBD',
            'ebd.licoes.access' => 'Acesso às lições EBD',
            'ebd.aulas.access' => 'Acesso às aulas EBD',
            'ebd.avaliacoes.access' => 'Acesso às avaliações EBD',
            'ebd.relatorios.access' => 'Acesso aos relatórios EBD',
            'ebd.quiz.access' => 'Acesso ao quiz bíblico',
            
            // Membros
            'ver membros' => 'Visualizar membros',
            'criar membros' => 'Criar novos membros',
            'editar membros' => 'Editar membros',
            'excluir membros' => 'Excluir membros',
            'members.access' => 'Acesso aos membros',
            'members.create' => 'Criar membros',
            'members.edit' => 'Editar membros',
            'members.delete' => 'Excluir membros',
            
            // Ministérios
            'ver ministerios' => 'Visualizar ministérios',
            'criar ministerios' => 'Criar ministérios',
            'editar ministerios' => 'Editar ministérios',
            'excluir ministerios' => 'Excluir ministérios',
            'ministries.access' => 'Acesso aos ministérios',
            'ministries.create' => 'Criar ministérios',
            'ministries.edit' => 'Editar ministérios',
            'ministries.delete' => 'Excluir ministérios',
            
            // Departamentos
            'ver departamentos' => 'Visualizar departamentos',
            'criar departamentos' => 'Criar departamentos',
            'editar departamentos' => 'Editar departamentos',
            'excluir departamentos' => 'Excluir departamentos',
            'departments.access' => 'Acesso aos departamentos',
            'departments.create' => 'Criar departamentos',
            'departments.edit' => 'Editar departamentos',
            'departments.delete' => 'Excluir departamentos',
            
            // Cargos
            'ver cargos' => 'Visualizar cargos',
            'criar cargos' => 'Criar cargos',
            'editar cargos' => 'Editar cargos',
            'excluir cargos' => 'Excluir cargos',
            
            // Campanhas
            'ver campanhas' => 'Visualizar campanhas',
            'criar campanhas' => 'Criar campanhas',
            'editar campanhas' => 'Editar campanhas',
            'excluir campanhas' => 'Excluir campanhas',
            'campaigns.access' => 'Acesso às campanhas',
            'campaigns.create' => 'Criar campanhas',
            'campaigns.edit' => 'Editar campanhas',
            'campaigns.delete' => 'Excluir campanhas',
            
            // Transações
            'ver transacoes' => 'Visualizar transações',
            'criar transacoes' => 'Criar transações',
            'editar transacoes' => 'Editar transações',
            'excluir transacoes' => 'Excluir transações',
            'transactions.access' => 'Acesso às transações',
            'transactions.create' => 'Criar transações',
            'transactions.edit' => 'Editar transações',
            'transactions.delete' => 'Excluir transações',
            
            // Relatórios
            'ver relatorios' => 'Visualizar relatórios',
            'exportar relatorios' => 'Exportar relatórios',
            'reports.access' => 'Acesso aos relatórios',
            'reports.export' => 'Exportar relatórios',
            
            // Configurações
            'ver configuracoes' => 'Visualizar configurações',
            'editar configuracoes' => 'Editar configurações',
            'settings.access' => 'Acesso às configurações',
            'settings.edit' => 'Editar configurações',
            
            // Usuários
            'ver usuarios' => 'Visualizar usuários',
            'criar usuarios' => 'Criar usuários',
            'editar usuarios' => 'Editar usuários',
            'excluir usuarios' => 'Excluir usuários',
            'users.access' => 'Acesso aos usuários',
            'users.create' => 'Criar usuários',
            'users.edit' => 'Editar usuários',
            'users.delete' => 'Excluir usuários',
            
            // Devocionais
            'ver devocionais' => 'Visualizar devocionais',
            'criar devocionais' => 'Criar devocionais',
            'editar devocionais' => 'Editar devocionais',
            'excluir devocionais' => 'Excluir devocionais',
            
            // Notificações
            'ver notificacoes' => 'Visualizar notificações',
            'criar notificacoes' => 'Criar notificações',
            'gerenciar notificacoes' => 'Gerenciar notificações',
            'notifications.access' => 'Acesso às notificações',
            'notifications.create' => 'Criar notificações',
            'notifications.edit' => 'Editar notificações',
            
            // Sistema
            'acesso sistema' => 'Acesso ao sistema',
            'backup sistema' => 'Fazer backup do sistema',
            'ver logs' => 'Visualizar logs do sistema',
            'logs sistema' => 'Visualizar logs do sistema',
            'logs.access' => 'Acesso aos logs',
            'logs.edit' => 'Editar logs',
            
            // Conselho
            'ver conselho' => 'Visualizar conselho',
            'criar conselho' => 'Criar reuniões do conselho',
            'editar conselho' => 'Editar reuniões do conselho',
            'excluir conselho' => 'Excluir reuniões do conselho',
            'votar conselho' => 'Votar no conselho',
            'gerenciar conselho' => 'Gerenciar conselho',
            
            // Intercessão
            'intercessor.access' => 'Acesso à área de intercessão',
            'intercessor.dashboard' => 'Visualizar dashboard de intercessor',
            'intercessor.view_pedidos' => 'Visualizar pedidos de oração',
            'intercessor.registrar_intercessao' => 'Registrar intercessões',
            'intercessor.atualizar_status' => 'Atualizar status dos pedidos',
            'intercessor.view_relatorios' => 'Visualizar relatórios',
        ];

        foreach ($permissions as $permission => $description) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Criar roles com permissões específicas
        $roles = [
            'Super Admin' => [
                'admin master',
                'acesso admin',
                'acesso membro',
                'acesso tesoureiro',
                'acesso lider',
                'acesso sistema',
                'people.access', 'finance.access', 'system.access', 'devotionals.access', 'council.access',
                'ebd.access', 'ebd.turmas.access', 'ebd.professores.access', 'ebd.alunos.access',
                'ebd.licoes.access', 'ebd.aulas.access', 'ebd.avaliacoes.access', 'ebd.relatorios.access', 'ebd.quiz.access',
                'ver membros', 'criar membros', 'editar membros', 'excluir membros',
                'members.access', 'members.create', 'members.edit', 'members.delete',
                'ver ministerios', 'criar ministerios', 'editar ministerios', 'excluir ministerios',
                'ministries.access', 'ministries.create', 'ministries.edit', 'ministries.delete',
                'ver departamentos', 'criar departamentos', 'editar departamentos', 'excluir departamentos',
                'departments.access', 'departments.create', 'departments.edit', 'departments.delete',
                'ver cargos', 'criar cargos', 'editar cargos', 'excluir cargos',
                'ver campanhas', 'criar campanhas', 'editar campanhas', 'excluir campanhas',
                'campaigns.access', 'campaigns.create', 'campaigns.edit', 'campaigns.delete',
                'ver transacoes', 'criar transacoes', 'editar transacoes', 'excluir transacoes',
                'transactions.access', 'transactions.create', 'transactions.edit', 'transactions.delete',
                'ver relatorios', 'exportar relatorios',
                'reports.access', 'reports.export',
                'ver configuracoes', 'editar configuracoes',
                'settings.access', 'settings.edit',
                'ver usuarios', 'criar usuarios', 'editar usuarios', 'excluir usuarios',
                'users.access', 'users.create', 'users.edit', 'users.delete',
                'ver devocionais', 'criar devocionais', 'editar devocionais', 'excluir devocionais',
                'ver notificacoes', 'criar notificacoes', 'gerenciar notificacoes',
                'notifications.access', 'notifications.create', 'notifications.edit',
                'ver conselho', 'criar conselho', 'editar conselho', 'excluir conselho', 'votar conselho', 'gerenciar conselho',
                'intercessor.access', 'intercessor.dashboard', 'intercessor.view_pedidos', 'intercessor.registrar_intercessao', 'intercessor.atualizar_status', 'intercessor.view_relatorios',
                'backup sistema', 'ver logs', 'logs sistema',
                'logs.access', 'logs.edit'
            ],
            'Pastor' => [
                'acesso admin',
                'acesso membro',
                'acesso tesoureiro',
                'acesso lider',
                'people.access', 'finance.access', 'system.access', 'devotionals.access', 'council.access',
                'ebd.access', 'ebd.turmas.access', 'ebd.professores.access', 'ebd.alunos.access',
                'ebd.licoes.access', 'ebd.aulas.access', 'ebd.avaliacoes.access', 'ebd.relatorios.access', 'ebd.quiz.access',
                'ver membros', 'criar membros', 'editar membros',
                'members.access', 'members.create', 'members.edit',
                'ver ministerios', 'criar ministerios', 'editar ministerios',
                'ministries.access', 'ministries.create', 'ministries.edit',
                'ver departamentos', 'criar departamentos', 'editar departamentos',
                'departments.access', 'departments.create', 'departments.edit',
                'ver cargos', 'criar cargos', 'editar cargos',
                'ver campanhas', 'criar campanhas', 'editar campanhas',
                'campaigns.access', 'campaigns.create', 'campaigns.edit',
                'ver transacoes', 'criar transacoes', 'editar transacoes',
                'transactions.access', 'transactions.create', 'transactions.edit',
                'ver relatorios', 'exportar relatorios',
                'reports.access', 'reports.export',
                'ver configuracoes', 'editar configuracoes',
                'settings.access', 'settings.edit',
                'ver usuarios', 'criar usuarios', 'editar usuarios',
                'users.access', 'users.create', 'users.edit',
                'ver devocionais', 'criar devocionais', 'editar devocionais',
                'ver notificacoes', 'criar notificacoes', 'gerenciar notificacoes',
                'notifications.access', 'notifications.create', 'notifications.edit',
                'ver conselho', 'criar conselho', 'editar conselho', 'votar conselho',
                'intercessor.access', 'intercessor.dashboard', 'intercessor.view_pedidos', 'intercessor.registrar_intercessao', 'intercessor.atualizar_status', 'intercessor.view_relatorios',
                'ver logs',
                'logs.access'
            ],
            'Tesoureiro' => [
                'acesso tesoureiro',
                'acesso membro',
                'people.access', 'finance.access',
                'ver membros',
                'members.access',
                'ver ministerios',
                'ministries.access',
                'ver departamentos',
                'departments.access',
                'ver cargos',
                'ver campanhas', 'criar campanhas', 'editar campanhas',
                'campaigns.access', 'campaigns.create', 'campaigns.edit',
                'ver transacoes', 'criar transacoes', 'editar transacoes',
                'transactions.access', 'transactions.create', 'transactions.edit',
                'ver relatorios', 'exportar relatorios',
                'reports.access', 'reports.export',
                'ver notificacoes',
                'notifications.access'
            ],
            'Líder' => [
                'acesso lider',
                'acesso membro',
                'people.access',
                'ver membros', 'criar membros', 'editar membros',
                'members.access', 'members.create', 'members.edit',
                'ver ministerios',
                'ministries.access',
                'ver departamentos',
                'departments.access',
                'ver cargos',
                'ver campanhas',
                'campaigns.access',
                'ver transacoes',
                'transactions.access',
                'ver relatorios',
                'reports.access',
                'ver notificacoes',
                'notifications.access',
                'intercessor.access', 'intercessor.dashboard', 'intercessor.view_pedidos', 'intercessor.registrar_intercessao', 'intercessor.atualizar_status', 'intercessor.view_relatorios'
            ],
            'Membro' => [
                'acesso membro',
                'ver notificacoes',
                'notifications.access',
                'ebd.quiz.access'
            ]
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::create(['name' => $roleName, 'guard_name' => 'web']);
            $role->givePermissionTo($rolePermissions);
        }

        $this->command->info('✅ Sistema de permissões configurado');
    }
} 