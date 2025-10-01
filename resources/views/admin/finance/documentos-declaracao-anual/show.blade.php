@extends('layouts.admin')

@section('title', 'Visualizar Documento de Declaração Anual - Gestão Financeira')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Cabeçalho -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Documento de Declaração Anual</h1>
            <p class="text-gray-600">Detalhes do documento para declaração anual da igreja</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.finance.documentos-declaracao-anual.edit', $documento) }}" 
               class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <a href="{{ route('admin.finance.documentos-declaracao-anual.pdf', $documento) }}" 
               class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-file-pdf mr-2"></i>Gerar PDF
            </a>
            <a href="{{ route('admin.finance.documentos-declaracao-anual.index') }}" 
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
                                {{ App\Models\DocumentoDeclaracaoAnual::TIPOS_DOCUMENTO[$documento->tipo_documento] ?? $documento->tipo_documento }}
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

                        <!-- Valor Total -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Valor Total</label>
                            <p class="text-2xl font-bold text-green-600">R$ {{ number_format($documento->valor_total, 2, ',', '.') }}</p>
                        </div>

                        <!-- Valor de Doações -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Valor de Doações</label>
                            <p class="text-lg font-semibold text-blue-600">R$ {{ number_format($documento->valor_doacoes, 2, ',', '.') }}</p>
                        </div>

                        <!-- Valor de Dízimos -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Valor de Dízimos</label>
                            <p class="text-lg font-semibold text-purple-600">R$ {{ number_format($documento->valor_dizimos, 2, ',', '.') }}</p>
                        </div>

                        <!-- Valor Outros -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Valor Outros</label>
                            <p class="text-lg font-semibold text-orange-600">R$ {{ number_format($documento->valor_outros, 2, ',', '.') }}</p>
                        </div>

                        <!-- Status -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                {{ $documento->status === 'VALIDADO' ? 'bg-green-100 text-green-800' : 
                                   ($documento->status === 'PENDENTE' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($documento->status === 'VENCIDO' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ App\Models\DocumentoDeclaracaoAnual::STATUS[$documento->status] ?? $documento->status }}
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
                    @if($documento->status !== 'VALIDADO')
                        <button onclick="validarDocumento()" 
                                class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-check-circle mr-2"></i>Validar Documento
                        </button>
                    @endif

                    <button onclick="gerarCodigoBarras()" 
                            class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-barcode mr-2"></i>Gerar Código de Barras
                    </button>

                    <button onclick="gerarQRCode()" 
                            class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                        <i class="fas fa-qrcode mr-2"></i>Gerar QR Code
                    </button>

                    @if($documento->isVencido())
                        <button onclick="calcularMultaJuros()" 
                                class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-calculator mr-2"></i>Calcular Multa/Juros
                        </button>
                    @endif
                </div>
            </div>

            <!-- Informações da Igreja -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informações da Igreja</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nome da Igreja</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $documento->igreja->nome ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">CNPJ</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $documento->igreja->cnpj_formatado ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Endereço</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $documento->igreja->endereco_completo ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pastor Responsável</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $documento->igreja->pastor_responsavel ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Entidade</label>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ App\Models\Igreja::TIPOS_ENTIDADE[$documento->igreja->tipo_entidade] ?? $documento->igreja->tipo_entidade }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seção de Cálculo de Multa e Juros -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Cálculo de Multa e Juros</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Valor Original</label>
                                <p class="text-lg font-semibold text-gray-900">R$ {{ number_format($documento->valor_total, 2, ',', '.') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status de Vencimento</label>
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $documento->isVencido() ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $documento->isVencido() ? 'Vencido' : 'Em dia' }}
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <button onclick="calcularMultaJuros()" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                <i class="fas fa-calculator mr-2"></i>Calcular Multa e Juros
                            </button>
                        </div>
                        
                        <div id="resultado-calculo" class="hidden">
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <h4 class="font-semibold text-yellow-800 mb-3">Detalhes do Cálculo:</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Valor Original:</p>
                                        <p class="font-semibold" id="valor-original">-</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Multa (2%):</p>
                                        <p class="font-semibold text-red-600" id="valor-multa">-</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Juros (1% ao mês):</p>
                                        <p class="font-semibold text-red-600" id="valor-juros">-</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Total com Multa/Juros:</p>
                                        <p class="font-semibold text-red-800" id="valor-total">-</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Dias Vencido:</p>
                                        <p class="font-semibold" id="dias-vencido">-</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Taxa de Juros:</p>
                                        <p class="font-semibold">1% ao mês</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Validação Legal -->
            <div class="bg-green-50 rounded-lg p-6">
                <h4 class="text-lg font-medium text-green-900 mb-4">
                    <i class="fas fa-shield-alt mr-2"></i>Validação Legal
                </h4>
                <div class="text-sm text-green-800 space-y-2">
                    <p><strong>•</strong> Documento válido para declaração anual</p>
                    <p><strong>•</strong> Protocolo RF: {{ $documento->protocolo_receita }}</p>
                    <p><strong>•</strong> Hash de integridade: {{ substr($documento->hash_documento, 0, 16) }}...</p>
                    <p><strong>•</strong> Certificado digital: {{ substr($documento->certificado_digital, 0, 16) }}...</p>
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

@include('components.modals')

@push('scripts')
<script>
function validarDocumento() {
    showInfoModal('Validando Documento', 'Aguarde, estamos validando o documento...');
    
    fetch(`{{ route('admin.finance.documentos-declaracao-anual.validar', $documento) }}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.valido) {
                showDocumentValidationModal(
                    'Documento Válido',
                    'O documento foi validado com sucesso e está pronto para uso na declaração anual.',
                    `<div class="mt-2 text-left">
                        <strong>Detalhes da Validação:</strong><br>
                        • Protocolo RF: {{ $documento->protocolo_receita }}<br>
                        • Número: {{ $documento->numero_documento }}<br>
                        • Status: Validado<br>
                        • Hash: {{ substr($documento->hash_documento, 0, 16) }}...<br>
                        • Data de Validação: ${new Date().toLocaleDateString('pt-BR')}<br>
                        • Validado por: {{ auth()->user()->name }}
                    </div>`
                );
                showToast('Documento validado com sucesso!', 'success');
                
                // Recarregar página após validação
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            } else {
                showDocumentError(data.mensagem || 'O documento não pôde ser validado.');
            }
        })
        .catch(error => {
            showErrorModal('Erro de Validação', 'Erro ao validar o documento. Tente novamente.');
        });
}

function gerarCodigoBarras() {
    showInfoModal('Gerando Código de Barras', 'Aguarde, estamos gerando o código de barras...');
    
    fetch(`{{ route('admin.finance.documentos-declaracao-anual.codigo-barras', $documento) }}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessModal('Código de Barras Gerado!');
                showToast('Código de barras gerado com sucesso!', 'success');
                
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

function gerarQRCode() {
    showInfoModal('Gerando QR Code', 'Aguarde, estamos gerando o QR Code...');
    
    fetch(`{{ route('admin.finance.documentos-declaracao-anual.qr-code', $documento) }}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showDocumentValidationModal(
                    'QR Code Gerado',
                    'QR Code gerado com sucesso para validação.',
                    `<div class="mt-2 text-center">
                        <div class="mb-4">
                            ${data.qr_code_svg}
                        </div>
                        <div class="text-left">
                            <strong>Detalhes do QR Code:</strong><br>
                            • Protocolo: ${data.protocolo}<br>
                            • Hash: ${data.hash.substring(0, 16)}...<br>
                            • Tipo: ${data.tipo}<br>
                            • Ano: ${data.ano}<br>
                            • Valor: R$ ${data.valor}<br>
                            • Data: ${data.data}
                        </div>
                    </div>`
                );
                showToast('QR Code gerado com sucesso!', 'success');
            } else {
                showErrorModal('Erro', 'Não foi possível gerar o QR Code.');
            }
        })
        .catch(error => {
            showErrorModal('Erro de Conexão', 'Erro ao conectar com o servidor. Tente novamente.');
        });
}

function calcularMultaJuros() {
    showInfoModal('Calculando Multa e Juros', 'Aguarde, estamos calculando os valores...');
    
    fetch(`{{ route('admin.finance.documentos-declaracao-anual.calcular-multa-juros', $documento) }}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mostrar resultados na página
                document.getElementById('valor-original').textContent = `R$ ${data.valor_original}`;
                document.getElementById('valor-multa').textContent = `R$ ${data.multa}`;
                document.getElementById('valor-juros').textContent = `R$ ${data.juros}`;
                document.getElementById('valor-total').textContent = `R$ ${data.valor_total}`;
                document.getElementById('dias-vencido').textContent = Math.round(data.dias_vencido);
                
                // Mostrar a seção de resultados
                document.getElementById('resultado-calculo').classList.remove('hidden');
                
                // Scroll para a seção de resultados
                document.getElementById('resultado-calculo').scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
                
                showToast('Cálculo realizado com sucesso!', 'success');
            } else {
                showErrorModal('Erro no Cálculo', data.mensagem || 'Não foi possível calcular multa e juros.');
            }
        })
        .catch(error => {
            showErrorModal('Erro de Cálculo', 'Erro ao calcular multa e juros. Tente novamente.');
        });
}

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