@extends('layouts.admin')

@section('title', __('Votações do Conselho'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Votações do Conselho') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Conselho:') }} <strong>{{ $conselho->titulo }}</strong></p>
        </div>
        <div class="flex space-x-3">
            <button onclick="criarVotacao()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i>
                {{ __('Nova Votação') }}
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
                    <i class="fas fa-vote-yea text-blue-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Total de Votações') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['total_votacoes'] }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['votacoes_pendentes'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-play text-green-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Em Andamento') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['votacoes_andamento'] }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['votacoes_finalizadas'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.council.voting.index', $conselho) }}" class="space-y-4">
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
                        <option value="andamento" {{ request('status') == 'andamento' ? 'selected' : '' }}>{{ __('Em Andamento') }}</option>
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
                        <option value="simples" {{ request('tipo') == 'simples' ? 'selected' : '' }}>{{ __('Simples') }}</option>
                        <option value="qualificada" {{ request('tipo') == 'qualificada' ? 'selected' : '' }}>{{ __('Qualificada') }}</option>
                        <option value="secreta" {{ request('tipo') == 'secreta' ? 'selected' : '' }}>{{ __('Secreta') }}</option>
                    </select>
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
            </div>

            <div class="flex justify-between items-center">
                <div class="flex space-x-3">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-search mr-2"></i>
                        {{ __('Filtrar') }}
                    </button>
                    <a href="{{ route('admin.council.voting.index', $conselho) }}" 
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
                        <option value="status" {{ request('sort') == 'status' ? 'selected' : '' }}>{{ __('Status') }}</option>
                        <option value="tipo" {{ request('sort') == 'tipo' ? 'selected' : '' }}>{{ __('Tipo') }}</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Lista de Votações -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($votacoes->total() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Votação') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Tipo') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Status') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Votos') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Resultado') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Quórum') }}
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
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $votacao->tipo_color }}">
                                        {{ $votacao->tipo_votacao_text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $votacao->status_color }}">
                                        {{ $votacao->status_text }}
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
                                    @if($votacao->status == 'finalizada')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $votacao->resultado_color }}">
                                            {{ $votacao->resultado_text }}
                                        </span>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $votacao->percentual_favoraveis }}% {{ __('a favor') }}
                                        </div>
                                    @else
                                        <span class="text-gray-400">{{ __('Pendente') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-center">
                                        <div class="text-sm font-medium {{ $votacao->quorum_atingido ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $votacao->votos->count() }}/{{ $votacao->conselho->quorum_minimo }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $votacao->quorum_atingido ? __('Atingido') : __('Não atingido') }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.council.voting.show', [$conselho, $votacao]) }}" 
                                           class="text-blue-600 hover:text-blue-900" 
                                           title="{{ __('Visualizar') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($votacao->pode_iniciar)
                                            <button onclick="iniciarVotacao({{ $votacao->id }})" 
                                                    class="text-green-600 hover:text-green-900" 
                                                    title="{{ __('Iniciar Votação') }}">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        @endif
                                        @if($votacao->pode_finalizar)
                                            <button onclick="finalizarVotacao({{ $votacao->id }})" 
                                                    class="text-purple-600 hover:text-purple-900" 
                                                    title="{{ __('Finalizar Votação') }}">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                        @if($votacao->pode_cancelar)
                                            <button onclick="cancelarVotacao({{ $votacao->id }})" 
                                                    class="text-yellow-600 hover:text-yellow-900" 
                                                    title="{{ __('Cancelar Votação') }}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                        <button onclick="excluirVotacao({{ $votacao->id }})" 
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
                <i class="fas fa-vote-yea text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Nenhuma votação encontrada') }}</h3>
                <p class="text-gray-500 mb-6">{{ __('Não há votações para os filtros selecionados.') }}</p>
                <button onclick="criarVotacao()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    {{ __('Criar Primeira Votação') }}
                </button>
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
let votacaoId = null;

function criarVotacao() {
    window.location.href = '{{ route("admin.council.voting.create", $conselho) }}';
}

function iniciarVotacao(id) {
    votacaoId = id;
    acaoAtual = 'iniciar';
    mostrarModal('Iniciar Votação', 'Deseja iniciar esta votação?');
}

function finalizarVotacao(id) {
    votacaoId = id;
    acaoAtual = 'finalizar';
    mostrarModal('Finalizar Votação', 'Deseja finalizar esta votação?');
}

function cancelarVotacao(id) {
    votacaoId = id;
    acaoAtual = 'cancelar';
    mostrarModal('Cancelar Votação', 'Deseja cancelar esta votação?');
}

function excluirVotacao(id) {
    votacaoId = id;
    acaoAtual = 'excluir';
    mostrarModal('Excluir Votação', 'Deseja excluir esta votação? Esta ação não pode ser desfeita.');
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
    votacaoId = null;
}

function executarAcao() {
    const baseUrl = '{{ route("admin.council.voting.index", $conselho) }}';
    if (acaoAtual === 'iniciar') {
        window.location.href = `${baseUrl}/${votacaoId}/start`;
    } else if (acaoAtual === 'finalizar') {
        window.location.href = `${baseUrl}/${votacaoId}/finish`;
    } else if (acaoAtual === 'cancelar') {
        window.location.href = `${baseUrl}/${votacaoId}/cancel`;
    } else if (acaoAtual === 'excluir') {
        // Para excluir, vamos usar um form com DELETE
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `${baseUrl}/${votacaoId}`;
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