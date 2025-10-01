@extends('layouts.member')

@section('title', 'Resultado do Quiz Bíblico - EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                <i class="fas fa-trophy text-yellow-600 mr-3"></i>
                Resultado do Quiz
            </h1>
            <p class="text-gray-600">Parabéns por completar o quiz bíblico!</p>
        </div>

        <!-- Resumo do Resultado -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="text-center">
                    <div class="text-4xl font-bold text-blue-600 mb-2">{{ $sessao->acertos }}/{{ $sessao->total_perguntas }}</div>
                    <div class="text-sm text-gray-600">Acertos</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-green-600 mb-2">{{ number_format($sessao->percentual, 1) }}%</div>
                    <div class="text-sm text-gray-600">Percentual</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-purple-600 mb-2">{{ $sessao->pontuacao_total }}</div>
                    <div class="text-sm text-gray-600">Pontuação</div>
                </div>
            </div>

            <!-- Conceito -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center px-6 py-3 rounded-full text-lg font-semibold
                    @if($sessao->conceito === 'Excelente') bg-green-100 text-green-800
                    @elseif($sessao->conceito === 'Muito Bom') bg-blue-100 text-blue-800
                    @elseif($sessao->conceito === 'Bom') bg-yellow-100 text-yellow-800
                    @elseif($sessao->conceito === 'Regular') bg-orange-100 text-orange-800
                    @else bg-red-100 text-red-800 @endif">
                    <i class="fas fa-medal mr-2"></i>
                    {{ $sessao->conceito }}
                </div>
            </div>

            <!-- Estatísticas da Sessão -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 mb-3">Informações da Sessão</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Nível:</span>
                            <span class="font-medium">{{ ucfirst($sessao->nivel) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Duração:</span>
                            <span class="font-medium">{{ $sessao->duracao_formatada }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Data:</span>
                            <span class="font-medium">{{ $sessao->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 mb-3">Performance</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Taxa de Acerto:</span>
                            <span class="font-medium">{{ number_format(($sessao->acertos / $sessao->total_perguntas) * 100, 1) }}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Pontos por Pergunta:</span>
                            <span class="font-medium">{{ number_format($sessao->pontuacao_total / $sessao->total_perguntas, 1) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tempo Médio:</span>
                            <span class="font-medium">{{ $sessao->duracao > 0 ? number_format($sessao->duracao / $sessao->total_perguntas, 1) . 's' : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Respostas Detalhadas -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">
                <i class="fas fa-list-ul text-blue-600 mr-2"></i>
                Respostas Detalhadas
            </h2>

            <div class="space-y-6">
                @foreach($respostas as $index => $resposta)
                <div class="border border-gray-200 rounded-lg p-6 {{ $resposta->correta ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sm font-semibold
                                {{ $resposta->correta ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $index + 1 }}
                            </span>
                            <span class="ml-3 text-sm font-medium text-gray-600">
                                {{ $resposta->correta ? 'Correta' : 'Incorreta' }}
                            </span>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-600">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $resposta->tempo_formatado }}
                            </div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ $resposta->pontuacao_obtida }} pts
                            </div>
                        </div>
                    </div>

                    <h3 class="font-medium text-gray-900 mb-3">{{ $resposta->pergunta->pergunta }}</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="space-y-2">
                            <div class="text-sm font-medium text-gray-700">Opções:</div>
                            <div class="space-y-1 text-sm">
                                <div class="flex items-center">
                                    <span class="w-4 h-4 rounded-full mr-2 {{ $resposta->pergunta->resposta_correta === 'a' ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                                    <span class="{{ $resposta->pergunta->resposta_correta === 'a' ? 'font-semibold text-green-700' : '' }}">A) {{ $resposta->pergunta->opcao_a }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-4 h-4 rounded-full mr-2 {{ $resposta->pergunta->resposta_correta === 'b' ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                                    <span class="{{ $resposta->pergunta->resposta_correta === 'b' ? 'font-semibold text-green-700' : '' }}">B) {{ $resposta->pergunta->opcao_b }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-4 h-4 rounded-full mr-2 {{ $resposta->pergunta->resposta_correta === 'c' ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                                    <span class="{{ $resposta->pergunta->resposta_correta === 'c' ? 'font-semibold text-green-700' : '' }}">C) {{ $resposta->pergunta->opcao_c }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-4 h-4 rounded-full mr-2 {{ $resposta->pergunta->resposta_correta === 'd' ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                                    <span class="{{ $resposta->pergunta->resposta_correta === 'd' ? 'font-semibold text-green-700' : '' }}">D) {{ $resposta->pergunta->opcao_d }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-700 mb-2">Sua Resposta:</div>
                            <div class="text-sm">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    {{ $resposta->correta ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ strtoupper($resposta->resposta_dada) }}
                                </span>
                            </div>

                            @if($resposta->pergunta->explicacao)
                            <div class="mt-3">
                                <div class="text-sm font-medium text-gray-700 mb-1">Explicação:</div>
                                <div class="text-sm text-gray-600 bg-white p-3 rounded border">
                                    {{ $resposta->pergunta->explicacao }}
                                </div>
                            </div>
                            @endif

                            @if($resposta->pergunta->referencia_biblica)
                            <div class="mt-3">
                                <div class="text-sm font-medium text-gray-700 mb-1">Referência Bíblica:</div>
                                <div class="text-sm text-blue-600 font-medium">
                                    {{ $resposta->pergunta->referencia_biblica }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Ações -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('member.ebd.quiz-biblico.index') }}" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                <i class="fas fa-play mr-2"></i>
                Novo Quiz
            </a>
            <a href="{{ route('member.ebd.quiz-biblico.historico') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                <i class="fas fa-history mr-2"></i>
                Ver Histórico
            </a>
            <a href="{{ route('member.ebd.dashboard') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar ao Dashboard
            </a>
        </div>
    </div>
</div>

<!-- Modal de Conclusão -->
@if($sessao->percentual >= 80)
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="modalConclusao">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                <i class="fas fa-trophy text-green-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Parabéns!</h3>
            <p class="text-sm text-gray-600 mb-4">
                Você obteve um excelente resultado de {{ number_format($sessao->percentual, 1) }}%!
                Continue estudando a Palavra de Deus para crescer ainda mais no conhecimento.
            </p>
            <button onclick="fecharModal()" class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-200">
                Continuar
            </button>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
function fecharModal() {
    document.getElementById('modalConclusao').style.display = 'none';
}

// Mostrar modal de conclusão após 1 segundo
setTimeout(function() {
    const modal = document.getElementById('modalConclusao');
    if (modal) {
        modal.style.display = 'block';
    }
}, 1000);
</script>
@endpush 