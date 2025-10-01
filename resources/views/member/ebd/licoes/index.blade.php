@extends('layouts.member')

@section('title', 'Lições EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Lições da Escola Bíblica Dominical</h1>
        <p class="text-gray-600">Explore as lições disponíveis para estudo e crescimento espiritual</p>
    </div>

    @if($licoes->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($licoes as $licao)
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-gray-900">{{ $licao->titulo }}</h3>
                        <span class="px-3 py-1 text-sm font-medium rounded-full 
                            {{ $licao->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $licao->ativo ? 'Ativa' : 'Inativa' }}
                        </span>
                    </div>

                    <div class="space-y-3">
                        @if($licao->descricao)
                            <p class="text-gray-600 text-sm">{{ Str::limit($licao->descricao, 120) }}</p>
                        @endif

                        @if($licao->versiculo_chave)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-book-bible mr-2"></i>
                                <span class="italic">{{ $licao->versiculo_chave }}</span>
                            </div>
                        @endif

                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-clock mr-2"></i>
                            <span>{{ $licao->duracao_formatada }}</span>
                        </div>

                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-signal mr-2"></i>
                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                {{ $licao->cor_dificuldade }}">
                                {{ $licao->dificuldade_formatada }}
                            </span>
                        </div>

                        @if($licao->objetivos)
                            <div class="text-sm text-gray-600">
                                <strong>Objetivos:</strong> {{ Str::limit($licao->objetivos, 80) }}
                            </div>
                        @endif

                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <span>{{ $licao->total_aulas }} aulas</span>
                            <span>{{ $licao->total_avaliacoes }} avaliações</span>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('member.ebd.licoes.show', $licao) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-eye mr-2"></i>
                            Ver Detalhes
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-book-open text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Nenhuma Lição Disponível</h3>
            <p class="text-gray-500">Não há lições EBD disponíveis no momento.</p>
        </div>
    @endif

    <!-- Filtros -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtros</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dificuldade</label>
                <select class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todas</option>
                    <option value="facil">Fácil</option>
                    <option value="medio">Médio</option>
                    <option value="dificil">Difícil</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Duração</label>
                <select class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Qualquer</option>
                    <option value="15-30">15-30 minutos</option>
                    <option value="30-60">30-60 minutos</option>
                    <option value="60+">60+ minutos</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="ativo">Ativas</option>
                    <option value="inativo">Inativas</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-book-open text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Lições</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $licoes->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Lições Ativas</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $licoes->where('ativo', true)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Duração Média</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($licoes->avg('duracao_minutos'), 0) }} min</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-graduation-cap text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Avaliações</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $licoes->sum('total_avaliacoes') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 