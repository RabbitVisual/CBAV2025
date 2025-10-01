@extends('layouts.member')

@section('title', 'Quiz Bíblico - EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                <i class="fas fa-question-circle text-blue-600 mr-3"></i>
                Quiz Bíblico
            </h1>
            <p class="text-gray-600">Teste seus conhecimentos bíblicos e aprenda mais sobre a Palavra de Deus!</p>
            
            <!-- Informações do Sistema -->
            <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    <div class="text-sm text-blue-800">
                        <strong>Configurações do Sistema:</strong> 
                        Tempo limite: {{ config('quiz.tempo_limite', 30) }}s por pergunta • 
                        Pontuação: Fácil {{ config('quiz.pontuacao.facil', 10) }}pts • 
                        Médio {{ config('quiz.pontuacao.medio', 20) }}pts • 
                        Difícil {{ config('quiz.pontuacao.dificil', 30) }}pts
                    </div>
                </div>
            </div>
        </div>

        <!-- Estatísticas do Usuário -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-gamepad text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total de Sessões</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['total_sessoes'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-trophy text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Melhor Pontuação</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['melhor_pontuacao'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-percentage text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Melhor Percentual</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($estatisticas['melhor_percentual'], 1) }}%</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total de Acertos</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['total_acertos'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Perguntas Disponíveis -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Nível Fácil -->
            <div class="bg-white rounded-lg shadow-md p-6 border border-green-200">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-seedling text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Nível Fácil</h3>
                        <p class="text-sm text-gray-600">{{ $perguntasFacil }} perguntas disponíveis</p>
                    </div>
                </div>
                <p class="text-gray-600 mb-4">Perguntas básicas sobre a Bíblia, ideais para iniciantes.</p>
                <button onclick="iniciarQuiz('facil')" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                    <i class="fas fa-play mr-2"></i>
                    Começar Quiz Fácil
                </button>
            </div>

            <!-- Nível Médio -->
            <div class="bg-white rounded-lg shadow-md p-6 border border-yellow-200">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-fire text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Nível Médio</h3>
                        <p class="text-sm text-gray-600">{{ $perguntasMedio }} perguntas disponíveis</p>
                    </div>
                </div>
                <p class="text-gray-600 mb-4">Perguntas intermediárias para quem já tem conhecimento bíblico.</p>
                <button onclick="iniciarQuiz('medio')" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                    <i class="fas fa-play mr-2"></i>
                    Começar Quiz Médio
                </button>
            </div>

            <!-- Nível Difícil -->
            <div class="bg-white rounded-lg shadow-md p-6 border border-red-200">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-crown text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Nível Difícil</h3>
                        <p class="text-sm text-gray-600">{{ $perguntasDificil }} perguntas disponíveis</p>
                    </div>
                </div>
                <p class="text-gray-600 mb-4">Perguntas avançadas para especialistas em Bíblia.</p>
                <button onclick="iniciarQuiz('dificil')" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                    <i class="fas fa-play mr-2"></i>
                    Começar Quiz Difícil
                </button>
            </div>
        </div>

        <!-- Últimas Sessões -->
        @if($ultimasSessoes->count() > 0)
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-history text-blue-600 mr-2"></i>
                Últimas Sessões
            </h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nível</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perguntas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acertos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentual</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pontuação</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($ultimasSessoes as $sessao)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $sessao->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($sessao->nivel === 'facil') bg-green-100 text-green-800
                                    @elseif($sessao->nivel === 'medio') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($sessao->nivel) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $sessao->total_perguntas }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $sessao->acertos }}/{{ $sessao->total_perguntas }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($sessao->percentual >= 80) bg-green-100 text-green-800
                                    @elseif($sessao->percentual >= 60) bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ number_format($sessao->percentual, 1) }}%
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $sessao->pontuacao_total }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Links de Navegação -->
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('member.ebd.quiz-biblico.historico') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition duration-200">
                <i class="fas fa-history mr-2"></i>
                Ver Histórico Completo
            </a>
            <a href="{{ route('member.ebd.dashboard') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar ao Dashboard EBD
            </a>
        </div>
    </div>
</div>

<!-- Modal de Configuração -->
<div id="modalConfiguracao" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Configurar Quiz</h3>
            <form id="formQuiz" method="POST" action="{{ route('member.ebd.quiz-biblico.iniciar') }}">
                @csrf
                <input type="hidden" id="nivelQuiz" name="nivel" value="">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Categoria (Opcional)</label>
                    <select name="categoria" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todas as categorias</option>
                        <option value="antigo_testamento">Antigo Testamento</option>
                        <option value="novo_testamento">Novo Testamento</option>
                        <option value="personagens">Personagens Bíblicos</option>
                        <option value="milagres">Milagres</option>
                        <option value="parabolas">Parábolas</option>
                        <option value="profetas">Profetas</option>
                        <option value="apostolos">Apóstolos</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantidade de Perguntas</label>
                    <select name="quantidade" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="5">5 perguntas</option>
                        <option value="10" selected>{{ config('quiz.perguntas_por_sessao', 10) }} perguntas (padrão)</option>
                        <option value="15">15 perguntas</option>
                        <option value="20">20 perguntas</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="fecharModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition duration-200">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition duration-200">
                        <i class="fas fa-play mr-2"></i>
                        Iniciar Quiz
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function iniciarQuiz(nivel) {
    document.getElementById('nivelQuiz').value = nivel;
    document.getElementById('modalConfiguracao').classList.remove('hidden');
}

function fecharModal() {
    document.getElementById('modalConfiguracao').classList.add('hidden');
}

// Fechar modal ao clicar fora
document.getElementById('modalConfiguracao').addEventListener('click', function(e) {
    if (e.target === this) {
        fecharModal();
    }
});
</script>
@endpush 