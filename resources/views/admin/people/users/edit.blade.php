@extends('layouts.admin')

@section('title', 'Editar Usuário')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Editar Usuário</h1>
        <a href="{{ route('admin.people.users.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Voltar
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg">
        <div class="p-6">
            <form action="{{ route('admin.people.users.update', $user->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Informações Básicas -->
                <div class="border-b border-gray-200 dark:border-gray-600 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Informações Básicas') }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Nome Completo') }} *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                                   placeholder="{{ __('Digite o nome completo') }}">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('E-mail') }} *</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                                   placeholder="{{ __('exemplo@email.com') }}">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ativo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Status') }}</label>
                            <select name="ativo" id="ativo" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('ativo') border-red-500 @enderror">
                                <option value="1" {{ old('ativo', $user->ativo) == 1 ? 'selected' : '' }}>{{ __('Ativo') }}</option>
                                <option value="0" {{ old('ativo', $user->ativo) == 0 ? 'selected' : '' }}>{{ __('Inativo') }}</option>
                            </select>
                            @error('ativo')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Alteração de Senha -->
                <div class="border-b border-gray-200 dark:border-gray-600 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Alteração de Senha') }}</h3>
                    
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4 mb-4">
                        <p class="text-sm text-blue-800 dark:text-blue-300">
                            <i class="fas fa-info-circle mr-2"></i>
                            {{ __('Deixe os campos de senha em branco se não quiser alterar a senha atual.') }}
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Nova Senha') }}</label>
                            <input type="password" name="password" id="password" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                                   placeholder="{{ __('Mínimo 8 caracteres') }}">
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Confirmar Nova Senha') }}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="{{ __('Confirme a nova senha') }}">
                        </div>
                    </div>
                </div>

                <!-- Permissões e Roles -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Permissões e Funções') }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="roles" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Funções') }}</label>
                            <select name="roles[]" id="roles" multiple
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('roles') border-red-500 @enderror">
                                @foreach($roles ?? [] as $role)
                                    <option value="{{ $role->id }}" {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Pressione Ctrl (ou Cmd) para selecionar múltiplas funções') }}</p>
                            @error('roles')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="permissions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Permissões Diretas') }}</label>
                            <select name="permissions[]" id="permissions" multiple
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('permissions') border-red-500 @enderror">
                                @foreach($permissions ?? [] as $permission)
                                    <option value="{{ $permission->id }}" {{ in_array($permission->id, old('permissions', $user->permissions->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $permission->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Permissões adicionais além das funções') }}</p>
                            @error('permissions')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Membro Associado -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Membro Associado') }}</h3>
                    
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4 mb-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mt-1 mr-3"></i>
                            <div>
                                <p class="text-sm text-blue-800 dark:text-blue-300 font-medium mb-1">{{ __('Status do Membro:') }}</p>
                                @if($user->membro)
                                    <p class="text-sm text-blue-700 dark:text-blue-300">{{ $user->membro->nome }} ({{ $user->membro->email }})</p>
                                    <a href="{{ route('admin.people.members.edit', $user->membro) }}" class="inline-flex items-center mt-2 text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                        <i class="fas fa-edit mr-1"></i> {{ __('Editar Dados do Membro') }}
                                    </a>
                                @else
                                    <p class="text-sm text-blue-700 dark:text-blue-300">{{ __('Nenhum membro associado. Um perfil de membro será criado automaticamente.') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informações Adicionais -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Informações Adicionais') }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Telefone') }}</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror"
                                   placeholder="{{ __('(11) 99999-9999') }}">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Departamento') }}</label>
                            <input type="text" name="department" id="department" value="{{ old('department', $user->department) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('department') border-red-500 @enderror"
                                   placeholder="{{ __('Departamento ou área') }}">
                            @error('department')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Cargo') }}</label>
                            <input type="text" name="position" id="position" value="{{ old('position', $user->position) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('position') border-red-500 @enderror"
                                   placeholder="{{ __('Cargo ou função') }}">
                            @error('position')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Observações') }}</label>
                            <textarea name="notes" id="notes" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-500 @enderror"
                                      placeholder="{{ __('Informações adicionais sobre o usuário') }}">{{ old('notes', $user->notes) }}</textarea>
                            @error('notes')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Configurações de Conta -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Configurações de Conta') }}</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="email_verified" id="email_verified" value="1" {{ old('email_verified', $user->email_verified_at ? '1' : '') ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded focus:ring-blue-500">
                            <label for="email_verified" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                {{ __('E-mail verificado') }}
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="force_password_change" id="force_password_change" value="1" {{ old('force_password_change', $user->force_password_change ? '1' : '') ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded focus:ring-blue-500">
                            <label for="force_password_change" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                {{ __('Forçar alteração de senha no próximo login') }}
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="two_factor_enabled" id="two_factor_enabled" value="1" {{ old('two_factor_enabled', $user->two_factor_enabled ? '1' : '') ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded focus:ring-blue-500">
                            <label for="two_factor_enabled" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                {{ __('Habilitar autenticação de dois fatores') }}
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Informações de Login -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Informações de Login') }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Data de Criação') }}</label>
                            <p class="text-gray-900 dark:text-gray-300">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Último Login') }}</label>
                            <p class="text-gray-900 dark:text-gray-300">
                                {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : __('Nunca acessou') }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('IP do Último Login') }}</label>
                            <p class="text-gray-900 dark:text-gray-300">{{ $user->last_login_ip ?: __('Não registrado') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Total de Logins') }}</label>
                            <p class="text-gray-900 dark:text-gray-300">{{ $user->login_count ?: '0' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="flex justify-end space-x-4 pt-6">
                    <a href="{{ route('admin.people.users.index') }}" 
                       class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        {{ __('Cancelar') }}
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i>{{ __('Atualizar Usuário') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Máscara para telefone
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{2})(\d)/, '($1) $2');
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
                e.target.value = value;
            }
        });
    }

    // Validação de senha
    const passwordInput = document.getElementById('password');
    const passwordConfirmationInput = document.getElementById('password_confirmation');
    
    if (passwordInput && passwordConfirmationInput) {
        function validatePassword() {
            if (passwordInput.value !== passwordConfirmationInput.value) {
                passwordConfirmationInput.setCustomValidity('{{ __("As senhas não coincidem") }}');
            } else {
                passwordConfirmationInput.setCustomValidity('');
            }
        }
        
        passwordInput.addEventListener('input', validatePassword);
        passwordConfirmationInput.addEventListener('input', validatePassword);
    }

    // Validação de força da senha
    if (passwordInput) {
        passwordInput.addEventListener('input', function(e) {
            const password = e.target.value;
            if (password.length === 0) {
                passwordInput.classList.remove('border-red-500', 'border-yellow-500', 'border-green-500');
                return;
            }
            
            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            // Remover classes anteriores
            passwordInput.classList.remove('border-red-500', 'border-yellow-500', 'border-green-500');
            
            // Adicionar classe baseada na força
            if (strength < 3) {
                passwordInput.classList.add('border-red-500');
            } else if (strength < 5) {
                passwordInput.classList.add('border-yellow-500');
            } else {
                passwordInput.classList.add('border-green-500');
            }
        });
    }
});
</script>
@endpush
@endsection