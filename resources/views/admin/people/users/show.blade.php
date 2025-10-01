@extends('layouts.admin')

@section('title', 'Detalhes do Usuário')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Detalhes do Usuário</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">{{ __('Informações completas sobre') }} {{ $user->name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.people.users.edit', $user->id) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-edit mr-2"></i>{{ __('Editar') }}
            </a>
            <a href="{{ route('admin.people.users.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>{{ __('Voltar') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informações Principais -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informações Básicas -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg">
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Informações Básicas') }}</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Nome Completo') }}</label>
                            <p class="text-gray-900 dark:text-gray-300 font-medium">{{ $user->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('E-mail') }}</label>
                            <p class="text-gray-900 dark:text-gray-300">{{ $user->email }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Nome de Usuário') }}</label>
                            <p class="text-gray-900 dark:text-gray-300">{{ $user->username }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Status') }}</label>
                            <p class="text-gray-900 dark:text-gray-300">
                                @switch($user->status)
                                    @case('active')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>{{ __('Ativo') }}
                                        </span>
                                        @break
                                    @case('inactive')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-pause-circle mr-1"></i>{{ __('Inativo') }}
                                        </span>
                                        @break
                                    @case('suspended')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-ban mr-1"></i>{{ __('Suspenso') }}
                                        </span>
                                        @break
                                    @default
                                        {{ __('Não definido') }}
                                @endswitch
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Telefone') }}</label>
                            <p class="text-gray-900 dark:text-gray-300">{{ $user->phone ?: __('Não informado') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Departamento') }}</label>
                            <p class="text-gray-900 dark:text-gray-300">{{ $user->department ?: __('Não informado') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Cargo') }}</label>
                            <p class="text-gray-900 dark:text-gray-300">{{ $user->position ?: __('Não informado') }}</p>
                        </div>

                        @if($user->notes)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Observações') }}</label>
                                <p class="text-gray-900 dark:text-gray-300">{{ $user->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Funções e Permissões -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg">
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Funções e Permissões') }}</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Funções Atribuídas') }}</label>
                            @if($user->roles->count() > 0)
                                <div class="space-y-2">
                                    @foreach($user->roles as $role)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300">
                                            <i class="fas fa-user-tag mr-2"></i>{{ $role->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('Nenhuma função atribuída') }}</p>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Permissões Diretas') }}</label>
                            @if($user->permissions->count() > 0)
                                <div class="space-y-2">
                                    @foreach($user->permissions as $permission)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                            <i class="fas fa-key mr-2"></i>{{ $permission->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('Nenhuma permissão direta') }}</p>
                            @endif
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Todas as Permissões (Incluindo Funções)') }}</label>
                            @php
                                $allPermissions = $user->getAllPermissions();
                            @endphp
                            @if($allPermissions->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                                    @foreach($allPermissions as $permission)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>{{ $permission->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm">{{ __('Nenhuma permissão disponível') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações de Login -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg">
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Informações de Login') }}</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Data de Criação') }}</label>
                            <p class="text-gray-900 dark:text-gray-300">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Última Atualização') }}</label>
                            <p class="text-gray-900 dark:text-gray-300">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Último Login') }}</label>
                            <p class="text-gray-900 dark:text-gray-300">
                                {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : __('Nunca acessou') }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('IP do Último Login') }}</label>
                            <p class="text-gray-900 dark:text-gray-300">{{ $user->last_login_ip ?: __('Não registrado') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Total de Logins') }}</label>
                            <p class="text-gray-900 dark:text-gray-300">{{ $user->login_count ?: '0' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('E-mail Verificado') }}</label>
                            <p class="text-gray-900 dark:text-gray-300">
                                @if($user->email_verified_at)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                        <i class="fas fa-check mr-1"></i>{{ __('Sim') }} ({{ $user->email_verified_at->format('d/m/Y') }})
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                        <i class="fas fa-times mr-1"></i>{{ __('Não') }}
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configurações de Conta -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg">
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Configurações de Conta') }}</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Forçar Alteração de Senha') }}</label>
                            <p class="text-gray-900 dark:text-gray-300">
                                @if($user->force_password_change)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>{{ __('Sim') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                        <i class="fas fa-check mr-1"></i>{{ __('Não') }}
                                    </span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Autenticação de Dois Fatores') }}</label>
                            <p class="text-gray-900 dark:text-gray-300">
                                @if($user->two_factor_enabled)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                        <i class="fas fa-shield-alt mr-1"></i>{{ __('Habilitado') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                        <i class="fas fa-shield-alt mr-1"></i>{{ __('Desabilitado') }}
                                    </span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Última Alteração de Senha') }}</label>
                            <p class="text-gray-900 dark:text-gray-300">
                                {{ $user->password_changed_at ? $user->password_changed_at->format('d/m/Y H:i') : __('Não registrado') }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Tentativas de Login Falhadas') }}</label>
                            <p class="text-gray-900 dark:text-gray-300">{{ $user->failed_login_attempts ?: '0' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Avatar e Status -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg">
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Avatar') }}</h3>
                </div>
                <div class="p-6">
                    <div class="text-center">
                        <div class="w-32 h-32 bg-gray-200 dark:bg-gray-700 rounded-full mx-auto flex items-center justify-center mb-4">
                            <i class="fas fa-user text-4xl text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $user->name }}</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->username }}</p>
                    </div>
                </div>
            </div>

            <!-- Estatísticas Rápidas -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg">
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Estatísticas') }}</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Tempo no Sistema') }}</span>
                            <span class="font-medium text-gray-900 dark:text-gray-300">{{ $user->created_at->diffForHumans() }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Funções Ativas') }}</span>
                            <span class="font-medium text-gray-900 dark:text-gray-300">{{ $user->roles->count() }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Permissões Diretas') }}</span>
                            <span class="font-medium text-gray-900 dark:text-gray-300">{{ $user->permissions->count() }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total de Permissões') }}</span>
                            <span class="font-medium text-gray-900 dark:text-gray-300">{{ $user->getAllPermissions()->count() }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Status da Conta') }}</span>
                            <span class="font-medium text-gray-900 dark:text-gray-300">
                                @switch($user->status)
                                    @case('active')
                                        <span class="text-green-600 dark:text-green-400">{{ __('Ativo') }}</span>
                                        @break
                                    @case('inactive')
                                        <span class="text-gray-600 dark:text-gray-400">{{ __('Inativo') }}</span>
                                        @break
                                    @case('suspended')
                                        <span class="text-red-600 dark:text-red-400">{{ __('Suspenso') }}</span>
                                        @break
                                    @default
                                        <span class="text-gray-600 dark:text-gray-400">{{ __('Indefinido') }}</span>
                                @endswitch
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg">
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Ações') }}</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="{{ route('admin.people.users.edit', $user->id) }}" 
                           class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                            <i class="fas fa-edit mr-2"></i>{{ __('Editar Usuário') }}
                        </a>

                        @if($user->status === 'active')
                            <button type="button" 
                                    class="w-full flex items-center justify-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors duration-200"
                                    onclick="confirmarSuspensao()">
                                <i class="fas fa-pause mr-2"></i>{{ __('Suspender Usuário') }}
                            </button>
                        @else
                            <button type="button" 
                                    class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200"
                                    onclick="confirmarAtivacao()">
                                <i class="fas fa-play mr-2"></i>{{ __('Ativar Usuário') }}
                            </button>
                        @endif

                        <button type="button" 
                                class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200"
                                onclick="confirmarExclusao()">
                            <i class="fas fa-trash mr-2"></i>{{ __('Excluir Usuário') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div id="modal-exclusao" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4">
            <div class="flex items-center mb-4">
                <i class="fas fa-exclamation-triangle text-red-500 text-2xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Confirmar Exclusão') }}</h3>
            </div>
            <p class="text-gray-600 dark:text-gray-300 mb-6">
                {{ __('Tem certeza que deseja excluir o usuário') }} <strong>{{ $user->name }}</strong>? 
                {{ __('Esta ação não pode ser desfeita.') }}
            </p>
            <div class="flex justify-end space-x-3">
                <button type="button" 
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200"
                        onclick="fecharModal()">
                    {{ __('Cancelar') }}
                </button>
                <form action="{{ route('admin.people.users.delete', $user->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200">
                        {{ __('Confirmar Exclusão') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmarExclusao() {
    document.getElementById('modal-exclusao').classList.remove('hidden');
}

function confirmarSuspensao() {
    if (confirm('{{ __("Tem certeza que deseja suspender este usuário?") }}')) {
        // Implementar ação de suspensão
        console.log('Suspender usuário');
    }
}

function confirmarAtivacao() {
    if (confirm('{{ __("Tem certeza que deseja ativar este usuário?") }}')) {
        // Implementar ação de ativação
        console.log('Ativar usuário');
    }
}

function fecharModal() {
    document.getElementById('modal-exclusao').classList.add('hidden');
}

// Fechar modal ao clicar fora dele
document.getElementById('modal-exclusao').addEventListener('click', function(e) {
    if (e.target === this) {
        fecharModal();
    }
});

// Fechar modal com ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        fecharModal();
    }
});
</script>
@endpush
@endsection