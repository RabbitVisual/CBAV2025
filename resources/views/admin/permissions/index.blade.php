@extends('layouts.admin')

@section('title', 'Gerenciamento de Permissões')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            <i class="fas fa-shield-alt text-blue-600 mr-3"></i>
            Gerenciamento de Permissões
        </h1>
        <p class="text-gray-600">Gerencie roles, permissões e acesso dos usuários ao sistema</p>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Roles</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['total_roles'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-key text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Permissões</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['total_permissions'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-user-friends text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Usuários</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['total_users'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                    <i class="fas fa-user-check text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Usuários com Roles</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['users_with_roles'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Navegação Rápida -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('admin.permissions.roles') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-user-tag text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Gerenciar Roles</h3>
                    <p class="text-sm text-gray-600">Criar, editar e excluir roles do sistema</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.permissions.permissions') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-key text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Gerenciar Permissões</h3>
                    <p class="text-sm text-gray-600">Criar, editar e excluir permissões</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.permissions.users') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-users-cog text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Gerenciar Usuários</h3>
                    <p class="text-sm text-gray-600">Atribuir roles e permissões aos usuários</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Roles Recentes -->
    <div class="bg-white rounded-lg shadow-md mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Roles do Sistema</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($roles as $role)
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
                        @foreach($role->permissions->take(3) as $permission)
                            <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">{{ $permission->name }}</span>
                        @endforeach
                        @if($role->permissions->count() > 3)
                            <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">+{{ $role->permissions->count() - 3 }} mais</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Usuários Recentes -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Usuários Recentes</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roles</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permissões Diretas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-700">{{ substr($user->name, 0, 2) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-wrap gap-1">
                                @foreach($user->roles as $role)
                                    <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">{{ $role->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-wrap gap-1">
                                @foreach($user->permissions->take(3) as $permission)
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">{{ $permission->name }}</span>
                                @endforeach
                                @if($user->permissions->count() > 3)
                                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">+{{ $user->permissions->count() - 3 }} mais</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.permissions.edit-user', $user) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="{{ route('admin.permissions.check-user', $user) }}" class="text-green-600 hover:text-green-900">
                                <i class="fas fa-eye"></i> Verificar
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection 