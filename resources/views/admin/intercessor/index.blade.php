@extends('layouts.admin')

@section('title', 'Pedidos de Oração')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Pedidos de Oração</h1>
        <p class="text-gray-600">Gerencie todos os pedidos de oração do sistema</p>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Filtros</h2>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('admin.intercessor.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        <option value="pendente" {{ request('status') === 'pendente' ? 'selected' : '' }}>Pendente</option>
                        <option value="em_oracao" {{ request('status') === 'em_oracao' ? 'selected' : '' }}>Em Oração</option>
                        <option value="atendido" {{ request('status') === 'atendido' ? 'selected' : '' }}>Atendido</option>
                    </select>
                </div>

                <div>
                    <label for="categoria" class="block text-sm font-medium text-gray-700 mb-2">Categoria</label>
                    <select name="categoria" id="categoria" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todas</option>
                        <option value="saude" {{ request('categoria') === 'saude' ? 'selected' : '' }}>Saúde</option>
                        <option value="familia" {{ request('categoria') === 'familia' ? 'selected' : '' }}>Família</option>
                        <option value="trabalho" {{ request('categoria') === 'trabalho' ? 'selected' : '' }}>Trabalho</option>
                        <option value="espiritual" {{ request('categoria') === 'espiritual' ? 'selected' : '' }}>Espiritual</option>
                        <option value="outros" {{ request('categoria') === 'outros' ? 'selected' : '' }}>Outros</option>
                    </select>
                </div>

                <div>
                    <label for="prioridade" class="block text-sm font-medium text-gray-700 mb-2">Prioridade</label>
                    <select name="prioridade" id="prioridade" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todas</option>
                        <option value="alta" {{ request('prioridade') === 'alta' ? 'selected' : '' }}>Alta</option>
                        <option value="media" {{ request('prioridade') === 'media' ? 'selected' : '' }}>Média</option>
                        <option value="baixa" {{ request('prioridade') === 'baixa' ? 'selected' : '' }}>Baixa</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                        <i class="fas fa-search mr-2"></i>
                        Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Pedidos -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Pedidos de Oração</h2>
                    <p class="text-sm text-gray-600">{{ $pedidos->total() }} pedido(s) encontrado(s)</p>
                </div>
                <a href="{{ route('admin.intercessor.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-tachometer-alt mr-2"></i>
                    Dashboard
                </a>
            </div>
        </div>
        <div class="p-6">
            @if($pedidos->count() > 0)
                <div class="space-y-4">
                    @foreach($pedidos as $pedido)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($pedido->status === 'pendente') bg-yellow-100 text-yellow-800
                                            @elseif($pedido->status === 'em_oracao') bg-blue-100 text-blue-800
                                            @else bg-green-100 text-green-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $pedido->status)) }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ml-2
                                            @if($pedido->prioridade === 'alta') bg-red-100 text-red-800
                                            @elseif($pedido->prioridade === 'media') bg-yellow-100 text-yellow-800
                                            @else bg-green-100 text-green-800 @endif">
                                            {{ ucfirst($pedido->prioridade) }}
                                        </span>
                                        <span class="ml-2 text-sm text-gray-500">{{ $pedido->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <h3 class="font-medium text-gray-900 mb-1">{{ $pedido->titulo }}</h3>
                                    <p class="text-sm text-gray-600 mb-2">{{ Str::limit($pedido->descricao, 200) }}</p>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-user mr-1"></i>
                                        <span>{{ $pedido->membro->nome ?? 'Anônimo' }}</span>
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-tag mr-1"></i>
                                        <span>{{ ucfirst($pedido->categoria) }}</span>
                                        @if($pedido->intercessores->count() > 0)
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-users mr-1"></i>
                                            <span>{{ $pedido->intercessores->count() }} intercessor(es)</span>
                                        @endif
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
                
                @if($pedidos->hasPages())
                    <div class="mt-6">
                        {{ $pedidos->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-8">
                    <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600">Nenhum pedido encontrado com os filtros aplicados.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 