@extends('layouts.admin')

@section('page-content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detalhes da Pergunta</h1>
                <p class="text-gray-600">Visualize os detalhes da pergunta do quiz bíblico</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.ebd.quiz-biblico.edit', $pergunta) }}" 
                   class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-lg hover:bg-yellow-700 transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Editar
                </a>
                <a href="{{ route('admin.ebd.quiz-biblico.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Detalhes da Pergunta -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Pergunta</h3>
                </div>
                <div class="p-6">
                    <div class="mb-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-2">{{ $pergunta->pergunta }}</h4>
                        @if($pergunta->referencia_biblica)
                            <p class="text-sm text-gray-500">Referência: {{ $pergunta->referencia_biblica }}</p>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="space-y-3">
                            <div class="flex items-center p-3 border rounded-lg {{ $pergunta->resposta_correta === 'a' ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                                <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs font-medium flex items-center justify-center mr-3">A</span>
                                <span class="text-sm">{{ $pergunta->opcao_a }}</span>
                                @if($pergunta->resposta_correta === 'a')
                                    <i class="fas fa-check-circle text-green-600 ml-auto"></i>
                                @endif
                            </div>
                            <div class="flex items-center p-3 border rounded-lg {{ $pergunta->resposta_correta === 'b' ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                                <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs font-medium flex items-center justify-center mr-3">B</span>
                                <span class="text-sm">{{ $pergunta->opcao_b }}</span>
                                @if($pergunta->resposta_correta === 'b')
                                    <i class="fas fa-check-circle text-green-600 ml-auto"></i>
                                @endif
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center p-3 border rounded-lg {{ $pergunta->resposta_correta === 'c' ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                                <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs font-medium flex items-center justify-center mr-3">C</span>
                                <span class="text-sm">{{ $pergunta->opcao_c }}</span>
                                @if($pergunta->resposta_correta === 'c')
                                    <i class="fas fa-check-circle text-green-600 ml-auto"></i>
                                @endif
                            </div>
                            <div class="flex items-center p-3 border rounded-lg {{ $pergunta->resposta_correta === 'd' ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                                <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs font-medium flex items-center justify-center mr-3">D</span>
                                <span class="text-sm">{{ $pergunta->opcao_d }}</span>
                                @if($pergunta->resposta_correta === 'd')
                                    <i class="fas fa-check-circle text-green-600 ml-auto"></i>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($pergunta->explicacao)
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Explicação</h4>
                            <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">{{ $pergunta->explicacao }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Informações e Estatísticas -->
        <div class="space-y-6">
            <!-- Informações -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informações</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500">Nível:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $pergunta->cor_nivel }}-100 text-{{ $pergunta->cor_nivel }}-800">
                            {{ $pergunta->nivel_formatado }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500">Categoria:</span>
                        <span class="text-sm text-gray-900">{{ $pergunta->categoria_formatada }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500">Pontuação:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $pergunta->pontuacao }} pts</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500">Status:</span>
                        @if($pergunta->ativo)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Ativa
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i>
                                Inativa
                            </span>
                        @endif
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500">Criada em:</span>
                        <span class="text-sm text-gray-900">{{ $pergunta->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Estatísticas</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500">Total de Respostas:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $estatisticas['total_respostas'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500">Respostas Corretas:</span>
                        <span class="text-sm font-medium text-green-600">{{ $estatisticas['respostas_corretas'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500">Respostas Incorretas:</span>
                        <span class="text-sm font-medium text-red-600">{{ $estatisticas['respostas_incorretas'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500">Percentual de Acertos:</span>
                        <span class="text-sm font-medium text-blue-600">{{ $estatisticas['percentual_acertos'] }}%</span>
                    </div>
                </div>
            </div>

            <!-- Ações -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Ações</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.ebd.quiz-biblico.edit', $pergunta) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-lg hover:bg-yellow-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Editar Pergunta
                    </a>
                    <button onclick="confirmarExclusao({{ $pergunta->id }})" 
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-trash mr-2"></i>
                        Excluir Pergunta
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação -->
@include('components.modals')

<script>
function confirmarExclusao(id) {
    if (confirm('Tem certeza que deseja excluir esta pergunta? Esta ação não pode ser desfeita.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/ebd/quiz-biblico/${id}`;
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = '{{ csrf_token() }}';
        
        form.appendChild(methodInput);
        form.appendChild(tokenInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection 