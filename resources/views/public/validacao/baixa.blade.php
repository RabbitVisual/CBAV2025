<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validação de Documento de Baixa - CBAV Sistema</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <img src="/img/logo.png" alt="CBAV Logo" class="h-8 w-auto">
                    <h1 class="ml-3 text-xl font-semibold text-gray-900">CBAV Sistema</h1>
                </div>
                <div class="text-sm text-gray-500">
                    Sistema de Validação Pública
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Status do Documento -->
        <div class="mb-8">
            @if($valido)
                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-medium text-green-800">Documento Válido</h3>
                            <p class="text-sm text-green-700">Este documento foi validado e está autêntico.</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-medium text-red-800">Documento Inválido</h3>
                            <p class="text-sm text-red-700">Este documento não pôde ser validado ou foi alterado.</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Aviso de Documento Vencido -->
            @if($valido && $documento->isVencido())
                <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h4 class="text-lg font-medium text-yellow-800">⚠️ Documento de Baixa Vencido</h4>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p class="mb-3">
                                    <strong>Data de Vencimento:</strong> {{ $documento->data_vencimento->format('d/m/Y') }}<br>
                                    <strong>Dias Vencido:</strong> {{ (int) $documento->data_vencimento->diffInDays(now()) }} dias
                                </p>
                                
                                <div class="mt-4 p-4 bg-yellow-100 rounded border-l-4 border-yellow-400">
                                    <h5 class="font-semibold text-yellow-800 mb-3">🔒 Informações de Segurança:</h5>
                                    <ul class="text-sm text-yellow-700 space-y-2">
                                        <li>• <strong>Hash Válido:</strong> O documento possui assinatura digital válida</li>
                                        <li>• <strong>Autenticidade Confirmada:</strong> Não foi detectada adulteração</li>
                                        <li>• <strong>Vencimento:</strong> Documento expirou em {{ $documento->data_vencimento->format('d/m/Y') }}</li>
                                        <li>• <strong>Uso Atual:</strong> Pode ser usado para consulta histórica</li>
                                        <li>• <strong>Limitações:</strong> Não é válido para pagamentos atuais</li>
                                    </ul>
                                </div>
                                
                                <div class="mt-4 p-4 bg-blue-50 rounded border-l-4 border-blue-400">
                                    <h5 class="font-semibold text-blue-800 mb-3">📋 Explicação Técnica:</h5>
                                    <p class="text-sm text-blue-700">
                                        Este documento de baixa possui <strong>hash de validação válido</strong>, confirmando que não foi adulterado. 
                                        O vencimento não afeta a autenticidade, mas pode limitar seu uso para pagamentos atuais. 
                                        Para pagamentos da Receita Federal, consulte documentos dentro do prazo de validade.
                                    </p>
                                </div>
                                
                                <div class="mt-4 p-4 bg-red-50 rounded border-l-4 border-red-400">
                                    <h5 class="font-semibold text-red-800 mb-3">🚨 Prevenção de Fraude:</h5>
                                    <p class="text-sm text-red-700">
                                        <strong>Importante:</strong> Documentos de baixa vencidos podem ser usados por fraudadores para criar documentos falsos. 
                                        Sempre verifique a data de vencimento e use apenas documentos atuais para pagamentos oficiais.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Aviso de Documento Válido e Atual -->
            @if($valido && !$documento->isVencido())
                <div class="mt-4 bg-green-50 border border-green-200 rounded-lg p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h4 class="text-lg font-medium text-green-800">✅ Documento de Baixa Válido e Atual</h4>
                            <div class="mt-2 text-sm text-green-700">
                                <p class="mb-3">
                                    <strong>Status:</strong> Documento dentro do prazo de validade<br>
                                    @if($documento->data_vencimento)
                                        <strong>Vencimento:</strong> {{ $documento->data_vencimento->format('d/m/Y') }}
                                    @endif
                                </p>
                                
                                <div class="mt-4 p-4 bg-green-100 rounded border-l-4 border-green-400">
                                    <h5 class="font-semibold text-green-800 mb-3">🔒 Validação de Segurança:</h5>
                                    <ul class="text-sm text-green-700 space-y-2">
                                        <li>• <strong>Hash Válido:</strong> Assinatura digital confirmada</li>
                                        <li>• <strong>Autenticidade:</strong> Documento não adulterado</li>
                                        <li>• <strong>Prazo Válido:</strong> Dentro do período de validade</li>
                                        <li>• <strong>Apto para Pagamento:</strong> Pode ser usado para pagamentos</li>
                                        <li>• <strong>Receita Federal:</strong> Aceito para fins oficiais</li>
                                    </ul>
                                </div>
                                
                                <div class="mt-4 p-4 bg-blue-50 rounded border-l-4 border-blue-400">
                                    <h5 class="font-semibold text-blue-800 mb-3">📋 Informações de Segurança:</h5>
                                    <p class="text-sm text-blue-700">
                                        Este documento de baixa passou por todas as verificações de segurança e está apto para pagamento oficial. 
                                        A assinatura digital garante que o documento não foi alterado desde sua emissão.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Informações do Documento -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Documento de Baixa</h2>
                <p class="text-sm text-gray-600">Receita Federal do Brasil</p>
            </div>

            <div class="px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Informações Gerais -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-3">Informações Gerais</h3>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-xs font-medium text-gray-500">Protocolo RF</dt>
                                <dd class="text-sm text-gray-900 font-mono">{{ $documento->protocolo_receita }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500">Tipo de Documento</dt>
                                <dd class="text-sm text-gray-900">{{ \App\Models\DocumentoBaixa::TIPOS_DOCUMENTO[$documento->tipo_documento] ?? $documento->tipo_documento }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500">Número do Documento</dt>
                                <dd class="text-sm text-gray-900">{{ $documento->numero_documento }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500">Ano de Exercício</dt>
                                <dd class="text-sm text-gray-900">{{ $documento->ano_exercicio }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500">Status</dt>
                                <dd class="text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $documento->status === 'PAGO' ? 'bg-green-100 text-green-800' : 
                                           ($documento->status === 'PENDENTE' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ \App\Models\DocumentoBaixa::STATUS[$documento->status] ?? $documento->status }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Datas -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-3">Datas</h3>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-xs font-medium text-gray-500">Data de Emissão</dt>
                                <dd class="text-sm text-gray-900">{{ $documento->data_emissao ? $documento->data_emissao->format('d/m/Y') : 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500">Data de Vencimento</dt>
                                <dd class="text-sm text-gray-900">{{ $documento->data_vencimento ? $documento->data_vencimento->format('d/m/Y') : 'N/A' }}</dd>
                            </div>
                            @if($documento->data_pagamento)
                            <div>
                                <dt class="text-xs font-medium text-gray-500">Data de Pagamento</dt>
                                <dd class="text-sm text-gray-900">{{ $documento->data_pagamento->format('d/m/Y') }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Informações da Transação -->
            <div class="px-6 py-4 border-t border-gray-200">
                <h3 class="text-sm font-medium text-gray-900 mb-3">Informações da Transação</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-xs font-medium text-gray-500">Membro</dt>
                                <dd class="text-sm text-gray-900">{{ $documento->transacao->membro->nome ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500">Campanha</dt>
                                <dd class="text-sm text-gray-900">{{ $documento->transacao->campanha->titulo ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500">Valor da Transação</dt>
                                <dd class="text-sm text-gray-900">R$ {{ number_format($documento->transacao->valor ?? 0, 2, ',', '.') }}</dd>
                            </div>
                        </dl>
                    </div>
                    <div>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-xs font-medium text-gray-500">Valor do Documento</dt>
                                <dd class="text-sm text-gray-900">R$ {{ number_format($documento->valor_documento, 2, ',', '.') }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500">Valor Pago</dt>
                                <dd class="text-sm text-gray-900">R$ {{ number_format($documento->valor_pago ?? 0, 2, ',', '.') }}</dd>
                            </div>
                            @if($multaJuros > 0)
                            <div>
                                <dt class="text-xs font-medium text-gray-500">Multa e Juros</dt>
                                <dd class="text-sm text-red-600 font-semibold">R$ {{ number_format($multaJuros, 2, ',', '.') }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Informações de Validação -->
            <div class="px-6 py-4 border-t border-gray-200">
                <h3 class="text-sm font-medium text-gray-900 mb-3">Informações de Validação e Segurança</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-xs font-medium text-gray-500">Hash de Validação</dt>
                                <dd class="text-xs text-gray-900 font-mono break-all">{{ $documento->hash_documento }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500">Assinatura Digital</dt>
                                <dd class="text-xs text-gray-900 font-mono break-all">{{ $documento->assinatura_digital ?? 'N/A' }}</dd>
                            </div>
                        </dl>
                    </div>
                    <div>
                        <dl class="space-y-2">
                            <div>
                                                <dt class="text-xs font-medium text-gray-500">Hash de Validação</dt>
                <dd class="text-xs text-gray-900 font-mono break-all">{{ $documento->hash_documento }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Validação Digital -->
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="text-center">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Validação Digital</h4>
                    <div class="inline-block p-4 bg-gray-50 border rounded-lg max-w-full">
                        <div class="font-mono text-xs text-gray-800 break-all">
                            {{ $documento->hash_documento }}
                        </div>
                    </div>
                    <p class="text-xs text-gray-600 mt-2">Hash de integridade e autenticidade do documento</p>
                </div>
            </div>

            @if($documento->observacoes)
            <!-- Observações -->
            <div class="px-6 py-4 border-t border-gray-200">
                <h3 class="text-sm font-medium text-gray-900 mb-3">Observações</h3>
                <p class="text-sm text-gray-700">{{ $documento->observacoes }}</p>
            </div>
            @endif
        </div>

        <!-- Formulário de Validação -->
        <div class="mt-8 bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Validar Outro Documento</h3>
                <p class="text-sm text-gray-600">Digite o hash do documento para validar</p>
            </div>
            <div class="px-6 py-4">
                <form action="{{ route('validacao.verificar') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="hash" class="block text-sm font-medium text-gray-700">Hash do Documento</label>
                        <input type="text" name="hash" id="hash" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="Digite o hash do documento">
                    </div>
                    <div>
                        <label for="tipo" class="block text-sm font-medium text-gray-700">Tipo de Documento (Opcional)</label>
                        <select name="tipo" id="tipo"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Detectar automaticamente</option>
                            <option value="declaracao-anual">Declaração Anual</option>
                            <option value="baixa">Documento de Baixa</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit"
                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Validar Documento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="text-center text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} CBAV Sistema. Todos os direitos reservados.</p>
                <p class="mt-1">Sistema de Validação Pública de Documentos</p>
            </div>
        </div>
    </footer>
</body>
</html> 