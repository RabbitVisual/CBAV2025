@extends('layouts.admin')

@php
use App\Helpers\PermissionHelper;
@endphp

@section('title', 'Editar Role - ' . $role->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    <i class="fas fa-user-tag text-blue-600 mr-3"></i>
                    Editar Role
                </h1>
                <p class="text-gray-600">Editar role: {{ $role->name }}</p>
            </div>
            <a href="{{ route('admin.permissions.roles') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i> Voltar
            </a>
        </div>
    </div>

    <!-- Formulário de Edição -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Editar Role: {{ $role->name }}</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.permissions.update-role', $role) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nome da Role</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Ex: admin, membro, tesoureiro">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                        <input type="text" name="description" id="description" value="{{ old('description', $role->description) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Descrição da role">
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Permissões</label>
                    <div class="max-h-96 overflow-y-auto border border-gray-200 rounded-md p-4">
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
                            <div class="mb-6">
                                <div class="bg-gray-50 px-4 py-2 rounded-t-lg border border-gray-200">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $categoria['title'] }}</h4>
                                </div>
                                <div class="border border-gray-200 border-t-0 rounded-b-lg p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        @foreach($categoria['permissions'] as $permission)
                                        <label class="flex items-center space-x-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                   {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
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
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">🔧 Outras Permissões</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 ml-4">
                                @foreach($permissionsNaoCategorizadas as $permission)
                                <label class="flex items-center space-x-3 p-2 rounded hover:bg-gray-50">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                           {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
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

                <div class="flex justify-between">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                        <i class="fas fa-save mr-2"></i> Salvar Alterações
                    </button>
                    
                    @if($role->users()->count() == 0)
                        <form action="{{ route('admin.permissions.delete-role', $role) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg" 
                                    onclick="return confirm('Tem certeza que deseja excluir esta role?')">
                                <i class="fas fa-trash mr-2"></i> Excluir Role
                            </button>
                        </form>
                    @else
                        <span class="text-gray-400 text-sm">Não é possível excluir uma role com usuários</span>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Informações da Role -->
    <div class="bg-white rounded-lg shadow-md mt-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Informações da Role</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Usuários com esta Role</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $role->users()->count() }}</p>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Permissões Ativas</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $role->permissions->count() }}</p>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Criada em</h3>
                    <p class="text-sm text-gray-600">{{ $role->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 