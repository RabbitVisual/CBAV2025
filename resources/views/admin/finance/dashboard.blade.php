@extends('layouts.admin')

@section('title', 'Gestão Financeira - Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Cabeçalho -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Gestão Financeira</h1>
            <p class="text-gray-600">Gerencie transações, campanhas e relatórios financeiros</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.finance.transactions.create') }}" 
               class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Nova Transação
            </a>
            <a href="{{ route('admin.finance.campaigns.create') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-bullhorn mr-2"></i>Nova Campanha
            </a>
        </div>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total de Transações -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-exchange-alt text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Transações</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($estatisticas['total_transacoes']) }}</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-green-600">
                    <i class="fas fa-arrow-up mr-1"></i>{{ $estatisticas['transacoes_hoje'] }} hoje
                </span>
            </div>
        </div>

        <!-- Total Recebido -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Recebido</p>
                    <p class="text-2xl font-bold text-gray-900">R$ {{ number_format($estatisticas['total_recebido'], 2, ',', '.') }}</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-green-600">
                    <i class="fas fa-check-circle mr-1"></i>Confirmado
                </span>
            </div>
        </div>

        <!-- Total Pendente -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Pendente</p>
                    <p class="text-2xl font-bold text-gray-900">R$ {{ number_format($estatisticas['total_pendente'], 2, ',', '.') }}</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-yellow-600">
                    <i class="fas fa-exclamation-triangle mr-1"></i>Aguardando
                </span>
            </div>
        </div>

        <!-- Campanhas Ativas -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-bullhorn text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Campanhas Ativas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($estatisticas['campanhas_ativas']) }}</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-purple-600">
                    <i class="fas fa-info-circle mr-1"></i>Em andamento
                </span>
            </div>
        </div>
    </div>

    <!-- Seções Principais -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Transações Recentes -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Transações Recentes</h3>
            </div>
            <div class="p-6">
                @if($transacoesRecentes->count() > 0)
                    <div class="space-y-4">
                        @foreach($transacoesRecentes as $transacao)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                        <i class="fas fa-exchange-alt text-gray-600"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $transacao->membro->nome ?? 'Anônimo' }}
                                        </p>
                                        <p class="text-sm text-gray-500">{{ $transacao->descricao }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">
                                        R$ {{ number_format($transacao->valor, 2, ',', '.') }}
                                    </p>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $transacao->status === 'confirmado' ? 'bg-green-100 text-green-800' : 
                                           ($transacao->status === 'pendente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($transacao->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.finance.transactions.index') }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Ver todas as transações →
                        </a>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Nenhuma transação registrada ainda.</p>
                @endif
            </div>
        </div>

        <!-- Campanhas Ativas -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Campanhas Ativas</h3>
            </div>
            <div class="p-6">
                @if($campanhasAtivas->count() > 0)
                    <div class="space-y-4">
                        @foreach($campanhasAtivas as $campanha)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-bullhorn text-purple-600"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $campanha->nome }}</p>
                                        <p class="text-sm text-gray-500">{{ $campanha->transacoes_count }} doações</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">
                                        R$ {{ number_format($campanha->meta, 2, ',', '.') }}
                                    </p>
                                    <div class="w-20 bg-gray-200 rounded-full h-2 mt-1">
                                        <div class="bg-green-600 h-2 rounded-full" 
                                             style="width: {{ min(100, ($campanha->valor_arrecadado / $campanha->meta) * 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.finance.campaigns.index') }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Ver todas as campanhas →
                        </a>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Nenhuma campanha ativa no momento.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Links Rápidos -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Ações Rápidas</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.finance.transactions.index') }}" 
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-exchange-alt text-blue-600 mr-3"></i>
                    <div>
                        <p class="font-medium text-gray-900">Gerenciar Transações</p>
                        <p class="text-sm text-gray-500">Visualizar e editar transações</p>
                    </div>
                </a>

                <a href="{{ route('admin.finance.campaigns.index') }}" 
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-bullhorn text-purple-600 mr-3"></i>
                    <div>
                        <p class="font-medium text-gray-900">Gerenciar Campanhas</p>
                        <p class="text-sm text-gray-500">Criar e gerenciar campanhas</p>
                    </div>
                </a>

                <a href="{{ route('admin.finance.reports.index') }}" 
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-chart-bar text-green-600 mr-3"></i>
                    <div>
                        <p class="font-medium text-gray-900">Relatórios</p>
                        <p class="text-sm text-gray-500">Relatórios financeiros</p>
                    </div>
                </a>

                <a href="{{ route('admin.finance.settings.index') }}" 
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-cog text-orange-600 mr-3"></i>
                    <div>
                        <p class="font-medium text-gray-900">Configurações</p>
                        <p class="text-sm text-gray-500">Configurar gateways de pagamento</p>
                    </div>
                </a>

                <a href="{{ route('admin.finance.reports.export', ['tipo' => 'transacoes']) }}" 
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-download text-teal-600 mr-3"></i>
                    <div>
                        <p class="font-medium text-gray-900">Exportar Dados</p>
                        <p class="text-sm text-gray-500">Baixar relatórios em Excel</p>
                    </div>
                </a>

                <a href="{{ route('admin.finance.campaigns.batch') }}" 
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-layer-group text-indigo-600 mr-3"></i>
                    <div>
                        <p class="font-medium text-gray-900">Criar em Lote</p>
                        <p class="text-sm text-gray-500">Criar múltiplas campanhas</p>
                    </div>
                </a>

                <a href="{{ route('admin.finance.documentos.index') }}" 
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-file-alt text-teal-600 mr-3"></i>
                    <div>
                        <p class="font-medium text-gray-900">Documentos de Baixa</p>
                        <p class="text-sm text-gray-500">Comprovantes para IR</p>
                    </div>
                </a>

                <a href="{{ route('admin.finance.reports.export', ['tipo' => 'financeiro']) }}" 
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-file-invoice-dollar text-yellow-600 mr-3"></i>
                    <div>
                        <p class="font-medium text-gray-900">Relatório Financeiro</p>
                        <p class="text-sm text-gray-500">Relatório detalhado</p>
                    </div>
                </a>

                <a href="{{ route('admin.finance.campaigns.transactions', 1) }}" 
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-list-alt text-pink-600 mr-3"></i>
                    <div>
                        <p class="font-medium text-gray-900">Transações por Campanha</p>
                        <p class="text-sm text-gray-500">Ver doações por campanha</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 