@extends('layouts.admin')

@php
use App\Helpers\PermissionHelper;
@endphp

@section('title', 'Editar Usuário - ' . $user->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    <i class="fas fa-user-edit text-purple-600 mr-3"></i>
                    Editar Usuário
                </h1>
                <p class="text-gray-600">Gerenciar roles e permissões de {{ $user->name }}</p>
            </div>
            <a href="{{ route('admin.permissions.users') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i> Voltar
            </a>
        </div>
    </div>

    <!-- Informações do Usuário -->
    <div class="bg-white rounded-lg shadow-md mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Informações do Usuário</h2>
        </div>
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-16 w-16">
                    <div class="h-16 w-16 rounded-full bg-gray-300 flex items-center justify-center">
                        <span class="text-lg font-medium text-gray-700">{{ substr($user->name, 0, 2) }}</span>
                    </div>
                </div>
                <div class="ml-6">
                    <h3 class="text-lg font-medium text-gray-900">{{ $user->name }}</h3>
                    <p class="text-gray-500">{{ $user->email }}</p>
                    <p class="text-sm text-gray-400">Membro desde {{ $user->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulário de Edição -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Gerenciar Permissões</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.permissions.update-user', $user) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Roles -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Roles</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($roles as $role)
                        <label class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                   {{ $user->hasRole($role->name) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <div>
                                <span class="text-sm font-medium text-gray-900">{{ $role->name }}</span>
                                @if($role->description)
                                    <p class="text-xs text-gray-500">{{ $role->description }}</p>
                                @endif
                                <p class="text-xs text-gray-400">{{ $role->permissions->count() }} permissões</p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Permissões Diretas -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Permissões Diretas</h3>
                    <p class="text-sm text-gray-600 mb-4">Estas permissões são atribuídas diretamente ao usuário, independente das roles.</p>
                    
                    <!-- Botões de Marcar/Desmarcar Todos -->
                    <div class="flex space-x-3 mb-4">
                        <button type="button" onclick="selectAllPermissions()" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                            <i class="fas fa-check-square mr-2"></i>Marcar Todas
                        </button>
                        <button type="button" onclick="deselectAllPermissions()" 
                                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm">
                            <i class="fas fa-square mr-2"></i>Desmarcar Todas
                        </button>
                        <button type="button" onclick="toggleAllPermissions()" 
                                class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm">
                            <i class="fas fa-exchange-alt mr-2"></i>Inverter Seleção
                        </button>
                    </div>
                    
                    <div class="max-h-64 overflow-y-auto border border-gray-200 rounded-md p-4">
                        @php
                            $categorias = [
                                'pessoas' => ['title' => '👥 Gestão de Pessoas', 'permissions' => $permissions->filter(fn($p) => str_contains($p->name, 'people.') || str_contains($p->name, 'members.') || str_contains($p->name, 'users.') || str_contains($p->name, 'ministries.') || str_contains($p->name, 'departments.'))],
                                'financeiro' => ['title' => '💰 Gestão Financeira', 'permissions' => $permissions->filter(fn($p) => str_contains($p->name, 'finance.') || str_contains($p->name, 'transactions.') || str_contains($p->name, 'campaigns.'))],
                                'ebd' => ['title' => '📚 Escola Bíblica Dominical', 'permissions' => $permissions->filter(fn($p) => str_contains($p->name, 'ebd.'))],
                                'devocionais' => ['title' => '📖 Devocionais', 'permissions' => $permissions->filter(fn($p) => str_contains($p->name, 'devotionals.'))],
                                'conselho' => ['title' => '🏛️ Conselho da Igreja', 'permissions' => $permissions->filter(fn($p) => str_contains($p->name, 'council.'))],
                                'intercessao' => ['title' => '🙏 Pedidos de Oração', 'permissions' => $permissions->filter(fn($p) => str_contains($p->name, 'intercessor.'))],
                                'notificacoes' => ['title' => '🔔 Notificações', 'permissions' => $permissions->filter(fn($p) => str_contains($p->name, 'notifications.'))],
                                'sistema' => ['title' => '⚙️ Sistema', 'permissions' => $permissions->filter(fn($p) => str_contains($p->name, 'system.') || str_contains($p->name, 'settings.') || str_contains($p->name, 'logs.'))],
                                'relatorios' => ['title' => '📊 Relatórios', 'permissions' => $permissions->filter(fn($p) => str_contains($p->name, 'reports.'))],
                            ];
                        @endphp
                        
                        @foreach($categorias as $categoriaKey => $categoria)
                            @if($categoria['permissions']->count() > 0)
                            <div class="mb-4">
                                <div class="bg-gray-50 px-4 py-2 rounded-t-lg border border-gray-200">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $categoria['title'] }}</h4>
                                </div>
                                <div class="border border-gray-200 border-t-0 rounded-b-lg p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        @foreach($categoria['permissions'] as $permission)
                                        <label class="flex items-center space-x-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                   {{ $user->hasDirectPermission($permission->name) ? 'checked' : '' }}
                                                   class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                            <div class="flex-1">
                                                <span class="text-sm font-medium text-gray-900">{{ PermissionHelper::getPermissionDisplayName($permission->name) }}</span>
                                                <p class="text-xs text-gray-500">{{ PermissionHelper::getPermissionDescription($permission->name) }}</p>
                                            </div>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                        
                        @php
                            $permissionsCategorizadas = collect();
                            foreach($categorias as $categoria) {
                                $permissionsCategorizadas = $permissionsCategorizadas->merge($categoria['permissions']);
                            }
                            $permissionsNaoCategorizadas = $permissions->diff($permissionsCategorizadas);
                        @endphp
                        
                        @if($permissionsNaoCategorizadas->count() > 0)
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">🔧 Outras Permissões</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 ml-4">
                                @foreach($permissionsNaoCategorizadas as $permission)
                                <label class="flex items-center space-x-3 p-2 rounded hover:bg-gray-50">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                           {{ $user->hasDirectPermission($permission->name) ? 'checked' : '' }}
                                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                    <div>
                                        <span class="text-sm text-gray-900">{{ $permission->name }}</span>
                                        <p class="text-xs text-gray-500">{{ $permission->description ?: 'Permissão do sistema' }}</p>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Resumo de Permissões -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Resumo de Permissões</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Permissões via Roles</h4>
                            <div class="space-y-1">
                                @foreach($user->getAllPermissions() as $permission)
                                    @if($user->hasRole($permission->roles->first()))
                                        <span class="inline-block px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded mr-1 mb-1">
                                            {{ $permission->name }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Permissões Diretas</h4>
                            <div class="space-y-1">
                                @foreach($user->getDirectPermissions() as $permission)
                                    <span class="inline-block px-2 py-1 text-xs bg-green-100 text-green-800 rounded mr-1 mb-1">
                                        {{ $permission->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                        <i class="fas fa-save mr-2"></i> Salvar Alterações
                    </button>
                    
                    <form action="{{ route('admin.permissions.reset-user', $user) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg" 
                                onclick="return confirm('Tem certeza que deseja resetar todas as permissões deste usuário?')">
                            <i class="fas fa-undo mr-2"></i> Resetar Permissões
                        </button>
                    </form>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function selectAllPermissions() {
    const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    showNotification('Todas as permissões foram marcadas!', 'success');
}

function deselectAllPermissions() {
    const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    showNotification('Todas as permissões foram desmarcadas!', 'success');
}

function toggleAllPermissions() {
    const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = !checkbox.checked;
    });
    showNotification('Seleção de permissões foi invertida!', 'success');
}

function showNotification(message, type = 'info') {
    // Criar notificação simples
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 ${
        type === 'success' ? 'bg-green-600' : 'bg-blue-600'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remover após 3 segundos
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Adicionar contador de permissões selecionadas
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
    const updateCounter = () => {
        const checkedCount = document.querySelectorAll('input[name="permissions[]"]:checked').length;
        const totalCount = checkboxes.length;
        
        // Atualizar texto dos botões com contador
        const selectAllBtn = document.querySelector('button[onclick="selectAllPermissions()"]');
        const deselectAllBtn = document.querySelector('button[onclick="deselectAllPermissions()"]');
        
        if (selectAllBtn) {
            selectAllBtn.innerHTML = `<i class="fas fa-check-square mr-2"></i>Marcar Todas (${checkedCount}/${totalCount})`;
        }
        if (deselectAllBtn) {
            deselectAllBtn.innerHTML = `<i class="fas fa-square mr-2"></i>Desmarcar Todas (${checkedCount}/${totalCount})`;
        }
    };
    
    // Adicionar listener para cada checkbox
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateCounter);
    });
    
    // Inicializar contador
    updateCounter();
});
</script>
@endsection 