@extends('layouts.admin')

@section('title', 'Pagamentos - ' . $evento->titulo)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Cabeçalho -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Pagamentos do Evento</h1>
                <p class="text-gray-600 mt-2">{{ $evento->titulo }}</p>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('admin.eventos.show', $evento) }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Voltar
                </a>
                <a href="{{ route('admin.eventos.exportar-pagamentos', $evento) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-download mr-2"></i>Exportar
                </a>
            </div>
        </div>

        <!-- Estatísticas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-credit-card text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total de Pagamentos</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalPagamentos }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Aprovados</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $pagamentosAprovados }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-clock text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pendentes</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $pagamentosPendentes }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-dollar-sign text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Aprovado</p>
                        <p class="text-2xl font-bold text-gray-900">R$ {{ number_format($totalAprovado, 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Nome, e-mail...">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Todos</option>
                            <option value="pendente" {{ request('status') === 'pendente' ? 'selected' : '' }}>Pendente</option>
                            <option value="processando" {{ request('status') === 'processando' ? 'selected' : '' }}>Processando</option>
                            <option value="aprovado" {{ request('status') === 'aprovado' ? 'selected' : '' }}>Aprovado</option>
                            <option value="rejeitado" {{ request('status') === 'rejeitado' ? 'selected' : '' }}>Rejeitado</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Forma de Pagamento</label>
                        <select name="forma_pagamento" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Todos</option>
                            <option value="stripe" {{ request('forma_pagamento') === 'stripe' ? 'selected' : '' }}>Stripe</option>
                            <option value="mercadopago" {{ request('forma_pagamento') === 'mercadopago' ? 'selected' : '' }}>Mercado Pago</option>
                            <option value="pix" {{ request('forma_pagamento') === 'pix' ? 'selected' : '' }}>PIX</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors">
                            <i class="fas fa-search mr-2"></i>Filtrar
                        </button>
                        <a href="{{ route('admin.eventos.pagamentos', $evento) }}" class="ml-2 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition-colors">
                            <i class="fas fa-times mr-2"></i>Limpar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de Pagamentos -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Lista de Pagamentos</h2>

                @if($pagamentos->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Participante
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Valor
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Forma de Pagamento
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Data
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($pagamentos as $pagamento)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $pagamento->inscricao->nome }}</div>
                                                <div class="text-sm text-gray-500">{{ $pagamento->inscricao->email }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                R$ {{ number_format($pagamento->valor, 2, ',', '.') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                                                                         <div class="flex items-center">
                                                 @if($pagamento->forma_pagamento === 'stripe')
                                                     <i class="fab fa-stripe text-blue-600 mr-2"></i>
                                                 @elseif($pagamento->forma_pagamento === 'mercadopago')
                                                     <i class="fas fa-credit-card text-green-600 mr-2"></i>
                                                 @elseif($pagamento->forma_pagamento === 'pix')
                                                     <i class="fas fa-qrcode text-purple-600 mr-2"></i>
                                                 @endif
                                                 <span class="text-sm text-gray-900">{{ ucfirst($pagamento->forma_pagamento) }}</span>
                                             </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $pagamento->status === 'aprovado' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $pagamento->status === 'pendente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $pagamento->status === 'processando' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $pagamento->status === 'rejeitado' ? 'bg-red-100 text-red-800' : '' }}">
                                                {{ ucfirst($pagamento->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $pagamento->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <button onclick="verDetalhesPagamento({{ $pagamento->id }})" 
                                                        class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                @if($pagamento->status === 'pendente')
                                                    <button onclick="aprovarPagamento({{ $pagamento->id }})" 
                                                            class="text-green-600 hover:text-green-900">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-6">
                        {{ $pagamentos->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-credit-card text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum pagamento encontrado</h3>
                        <p class="text-gray-600">Não há pagamentos que correspondam aos filtros aplicados.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalhes do Pagamento -->
<div id="modalDetalhesPagamento" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Detalhes do Pagamento</h3>
            <div id="detalhesPagamentoConteudo">
                <!-- Conteúdo será carregado via AJAX -->
            </div>
            <div class="flex justify-end mt-6">
                <button onclick="fecharModalPagamento()" 
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function verDetalhesPagamento(id) {
    // Implementar carregamento de detalhes via AJAX
    document.getElementById('modalDetalhesPagamento').classList.remove('hidden');
}

function fecharModalPagamento() {
    document.getElementById('modalDetalhesPagamento').classList.add('hidden');
}

function aprovarPagamento(id) {
    if (confirm('Aprovar este pagamento?')) {
        // Implementar aprovação via AJAX
        window.location.reload();
    }
}
</script>
@endsection 