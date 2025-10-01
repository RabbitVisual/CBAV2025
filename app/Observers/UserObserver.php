<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Membro;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // Criar perfil de membro automaticamente para todo usuário
        $this->createMemberProfile($user);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        // Se o nome foi alterado, atualizar o nome do membro também
        if ($user->wasChanged('name')) {
            $membro = $user->membro;
            if ($membro) {
                $membro->update(['nome' => $user->name]);
            }
        }

        // Se o email foi alterado, atualizar o email do membro também
        if ($user->wasChanged('email')) {
            $membro = $user->membro;
            if ($membro) {
                $membro->update(['email' => $user->email]);
            }
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        // Se o usuário for deletado, deletar o membro associado também
        $membro = $user->membro;
        if ($membro) {
            $membro->delete();
        }
    }

    /**
     * Criar perfil de membro automaticamente
     */
    private function createMemberProfile(User $user)
    {
        // Verificar se já existe um membro com este email
        $membroExistente = Membro::where('email', $user->email)->first();
        
        if (!$membroExistente) {
            // Criar novo membro
            Membro::create([
                'user_id' => $user->id,
                'nome' => $user->name,
                'email' => $user->email,
                'ativo' => $user->ativo ?? true,
                'data_ingresso' => now(),
            ]);
        } else {
            // Associar membro existente ao usuário
            $membroExistente->update(['user_id' => $user->id]);
        }
    }
}