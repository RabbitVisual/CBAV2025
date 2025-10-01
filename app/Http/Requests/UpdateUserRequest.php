<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Membro;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $userId = $this->route('user')->id;
        
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
                function ($attribute, $value, $fail) use ($userId) {
                    // Verificar se existe membro com este email que não é o membro atual do usuário
                    $user = User::find($userId);
                    $membroAtual = $user->membro;
                    
                    $membroConflitante = Membro::where('email', $value)
                        ->when($membroAtual, function ($query) use ($membroAtual) {
                            return $query->where('id', '!=', $membroAtual->id);
                        })
                        ->first();
                    
                    if ($membroConflitante) {
                        if ($membroConflitante->user_id && $membroConflitante->user_id != $userId) {
                            $fail('Este email já está sendo usado por outro membro que possui conta de usuário.');
                        }
                    }
                },
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'ativo' => 'boolean',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'Este email já está sendo usado por outro usuário.',
            'roles.*.exists' => 'Uma ou mais funções selecionadas não existem.',
            'permissions.*.exists' => 'Uma ou mais permissões selecionadas não existem.',
        ];
    }
}