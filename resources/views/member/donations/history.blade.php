@extends('layouts.member')

@section('title', 'Histórico de Doações')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-8">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('member.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ route('member.donations.index') }}" class="hover:text-blue-600">Doações</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-900 font-medium">Histórico</li>
        </ol>
    </nav>

    <!-- Header com Frase Bíblica -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Histórico de Doações</h1>
                <p class="text-gray-600 mb-2">Visualize todas as suas contribuições e acompanhe o impacto das suas doações</p>
                <div class="bg-gradient-to-r from-green-50 to-blue-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                    <p class="text-sm text-green-800 italic">
                        <i class="fas fa-quote-left mr-2"></i>
                        "Lembrem-se: aquele que semeia pouco, também colherá pouco, e aquele que semeia com fartura, também colherá fartura." 
                        <span class="font-semibold">- 2 Coríntios 9:6</span>
                    </p>
                </div>
            </div>
            <div class="flex space-x-4">
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

    <!-- Estatísticas Melhoradas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Total Doado') }}</p>
                    <p class="text-2xl font-bold text-gray-900">R$ {{ number_format($totalDoado, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-hand-holding-heart text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Total de Doações') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalDoacoes }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-calendar-alt text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Este Mês') }}</p>
                    <p class="text-2xl font-bold text-gray-900">R$ {{ number_format($doacoesMes, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Média Mensal') }}</p>
                    <p class="text-2xl font-bold text-gray-900">R$ {{ number_format($mediaMensal, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros Melhorados -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-filter text-blue-600 mr-2"></i>
            {{ __('Filtros e Busca') }}
        </h2>
        <form method="GET" action="{{ route('member.donations.history') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Período -->
            <div>
                <label for="periodo" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Período') }}
                </label>
                <select id="periodo" name="periodo" class="form-select w-full">
                    <option value="">{{ __('Todos os períodos') }}</option>
                    <option value="7" {{ request('periodo') == '7' ? 'selected' : '' }}>{{ __('Últimos 7 dias') }}</option>
                    <option value="30" {{ request('periodo') == '30' ? 'selected' : '' }}>{{ __('Últimos 30 dias') }}</option>
                    <option value="90" {{ request('periodo') == '90' ? 'selected' : '' }}>{{ __('Últimos 90 dias') }}</option>
                    <option value="365" {{ request('periodo') == '365' ? 'selected' : '' }}>{{ __('Último ano') }}</option>
                </select>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Status') }}
                </label>
                <select id="status" name="status" class="form-select w-full">
                    <option value="">{{ __('Todos os status') }}</option>
                    <option value="confirmado" {{ request('status') == 'confirmado' ? 'selected' : '' }}>{{ __('Confirmado') }}</option>
                    <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>{{ __('Pendente') }}</option>
                    <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>{{ __('Cancelado') }}</option>
                </select>
            </div>

            <!-- Tipo -->
            <div>
                <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Tipo') }}
                </label>
                <select id="tipo" name="tipo" class="form-select w-full">
                    <option value="">{{ __('Todos os tipos') }}</option>
                    <option value="entrada" {{ request('tipo') == 'entrada' ? 'selected' : '' }}>{{ __('Entrada') }}</option>
                    <option value="saida" {{ request('tipo') == 'saida' ? 'selected' : '' }}>{{ __('Saída') }}</option>
                </select>
            </div>

            <!-- Busca -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Buscar') }}
                </label>
                <input type="text" 
                       id="search" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="{{ __('Descrição, campanha...') }}"
                       class="form-input w-full">
            </div>

            <!-- Botões -->
            <div class="md:col-span-4 flex space-x-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search mr-2"></i>{{ __('Filtrar') }}
                </button>
                <a href="{{ route('member.donations.history') }}" class="btn btn-secondary">
                    <i class="fas fa-times mr-2"></i>{{ __('Limpar') }}
                </a>
            </div>
        </form>
    </div>

    <!-- Lista de Doações Melhorada -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-list text-blue-600 mr-2"></i>
                {{ __('Suas Doações') }}
            </h2>
        </div>

        @if($transacoes->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Data') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Descrição') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Campanha') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Valor') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Status') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Ações') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($transacoes as $transacao)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ $transacao->created_at->format('d/m/Y') }}</span>
                                        <span class="text-gray-500">{{ $transacao->created_at->format('H:i') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div>
                                        <p class="font-medium">{{ $transacao->descricao }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @if($transacao->campanha)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-heart mr-1"></i>
                                            {{ $transacao->campanha->titulo }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">{{ __('Sem campanha') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="font-medium {{ $transacao->tipo == 'entrada' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transacao->tipo == 'entrada' ? '+' : '-' }} R$ {{ number_format($transacao->valor, 2, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($transacao->status == 'confirmado') bg-green-100 text-green-800
                                        @elseif($transacao->status == 'pendente') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        @if($transacao->status == 'confirmado')
                                            <i class="fas fa-check mr-1"></i>
                                        @elseif($transacao->status == 'pendente')
                                            <i class="fas fa-clock mr-1"></i>
                                        @else
                                            <i class="fas fa-times mr-1"></i>
                                        @endif
                                        {{ ucfirst($transacao->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex space-x-2">
                                        @if($transacao->status == 'confirmado')
                                            <button onclick="baixarComprovante({{ $transacao->id }})" 
                                                    class="text-blue-600 hover:text-blue-900 transition-colors" 
                                                    title="{{ __('Baixar Comprovante') }}">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        @endif
                                        <button onclick="verDetalhes({{ $transacao->id }})" 
                                                class="text-gray-600 hover:text-gray-900 transition-colors" 
                                                title="{{ __('Ver Detalhes') }}">
                                            <i class="fas fa-eye"></i>
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
                {{ $transacoes->appends(request()->query())->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <div class="text-gray-400 mb-4">
                    <i class="fas fa-hand-holding-heart text-6xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Nenhuma doação encontrada') }}</h3>
                <p class="text-gray-500 mb-6">{{ __('Você ainda não fez nenhuma doação ou os filtros aplicados não retornaram resultados.') }}</p>
                <div class="space-x-4">
                    <a href="{{ route('member.donations.donate') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i>{{ __('Fazer Primeira Doação') }}
                    </a>
                    <a href="{{ route('member.donations.campaigns') }}" class="btn btn-secondary">
                        <i class="fas fa-heart mr-2"></i>{{ __('Ver Campanhas') }}
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Gráfico de Doações -->
    @if($transacoes->count() > 0)
        <div class="bg-white rounded-lg shadow-md p-6 mt-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-chart-line text-blue-600 mr-2"></i>
                {{ __('Evolução das Doações') }}
            </h2>
            <div class="h-64">
                <canvas id="doacoesChart"></canvas>
            </div>
        </div>
    @endif

    <!-- Resumo Estatístico -->
    @if($transacoes->count() > 0)
        <div class="bg-white rounded-lg shadow-md p-6 mt-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-chart-bar text-green-600 mr-2"></i>
                {{ __('Resumo Estatístico') }}
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <h3 class="text-lg font-semibold text-blue-900">{{ __('Maior Doação') }}</h3>
                    <p class="text-2xl font-bold text-blue-600">
                        R$ {{ number_format($transacoes->max('valor') ?? 0, 2, ',', '.') }}
                    </p>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <h3 class="text-lg font-semibold text-green-900">{{ __('Média por Doação') }}</h3>
                    <p class="text-2xl font-bold text-green-600">
                        R$ {{ number_format($transacoes->avg('valor') ?? 0, 2, ',', '.') }}
                    </p>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <h3 class="text-lg font-semibold text-purple-900">{{ __('Última Doação') }}</h3>
                    <p class="text-2xl font-bold text-purple-600">
                        {{ $transacoes->first() ? $transacoes->first()->created_at->diffForHumans() : __('N/A') }}
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Modal para Detalhes da Transação -->
<div id="transactionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('Detalhes da Doação') }}</h3>
                    <button onclick="fecharModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div id="transactionDetails" class="space-y-4">
                    <!-- Conteúdo será carregado dinamicamente -->
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if($transacoes->count() > 0 && isset($dadosGrafico))
        // Dados para o gráfico
        const dadosGrafico = @json($dadosGrafico);
        
        const ctx = document.getElementById('doacoesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dadosGrafico.labels,
                datasets: [{
                    label: '{{ __("Valor Doado") }}',
                    data: dadosGrafico.valores,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'R$ ' + value.toFixed(2);
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'R$ ' + context.parsed.y.toFixed(2);
                            }
                        }
                    },
                    legend: {
                        display: false
                    }
                }
            }
        });
    @endif
});

function baixarComprovante(transacaoId) {
    window.open(`/member/donations/transaction/${transacaoId}/comprovante`, '_blank');
}

function verDetalhes(transacaoId) {
    const modal = document.getElementById('transactionModal');
    const detailsDiv = document.getElementById('transactionDetails');
    
    // Mostrar loading
    detailsDiv.innerHTML = `
        <div class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-600">{{ __('Carregando detalhes...') }}</p>
        </div>
    `;
    
    modal.classList.remove('hidden');
    
    // Buscar detalhes
    fetch(`/member/donations/transaction/${transacaoId}/details`)
        .then(response => response.json())
        .then(data => {
            let html = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">{{ __('Informações da Doação') }}</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('ID da Transação:') }}</span>
                                <span class="font-medium">#${data.transacao.id}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('Valor:') }}</span>
                                <span class="font-medium text-green-600">R$ ${data.transacao.valor}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('Status:') }}</span>
                                <span class="font-medium ${data.transacao.status === 'confirmado' ? 'text-green-600' : data.transacao.status === 'pendente' ? 'text-yellow-600' : 'text-red-600'}">${data.transacao.status}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('Data:') }}</span>
                                <span class="font-medium">${data.transacao.data}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('Método:') }}</span>
                                <span class="font-medium">${data.transacao.gateway}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('Campanha:') }}</span>
                                <span class="font-medium">${data.transacao.campanha}</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">{{ __('Descrição') }}</h4>
                        <p class="text-sm text-gray-600">${data.transacao.descricao}</p>
                    </div>
                </div>
            `;
            
            // Adicionar QR Code se for PIX pendente
            if (data.pix_data && data.transacao.status === 'pendente') {
                html += `
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <h4 class="font-medium text-blue-900 mb-3">{{ __('Pagamento PIX Pendente') }}</h4>
                        <div class="text-center">
                            <img src="data:image/png;base64,${data.pix_data.qr_code}" 
                                 alt="QR Code PIX" 
                                 class="mx-auto border-2 border-gray-200 rounded-lg mb-3">
                            <p class="text-sm text-blue-700 mb-2">{{ __('Escaneie o QR Code com seu app bancário') }}</p>
                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('Chave PIX:') }}</span>
                                    <span class="font-mono">${data.pix_data.chave}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('Valor:') }}</span>
                                    <span class="font-medium">R$ ${data.pix_data.valor}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            detailsDiv.innerHTML = html;
        })
        .catch(error => {
            detailsDiv.innerHTML = `
                <div class="text-center py-8">
                    <div class="text-red-500 mb-4">
                        <i class="fas fa-exclamation-triangle text-3xl"></i>
                    </div>
                    <p class="text-red-600">{{ __('Erro ao carregar detalhes da transação.') }}</p>
                </div>
            `;
        });
}

function fecharModal() {
    document.getElementById('transactionModal').classList.add('hidden');
}

// Fechar modal ao clicar fora
document.getElementById('transactionModal').addEventListener('click', function(e) {
    if (e.target === this) {
        fecharModal();
    }
});
</script>
@endpush
@endsection 