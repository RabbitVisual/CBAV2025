@extends('layouts.admin')

@section('page-content')
<div class="space-y-6">
    <!-- Cabeçalho -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Estatísticas do Quiz Bíblico</h1>
            <p class="text-gray-600">Visualize as estatísticas detalhadas do sistema de quiz</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.ebd.quiz-biblico.configuracoes') }}" 
               class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-lg hover:bg-yellow-700 transition-colors">
                <i class="fas fa-cog mr-2"></i>
                Configurações
            </a>
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-download mr-2"></i>
                    Exportar
                    <i class="fas fa-chevron-down ml-2"></i>
                </button>
                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50">
                    <div class="py-1">
                        <a href="{{ route('admin.ebd.quiz-biblico.estatisticas.exportar', ['formato' => 'excel']) }}" 
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-file-excel mr-2"></i>
                            Exportar Excel
                        </a>
                        <a href="{{ route('admin.ebd.quiz-biblico.estatisticas.exportar', ['formato' => 'pdf']) }}" 
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-file-pdf mr-2"></i>
                            Exportar PDF
                        </a>
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.ebd.quiz-biblico.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar
            </a>
        </div>
    </div>

    <!-- Estatísticas Gerais -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-question-circle text-blue-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Total de Perguntas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['total_perguntas'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Perguntas Ativas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['perguntas_ativas'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i class="fas fa-gamepad text-purple-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Total de Sessões</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['total_sessoes'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-2 bg-orange-100 rounded-lg">
                    <i class="fas fa-calendar-day text-orange-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Sessões Hoje</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['sessoes_hoje'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Estatísticas por Nível -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Nível Fácil</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-500">Perguntas:</span>
                    <span class="text-sm font-medium text-gray-900">{{ $estatisticasNivel['facil']['perguntas'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-500">Sessões:</span>
                    <span class="text-sm font-medium text-gray-900">{{ $estatisticasNivel['facil']['sessoes'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-500">Média de Acertos:</span>
                    <span class="text-sm font-medium text-green-600">{{ number_format($estatisticasNivel['facil']['media_percentual'], 1) }}%</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Nível Médio</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-500">Perguntas:</span>
                    <span class="text-sm font-medium text-gray-900">{{ $estatisticasNivel['medio']['perguntas'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-500">Sessões:</span>
                    <span class="text-sm font-medium text-gray-900">{{ $estatisticasNivel['medio']['sessoes'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-500">Média de Acertos:</span>
                    <span class="text-sm font-medium text-yellow-600">{{ number_format($estatisticasNivel['medio']['media_percentual'], 1) }}%</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Nível Difícil</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-500">Perguntas:</span>
                    <span class="text-sm font-medium text-gray-900">{{ $estatisticasNivel['dificil']['perguntas'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-500">Sessões:</span>
                    <span class="text-sm font-medium text-gray-900">{{ $estatisticasNivel['dificil']['sessoes'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-500">Média de Acertos:</span>
                    <span class="text-sm font-medium text-red-600">{{ number_format($estatisticasNivel['dificil']['media_percentual'], 1) }}%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Melhores Pontuações -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Top 10 Melhores Pontuações</h3>
        </div>
        <div class="overflow-x-auto">
            @if($melhoresPontuacoes->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Posição
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Usuário
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nível
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pontuação
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Percentual
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Data
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($melhoresPontuacoes as $index => $sessao)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $index < 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                #{{ $index + 1 }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $sessao->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $sessao->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $sessao->nivel === 'facil' ? 'bg-green-100 text-green-800' : 
                                   ($sessao->nivel === 'medio' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($sessao->nivel) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">{{ $sessao->pontuacao_total }} pts</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-green-600">{{ $sessao->percentual }}%</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $sessao->created_at->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="px-6 py-12 text-center">
                <i class="fas fa-trophy text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma pontuação registrada</h3>
                <p class="text-gray-500">Ainda não há sessões de quiz realizadas.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Últimas Sessões -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Últimas Sessões</h3>
        </div>
        <div class="overflow-x-auto">
            @if($ultimasSessoes->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Usuário
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nível
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Perguntas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acertos
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Percentual
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Data
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($ultimasSessoes as $sessao)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $sessao->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $sessao->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $sessao->nivel === 'facil' ? 'bg-green-100 text-green-800' : 
                                   ($sessao->nivel === 'medio' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($sessao->nivel) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $sessao->total_perguntas }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $sessao->acertos }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium 
                                {{ $sessao->percentual >= 80 ? 'text-green-600' : 
                                   ($sessao->percentual >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $sessao->percentual }}%
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $sessao->created_at->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="px-6 py-12 text-center">
                <i class="fas fa-history text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma sessão encontrada</h3>
                <p class="text-gray-500">Ainda não há sessões de quiz realizadas.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 