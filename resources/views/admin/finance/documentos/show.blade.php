@extends('layouts.admin')

@section('title', 'Visualizar Documento de Baixa - Gestão Financeira')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Cabeçalho -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Documento de Baixa</h1>
            <p class="text-gray-600">Detalhes do documento para comprovante de IR</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.finance.documentos.edit', $documento) }}" 
               class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <a href="{{ route('admin.finance.documentos.pdf', $documento) }}" 
               class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-file-pdf mr-2"></i>Gerar PDF
            </a>
            <a href="{{ route('admin.finance.documentos.index') }}" 
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>

    <!-- Informações Principais -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Detalhes do Documento -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informações do Documento</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Protocolo RF -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Protocolo RF</label>
                            <p class="text-lg font-semibold text-blue-600">{{ $documento->protocolo_receita }}</p>
                        </div>

                        <!-- Tipo de Documento -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Documento</label>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ App\Models\DocumentoBaixa::TIPOS_DOCUMENTO[$documento->tipo_documento] ?? $documento->tipo_documento }}
                            </p>
                        </div>

                        <!-- Número do Documento -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Número do Documento</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $documento->numero_documento }}</p>
                        </div>

                        <!-- Ano Exercício -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ano Exercício</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $documento->ano_exercicio }}</p>
                        </div>

                        <!-- Data de Emissão -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Data de Emissão</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $documento->data_emissao->format('d/m/Y') }}</p>
                        </div>

                        <!-- Data de Vencimento -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Data de Vencimento</label>
                            <p class="text-lg font-semibold {{ $documento->isVencido() ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $documento->data_vencimento ? $documento->data_vencimento->format('d/m/Y') : 'N/A' }}
                            </p>
                        </div>

                        <!-- Valor do Documento -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Valor do Documento</label>
                            <p class="text-2xl font-bold text-green-600">R$ {{ number_format($documento->valor_documento, 2, ',', '.') }}</p>
                        </div>

                        <!-- Valor Pago -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Valor Pago</label>
                            <p class="text-2xl font-bold {{ $documento->valor_pago > 0 ? 'text-blue-600' : 'text-gray-400' }}">
                                R$ {{ number_format($documento->valor_pago, 2, ',', '.') }}
                            </p>
                        </div>

                        <!-- Status -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                {{ $documento->status === 'PAGO' ? 'bg-green-100 text-green-800' : 
                                   ($documento->status === 'PENDENTE' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($documento->status === 'VENCIDO' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ App\Models\DocumentoBaixa::STATUS[$documento->status] ?? $documento->status }}
                            </span>
                        </div>

                        <!-- Hash do Documento -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Hash de Validação</label>
                            <p class="text-sm font-mono text-gray-600 break-all">{{ $documento->hash_documento }}</p>
                        </div>

                        <!-- Observações -->
                        @if($documento->observacoes)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                                <p class="text-gray-900">{{ $documento->observacoes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar com Ações e Informações -->
        <div class="space-y-6">
            <!-- Ações Rápidas -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Ações Rápidas</h3>
                </div>
                <div class="p-6 space-y-3">
                    @if($documento->status !== 'PAGO')
                        <button onclick="marcarComoPago()" 
                                class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-check mr-2"></i>Marcar como Pago
                        </button>
                    @endif

                    <button onclick="gerarCodigoBarras()" 
                            class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-barcode mr-2"></i>Gerar Código de Barras
                    </button>

                    <button onclick="validarDocumento()" 
                            class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                        <i class="fas fa-check-circle mr-2"></i>Validar Documento
                    </button>

                    @if($documento->isVencido())
                        <button onclick="calcularMultaJuros()" 
                                class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-calculator mr-2"></i>Calcular Multa/Juros
                        </button>
                    @endif
                </div>
            </div>

            <!-- Informações da Transação -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Transação Relacionada</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ID da Transação</label>
                            <p class="text-lg font-semibold text-gray-900">#{{ $documento->transacao->id }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Membro</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $documento->transacao->membro->nome ?? 'Anônimo' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Campanha</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $documento->transacao->campanha->nome ?? 'Doação Geral' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Valor da Transação</label>
                            <p class="text-lg font-semibold text-green-600">R$ {{ number_format($documento->transacao->valor, 2, ',', '.') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Data da Transação</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $documento->transacao->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações Legais -->
            <div class="bg-blue-50 rounded-lg p-6">
                <h4 class="text-lg font-medium text-blue-900 mb-4">
                    <i class="fas fa-shield-alt mr-2"></i>Validação Legal
                </h4>
                <div class="text-sm text-blue-800 space-y-2">
                    <p><strong>•</strong> Documento válido para declaração de IR</p>
                    <p><strong>•</strong> Protocolo RF: {{ $documento->protocolo_receita }}</p>
                    <p><strong>•</strong> Hash de integridade: {{ substr($documento->hash_documento, 0, 16) }}...</p>
                    <p><strong>•</strong> Gerado em: {{ $documento->created_at->format('d/m/Y H:i:s') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Arquivo Comprovante -->
    @if($documento->arquivo_comprovante)
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Arquivo Comprovante</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-file-pdf text-4xl text-red-500"></i>
                    <div>
                        <p class="text-lg font-semibold text-gray-900">Comprovante do Documento</p>
                        <p class="text-sm text-gray-600">{{ basename($documento->arquivo_comprovante) }}</p>
                    </div>
                    <a href="{{ Storage::url($documento->arquivo_comprovante) }}" target="_blank"
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-download mr-2"></i>Baixar
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Modal para Marcar como Pago -->
<div id="modalPago" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Marcar como Pago</h3>
            </div>
            <form id="formPago" action="{{ route('admin.finance.documentos.marcar-pago', $documento) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="valor_pago" class="block text-sm font-medium text-gray-700 mb-2">
                            Valor Pago <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">R$</span>
                            <input type="number" name="valor_pago" id="valor_pago" 
                                   value="{{ $documento->valor_documento }}" step="0.01" min="0.01" required
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div>
                        <label for="data_pagamento" class="block text-sm font-medium text-gray-700 mb-2">
                            Data do Pagamento <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="data_pagamento" id="data_pagamento" 
                               value="{{ date('Y-m-d') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="comprovante_pagamento" class="block text-sm font-medium text-gray-700 mb-2">
                            Comprovante de Pagamento
                        </label>
                        <input type="file" name="comprovante_pagamento" id="comprovante_pagamento"
                               accept=".pdf,.jpg,.jpeg,.png"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" onclick="fecharModal()" 
                            class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-check mr-2"></i>Confirmar Pagamento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('components.modals')

@push('scripts')
<script>
function marcarComoPago() {
    document.getElementById('modalPago').classList.remove('hidden');
}

function fecharModal() {
    document.getElementById('modalPago').classList.add('hidden');
}

function gerarCodigoBarras() {
    showInfoModal('Gerando Código de Barras', 'Aguarde, estamos gerando o código de barras...');
    
    fetch(`{{ route('admin.finance.documentos.codigo-barras', $documento) }}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessModal('Código de Barras Gerado!');
                showToast('Código de barras gerado com sucesso!', 'success');
                
                // Mostrar detalhes em um modal específico
                showDocumentValidationModal(
                    'Código de Barras',
                    'Código de barras gerado com sucesso para pagamento.',
                    `<div class="mt-2 text-center">
                        <div class="mb-4">
                            ${data.codigo_barras_svg}
                        </div>
                        <div class="text-left">
                            <strong>Detalhes:</strong><br>
                            • Código: ${data.codigo_barras}<br>
                            • Valor: R$ ${data.valor}<br>
                            • Vencimento: ${data.vencimento}<br>
                            • Documento: ${data.numero_documento}
                        </div>
                    </div>`
                );
            } else {
                showErrorModal('Erro', 'Não foi possível gerar o código de barras.');
            }
        })
        .catch(error => {
            showErrorModal('Erro de Conexão', 'Erro ao conectar com o servidor. Tente novamente.');
        });
}

function validarDocumento() {
    showInfoModal('Validando Documento', 'Aguarde, estamos validando o documento...');
    
    fetch(`{{ route('admin.finance.documentos.validar', $documento) }}`)
        .then(response => response.json())
        .then(data => {
            if (data.valido) {
                showDocumentValidationModal(
                    'Documento Válido',
                    'O documento foi validado com sucesso e está pronto para uso.',
                    `<div class="mt-2 text-left">
                        <strong>Detalhes da Validação:</strong><br>
                        • Protocolo RF: {{ $documento->protocolo_receita }}<br>
                        • Número: {{ $documento->numero_documento }}<br>
                        • Status: {{ App\Models\DocumentoBaixa::STATUS[$documento->status] ?? $documento->status }}<br>
                        • Hash: {{ substr($documento->hash_documento, 0, 16) }}...<br>
                        • Data de Validação: ${new Date().toLocaleDateString('pt-BR')}
                    </div>`
                );
                showToast('Documento validado com sucesso!', 'success');
            } else {
                showDocumentError(data.mensagem || 'O documento não pôde ser validado.');
            }
        })
        .catch(error => {
            showErrorModal('Erro de Validação', 'Erro ao validar o documento. Tente novamente.');
        });
}

function calcularMultaJuros() {
    showInfoModal('Calculando Multa e Juros', 'Aguarde, estamos calculando os valores...');
    
    fetch(`{{ route('admin.finance.documentos.calcular-multa-juros', $documento) }}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showDocumentWarningModal(
                    'Cálculo de Multa e Juros',
                    'O documento está vencido e possui multa e juros aplicados.'
                );
                
                // Mostrar detalhes em um modal específico
                showDocumentValidationModal(
                    'Cálculo Concluído',
                    'Multa e juros calculados com sucesso.',
                    `<div class="mt-2 text-left">
                        <strong>Detalhes do Cálculo:</strong><br>
                        • Valor Original: R$ ${data.valor_original}<br>
                        • Multa e Juros: R$ ${data.multa_juros.toFixed(2)}<br>
                        • Valor Total: R$ ${data.valor_total.toFixed(2)}<br>
                        • Dias Vencido: ${Math.round(data.dias_vencido)}<br>
                        • Taxa de Juros: ${data.taxa_juros}% ao mês
                    </div>`
                );
                
                showToast('Cálculo de multa e juros concluído!', 'warning');
            } else {
                showErrorModal('Erro no Cálculo', data.mensagem || 'Não foi possível calcular multa e juros.');
            }
        })
        .catch(error => {
            showErrorModal('Erro de Cálculo', 'Erro ao calcular multa e juros. Tente novamente.');
        });
}

// Processar formulário de pagamento
document.getElementById('formPago').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const valorPago = document.getElementById('valor_pago').value;
    const dataPagamento = document.getElementById('data_pagamento').value;
    
    if (!valorPago || !dataPagamento) {
        showErrorModal('Campos Obrigatórios', 'Por favor, preencha todos os campos obrigatórios.');
        return;
    }
    
    showConfirmModal(
        'Confirmar Pagamento',
        `Tem certeza que deseja marcar este documento como pago?\n\nValor: R$ ${valorPago}\nData: ${dataPagamento}`,
        function() {
            // Submeter o formulário
            document.getElementById('formPago').submit();
        }
    );
});

// Fechar modal ao clicar fora
document.getElementById('modalPago').addEventListener('click', function(e) {
    if (e.target === this) {
        fecharModal();
    }
});

// Mostrar toast de sucesso se houver mensagem de sucesso
@if(session('success'))
    showToast('{{ session('success') }}', 'success');
@endif

// Mostrar toast de erro se houver mensagem de erro
@if(session('error'))
    showToast('{{ session('error') }}', 'error');
@endif

// Mostrar toast de aviso se houver mensagem de aviso
@if(session('warning'))
    showToast('{{ session('warning') }}', 'warning');
@endif

// Mostrar toast de informação se houver mensagem de info
@if(session('info'))
    showToast('{{ session('info') }}', 'info');
@endif
</script>
@endpush
@endsection 