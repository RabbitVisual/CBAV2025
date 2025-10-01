@extends('layouts.admin')

@section('title', 'Relatórios Financeiros')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Cabeçalho da página -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-chart-line text-blue-600 mr-3"></i>
                        Relatórios Financeiros
                    </h1>
                    <p class="text-lg text-gray-600 mb-4">Análise detalhada das ofertas e dízimos da igreja</p>
                    
                    <!-- Dicas de uso -->
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-400 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">💡 Como usar esta página</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p class="mb-1">• Selecione o período desejado nos filtros</p>
                                    <p class="mb-1">• Visualize os gráficos de evolução e distribuição</p>
                                    <p class="mb-1">• Exporte relatórios para Excel ou PDF</p>
                                    <p class="mb-0">• Clique nos valores para ver detalhes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Botões de ação -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200" 
                            onclick="window.print()">
                        <i class="fas fa-print mr-2 text-gray-500"></i>
                        Imprimir
                    </button>
                    
                    <div class="relative inline-block text-left">
                        <button type="button" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200" 
                                onclick="toggleExportDropdown()">
                            <i class="fas fa-download mr-2"></i>
                            Exportar
                            <i class="fas fa-chevron-down ml-2"></i>
                        </button>
                        
                        <div id="exportDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 z-50">
                            <div class="py-1">
                                <a href="{{ route('admin.finance.reports.export', ['tipo' => 'financeiro', 'formato' => 'excel', 'periodo' => $periodo, 'ano' => $ano, 'mes' => $mes]) }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                                    <i class="fas fa-file-excel mr-3 text-green-600"></i>
                                    <span>Excel (.xlsx)</span>
                                </a>
                                <a href="{{ route('admin.finance.reports.export', ['tipo' => 'financeiro', 'formato' => 'pdf', 'periodo' => $periodo, 'ano' => $ano, 'mes' => $mes]) }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                                    <i class="fas fa-file-pdf mr-3 text-red-600"></i>
                                    <span>PDF</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="fas fa-filter text-blue-600 mr-2"></i>
                    Filtros de Período
                </h3>
                <p class="text-sm text-gray-600 mt-1">Selecione o período que deseja analisar</p>
            </div>
            <div class="px-6 py-4">
                <form method="GET" action="{{ route('admin.finance.reports.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                    <div class="md:col-span-2">
                        <label for="periodo" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Período</label>
                        <select name="periodo" id="periodo" 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                                onchange="togglePeriodoFields()">
                            <option value="mes" {{ $periodo == 'mes' ? 'selected' : '' }}>Mensal</option>
                            <option value="ano" {{ $periodo == 'ano' ? 'selected' : '' }}>Anual</option>
                            <option value="personalizado" {{ $periodo == 'personalizado' ? 'selected' : '' }}>Personalizado</option>
                        </select>
                    </div>
                    
                    <div id="anoField">
                        <label for="ano" class="block text-sm font-medium text-gray-700 mb-1">Ano</label>
                        <select name="ano" id="ano" 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                            @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                <option value="{{ $i }}" {{ $ano == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    
                    <div id="mesField">
                        <label for="mes" class="block text-sm font-medium text-gray-700 mb-1">Mês</label>
                        <select name="mes" id="mes" 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                            @foreach([
                                1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
                                5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
                                9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
                            ] as $num => $nome)
                                <option value="{{ $num }}" {{ $mes == $num ? 'selected' : '' }}>{{ $nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div id="dataInicioField" class="hidden">
                        <label for="data_inicio" class="block text-sm font-medium text-gray-700 mb-1">Data Início</label>
                        <input type="date" name="data_inicio" id="data_inicio" 
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                               value="{{ request('data_inicio') }}">
                    </div>
                    
                    <div id="dataFimField" class="hidden">
                        <label for="data_fim" class="block text-sm font-medium text-gray-700 mb-1">Data Fim</label>
                        <input type="date" name="data_fim" id="data_fim" 
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                               value="{{ request('data_fim') }}">
                    </div>
                    
                    <div class="flex items-end space-x-3">
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <i class="fas fa-search mr-2"></i>
                            Aplicar Filtros
                        </button>
                        <a href="{{ route('admin.finance.reports.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Limpar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Resumo Geral -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-hand-holding-heart text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Ofertas e Dízimos</p>
                        <p class="text-2xl font-bold text-gray-900">R$ {{ number_format($dados['resumo']['total_entrada'], 2, ',', '.') }}</p>
                        <p class="text-xs text-green-600 font-medium">Total recebido</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Confirmado</p>
                        <p class="text-2xl font-bold text-gray-900">R$ {{ number_format($dados['resumo']['total_confirmado'], 2, ',', '.') }}</p>
                        <p class="text-xs text-green-600 font-medium">{{ number_format(($dados['resumo']['total_confirmado'] / max($dados['resumo']['total_entrada'], 1)) * 100, 1) }}% confirmado</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pendente</p>
                        <p class="text-2xl font-bold text-gray-900">R$ {{ number_format($dados['resumo']['total_pendente'], 2, ',', '.') }}</p>
                        <p class="text-xs text-yellow-600 font-medium">{{ number_format(($dados['resumo']['total_pendente'] / max($dados['resumo']['total_entrada'], 1)) * 100, 1) }}% pendente</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-list text-indigo-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Transações</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($dados['resumo']['quantidade_transacoes'], 0, ',', '.') }}</p>
                        <p class="text-xs text-indigo-600 font-medium">Média: R$ {{ number_format($dados['resumo']['total_entrada'] / max($dados['resumo']['quantidade_transacoes'], 1), 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Evolução da Arrecadação</h3>
                    <p class="text-sm text-gray-600 mt-1">Visualize a tendência de ofertas e dízimos ao longo do tempo</p>
                </div>
                <div class="p-6">
                    <canvas id="evolucaoChart" width="400" height="200"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Distribuição por Status</h3>
                    <p class="text-sm text-gray-600 mt-1">Veja como os valores estão distribuídos</p>
                </div>
                <div class="p-6">
                    <canvas id="statusChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Tabela de Transações -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Transações do Período</h3>
                        <p class="text-sm text-gray-600 mt-1">Lista detalhada de todas as transações</p>
                    </div>
                    <div class="flex space-x-3">
                        <button type="button" 
                                class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200" 
                                onclick="exportTable()">
                            <i class="fas fa-download mr-2"></i>
                            Exportar Tabela
                        </button>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Membro</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campanha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($dados['transacoes'] as $transacao)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $transacao->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transacao->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($transacao->membro)
                                    <div class="flex items-center">
                                        @if($transacao->membro->foto)
                                            <img src="{{ asset('storage/' . $transacao->membro->foto) }}" 
                                                 class="w-8 h-8 rounded-full mr-3 border border-gray-200" alt="Foto">
                                        @else
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3 border border-gray-200">
                                                <i class="fas fa-user text-blue-600 text-sm"></i>
                                            </div>
                                        @endif
                                        <span class="text-sm text-gray-900">{{ $transacao->membro->nome }}</span>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500">Anônimo</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ Str::limit($transacao->descricao, 50) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $transacao->tipo == 'entrada' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($transacao->tipo) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $transacao->tipo == 'entrada' ? 'text-green-600' : 'text-red-600' }}">
                                R$ {{ number_format($transacao->valor, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $transacao->status == 'confirmado' ? 'bg-green-100 text-green-800' : 
                                       ($transacao->status == 'pendente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($transacao->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($transacao->campanha)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $transacao->campanha->nome }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.finance.transactions.show', $transacao) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition-colors duration-150" title="Ver detalhes">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.finance.transactions.edit', $transacao) }}" 
                                       class="text-yellow-600 hover:text-yellow-900 transition-colors duration-150" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-4 text-gray-300"></i>
                                    <p class="text-lg font-medium">Nenhuma transação encontrada</p>
                                    <p class="text-sm">Não há transações para o período selecionado.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Análises Adicionais -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Principais Contribuintes</h3>
                    <p class="text-sm text-gray-600 mt-1">Membros com maior contribuição no período</p>
                </div>
                <div class="p-6">
                    @php
                        $topContribuintes = $dados['transacoes']->where('tipo', 'entrada')
                            ->groupBy('membro_id')
                            ->map(function($group) {
                                return [
                                    'membro' => $group->first()->membro,
                                    'total' => $group->sum('valor'),
                                    'quantidade' => $group->count()
                                ];
                            })
                            ->sortByDesc('total')
                            ->take(5);
                    @endphp
                    
                    @forelse($topContribuintes as $index => $contribuinte)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3 text-blue-600 font-bold text-xs">
                                {{ (int)$index + 1 }}
                            </div>
                            @if($contribuinte['membro'] && $contribuinte['membro']->foto)
                                <img src="{{ asset('storage/' . $contribuinte['membro']->foto) }}" 
                                     class="w-8 h-8 rounded-full mr-3 border border-gray-200" alt="Foto">
                            @else
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3 border border-gray-200">
                                    <i class="fas fa-user text-blue-600 text-sm"></i>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $contribuinte['membro'] ? $contribuinte['membro']->nome : 'Anônimo' }}</p>
                                <p class="text-xs text-gray-500">{{ $contribuinte['quantidade'] }} contribuição(ões)</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-green-600">R$ {{ number_format($contribuinte['total'], 2, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">{{ number_format(($contribuinte['total'] / max($dados['resumo']['total_entrada'], 1)) * 100, 1) }}% do total</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Nenhum contribuinte encontrado.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Campanhas Mais Ativas</h3>
                    <p class="text-sm text-gray-600 mt-1">Campanhas com maior arrecadação</p>
                </div>
                <div class="p-6">
                    @php
                        $campanhasAtivas = $dados['transacoes']->where('tipo', 'entrada')
                            ->groupBy('campanha_id')
                            ->map(function($group) {
                                return [
                                    'campanha' => $group->first()->campanha,
                                    'total' => $group->sum('valor'),
                                    'quantidade' => $group->count()
                                ];
                            })
                            ->sortByDesc('total')
                            ->take(5);
                    @endphp
                    
                    @forelse($campanhasAtivas as $index => $campanha)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center mr-3 text-purple-600 font-bold text-xs">
                                {{ (int)$index + 1 }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $campanha['campanha'] ? $campanha['campanha']->nome : 'Sem Campanha' }}</p>
                                <p class="text-xs text-gray-500">{{ $campanha['quantidade'] }} transação(ões)</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-blue-600">R$ {{ number_format($campanha['total'], 2, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">{{ number_format(($campanha['total'] / max($dados['resumo']['total_entrada'], 1)) * 100, 1) }}% do total</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-bullhorn text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Nenhuma campanha encontrada.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Exportação -->
<div id="exportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Exportar Relatório</h3>
                <p class="text-sm text-gray-600 mt-1">Escolha o formato de exportação</p>
            </div>
            <div class="px-6 py-4">
                <form id="exportForm" method="GET" action="{{ route('admin.finance.reports.export') }}">
                    <div class="mb-4">
                        <label for="exportFormato" class="block text-sm font-medium text-gray-700 mb-2">Formato</label>
                        <select name="formato" id="exportFormato" 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                    <input type="hidden" name="tipo" value="financeiro">
                    <input type="hidden" name="periodo" value="{{ $periodo }}">
                    <input type="hidden" name="ano" value="{{ $ano }}">
                    <input type="hidden" name="mes" value="{{ $mes }}">
                </form>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" 
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200" 
                        onclick="closeExportModal()">
                    Cancelar
                </button>
                <button type="submit" form="exportForm" 
                        class="px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <i class="fas fa-download mr-2"></i>
                    Exportar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Dados para os gráficos
const dadosGraficos = {
    evolucao: @json($dados['transacoes']->groupBy(function($item) {
        return $item->created_at->format('Y-m-d');
    })->map(function($group) {
        return $group->where('tipo', 'entrada')->sum('valor');
    })),
    status: {
        confirmado: @json($dados['resumo']['total_confirmado']),
        pendente: @json($dados['resumo']['total_pendente']),
        cancelado: @json($dados['transacoes']->where('status', 'cancelado')->sum('valor'))
    }
};

// Gráfico de evolução
const ctxEvolucao = document.getElementById('evolucaoChart').getContext('2d');
new Chart(ctxEvolucao, {
    type: 'line',
    data: {
        labels: Object.keys(dadosGraficos.evolucao),
        datasets: [{
            label: 'Arrecadação (R$)',
            data: Object.values(dadosGraficos.evolucao),
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.1,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'R$ ' + value.toLocaleString('pt-BR');
                    }
                }
            }
        }
    }
});

// Gráfico de status
const ctxStatus = document.getElementById('statusChart').getContext('2d');
new Chart(ctxStatus, {
    type: 'doughnut',
    data: {
        labels: ['Confirmado', 'Pendente', 'Cancelado'],
        datasets: [{
            data: [
                dadosGraficos.status.confirmado,
                dadosGraficos.status.pendente,
                dadosGraficos.status.cancelado
            ],
            backgroundColor: [
                'rgb(34, 197, 94)',
                'rgb(251, 191, 36)',
                'rgb(239, 68, 68)'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});

// Funções JavaScript
function togglePeriodoFields() {
    const periodo = document.getElementById('periodo').value;
    const anoField = document.getElementById('anoField');
    const mesField = document.getElementById('mesField');
    const dataInicioField = document.getElementById('dataInicioField');
    const dataFimField = document.getElementById('dataFimField');
    
    if (periodo === 'personalizado') {
        anoField.classList.add('hidden');
        mesField.classList.add('hidden');
        dataInicioField.classList.remove('hidden');
        dataFimField.classList.remove('hidden');
    } else {
        anoField.classList.remove('hidden');
        mesField.classList.remove('hidden');
        dataInicioField.classList.add('hidden');
        dataFimField.classList.add('hidden');
        
        if (periodo === 'ano') {
            mesField.classList.add('hidden');
        }
    }
}

function toggleExportDropdown() {
    const dropdown = document.getElementById('exportDropdown');
    dropdown.classList.toggle('hidden');
}

function exportTable() {
    const modal = document.getElementById('exportModal');
    modal.classList.remove('hidden');
}

function closeExportModal() {
    const modal = document.getElementById('exportModal');
    modal.classList.add('hidden');
}

// Fechar dropdown quando clicar fora
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('exportDropdown');
    const button = event.target.closest('button');
    
    if (!button || !button.id || button.id !== 'exportDropdown') {
        dropdown.classList.add('hidden');
    }
});

// Fechar modal quando clicar fora
document.getElementById('exportModal').addEventListener('click', function(event) {
    if (event.target === this) {
        closeExportModal();
    }
});

// Inicializar campos de período
document.addEventListener('DOMContentLoaded', function() {
    togglePeriodoFields();
});
</script>
@endpush

@push('styles')
<style>
@media print {
    .btn, .dropdown, .card-header .dropdown, #exportModal {
        display: none !important;
    }
    
    .card {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
    }
    
    .card-header {
        background-color: #f8f9fa !important;
        border-bottom: 1px solid #ddd !important;
    }
}
</style>
@endpush 