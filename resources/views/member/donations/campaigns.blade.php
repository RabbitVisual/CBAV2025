@extends('layouts.member')

@section('title', 'Campanhas de Doação')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-8">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('member.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ route('member.donations.index') }}" class="hover:text-blue-600">Doações</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-900 font-medium">Campanhas</li>
        </ol>
    </nav>

    <!-- Header com Frase Bíblica -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    <svg class="w-8 h-8 text-red-600 mr-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    Campanhas de Doação
                </h1>
                <p class="text-gray-600 mb-2">Participe das campanhas ativas e ajude nossa igreja a realizar seus projetos</p>
                <div class="bg-gradient-to-r from-green-50 to-blue-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                    <p class="text-sm text-green-800 italic">
                        <i class="fas fa-quote-left mr-2"></i>
                        "Cada um deve dar conforme determinou em seu coração, não com pesar ou por obrigação, pois Deus ama quem dá com alegria." 
                        <span class="font-semibold">- 2 Coríntios 9:7</span>
                    </p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('member.donations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Voltar
                </a>
                <a href="{{ route('member.donations.donate') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nova Doação
                </a>
            </div>
        </div>
    </div>

    <!-- Guia Informativo Melhorado -->
    <div class="bg-gradient-to-r from-blue-50 to-purple-50 border border-blue-200 rounded-lg p-6 mb-8">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-blue-900">{{ __('Como funcionam as campanhas:') }}</h3>
                <div class="mt-2 text-sm text-blue-700 space-y-1">
                    <p><i class="fas fa-check-circle text-green-500 mr-2"></i>{{ __('Cada campanha tem um objetivo específico e uma meta de arrecadação') }}</p>
                    <p><i class="fas fa-chart-line text-blue-500 mr-2"></i>{{ __('Você pode acompanhar o progresso em tempo real') }}</p>
                    <p><i class="fas fa-heart text-red-500 mr-2"></i>{{ __('Doações podem ser feitas para campanhas específicas ou gerais') }}</p>
                    <p><i class="fas fa-shield-alt text-purple-500 mr-2"></i>{{ __('Todas as transações são seguras e transparentes') }}</p>
                </div>
                <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-sm text-yellow-800">
                        <i class="fas fa-lightbulb text-yellow-600 mr-2"></i>
                        <strong>{{ __('Dica:') }}</strong> {{ __('Clique em "Ver Detalhes" para saber mais sobre cada campanha ou "Doar" para contribuir diretamente.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Estatísticas Melhoradas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.5a.5.5 0 01.5-.5h2a.5.5 0 01.5.5v2a.5.5 0 01-.5.5h-2a.5.5 0 01-.5-.5v-2zM11 16.5a.5.5 0 01.5-.5h2a.5.5 0 01.5.5v2a.5.5 0 01-.5.5h-2a.5.5 0 01-.5-.5v-2zM4 5.5a.5.5 0 01.5-.5h2a.5.5 0 01.5.5v2a.5.5 0 01-.5.5h-2a.5.5 0 01-.5-.5v-2zM4 16.5a.5.5 0 01.5-.5h2a.5.5 0 01.5.5v2a.5.5 0 01-.5.5h-2a.5.5 0 01-.5-.5v-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Campanhas Ativas') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $campanhas->where('ativo', true)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Total Arrecadado') }}</p>
                    <p class="text-2xl font-bold text-gray-900">R$ {{ number_format($campanhas->sum('valor_arrecadado'), 2, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Meta Total') }}</p>
                    <p class="text-2xl font-bold text-gray-900">R$ {{ number_format($campanhas->sum('meta_valor'), 2, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Progresso Geral') }}</p>
                    <p class="text-2xl font-bold text-gray-900">
                        @php
                            $totalMeta = $campanhas->sum('meta_valor');
                            $totalArrecadado = $campanhas->sum('valor_arrecadado');
                            $progresso = $totalMeta > 0 ? round(($totalArrecadado / $totalMeta) * 100, 1) : 0;
                        @endphp
                        {{ $progresso }}%
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Campanhas Melhorada -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">
                <svg class="w-5 h-5 text-blue-600 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                {{ __('Campanhas Disponíveis') }}
            </h3>
        </div>
        <div class="p-6">
            @if($campanhas->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($campanhas as $campanha)
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                            @if($campanha->imagem)
                                <div class="aspect-w-16 aspect-h-9">
                                    <img src="{{ asset('storage/' . $campanha->imagem) }}" 
                                         alt="{{ $campanha->titulo }}" 
                                         class="w-full h-48 object-cover">
                                </div>
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-semibold text-lg text-gray-900">{{ $campanha->titulo }}</h4>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $campanha->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $campanha->ativo ? 'Ativa' : 'Inativa' }}
                                    </span>
                                </div>
                                
                                <p class="text-gray-600 text-sm mb-4">{{ Str::limit($campanha->descricao, 120) }}</p>
                                
                                @if($campanha->meta_valor > 0)
                                    <!-- Progresso -->
                                    <div class="mb-4">
                                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                                            <span>{{ __('Progresso') }}</span>
                                            <span>{{ $campanha->progresso }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $campanha->progresso }}%"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Valores -->
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div class="text-center p-3 bg-green-50 rounded-lg">
                                            <p class="text-xs text-gray-500">{{ __('Arrecadado') }}</p>
                                            <p class="font-semibold text-green-600">R$ {{ number_format($campanha->valor_arrecadado, 2, ',', '.') }}</p>
                                        </div>
                                        <div class="text-center p-3 bg-blue-50 rounded-lg">
                                            <p class="text-xs text-gray-500">{{ __('Meta') }}</p>
                                            <p class="font-semibold text-blue-600">R$ {{ number_format($campanha->meta_valor, 2, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Informações Adicionais -->
                                <div class="space-y-2 mb-4 text-xs text-gray-500">
                                    @if($campanha->data_inicio)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>{{ __('Início:') }} {{ $campanha->data_inicio->format('d/m/Y') }}</span>
                                        </div>
                                    @endif
                                    @if($campanha->data_fim)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>{{ __('Fim:') }} {{ $campanha->data_fim->format('d/m/Y') }}</span>
                                        </div>
                                    @endif
                                    @if($campanha->dias_restantes !== null)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="{{ $campanha->dias_restantes <= 7 ? 'text-red-600 font-medium' : '' }}">
                                                {{ $campanha->dias_restantes }} {{ __('dias restantes') }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Ações -->
                                <div class="flex space-x-2">
                                    <a href="{{ route('member.donations.campaigns.show', $campanha) }}" 
                                       class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        {{ __('Ver Detalhes') }}
                                    </a>
                                    @if($campanha->ativo)
                                        <a href="{{ route('member.donations.donate', ['campanha_id' => $campanha->id]) }}" 
                                           class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                            {{ __('Doar') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="mb-6">
                        <svg class="w-24 h-24 text-gray-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Nenhuma campanha disponível') }}</h3>
                    <p class="text-gray-500 mb-6">{{ __('Não há campanhas ativas no momento. Você ainda pode fazer uma doação geral.') }}</p>
                    <div class="space-x-4">
                        <a href="{{ route('member.donations.donate') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ __('Fazer Doação Geral') }}
                        </a>
                        <a href="{{ route('member.donations.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            {{ __('Ver Histórico') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Informações Adicionais Melhoradas -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Como Funciona -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <svg class="w-5 h-5 text-blue-600 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ __('Como Funciona') }}
            </h3>
            <div class="space-y-3 text-sm text-gray-600">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-medium mr-3 mt-0.5">1</div>
                    <span>{{ __('Escolha uma campanha que você gostaria de apoiar') }}</span>
                </div>
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-medium mr-3 mt-0.5">2</div>
                    <span>{{ __('Clique em "Doar" e escolha o valor que deseja contribuir') }}</span>
                </div>
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-medium mr-3 mt-0.5">3</div>
                    <span>{{ __('Selecione a forma de pagamento mais conveniente') }}</span>
                </div>
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-medium mr-3 mt-0.5">4</div>
                    <span>{{ __('Confirme a doação e acompanhe o progresso da campanha') }}</span>
                </div>
            </div>
        </div>

        <!-- Transparência -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <svg class="w-5 h-5 text-green-600 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                {{ __('Transparência') }}
            </h3>
            <div class="space-y-3 text-sm text-gray-600">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-2 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span>{{ __('Progresso atualizado em tempo real') }}</span>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-green-600 mr-2 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>{{ __('Comprovante enviado por email') }}</span>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-purple-600 mr-2 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ __('Histórico completo de doações') }}</span>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-orange-600 mr-2 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <span>{{ __('Dados protegidos e seguros') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animação de progresso
    const progressBars = document.querySelectorAll('.bg-blue-600');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 100);
    });

    // Tooltip para campanhas
    const campaignCards = document.querySelectorAll('.border-gray-200');
    campaignCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endsection 