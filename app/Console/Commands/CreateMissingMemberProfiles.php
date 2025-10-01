<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Membro;

class CreateMissingMemberProfiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'members:create-profiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Criar perfis de membro para todos os usuários que não têm';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando criação de perfis de membro...');

        $users = User::all();
        $created = 0;
        $skipped = 0;

        foreach ($users as $user) {
            // Verificar se já existe um membro com este email
            $existingMembro = Membro::where('email', $user->email)->first();
            
            if (!$existingMembro) {
                try {
                    // Criar membro básico
                    Membro::create([
                        'nome' => $user->name,
                        'email' => $user->email,
                        'data_nascimento' => now()->subYears(25), // Data padrão
                        'telefone' => '',
                        'endereco' => '',
                        'cidade' => '',
                        'estado' => '',
                        'cep' => '',
                        'data_batismo' => null,
                        'ativo' => true,
                        'data_ingresso' => now(),
                        'observacoes' => 'Perfil criado automaticamente pelo comando.',
                    ]);
                    
                    $created++;
                    $this->info("✅ Criado perfil para: {$user->name} ({$user->email})");
                } catch (\Exception $e) {
                    $this->error("❌ Erro ao criar perfil para {$user->name}: " . $e->getMessage());
                }
            } else {
                $skipped++;
                $this->line("⏭️  Perfil já existe para: {$user->name} ({$user->email})");
            }
        }

        $this->info("\n📊 Resumo:");
        $this->info("   - Perfis criados: {$created}");
        $this->info("   - Perfis já existentes: {$skipped}");
        $this->info("   - Total de usuários: " . count($users));
        
        $this->info("\n✅ Processo concluído!");
    }
} 