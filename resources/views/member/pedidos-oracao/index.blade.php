@extends('layouts.member')

@section('title', 'Meus Pedidos de Oração')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Meus Pedidos de Oração</h1>
        <p class="text-gray-600">Gerencie seus pedidos de oração e acompanhe as intercessões</p>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $estatisticas['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pendentes</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $estatisticas['pendentes'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Em Oração</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $estatisticas['em_oracao'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Atendidos</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $estatisticas['atendidos'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                    <option value="em_oracao" {{ request('status') == 'em_oracao' ? 'selected' : '' }}>Em Oração</option>
                    <option value="atendido" {{ request('status') == 'atendido' ? 'selected' : '' }}>Atendido</option>
                    <option value="arquivado" {{ request('status') == 'arquivado' ? 'selected' : '' }}>Arquivado</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Categoria</label>
                <select name="categoria" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todas</option>
                    <option value="saude" {{ request('categoria') == 'saude' ? 'selected' : '' }}>Saúde</option>
                    <option value="familia" {{ request('categoria') == 'familia' ? 'selected' : '' }}>Família</option>
                    <option value="trabalho" {{ request('categoria') == 'trabalho' ? 'selected' : '' }}>Trabalho</option>
                    <option value="espiritual" {{ request('categoria') == 'espiritual' ? 'selected' : '' }}>Espiritual</option>
                    <option value="outros" {{ request('categoria') == 'outros' ? 'selected' : '' }}>Outros</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Prioridade</label>
                <select name="prioridade" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todas</option>
                    <option value="baixa" {{ request('prioridade') == 'baixa' ? 'selected' : '' }}>Baixa</option>
                    <option value="media" {{ request('prioridade') == 'media' ? 'selected' : '' }}>Média</option>
                    <option value="alta" {{ request('prioridade') == 'alta' ? 'selected' : '' }}>Alta</option>
                    <option value="urgente" {{ request('prioridade') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Botão Novo Pedido -->
    <div class="mb-6">
        <a href="{{ route('member.pedidos-oracao.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Novo Pedido de Oração
        </a>
    </div>

    <!-- Lista de Pedidos -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($pedidos->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioridade</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pedidos as $pedido)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $pedido->titulo }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($pedido->descricao, 50) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $pedido->categoria_text }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $pedido->prioridade_color }}-100 text-{{ $pedido->prioridade_color }}-800">
                                    {{ $pedido->prioridade_text }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $pedido->status_color }}-100 text-{{ $pedido->status_color }}-800">
                                    {{ $pedido->status_text }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $pedido->data_pedido->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('member.pedidos-oracao.show', $pedido) }}" class="text-blue-600 hover:text-blue-900">Ver</a>
                                    @if($pedido->status === 'pendente')
                                        <a href="{{ route('member.pedidos-oracao.edit', $pedido) }}" class="text-yellow-600 hover:text-yellow-900">Editar</a>
                                        <form method="POST" action="{{ route('member.pedidos-oracao.destroy', $pedido) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Tem certeza que deseja excluir este pedido?')">Excluir</button>
                                        </form>
                                    @endif
                                    @if($pedido->status === 'em_oracao')
                                        <form method="POST" action="{{ route('member.pedidos-oracao.marcar-atendido', $pedido) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900">Marcar Atendido</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $pedidos->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum pedido encontrado</h3>
                <p class="mt-1 text-sm text-gray-500">Comece criando seu primeiro pedido de oração.</p>
                <div class="mt-6">
                    <a href="{{ route('member.pedidos-oracao.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Novo Pedido de Oração
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Pedidos Compartilhados -->
    @if($pedidosCompartilhados->count() > 0)
    <div class="mt-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Pedidos Compartilhados da Igreja</h2>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Membro</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioridade</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pedidosCompartilhados as $pedido)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $pedido->membro->nome }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $pedido->titulo }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($pedido->descricao, 50) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $pedido->categoria_text }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $pedido->prioridade_color }}-100 text-{{ $pedido->prioridade_color }}-800">
                                    {{ $pedido->prioridade_text }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $pedido->status_color }}-100 text-{{ $pedido->status_color }}-800">
                                    {{ $pedido->status_text }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $pedido->data_pedido->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('member.pedidos-oracao.show', $pedido) }}" class="text-blue-600 hover:text-blue-900">Ver</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection 