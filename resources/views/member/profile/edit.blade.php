@extends('layouts.member')

@section('title', __('Editar Perfil'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Cabeçalho -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ __('Editar Perfil') }}</h1>
                    <p class="text-gray-600 mt-2">{{ __('Atualize suas informações pessoais e configurações da conta') }}</p>
                </div>
                <a href="{{ route('member.profile.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>{{ __('Voltar') }}
                </a>
            </div>
        </div>

        <!-- Alertas -->
        @if(session('success'))
            <div class="alert alert-success mb-6">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Formulário -->
        <form action="{{ route('member.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Informações Pessoais -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-user mr-3 text-blue-600"></i>
                    {{ __('Informações Pessoais') }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nome -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Nome Completo') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $user->name) }}"
                               class="form-input w-full @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('E-mail') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}"
                               class="form-input w-full @error('email') border-red-500 @enderror"
                               required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Telefone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Telefone') }}
                        </label>
                        <input type="text" 
                               id="telefone" 
                               name="telefone" 
                               value="{{ old('telefone', $membro->telefone) }}"
                               class="form-input w-full @error('telefone') border-red-500 @enderror"
                               placeholder="(11) 99999-9999">
                        @error('telefone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Data de Nascimento -->
                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Data de Nascimento') }}
                        </label>
                        <input type="date" 
                               id="data_nascimento" 
                               name="data_nascimento" 
                               value="{{ old('data_nascimento', $membro->data_nascimento ? $membro->data_nascimento->format('Y-m-d') : '') }}"
                               class="form-input w-full @error('data_nascimento') border-red-500 @enderror">
                        @error('data_nascimento')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Foto do Perfil -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-camera mr-3 text-blue-600"></i>
                    {{ __('Foto do Perfil') }}
                </h2>

                <div class="flex items-center space-x-6">
                    <!-- Foto Atual -->
                    <div class="flex-shrink-0">
                        <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                            @if($membro->foto_existe)
                                <img src="{{ $membro->foto_url }}" 
                                     alt="{{ $membro->nome }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-blue-600 flex items-center justify-center">
                                    <span class="text-white text-2xl font-bold">
                                        {{ $membro->iniciais }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Upload de Nova Foto -->
                    <div class="flex-1">
                        <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Nova Foto') }}
                        </label>
                        <input type="file" 
                               id="foto" 
                               name="foto" 
                               accept="image/*"
                               class="form-input w-full @error('foto') border-red-500 @enderror">
                        <p class="text-sm text-gray-500 mt-1">
                            {{ __('Formatos aceitos: JPG, PNG, WebP. Tamanho máximo: 5MB') }}
                        </p>
                        @error('foto')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Endereço -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-map-marker-alt mr-3 text-blue-600"></i>
                    {{ __('Endereço') }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- CEP -->
                    <div>
                        <label for="cep" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('CEP') }}
                        </label>
                        <input type="text" 
                               id="cep" 
                               name="cep" 
                               value="{{ old('cep', $user->cep) }}"
                               class="form-input w-full @error('cep') border-red-500 @enderror"
                               placeholder="00000-000">
                        @error('cep')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Endereço -->
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Endereço') }}
                        </label>
                        <input type="text" 
                               id="endereco" 
                               name="endereco" 
                               value="{{ old('endereco', $membro->endereco) }}"
                               class="form-input w-full @error('endereco') border-red-500 @enderror"
                               placeholder="{{ __('Rua, número, complemento') }}">
                        @error('endereco')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cidade -->
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Cidade') }}
                        </label>
                        <input type="text" 
                               id="cidade" 
                               name="cidade" 
                               value="{{ old('cidade', $membro->cidade) }}"
                               class="form-input w-full @error('cidade') border-red-500 @enderror">
                        @error('cidade')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estado -->
                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Estado') }}
                        </label>
                        <select id="estado" 
                                name="estado" 
                                class="form-select w-full @error('estado') border-red-500 @enderror">
                            <option value="">{{ __('Selecione um estado') }}</option>
                            @foreach(['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'] as $estado)
                                <option value="{{ $estado }}" {{ old('estado', $membro->estado) == $estado ? 'selected' : '' }}>
                                    {{ $estado }}
                                </option>
                            @endforeach
                        </select>
                        @error('estado')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Configurações da Conta -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-cog mr-3 text-blue-600"></i>
                    {{ __('Configurações da Conta') }}
                </h2>

                <div class="space-y-4">
                    <!-- Notificações por Email -->
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">{{ __('Notificações por E-mail') }}</h3>
                            <p class="text-sm text-gray-500">{{ __('Receber notificações importantes por e-mail') }}</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" 
                                   name="email_notifications" 
                                   value="1"
                                   {{ old('email_notifications', $user->email_notifications) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <!-- Notificações Push -->
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">{{ __('Notificações Push') }}</h3>
                            <p class="text-sm text-gray-500">{{ __('Receber notificações no navegador') }}</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" 
                                   name="push_notifications" 
                                   value="1"
                                   {{ old('push_notifications', $user->push_notifications) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <!-- Privacidade do Perfil -->
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">{{ __('Perfil Público') }}</h3>
                            <p class="text-sm text-gray-500">{{ __('Permitir que outros membros vejam seu perfil') }}</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" 
                                   name="public_profile" 
                                   value="1"
                                   {{ old('public_profile', $user->public_profile) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="flex items-center justify-between pt-6">
                <div class="flex space-x-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>{{ __('Salvar Alterações') }}
                    </button>
                    <a href="{{ route('member.profile.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-2"></i>{{ __('Cancelar') }}
                    </a>
                </div>

                <div class="flex space-x-4">
                    <a href="{{ route('member.profile.password') }}" class="btn btn-outline">
                        <i class="fas fa-key mr-2"></i>{{ __('Alterar Senha') }}
                    </a>
                    @if($membro->foto_existe)
                        <form action="{{ route('member.profile.photo.delete') }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('Tem certeza que deseja remover sua foto?') }}')">
                                <i class="fas fa-trash mr-2"></i>{{ __('Remover Foto') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </form>
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
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
        });
    }

    // Máscara para CEP
    const cepInput = document.getElementById('cep');
    if (cepInput) {
        cepInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
        });
    }

    // Busca automática de endereço pelo CEP
    if (cepInput) {
        cepInput.addEventListener('blur', function() {
            const cep = this.value.replace(/\D/g, '');
            if (cep.length === 8) {
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.erro) {
                            document.getElementById('address').value = data.logradouro;
                            document.getElementById('city').value = data.localidade;
                            document.getElementById('state').value = data.uf;
                        }
                    })
                    .catch(error => console.error('Erro ao buscar CEP:', error));
            }
        });
    }
});
</script>
@endpush
@endsection 