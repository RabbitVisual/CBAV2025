@extends('layouts.member')

@section('title', __('Meu Perfil'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Meu Perfil') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Gerencie suas informações pessoais') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('member.profile.edit') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-edit mr-2"></i>
                {{ __('Editar Perfil') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informações Pessoais -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Dados Básicos -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-user mr-3 text-blue-500"></i>
                    {{ __('Informações Pessoais') }}
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Nome Completo') }}</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $membro->nome }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email') }}</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $membro->email }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Telefone') }}</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $membro->telefone ?? 'Não informado' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Data de Nascimento') }}</label>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $membro->data_nascimento ? $membro->data_nascimento->format('d/m/Y') : 'Não informado' }}
                            @if($membro->data_nascimento)
                                <span class="text-sm text-gray-500">({{ $membro->idade }} anos)</span>
                            @endif
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Estado Civil') }}</label>
                        <p class="text-lg font-semibold text-gray-900">{{ ucfirst($membro->estado_civil ?? 'Não informado') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Data de Ingresso') }}</label>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $membro->data_ingresso ? $membro->data_ingresso->format('d/m/Y') : 'Não informado' }}
                            @if($membro->data_ingresso)
                                <span class="text-sm text-gray-500">({{ $membro->tempo_igreja }})</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Endereço -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-map-marker-alt mr-3 text-green-500"></i>
                    {{ __('Endereço') }}
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Endereço Completo') }}</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $membro->endereco ?? 'Não informado' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Cidade') }}</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $membro->cidade ?? 'Não informado' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Estado') }}</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $membro->estado ?? 'Não informado' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('CEP') }}</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $membro->cep ?? 'Não informado' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Data do Batismo') }}</label>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $membro->data_batismo ? $membro->data_batismo->format('d/m/Y') : 'Não batizado' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Ministérios e Cargos -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-users mr-3 text-purple-500"></i>
                    {{ __('Ministérios e Cargos') }}
                </h2>
                
                @if($membro->cargos->count() > 0)
                    <div class="space-y-4">
                        @foreach($membro->cargos as $cargo)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $cargo->nome }}</h3>
                                        <p class="text-sm text-gray-600">{{ $cargo->departamento->ministerio->nome }}</p>
                                        <p class="text-xs text-gray-500">{{ $cargo->departamento->nome }}</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>
                                            {{ __('Ativo') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-users text-gray-400 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Nenhum cargo atribuído') }}</h3>
                        <p class="text-gray-500">{{ __('Você ainda não possui cargos atribuídos.') }}</p>
                    </div>
                @endif
            </div>

            <!-- Observações -->
            @if($membro->observacoes)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-sticky-note mr-3 text-yellow-500"></i>
                        {{ __('Observações') }}
                    </h2>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700">{{ $membro->observacoes }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Foto do Perfil -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-camera mr-3 text-indigo-500"></i>
                    {{ __('Foto do Perfil') }}
                </h2>
                
                <div class="text-center">
                    @if($membro->foto_existe)
                        <img src="{{ $membro->foto_url }}" 
                             alt="{{ $membro->nome }}" 
                             class="w-32 h-32 rounded-full mx-auto mb-4 object-cover border-4 border-gray-200">
                    @else
                        <div class="w-32 h-32 rounded-full mx-auto mb-4 bg-gray-300 flex items-center justify-center">
                            <span class="text-4xl font-bold text-gray-600">{{ $membro->iniciais }}</span>
                        </div>
                    @endif
                    
                    <button onclick="abrirModalFoto()" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-camera mr-2"></i>
                        {{ __('Alterar Foto') }}
                    </button>
                    
                    @if($membro->foto_existe)
                        <button onclick="excluirFoto()" 
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200 mt-2">
                            <i class="fas fa-trash mr-2"></i>
                            {{ __('Remover Foto') }}
                        </button>
                    @endif
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-chart-bar mr-3 text-green-500"></i>
                    {{ __('Estatísticas') }}
                </h2>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ __('Tempo na Igreja:') }}</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $membro->tempo_igreja }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ __('Ministérios:') }}</span>
                        <span class="text-sm font-semibold text-gray-900">
                            @php
                                $cargosAtivos = $membro->cargos()->wherePivot('ativo', true)->get();
                                $ministeriosUnicos = $cargosAtivos->map(function($cargo) {
                                    return $cargo->departamento && $cargo->departamento->ministerio 
                                        ? $cargo->departamento->ministerio 
                                        : null;
                                })->filter()->unique('id');
                            @endphp
                            {{ $ministeriosUnicos->count() }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ __('Cargos:') }}</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $membro->cargos()->wherePivot('ativo', true)->count() }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ __('Status:') }}</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $membro->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $membro->ativo ? __('Ativo') : __('Inativo') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-bolt mr-3 text-orange-500"></i>
                    {{ __('Ações Rápidas') }}
                </h2>
                
                <div class="space-y-3">
                    <a href="{{ route('member.donations.index') }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-hand-holding-heart mr-2"></i>
                        {{ __('Minhas Doações') }}
                    </a>
                    
                    <a href="{{ route('member.ministries.index') }}" 
                       class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-users mr-2"></i>
                        {{ __('Meus Ministérios') }}
                    </a>
                    
                    <a href="{{ route('member.bible.index') }}" 
                       class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-bible mr-2"></i>
                        {{ __('Bíblia Online') }}
                    </a>
                    
                    <a href="{{ route('member.notifications.index') }}" 
                       class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-bell mr-2"></i>
                        {{ __('Notificações') }}
                    </a>
                </div>
            </div>

            <!-- Informações da Conta -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-shield-alt mr-3 text-red-500"></i>
                    {{ __('Informações da Conta') }}
                </h2>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ __('Último Acesso:') }}</span>
                        <span class="text-sm font-semibold text-gray-900">
                            {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('d/m/Y H:i') : 'Nunca' }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ __('Conta Criada:') }}</span>
                        <span class="text-sm font-semibold text-gray-900">
                            {{ auth()->user()->created_at->format('d/m/Y') }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ __('Verificação de Email:') }}</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ auth()->user()->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ auth()->user()->email_verified_at ? __('Verificado') : __('Não verificado') }}
                        </span>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('member.profile.edit') }}" 
                       class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-edit mr-2"></i>
                        {{ __('Editar Perfil') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function abrirModalFoto() {
    // Criar modal dinamicamente
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
    modal.id = 'modalFoto';
    
    modal.innerHTML = `
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Alterar Foto do Perfil') }}</h3>
                    <button onclick="fecharModalFoto()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="mb-4">
                    <label for="novaFoto" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Selecionar Nova Foto') }}
                    </label>
                    <input type="file" 
                           id="novaFoto" 
                           accept="image/*" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">{{ __('Formatos: JPG, PNG, WebP. Máximo: 5MB') }}</p>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button onclick="fecharModalFoto()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        {{ __('Cancelar') }}
                    </button>
                    <button onclick="enviarFoto()" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        {{ __('Enviar') }}
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
}

function fecharModalFoto() {
    const modal = document.getElementById('modalFoto');
    if (modal) {
        modal.remove();
    }
}

function enviarFoto() {
    const input = document.getElementById('novaFoto');
    const file = input.files[0];
    
    if (!file) {
        alert('{{ __("Por favor, selecione uma foto.") }}');
        return;
    }
    
    // Validar tamanho (5MB)
    if (file.size > 5 * 1024 * 1024) {
        alert('{{ __("A foto deve ter no máximo 5MB.") }}');
        return;
    }
    
    // Validar tipo
    const tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    if (!tiposPermitidos.includes(file.type)) {
        alert('{{ __("Formato de imagem não suportado. Use JPG, PNG ou WebP.") }}');
        return;
    }
    
    const formData = new FormData();
    formData.append('foto', file);
    formData.append('_token', '{{ csrf_token() }}');
    
    // Mostrar loading
    const btnEnviar = event.target;
    const textoOriginal = btnEnviar.innerHTML;
    btnEnviar.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>{{ __("Enviando...") }}';
    btnEnviar.disabled = true;
    
    fetch('{{ route("member.profile.photo.upload") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Atualizar a foto na página
            const fotoElement = document.querySelector('.w-32.h-32.rounded-full');
            if (fotoElement) {
                fotoElement.src = data.foto_url + '?v=' + new Date().getTime();
            }
            
            // Fechar modal
            fecharModalFoto();
            
            // Mostrar mensagem de sucesso
            mostrarMensagem('{{ __("Foto atualizada com sucesso!") }}', 'success');
            
            // Recarregar a página após 1 segundo
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            alert(data.error || '{{ __("Erro ao enviar foto.") }}');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('{{ __("Erro ao enviar foto. Tente novamente.") }}');
    })
    .finally(() => {
        btnEnviar.innerHTML = textoOriginal;
        btnEnviar.disabled = false;
    });
}

function excluirFoto() {
    if (!confirm('{{ __("Tem certeza que deseja remover sua foto?") }}')) {
        return;
    }
    
    fetch('{{ route("member.profile.photo.delete") }}', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Recarregar a página
            window.location.reload();
        } else {
            alert(data.error || '{{ __("Erro ao excluir foto.") }}');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('{{ __("Erro ao excluir foto. Tente novamente.") }}');
    });
}

function mostrarMensagem(mensagem, tipo = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `fixed top-4 right-4 p-4 rounded-md shadow-lg z-50 ${
        tipo === 'success' ? 'bg-green-500 text-white' : 'bg-blue-500 text-white'
    }`;
    alertDiv.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${tipo === 'success' ? 'check' : 'info'}-circle mr-2"></i>
            <span>${mensagem}</span>
        </div>
    `;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}
</script>
@endpush
@endsection 