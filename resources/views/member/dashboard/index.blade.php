@extends('layouts.member')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    {{-- Cabeçalho de Boas-Vindas --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 md:p-8">
        <div class="flex flex-col md:flex-row justify-between items-start gap-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Bem-vindo, {{ auth()->user()->first_name ?? auth()->user()->name }}!</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Que a paz do Senhor esteja com você hoje.</p>
            </div>
            <div class="flex-shrink-0 flex items-center gap-x-3">
                <a href="{{ route('member.donations.donate') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-2.5 rounded-lg transition-transform transform hover:scale-105 shadow-md flex items-center">
                    <i class="fas fa-heart mr-2"></i>Fazer uma Doação
                </a>
                <a href="{{ route('member.profile.edit') }}"
                   class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-semibold px-5 py-2.5 rounded-lg transition-colors flex items-center">
                    <i class="fas fa-user-edit mr-2"></i>Meu Perfil
                </a>
            </div>
        </div>
    </div>

    {{-- Layout principal com duas colunas --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Coluna Principal (2/3) --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- Seção "Próximos Passos" / "Fique por Dentro" --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white">Fique por Dentro</h3>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="bg-blue-50 dark:bg-blue-900/50 p-5 rounded-xl flex items-start gap-4">
                        <div class="bg-blue-100 dark:bg-blue-800 p-3 rounded-full text-blue-600 dark:text-blue-300">
                            <i class="fas fa-bell fa-lg"></i>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 dark:text-white">Notificações</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $estatisticas['notificacoes_nao_lidas'] ?? 0 }} não lidas</p>
                            <a href="#" class="text-sm font-semibold text-blue-600 dark:text-blue-400 hover:underline mt-2 inline-block">Ver agora &rarr;</a>
                        </div>
                    </div>
                    <div class="bg-purple-50 dark:bg-purple-900/50 p-5 rounded-xl flex items-start gap-4">
                        <div class="bg-purple-100 dark:bg-purple-800 p-3 rounded-full text-purple-600 dark:text-purple-300">
                            <i class="fas fa-calendar-check fa-lg"></i>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 dark:text-white">Próximos Eventos</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $estatisticas['proximos_eventos'] ?? 0 }} eventos agendados</p>
                            <a href="{{ route('member.eventos.index') }}" class="text-sm font-semibold text-purple-600 dark:text-purple-400 hover:underline mt-2 inline-block">Ver todos &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Devocional do Dia --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white">Devocional do Dia</h3>
                </div>
                <div class="p-6">
                    @if($devocionalDiario)
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $devocionalDiario['titulo'] }}</h4>
                        <p class="text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">"{{ $versiculoAleatorio['texto'] ?? 'Porque Deus amou o mundo...' }}" - <strong>{{ $versiculoAleatorio['referencia'] ?? 'João 3:16' }}</strong></p>
                        <p class="text-gray-700 dark:text-gray-300 mb-5">{{ Str::limit($devocionalDiario['reflexao'], 200) }}</p>
                        <a href="{{ route('member.devotionals.index') }}" class="font-semibold text-blue-600 dark:text-blue-400 hover:underline">Continuar lendo &rarr;</a>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">Nenhum devocional para hoje.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Coluna Lateral (1/3) --}}
        <div class="lg:col-span-1 space-y-8">
            {{-- Campanhas Ativas --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white">Campanhas</h3>
                </div>
                <div class="p-6 space-y-4">
                    @if($campanhasAtivas && $campanhasAtivas->count() > 0)
                        @foreach($campanhasAtivas->take(2) as $campanha)
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <h4 class="font-semibold text-gray-800 dark:text-white">{{ $campanha->titulo }}</h4>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $campanha->dias_restantes }} dias</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                    <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $campanha->progresso_percentual ?? 0 }}%"></div>
                                </div>
                                <a href="{{ route('member.donations.campaigns.show', $campanha) }}" class="text-sm font-semibold text-blue-600 dark:text-blue-400 hover:underline mt-2 inline-block">Ver campanha &rarr;</a>
                            </div>
                        @endforeach
                    @else
                        <p class="text-center text-gray-500 dark:text-gray-400 py-4">Nenhuma campanha ativa.</p>
                    @endif
                </div>
            </div>

            {{-- Acesso Rápido --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white">Acesso Rápido</h3>
                </div>
                <div class="p-6 grid grid-cols-2 gap-4">
                    <a href="{{ route('member.bible.index') }}" class="text-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <i class="fas fa-bible text-2xl text-orange-500 mb-2"></i>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Bíblia</span>
                    </a>
                    <a href="{{ route('member.ministries.index') }}" class="text-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <i class="fas fa-church text-2xl text-purple-500 mb-2"></i>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Ministérios</span>
                    </a>
                    <a href="{{ route('member.pedidos-oracao.index') }}" class="text-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <i class="fas fa-praying-hands text-2xl text-teal-500 mb-2"></i>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Orações</span>
                    </a>
                    <a href="{{ route('member.chat.index') }}" class="text-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <i class="fas fa-comments text-2xl text-cyan-500 mb-2"></i>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Chat</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection