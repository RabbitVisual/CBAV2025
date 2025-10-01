<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ListUsers extends Command
{
    protected $signature = 'users:list';
    protected $description = 'Lista todos os usuários do sistema';

    public function handle()
    {
        $users = User::all(['name', 'email']);
        
        $this->info('Usuários do sistema:');
        $this->table(['Nome', 'Email'], $users->map(function($user) {
            return [$user->name, $user->email];
        }));
        
        return 0;
    }
} 