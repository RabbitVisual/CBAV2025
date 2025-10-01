@extends('layouts.member')

@section('title', 'Detalhes da Lição EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $licao->titulo }}</h1>
                <p class="text-gray-600">{{ $licao->descricao }}</p>
            </div>
            <span class="px-4 py-2 text-sm font-medium rounded-full 
                {{ $licao->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $licao->ativo ? 'Ativa' : 'Inativa' }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Conteúdo Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informações Básicas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informações da Lição</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Duração</label>
                        <p class="text-gray-900">{{ $licao->duracao_formatada }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Dificuldade</label>
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $licao->cor_dificuldade }}">
                            {{ $licao->dificuldade_formatada }}
                        </span>
                    </div>
                    
                    @if($licao->versiculo_chave)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Versículo Chave</label>
                            <p class="text-gray-900 italic">{{ $licao->versiculo_chave }}</p>
                        </div>
                    @endif
                    
                    @if($licao->objetivos)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Objetivos</label>
                            <p class="text-gray-900">{{ $licao->objetivos }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Conteúdo da Lição -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Conteúdo da Lição</h2>
                
                <div class="prose max-w-none">
                    {!! nl2br(e($licao->conteudo)) !!}
                </div>
            </div>

            <!-- Aplicação Prática -->
            @if($licao->aplicacao_pratica)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Aplicação Prática</h2>
                    
                    <div class="prose max-w-none">
                        {!! nl2br(e($licao->aplicacao_pratica)) !!}
                    </div>
                </div>
            @endif

            <!-- Oração -->
            @if($licao->oracao)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Oração</h2>
                    
                    <div class="prose max-w-none">
                        {!! nl2br(e($licao->oracao)) !!}
                    </div>
                </div>
            @endif

            <!-- Material Necessário -->
            @if($licao->material_necessario)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Material Necessário</h2>
                    
                    <div class="prose max-w-none">
                        {!! nl2br(e($licao->material_necessario)) !!}
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
                        <span class="text-gray-600">Total de Aulas</span>
                        <span class="font-medium">{{ $licao->total_aulas }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Aulas Agendadas</span>
                        <span class="font-medium">{{ $licao->aulas_agendadas()->count() }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Aulas Realizadas</span>
                        <span class="font-medium">{{ $licao->aulas_realizadas()->count() }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total de Avaliações</span>
                        <span class="font-medium">{{ $licao->total_avaliacoes }}</span>
                    </div>
                </div>
            </div>

            <!-- Aulas Relacionadas -->
            @if($licao->aulas->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Aulas Relacionadas</h3>
                    
                    <div class="space-y-3">
                        @foreach($licao->aulas->take(5) as $aula)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $aula->turma->nome }}</p>
                                    <p class="text-sm text-gray-600">{{ $aula->data_aula->format('d/m/Y H:i') }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                    {{ $aula->status === 'realizada' ? 'bg-green-100 text-green-800' : 
                                       ($aula->status === 'agendada' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($aula->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Avaliações -->
            @if($licao->avaliacoes->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Avaliações</h3>
                    
                    <div class="space-y-3">
                        @foreach($licao->avaliacoes->take(5) as $avaliacao)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $avaliacao->titulo }}</p>
                                    <p class="text-sm text-gray-600">{{ ucfirst($avaliacao->tipo_formatado) }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                    {{ $avaliacao->cor_tipo }}">
                                    {{ $avaliacao->pontuacao_maxima }} pts
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
                    <a href="{{ route('member.ebd.aulas.index') }}" 
                       class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Ver Aulas
                    </a>
                    
                    <a href="{{ route('member.ebd.quiz.show', $licao->avaliacoes->first()) }}" 
                       class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                        <i class="fas fa-clipboard-check mr-2"></i>
                        Ver Avaliações
                    </a>
                    
                    <a href="{{ route('member.ebd.licoes.index') }}" 
                       class="w-full flex items-center justify-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Voltar às Lições
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 