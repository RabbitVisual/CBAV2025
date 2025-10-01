@extends('layouts.admin')

@section('title', __('Histórico de Votações'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Histórico de Votações') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Visualize todas as votações finalizadas do conselho') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.council.dashboard') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('Voltar ao Dashboard') }}
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.council.voting.history') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Buscar') }}</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="{{ __('Título, descrição...') }}">
                </div>

                <div>
                    <label for="resultado" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Resultado') }}</label>
                    <select id="resultado" 
                            name="resultado"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('Todos') }}</option>
                        <option value="aprovada" {{ request('resultado') == 'aprovada' ? 'selected' : '' }}>{{ __('Aprovada') }}</option>
                        <option value="rejeitada" {{ request('resultado') == 'rejeitada' ? 'selected' : '' }}>{{ __('Rejeitada') }}</option>
                        <option value="empatada" {{ request('resultado') == 'empatada' ? 'selected' : '' }}>{{ __('Empatada') }}</option>
                    </select>
                </div>

                <div>
                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Tipo') }}</label>
                    <select id="tipo" 
                            name="tipo"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('Todos') }}</option>
                        <option value="simples" {{ request('tipo') == 'simples' ? 'selected' : '' }}>{{ __('Simples') }}</option>
                        <option value="qualificada" {{ request('tipo') == 'qualificada' ? 'selected' : '' }}>{{ __('Qualificada') }}</option>
                        <option value="secreta" {{ request('tipo') == 'secreta' ? 'selected' : '' }}>{{ __('Secreta') }}</option>
                    </select>
                </div>

                <div>
                    <label for="data_inicio" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Data Início') }}</label>
                    <input type="date" 
                           id="data_inicio" 
                           name="data_inicio" 
                           value="{{ request('data_inicio') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="flex justify-between items-center">
                <div class="flex space-x-3">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-search mr-2"></i>
                        {{ __('Filtrar') }}
                    </button>
                    <a href="{{ route('admin.council.voting.history') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-times mr-2"></i>
                        {{ __('Limpar') }}
                    </a>
                </div>
                
                <div class="flex items-center space-x-2">
                    <label for="ordenacao" class="text-sm font-medium text-gray-700">{{ __('Ordenar por:') }}</label>
                    <select id="ordenacao" 
                            name="sort"
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="created_desc" {{ request('sort', 'created_desc') == 'created_desc' ? 'selected' : '' }}>{{ __('Mais Recentes') }}</option>
                        <option value="created_asc" {{ request('sort') == 'created_asc' ? 'selected' : '' }}>{{ __('Mais Antigas') }}</option>
                        <option value="resultado" {{ request('sort') == 'resultado' ? 'selected' : '' }}>{{ __('Resultado') }}</option>
                        <option value="tipo" {{ request('sort') == 'tipo' ? 'selected' : '' }}>{{ __('Tipo') }}</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Lista de Votações -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($votacoes->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Votação') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Reunião') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Tipo') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Votos') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Resultado') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Data') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Ações') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($votacoes as $votacao)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $votacao->titulo }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($votacao->descricao, 100) }}</div>
                                        @if($votacao->pauta)
                                            <div class="text-xs text-blue-600">{{ __('Pauta:') }} {{ $votacao->pauta->titulo }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $votacao->conselho->titulo }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $votacao->tipo_color }}">
                                        {{ $votacao->tipo_votacao_text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="text-center">
                                        <div class="text-lg font-bold">{{ $votacao->votos->count() }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ __('de') }} {{ $votacao->conselho->participantes->count() }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $votacao->resultado_color }}">
                                        {{ $votacao->resultado_text }}
                                    </span>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $votacao->percentual_favoraveis }}% {{ __('a favor') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $votacao->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.council.voting.show', [$votacao->conselho, $votacao]) }}" 
                                           class="text-blue-600 hover:text-blue-900" 
                                           title="{{ __('Visualizar') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button onclick="exportarVotacao({{ $votacao->id }})" 
                                                class="text-green-600 hover:text-green-900" 
                                                title="{{ __('Exportar') }}">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-vote-yea text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Nenhuma votação encontrada') }}</h3>
                <p class="text-gray-500 mb-6">{{ __('Não há votações finalizadas para os filtros selecionados.') }}</p>
            </div>
        @endif
    </div>

    <!-- Paginação -->
    @if($votacoes->hasPages())
        <div class="mt-6">
            {{ $votacoes->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
function exportarVotacao(id) {
    // Implementar exportação da votação
    alert('Funcionalidade de exportação será implementada em breve.');
}
</script>
@endpush
@endsection 