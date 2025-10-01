@extends('layouts.admin')

@section('title', __('Departamentos'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Departamentos') }}</h1>
        <a href="{{ route('admin.people.departments.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
            <i class="fas fa-plus mr-2"></i>{{ __('Novo Departamento') }}
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-800 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-blue-500 text-white">
                    <i class="fas fa-sitemap text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $departamentos->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
             <div class="flex items-center">
                 <div class="p-3 rounded-lg bg-green-500 text-white">
                     <i class="fas fa-check-circle text-xl"></i>
                 </div>
                 <div class="ml-4">
                     <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Ativos') }}</p>
                     <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $departamentos->where('ativo', true)->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
             <div class="flex items-center">
                 <div class="p-3 rounded-lg bg-purple-500 text-white">
                     <i class="fas fa-users text-xl"></i>
                 </div>
                 <div class="ml-4">
                     <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Membros') }}</p>
                     <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalMembros }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
             <div class="flex items-center">
                 <div class="p-3 rounded-lg bg-indigo-500 text-white">
                     <i class="fas fa-briefcase text-xl"></i>
                 </div>
                 <div class="ml-4">
                     <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Cargos') }}</p>
                     <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalCargos }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Filtros e Busca') }}</h3>
        <form method="GET" action="{{ route('admin.people.departments.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Buscar') }}</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400"
                           placeholder="{{ __('Nome do departamento...') }}">
                </div>

                <div>
                    <label for="ministerio_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Ministério') }}</label>
                    <select id="ministerio_id" 
                            name="ministerio_id"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                        <option value="">{{ __('Todos os ministérios') }}</option>
                        @foreach($ministerios as $ministerio)
                            <option value="{{ $ministerio->id }}" {{ request('ministerio_id') == $ministerio->id ? 'selected' : '' }}>
                                {{ $ministerio->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Status') }}</label>
                    <select id="status" 
                            name="status"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                        <option value="">{{ __('Todos') }}</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>{{ __('Ativos') }}</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>{{ __('Inativos') }}</option>
                    </select>
                </div>

                <div class="flex items-end space-x-2">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                        <i class="fas fa-search mr-2"></i>{{ __('Filtrar') }}
                    </button>
                    <a href="{{ route('admin.people.departments.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>{{ __('Limpar') }}
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Lista de Departamentos -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
        @if($departamentos->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Departamento') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Ministério') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Responsável') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Membros') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Cargos') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Status') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Ações') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($departamentos as $departamento)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full mr-3" style="background-color: {{ $departamento->cor ?? '#3B82F6' }}"></div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $departamento->nome }}</div>
                                            @if($departamento->descricao)
                                                <div class="text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">{{ $departamento->descricao }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $departamento->ministerio->nome }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($departamento->responsavel)
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                @if($departamento->responsavel->foto_existe)
                                                    <img class="h-8 w-8 rounded-full" src="{{ $departamento->responsavel->foto_url }}" alt="{{ $departamento->responsavel->nome }}">
                                                @else
                                                    <div class="h-8 w-8 rounded-full bg-purple-500 flex items-center justify-center">
                                                        <span class="text-white font-medium text-sm">{{ $departamento->responsavel->iniciais }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $departamento->responsavel->nome }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500 text-sm">{{ __('Não definido') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
                                        {{ $departamento->membros_count ?? 0 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                                        {{ $departamento->cargos_count ?? 0 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($departamento->ativo)
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                            {{ __('Ativo') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                            {{ __('Inativo') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.people.departments.show', $departamento) }}" 
                                           class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.people.departments.edit', $departamento) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-sitemap text-gray-400 dark:text-gray-500 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('Nenhum departamento encontrado') }}</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">{{ __('Comece criando o primeiro departamento da igreja.') }}</p>
                <a href="{{ route('admin.people.departments.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>{{ __('Criar Primeiro Departamento') }}
                </a>
            </div>
        @endif
    </div>

    <!-- Paginação -->
    @if($departamentos->hasPages())
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mt-6">
            {{ $departamentos->links() }}
        </div>
    @endif
</div>
@endsection