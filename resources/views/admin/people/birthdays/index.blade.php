@extends('layouts.admin')

@section('title', __('Aniversariantes'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ __('Aniversariantes') }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.people.birthdays.upcoming') }}" 
               class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-calendar-alt mr-2"></i>
                Próximos Aniversários
            </a>
            <a href="{{ route('admin.people.members.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-plus mr-2"></i>
                {{ __('Novo Membro') }}
            </a>
        </div>
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
                <div class="p-3 rounded-lg bg-pink-500 text-white">
                    <i class="fas fa-birthday-cake text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Este Mês') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ isset($aniversariantesMes) && $aniversariantesMes ? $aniversariantesMes->count() : 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-blue-500 text-white">
                    <i class="fas fa-calendar-day text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Hoje') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ isset($aniversariantesHoje) && $aniversariantesHoje ? $aniversariantesHoje->count() : 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-purple-500 text-white">
                    <i class="fas fa-calendar-week text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Esta Semana') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ isset($aniversariantesSemana) && $aniversariantesSemana ? $aniversariantesSemana->count() : 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-green-500 text-white">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Ativos') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalMembrosAtivos ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Filtros de Pesquisa') }}</h3>
        <form method="GET" action="{{ route('admin.people.birthdays.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="mes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Mês') }}</label>
                    <select id="mes" 
                            name="mes"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">{{ __('Todos os meses') }}</option>
                        @if(isset($meses))
                            @foreach($meses as $numero => $nome)
                                <option value="{{ $numero }}" {{ request('mes', now()->month) == $numero ? 'selected' : '' }}>
                                    {{ $nome }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div>
                    <label for="ministerio_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Ministério') }}</label>
                    <select id="ministerio_id" 
                            name="ministerio_id"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">{{ __('Todos os ministérios') }}</option>
                        @if(isset($ministerios))
                            @foreach($ministerios as $ministerio)
                                <option value="{{ $ministerio->id }}" {{ request('ministerio_id') == $ministerio->id ? 'selected' : '' }}>
                                    {{ $ministerio->nome }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="flex items-end space-x-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                        <i class="fas fa-search mr-2"></i>
                        {{ __('Filtrar') }}
                    </button>
                    <a href="{{ route('admin.people.birthdays.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>
                        {{ __('Limpar') }}
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Lista de Aniversariantes -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
        @if(isset($aniversariantes) && $aniversariantes && $aniversariantes->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Nome') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Data de Nascimento') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Idade') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($aniversariantes as $membro)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                                            <span class="text-white font-bold">
                                                {{ strtoupper(substr($membro->nome, 0, 2)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $membro->nome }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $membro->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $membro->data_nascimento ? $membro->data_nascimento->format('d/m/Y') : '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-pink-100 text-pink-800">
                                        {{ $membro->data_nascimento ? $membro->data_nascimento->age : '-' }} anos
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Paginação -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                {{ $aniversariantes->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-16 h-16 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-birthday-cake text-gray-400 dark:text-gray-300 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ __('Nenhum aniversariante encontrado') }}</h3>
                <p class="text-gray-500 dark:text-gray-400">{{ __('Não há aniversariantes para os filtros selecionados.') }}</p>
            </div>
        @endif
    </div>
</div>
@endsection