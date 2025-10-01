@extends('layouts.admin')

@section('title', 'Detalhes da Avaliação EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $avaliacao->titulo }}</h1>
                <p class="text-gray-600">Detalhes da avaliação EBD</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.ebd.avaliacoes.edit', $avaliacao) }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Editar
                </a>
                <a href="{{ route('admin.ebd.avaliacoes.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar
                </a>
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
            @else
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Questões</h2>
                    
                    <div class="text-center py-8">
                        <i class="fas fa-question-circle text-4xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-semibold text-gray-600 mb-2">Nenhuma Questão</h3>
                        <p class="text-gray-500">Esta avaliação ainda não possui questões.</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Estatísticas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Estatísticas</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total de Questões</span>
                        <span class="font-medium">{{ $avaliacao->questoes->count() }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Alunos que Fizeram</span>
                        <span class="font-medium">{{ $avaliacao->notas->count() }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Média Geral</span>
                        <span class="font-medium">
                            @if($avaliacao->notas->count() > 0)
                                {{ number_format($avaliacao->notas->avg('percentual'), 1) }}%
                            @else
                                0.0%
                            @endif
                        </span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Foi Aplicada</span>
                        <span class="font-medium">{{ $avaliacao->notas->count() > 0 ? 'Sim' : 'Não' }}</span>
                    </div>
                </div>
            </div>

            <!-- Notas -->
            @if($avaliacao->notas->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Últimas Notas</h3>
                    
                    <div class="space-y-3">
                        @foreach($avaliacao->notas->take(5) as $nota)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $nota->aluno->nome }}</p>
                                    <p class="text-sm text-gray-600">{{ $nota->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                    @if($nota->percentual >= 70) bg-green-100 text-green-800
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

            <!-- Ações -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ações</h3>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.ebd.avaliacoes.edit', $avaliacao) }}" 
                       class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Editar Avaliação
                    </a>
                    
                    <a href="{{ route('admin.ebd.questoes.create', ['avaliacao_id' => $avaliacao->id]) }}" 
                       class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Adicionar Questões
                    </a>
                    
                    <a href="{{ route('admin.ebd.avaliacoes.relatorio', $avaliacao) }}" 
                       class="w-full flex items-center justify-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700 transition-colors">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Ver Relatórios
                    </a>
                    
                    <form action="{{ route('admin.ebd.avaliacoes.destroy', $avaliacao) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition-colors"
                                onclick="return confirm('Tem certeza que deseja excluir esta avaliação?')">
                            <i class="fas fa-trash mr-2"></i>
                            Excluir Avaliação
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 