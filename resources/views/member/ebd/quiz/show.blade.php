@extends('layouts.member')
@section('title', 'Avaliação EBD')
@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $avaliacao->titulo }}</h1>
            <p class="text-gray-600">{{ $avaliacao->descricao }}</p>
            <div class="mt-4 flex items-center space-x-4 text-sm text-gray-500">
                <span><i class="fas fa-book mr-1"></i>{{ $avaliacao->aula->licao->titulo }}</span>
                <span><i class="fas fa-clock mr-1"></i>{{ $avaliacao->aula->licao->duracao_formatada }}</span>
                <span><i class="fas fa-star mr-1"></i>{{ $avaliacao->pontuacao_maxima }} pontos</span>
            </div>
        </div>

        <form method="POST" action="{{ route('member.ebd.quiz.responder', $avaliacao) }}">
            @csrf
            <div class="space-y-8">
                @foreach($questoes as $index => $questao)
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            Questão {{ $index + 1 }}
                        </h3>
                        <p class="text-gray-700">{{ $questao->pergunta }}</p>
                        @if($questao->explicacao)
                            <p class="text-sm text-gray-500 mt-2">{{ $questao->explicacao }}</p>
                        @endif
                    </div>

                    <div class="space-y-3">
                        @if($questao->e_multipla_escolha)
                            @foreach($questao->opcoes_formatadas as $opcao)
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="respostas[{{ $questao->id }}]" value="{{ $opcao['valor'] }}" required
                                       class="mr-3 text-blue-600 focus:ring-blue-500">
                                <span class="text-gray-700">{{ $opcao['texto'] }}</span>
                            </label>
                            @endforeach
                        @elseif($questao->e_verdadeiro_falso)
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="respostas[{{ $questao->id }}]" value="verdadeiro" required
                                       class="mr-3 text-blue-600 focus:ring-blue-500">
                                <span class="text-gray-700">Verdadeiro</span>
                            </label>
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="respostas[{{ $questao->id }}]" value="falso" required
                                       class="mr-3 text-blue-600 focus:ring-blue-500">
                                <span class="text-gray-700">Falso</span>
                            </label>
                        @elseif($questao->e_dissertativa)
                            <textarea name="respostas[{{ $questao->id }}]" rows="4" required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Digite sua resposta..."></textarea>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-8 bg-blue-50 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Instruções</h3>
                        <ul class="mt-2 text-sm text-gray-600 space-y-1">
                            <li>• Leia cada questão com atenção</li>
                            <li>• Você pode revisar suas respostas antes de enviar</li>
                            <li>• Após enviar, não será possível alterar as respostas</li>
                            <li>• A avaliação será corrigida automaticamente</li>
                        </ul>
                    </div>
                    <button type="submit" 
                            id="submitBtn"
                            class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed"
                            onclick="return validateAndConfirm()">
                        Enviar Avaliação
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Timer opcional para a avaliação
let timeLeft = {{ $avaliacao->aula->licao->duracao_minutos * 60 }};
const timerElement = document.createElement('div');
timerElement.className = 'fixed top-4 right-4 bg-red-600 text-white px-4 py-2 rounded-lg font-semibold';
document.body.appendChild(timerElement);

function updateTimer() {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    timerElement.textContent = `Tempo: ${minutes}:${seconds.toString().padStart(2, '0')}`;
    
    if (timeLeft <= 0) {
        alert('Tempo esgotado! A avaliação será enviada automaticamente.');
        document.querySelector('form').submit();
    } else {
        timeLeft--;
        setTimeout(updateTimer, 1000);
    }
}

// Função para validar respostas antes de enviar
function validateAndConfirm() {
    const form = document.querySelector('form');
    const formData = new FormData(form);
    const respostas = {};
    
    // Coletar todas as respostas
    for (let [key, value] of formData.entries()) {
        if (key.startsWith('respostas[')) {
            const questaoId = key.match(/\[(\d+)\]/)[1];
            respostas[questaoId] = value.trim();
        }
    }
    
    // Verificar se há pelo menos uma resposta
    const respostasPreenchidas = Object.values(respostas).filter(resposta => resposta !== '');
    
    if (respostasPreenchidas.length === 0) {
        alert('Você deve responder pelo menos uma questão antes de enviar a avaliação.');
        return false;
    }
    
    // Verificar questões obrigatórias (não dissertativas)
    const questoesObrigatorias = document.querySelectorAll('input[type="radio"][required]');
    const questoesIds = new Set();
    
    questoesObrigatorias.forEach(input => {
        const questaoId = input.name.match(/\[(\d+)\]/)[1];
        questoesIds.add(questaoId);
    });
    
    for (let questaoId of questoesIds) {
        const radios = document.querySelectorAll(`input[name="respostas[${questaoId}]"]:checked`);
        if (radios.length === 0) {
            alert('Todas as questões de múltipla escolha e verdadeiro/falso devem ser respondidas.');
            return false;
        }
    }
    
    // Confirmar envio
    return confirm('Tem certeza que deseja enviar a avaliação? Não será possível alterar as respostas.');
}

// Atualizar contador de respostas em tempo real
function updateAnswerCounter() {
    const form = document.querySelector('form');
    const formData = new FormData(form);
    const respostas = {};
    
    for (let [key, value] of formData.entries()) {
        if (key.startsWith('respostas[')) {
            const questaoId = key.match(/\[(\d+)\]/)[1];
            respostas[questaoId] = value.trim();
        }
    }
    
    const respostasPreenchidas = Object.values(respostas).filter(resposta => resposta !== '').length;
    const totalQuestoes = {{ $questoes->count() }};
    
    // Atualizar botão de envio
    const submitBtn = document.getElementById('submitBtn');
    if (respostasPreenchidas > 0) {
        submitBtn.disabled = false;
        submitBtn.textContent = `Enviar Avaliação (${respostasPreenchidas}/${totalQuestoes} respondidas)`;
    } else {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Responda pelo menos uma questão';
    }
}

// Adicionar listeners para atualizar contador
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input[type="radio"], textarea');
    inputs.forEach(input => {
        input.addEventListener('change', updateAnswerCounter);
        input.addEventListener('input', updateAnswerCounter);
    });
    
    // Atualizar contador inicial
    updateAnswerCounter();
});

// Iniciar timer
updateTimer();
</script>
@endsection