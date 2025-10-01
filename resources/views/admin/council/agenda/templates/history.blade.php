@extends('layouts.admin')

@section('title', __('Histórico de Usos dos Templates'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Histórico de Usos dos Templates') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Acompanhe como os templates estão sendo utilizados') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.council.agenda.template.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('Voltar') }}
            </a>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-copy text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Total de Templates') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['total_templates'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Templates Ativos') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['templates_ativos'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Total de Usos') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['total_usos'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-star text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Template Mais Usado') }}</p>
                    <p class="text-lg font-bold text-gray-900">{{ $estatisticas['template_mais_usado'] ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.council.agenda.template.history') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Buscar') }}</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="{{ __('Buscar por template ou reunião...') }}">
                </div>

                <div>
                    <label for="template_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Template') }}</label>
                    <select id="template_id" 
                            name="template_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('Todos os templates') }}</option>
                        @foreach($templates as $template)
                            <option value="{{ $template->id }}" {{ request('template_id') == $template->id ? 'selected' : '' }}>
                                {{ $template->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="periodo" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Período') }}</label>
                    <select id="periodo" 
                            name="periodo"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('Todos os períodos') }}</option>
                        <option value="hoje" {{ request('periodo') == 'hoje' ? 'selected' : '' }}>{{ __('Hoje') }}</option>
                        <option value="semana" {{ request('periodo') == 'semana' ? 'selected' : '' }}>{{ __('Última semana') }}</option>
                        <option value="mes" {{ request('periodo') == 'mes' ? 'selected' : '' }}>{{ __('Último mês') }}</option>
                        <option value="ano" {{ request('periodo') == 'ano' ? 'selected' : '' }}>{{ __('Último ano') }}</option>
                    </select>
                </div>

                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Ordenar') }}</label>
                    <select id="sort" 
                            name="sort"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>{{ __('Mais recentes') }}</option>
                        <option value="old" {{ request('sort') == 'old' ? 'selected' : '' }}>{{ __('Mais antigos') }}</option>
                        <option value="template" {{ request('sort') == 'template' ? 'selected' : '' }}>{{ __('Por template') }}</option>
                        <option value="usos" {{ request('sort') == 'usos' ? 'selected' : '' }}>{{ __('Por número de usos') }}</option>
                    </select>
                </div>
            </div>

            <div class="flex space-x-3">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                    <i class="fas fa-search mr-2"></i>
                    {{ __('Filtrar') }}
                </button>
                <a href="{{ route('admin.council.agenda.template.history') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                    <i class="fas fa-times mr-2"></i>
                    {{ __('Limpar') }}
                </a>
            </div>
        </form>
    </div>

    <!-- Lista de Usos -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($usos->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Template') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Reunião') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Data de Uso') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Criado Por') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Status da Reunião') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Ações') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($usos as $uso)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <i class="fas fa-copy text-blue-600"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $uso->template->nome }}</div>
                                            <div class="text-sm text-gray-500">{{ $uso->template->categoria_text }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $uso->conselho->titulo }}</div>
                                    <div class="text-sm text-gray-500">{{ $uso->conselho->data_reuniao->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $uso->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $uso->conselho->criadoPor->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $uso->conselho->criadoPor->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $uso->conselho->status_color }}">
                                        {{ $uso->conselho->status_text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.council.show', $uso->conselho) }}" 
                                           class="text-blue-600 hover:text-blue-900" 
                                           title="{{ __('Ver Reunião') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.council.agenda.template.edit', $uso->template) }}" 
                                           class="text-green-600 hover:text-green-900" 
                                           title="{{ __('Editar Template') }}">
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
            <div class="text-center py-12">
                <i class="fas fa-history text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Nenhum uso encontrado') }}</h3>
                <p class="text-gray-500 mb-6">{{ __('Nenhum template foi usado ainda.') }}</p>
                <a href="{{ route('admin.council.agenda.template.index') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 inline-flex items-center">
                    <i class="fas fa-copy mr-2"></i>
                    {{ __('Ver Templates') }}
                </a>
            </div>
        @endif
    </div>

    <!-- Paginação -->
    @if($usos && method_exists($usos, 'hasPages') && $usos->hasPages())
        <div class="mt-6">
            {{ $usos->links() }}
        </div>
    @endif
</div>

@include('components.modals')
@endsection 