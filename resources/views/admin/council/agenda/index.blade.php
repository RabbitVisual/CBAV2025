@extends('layouts.admin')

@section('title', __('Agenda do Conselho'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Agenda do Conselho') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Conselho:') }} <strong>{{ $conselho->titulo }}</strong></p>
        </div>
        <div class="flex space-x-3">
            <button onclick="adicionarPauta()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i>
                {{ __('Nova Pauta') }}
            </button>
            <a href="{{ route('admin.council.show', $conselho) }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('Voltar ao Conselho') }}
            </a>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-list text-blue-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Total de Pautas') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['total_pautas'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-clock text-yellow-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Pendentes') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['pautas_pendentes'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-comments text-green-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Em Discussão') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['pautas_discussao'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check text-purple-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Finalizadas') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['pautas_finalizadas'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.council.agenda.index', $conselho) }}" class="space-y-4">
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
                        <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>{{ __('Pendente') }}</option>
                        <option value="discussao" {{ request('status') == 'discussao' ? 'selected' : '' }}>{{ __('Em Discussão') }}</option>
                        <option value="finalizada" {{ request('status') == 'finalizada' ? 'selected' : '' }}>{{ __('Finalizada') }}</option>
                        <option value="adiada" {{ request('status') == 'adiada' ? 'selected' : '' }}>{{ __('Adiada') }}</option>
                    </select>
                </div>

                <div>
                    <label for="prioridade" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Prioridade') }}</label>
                    <select id="prioridade" 
                            name="prioridade"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('Todas') }}</option>
                        <option value="baixa" {{ request('prioridade') == 'baixa' ? 'selected' : '' }}>{{ __('Baixa') }}</option>
                        <option value="media" {{ request('prioridade') == 'media' ? 'selected' : '' }}>{{ __('Média') }}</option>
                        <option value="alta" {{ request('prioridade') == 'alta' ? 'selected' : '' }}>{{ __('Alta') }}</option>
                        <option value="urgente" {{ request('prioridade') == 'urgente' ? 'selected' : '' }}>{{ __('Urgente') }}</option>
                    </select>
                </div>

                <div>
                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Tipo') }}</label>
                    <select id="tipo" 
                            name="tipo"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('Todos') }}</option>
                        <option value="administrativa" {{ request('tipo') == 'administrativa' ? 'selected' : '' }}>{{ __('Administrativa') }}</option>
                        <option value="financeira" {{ request('tipo') == 'financeira' ? 'selected' : '' }}>{{ __('Financeira') }}</option>
                        <option value="pastoral" {{ request('tipo') == 'pastoral' ? 'selected' : '' }}>{{ __('Pastoral') }}</option>
                        <option value="ministerial" {{ request('tipo') == 'ministerial' ? 'selected' : '' }}>{{ __('Ministerial') }}</option>
                        <option value="outros" {{ request('tipo') == 'outros' ? 'selected' : '' }}>{{ __('Outros') }}</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-between items-center">
                <div class="flex space-x-3">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-search mr-2"></i>
                        {{ __('Filtrar') }}
                    </button>
                    <a href="{{ route('admin.council.agenda.index', $conselho) }}" 
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
                        <option value="ordem" {{ request('sort', 'ordem') == 'ordem' ? 'selected' : '' }}>{{ __('Ordem') }}</option>
                        <option value="prioridade" {{ request('sort') == 'prioridade' ? 'selected' : '' }}>{{ __('Prioridade') }}</option>
                        <option value="tipo" {{ request('sort') == 'tipo' ? 'selected' : '' }}>{{ __('Tipo') }}</option>
                        <option value="status" {{ request('sort') == 'status' ? 'selected' : '' }}>{{ __('Status') }}</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Lista de Pautas -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($pautas->total() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Ordem') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Pauta') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Tipo') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Prioridade') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Status') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Responsável') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Ações') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pautas as $pauta)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $pauta->ordem }}
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $pauta->titulo }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($pauta->descricao, 100) }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pauta->tipo_color }}">
                                        {{ $pauta->tipo_text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pauta->prioridade_color }}">
                                        {{ $pauta->prioridade_text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pauta->status_color }}">
                                        {{ $pauta->status_text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $pauta->responsavel->name ?? 'Não definido' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.council.agenda.show', [$conselho, $pauta]) }}" 
                                           class="text-blue-600 hover:text-blue-900" 
                                           title="{{ __('Visualizar') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.council.agenda.edit', [$conselho, $pauta]) }}" 
                                           class="text-green-600 hover:text-green-900" 
                                           title="{{ __('Editar') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($pauta->pode_iniciar_discussao)
                                            <button onclick="iniciarDiscussao({{ $pauta->id }})" 
                                                    class="text-yellow-600 hover:text-yellow-900" 
                                                    title="{{ __('Iniciar Discussão') }}">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        @endif
                                        @if($pauta->pode_finalizar)
                                            <button onclick="finalizarPauta({{ $pauta->id }})" 
                                                    class="text-purple-600 hover:text-purple-900" 
                                                    title="{{ __('Finalizar') }}">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                        <button onclick="excluirPauta({{ $pauta->id }})" 
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
                <i class="fas fa-list text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Nenhuma pauta encontrada') }}</h3>
                <p class="text-gray-500 mb-6">{{ __('Não há pautas para os filtros selecionados.') }}</p>
                <button onclick="adicionarPauta()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    {{ __('Criar Primeira Pauta') }}
                </button>
            </div>
        @endif
    </div>

    <!-- Paginação -->
    @if($pautas->hasPages())
        <div class="mt-6">
            {{ $pautas->links() }}
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
let pautaId = null;

function adicionarPauta() {
    window.location.href = '{{ route("admin.council.agenda.create", $conselho) }}';
}

function iniciarDiscussao(id) {
    pautaId = id;
    acaoAtual = 'iniciar';
    mostrarModal('Iniciar Discussão', 'Deseja iniciar a discussão desta pauta?');
}

function finalizarPauta(id) {
    pautaId = id;
    acaoAtual = 'finalizar';
    mostrarModal('Finalizar Pauta', 'Deseja finalizar esta pauta?');
}

function excluirPauta(id) {
    pautaId = id;
    acaoAtual = 'excluir';
    mostrarModal('Excluir Pauta', 'Deseja excluir esta pauta? Esta ação não pode ser desfeita.');
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
    pautaId = null;
}

function executarAcao() {
    const baseUrl = '{{ route("admin.council.agenda.index", $conselho) }}';
    if (acaoAtual === 'iniciar') {
        window.location.href = `${baseUrl}/${pautaId}/discussao`;
    } else if (acaoAtual === 'finalizar') {
        window.location.href = `${baseUrl}/${pautaId}/finalizar`;
    } else if (acaoAtual === 'excluir') {
        // Para excluir, vamos usar um form com DELETE
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `${baseUrl}/${pautaId}`;
        form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
        document.body.appendChild(form);
        form.submit();
    }
    
    cancelarModal();
}
</script>
@endpush
@endsection 