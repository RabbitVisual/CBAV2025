<div class="p-6 bg-white rounded-lg">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold text-gray-900">{{ __('Detalhes do Log') }}</h3>
        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <div class="space-y-6">
        <!-- Informações Básicas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-50 p-4 rounded-lg border">
                <p class="text-sm font-medium text-gray-600 mb-1">{{ __('ID do Log') }}</p>
                <p class="text-sm text-gray-900 font-mono">{{ $id }}</p>
            </div>
            
            <div class="bg-gray-50 p-4 rounded-lg border">
                <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Nível') }}</p>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $log['level_color'] }}">
                    <i class="{{ $log['level_icon'] }} mr-1"></i>
                    {{ strtoupper($log['level']) }}
                </span>
            </div>
            
            <div class="bg-gray-50 p-4 rounded-lg border">
                <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Data/Hora') }}</p>
                <p class="text-sm text-gray-900">{{ $log['datetime'] ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Mensagem Principal -->
        <div class="bg-gray-50 p-4 rounded-lg border">
            <p class="text-sm font-medium text-gray-600 mb-2">{{ __('Mensagem') }}</p>
            <div class="bg-white p-3 rounded border">
                <p class="text-sm text-gray-900 font-mono break-words whitespace-pre-wrap">{{ $log['message'] ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Contexto -->
        @if(isset($log['context']) && !empty($log['context']))
        <div class="bg-gray-50 p-4 rounded-lg border">
            <p class="text-sm font-medium text-gray-600 mb-2">{{ __('Contexto') }}</p>
            <div class="bg-white p-3 rounded border">
                <pre class="text-xs text-gray-800 overflow-x-auto">{{ json_encode($log['context'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </div>
        @endif

        <!-- Stack Trace -->
        @if(isset($log['stack_trace']) && !empty($log['stack_trace']))
        <div class="bg-gray-50 p-4 rounded-lg border">
            <p class="text-sm font-medium text-gray-600 mb-2">{{ __('Stack Trace') }}</p>
            <div class="bg-white p-3 rounded border max-h-64 overflow-y-auto">
                <pre class="text-xs text-gray-800 whitespace-pre-wrap">{{ $log['stack_trace'] }}</pre>
            </div>
        </div>
        @endif

        <!-- Log Bruto -->
        <div class="bg-gray-50 p-4 rounded-lg border">
            <p class="text-sm font-medium text-gray-600 mb-2">{{ __('Log Bruto') }}</p>
            <div class="bg-white p-3 rounded border">
                <pre class="text-xs text-gray-800 break-words whitespace-pre-wrap">{{ $raw }}</pre>
            </div>
        </div>

        <!-- Ações -->
        <div class="flex justify-end space-x-3 pt-4 border-t">
            <button onclick="copyToClipboard('{{ addslashes($raw) }}')" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-copy mr-2"></i>
                {{ __('Copiar') }}
            </button>
            <button onclick="closeModal()" 
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                {{ __('Fechar') }}
            </button>
        </div>
    </div>
</div>

<script>
// Função para copiar texto para a área de transferência
function copyToClipboard(text) {
    // Remover caracteres de escape
    text = text.replace(/\\'/g, "'");
    text = text.replace(/\\"/g, '"');
    text = text.replace(/\\\\/g, '\\');
    
    navigator.clipboard.writeText(text).then(function() {
        if (typeof showSuccessModal !== 'undefined') {
            showSuccessModal('{{ __("Log copiado para a área de transferência!") }}');
        } else {
            alert('{{ __("Log copiado para a área de transferência!") }}');
        }
    }).catch(function(err) {
        console.error('Erro ao copiar:', err);
        if (typeof showErrorModal !== 'undefined') {
            showErrorModal('{{ __("Erro ao copiar log") }}');
        } else {
            alert('{{ __("Erro ao copiar log") }}');
        }
    });
}

// Função para fechar o modal
function closeModal() {
    const modal = document.getElementById('logDetailModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}
</script> 