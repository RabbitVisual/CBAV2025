@extends('layouts.admin')

@section('title', 'Editar Permissão - ' . $permission->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    <i class="fas fa-key text-green-600 mr-3"></i>
                    Editar Permissão
                </h1>
                <p class="text-gray-600">Editar permissão: {{ $permission->name }}</p>
            </div>
            <a href="{{ route('admin.permissions.permissions') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i> Voltar
            </a>
        </div>
    </div>

    <!-- Formulário de Edição -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Editar Permissão: {{ $permission->name }}</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.permissions.update-permission', $permission) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nome da Permissão</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $permission->name) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               placeholder="Ex: users.create, finance.access">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                        <input type="text" name="description" id="description" value="{{ old('description', $permission->description) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               placeholder="Descrição da permissão">
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="guard_name" class="block text-sm font-medium text-gray-700 mb-2">Guard</label>
                        <select name="guard_name" id="guard_name" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="web" {{ old('guard_name', $permission->guard_name) == 'web' ? 'selected' : '' }}>Web</option>
                            <option value="api" {{ old('guard_name', $permission->guard_name) == 'api' ? 'selected' : '' }}>API</option>
                        </select>
                        @error('guard_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-between">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                        <i class="fas fa-save mr-2"></i> Salvar Alterações
                    </button>
                    
                    @if($permission->roles()->count() == 0)
                        <form action="{{ route('admin.permissions.delete-permission', $permission) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg" 
                                    onclick="return confirm('Tem certeza que deseja excluir esta permissão?')">
                                <i class="fas fa-trash mr-2"></i> Excluir Permissão
                            </button>
                        </form>
                    @else
                        <span class="text-gray-400 text-sm">Não é possível excluir uma permissão em uso</span>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Informações da Permissão -->
    <div class="bg-white rounded-lg shadow-md mt-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Informações da Permissão</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Roles que usam esta permissão</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $permission->roles()->count() }}</p>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Usuários com permissão direta</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $permission->users()->count() }}</p>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Criada em</h3>
                    <p class="text-sm text-gray-600">{{ $permission->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Roles que usam esta permissão -->
    @if($permission->roles()->count() > 0)
    <div class="bg-white rounded-lg shadow-md mt-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Roles que usam esta permissão</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($permission->roles as $role)
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
                        @foreach($role->permissions->take(3) as $rolePermission)
                            <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">{{ $rolePermission->name }}</span>
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
    @endif
</div>
@endsection 