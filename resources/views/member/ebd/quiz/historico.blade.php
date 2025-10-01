@extends('layouts.member')

@section('title', 'Histórico do Quiz Bíblico - EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                <i class="fas fa-history text-blue-600 mr-3"></i>
                Histórico do Quiz Bíblico
            </h1>
            <p class="text-gray-600">Acompanhe seu progresso e evolução nos quizzes bíblicos.</p>
        </div>

        <!-- Estatísticas Gerais -->
        @if($sessoes->count() > 0)
        @php
            $totalSessoes = $sessoes->total();
            $melhorPontuacao = $sessoes->max('pontuacao_total');
            $melhorPercentual = $sessoes->max('percentual');
            $totalAcertos = $sessoes->sum('acertos');
            $totalPerguntas = $sessoes->sum('total_perguntas');
            $mediaPercentual = $sessoes->avg('percentual');
        @endphp
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-gamepad text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total de Sessões</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalSessoes }}</p>
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
                        <p class="text-2xl font-bold text-gray-900">{{ $melhorPontuacao }}</p>
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
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($melhorPercentual, 1) }}%</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-chart-line text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Média Geral</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($mediaPercentual, 1) }}%</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Lista de Sessões -->
        @if($sessoes->count() > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-list-ul text-blue-600 mr-2"></i>
                    Todas as Sessões
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nível</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perguntas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acertos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentual</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pontuação</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duração</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Conceito</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($sessoes as $sessao)
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
                                {{ $sessao->categoria ? ucfirst(str_replace('_', ' ', $sessao->categoria)) : 'Geral' }}
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $sessao->duracao_formatada }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($sessao->conceito === 'Excelente') bg-green-100 text-green-800
                                    @elseif($sessao->conceito === 'Muito Bom') bg-blue-100 text-blue-800
                                    @elseif($sessao->conceito === 'Bom') bg-yellow-100 text-yellow-800
                                    @elseif($sessao->conceito === 'Regular') bg-orange-100 text-orange-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $sessao->conceito }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            @if($sessoes->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $sessoes->links() }}
            </div>
            @endif
        </div>
        @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 mb-4">
                <i class="fas fa-history text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma sessão encontrada</h3>
            <p class="text-gray-600 mb-6">Você ainda não participou de nenhum quiz bíblico. Que tal começar agora?</p>
            <a href="{{ route('member.ebd.quiz-biblico.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition duration-200">
                <i class="fas fa-play mr-2"></i>
                Começar Quiz
            </a>
        </div>
        @endif

        <!-- Gráficos de Progresso -->
        @if($sessoes->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
            <!-- Progresso por Nível -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
                    Progresso por Nível
                </h3>
                <div class="space-y-4">
                    @php
                        $niveis = ['facil', 'medio', 'dificil'];
                        $cores = ['green', 'yellow', 'red'];
                        $icones = ['seedling', 'fire', 'crown'];
                    @endphp
                    
                    @foreach($niveis as $index => $nivel)
                    @php
                        $sessoesNivel = $sessoes->where('nivel', $nivel);
                        $totalNivel = $sessoesNivel->count();
                        $mediaNivel = $sessoesNivel->avg('percentual') ?? 0;
                    @endphp
                    
                    @if($totalNivel > 0)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-{{ $cores[$index] }}-100 flex items-center justify-center mr-3">
                                <i class="fas fa-{{ $icones[$index] }} text-{{ $cores[$index] }}-600 text-sm"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">{{ ucfirst($nivel) }}</div>
                                <div class="text-sm text-gray-600">{{ $totalNivel }} sessões</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-semibold text-gray-900">{{ number_format($mediaNivel, 1) }}%</div>
                            <div class="text-sm text-gray-600">média</div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>

            <!-- Melhores Resultados -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-trophy text-yellow-600 mr-2"></i>
                    Melhores Resultados
                </h3>
                <div class="space-y-4">
                    @php
                        $melhoresSessoes = $sessoes->sortByDesc('percentual')->take(3);
                    @endphp
                    
                    @foreach($melhoresSessoes as $index => $sessao)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center mr-3">
                                <span class="text-yellow-600 font-bold text-sm">{{ $index + 1 }}</span>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">{{ ucfirst($sessao->nivel) }}</div>
                                <div class="text-sm text-gray-600">{{ $sessao->created_at->format('d/m/Y') }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-semibold text-gray-900">{{ number_format($sessao->percentual, 1) }}%</div>
                            <div class="text-sm text-gray-600">{{ $sessao->acertos }}/{{ $sessao->total_perguntas }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Links de Navegação -->
        <div class="flex flex-col sm:flex-row gap-4 mt-8">
            <a href="{{ route('member.ebd.quiz-biblico.index') }}" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                <i class="fas fa-play mr-2"></i>
                Novo Quiz
            </a>
            <a href="{{ route('member.ebd.dashboard') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar ao Dashboard
            </a>
        </div>
    </div>
</div>

@endsection 