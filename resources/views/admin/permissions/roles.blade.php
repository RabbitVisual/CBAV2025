@extends('layouts.admin')

@section('title', 'Gerenciar Roles')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    <i class="fas fa-user-tag text-blue-600 mr-3"></i>
                    Gerenciar Roles
                </h1>
                <p class="text-gray-600">Criar, editar e excluir roles do sistema</p>
            </div>
            <a href="{{ route('admin.permissions.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i> Voltar
            </a>
        </div>
    </div>

    <!-- Formulário de Criação -->
    <div class="bg-white rounded-lg shadow-md mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Criar Nova Role</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.permissions.create-role') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nome da Role</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Ex: admin, membro, tesoureiro">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                        <input type="text" name="description" id="description" value="{{ old('description') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Descrição da role">
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Permissões</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-64 overflow-y-auto border border-gray-200 rounded-md p-4">
                        @foreach($permissions as $permission)
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                        <i class="fas fa-plus mr-2"></i> Criar Role
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Roles -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Roles Existentes</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permissões</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuários</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($roles as $role)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-user-tag text-blue-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $role->name }}</div>
                                    <div class="text-sm text-gray-500">Criada em {{ $role->created_at->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $role->description ?: 'Sem descrição' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($role->permissions->take(5) as $permission)
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">{{ $permission->name }}</span>
                                @endforeach
                                @if($role->permissions->count() > 5)
                                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">+{{ $role->permissions->count() - 5 }} mais</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">
                                {{ $role->users_count ?? 0 }} usuários
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.permissions.edit-role', $role) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            @if(($role->users_count ?? 0) == 0)
                                <form action="{{ route('admin.permissions.delete-role', $role) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" 
                                            onclick="return confirm('Tem certeza que deseja excluir esta role?')">
                                        <i class="fas fa-trash"></i> Excluir
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-400 cursor-not-allowed">
                                    <i class="fas fa-trash"></i> Excluir
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 