@extends('layouts.public')

@section('page-title', 'Doação Confirmada')
@section('page-description', 'Obrigado pela sua contribuição')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 flex items-center justify-center py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl shadow-2xl p-8 text-center">
            <!-- Ícone de Sucesso -->
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check-circle text-green-600 text-4xl"></i>
            </div>
            
            <!-- Título -->
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Doação Confirmada!</h1>
            <p class="text-lg text-gray-600 mb-8">
                Obrigado pela sua contribuição. Sua doação foi processada com sucesso e será utilizada para a obra de Deus.
            </p>
            
            <!-- Detalhes da Transação -->
            <div class="bg-gradient-to-r from-blue-50 to-green-50 rounded-2xl p-6 mb-8 border border-blue-200">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Detalhes da Doação</h2>
                <div class="space-y-3 text-left">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Valor:</span>
                        <span class="font-bold text-2xl text-green-600">R$ {{ number_format($transacao->valor, 2, ',', '.') }}</span>
                    </div>
                    
                    @if($transacao->campanha)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Campanha:</span>
                        <span class="font-semibold text-gray-900">{{ $transacao->campanha->titulo }}</span>
                    </div>
                    @endif
                    
                    @if($transacao->descricao)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Descrição:</span>
                        <span class="font-semibold text-gray-900">{{ $transacao->descricao }}</span>
                    </div>
                    @endif
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Data:</span>
                        <span class="font-semibold text-gray-900">{{ $transacao->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Status:</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check mr-1"></i>Confirmado
                        </span>
                    </div>
                    
                    @if($transacao->membro)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Doador:</span>
                        <span class="font-semibold text-gray-900">{{ $transacao->membro->nome }}</span>
                    </div>
                    @elseif(isset($transacao->dados_extras['nome_doador']))
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Doador:</span>
                        <span class="font-semibold text-gray-900">{{ $transacao->dados_extras['nome_doador'] }}</span>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Informações do Gateway -->
            @if(isset($transacao->dados_extras['gateway']))
            <div class="bg-gray-50 rounded-2xl p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações do Pagamento</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Método:</span>
                        <span class="font-medium text-gray-900">
                            @switch($transacao->dados_extras['gateway'])
                                @case('stripe')
                                    <i class="fab fa-cc-stripe mr-1"></i>Cartão de Crédito
                                    @break
                                @case('mercadopago')
                                    <i class="fas fa-wallet mr-1"></i>Mercado Pago
                                    @break
                                @case('pix')
                                    <i class="fas fa-qrcode mr-1"></i>PIX
                                    @break
                                @default
                                    {{ ucfirst($transacao->dados_extras['gateway']) }}
                            @endswitch
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">ID da Transação:</span>
                        <span class="font-mono text-gray-900 text-xs">{{ $transacao->id }}</span>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Mensagem de Agradecimento -->
            <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl p-6 mb-8 border border-green-200">
                <div class="flex items-center justify-center mb-4">
                    <i class="fas fa-heart text-green-600 text-2xl mr-3"></i>
                    <h3 class="text-lg font-semibold text-green-900">Obrigado!</h3>
                </div>
                <p class="text-green-800 text-center">
                    Sua generosidade faz a diferença em nossa comunidade. 
                    Que Deus abençoe você e sua família!
                </p>
            </div>
            
            <!-- Botões de Ação -->
            <div class="space-y-4">
                @if(auth()->check() && auth()->user()->hasRole('Membro'))
                    <a href="{{ route('member.dashboard') }}" 
                       class="block w-full bg-blue-600 text-white py-4 rounded-xl hover:bg-blue-700 transition-all duration-200 font-semibold text-lg">
                        <i class="fas fa-home mr-2"></i>Voltar ao Dashboard
                    </a>
                @else
                    <a href="{{ route('home') }}" 
                       class="block w-full bg-blue-600 text-white py-4 rounded-xl hover:bg-blue-700 transition-all duration-200 font-semibold text-lg">
                        <i class="fas fa-home mr-2"></i>Voltar ao Início
                    </a>
                @endif
                
                <a href="{{ route('doacao.index') }}" 
                   class="block w-full bg-green-600 text-white py-4 rounded-xl hover:bg-green-700 transition-all duration-200 font-semibold text-lg">
                    <i class="fas fa-heart mr-2"></i>Fazer Nova Doação
                </a>
            </div>
            
            <!-- Informações Adicionais -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="text-center">
                        <i class="fas fa-shield-alt text-blue-600 text-xl mb-2"></i>
                        <p class="text-gray-600">Pagamento Seguro</p>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-receipt text-green-600 text-xl mb-2"></i>
                        <p class="text-gray-600">Comprovante Enviado</p>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-church text-purple-600 text-xl mb-2"></i>
                        <p class="text-gray-600">Obra de Deus</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-8 text-gray-500 text-sm">
            <p>Se você tiver alguma dúvida, entre em contato conosco.</p>
            <p class="mt-2">
                <i class="fas fa-envelope mr-1"></i>
                {{ \App\Models\Configuracao::get('igreja_email', 'contato@igreja.com') }}
            </p>
        </div>
    </div>
</div>

<script>
// Auto-redirect após 10 segundos (opcional)
setTimeout(function() {
    @if(auth()->check() && auth()->user()->hasRole('Membro'))
        window.location.href = '{{ route("member.dashboard") }}';
    @else
        window.location.href = '{{ route("home") }}';
    @endif
}, 10000);
</script>
@endsection 