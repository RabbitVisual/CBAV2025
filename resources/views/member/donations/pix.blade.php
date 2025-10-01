@extends('layouts.member')

@section('title', 'Pagamento PIX')

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
            <li class="text-gray-900 font-medium">Pagamento PIX</li>
        </ol>
    </nav>

    <!-- Header com Frase Bíblica -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Pagamento PIX</h1>
        <p class="text-gray-600 mb-2">Complete sua doação usando PIX instantâneo</p>
        <div class="bg-gradient-to-r from-green-50 to-blue-50 border-l-4 border-green-400 p-4 rounded-r-lg">
            <p class="text-sm text-green-800 italic">
                <i class="fas fa-quote-left mr-2"></i>
                "Honra ao Senhor com os teus bens e com as primícias de toda a tua renda." 
                <span class="font-semibold">- Provérbios 3:9</span>
            </p>
        </div>
    </div>

    <!-- Informações da Transação Melhoradas -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">
            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
            {{ __('Detalhes da Doação') }}
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-600 mb-1">{{ __('Valor da Doação') }}</p>
                <p class="text-2xl font-bold text-green-600">R$ {{ $pixData['valor'] }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">{{ __('ID da Transação') }}</p>
                <p class="text-lg font-medium text-gray-900">#{{ $transacao->id }}</p>
            </div>
            @if($transacao->campanha)
            <div class="md:col-span-2">
                <p class="text-sm text-gray-600 mb-1">{{ __('Campanha') }}</p>
                <p class="text-lg font-medium text-gray-900">{{ $transacao->campanha->titulo }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- QR Code e Informações PIX Melhoradas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- QR Code -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-qrcode text-purple-600 mr-2"></i>
                {{ __('QR Code PIX') }}
            </h3>
            
            @if($pixData['qr_code'])
            <div class="text-center mb-6">
                <img src="data:image/png;base64,{{ $pixData['qr_code'] }}" 
                     alt="QR Code PIX" 
                     class="mx-auto border-2 border-gray-200 rounded-lg">
                <p class="text-sm text-gray-600 mt-2">{{ __('Escaneie com seu app bancário') }}</p>
            </div>
            @else
            <div class="text-center py-8">
                <div class="text-red-500 mb-2">
                    <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <p class="text-gray-600">{{ __('Erro ao gerar QR Code') }}</p>
            </div>
            @endif

            <!-- Botão Copiar QR Code -->
            <button onclick="copiarQRCode()" 
                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
                {{ __('Copiar QR Code') }}
            </button>
        </div>

        <!-- Informações PIX -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-credit-card text-green-600 mr-2"></i>
                {{ __('Informações PIX') }}
            </h3>
            
            <div class="space-y-4">
                <!-- Chave PIX -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Chave PIX') }}</label>
                    <div class="flex">
                        <input type="text" 
                               value="{{ $pixData['chave'] }}" 
                               readonly 
                               class="flex-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg p-3">
                        <button onclick="copiarChavePix()" 
                                class="bg-blue-600 text-white px-4 py-3 rounded-r-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Beneficiário -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Beneficiário') }}</label>
                    <input type="text" 
                           value="{{ $pixData['beneficiario'] }}" 
                           readonly 
                           class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-3">
                </div>

                <!-- Valor -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Valor') }}</label>
                    <input type="text" 
                           value="R$ {{ $pixData['valor'] }}" 
                           readonly 
                           class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-3">
                </div>

                <!-- Código PIX Copia e Cola -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Código PIX (Copia e Cola)') }}</label>
                    <div class="flex">
                        <textarea readonly 
                                  rows="3"
                                  class="flex-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg p-3 resize-none">{{ $pixData['codigo_pix'] }}</textarea>
                        <button onclick="copiarPixCode()" 
                                class="bg-blue-600 text-white px-4 py-3 rounded-r-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Instruções de Pagamento -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-8">
        <h3 class="text-lg font-semibold text-blue-900 mb-4">
            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
            {{ __('Como fazer o pagamento PIX:') }}
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-3">
                <div class="flex items-start">
                    <div class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-medium mr-3 mt-0.5">1</div>
                    <span class="text-sm text-blue-800">{{ __('Abra o app do seu banco') }}</span>
                </div>
                <div class="flex items-start">
                    <div class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-medium mr-3 mt-0.5">2</div>
                    <span class="text-sm text-blue-800">{{ __('Escolha a opção PIX') }}</span>
                </div>
                <div class="flex items-start">
                    <div class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-medium mr-3 mt-0.5">3</div>
                    <span class="text-sm text-blue-800">{{ __('Escaneie o QR Code ou cole o código') }}</span>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex items-start">
                    <div class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-medium mr-3 mt-0.5">4</div>
                    <span class="text-sm text-blue-800">{{ __('Confirme os dados e valor') }}</span>
                </div>
                <div class="flex items-start">
                    <div class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-medium mr-3 mt-0.5">5</div>
                    <span class="text-sm text-blue-800">{{ __('Digite sua senha e confirme') }}</span>
                </div>
                <div class="flex items-start">
                    <div class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-medium mr-3 mt-0.5">6</div>
                    <span class="text-sm text-blue-800">{{ __('Aguarde a confirmação automática') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Status do Pagamento Melhorado -->
    <div class="bg-white rounded-lg shadow-md p-6 mt-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-clock text-yellow-600 mr-2"></i>
            {{ __('Status do Pagamento') }}
        </h3>
        
        <div id="paymentStatus" class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-600">{{ __('Verificando status do pagamento...') }}</p>
        </div>

        <div id="paymentSuccess" class="hidden text-center py-8">
            <div class="text-green-500 mb-4">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h4 class="text-xl font-semibold text-green-600 mb-2">{{ __('Pagamento Confirmado!') }}</h4>
            <p class="text-gray-600 mb-4">{{ __('Sua doação foi processada com sucesso.') }}</p>
            <a href="{{ route('member.donations.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ __('Voltar às Doações') }}
            </a>
        </div>

        <div id="paymentPending" class="hidden text-center py-8">
            <div class="text-yellow-500 mb-4">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h4 class="text-xl font-semibold text-yellow-600 mb-2">{{ __('Aguardando Pagamento') }}</h4>
            <p class="text-gray-600 mb-4">{{ __('Após realizar o pagamento, clique no botão abaixo para verificar.') }}</p>
            <button onclick="verificarPagamento()" 
                    class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                {{ __('Verificar Pagamento') }}
            </button>
        </div>
    </div>

    <!-- Ações -->
    <div class="flex justify-between items-center mt-8">
        <a href="{{ route('member.donations.donate') }}" 
           class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            {{ __('Voltar') }}
        </a>
        
        <a href="{{ route('member.donations.index') }}" 
           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            {{ __('Minhas Doações') }}
        </a>
    </div>
</div>

<script>
// Funções para copiar dados
function copiarQRCode() {
    const qrCode = document.querySelector('img[alt="QR Code PIX"]');
    if (qrCode) {
        // Criar um canvas para copiar a imagem
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const img = new Image();
        img.onload = function() {
            canvas.width = img.width;
            canvas.height = img.height;
            ctx.drawImage(img, 0, 0);
            canvas.toBlob(function(blob) {
                const item = new ClipboardItem({ "image/png": blob });
                navigator.clipboard.write([item]).then(function() {
                    mostrarMensagem('{{ __("QR Code copiado!") }}');
                });
            });
        };
        img.src = qrCode.src;
    }
}

function copiarChavePix() {
    const chave = '{{ $pixData["chave"] }}';
    navigator.clipboard.writeText(chave).then(function() {
        mostrarMensagem('{{ __("Chave PIX copiada!") }}');
    });
}

function copiarPixCode() {
    const codigo = '{{ $pixData["codigo_pix"] }}';
    navigator.clipboard.writeText(codigo).then(function() {
        mostrarMensagem('{{ __("Código PIX copiado!") }}');
    });
}

function mostrarMensagem(mensagem) {
    // Criar toast de sucesso
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    toast.textContent = mensagem;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Verificar pagamento
function verificarPagamento() {
    const statusDiv = document.getElementById('paymentStatus');
    const successDiv = document.getElementById('paymentSuccess');
    const pendingDiv = document.getElementById('paymentPending');
    
    statusDiv.classList.remove('hidden');
    successDiv.classList.add('hidden');
    pendingDiv.classList.add('hidden');
    
    fetch('{{ route("member.donations.pix.verificar", $transacao->id) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        statusDiv.classList.add('hidden');
        
        if (data.status === 'confirmado') {
            successDiv.classList.remove('hidden');
        } else {
            pendingDiv.classList.remove('hidden');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        statusDiv.classList.add('hidden');
        pendingDiv.classList.remove('hidden');
    });
}

// Verificar automaticamente a cada 30 segundos
setInterval(verificarPagamento, 30000);

// Verificar na primeira vez após 5 segundos
setTimeout(verificarPagamento, 5000);
</script>
@endsection 