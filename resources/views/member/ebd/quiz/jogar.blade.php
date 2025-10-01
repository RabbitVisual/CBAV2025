@extends('layouts.member')

@section('title', 'Jogando Quiz Bíblico - EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-question-circle text-blue-600 mr-3"></i>
                        Quiz Bíblico
                    </h1>
                    <p class="text-gray-600">Nível: <span class="font-semibold">{{ ucfirst($sessao->nivel) }}</span></p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-600">Progresso</div>
                    <div class="text-2xl font-bold text-blue-600">{{ $progresso['atual'] }}/{{ $progresso['total'] }}</div>
                </div>
            </div>
        </div>

        <!-- Barra de Progresso -->
        <div class="mb-8">
            <div class="bg-gray-200 rounded-full h-3">
                <div class="bg-blue-600 h-3 rounded-full transition-all duration-300" style="width: {{ $progresso['percentual'] }}%"></div>
            </div>
            <div class="flex justify-between text-sm text-gray-600 mt-2">
                <span>{{ $progresso['percentual'] }}% completo</span>
                <span>{{ $sessao->pontuacao_total }} pontos</span>
            </div>
        </div>

        <!-- Pergunta -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                        @if($sessao->nivel === 'facil') bg-green-100 text-green-800
                        @elseif($sessao->nivel === 'medio') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800 @endif">
                        {{ ucfirst($sessao->nivel) }}
                    </span>
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-clock mr-1"></i>
                        <span id="tempo">00:00</span>
                    </div>
                </div>
                
                <h2 class="text-xl font-semibold text-gray-900 mb-6">
                    {{ $proximaPergunta->pergunta }}
                </h2>
            </div>

            <!-- Opções -->
            <form id="formResposta" method="POST" action="{{ route('member.ebd.quiz-biblico.responder', $sessao) }}">
                @csrf
                <input type="hidden" name="pergunta_id" value="{{ $proximaPergunta->id }}">
                <input type="hidden" name="tempo_resposta" id="tempoResposta" value="0">
                
                <div class="space-y-4">
                    <div class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-300 cursor-pointer transition-colors" onclick="selecionarResposta('a')">
                        <input type="radio" name="resposta" value="a" id="opcao_a" class="hidden">
                        <div class="w-6 h-6 border-2 border-gray-300 rounded-full mr-4 flex items-center justify-center" id="radio_a">
                            <div class="w-3 h-3 bg-blue-600 rounded-full hidden" id="dot_a"></div>
                        </div>
                        <label for="opcao_a" class="flex-1 cursor-pointer">
                            <span class="font-medium text-gray-900">A)</span>
                            <span class="ml-2 text-gray-700">{{ $proximaPergunta->opcao_a }}</span>
                        </label>
                    </div>

                    <div class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-300 cursor-pointer transition-colors" onclick="selecionarResposta('b')">
                        <input type="radio" name="resposta" value="b" id="opcao_b" class="hidden">
                        <div class="w-6 h-6 border-2 border-gray-300 rounded-full mr-4 flex items-center justify-center" id="radio_b">
                            <div class="w-3 h-3 bg-blue-600 rounded-full hidden" id="dot_b"></div>
                        </div>
                        <label for="opcao_b" class="flex-1 cursor-pointer">
                            <span class="font-medium text-gray-900">B)</span>
                            <span class="ml-2 text-gray-700">{{ $proximaPergunta->opcao_b }}</span>
                        </label>
                    </div>

                    <div class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-300 cursor-pointer transition-colors" onclick="selecionarResposta('c')">
                        <input type="radio" name="resposta" value="c" id="opcao_c" class="hidden">
                        <div class="w-6 h-6 border-2 border-gray-300 rounded-full mr-4 flex items-center justify-center" id="radio_c">
                            <div class="w-3 h-3 bg-blue-600 rounded-full hidden" id="dot_c"></div>
                        </div>
                        <label for="opcao_c" class="flex-1 cursor-pointer">
                            <span class="font-medium text-gray-900">C)</span>
                            <span class="ml-2 text-gray-700">{{ $proximaPergunta->opcao_c }}</span>
                        </label>
                    </div>

                    <div class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-300 cursor-pointer transition-colors" onclick="selecionarResposta('d')">
                        <input type="radio" name="resposta" value="d" id="opcao_d" class="hidden">
                        <div class="w-6 h-6 border-2 border-gray-300 rounded-full mr-4 flex items-center justify-center" id="radio_d">
                            <div class="w-3 h-3 bg-blue-600 rounded-full hidden" id="dot_d"></div>
                        </div>
                        <label for="opcao_d" class="flex-1 cursor-pointer">
                            <span class="font-medium text-gray-900">D)</span>
                            <span class="ml-2 text-gray-700">{{ $proximaPergunta->opcao_d }}</span>
                        </label>
                    </div>
                </div>

                <div class="mt-8 flex justify-between">
                    <button type="button" onclick="voltarQuiz()" class="px-6 py-3 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Voltar
                    </button>
                    
                    <button type="submit" id="btnResponder" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <i class="fas fa-check mr-2"></i>
                        Responder
                    </button>
                </div>
            </form>
        </div>

        <!-- Dicas -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start">
                <i class="fas fa-lightbulb text-blue-600 mt-1 mr-3"></i>
                <div>
                    <h4 class="font-medium text-blue-900 mb-2">Dicas para o Quiz</h4>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Leia a pergunta com atenção antes de responder</li>
                        <li>• Elimine as opções que você tem certeza que estão incorretas</li>
                        <li>• Use o tempo a seu favor para pensar na resposta</li>
                        <li>• Não se preocupe se errar - o importante é aprender!</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let tempoInicio = Date.now();
let tempoInterval;

// Iniciar cronômetro
function iniciarCronometro() {
    tempoInterval = setInterval(function() {
        const agora = Date.now();
        const tempoDecorrido = Math.floor((agora - tempoInicio) / 1000);
        const minutos = Math.floor(tempoDecorrido / 60);
        const segundos = tempoDecorrido % 60;
        
        document.getElementById('tempo').textContent = 
            (minutos < 10 ? '0' : '') + minutos + ':' + 
            (segundos < 10 ? '0' : '') + segundos;
    }, 1000);
}

// Selecionar resposta
function selecionarResposta(opcao) {
    // Remover seleção anterior
    document.querySelectorAll('input[name="resposta"]').forEach(input => {
        input.checked = false;
    });
    document.querySelectorAll('[id^="radio_"]').forEach(radio => {
        radio.classList.remove('border-blue-600');
        radio.classList.add('border-gray-300');
    });
    document.querySelectorAll('[id^="dot_"]').forEach(dot => {
        dot.classList.add('hidden');
    });

    // Selecionar nova opção
    document.getElementById('opcao_' + opcao).checked = true;
    document.getElementById('radio_' + opcao).classList.remove('border-gray-300');
    document.getElementById('radio_' + opcao).classList.add('border-blue-600');
    document.getElementById('dot_' + opcao).classList.remove('hidden');

    // Habilitar botão de responder
    document.getElementById('btnResponder').disabled = false;
}

// Voltar ao quiz
function voltarQuiz() {
    if (confirm('Tem certeza que deseja sair? Seu progresso será perdido.')) {
        window.location.href = '{{ route("member.ebd.quiz-biblico.index") }}';
    }
}

// Atualizar tempo de resposta antes de enviar
document.getElementById('formResposta').addEventListener('submit', function() {
    const agora = Date.now();
    const tempoResposta = Math.floor((agora - tempoInicio) / 1000);
    document.getElementById('tempoResposta').value = tempoResposta;
});

// Iniciar cronômetro quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
    iniciarCronometro();
});

// Parar cronômetro quando sair da página
window.addEventListener('beforeunload', function() {
    if (tempoInterval) {
        clearInterval(tempoInterval);
    }
});
</script>
@endpush 