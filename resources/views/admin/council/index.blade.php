@extends('layouts.admin')

@section('title', __('Reuniões do Conselho'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Reuniões do Conselho') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Gerencie todas as reuniões do conselho') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.council.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i>
                {{ __('Nova Reunião') }}
            </a>
            <a href="{{ route('admin.council.dashboard') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('Voltar ao Dashboard') }}
            </a>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-users text-blue-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Total de Reuniões') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['total'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-calendar-check text-green-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Reuniões Agendadas') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['agendadas'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-clock text-yellow-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Em Andamento') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['em_andamento'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-purple-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Reuniões Finalizadas') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['finalizadas'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.council.index') }}" class="space-y-4">
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
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Status') }}</label>
                    <select id="status" 
                            name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('Todos') }}</option>
                        <option value="agendada" {{ request('status') == 'agendada' ? 'selected' : '' }}>{{ __('Agendada') }}</option>
                        <option value="em_andamento" {{ request('status') == 'em_andamento' ? 'selected' : '' }}>{{ __('Em Andamento') }}</option>
                        <option value="finalizada" {{ request('status') == 'finalizada' ? 'selected' : '' }}>{{ __('Finalizada') }}</option>
                        <option value="cancelada" {{ request('status') == 'cancelada' ? 'selected' : '' }}>{{ __('Cancelada') }}</option>
                    </select>
                </div>

                <div>
                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Tipo') }}</label>
                    <select id="tipo" 
                            name="tipo"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('Todos') }}</option>
                        <option value="reuniao_ordinaria" {{ request('tipo') == 'reuniao_ordinaria' ? 'selected' : '' }}>{{ __('Reunião Ordinária') }}</option>
                        <option value="reuniao_extraordinaria" {{ request('tipo') == 'reuniao_extraordinaria' ? 'selected' : '' }}>{{ __('Reunião Extraordinária') }}</option>
                        <option value="votacao" {{ request('tipo') == 'votacao' ? 'selected' : '' }}>{{ __('Votação') }}</option>
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
                    <a href="{{ route('admin.council.index') }}" 
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
                        <option value="data_desc" {{ request('sort', 'data_desc') == 'data_desc' ? 'selected' : '' }}>{{ __('Data (Mais Recente)') }}</option>
                        <option value="data_asc" {{ request('sort') == 'data_asc' ? 'selected' : '' }}>{{ __('Data (Mais Antiga)') }}</option>
                        <option value="titulo" {{ request('sort') == 'titulo' ? 'selected' : '' }}>{{ __('Título') }}</option>
                        <option value="status" {{ request('sort') == 'status' ? 'selected' : '' }}>{{ __('Status') }}</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Lista de Reuniões -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($reunioes->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Reunião') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Data e Hora') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Tipo') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Status') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Participantes') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Ações') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reunioes as $reuniao)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $reuniao->titulo }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($reuniao->descricao, 100) }}</div>
                                        @if($reuniao->local)
                                            <div class="text-xs text-blue-600">{{ __('Local:') }} {{ $reuniao->local }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>
                                        <div>{{ $reuniao->data_reuniao->format('d/m/Y') }}</div>
                                        <div class="text-gray-500">{{ $reuniao->hora_inicio }} - {{ $reuniao->hora_fim ?? __('Não definido') }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $reuniao->tipo_color }}">
                                        {{ $reuniao->tipo_text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $reuniao->status_color }}">
                                        {{ $reuniao->status_text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="text-center">
                                        <div class="text-lg font-bold">{{ $reuniao->participantes->count() }}</div>
                                        <div class="text-xs text-gray-500">{{ __('participantes') }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.council.show', $reuniao) }}" 
                                           class="text-blue-600 hover:text-blue-900" 
                                           title="{{ __('Visualizar') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.council.edit', $reuniao) }}" 
                                           class="text-green-600 hover:text-green-900" 
                                           title="{{ __('Editar') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($reuniao->status === 'agendada')
                                            <button onclick="iniciarReuniao({{ $reuniao->id }})" 
                                                    class="text-yellow-600 hover:text-yellow-900" 
                                                    title="{{ __('Iniciar') }}">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        @endif
                                        @if($reuniao->status === 'em_andamento')
                                            <button onclick="finalizarReuniao({{ $reuniao->id }})" 
                                                    class="text-purple-600 hover:text-purple-900" 
                                                    title="{{ __('Finalizar') }}">
                                                <i class="fas fa-stop"></i>
                                            </button>
                                        @endif
                                        <button onclick="excluirReuniao({{ $reuniao->id }})" 
                                                class="text-red-600 hover:text-red-900" 
                                                title="{{ __('Excluir') }}">
                                            <i class="fas fa-trash"></i>
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
                <i class="fas fa-users text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Nenhuma reunião encontrada') }}</h3>
                <p class="text-gray-500 mb-6">{{ __('Crie sua primeira reunião do conselho.') }}</p>
                <a href="{{ route('admin.council.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    {{ __('Criar Primeira Reunião') }}
                </a>
            </div>
        @endif
    </div>

    <!-- Paginação -->
    @if($reunioes->hasPages())
        <div class="mt-6">
            {{ $reunioes->links() }}
        </div>
    @endif
</div>

<!-- Modal de Confirmação -->
<div id="modalConfirmacao" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-gray-900" id="modalTitulo">{{ __('Confirmar Ação') }}</h3>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-6" id="modalMensagem">
                    {{ __('Tem certeza que deseja executar esta ação?') }}
                </p>
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="cancelarModal()"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        {{ __('Cancelar') }}
                    </button>
                    <button type="button" 
                            id="confirmarAcao"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200">
                        {{ __('Confirmar') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let acaoAtual = '';
let reuniaoId = null;

function iniciarReuniao(id) {
    reuniaoId = id;
    acaoAtual = 'iniciar';
    mostrarModal('Iniciar Reunião', 'Deseja iniciar esta reunião?');
}

function finalizarReuniao(id) {
    reuniaoId = id;
    acaoAtual = 'finalizar';
    mostrarModal('Finalizar Reunião', 'Deseja finalizar esta reunião?');
}

function excluirReuniao(id) {
    reuniaoId = id;
    acaoAtual = 'excluir';
    mostrarModal('Excluir Reunião', 'Deseja excluir esta reunião? Esta ação não pode ser desfeita.');
}

function mostrarModal(titulo, mensagem) {
    document.getElementById('modalTitulo').textContent = titulo;
    document.getElementById('modalMensagem').textContent = mensagem;
    document.getElementById('modalConfirmacao').classList.remove('hidden');
    
    document.getElementById('confirmarAcao').onclick = executarAcao;
}

function cancelarModal() {
    document.getElementById('modalConfirmacao').classList.add('hidden');
    acaoAtual = '';
    reuniaoId = null;
}

function executarAcao() {
    if (acaoAtual === 'iniciar') {
        window.location.href = `/admin/council/${reuniaoId}/iniciar`;
    } else if (acaoAtual === 'finalizar') {
        window.location.href = `/admin/council/${reuniaoId}/finalizar`;
    } else if (acaoAtual === 'excluir') {
        // Implementar exclusão via AJAX
        fetch(`/admin/council/${reuniaoId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(response => {
            if (response.ok) {
                window.location.reload();
            }
        });
    }
    
    cancelarModal();
}
</script>
@endpush
@endsection 