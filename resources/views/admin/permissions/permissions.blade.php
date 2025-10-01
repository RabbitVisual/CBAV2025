@extends('layouts.admin')

@section('content')
@php use App\Helpers\PermissionHelper; @endphp

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Gerenciar Permissões</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.permissions.create-permission') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-plus mr-2"></i>Nova Permissão
            </a>
            <a href="{{ route('admin.permissions.roles') }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-users mr-2"></i>Gerenciar Roles
            </a>
            <a href="{{ route('admin.permissions.users') }}" 
               class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-user-cog mr-2"></i>Gerenciar Usuários
            </a>
        </div>
    </div>

    <div class="space-y-6">
        @foreach($categorias as $categoriaKey => $categoria)
            @if($categoria['permissions']->count() > 0)
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center">
                        <span class="text-xl font-semibold text-gray-900">{{ $categoria['title'] }}</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">{{ $categoria['description'] }}</p>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach($categoria['permissions'] as $permission)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-key text-gray-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">{{ PermissionHelper::getPermissionDisplayName($permission->name) }}</h3>
                                    <p class="text-xs text-gray-500">{{ $permission->description ?: 'Permissão do sistema' }}</p>
                                    <div class="flex items-center space-x-4 mt-1 text-xs text-gray-500">
                                        <span>Guard: {{ $permission->guard_name }}</span>
                                        <span>{{ $permission->roles->count() }} roles</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                @if($permission->roles->count() > 0)
                                <div class="flex flex-wrap gap-1 max-w-xs">
                                    @foreach($permission->roles->take(3) as $role)
                                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">{{ $role->name }}</span>
                                    @endforeach
                                    @if($permission->roles->count() > 3)
                                        <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">+{{ $permission->roles->count() - 3 }} mais</span>
                                    @endif
                                </div>
                                @endif
                                
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.permissions.edit-permission', $permission) }}" 
                                       class="text-blue-600 hover:text-blue-900 text-sm">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    @if($permission->roles->count() == 0)
                                        <form action="{{ route('admin.permissions.delete-permission', $permission) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm" 
                                                    onclick="return confirm('Tem certeza que deseja excluir esta permissão?')">
                                                <i class="fas fa-trash"></i> Excluir
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 text-sm cursor-not-allowed">
                                            <i class="fas fa-trash"></i> Excluir
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    </div>
    
    <!-- Permissões não categorizadas -->
    @php
        $permissionsCategorizadas = collect();
        foreach($categorias as $categoria) {
            $permissionsCategorizadas = $permissionsCategorizadas->merge($categoria['permissions']);
        }
        $permissionsNaoCategorizadas = $permissions->diff($permissionsCategorizadas);
    @endphp
    
    @if($permissionsNaoCategorizadas->count() > 0)
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center">
                <span class="text-xl font-semibold text-gray-900">🔧 Outras Permissões</span>
            </div>
            <p class="text-sm text-gray-600 mt-1">Permissões que não se encaixam nas categorias principais</p>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                @foreach($permissionsNaoCategorizadas as $permission)
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                            <i class="fas fa-cog text-gray-600"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">{{ PermissionHelper::getPermissionDisplayName($permission->name) }}</h3>
                            <p class="text-xs text-gray-500">{{ $permission->description ?: 'Permissão do sistema' }}</p>
                            <div class="flex items-center space-x-4 mt-1 text-xs text-gray-500">
                                <span>Guard: {{ $permission->guard_name }}</span>
                                <span>{{ $permission->roles->count() }} roles</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        @if($permission->roles->count() > 0)
                        <div class="flex flex-wrap gap-1 max-w-xs">
                            @foreach($permission->roles->take(3) as $role)
                                <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">{{ $role->name }}</span>
                            @endforeach
                            @if($permission->roles->count() > 3)
                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">+{{ $permission->roles->count() - 3 }} mais</span>
                            @endif
                        </div>
                        @endif
                        
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.permissions.edit-permission', $permission) }}" 
                               class="text-blue-600 hover:text-blue-900 text-sm">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            @if($permission->roles->count() == 0)
                                <form action="{{ route('admin.permissions.delete-permission', $permission) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm" 
                                            onclick="return confirm('Tem certeza que deseja excluir esta permissão?')">
                                        <i class="fas fa-trash"></i> Excluir
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-400 text-sm cursor-not-allowed">
                                    <i class="fas fa-trash"></i> Excluir
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection 