<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class AssignEbdPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ebd:assign-permissions {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atribuir permissões EBD ao usuário especificado ou ao primeiro admin encontrado';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        if ($email) {
            $user = User::where('email', $email)->first();
        } else {
            $user = User::whereHas('roles', function($query) {
                $query->whereIn('name', ['admin', 'super admin', 'pastor']);
            })->first();
        }

        if (!$user) {
            $this->error('Usuário não encontrado!');
            return 1;
        }

        $this->info("Atribuindo permissões EBD ao usuário: {$user->name} ({$user->email})");

        // Permissões EBD
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
            $perm = Permission::where('name', $permission)->first();
            if ($perm) {
                $user->givePermissionTo($permission);
                $this->line("✓ {$permission}");
            } else {
                $this->warn("✗ {$permission} (não encontrada)");
            }
        }

        $this->info('Permissões EBD atribuídas com sucesso!');
        
        // Mostrar permissões atuais
        $this->info('Permissões atuais do usuário:');
        $permissions = $user->getAllPermissions()->pluck('name')->toArray();
        foreach ($permissions as $permission) {
            $this->line("  - {$permission}");
        }

        return 0;
    }
} 