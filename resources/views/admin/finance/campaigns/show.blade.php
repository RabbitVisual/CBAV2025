@extends('layouts.admin')

@section('title', 'Detalhes da Campanha')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $campanha->titulo }}</h1>
                <p class="text-gray-600 mt-2">{{ $campanha->descricao }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.finance.campaigns.edit', $campanha) }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-edit mr-2"></i>
                    Editar
                </a>
                <a href="{{ route('admin.finance.campaigns.index') }}" 
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Estatísticas Principais -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-bullseye text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Meta</p>
                    <p class="text-2xl font-bold text-gray-900">R$ {{ number_format($campanha->meta_valor, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Arrecadado</p>
                    <p class="text-2xl font-bold text-gray-900">R$ {{ number_format($campanha->valor_arrecadado, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Progresso</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($campanha->progresso, 1) }}%</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Transações</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $campanha->transacoes->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informações da Campanha -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Informações da Campanha</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Título</label>
                            <p class="text-gray-900">{{ $campanha->titulo }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst($campanha->tipo) }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            @if($campanha->status == 'ativa')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-play mr-1"></i>
                                    Ativa
                                </span>
                            @elseif($campanha->status == 'pausada')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-pause mr-1"></i>
                                    Pausada
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-stop mr-1"></i>
                                    Finalizada
                                </span>
                            @endif
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ativa</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $campanha->ativo ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                <i class="fas {{ $campanha->ativo ? 'fa-check' : 'fa-times' }} mr-1"></i>
                                {{ $campanha->ativo ? 'Sim' : 'Não' }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Data de Início</label>
                            <p class="text-gray-900">{{ $campanha->data_inicio->format('d/m/Y') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Data de Fim</label>
                            <p class="text-gray-900">{{ $campanha->data_fim ? $campanha->data_fim->format('d/m/Y') : 'Não definida' }}</p>
                        </div>
                        
                        @if($campanha->dias_restantes !== null)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dias Restantes</label>
                            <p class="text-gray-900">{{ $campanha->dias_restantes }} dias</p>
                        </div>
                        @endif
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                            <p class="text-gray-900">{{ $campanha->descricao ?: 'Nenhuma descrição fornecida' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Barra de Progresso -->
            <div class="bg-white rounded-lg shadow-md mt-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Progresso da Campanha</h3>
                </div>
                <div class="p-6">
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>Progresso</span>
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
            </div>
        </div>

        <!-- Ações Rápidas -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Ações Rápidas</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="{{ route('admin.finance.transactions.create', ['campanha_id' => $campanha->id]) }}" 
                           class="w-full flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <i class="fas fa-plus mr-2"></i>
                            Nova Transação
                        </a>
                        
                        <a href="{{ route('admin.finance.campaigns.transactions', $campanha) }}" 
                           class="w-full flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-list mr-2"></i>
                            Ver Transações
                        </a>
                        
                        <a href="{{ route('admin.finance.campaigns.export-report', $campanha) }}" 
                           class="w-full flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700"
                           target="_blank">
                            <i class="fas fa-download mr-2"></i>
                            Exportar Relatório
                        </a>
                        
                        <form action="{{ route('admin.finance.campaigns.delete', $campanha) }}" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
                                    onclick="return confirm('Tem certeza que deseja excluir esta campanha?')">
                                <i class="fas fa-trash mr-2"></i>
                                Excluir Campanha
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Últimas Transações -->
            <div class="bg-white rounded-lg shadow-md mt-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Últimas Transações</h3>
                </div>
                <div class="p-6">
                    @if($campanha->transacoes->count() > 0)
                        <div class="space-y-3">
                            @foreach($campanha->transacoes->take(5) as $transacao)
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $transacao->membro->nome ?? 'Anônimo' }}</p>
                                        <p class="text-xs text-gray-500">{{ $transacao->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">R$ {{ number_format($transacao->valor, 2, ',', '.') }}</p>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                   {{ $transacao->status == 'confirmado' ? 'bg-green-100 text-green-800' : 
                                                      ($transacao->status == 'pendente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($transacao->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($campanha->transacoes->count() > 5)
                            <div class="mt-4 text-center">
                                <a href="{{ route('admin.finance.campaigns.transactions', $campanha) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm">
                                    Ver todas as transações ({{ $campanha->transacoes->count() }})
                                </a>
                            </div>
                        @endif
                    @else
                        <p class="text-gray-500 text-center py-4">Nenhuma transação encontrada</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 