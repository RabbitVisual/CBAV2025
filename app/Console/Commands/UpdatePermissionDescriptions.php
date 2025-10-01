<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use App\Helpers\PermissionHelper;

class UpdatePermissionDescriptions extends Command
{
    protected $signature = 'permissions:update-descriptions';
    protected $description = 'Atualizar descrições das permissões no banco de dados';

    public function handle()
    {
        $this->info('🔄 Atualizando descrições das permissões...');
        $this->newLine();

        $permissions = Permission::all();
        $updated = 0;

        foreach ($permissions as $permission) {
            $description = PermissionHelper::getPermissionDescription($permission->name);
            
            if ($permission->description !== $description) {
                $permission->update(['description' => $description]);
                $this->line("  ✅ {$permission->name}: {$description}");
                $updated++;
            }
        }

        $this->newLine();
        $this->info("✅ {$updated} permissões atualizadas com sucesso!");
        
        return 0;
    }
} 