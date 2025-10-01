<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento PIX - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .pulse-slow {
            animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>
<body class="gradient-bg min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <!-- Background elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-20 left-10 w-32 h-32 bg-white bg-opacity-10 rounded-full floating-animation"></div>
            <div class="absolute top-40 right-20 w-24 h-24 bg-white bg-opacity-10 rounded-full floating-animation" style="animation-delay: 2s;"></div>
            <div class="absolute bottom-20 left-20 w-20 h-20 bg-white bg-opacity-10 rounded-full floating-animation" style="animation-delay: 4s;"></div>
        </div>

        <div class="max-w-lg w-full space-y-8 relative z-10">
            <div class="bg-white rounded-3xl shadow-2xl p-8 card-hover">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-xl">
                        <i class="fas fa-qrcode text-white text-3xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Pagamento PIX</h2>
                    <p class="text-gray-600">Escaneie o QR Code para finalizar sua doação</p>
                </div>

                <!-- Informações da Transação -->
                <div class="bg-gradient-to-r from-blue-50 to-green-50 rounded-2xl p-6 mb-8 border border-blue-100">
                    <h3 class="font-bold text-gray-900 mb-4 text-lg">Detalhes da Doação</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Valor da Doação:</span>
                            <span class="text-2xl font-bold text-green-600">R$ {{ number_format($transacao->valor, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Descrição:</span>
                            <span class="text-gray-900 font-medium">{{ $transacao->descricao ?: 'Doação anônima' }}</span>
                        </div>
                        @if($transacao->campanha)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Campanha:</span>
                            <span class="text-gray-900 font-medium">{{ $transacao->campanha->titulo }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Status:</span>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold">
                                Aguardando Pagamento
                            </span>
                        </div>
                    </div>
                </div>

                <!-- QR Code -->
                <div class="text-center mb-8">
                    <div class="bg-white border-2 border-gray-200 rounded-2xl p-8 inline-block shadow-lg">
                        <div class="w-56 h-56 bg-gray-100 rounded-2xl flex items-center justify-center mb-6 pulse-slow" id="qr-code-container">
                            @if(isset($pixData['qr_code']) && $pixData['qr_code'])
                                <img id="qr-code-image" src="data:image/png;base64,{{ $pixData['qr_code'] }}" class="w-full h-full object-contain" alt="QR Code PIX">
                                <i class="fas fa-qrcode text-8xl text-gray-400 hidden" id="qr-placeholder"></i>
                            @else
                                <i class="fas fa-qrcode text-8xl text-gray-400" id="qr-placeholder"></i>
                                <img id="qr-code-image" class="w-full h-full object-contain hidden" alt="QR Code PIX">
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 font-medium">QR Code PIX</p>
                        <p class="text-xs text-gray-500 mt-2">Use o app do seu banco para escanear</p>
                    </div>
                </div>

                <!-- Código PIX Copia e Cola -->
                <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-2xl p-6 mb-8 border border-green-200">
                    <h3 class="font-bold text-green-900 mb-4 text-lg flex items-center">
                        <i class="fas fa-copy mr-2"></i>
                        Código PIX Copia e Cola
                    </h3>
                    <div class="relative">
                        <textarea id="pix-code" readonly 
                                  class="w-full p-4 bg-white border border-green-300 rounded-xl text-sm font-mono resize-none"
                                  rows="4">{{ $pixData['codigo_pix'] ?? 'Código PIX será gerado aqui...' }}</textarea>
                        <button onclick="copiarPixCode(event)" 
                                class="absolute top-2 right-2 p-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors duration-200"
                                title="Copiar código PIX">
                            <i class="fas fa-copy text-sm"></i>
                        </button>
                    </div>
                    <p class="text-xs text-green-700 mt-2">Cole este código no app do seu banco</p>
                </div>

                <!-- Informações PIX -->
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 mb-8 border border-blue-200">
                    <h3 class="font-bold text-blue-900 mb-4 text-lg flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Informações PIX
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center bg-white rounded-lg p-3">
                            <span class="text-blue-700 font-medium">Chave PIX:</span>
                            <span class="text-blue-900 font-mono bg-blue-50 px-2 py-1 rounded">{{ $pixData['chave'] }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-white rounded-lg p-3">
                            <span class="text-blue-700 font-medium">Beneficiário:</span>
                            <span class="text-blue-900 font-medium">{{ $pixData['beneficiario'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Instruções -->
                <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-2xl p-6 mb-8 border border-yellow-200">
                    <h3 class="font-bold text-yellow-900 mb-4 text-lg flex items-center">
                        <i class="fas fa-lightbulb mr-2"></i>
                        Como pagar
                    </h3>
                    <ol class="text-sm text-yellow-800 space-y-3">
                        <li class="flex items-start">
                            <span class="bg-yellow-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5">1</span>
                            <span>Abra o app do seu banco ou instituição financeira</span>
                        </li>
                        <li class="flex items-start">
                            <span class="bg-yellow-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5">2</span>
                            <span>Escolha a opção PIX no menu principal</span>
                        </li>
                        <li class="flex items-start">
                            <span class="bg-yellow-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5">3</span>
                            <span>Escaneie o QR Code ou cole a chave PIX</span>
                        </li>
                        <li class="flex items-start">
                            <span class="bg-yellow-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5">4</span>
                            <span>Confirme o valor e finalize o pagamento</span>
                        </li>
                    </ol>
                </div>

                <!-- Botões -->
                <div class="space-y-4">
                    <button onclick="copiarChavePix(event)" 
                            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-4 rounded-2xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 font-semibold text-lg shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i class="fas fa-copy mr-3"></i>Copiar Chave PIX
                    </button>
                    
                    <button onclick="verificarPagamento()" 
                            class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white py-4 rounded-2xl hover:from-green-700 hover:to-green-800 transition-all duration-300 font-semibold text-lg shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i class="fas fa-check mr-3"></i>Verificar Pagamento
                    </button>
                    
                    <a href="{{ route('home') }}" 
                       class="block w-full bg-gray-200 text-gray-700 py-4 rounded-2xl hover:bg-gray-300 transition-all duration-300 font-semibold text-center text-lg">
                        <i class="fas fa-arrow-left mr-3"></i>Voltar ao Início
                    </a>
                </div>

                <!-- Status do pagamento -->
                <div id="status-pagamento" class="hidden mt-6 p-4 rounded-2xl text-center">
                    <div class="flex items-center justify-center mb-2">
                        <i class="fas fa-spinner fa-spin text-blue-600 text-xl mr-2"></i>
                        <span class="text-blue-600 font-semibold">Verificando pagamento...</span>
                    </div>
                    <p class="text-sm text-gray-600">Aguarde enquanto verificamos o status da sua doação</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copiarPixCode(event) {
            const pixCode = document.getElementById('pix-code');
            pixCode.select();
            pixCode.setSelectionRange(0, 99999); // Para dispositivos móveis
            
            try {
                navigator.clipboard.writeText(pixCode.value).then(function() {
                    // Mostrar notificação de sucesso
                    const button = event.target.closest('button');
                    const originalHTML = button.innerHTML;
                    button.innerHTML = '<i class="fas fa-check text-sm"></i>';
                    button.classList.remove('bg-green-500', 'hover:bg-green-600');
                    button.classList.add('bg-green-600');
                    
                    setTimeout(() => {
                        button.innerHTML = originalHTML;
                        button.classList.remove('bg-green-600');
                        button.classList.add('bg-green-500', 'hover:bg-green-600');
                    }, 2000);
                });
            } catch (err) {
                // Fallback para navegadores mais antigos
                document.execCommand('copy');
                alert('Código PIX copiado!');
            }
        }

        function copiarChavePix(event) {
            const chave = '{{ $pixData['chave'] }}';
            navigator.clipboard.writeText(chave).then(function() {
                // Mostrar notificação de sucesso
                const button = event.target.closest('button');
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check mr-3"></i>Chave Copiada!';
                button.classList.remove('from-blue-600', 'to-blue-700', 'hover:from-blue-700', 'hover:to-blue-800');
                button.classList.add('from-green-600', 'to-green-700', 'hover:from-green-700', 'hover:to-green-800');
                
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.classList.remove('from-green-600', 'to-green-700', 'hover:from-green-700', 'hover:to-green-800');
                    button.classList.add('from-blue-600', 'to-blue-700', 'hover:from-blue-700', 'hover:to-blue-800');
                }, 2000);
            }).catch(function() {
                alert('Erro ao copiar chave PIX. Tente novamente.');
            });
        }

        function verificarPagamento() {
            const statusDiv = document.getElementById('status-pagamento');
            statusDiv.classList.remove('hidden');
            
            // Fazer requisição AJAX para verificar o status
            fetch('{{ route("payment.verificar", $transacao->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.status === 'confirmado') {
                    statusDiv.innerHTML = `
                        <div class="flex items-center justify-center mb-2">
                            <i class="fas fa-check-circle text-green-600 text-xl mr-2"></i>
                            <span class="text-green-600 font-semibold">Pagamento Confirmado!</span>
                        </div>
                        <p class="text-sm text-gray-600">Sua doação foi processada com sucesso. Obrigado!</p>
                        <a href="${data.redirect_url}" class="inline-block mt-3 bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            Ver Confirmação
                        </a>
                    `;
                    
                    // Redirecionar automaticamente após 3 segundos
                    setTimeout(() => {
                        window.location.href = data.redirect_url;
                    }, 3000);
                } else {
                    statusDiv.innerHTML = `
                        <div class="flex items-center justify-center mb-2">
                            <i class="fas fa-clock text-yellow-600 text-xl mr-2"></i>
                            <span class="text-yellow-600 font-semibold">Aguardando Pagamento</span>
                        </div>
                        <p class="text-sm text-gray-600">${data.message}</p>
                        <button onclick="verificarPagamento()" class="inline-block mt-3 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Verificar Novamente
                        </button>
                    `;
                }
            })
            .catch(error => {
                statusDiv.innerHTML = `
                    <div class="flex items-center justify-center mb-2">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl mr-2"></i>
                        <span class="text-red-600 font-semibold">Erro na Verificação</span>
                    </div>
                    <p class="text-sm text-gray-600">Erro ao verificar o pagamento. Tente novamente.</p>
                    <button onclick="verificarPagamento()" class="inline-block mt-3 bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors">
                        Tentar Novamente
                    </button>
                `;
            });
        }

        // Auto-verificação a cada 30 segundos
        setInterval(() => {
            // Aqui você implementaria a verificação automática
            console.log('Verificando status do pagamento...');
        }, 30000);
    </script>
</body>
</html> 