@extends('layouts.admin')

@section('title', 'Inscrições - ' . $evento->titulo)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Cabeçalho -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Inscrições do Evento</h1>
                <p class="text-gray-600 mt-2">{{ $evento->titulo }}</p>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('admin.eventos.show', $evento) }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Voltar
                </a>
                <a href="{{ route('admin.eventos.exportar-inscricoes', $evento) }}" 
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
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total de Inscrições</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalInscricoes }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Confirmadas</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $inscricoesConfirmadas }}</p>
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
                        <p class="text-2xl font-bold text-gray-900">{{ $inscricoesPendentes }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <i class="fas fa-times-circle text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Canceladas</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $inscricoesCanceladas }}</p>
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
                            <option value="confirmada" {{ request('status') === 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                            <option value="cancelada" {{ request('status') === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Presença</label>
                        <select name="presenca" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Todos</option>
                            <option value="1" {{ request('presenca') === '1' ? 'selected' : '' }}>Presente</option>
                            <option value="0" {{ request('presenca') === '0' ? 'selected' : '' }}>Ausente</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors">
                            <i class="fas fa-search mr-2"></i>Filtrar
                        </button>
                        <a href="{{ route('admin.eventos.inscricoes', $evento) }}" class="ml-2 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition-colors">
                            <i class="fas fa-times mr-2"></i>Limpar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de Inscrições -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Lista de Inscrições</h2>

                @if($inscricoes->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Participante
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Contato
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Presença
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Data Inscrição
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($inscricoes as $inscricao)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $inscricao->nome }}</div>
                                                <div class="text-sm text-gray-500">{{ $inscricao->cpf }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm text-gray-900">{{ $inscricao->email }}</div>
                                                <div class="text-sm text-gray-500">{{ $inscricao->telefone }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $inscricao->status === 'confirmada' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $inscricao->status === 'pendente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $inscricao->status === 'cancelada' ? 'bg-red-100 text-red-800' : '' }}">
                                                {{ $inscricao->status_formatado }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($inscricao->presenca !== null)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    {{ $inscricao->presenca ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $inscricao->presenca ? 'Presente' : 'Ausente' }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-sm">Não registrado</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $inscricao->data_inscricao ? $inscricao->data_inscricao->format('d/m/Y H:i') : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <button onclick="verDetalhes({{ $inscricao->id }})" 
                                                        class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                @if($inscricao->status === 'pendente')
                                                    <button onclick="confirmarInscricao({{ $inscricao->id }})" 
                                                            class="text-green-600 hover:text-green-900">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                                @if($inscricao->status !== 'cancelada')
                                                    <button onclick="cancelarInscricao({{ $inscricao->id }})" 
                                                            class="text-red-600 hover:text-red-900">
                                                        <i class="fas fa-times"></i>
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
                        {{ $inscricoes->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-users text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma inscrição encontrada</h3>
                        <p class="text-gray-600">Não há inscrições que correspondam aos filtros aplicados.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalhes -->
<div id="modalDetalhes" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Detalhes da Inscrição</h3>
            <div id="detalhesConteudo">
                <!-- Conteúdo será carregado via AJAX -->
            </div>
            <div class="flex justify-end mt-6">
                <button onclick="fecharModal()" 
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function verDetalhes(id) {
    // Implementar carregamento de detalhes via AJAX
    document.getElementById('modalDetalhes').classList.remove('hidden');
}

function fecharModal() {
    document.getElementById('modalDetalhes').classList.add('hidden');
}

function confirmarInscricao(id) {
    if (confirm('Confirmar esta inscrição?')) {
        // Implementar confirmação via AJAX
        window.location.reload();
    }
}

function cancelarInscricao(id) {
    if (confirm('Cancelar esta inscrição?')) {
        // Implementar cancelamento via AJAX
        window.location.reload();
    }
}
</script>
@endsection 