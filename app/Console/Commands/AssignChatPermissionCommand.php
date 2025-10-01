<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class AssignChatPermissionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat:assign-permission {email? : Email do usuário}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atribuir permissão de chat a um usuário';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        if ($email) {
            $user = User::where('email', $email)->first();
            if (!$user) {
                $this->error("Usuário com email {$email} não encontrado!");
                return;
            }
        } else {
            // Buscar primeiro usuário admin
            $user = User::whereHas('roles', function($query) {
                $query->where('name', 'admin');
            })->first();
            
            if (!$user) {
                $user = User::first();
            }
            
            if (!$user) {
                $this->error("Nenhum usuário encontrado!");
                return;
            }
        }
        
        // Verificar se a permissão existe
        $permission = Permission::where('name', 'chat.access')->first();
        if (!$permission) {
            $this->error("Permissão 'chat.access' não encontrada!");
            return;
        }
        
        // Atribuir permissão
        $user->givePermissionTo('chat.access');
        
        $this->info("Permissão 'chat.access' atribuída com sucesso ao usuário {$user->name} ({$user->email})!");
    }
} 