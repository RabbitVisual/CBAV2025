<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'create:admin-user';
    protected $description = 'Criar usuário administrador';

    public function handle()
    {
        $this->info('👤 Criando usuário administrador...');

        $user = User::where('email', 'admin@teste.com')->first();
        
        if (!$user) {
            $user = User::create([
                'name' => 'Administrador',
                'email' => 'admin@teste.com',
                'password' => Hash::make('senha123')
            ]);
            $this->line('  ✅ Usuário criado com sucesso!');
        } else {
            $this->line('  ✅ Usuário já existe!');
        }

        // Atribuir role de Super Admin
        $user->assignRole('Super Admin');
        $this->line('  ✅ Role Super Admin atribuída!');

        $this->newLine();
        $this->info('🔑 Credenciais do Administrador:');
        $this->line('  📧 Email: admin@teste.com');
        $this->line('  🔐 Senha: senha123');
        $this->line('  👑 Role: Super Admin');

        $this->newLine();
        $this->info('✅ Usuário administrador criado com sucesso!');
        
        return 0;
    }
} 