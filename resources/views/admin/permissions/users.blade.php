@extends('layouts.admin')

@section('title', 'Gerenciar Usuários')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    <i class="fas fa-users-cog text-purple-600 mr-3"></i>
                    Gerenciar Usuários
                </h1>
                <p class="text-gray-600">Atribuir roles e permissões aos usuários</p>
            </div>
            <a href="{{ route('admin.permissions.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i> Voltar
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Filtros</h2>
        </div>
        <div class="p-6">
            <form method="GET" class="flex gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Buscar por nome ou email..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-search mr-2"></i> Buscar
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.permissions.users') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                        <i class="fas fa-times mr-2"></i> Limpar
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Lista de Usuários -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Usuários do Sistema</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roles</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permissões Diretas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
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
                                    <div class="text-xs text-gray-400">Membro desde {{ $user->created_at->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($user->roles as $role)
                                    <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">{{ $role->name }}</span>
                                @endforeach
                                @if($user->roles->count() == 0)
                                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-500 rounded">Sem roles</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($user->permissions->take(3) as $permission)
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">{{ $permission->name }}</span>
                                @endforeach
                                @if($user->permissions->count() > 3)
                                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">+{{ $user->permissions->count() - 3 }} mais</span>
                                @endif
                                @if($user->permissions->count() == 0)
                                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-500 rounded">Sem permissões diretas</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->hasAnyRole(['admin', 'super admin']))
                                <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded">Administrador</span>
                            @elseif($user->roles->count() > 0)
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">Com Roles</span>
                            @else
                                <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded">Sem Roles</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.permissions.edit-user', $user) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="{{ route('admin.permissions.check-user', $user) }}" class="text-green-600 hover:text-green-900 mr-3">
                                <i class="fas fa-eye"></i> Verificar
                            </a>
                            <form action="{{ route('admin.permissions.reset-user', $user) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-orange-600 hover:text-orange-900" 
                                        onclick="return confirm('Tem certeza que deseja resetar as permissões deste usuário?')">
                                    <i class="fas fa-undo"></i> Resetar
                                </button>
                            </form>
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

    <!-- Ações em Massa -->
    <div class="bg-white rounded-lg shadow-md mt-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Ações em Massa</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Atribuir Role -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Atribuir Role</h3>
                    <form action="{{ route('admin.permissions.bulk-assign-roles') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Selecionar Usuários</label>
                                <div class="max-h-32 overflow-y-auto border border-gray-200 rounded-md p-2">
                                    @foreach($users as $user)
                                    <label class="flex items-center space-x-3 mb-2">
                                        <input type="checkbox" name="users[]" value="{{ $user->id }}"
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <span class="text-sm text-gray-700">{{ $user->name }} ({{ $user->email }})</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                                <select name="role" id="role" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <option value="">Selecione uma role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                <i class="fas fa-user-tag mr-2"></i> Atribuir Role
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Atribuir Permissões -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Atribuir Permissões</h3>
                    <form action="{{ route('admin.permissions.bulk-assign-permissions') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Selecionar Usuários</label>
                                <div class="max-h-32 overflow-y-auto border border-gray-200 rounded-md p-2">
                                    @foreach($users as $user)
                                    <label class="flex items-center space-x-3 mb-2">
                                        <input type="checkbox" name="users[]" value="{{ $user->id }}"
                                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                        <span class="text-sm text-gray-700">{{ $user->name }} ({{ $user->email }})</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Permissões</label>
                                <div class="max-h-32 overflow-y-auto border border-gray-200 rounded-md p-2">
                                    @foreach($permissions as $permission)
                                    <label class="flex items-center space-x-3 mb-2">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                        <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                                <i class="fas fa-key mr-2"></i> Atribuir Permissões
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 