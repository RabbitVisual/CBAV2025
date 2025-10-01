<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documento Não Encontrado - CBAV Sistema</title>
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
            <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-red-800">Documento Não Encontrado</h3>
                        <p class="text-sm text-red-700">O documento solicitado não foi encontrado em nosso sistema.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informações -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Documento Não Encontrado</h2>
                <p class="text-sm text-gray-600">Receita Federal do Brasil</p>
            </div>

            <div class="px-6 py-4">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Documento não encontrado</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        O documento com o hash fornecido não foi encontrado em nosso sistema.
                    </p>
                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Possíveis motivos:</h4>
                        <ul class="text-sm text-gray-500 space-y-1">
                            <li>• O hash do documento está incorreto</li>
                            <li>• O documento foi removido do sistema</li>
                            <li>• O documento ainda não foi processado</li>
                            <li>• O documento pertence a outro sistema</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulário de Validação -->
        <div class="mt-8 bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Tentar Validar Outro Documento</h3>
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

        <!-- Informações Adicionais -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Informações Importantes</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Certifique-se de que o hash está correto e completo</li>
                            <li>O hash deve ter pelo menos 10 caracteres</li>
                            <li>Documentos válidos são gerados automaticamente pelo sistema</li>
                            <li>Para dúvidas, entre em contato com a administração</li>
                        </ul>
                    </div>
                </div>
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