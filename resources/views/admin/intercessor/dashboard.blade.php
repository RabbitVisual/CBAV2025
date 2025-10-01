@extends('layouts.admin')

@section('title', 'Dashboard de Intercessor')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard de Intercessor</h1>
        <p class="text-gray-600">Gerencie os pedidos de oração e suas intercessões</p>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pendentes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['total_pendentes'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-praying-hands text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Em Oração</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['total_em_oracao'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Atendidos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['total_atendidos'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-user text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Minhas Intercessões</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['minhas_intercessoes'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pedidos Pendentes -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Pedidos Pendentes</h2>
            <p class="text-sm text-gray-600">Pedidos que aguardam intercessão</p>
        </div>
        <div class="p-6">
            @if($pedidosPendentes->count() > 0)
                <div class="space-y-4">
                    @foreach($pedidosPendentes as $pedido)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($pedido->prioridade === 'alta') bg-red-100 text-red-800
                                            @elseif($pedido->prioridade === 'media') bg-yellow-100 text-yellow-800
                                            @else bg-green-100 text-green-800 @endif">
                                            {{ ucfirst($pedido->prioridade) }}
                                        </span>
                                        <span class="ml-2 text-sm text-gray-500">{{ $pedido->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <h3 class="font-medium text-gray-900 mb-1">{{ $pedido->titulo }}</h3>
                                    <p class="text-sm text-gray-600 mb-2">{{ Str::limit($pedido->descricao, 150) }}</p>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-user mr-1"></i>
                                        <span>{{ $pedido->membro->nome ?? 'Anônimo' }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <a href="{{ route('admin.intercessor.show', $pedido) }}" 
                                       class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-eye mr-1"></i>
                                        Ver Detalhes
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($pedidosPendentes->count() > 10)
                    <div class="mt-6 text-center">
                        <a href="{{ route('admin.intercessor.index') }}" class="text-blue-600 hover:text-blue-800">
                            Ver todos os pedidos pendentes →
                        </a>
                    </div>
                @endif
            @else
                <div class="text-center py-8">
                    <i class="fas fa-check-circle text-4xl text-green-500 mb-4"></i>
                    <p class="text-gray-600">Nenhum pedido pendente no momento!</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Pedidos Em Oração -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Pedidos Em Oração</h2>
            <p class="text-sm text-gray-600">Pedidos que estão sendo intercedidos</p>
        </div>
        <div class="p-6">
            @if($pedidosEmOracao->count() > 0)
                <div class="space-y-4">
                    @foreach($pedidosEmOracao as $pedido)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Em Oração
                                        </span>
                                        <span class="ml-2 text-sm text-gray-500">{{ $pedido->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <h3 class="font-medium text-gray-900 mb-1">{{ $pedido->titulo }}</h3>
                                    <p class="text-sm text-gray-600 mb-2">{{ Str::limit($pedido->descricao, 150) }}</p>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-user mr-1"></i>
                                        <span>{{ $pedido->membro->nome ?? 'Anônimo' }}</span>
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-users mr-1"></i>
                                        <span>{{ $pedido->intercessores->count() }} intercessor(es)</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <a href="{{ route('admin.intercessor.show', $pedido) }}" 
                                       class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <i class="fas fa-praying-hands mr-1"></i>
                                        Interceder
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($pedidosEmOracao->count() > 10)
                    <div class="mt-6 text-center">
                        <a href="{{ route('admin.intercessor.index') }}" class="text-blue-600 hover:text-blue-800">
                            Ver todos os pedidos em oração →
                        </a>
                    </div>
                @endif
            @else
                <div class="text-center py-8">
                    <i class="fas fa-praying-hands text-4xl text-blue-500 mb-4"></i>
                    <p class="text-gray-600">Nenhum pedido em oração no momento!</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 