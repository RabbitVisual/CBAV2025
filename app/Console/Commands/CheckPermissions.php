<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Permission;

class CheckPermissions extends Command
{
    protected $signature = 'permissions:check {type=all}';
    protected $description = 'Verificar permissões do sistema';

    public function handle()
    {
        $type = $this->argument('type');
        
        if ($type === 'transactions' || $type === 'all') {
            $this->info('🔍 Verificando permissões de transações...');
            
            $permissions = Permission::where('name', 'like', '%transaction%')->get();
            
            if ($permissions->count() > 0) {
                $this->info('✅ Permissões encontradas:');
                foreach ($permissions as $permission) {
                    $this->line("  - {$permission->name}");
                }
            } else {
                $this->warn('⚠️ Nenhuma permissão de transação encontrada');
            }
        }
        
        if ($type === 'finance' || $type === 'all') {
            $this->info('🔍 Verificando permissões financeiras...');
            
            $permissions = Permission::where('name', 'like', '%finance%')->get();
            
            if ($permissions->count() > 0) {
                $this->info('✅ Permissões encontradas:');
                foreach ($permissions as $permission) {
                    $this->line("  - {$permission->name}");
                }
            } else {
                $this->warn('⚠️ Nenhuma permissão financeira encontrada');
            }
        }
        
        if ($type === 'delete' || $type === 'all') {
            $this->info('🔍 Verificando permissões de exclusão...');
            
            $permissions = Permission::where('name', 'like', '%delete%')->get();
            
            if ($permissions->count() > 0) {
                $this->info('✅ Permissões encontradas:');
                foreach ($permissions as $permission) {
                    $this->line("  - {$permission->name}");
                }
            } else {
                $this->warn('⚠️ Nenhuma permissão de exclusão encontrada');
            }
        }
        
        $this->info('✅ Verificação concluída!');
    }
} 