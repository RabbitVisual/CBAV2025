@extends('layouts.admin')

@section('title', 'Relatório da Avaliação EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Relatório: {{ $avaliacao->titulo }}</h1>
                <p class="text-gray-600">Relatório detalhado da avaliação EBD</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.ebd.avaliacoes.show', $avaliacao) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar
                </a>
                <button onclick="window.print()" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-print mr-2"></i>
                    Imprimir
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Conteúdo Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informações da Avaliação -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informações da Avaliação</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Título</label>
                        <p class="text-gray-900">{{ $avaliacao->titulo }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipo</label>
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $avaliacao->cor_tipo }}">
                            {{ $avaliacao->tipo_formatado }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Pontuação Máxima</label>
                        <p class="text-gray-900">{{ $avaliacao->pontuacao_maxima }} pontos</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                            {{ $avaliacao->obrigatoria ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                            {{ $avaliacao->obrigatoria ? 'Obrigatória' : 'Opcional' }}
                        </span>
                    </div>
                    
                    @if($avaliacao->descricao)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Descrição</label>
                            <p class="text-gray-900">{{ $avaliacao->descricao }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Aula Relacionada -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Aula Relacionada</h2>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">{{ $avaliacao->aula->licao->titulo ?? 'Aula sem Lição' }}</p>
                            <p class="text-sm text-gray-600">{{ $avaliacao->aula->turma->nome }} - {{ $avaliacao->aula->data_aula->format('d/m/Y') }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                            {{ $avaliacao->aula->status === 'realizada' ? 'bg-green-100 text-green-800' : 
                               ($avaliacao->aula->status === 'agendada' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($avaliacao->aula->status) }}
                        </span>
                    </div>
                    
                    @if($avaliacao->aula->professor)
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-chalkboard-teacher mr-2"></i>
                            <span>Professor: {{ $avaliacao->aula->professor->membro->nome ?? $avaliacao->aula->professor->nome }}</span>
                        </div>
                    @endif
                    
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-clock mr-2"></i>
                        <span>{{ $avaliacao->aula->horario_inicio->format('H:i') }} - {{ $avaliacao->aula->horario_fim->format('H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Questões -->
            @if($avaliacao->questoes->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Questões ({{ $avaliacao->questoes->count() }})</h2>
                    
                    <div class="space-y-4">
                        @foreach($avaliacao->questoes as $questao)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-medium text-gray-900">Questão {{ $loop->iteration }}</h4>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $questao->cor_tipo }}">
                                        {{ $questao->tipo_formatado }}
                                    </span>
                                </div>
                                
                                <p class="text-gray-700 mb-3">{{ $questao->pergunta }}</p>
                                
                                @if($questao->opcoes)
                                    <div class="ml-4">
                                        @php
                                            $opcoes = is_string($questao->opcoes) ? json_decode($questao->opcoes, true) : $questao->opcoes;
                                        @endphp
                                        @if(is_array($opcoes))
                                            @foreach($opcoes as $index => $opcao)
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <span class="mr-2">{{ chr(65 + (int)$index) }}.</span>
                                                    <span>{{ $opcao }}</span>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                @endif
                                
                                @if($questao->resposta_correta)
                                    <div class="mt-2 text-sm">
                                        <span class="font-medium text-green-600">Resposta correta:</span>
                                        <span class="text-gray-700">{{ $questao->resposta_correta }}</span>
                                    </div>
                                @endif
                                
                                <div class="mt-2 text-xs text-gray-500">
                                    Pontuação: {{ $questao->pontuacao }} pontos
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Estatísticas Gerais -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Estatísticas Gerais</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total de Alunos</span>
                        <span class="font-medium">{{ $totalAlunos }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Alunos que Fizeram</span>
                        <span class="font-medium">{{ $alunosQueFizeram }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Taxa de Participação</span>
                        <span class="font-medium">
                            @if($totalAlunos > 0)
                                {{ number_format(($alunosQueFizeram / $totalAlunos) * 100, 1) }}%
                            @else
                                0.0%
                            @endif
                        </span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Média Geral</span>
                        <span class="font-medium">{{ number_format($mediaGeral, 1) }}%</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Maior Nota</span>
                        <span class="font-medium">{{ $maiorNota }}/{{ $avaliacao->pontuacao_maxima }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Menor Nota</span>
                        <span class="font-medium">{{ $menorNota }}/{{ $avaliacao->pontuacao_maxima }}</span>
                    </div>
                </div>
            </div>

            <!-- Distribuição de Notas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribuição de Notas</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Excelente (≥90%)</span>
                        <div class="flex items-center">
                            <span class="font-medium mr-2">{{ $distribuicaoNotas['excelente'] }}</span>
                            <div class="w-20 bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ $alunosQueFizeram > 0 ? ($distribuicaoNotas['excelente'] / $alunosQueFizeram) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Bom (70-89%)</span>
                        <div class="flex items-center">
                            <span class="font-medium mr-2">{{ $distribuicaoNotas['bom'] }}</span>
                            <div class="w-20 bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $alunosQueFizeram > 0 ? ($distribuicaoNotas['bom'] / $alunosQueFizeram) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Regular (50-69%)</span>
                        <div class="flex items-center">
                            <span class="font-medium mr-2">{{ $distribuicaoNotas['regular'] }}</span>
                            <div class="w-20 bg-gray-200 rounded-full h-2">
                                <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $alunosQueFizeram > 0 ? ($distribuicaoNotas['regular'] / $alunosQueFizeram) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Insuficiente (<50%)</span>
                        <div class="flex items-center">
                            <span class="font-medium mr-2">{{ $distribuicaoNotas['insuficiente'] }}</span>
                            <div class="w-20 bg-gray-200 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full" style="width: {{ $alunosQueFizeram > 0 ? ($distribuicaoNotas['insuficiente'] / $alunosQueFizeram) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notas Detalhadas -->
            @if($avaliacao->notas->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Notas Detalhadas</h3>
                    
                    <div class="space-y-3 max-h-64 overflow-y-auto">
                        @foreach($avaliacao->notas->sortByDesc('created_at') as $nota)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $nota->aluno->nome }}</p>
                                    <p class="text-sm text-gray-600">{{ $nota->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                    @if($nota->percentual >= 90) bg-green-100 text-green-800
                                    @elseif($nota->percentual >= 70) bg-blue-100 text-blue-800
                                    @elseif($nota->percentual >= 50) bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $nota->nota }}/{{ $nota->pontuacao_maxima }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
@media print {
    .container {
        max-width: none;
        margin: 0;
        padding: 0;
    }
    
    .bg-white {
        background: white !important;
        box-shadow: none !important;
        border: 1px solid #e5e7eb !important;
    }
    
    .space-y-6 > * + * {
        margin-top: 1.5rem;
    }
}
</style>
@endsection 