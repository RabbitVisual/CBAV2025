@extends('layouts.member')

@section('title', 'Ministérios - Área do Membro')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            <i class="fas fa-users text-purple-600 mr-3"></i>
            Ministérios
        </h1>
        <p class="text-gray-600">Explore os ministérios disponíveis e participe das atividades da igreja.</p>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Ministérios Ativos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $ministeriosAtivos }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Solicitações Pendentes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $solicitacoesPendentes->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Participações Confirmadas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $participacoesConfirmadas }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Ministérios Disponíveis -->
    <div class="bg-white rounded-lg shadow-md mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-list text-purple-600 mr-2"></i>
                Ministérios Disponíveis
            </h3>
        </div>
        <div class="p-6">
            @if($ministeriosDisponiveis->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($ministeriosDisponiveis as $ministerio)
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-users text-purple-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-semibold text-gray-900">{{ $ministerio->nome }}</h4>
                                <p class="text-sm text-gray-600">{{ $ministerio->departamentos->first()?->nome ?? 'Sem departamento' }}</p>
                            </div>
                        </div>
                        
                        <p class="text-sm text-gray-600 mb-4">{{ Str::limit($ministerio->descricao, 120) }}</p>
                        
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-users mr-1"></i>{{ $ministerio->membros_count ?? 0 }} membros
                            </span>
                            <!-- Líder do ministério seria exibido aqui se existisse -->
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('member.ministries.request.form', $ministerio->id) }}" 
                               class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm font-medium">
                                <i class="fas fa-plus mr-2"></i>Solicitar Participação
                            </a>
                            
                            <a href="{{ route('member.ministries.show', $ministerio->id) }}" 
                               class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                Ver Detalhes <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-users text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum ministério disponível</h3>
                    <p class="text-gray-500">Não há ministérios abertos para participação no momento.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Meus Ministérios -->
    @if($meusMinisterios->count() > 0)
    <div class="bg-white rounded-lg shadow-md mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-star text-yellow-600 mr-2"></i>
                Meus Ministérios
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($meusMinisterios as $ministerio)
                <div class="border border-green-200 bg-green-50 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-star text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-gray-900">{{ $ministerio->nome }}</h4>
                            <p class="text-sm text-gray-600">{{ $ministerio->departamentos->first()?->nome ?? 'Sem departamento' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm text-gray-500">
                            <i class="fas fa-calendar mr-1"></i>Participando
                        </span>
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                            Ativo
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('member.ministries.show', $ministerio->id) }}" 
                           class="text-green-600 hover:text-green-800 text-sm font-medium">
                            Ver Detalhes <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                        <button onclick="confirmarSaida({{ $ministerio->id }})" 
                                class="text-red-600 hover:text-red-800 text-sm font-medium">
                            <i class="fas fa-sign-out-alt mr-1"></i>Sair
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Solicitações Pendentes -->
                    @if($solicitacoesPendentes->count() > 0)
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-clock text-yellow-600 mr-2"></i>
                Solicitações Pendentes
            </h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach($solicitacoesPendentes as $solicitacao)
                <div class="border border-yellow-200 bg-yellow-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-clock text-yellow-600"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-medium text-gray-900">{{ $solicitacao->ministerio->nome }}</h4>
                                <p class="text-sm text-gray-600">Solicitado em {{ $solicitacao->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">
                                Aguardando Aprovação
                            </span>
                            <button onclick="cancelarSolicitacao({{ $solicitacao->id }})" 
                                    class="text-red-600 hover:text-red-800 text-sm font-medium">
                                <i class="fas fa-times mr-1"></i>Cancelar
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modal de Confirmação -->
<div id="confirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Confirmar Ação</h3>
            <p id="confirmMessage" class="text-gray-600 mb-6"></p>
            <div class="flex justify-end space-x-3">
                <button onclick="closeConfirmModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Cancelar
                </button>
                <button id="confirmButton" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Confirmar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarSaida(ministerioId) {
    document.getElementById('confirmMessage').textContent = 'Tem certeza que deseja sair deste ministério?';
    document.getElementById('confirmButton').onclick = () => {
        window.location.href = `/member/ministries/${ministerioId}/leave`;
    };
    document.getElementById('confirmModal').classList.remove('hidden');
}

function cancelarSolicitacao(solicitacaoId) {
    document.getElementById('confirmMessage').textContent = 'Tem certeza que deseja cancelar esta solicitação?';
    document.getElementById('confirmButton').onclick = () => {
        window.location.href = `/member/ministries/request/${solicitacaoId}/cancel`;
    };
    document.getElementById('confirmModal').classList.remove('hidden');
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
}
</script>
@endsection 