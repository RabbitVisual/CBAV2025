<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento via Cartão - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #6366f1 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <i class="fas fa-credit-card text-white text-3xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Pagamento via Cartão</h2>
                <p class="text-gray-600">Complete sua doação de forma segura</p>
            </div>

            <!-- Card de Pagamento -->
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 card-hover">
                <!-- Informações da Transação -->
                <div class="mb-8 p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Resumo da Doação</h3>
                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-heart text-white"></i>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Valor:</span>
                            <span class="font-bold text-xl text-blue-600">R$ {{ number_format($transacao->valor, 2, ',', '.') }}</span>
                        </div>
                        @if($transacao->campanha)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Campanha:</span>
                            <span class="font-semibold text-gray-900">{{ $transacao->campanha->titulo }}</span>
                        </div>
                        @endif
                        @if($transacao->descricao)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Descrição:</span>
                            <span class="font-semibold text-gray-900">{{ $transacao->descricao }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold">
                                Pendente
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Formulário de Pagamento -->
                <form id="payment-form" class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Informações do Cartão</label>
                        <div id="card-element" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-transparent">
                            <!-- Stripe Elements será inserido aqui -->
                        </div>
                        <div id="card-errors" class="mt-2 text-sm text-red-600 hidden"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nome no Cartão</label>
                            <input type="text" id="cardholder-name" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg"
                                   placeholder="Nome completo" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                            <input type="email" id="email" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg"
                                   placeholder="seu@email.com" required>
                        </div>
                    </div>

                    <button type="submit" id="submit-button" 
                            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-4 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 font-semibold text-lg shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-lock mr-2"></i>
                        <span id="button-text">Pagar R$ {{ number_format($transacao->valor, 2, ',', '.') }}</span>
                        <div id="spinner" class="hidden">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </button>
                </form>

                <!-- Informações de Segurança -->
                <div class="mt-8 p-4 bg-green-50 rounded-lg border border-green-200">
                    <div class="flex items-center">
                        <i class="fas fa-shield-alt text-green-600 mr-3"></i>
                        <div>
                            <h4 class="font-semibold text-green-800">Pagamento Seguro</h4>
                            <p class="text-sm text-green-700">Seus dados estão protegidos com criptografia SSL</p>
                        </div>
                    </div>
                </div>

                <!-- Links de Navegação -->
                <div class="mt-8 space-y-3">
                    <a href="{{ route('home') }}" 
                       class="block w-full bg-gray-100 text-gray-700 py-3 rounded-lg hover:bg-gray-200 transition-colors text-center font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Voltar ao Início
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center text-gray-500 text-sm">
                <p>Powered by <span class="font-semibold">Stripe</span> • Pagamento seguro e confiável</p>
            </div>
        </div>
    </div>

    <script>
        // Configuração do Stripe
        const stripe = Stripe('{{ $stripeKey }}');
        const elements = stripe.elements();

        // Criar elemento do cartão
        const cardElement = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    color: '#374151',
                    '::placeholder': {
                        color: '#9CA3AF',
                    },
                },
            },
        });

        cardElement.mount('#card-element');

        // Manipular erros de validação
        cardElement.addEventListener('change', function(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
                displayError.classList.remove('hidden');
            } else {
                displayError.textContent = '';
                displayError.classList.add('hidden');
            }
        });

        // Manipular envio do formulário
        const form = document.getElementById('payment-form');
        const submitButton = document.getElementById('submit-button');
        const buttonText = document.getElementById('button-text');
        const spinner = document.getElementById('spinner');

        form.addEventListener('submit', async function(event) {
            event.preventDefault();

            // Desabilitar botão e mostrar spinner
            submitButton.disabled = true;
            buttonText.classList.add('hidden');
            spinner.classList.remove('hidden');

            // Criar token do cartão
            const {token, error} = await stripe.createToken(cardElement, {
                name: document.getElementById('cardholder-name').value,
                email: document.getElementById('email').value,
            });

            if (error) {
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
                errorElement.classList.remove('hidden');
                
                // Reabilitar botão
                submitButton.disabled = false;
                buttonText.classList.remove('hidden');
                spinner.classList.add('hidden');
            } else {
                // Enviar token para o servidor
                const response = await fetch('/pagamento/stripe/processar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        transacao_id: {{ $transacao->id }},
                        stripe_token: token.id,
                        valor: {{ $transacao->valor }},
                        email: document.getElementById('email').value
                    })
                });

                const result = await response.json();

                if (result.success) {
                    // Sucesso - redirecionar para página de sucesso
                    window.location.href = '/pagamento/sucesso/' + {{ $transacao->id }};
                } else {
                    // Erro
                    const errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error || 'Erro ao processar pagamento';
                    errorElement.classList.remove('hidden');
                    
                    // Reabilitar botão
                    submitButton.disabled = false;
                    buttonText.classList.remove('hidden');
                    spinner.classList.add('hidden');
                }
            }
        });
    </script>
</body>
</html> 