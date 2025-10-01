@extends('layouts.member')

@section('title', $campanha->titulo . ' - Campanha de Doação')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-8">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('member.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ route('member.donations.index') }}" class="hover:text-blue-600">Doações</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ route('member.donations.campaigns') }}" class="hover:text-blue-600">Campanhas</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-900 font-medium">{{ $campanha->titulo }}</li>
        </ol>
    </nav>

    <!-- Header da Campanha -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    <svg class="w-8 h-8 text-red-600 mr-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    {{ $campanha->titulo }}
                </h1>
                <p class="text-gray-600 mb-4">{{ $campanha->descricao }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('member.donations.campaigns') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Voltar
                </a>
                <a href="{{ route('member.donations.donate', ['campanha_id' => $campanha->id]) }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    Doar Agora
                </a>
            </div>
        </div>
    </div>

    <!-- Informações da Campanha -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Coluna Principal -->
        <div class="lg:col-span-2">
            <!-- Estatísticas da Campanha -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Progresso da Campanha</h2>
                
                <!-- Barra de Progresso -->
                <div class="mb-6">
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>Progresso</span>
                        <span>{{ number_format($campanha->progresso, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="bg-gradient-to-r from-green-500 to-blue-500 h-4 rounded-full transition-all duration-300" 
                             style="width: {{ min($campanha->progresso, 100) }}%"></div>
                    </div>
                    <div class="flex justify-between text-sm text-gray-500 mt-2">
                        <span>R$ {{ number_format($campanha->valor_arrecadado, 2, ',', '.') }} arrecadado</span>
                        <span>R$ {{ number_format($campanha->meta_valor, 2, ',', '.') }} meta</span>
                    </div>
                </div>

                <!-- Cards de Estatísticas -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <div class="flex items-center">
                            <div class="p-2 rounded-full bg-blue-100">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-blue-900">Meta</p>
                                <p class="text-lg font-bold text-blue-600">R$ {{ number_format($campanha->meta_valor, 2, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                        <div class="flex items-center">
                            <div class="p-2 rounded-full bg-green-100">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-900">Arrecadado</p>
                                <p class="text-lg font-bold text-green-600">R$ {{ number_format($campanha->valor_arrecadado, 2, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                        <div class="flex items-center">
                            <div class="p-2 rounded-full bg-purple-100">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-purple-900">Dias Restantes</p>
                                <p class="text-lg font-bold text-purple-600">{{ $campanha->dias_restantes }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalhes da Campanha -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Detalhes da Campanha</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Informações Gerais</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tipo:</span>
                                <span class="font-medium">{{ ucfirst($campanha->tipo) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="font-medium">
                                    @if($campanha->status === 'ativa')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Ativa
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ ucfirst($campanha->status) }}
                                        </span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Data de Início:</span>
                                <span class="font-medium">{{ $campanha->data_inicio->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Data de Fim:</span>
                                <span class="font-medium">{{ $campanha->data_fim->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Resumo Financeiro</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Valor Restante:</span>
                                <span class="font-medium text-red-600">R$ {{ number_format($campanha->meta_valor - $campanha->valor_arrecadado, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Percentual Concluído:</span>
                                <span class="font-medium text-green-600">{{ number_format($campanha->progresso, 1) }}%</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Duração Total:</span>
                                <span class="font-medium">{{ $campanha->data_inicio->diffInDays($campanha->data_fim) }} dias</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Frase Motivacional -->
            <div class="bg-gradient-to-r from-green-50 to-blue-50 border-l-4 border-green-400 p-6 rounded-r-lg">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-green-900 mb-2">Sua Contribuição Faz a Diferença!</h3>
                        <p class="text-green-800">
                            "Cada doação, por menor que seja, é um passo em direção ao objetivo desta campanha. 
                            Sua generosidade ajuda a transformar vidas e realizar sonhos. 
                            <strong>Junte-se a nós nesta missão!</strong>"
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Card de Doação -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Faça Sua Doação</h3>
                
                <div class="space-y-4">
                    <div class="text-center">
                        <p class="text-sm text-gray-600 mb-2">Escolha o valor da sua doação:</p>
                        <div class="grid grid-cols-2 gap-2 mb-4">
                            <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                R$ 10,00
                            </button>
                            <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                R$ 25,00
                            </button>
                            <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                R$ 50,00
                            </button>
                            <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                R$ 100,00
                            </button>
                        </div>
                    </div>

                    <a href="{{ route('member.donations.donate', ['campanha_id' => $campanha->id]) }}" 
                       class="w-full inline-flex justify-center items-center px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        Doar para esta Campanha
                    </a>

                    <div class="text-center">
                        <p class="text-xs text-gray-500">
                            <i class="fas fa-lock mr-1"></i>
                            Pagamento seguro e criptografado
                        </p>
                    </div>
                </div>
            </div>

            <!-- Informações Importantes -->
            <div class="bg-blue-50 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-4">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Informações Importantes
                </h3>
                
                <div class="space-y-3 text-sm text-blue-800">
                    <div class="flex items-start">
                        <svg class="w-4 h-4 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Todas as doações são seguras e transparentes</span>
                    </div>
                    <div class="flex items-start">
                        <svg class="w-4 h-4 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Você receberá um comprovante por email</span>
                    </div>
                    <div class="flex items-start">
                        <svg class="w-4 h-4 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Pode acompanhar o progresso em tempo real</span>
                    </div>
                    <div class="flex items-start">
                        <svg class="w-4 h-4 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Suas informações são mantidas em sigilo</span>
                    </div>
                </div>
            </div>

            <!-- Links Úteis -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Links Úteis</h3>
                
                <div class="space-y-3">
                    <a href="{{ route('member.donations.history') }}" class="flex items-center text-blue-600 hover:text-blue-800 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Histórico de Doações
                    </a>
                    <a href="{{ route('member.donations.campaigns') }}" class="flex items-center text-blue-600 hover:text-blue-800 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Ver Todas as Campanhas
                    </a>
                    <a href="{{ route('member.donations.index') }}" class="flex items-center text-blue-600 hover:text-blue-800 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        Nova Doação
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 