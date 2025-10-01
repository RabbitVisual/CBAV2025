@extends('layouts.member')

@section('title', 'EBD - Meu Progresso')

@section('content')
<div class="space-y-8">
    {{-- Cabeçalho --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 md:p-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Escola Bíblica Digital</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Seu caminho de crescimento na Palavra de Deus.</p>
    </div>

    {{-- Seção de Disciplinas em Andamento --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white">Minhas Disciplinas</h3>
        </div>
        <div class="p-6">
            @if($disciplinas->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($disciplinas as $disciplina)
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6 flex flex-col justify-between hover:shadow-xl hover:scale-105 transition-all duration-300">
                            <div>
                                <h4 class="font-bold text-lg text-gray-800 dark:text-white">{{ $disciplina->nome }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    Professor: {{ $disciplina->professorResponsavel->name ?? 'A definir' }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">{{ Str::limit($disciplina->descricao, 100) }}</p>
                            </div>
                            <div class="mt-6">
                                @php
                                    $progresso = $progressoAluno[$disciplina->nome] ?? ['percentual' => 0];
                                @endphp
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Progresso</span>
                                    <span class="text-sm font-bold text-blue-600 dark:text-blue-400">{{ $progresso['percentual'] }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2.5">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $progresso['percentual'] }}%"></div>
                                </div>
                                <a href="#" class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg transition-colors w-full text-center">
                                    Acessar Disciplina
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-book-reader text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                    <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Nenhuma disciplina encontrada</h4>
                    <p class="text-gray-500 dark:text-gray-400 mt-2">Você ainda não está matriculado em nenhuma disciplina. Fale com a secretaria da EBD.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection