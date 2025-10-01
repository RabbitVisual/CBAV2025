<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Membro;

class StoreUserRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
                function ($attribute, $value, $fail) {
                    // Verificar se já existe um membro com este email que não tem usuário associado
                    $membroExistente = Membro::where('email', $value)
                        ->whereNull('user_id')
                        ->first();
                    
                    if (!$membroExistente) {
                        // Se não existe membro sem usuário, verificar se existe membro com usuário
                        $membroComUsuario = Membro::where('email', $value)
                            ->whereNotNull('user_id')
                            ->first();
                        
                        if ($membroComUsuario) {
                            $fail('Este email já está sendo usado por um membro que possui conta de usuário.');
                        }
                    }
                },
            ],
            'password' => 'required|string|min:8|confirmed',
            'is_admin' => 'required|boolean',
            'ativo' => 'boolean',
            'membro_id' => [
                'nullable',
                'exists:membros,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $membro = Membro::find($value);
                        if ($membro && $membro->user_id) {
                            $fail('Este membro já possui uma conta de usuário associada.');
                        }
                    }
                },
            ],
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
            'email_verified' => 'boolean',
            'send_welcome_email' => 'boolean',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'Este email já está sendo usado por outro usuário.',
            'membro_id.exists' => 'O membro selecionado não existe.',
            'roles.*.exists' => 'Uma ou mais funções selecionadas não existem.',
            'permissions.*.exists' => 'Uma ou mais permissões selecionadas não existem.',
        ];
    }
}