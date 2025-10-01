@extends('layouts.member')

@section('title', $ministerio->nome)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Cabeçalho -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('member.ministries.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>{{ __('Voltar') }}
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $ministerio->nome }}</h1>
                        <p class="text-gray-600 mt-2">{{ __('Detalhes do ministério e oportunidades de participação') }}</p>
                    </div>
                </div>
                <div class="flex space-x-4">
                    @if($membroParticipa)
                        <span class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check mr-2"></i>{{ __('Você participa') }}
                        </span>
                    @else
                        <button onclick="solicitarParticipacao()" class="btn btn-primary">
                            <i class="fas fa-hand-paper mr-2"></i>{{ __('Solicitar Participação') }}
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Alertas -->
        @if(session('success'))
            <div class="alert alert-success mb-6">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informações Principais -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Descrição -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-3 text-blue-600"></i>
                        {{ __('Sobre o Ministério') }}
                    </h2>
                    <div class="prose max-w-none">
                        @if($ministerio->descricao)
                            <p class="text-gray-700 leading-relaxed">{{ $ministerio->descricao }}</p>
                        @else
                            <p class="text-gray-500 italic">{{ __('Nenhuma descrição disponível.') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Departamentos -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-sitemap mr-3 text-blue-600"></i>
                        {{ __('Departamentos') }}
                    </h2>
                    @if($ministerio->departamentos->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($ministerio->departamentos as $departamento)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <h3 class="font-semibold text-gray-900 mb-2">{{ $departamento->nome }}</h3>
                                    @if($departamento->descricao)
                                        <p class="text-sm text-gray-600 mb-3">{{ $departamento->descricao }}</p>
                                    @endif
                                    
                                    <!-- Cargos -->
                                    @if($departamento->cargos->count() > 0)
                                        <div class="space-y-2">
                                            <h4 class="text-sm font-medium text-gray-700">{{ __('Cargos Disponíveis:') }}</h4>
                                            @foreach($departamento->cargos as $cargo)
                                                <div class="flex items-center justify-between">
                                                    <span class="text-sm text-gray-600">{{ $cargo->nome }}</span>
                                                    <span class="text-xs text-gray-500">{{ $cargo->membros->count() }} membros</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic">{{ __('Nenhum departamento cadastrado.') }}</p>
                    @endif
                </div>

                <!-- Atividades Recentes -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-calendar-alt mr-3 text-blue-600"></i>
                        {{ __('Atividades Recentes') }}
                    </h2>
                    @if($atividades->count() > 0)
                        <div class="space-y-4">
                            @foreach($atividades as $atividade)
                                <div class="border-l-4 border-blue-500 pl-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-medium text-gray-900">{{ $atividade['titulo'] }}</h3>
                                            <p class="text-sm text-gray-600">{{ $atividade['descricao'] }}</p>
                                        </div>
                                        <span class="text-sm text-gray-500">{{ $atividade['data']->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic">{{ __('Nenhuma atividade recente.') }}</p>
                    @endif
                </div>

                <!-- Solicitações Pendentes -->
                @if($solicitacoesPendentes->count() > 0)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-clock mr-3 text-yellow-600"></i>
                            {{ __('Suas Solicitações Pendentes') }}
                        </h2>
                        <div class="space-y-4">
                            @foreach($solicitacoesPendentes as $solicitacao)
                                <div class="border border-yellow-200 rounded-lg p-4 bg-yellow-50">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-medium text-gray-900">{{ $solicitacao->cargo->nome }}</h3>
                                            <p class="text-sm text-gray-600">{{ $solicitacao->cargo->departamento->nome ?? 'Sem departamento' }}</p>
                                            <p class="text-xs text-gray-500">{{ __('Solicitado em:') }} {{ $solicitacao->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                        <button onclick="cancelarSolicitacao({{ $solicitacao->id }})" 
                                                class="btn btn-sm btn-danger">
                                            <i class="fas fa-times mr-1"></i>{{ __('Cancelar') }}
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Estatísticas -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Estatísticas') }}</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ __('Total de Membros') }}</span>
                            <span class="font-semibold text-gray-900">{{ $membrosParticipantes->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ __('Departamentos') }}</span>
                            <span class="font-semibold text-gray-900">{{ $ministerio->departamentos->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ __('Cargos') }}</span>
                            <span class="font-semibold text-gray-900">{{ $totalCargos }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ __('Atividades Este Mês') }}</span>
                            <span class="font-semibold text-gray-900">{{ $atividadesMes }}</span>
                        </div>
                    </div>
                </div>

                <!-- Líderes -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Liderança') }}</h2>
                    <p class="text-gray-500 italic">{{ __('Informações de liderança não disponíveis.') }}</p>
                </div>

                <!-- Informações de Contato -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Informações de Contato') }}</h2>
                    <p class="text-gray-500 italic">{{ __('Informações de contato não disponíveis.') }}</p>
                </div>

                <!-- Status do Ministério -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Status') }}</h2>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ __('Status') }}</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($ministerio->ativo) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                @if($ministerio->ativo)
                                    <i class="fas fa-check mr-1"></i>{{ __('Ativo') }}
                                @else
                                    <i class="fas fa-times mr-1"></i>{{ __('Inativo') }}
                                @endif
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ __('Criado em') }}</span>
                            <span class="text-sm text-gray-900">{{ $ministerio->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Solicitação -->
        <div id="solicitacaoModal" class="modal hidden" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="text-lg font-semibold">{{ __('Solicitar Participação') }}</h3>
                    <button onclick="fecharModal()" class="modal-close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="solicitacaoForm" method="POST" action="{{ route('member.ministries.request', $ministerio) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-4">
                            <label for="cargo_id" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Cargo Desejado') }} <span class="text-red-500">*</span>
                            </label>
                            <select id="cargo_id" name="cargo_id" class="form-select w-full" required>
                                <option value="">{{ __('Selecione um cargo') }}</option>
                                @foreach($departamentos as $departamento)
                                    @foreach($departamento->cargos as $cargo)
                                        <option value="{{ $cargo->id }}">
                                            {{ $cargo->nome }} - {{ $departamento->nome }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="motivo" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Motivo da Solicitação') }}
                            </label>
                            <textarea id="motivo" 
                                      name="motivo" 
                                      rows="4" 
                                      class="form-textarea w-full"
                                      placeholder="{{ __('Descreva por que você gostaria de participar deste ministério...') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="fecharModal()" class="btn btn-secondary">
                            {{ __('Cancelar') }}
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane mr-2"></i>{{ __('Enviar Solicitação') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript inline como fallback -->
<script>
// Definir funções globalmente
window.solicitarParticipacao = function() {
    const modal = document.getElementById('solicitacaoModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
    }
};

window.fecharModal = function() {
    const modal = document.getElementById('solicitacaoModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }
};

window.cancelarSolicitacao = function(solicitacaoId) {
    if (confirm('{{ __("Tem certeza que deseja cancelar esta solicitação?") }}')) {
        fetch(`/member/ministries/request/${solicitacaoId}/cancel`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || '{{ __("Erro ao cancelar solicitação") }}');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('{{ __("Erro ao cancelar solicitação") }}');
        });
    }
};

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    // Fechar modal ao clicar fora
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('solicitacaoModal');
        if (event.target === modal) {
            fecharModal();
        }
    });

    // Fechar modal com ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            fecharModal();
        }
    });
});
</script>

@push('scripts')
<style>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content {
    background: white;
    border-radius: 8px;
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.25rem;
    cursor: pointer;
    color: #6b7280;
    padding: 0.25rem;
    border-radius: 4px;
    transition: color 0.2s;
}

.modal-close:hover {
    color: #374151;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    padding: 1.5rem;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
}

.hidden {
    display: none !important;
}

.form-select {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    line-height: 1.25rem;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.form-select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-textarea {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    line-height: 1.25rem;
    resize: vertical;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.form-textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.btn {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
    border: 1px solid transparent;
    cursor: pointer;
}

.btn-primary {
    background-color: #3b82f6;
    color: white;
    border-color: #3b82f6;
}

.btn-primary:hover {
    background-color: #2563eb;
    border-color: #2563eb;
}

.btn-secondary {
    background-color: #6b7280;
    color: white;
    border-color: #6b7280;
}

.btn-secondary:hover {
    background-color: #4b5563;
    border-color: #4b5563;
}

.btn-danger {
    background-color: #ef4444;
    color: white;
    border-color: #ef4444;
}

.btn-danger:hover {
    background-color: #dc2626;
    border-color: #dc2626;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}
</style>
@endpush
@endsection 