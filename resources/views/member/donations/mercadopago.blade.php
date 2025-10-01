@extends('layouts.member')

@section('title', 'Pagamento Mercado Pago')

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
            <li class="text-gray-900 font-medium">Pagamento Mercado Pago</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Pagamento Mercado Pago</h1>
        <p class="text-gray-600">Complete sua doação usando Mercado Pago</p>
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
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Escolha a Forma de Pagamento</h3>
            
            <form id="payment-form" action="{{ route('payment.mercadopago') }}" method="POST">
                @csrf
                <input type="hidden" name="transacao_id" value="{{ $transacao->id }}">
                
                <div class="space-y-6">
                    <!-- Opções de Pagamento -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Cartão de Crédito -->
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-500 cursor-pointer payment-option" data-method="credit_card">
                            <div class="flex items-center">
                                <input type="radio" name="payment_method" value="credit_card" id="credit_card" class="mr-3">
                                <label for="credit_card" class="flex-1 cursor-pointer">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                        <span class="font-medium">Cartão de Crédito</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">Visa, Mastercard, Elo, etc.</p>
                                </label>
                            </div>
                        </div>

                        <!-- PIX -->
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-500 cursor-pointer payment-option" data-method="pix">
                            <div class="flex items-center">
                                <input type="radio" name="payment_method" value="pix" id="pix" class="mr-3">
                                <label for="pix" class="flex-1 cursor-pointer">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        <span class="font-medium">PIX</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">Pagamento instantâneo</p>
                                </label>
                            </div>
                        </div>

                        <!-- Boleto -->
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-500 cursor-pointer payment-option" data-method="boleto">
                            <div class="flex items-center">
                                <input type="radio" name="payment_method" value="boleto" id="boleto" class="mr-3">
                                <label for="boleto" class="flex-1 cursor-pointer">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span class="font-medium">Boleto Bancário</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">Pagamento em até 3 dias</p>
                                </label>
                            </div>
                        </div>

                        <!-- Cartão de Débito -->
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-500 cursor-pointer payment-option" data-method="debit_card">
                            <div class="flex items-center">
                                <input type="radio" name="payment_method" value="debit_card" id="debit_card" class="mr-3">
                                <label for="debit_card" class="flex-1 cursor-pointer">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                        <span class="font-medium">Cartão de Débito</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">Débito automático</p>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Informações do Comprador -->
                    <div class="border-t pt-6">
                        <h4 class="text-md font-semibold text-gray-900 mb-4">Informações do Comprador</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nome Completo</label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       required
                                       value="{{ auth()->user()->name }}"
                                       class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">E-mail</label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       required
                                       value="{{ auth()->user()->email }}"
                                       class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Telefone</label>
                                <input type="tel" 
                                       id="phone" 
                                       name="phone" 
                                       required
                                       value="{{ auth()->user()->membro->telefone ?? '' }}"
                                       class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="cpf" class="block text-sm font-medium text-gray-700 mb-2">CPF</label>
                                <input type="text" 
                                       id="cpf" 
                                       name="cpf" 
                                       required
                                       value="{{ auth()->user()->membro->cpf ?? '' }}"
                                       class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
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
                    <p>Seus dados são protegidos pelo Mercado Pago com criptografia SSL de 256 bits.</p>
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

<script>
// Seleção de método de pagamento
document.querySelectorAll('.payment-option').forEach(option => {
    option.addEventListener('click', function() {
        // Remover seleção anterior
        document.querySelectorAll('.payment-option').forEach(opt => {
            opt.classList.remove('border-blue-500', 'bg-blue-50');
            opt.classList.add('border-gray-200');
        });
        
        // Selecionar opção atual
        this.classList.remove('border-gray-200');
        this.classList.add('border-blue-500', 'bg-blue-50');
        
        // Marcar radio button
        const radio = this.querySelector('input[type="radio"]');
        radio.checked = true;
    });
});

// Validação do formulário
document.getElementById('payment-form').addEventListener('submit', function(event) {
    const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
    const submitButton = document.getElementById('submit-button');
    const buttonText = document.getElementById('button-text');
    const spinner = document.getElementById('spinner');
    const errorDiv = document.getElementById('payment-error');
    
    if (!selectedMethod) {
        event.preventDefault();
        errorDiv.classList.remove('hidden');
        document.getElementById('error-title').textContent = 'Método de Pagamento';
        document.getElementById('error-message').textContent = 'Por favor, selecione uma forma de pagamento.';
        return;
    }
    
    // Desabilitar botão e mostrar spinner
    submitButton.disabled = true;
    buttonText.classList.add('hidden');
    spinner.classList.remove('hidden');
    errorDiv.classList.add('hidden');
});

// Máscara para CPF
document.getElementById('cpf').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    value = value.replace(/(\d{3})(\d)/, '$1.$2');
    value = value.replace(/(\d{3})(\d)/, '$1.$2');
    value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    e.target.value = value;
});

// Máscara para telefone
document.getElementById('phone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    value = value.replace(/(\d{2})(\d)/, '($1) $2');
    value = value.replace(/(\d{5})(\d)/, '$1-$2');
    e.target.value = value;
});
</script>
@endsection 