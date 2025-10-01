@extends('layouts.admin')

@section('title', __('Membros'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Cabeçalho com Glassmorphism -->
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border border-white/20 dark:border-gray-700/30 rounded-2xl p-6 mb-8 shadow-xl">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    {{ __('Membros') }}
                </h1>
                <p class="text-gray-600 dark:text-gray-300 mt-1">{{ __('Gerencie os membros da igreja') }}</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <a href="{{ route('admin.people.members.import') }}" 
                   class="inline-flex items-center justify-center px-4 py-2.5 bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-upload mr-2"></i>{{ __('Importar') }}
                </a>
                <a href="{{ route('admin.people.members.create') }}" 
                   class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>{{ __('Novo Membro') }}
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded-xl mb-6 shadow-sm">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border border-white/20 dark:border-gray-700/30 rounded-2xl p-6 mb-8 shadow-xl">
        <div class="flex items-center mb-4">
            <i class="fas fa-filter text-blue-600 dark:text-blue-400 mr-2"></i>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Filtros de Busca') }}</h3>
        </div>
        <form method="GET" action="{{ route('admin.people.members.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Buscar') }}</label>
                <input type="text" 
                       id="search" 
                       name="search" 
                       value="{{ request('search') }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 bg-white dark:bg-gray-800 text-gray-900 dark:text-white transition-colors duration-200"
                       placeholder="{{ __('Nome, email ou telefone...') }}">
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Status') }}</label>
                <select id="status" 
                        name="status"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 bg-white dark:bg-gray-800 text-gray-900 dark:text-white transition-colors duration-200">
                    <option value="">{{ __('Todos') }}</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>{{ __('Ativos') }}</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>{{ __('Inativos') }}</option>
                </select>
            </div>
            
            <div>
                <label for="sexo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Gênero') }}</label>
                <select id="sexo" 
                        name="sexo"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 bg-white dark:bg-gray-800 text-gray-900 dark:text-white transition-colors duration-200">
                    <option value="">{{ __('Todos') }}</option>
                    <option value="M" {{ request('sexo') == 'M' ? 'selected' : '' }}>{{ __('Masculino') }}</option>
                    <option value="F" {{ request('sexo') == 'F' ? 'selected' : '' }}>{{ __('Feminino') }}</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white font-medium py-2.5 px-4 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-search mr-2"></i>{{ __('Filtrar') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border border-white/20 dark:border-gray-700/30 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-lg">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total de Membros') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalMembros }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border border-white/20 dark:border-gray-700/30 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-gradient-to-br from-green-500 to-green-600 text-white shadow-lg">
                    <i class="fas fa-user-check text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Membros Ativos') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $membrosAtivos }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border border-white/20 dark:border-gray-700/30 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-gradient-to-br from-red-500 to-red-600 text-white shadow-lg">
                    <i class="fas fa-user-times text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Membros Inativos') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $membrosInativos }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border border-white/20 dark:border-gray-700/30 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 text-white shadow-lg">
                    <i class="fas fa-birthday-cake text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Aniversariantes') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $aniversariantes }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Membros -->
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border border-white/20 dark:border-gray-700/30 rounded-2xl overflow-hidden shadow-xl">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <i class="fas fa-list text-blue-600 dark:text-blue-400 mr-2"></i>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Lista de Membros') }}</h3>
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Mostrando') }} {{ $membros->firstItem() ?? 0 }} - {{ $membros->lastItem() ?? 0 }} 
                    {{ __('de') }} {{ $membros->total() }} {{ __('membros') }}
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            {{ __('Membro') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            {{ __('Contato') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            {{ __('Idade') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            {{ __('Status') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            {{ __('Ministérios') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            {{ __('Ações') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800/50 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($membros as $membro)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($membro->foto_existe)
                                        <img class="h-10 w-10 rounded-full object-cover ring-2 ring-white dark:ring-gray-600" 
                                             src="{{ $membro->foto_url }}" 
                                             alt="{{ $membro->nome }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg">
                                            <span class="text-white font-bold text-sm">{{ $membro->iniciais }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $membro->nome }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('ID:') }} #{{ $membro->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">{{ $membro->email }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $membro->telefone ?: __('Não informado') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($membro->data_nascimento)
                                <div class="text-sm text-gray-900 dark:text-white">{{ $membro->idade }} {{ __('anos') }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $membro->data_nascimento->format('d/m/Y') }}</div>
                            @else
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Não informado') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $membro->ativo ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' }}">
                                {{ $membro->ativo ? __('Ativo') : __('Inativo') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-wrap gap-1">
                                @forelse($membro->ministerios->take(2) as $ministerio)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                        {{ $ministerio->nome }}
                                    </span>
                                @empty
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Nenhum') }}</span>
                                @endforelse
                                @if($membro->ministerios->count() > 2)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                        +{{ $membro->ministerios->count() - 2 }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-3">
                                <a href="{{ route('admin.people.members.show', $membro) }}" 
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors duration-200" 
                                   title="{{ __('Visualizar') }}">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                <a href="{{ route('admin.people.members.edit', $membro) }}" 
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-900/50 transition-colors duration-200" 
                                   title="{{ __('Editar') }}">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-users text-4xl text-gray-400 dark:text-gray-600 mb-4"></i>
                                <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">{{ __('Nenhum membro encontrado.') }}</p>
                                <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">{{ __('Tente ajustar os filtros de busca.') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($membros->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30">
            {{ $membros->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection