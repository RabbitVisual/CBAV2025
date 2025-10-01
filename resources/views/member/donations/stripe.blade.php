@extends('layouts.member')

@section('title', 'Pagamento com Cartão')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-8">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('member.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ route('member.donations.index') }}" class="hover:text-blue-600">Doações</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ route('member.donations.donate') }}" class="hover:text-blue-600">Fazer Doação</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-900 font-medium">Pagamento com Cartão</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Pagamento com Cartão</h1>
        <p class="text-gray-600">Complete sua doação usando cartão de crédito ou débito</p>
    </div>

    <!-- Informações da Transação -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Detalhes da Doação</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-600 mb-1">Valor da Doação</p>
                <p class="text-2xl font-bold text-green-600">R$ {{ number_format($transacao->valor, 2, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">ID da Transação</p>
                <p class="text-lg font-medium text-gray-900">#{{ $transacao->id }}</p>
            </div>
            @if($transacao->campanha)
            <div class="md:col-span-2">
                <p class="text-sm text-gray-600 mb-1">Campanha</p>
                <p class="text-lg font-medium text-gray-900">{{ $transacao->campanha->titulo }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Formulário de Pagamento -->
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Informações do Cartão</h3>
            
            <form id="payment-form" action="{{ route('payment.stripe') }}" method="POST">
                @csrf
                <input type="hidden" name="transacao_id" value="{{ $transacao->id }}">
                
                <div class="space-y-6">
                    <!-- Número do Cartão -->
                    <div>
                        <label for="card_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Número do Cartão
                        </label>
                        <div id="card_number" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></div>
                        <div id="card_number_error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Data de Expiração e CVV -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="card_expiry" class="block text-sm font-medium text-gray-700 mb-2">
                                Data de Expiração
                            </label>
                            <div id="card_expiry" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></div>
                            <div id="card_expiry_error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>
                        <div>
                            <label for="card_cvc" class="block text-sm font-medium text-gray-700 mb-2">
                                CVV
                            </label>
                            <div id="card_cvc" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></div>
                            <div id="card_cvc_error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>
                    </div>

                    <!-- Nome no Cartão -->
                    <div>
                        <label for="card_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome no Cartão
                        </label>
                        <input type="text" 
                               id="card_name" 
                               name="card_name" 
                               required
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Como aparece no cartão">
                    </div>

                    <!-- Botão de Pagamento -->
                    <button type="submit" 
                            id="submit-button"
                            class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="button-text">Pagar R$ {{ number_format($transacao->valor, 2, ',', '.') }}</span>
                        <div id="spinner" class="hidden">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </button>
                </div>
            </form>

            <!-- Mensagens de Erro -->
            <div id="payment-error" class="hidden mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800" id="error-title">Erro no Pagamento</h3>
                        <div class="mt-2 text-sm text-red-700" id="error-message"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informações de Segurança -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Pagamento Seguro</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p>Seus dados são protegidos com criptografia SSL de 256 bits. Não armazenamos informações do seu cartão.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Ações -->
    <div class="flex justify-between items-center mt-8">
        <a href="{{ route('member.donations.donate') }}" 
           class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Voltar
        </a>
        
        <a href="{{ route('member.donations.index') }}" 
           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            Minhas Doações
        </a>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
// Inicializar Stripe
const stripe = Stripe('{{ $stripeKey }}');
const elements = stripe.elements();

// Criar elementos do cartão
const cardNumber = elements.create('cardNumber', {
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

const cardExpiry = elements.create('cardExpiry', {
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

const cardCvc = elements.create('cardCvc', {
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

// Montar elementos
cardNumber.mount('#card_number');
cardExpiry.mount('#card_expiry');
cardCvc.mount('#card_cvc');

// Manipular erros de validação
cardNumber.addEventListener('change', function(event) {
    const displayError = document.getElementById('card_number_error');
    if (event.error) {
        displayError.textContent = event.error.message;
        displayError.classList.remove('hidden');
    } else {
        displayError.classList.add('hidden');
    }
});

cardExpiry.addEventListener('change', function(event) {
    const displayError = document.getElementById('card_expiry_error');
    if (event.error) {
        displayError.textContent = event.error.message;
        displayError.classList.remove('hidden');
    } else {
        displayError.classList.add('hidden');
    }
});

cardCvc.addEventListener('change', function(event) {
    const displayError = document.getElementById('card_cvc_error');
    if (event.error) {
        displayError.textContent = event.error.message;
        displayError.classList.remove('hidden');
    } else {
        displayError.classList.add('hidden');
    }
});

// Manipular envio do formulário
const form = document.getElementById('payment-form');
const submitButton = document.getElementById('submit-button');
const buttonText = document.getElementById('button-text');
const spinner = document.getElementById('spinner');
const errorDiv = document.getElementById('payment-error');
const errorTitle = document.getElementById('error-title');
const errorMessage = document.getElementById('error-message');

form.addEventListener('submit', function(event) {
    event.preventDefault();
    
    // Desabilitar botão e mostrar spinner
    submitButton.disabled = true;
    buttonText.classList.add('hidden');
    spinner.classList.remove('hidden');
    errorDiv.classList.add('hidden');
    
    stripe.createToken(cardNumber).then(function(result) {
        if (result.error) {
            // Mostrar erro
            errorTitle.textContent = 'Erro no Cartão';
            errorMessage.textContent = result.error.message;
            errorDiv.classList.remove('hidden');
            
            // Reabilitar botão
            submitButton.disabled = false;
            buttonText.classList.remove('hidden');
            spinner.classList.add('hidden');
        } else {
            // Adicionar token ao formulário
            const hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', result.token.id);
            form.appendChild(hiddenInput);
            
            // Enviar formulário
            form.submit();
        }
    });
});
</script>
@endsection 