@extends('layouts.admin')

@section('title', __('Logs do Sistema'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Logs do Sistema') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Visualize e gerencie os logs do sistema') }}</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="limparLogs()" 
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-trash mr-2"></i>
                {{ __('Limpar Logs') }}
            </button>
            <button onclick="limparLogsAntigos()" 
                    class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-clock mr-2"></i>
                {{ __('Limpar Antigos') }}
            </button>
            <button onclick="exportarLogs()" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-download mr-2"></i>
                {{ __('Exportar') }}
            </button>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Total de Logs') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['total'] }}</p>
                    <p class="text-xs text-gray-500">{{ __('Últimas 24h') }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Avisos') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['warnings'] }}</p>
                    <p class="text-xs text-gray-500">{{ __('Requerem atenção') }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="p-2 bg-red-100 rounded-lg">
                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Erros') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['errors'] }}</p>
                    <p class="text-xs text-gray-500">{{ __('Críticos') }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <i class="fas fa-hdd text-purple-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Tamanho') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['tamanho'] }}</p>
                    <p class="text-xs text-gray-500">{{ __('Arquivos de log') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.system.logs.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Arquivo de Log -->
                <div>
                    <label for="arquivo" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Arquivo') }}</label>
                    <select id="arquivo" 
                            name="arquivo"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @foreach($arquivos as $arquivo)
                            <option value="{{ $arquivo }}" {{ request('arquivo', 'laravel.log') == $arquivo ? 'selected' : '' }}>
                                {{ $arquivo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Nível -->
                <div>
                    <label for="nivel" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Nível') }}</label>
                    <select id="nivel" 
                            name="nivel"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('Todos') }}</option>
                        <option value="emergency" {{ request('nivel') == 'emergency' ? 'selected' : '' }}>{{ __('Emergency') }}</option>
                        <option value="alert" {{ request('nivel') == 'alert' ? 'selected' : '' }}>{{ __('Alert') }}</option>
                        <option value="critical" {{ request('nivel') == 'critical' ? 'selected' : '' }}>{{ __('Critical') }}</option>
                        <option value="error" {{ request('nivel') == 'error' ? 'selected' : '' }}>{{ __('Error') }}</option>
                        <option value="warning" {{ request('nivel') == 'warning' ? 'selected' : '' }}>{{ __('Warning') }}</option>
                        <option value="notice" {{ request('nivel') == 'notice' ? 'selected' : '' }}>{{ __('Notice') }}</option>
                        <option value="info" {{ request('nivel') == 'info' ? 'selected' : '' }}>{{ __('Info') }}</option>
                        <option value="debug" {{ request('nivel') == 'debug' ? 'selected' : '' }}>{{ __('Debug') }}</option>
                    </select>
                </div>

                <!-- Busca -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Buscar') }}</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="{{ __('Mensagem, contexto...') }}">
                </div>

                <!-- Data -->
                <div>
                    <label for="data" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Data') }}</label>
                    <input type="date" 
                           id="data" 
                           name="data" 
                           value="{{ request('data') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="flex justify-between items-center">
                <div class="flex space-x-3">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-search mr-2"></i>
                        {{ __('Filtrar') }}
                    </button>
                    <a href="{{ route('admin.system.logs.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-times mr-2"></i>
                        {{ __('Limpar') }}
                    </a>
                </div>
                
                <div class="flex items-center space-x-2">
                    <label for="linhas" class="text-sm font-medium text-gray-700">{{ __('Linhas:') }}</label>
                    <select id="linhas" 
                            name="linhas"
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="100" {{ request('linhas', '100') == '100' ? 'selected' : '' }}>100</option>
                        <option value="500" {{ request('linhas', '100') == '500' ? 'selected' : '' }}>500</option>
                        <option value="1000" {{ request('linhas', '100') == '1000' ? 'selected' : '' }}>1000</option>
                        <option value="all" {{ request('linhas', '100') == 'all' ? 'selected' : '' }}>{{ __('Todas') }}</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Logs -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @php
            $totalLogs = 0;
            foreach($logs as $fileData) {
                $totalLogs += count($fileData['lines']);
            }
        @endphp
        @if($totalLogs > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Data/Hora') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Nível') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Mensagem') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Contexto') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Ações') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($logs as $fileName => $fileData)
                            @foreach($fileData['lines'] as $index => $log)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $log['datetime'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $log['level_color'] }}">
                                            <i class="{{ $log['level_icon'] }} mr-1"></i>
                                            {{ strtoupper($log['level']) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <div class="max-w-xs truncate" title="{{ $log['message'] }}">
                                            {{ $log['message'] }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        @if(isset($log['context']))
                                            <span class="text-xs bg-gray-100 px-2 py-1 rounded">
                                                {{ $log['context'] }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">{{ __('N/A') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button onclick="visualizarLog('{{ $index }}')" 
                                                class="text-blue-600 hover:text-blue-900" 
                                                title="{{ __('Visualizar Detalhes') }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-file-alt text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Nenhum log encontrado') }}</h3>
                <p class="text-gray-500 mb-6">{{ __('Não há logs para os filtros selecionados.') }}</p>
            </div>
        @endif
    </div>

    <!-- Paginação -->
    @if(isset($pagination) && $pagination->hasPages())
        <div class="mt-6">
            {{ $pagination->links() }}
        </div>
    @endif
</div>

<!-- Modal de Detalhes do Log -->
<div id="logDetailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div id="logDetailContent" class="p-6">
                <!-- Conteúdo do log será inserido aqui -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
@include('components.modals')
<script>
// Funções para gerenciar logs
function limparLogs() {
    if (typeof showConfirmModal === 'undefined') {
        if (confirm('{{ __("Deseja limpar todos os logs? Esta ação não pode ser desfeita.") }}')) {
            executarLimpezaLogs();
        }
        return;
    }
    
    showConfirmModal(
        '{{ __("Limpar Logs") }}',
        '{{ __("Deseja limpar todos os logs? Esta ação não pode ser desfeita.") }}',
        function() {
            executarLimpezaLogs();
        }
    );
}

function executarLimpezaLogs() {
    fetch('{{ route("admin.system.logs.clear") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            if (typeof showSuccessModal !== 'undefined') {
                showSuccessModal(data.message);
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                alert(data.message);
                location.reload();
            }
        } else {
            if (typeof showErrorModal !== 'undefined') {
                showErrorModal(data.message || '{{ __("Erro ao limpar logs.") }}');
            } else {
                alert(data.message || '{{ __("Erro ao limpar logs.") }}');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof showErrorModal !== 'undefined') {
            showErrorModal('{{ __("Erro ao limpar logs. Verifique a conexão.") }}');
        } else {
            alert('{{ __("Erro ao limpar logs. Verifique a conexão.") }}');
        }
    });
}

function limparLogsAntigos() {
    if (typeof showConfirmModal === 'undefined') {
        if (confirm('{{ __("Deseja limpar todos os logs antigos? Esta ação não pode ser desfeita.") }}')) {
            executarLimpezaLogsAntigos();
        }
        return;
    }
    
    showConfirmModal(
        '{{ __("Limpar Logs Antigos") }}',
        '{{ __("Deseja limpar todos os logs antigos? Esta ação não pode ser desfeita.") }}',
        function() {
            executarLimpezaLogsAntigos();
        }
    );
}

function executarLimpezaLogsAntigos() {
    fetch('{{ route("admin.system.logs.clear-old") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            if (typeof showSuccessModal !== 'undefined') {
                showSuccessModal(data.message);
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                alert(data.message);
                location.reload();
            }
        } else {
            if (typeof showErrorModal !== 'undefined') {
                showErrorModal(data.message || '{{ __("Erro ao limpar logs antigos.") }}');
            } else {
                alert(data.message || '{{ __("Erro ao limpar logs antigos.") }}');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof showErrorModal !== 'undefined') {
            showErrorModal('{{ __("Erro ao limpar logs antigos. Verifique a conexão.") }}');
        } else {
            alert('{{ __("Erro ao limpar logs antigos. Verifique a conexão.") }}');
        }
    });
}

function exportarLogs() {
    const params = new URLSearchParams(window.location.search);
    window.open(`{{ route('admin.system.logs.export') }}?${params.toString()}`, '_blank');
}

function visualizarLog(logId) {
    const params = new URLSearchParams(window.location.search);
    const arquivo = params.get('arquivo') || 'laravel.log';
    
    fetch(`{{ route('admin.system.logs.show') }}?id=${logId}&arquivo=${arquivo}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            document.getElementById('logDetailContent').innerHTML = data.content;
            document.getElementById('logDetailModal').classList.remove('hidden');
        } else {
            if (typeof showErrorModal !== 'undefined') {
                showErrorModal(data.message || '{{ __("Erro ao carregar detalhes do log.") }}');
            } else {
                alert(data.message || '{{ __("Erro ao carregar detalhes do log.") }}');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof showErrorModal !== 'undefined') {
            showErrorModal('{{ __("Erro ao carregar detalhes do log. Verifique a conexão.") }}');
        } else {
            alert('{{ __("Erro ao carregar detalhes do log. Verifique a conexão.") }}');
        }
    });
}

function fecharModal() {
    document.getElementById('logDetailModal').classList.add('hidden');
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        if (typeof showSuccessModal !== 'undefined') {
            showSuccessModal('{{ __("Log copiado para a área de transferência!") }}');
        } else {
            alert('{{ __("Log copiado para a área de transferência!") }}');
        }
    }).catch(function(err) {
        if (typeof showErrorModal !== 'undefined') {
            showErrorModal('{{ __("Erro ao copiar log") }}');
        } else {
            alert('{{ __("Erro ao copiar log") }}');
        }
    });
}

function closeModal() {
    document.getElementById('logDetailModal').classList.add('hidden');
}

// Fechar modal clicando fora
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('logDetailModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });
});

// Auto-refresh a cada 30 segundos (apenas se não houver modal aberto)
setInterval(function() {
    if (!document.getElementById('logDetailModal').classList.contains('hidden')) {
        return; // Não atualizar se o modal estiver aberto
    }
    
    const currentUrl = window.location.href;
    if (currentUrl.includes('admin/system/logs')) {
        location.reload();
    }
}, 30000);
</script>
@endpush
@endsection 