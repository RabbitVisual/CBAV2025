@extends('layouts.admin')

@section('title', 'Transações da Campanha - ' . $campanha->titulo)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Transações da Campanha</h1>
            <p class="text-gray-600 mt-2">{{ $campanha->titulo }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.finance.campaigns.show', $campanha) }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar à Campanha
            </a>
            <a href="{{ route('admin.finance.transactions.create', ['campanha_id' => $campanha->id]) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Nova Transação
            </a>
            <a href="{{ route('admin.finance.campaigns.export-report', $campanha) }}" 
               class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center"
               target="_blank">
                <i class="fas fa-download mr-2"></i>
                Exportar Relatório
            </a>
        </div>
    </div>

    <!-- Estatísticas da Campanha -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-bullseye text-blue-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Meta</p>
                    <p class="text-2xl font-bold text-gray-900">R$ {{ number_format($campanha->meta_valor, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-dollar-sign text-green-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Arrecadado</p>
                    <p class="text-2xl font-bold text-green-600">R$ {{ number_format($campanha->valor_arrecadado, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-chart-line text-purple-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Progresso</p>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($campanha->progresso, 1) }}%</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-list text-orange-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Transações</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $transacoes->total() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Barra de Progresso -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between text-sm text-gray-600 mb-2">
            <span>Progresso da Campanha</span>
            <span>{{ number_format($campanha->progresso, 1) }}%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-4">
            <div class="bg-blue-600 h-4 rounded-full transition-all duration-300" 
                 style="width: {{ min($campanha->progresso, 100) }}%"></div>
        </div>
        <div class="flex justify-between text-sm text-gray-500 mt-2">
            <span>R$ {{ number_format($campanha->valor_arrecadado, 2, ',', '.') }} arrecadado</span>
            <span>R$ {{ number_format($campanha->meta_valor, 2, ',', '.') }} meta</span>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Lista de Transações -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Transações da Campanha</h2>
        </div>
        
        @if($transacoes->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Membro
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Valor
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tipo
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
                        @foreach($transacoes as $transacao)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <i class="fas fa-user text-blue-600"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $transacao->membro->nome ?? 'Anônimo' }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $transacao->membro->email ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        R$ {{ number_format($transacao->valor, 2, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $transacao->tipo == 'entrada' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($transacao->tipo) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $transacao->status == 'confirmado' ? 'bg-green-100 text-green-800' : 
                                           ($transacao->status == 'pendente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($transacao->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transacao->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.finance.transactions.show', $transacao) }}" 
                                           class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.finance.transactions.edit', $transacao) }}" 
                                           class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="deleteTransaction({{ $transacao->id }})" 
                                                class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Paginação -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $transacoes->links() }}
            </div>
        @else
            <div class="p-6 text-center">
                <div class="text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-4"></i>
                    <p class="text-lg font-medium">Nenhuma transação encontrada</p>
                    <p class="text-sm">Esta campanha ainda não possui transações registradas.</p>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.finance.transactions.create', ['campanha_id' => $campanha->id]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i>
                        Criar Primeira Transação
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg p-6 max-w-sm mx-4">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-2xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-gray-900">Confirmar Exclusão</h3>
                </div>
            </div>
            <p class="text-sm text-gray-500 mb-4">
                Tem certeza que deseja excluir esta transação? Esta ação não pode ser desfeita.
            </p>
            <div class="flex justify-end space-x-3">
                <button onclick="closeDeleteModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Cancelar
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Excluir
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteTransaction(transacaoId) {
    if (confirm('Tem certeza que deseja excluir esta transação?')) {
        window.location.href = `/admin/finance/transactions/${transacaoId}/delete`;
    }
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

function showDeleteModal(transacaoId) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    form.action = `/admin/finance/transactions/${transacaoId}`;
    modal.classList.remove('hidden');
}
</script>
@endsection 