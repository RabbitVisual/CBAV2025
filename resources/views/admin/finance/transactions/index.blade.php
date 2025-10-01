@extends('layouts.admin')

@section('title', __('Transações') . ' - ' . \App\Models\Configuracao::get('app_name', 'CBAV'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Transações') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Gerencie todas as transações financeiras') }} - {{ \App\Models\Configuracao::get('app_description', 'Sistema de Gestão Ministerial') }}</p>
        </div>
        <div class="flex space-x-3">
            <div class="relative">
                <button onclick="exportarTransacoes()" 
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                    <i class="fas fa-download mr-2"></i>
                    {{ __('Exportar') }}
                </button>
                <div id="exportOptions" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 border border-gray-200">
                    <div class="py-1">
                        <a href="{{ route('admin.finance.transactions.export') }}?formato=excel" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-file-excel mr-2 text-green-600"></i>Excel (.xlsx)
                        </a>
                        <a href="{{ route('admin.finance.transactions.export') }}?formato=pdf" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-file-pdf mr-2 text-red-600"></i>PDF (.pdf)
                        </a>
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.finance.transactions.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i>
                {{ __('Nova Transação') }}
            </a>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-arrow-up text-green-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Receitas') }}</p>
                    <p class="text-2xl font-bold text-green-600">R$ {{ number_format($estatisticas['receitas'], 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-arrow-down text-red-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Despesas') }}</p>
                    <p class="text-2xl font-bold text-red-600">R$ {{ number_format($estatisticas['despesas'], 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-balance-scale text-blue-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Saldo') }}</p>
                    <p class="text-2xl font-bold {{ $estatisticas['saldo'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        R$ {{ number_format($estatisticas['saldo'], 2, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-list text-purple-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Total') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $transacoes->total() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.finance.transactions.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Busca -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Buscar') }}</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="{{ __('Descrição, membro...') }}">
                </div>

                <!-- Tipo -->
                <div>
                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Tipo') }}</label>
                    <select id="tipo" 
                            name="tipo"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('Todos') }}</option>
                        <option value="entrada" {{ request('tipo') == 'entrada' ? 'selected' : '' }}>{{ __('Entrada') }}</option>
                        <option value="saida" {{ request('tipo') == 'saida' ? 'selected' : '' }}>{{ __('Saída') }}</option>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Status') }}</label>
                    <select id="status" 
                            name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('Todos') }}</option>
                        <option value="confirmado" {{ request('status') == 'confirmado' ? 'selected' : '' }}>{{ __('Confirmado') }}</option>
                        <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>{{ __('Pendente') }}</option>
                        <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>{{ __('Cancelado') }}</option>
                    </select>
                </div>

                <!-- Período -->
                <div>
                    <label for="periodo" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Período') }}</label>
                    <select id="periodo" 
                            name="periodo"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('Todos') }}</option>
                        <option value="hoje" {{ request('periodo') == 'hoje' ? 'selected' : '' }}>{{ __('Hoje') }}</option>
                        <option value="semana" {{ request('periodo') == 'semana' ? 'selected' : '' }}>{{ __('Esta Semana') }}</option>
                        <option value="mes" {{ request('periodo') == 'mes' ? 'selected' : '' }}>{{ __('Este Mês') }}</option>
                        <option value="ano" {{ request('periodo') == 'ano' ? 'selected' : '' }}>{{ __('Este Ano') }}</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-between items-center">
                <div class="flex space-x-3">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-search mr-2"></i>
                        {{ __('Filtrar') }}
                    </button>
                    <a href="{{ route('admin.finance.transactions.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-times mr-2"></i>
                        {{ __('Limpar') }}
                    </a>
                </div>
                
                <div class="flex items-center space-x-2">
                    <label for="ordenacao" class="text-sm font-medium text-gray-700">{{ __('Ordenar por:') }}</label>
                    <select id="ordenacao" 
                            name="sort"
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="data_desc" {{ request('sort', 'data_desc') == 'data_desc' ? 'selected' : '' }}>{{ __('Data (Mais Recente)') }}</option>
                        <option value="data_asc" {{ request('sort') == 'data_asc' ? 'selected' : '' }}>{{ __('Data (Mais Antiga)') }}</option>
                        <option value="valor_desc" {{ request('sort') == 'valor_desc' ? 'selected' : '' }}>{{ __('Valor (Maior)') }}</option>
                        <option value="valor_asc" {{ request('sort') == 'valor_asc' ? 'selected' : '' }}>{{ __('Valor (Menor)') }}</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Ações em Lote -->
    @if(auth()->user()->hasRole('Super Admin'))
    <div id="acoes_lote" class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6 hidden">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-check-square text-yellow-600 mr-3"></i>
                <span class="text-yellow-800 font-medium" id="contador_selecionados">0 {{ __('itens selecionados') }}</span>
            </div>
            <div class="flex space-x-3">
                <button onclick="confirmarTransacoes()" 
                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                    <i class="fas fa-check mr-1"></i>
                    {{ __('Confirmar') }}
                </button>
                <button onclick="cancelarTransacoes()" 
                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                    <i class="fas fa-times mr-1"></i>
                    {{ __('Cancelar') }}
                </button>
                <button onclick="excluirTransacoes()" 
                        class="bg-red-800 hover:bg-red-900 text-white px-3 py-1 rounded text-sm">
                    <i class="fas fa-trash mr-1"></i>
                    {{ __('Excluir') }}
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Lista de Transações -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($transacoes->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @if(auth()->user()->hasRole('Super Admin'))
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" 
                                       id="select_all" 
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            @endif
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Transação') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Valor') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Membro') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Campanha') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Status') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Data') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Ações') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($transacoes as $transacao)
                            <tr class="hover:bg-gray-50">
                                @if(auth()->user()->hasRole('Super Admin'))
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" 
                                           class="transacao-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                           value="{{ $transacao->id }}">
                                </td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $transacao->descricao }}</div>
                                        <div class="text-sm text-gray-500">{{ ucfirst($transacao->categoria ?? __('Sem categoria')) }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium {{ $transacao->tipo == 'entrada' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transacao->tipo == 'entrada' ? '+' : '-' }}R$ {{ number_format($transacao->valor, 2, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ ucfirst($transacao->metodo_pagamento ?? __('N/A')) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($transacao->membro)
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                @if($transacao->membro->foto_existe)
                                                    <img class="h-8 w-8 rounded-full" src="{{ $transacao->membro->foto_url }}" alt="{{ $transacao->membro->nome }}">
                                                @else
                                                    <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                                        <span class="text-gray-600 text-xs font-medium">{{ $transacao->membro->iniciais }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $transacao->membro->nome }}</div>
                                                <div class="text-sm text-gray-500">{{ $transacao->membro->email }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-400">{{ __('Anônimo') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($transacao->campanha)
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $transacao->campanha->titulo }}</div>
                                            <div class="text-sm text-gray-500">{{ number_format($transacao->campanha->progresso, 1) }}%</div>
                                        </div>
                                    @else
                                        <span class="text-gray-400">{{ __('N/A') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($transacao->status == 'confirmado')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            {{ __('Confirmado') }}
                                        </span>
                                    @elseif($transacao->status == 'pendente')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ __('Pendente') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>
                                            {{ __('Cancelado') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transacao->data ? $transacao->data->format('d/m/Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.finance.transactions.show', $transacao) }}" 
                                           class="text-blue-600 hover:text-blue-900" 
                                           title="{{ __('Visualizar') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.finance.transactions.edit', $transacao) }}" 
                                           class="text-green-600 hover:text-green-900" 
                                           title="{{ __('Editar') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="excluirTransacao({{ $transacao->id }})" 
                                                class="text-red-600 hover:text-red-900" 
                                                title="{{ __('Excluir') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-receipt text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Nenhuma transação encontrada') }}</h3>
                <p class="text-gray-500 mb-6">{{ __('Não há transações para os filtros selecionados.') }}</p>
                <a href="{{ route('admin.finance.transactions.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    {{ __('Criar Transação') }}
                </a>
            </div>
        @endif
    </div>

    <!-- Paginação -->
    @if($transacoes->hasPages())
        <div class="mt-6">
            {{ $transacoes->links() }}
        </div>
    @endif

    <!-- Footer com Informações do Sistema -->
    <div class="mt-8 bg-gray-50 rounded-lg p-4">
        <div class="text-center text-sm text-gray-600">
            <p><strong>{{ \App\Models\Configuracao::get('app_name', 'CBAV') }}</strong> - {{ \App\Models\Configuracao::get('app_description', 'Sistema de Gestão Ministerial') }}</p>
            @if(\App\Models\Configuracao::get('contact_email'))
            <p class="mt-1"><i class="fas fa-envelope mr-1"></i>{{ \App\Models\Configuracao::get('contact_email') }}</p>
            @endif
            @if(\App\Models\Configuracao::get('contact_phone'))
            <p class="mt-1"><i class="fas fa-phone mr-1"></i>{{ \App\Models\Configuracao::get('contact_phone') }}</p>
            @endif
            @if(\App\Models\Configuracao::get('address'))
            <p class="mt-1"><i class="fas fa-map-marker-alt mr-1"></i>{{ \App\Models\Configuracao::get('address') }}</p>
            @endif
            <p class="mt-2 text-xs text-gray-500">Página gerada em {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</div>

<!-- Modal de Confirmação -->
<div id="modalConfirmacao" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-gray-900" id="modalTitulo">{{ __('Confirmar Ação') }}</h3>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-6" id="modalMensagem">
                    {{ __('Tem certeza que deseja executar esta ação?') }}
                </p>
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="cancelarModal()"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        {{ __('Cancelar') }}
                    </button>
                    <button type="button" 
                            id="confirmarAcao"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200">
                        {{ __('Confirmar') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let acaoAtual = '';
let transacaoId = null;

document.addEventListener('DOMContentLoaded', function() {
    // Controle de seleção de todas as transações
    const selectAll = document.getElementById('select_all');
    const checkboxes = document.querySelectorAll('.transacao-checkbox');
    
    selectAll.addEventListener('change', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        atualizarAcoesLote();
    });
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            atualizarAcoesLote();
            
            // Verificar se todos estão selecionados
            const todosSelecionados = Array.from(checkboxes).every(cb => cb.checked);
            selectAll.checked = todosSelecionados;
        });
    });
});

function atualizarAcoesLote() {
    @if(auth()->user()->hasRole('Super Admin'))
    const checkboxes = document.querySelectorAll('.transacao-checkbox:checked');
    const acoesLote = document.getElementById('acoes_lote');
    const contador = document.getElementById('contador_selecionados');
    
    if (checkboxes.length > 0) {
        acoesLote.classList.remove('hidden');
        contador.textContent = `${checkboxes.length} ${checkboxes.length === 1 ? 'item selecionado' : 'itens selecionados'}`;
    } else {
        acoesLote.classList.add('hidden');
    }
    @endif
}

function confirmarTransacoes() {
    @if(auth()->user()->hasRole('Super Admin'))
    const checkboxes = document.querySelectorAll('.transacao-checkbox:checked');
    if (checkboxes.length === 0) return;
    
    acaoAtual = 'confirmar';
    mostrarModal('Confirmar Transações', `Deseja confirmar ${checkboxes.length} transação(ões)?`);
    @else
    alert('Apenas Super Administradores podem executar ações em lote.');
    @endif
}

function cancelarTransacoes() {
    @if(auth()->user()->hasRole('Super Admin'))
    const checkboxes = document.querySelectorAll('.transacao-checkbox:checked');
    if (checkboxes.length === 0) return;
    
    acaoAtual = 'cancelar';
    mostrarModal('Cancelar Transações', `Deseja cancelar ${checkboxes.length} transação(ões)?`);
    @else
    alert('Apenas Super Administradores podem executar ações em lote.');
    @endif
}

function excluirTransacoes() {
    @if(auth()->user()->hasRole('Super Admin'))
    const checkboxes = document.querySelectorAll('.transacao-checkbox:checked');
    if (checkboxes.length === 0) return;
    
    acaoAtual = 'excluir';
    mostrarModal('Excluir Transações', `Deseja excluir ${checkboxes.length} transação(ões)? Esta ação não pode ser desfeita.`);
    @else
    alert('Apenas Super Administradores podem executar ações em lote.');
    @endif
}

function excluirTransacao(id) {
    transacaoId = id;
    acaoAtual = 'excluir_individual';
    mostrarModal('Excluir Transação', 'Deseja excluir esta transação? Esta ação não pode ser desfeita.');
}

function mostrarModal(titulo, mensagem) {
    document.getElementById('modalTitulo').textContent = titulo;
    document.getElementById('modalMensagem').textContent = mensagem;
    document.getElementById('modalConfirmacao').classList.remove('hidden');
    
    document.getElementById('confirmarAcao').onclick = executarAcao;
}

function cancelarModal() {
    document.getElementById('modalConfirmacao').classList.add('hidden');
    acaoAtual = '';
    transacaoId = null;
}

function executarAcao() {
    if (acaoAtual === 'excluir_individual') {
        window.location.href = `/admin/finance/transactions/${transacaoId}/delete`;
    } else if (acaoAtual === 'excluir') {
        @if(auth()->user()->hasRole('Super Admin'))
        // Executar exclusão em lote
        const checkboxes = document.querySelectorAll('.transacao-checkbox:checked');
        const ids = Array.from(checkboxes).map(cb => cb.value);
        
        // Criar formulário para enviar os IDs
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.finance.transactions.bulk-delete") }}';
        
        // Adicionar CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Adicionar IDs das transações
        ids.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            form.appendChild(input);
        });
        
        // Adicionar formulário ao DOM e enviar
        document.body.appendChild(form);
        form.submit();
        @else
        alert('Apenas Super Administradores podem executar ações em lote.');
        @endif
    } else {
        // Outras ações em lote (confirmar, cancelar, etc.)
        @if(auth()->user()->hasRole('Super Admin'))
        const checkboxes = document.querySelectorAll('.transacao-checkbox:checked');
        const ids = Array.from(checkboxes).map(cb => cb.value);
        
        console.log(`Executando ação: ${acaoAtual}`, ids);
        // TODO: Implementar outras ações em lote
        @else
        alert('Apenas Super Administradores podem executar ações em lote.');
        @endif
    }
    
    cancelarModal();
}

function exportarTransacoes() {
    const exportOptions = document.getElementById('exportOptions');
    exportOptions.classList.toggle('hidden');
}

// Fechar dropdown quando clicar fora
document.addEventListener('click', function(event) {
    const exportOptions = document.getElementById('exportOptions');
    const exportButton = event.target.closest('button');
    
    if (!exportButton || !exportButton.onclick || !exportButton.onclick.toString().includes('exportarTransacoes')) {
        exportOptions.classList.add('hidden');
    }
});

function exportarComFormato(formato) {
    const params = new URLSearchParams(window.location.search);
    params.append('formato', formato);
    window.open(`{{ route('admin.finance.transactions.export') }}?${params.toString()}`, '_blank');
}
</script>
@endpush
@endsection 