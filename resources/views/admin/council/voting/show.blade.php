@extends('layouts.admin')

@section('title', __('Detalhes da Votação'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Detalhes da Votação') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Conselho:') }} <strong>{{ $conselho->titulo }}</strong></p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.council.voting.index', $conselho) }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('Voltar') }}
            </a>
        </div>
    </div>

    <!-- Informações da Votação -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Informações Gerais') }}</h3>
                
                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-700">{{ __('Título:') }}</span>
                        <p class="text-lg text-gray-900">{{ $votacao->titulo }}</p>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-700">{{ __('Descrição:') }}</span>
                        <p class="text-gray-900">{{ $votacao->descricao ?: 'Nenhuma descrição fornecida' }}</p>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-700">{{ __('Tipo:') }}</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $votacao->tipo_color }}">
                            {{ $votacao->tipo_votacao_text }}
                        </span>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-700">{{ __('Status:') }}</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $votacao->status_color }}">
                            {{ $votacao->status_text }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Estatísticas') }}</h3>
                
                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-700">{{ __('Total de Votos:') }}</span>
                        <p class="text-lg font-bold text-gray-900">{{ $votacao->votos->count() }}</p>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-700">{{ __('Votos Favoráveis:') }}</span>
                        <p class="text-lg font-bold text-green-600">{{ $votacao->votos->where('voto', 'favoravel')->count() }}</p>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-700">{{ __('Votos Contrários:') }}</span>
                        <p class="text-lg font-bold text-red-600">{{ $votacao->votos->where('voto', 'contrario')->count() }}</p>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-700">{{ __('Abstenções:') }}</span>
                        <p class="text-lg font-bold text-yellow-600">{{ $votacao->votos->where('voto', 'abstencao')->count() }}</p>
                    </div>
                    
                    @if($votacao->status == 'finalizada')
                        <div>
                            <span class="text-sm font-medium text-gray-700">{{ __('Resultado:') }}</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $votacao->resultado_color }}">
                                {{ $votacao->resultado_text }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Configurações da Votação -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Configurações') }}</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <span class="text-sm font-medium text-gray-700">{{ __('Tempo Limite:') }}</span>
                <p class="text-lg text-gray-900">{{ $votacao->tempo_limite_formatado }}</p>
            </div>
            
            <div>
                <span class="text-sm font-medium text-gray-700">{{ __('Quórum Mínimo:') }}</span>
                <p class="text-lg text-gray-900">{{ $votacao->quorum_minimo }} participantes</p>
            </div>
            
            <div>
                <span class="text-sm font-medium text-gray-700">{{ __('Quórum Atingido:') }}</span>
                <p class="text-lg {{ $votacao->quorum_atingido ? 'text-green-600' : 'text-red-600' }}">
                    {{ $votacao->quorum_atingido ? 'Sim' : 'Não' }}
                </p>
            </div>
            
            <div>
                <span class="text-sm font-medium text-gray-700">{{ __('Permitir Abstenção:') }}</span>
                <p class="text-lg text-gray-900">{{ $votacao->permitir_abstencao ? 'Sim' : 'Não' }}</p>
            </div>
            
            <div>
                <span class="text-sm font-medium text-gray-700">{{ __('Justificativa Obrigatória:') }}</span>
                <p class="text-lg text-gray-900">{{ $votacao->justificativa_obrigatoria ? 'Sim' : 'Não' }}</p>
            </div>
            
            <div>
                <span class="text-sm font-medium text-gray-700">{{ __('Voto Anônimo:') }}</span>
                <p class="text-lg text-gray-900">{{ $votacao->voto_anonimo ? 'Sim' : 'Não' }}</p>
            </div>
        </div>
    </div>

    <!-- Pauta Relacionada -->
    @if($votacao->pauta)
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Pauta Relacionada') }}</h3>
            
            <div class="space-y-3">
                <div>
                    <span class="text-sm font-medium text-gray-700">{{ __('Título:') }}</span>
                    <p class="text-lg text-gray-900">{{ $votacao->pauta->titulo }}</p>
                </div>
                
                <div>
                    <span class="text-sm font-medium text-gray-700">{{ __('Descrição:') }}</span>
                    <p class="text-gray-900">{{ $votacao->pauta->descricao ?: 'Nenhuma descrição fornecida' }}</p>
                </div>
                
                <div>
                    <span class="text-sm font-medium text-gray-700">{{ __('Status:') }}</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $votacao->pauta->status_color }}">
                        {{ $votacao->pauta->status_text }}
                    </span>
                </div>
            </div>
        </div>
    @endif

    <!-- Lista de Votos -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">{{ __('Votos Registrados') }}</h3>
        </div>
        
        @if($votacao->votos->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Participante') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Voto') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Data/Hora') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Justificativa') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($votacao->votos as $voto)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($voto->user->foto_existe)
                                                <img class="h-10 w-10 rounded-full" 
                                                     src="{{ $voto->user->foto_url }}" 
                                                     alt="{{ $voto->user->name }}">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-gray-700">
                                                        {{ $voto->user->iniciais }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $voto->user->name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $voto->user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $voto->voto_color }}">
                                        {{ $voto->voto_text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $voto->data_voto->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $voto->justificativa ?: 'Nenhuma justificativa fornecida' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-vote-yea text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Nenhum voto registrado') }}</h3>
                <p class="text-gray-500">{{ __('Ainda não há votos registrados para esta votação.') }}</p>
            </div>
        @endif
    </div>

    <!-- Ações -->
    <div class="mt-6 flex justify-end space-x-3">
        @if($votacao->pode_iniciar)
            <button onclick="iniciarVotacao({{ $votacao->id }})" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-play mr-2"></i>
                {{ __('Iniciar Votação') }}
            </button>
        @endif
        
        @if($votacao->pode_finalizar)
            <button onclick="finalizarVotacao({{ $votacao->id }})" 
                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-check mr-2"></i>
                {{ __('Finalizar Votação') }}
            </button>
        @endif
        
        @if($votacao->pode_cancelar)
            <button onclick="cancelarVotacao({{ $votacao->id }})" 
                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-times mr-2"></i>
                {{ __('Cancelar Votação') }}
            </button>
        @endif
        
        <button onclick="excluirVotacao({{ $votacao->id }})" 
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
            <i class="fas fa-trash mr-2"></i>
            {{ __('Excluir') }}
        </button>
    </div>
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
    if (acaoAtual === 'iniciar') {
        window.location.href = `{{ route('admin.council.voting.start', $conselho) }}?votacao=${votacaoId}`;
    } else if (acaoAtual === 'finalizar') {
        window.location.href = `{{ route('admin.council.voting.finish', $conselho) }}?votacao=${votacaoId}`;
    } else if (acaoAtual === 'cancelar') {
        window.location.href = `{{ route('admin.council.voting.cancel', $conselho) }}?votacao=${votacaoId}`;
    } else if (acaoAtual === 'excluir') {
        window.location.href = `{{ route('admin.council.voting.destroy', $conselho) }}?votacao=${votacaoId}`;
    }
    
    cancelarModal();
}
</script>
@endpush
@endsection 