<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EbdAluno;
use App\Models\User;

class CheckEbdAlunos extends Command
{
    protected $signature = 'ebd:check-alunos';
    protected $description = 'Verificar alunos EBD e suas informações';

    public function handle()
    {
        $this->info('=== VERIFICAÇÃO DE ALUNOS EBD ===');
        
        $alunos = EbdAluno::all();
        $this->info("Total de alunos EBD: {$alunos->count()}");
        
        $this->table(
            ['ID', 'Nome', 'Email', 'Membro ID', 'Status', 'Turma'],
            $alunos->map(function($aluno) {
                return [
                    $aluno->id,
                    $aluno->nome,
                    $aluno->email ?? 'N/A',
                    $aluno->membro_id ?? 'N/A',
                    $aluno->status,
                    $aluno->turma ? $aluno->turma->nome : 'N/A'
                ];
            })->toArray()
        );
        
        $this->info('=== VERIFICAÇÃO DE USUÁRIOS LOGADOS ===');
        $users = User::all();
        $this->info("Total de usuários: {$users->count()}");
        
        $this->table(
            ['ID', 'Nome', 'Email', 'Membro ID'],
            $users->map(function($user) {
                return [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->membro ? $user->membro->id : 'N/A'
                ];
            })->toArray()
        );
        
        $this->info('=== VERIFICAÇÃO DE MATCHING ===');
        foreach ($users as $user) {
            $aluno = EbdAluno::where('email', $user->email)
                            ->orWhere('membro_id', $user->membro?->id)
                            ->first();
            
            if ($aluno) {
                $this->info("✓ Usuário {$user->email} MATCH com aluno EBD: {$aluno->nome}");
            } else {
                $this->warn("✗ Usuário {$user->email} NÃO encontrado como aluno EBD");
            }
        }
        
        return 0;
    }
} 