<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProfileService
{
    /**
     * Atualiza o perfil de um usuário.
     *
     * @param User $user
     * @param array $data
     * @param UploadedFile|null $photo
     * @return User
     */
    public function updateProfile(User $user, array $data, ?UploadedFile $photo = null): User
    {
        // Atualiza os dados principais do usuário
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        // Atualiza ou cria o perfil com dados adicionais
        $profileData = [
            'telefone' => $data['telefone'] ?? null,
            'endereco' => $data['endereco'] ?? null,
        ];

        if ($photo) {
            // Remove a foto antiga, se existir
            if ($user->profile && $user->profile->foto) {
                Storage::disk('public')->delete($user->profile->foto);
            }
            // Salva a nova foto
            $profileData['foto'] = $photo->store('profiles', 'public');
        }

        $user->profile()->updateOrCreate(['user_id' => $user->id], $profileData);

        return $user->fresh();
    }

    /**
     * Altera a senha de um usuário.
     *
     * @param User $user
     * @param string $currentPassword
     * @param string $newPassword
     * @return bool
     * @throws ValidationException
     */
    public function changePassword(User $user, string $currentPassword, string $newPassword): bool
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'A senha atual está incorreta.',
            ]);
        }

        return $user->update(['password' => Hash::make($newPassword)]);
    }

    /**
     * Remove a foto de perfil de um usuário.
     *
     * @param User $user
     * @return void
     */
    public function deletePhoto(User $user): void
    {
        if ($user->profile && $user->profile->foto) {
            Storage::disk('public')->delete($user->profile->foto);
            $user->profile->update(['foto' => null]);
        }
    }
}