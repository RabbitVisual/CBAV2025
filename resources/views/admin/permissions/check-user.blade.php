@extends('layouts.admin')

@section('title', 'Verificar Permissões - ' . $user->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    <i class="fas fa-eye text-green-600 mr-3"></i>
                    Verificar Permissões
                </h1>
                <p class="text-gray-600">Análise detalhada das permissões de {{ $user->name }}</p>
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

    <!-- Resumo de Permissões -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-user-tag text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Roles Ativas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $user->roles->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-key text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Permissões Diretas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $user->permissions->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-shield-alt text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Permissões</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $user->getAllPermissions()->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Análise Detalhada -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Análise Detalhada de Permissões</h2>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permissão</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Via Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Direta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roles</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($permissionsStatus as $permissionName => $status)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $permissionName }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($status['has_permission'])
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">Ativa</span>
                                @else
                                    <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded">Inativa</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($status['via_role'])
                                    <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">Sim</span>
                                @else
                                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-500 rounded">Não</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($status['direct'])
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">Sim</span>
                                @else
                                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-500 rounded">Não</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @php
                                        $permission = \Spatie\Permission\Models\Permission::where('name', $permissionName)->first();
                                        $roles = $permission ? $permission->roles : collect();
                                    @endphp
                                    @foreach($roles as $role)
                                        @if($user->hasRole($role))
                                            <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">{{ $role->name }}</span>
                                        @else
                                            <span class="px-2 py-1 text-xs bg-gray-100 text-gray-500 rounded">{{ $role->name }}</span>
                                        @endif
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Roles do Usuário -->
    <div class="bg-white rounded-lg shadow-md mt-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Roles do Usuário</h2>
        </div>
        <div class="p-6">
            @if($user->roles->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($user->roles as $role)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-semibold text-gray-900">{{ $role->name }}</h3>
                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                {{ $role->permissions->count() }} permissões
                            </span>
                        </div>
                        @if($role->description)
                            <p class="text-sm text-gray-600 mb-3">{{ $role->description }}</p>
                        @endif
                        <div class="flex flex-wrap gap-1">
                            @foreach($role->permissions->take(5) as $permission)
                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">{{ $permission->name }}</span>
                            @endforeach
                            @if($role->permissions->count() > 5)
                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">+{{ $role->permissions->count() - 5 }} mais</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Este usuário não possui roles atribuídas.</p>
            @endif
        </div>
    </div>

    <!-- Permissões Diretas -->
    <div class="bg-white rounded-lg shadow-md mt-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Permissões Diretas</h2>
        </div>
        <div class="p-6">
            @if($user->permissions->count() > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach($user->permissions as $permission)
                        <span class="px-3 py-1 text-sm bg-green-100 text-green-800 rounded-full">
                            {{ $permission->name }}
                        </span>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Este usuário não possui permissões diretas.</p>
            @endif
        </div>
    </div>

    <!-- Ações -->
    <div class="mt-8 flex justify-center space-x-4">
        <a href="{{ route('admin.permissions.edit-user', $user) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
            <i class="fas fa-edit mr-2"></i> Editar Permissões
        </a>
        
        <form action="{{ route('admin.permissions.reset-user', $user) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg" 
                    onclick="return confirm('Tem certeza que deseja resetar todas as permissões deste usuário?')">
                <i class="fas fa-undo mr-2"></i> Resetar Permissões
            </button>
        </form>
    </div>
</div>
@endsection 