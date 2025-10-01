@extends('layouts.admin')

@section('title', 'Editar Perfil')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Editar Perfil</h1>
                <p class="text-gray-600 mt-2">Atualize suas informações pessoais</p>
            </div>
            <a href="{{ route('admin.profile.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Informações Pessoais</h2>
            </div>
            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')

                <!-- Foto do Perfil -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto do Perfil</label>
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            @if($user->foto && Storage::disk('public')->exists($user->foto))
                                <img class="h-20 w-20 rounded-full object-cover" 
                                     src="{{ Storage::url($user->foto) }}?v={{ md5($user->foto . $user->updated_at) }}" 
                                     alt="Foto atual">
                            @elseif($membro && $membro->foto && Storage::disk('public')->exists($membro->foto))
                                <img class="h-20 w-20 rounded-full object-cover" 
                                     src="{{ Storage::url($membro->foto) }}?v={{ md5($membro->foto . $membro->updated_at) }}" 
                                     alt="Foto atual">
                            @else
                                <div class="h-20 w-20 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-2xl font-bold text-blue-600">
                                        {{ $user->iniciais }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" name="foto" accept="image/*" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-sm text-gray-500 mt-1">Formatos aceitos: JPEG, PNG, JPG, GIF. Máximo 2MB.</p>
                        </div>
                    </div>
                </div>

                <!-- Nome -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nome Completo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- E-mail -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        E-mail <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                @if($membro)
                    <!-- Telefone -->
                    <div class="mb-4">
                        <label for="telefone" class="block text-sm font-medium text-gray-700 mb-2">
                            Telefone
                        </label>
                        <input type="text" id="telefone" name="telefone" 
                               value="{{ old('telefone', $membro->telefone) }}"
                               placeholder="(00) 00000-0000"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Endereço -->
                    <div class="mb-6">
                        <label for="endereco" class="block text-sm font-medium text-gray-700 mb-2">
                            Endereço
                        </label>
                        <textarea id="endereco" name="endereco" rows="3"
                                  placeholder="Digite seu endereço completo"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('endereco', $membro->endereco) }}</textarea>
                    </div>
                @endif

                <!-- Botões -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.profile.index') }}" 
                       class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-save mr-2"></i>
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>

        <!-- Alterar Senha -->
        <div class="bg-white rounded-lg shadow-md mt-6">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Alterar Senha</h2>
                <p class="text-sm text-gray-600 mt-1">Altere sua senha de acesso ao sistema</p>
            </div>
            <form action="{{ route('admin.profile.change-password') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Senha Atual <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="current_password" name="current_password" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Nova Senha <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="password" name="password" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-sm text-gray-500 mt-1">Mínimo 8 caracteres</p>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmar Nova Senha <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="submit" 
                            class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                        <i class="fas fa-key mr-2"></i>
                        Alterar Senha
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Máscara para telefone
document.getElementById('telefone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length <= 11) {
        value = value.replace(/(\d{2})(\d)/, '($1) $2');
        value = value.replace(/(\d{5})(\d)/, '$1-$2');
        e.target.value = value;
    }
});

// Validação de força da senha
document.getElementById('password').addEventListener('input', function(e) {
    const password = e.target.value;
    const strength = calculatePasswordStrength(password);
    updatePasswordStrengthIndicator(strength);
});

function calculatePasswordStrength(password) {
    let strength = 0;
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    return strength;
}

function updatePasswordStrengthIndicator(strength) {
    const indicator = document.getElementById('password-strength');
    if (!indicator) return;
    
    const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-blue-500', 'bg-green-500'];
    const texts = ['Muito Fraca', 'Fraca', 'Média', 'Forte', 'Muito Forte'];
    
    indicator.className = `h-2 rounded ${colors[strength - 1] || 'bg-gray-300'}`;
    indicator.style.width = `${(strength / 5) * 100}%`;
    
    const textElement = document.getElementById('password-strength-text');
    if (textElement) {
        textElement.textContent = texts[strength - 1] || '';
    }
}
</script>
@endsection 