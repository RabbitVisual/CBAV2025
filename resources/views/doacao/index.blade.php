@extends('layouts.public')

@section('page-title', 'Faça uma Doação')
@section('page-description', 'Contribua para a obra de Deus')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-green-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-green-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-heart text-white text-4xl"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Faça uma Doação</h1>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                Sua contribuição ajuda a manter nossa igreja e expandir a obra de Deus. 
                Seja uma bênção para nossa comunidade!
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Formulário de Doação -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Informações da Doação</h2>
            
            <form method="POST" action="{{ route('doacao.process') }}" id="formDoacao" class="space-y-6">
                @csrf
                
                <!-- Valor da Doação -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Valor da Doação</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">R$</span>
                        <input type="number" 
                               name="valor" 
                               id="valor"
                               step="0.01" 
                               min="{{ $configuracoes['doacao_valor_minimo'] ?? 1 }}" 
                               max="{{ $configuracoes['doacao_valor_maximo'] ?? 10000 }}"
                               class="w-full pl-12 pr-4 py-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg"
                               placeholder="0,00"
                               required>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">
                        Valor mínimo: R$ {{ number_format($configuracoes['doacao_valor_minimo'] ?? 1, 2, ',', '.') }} | 
                        Valor máximo: R$ {{ number_format($configuracoes['doacao_valor_maximo'] ?? 10000, 2, ',', '.') }}
                    </p>
                </div>

                <!-- Destino da Doação -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Destino da Doação</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="tipo_destino" value="igreja" class="sr-only" checked>
                            <div class="border-2 border-gray-200 rounded-lg p-4 text-center hover:border-blue-500 transition-colors destino-option">
                                <i class="fas fa-church text-2xl text-blue-600 mb-2"></i>
                                <div class="font-semibold text-gray-900">Igreja</div>
                                <div class="text-sm text-gray-600">Manutenção geral</div>
                            </div>
                        </label>
                        
                        @if($campanhas->count() > 0)
                        <label class="relative cursor-pointer">
                            <input type="radio" name="tipo_destino" value="campanha" class="sr-only">
                            <div class="border-2 border-gray-200 rounded-lg p-4 text-center hover:border-blue-500 transition-colors destino-option">
                                <i class="fas fa-bullhorn text-2xl text-green-600 mb-2"></i>
                                <div class="font-semibold text-gray-900">Campanha</div>
                                <div class="text-sm text-gray-600">Projetos específicos</div>
                            </div>
                        </label>
                        @endif
                        
                        @if($ministerios->count() > 0)
                        <label class="relative cursor-pointer">
                            <input type="radio" name="tipo_destino" value="ministerio" class="sr-only">
                            <div class="border-2 border-gray-200 rounded-lg p-4 text-center hover:border-blue-500 transition-colors destino-option">
                                <i class="fas fa-hands-helping text-2xl text-purple-600 mb-2"></i>
                                <div class="font-semibold text-gray-900">Ministério</div>
                                <div class="text-sm text-gray-600">Ministérios específicos</div>
                            </div>
                        </label>
                        @endif
                    </div>
                </div>

                <!-- Seleção de Campanha -->
                <div id="selecao-campanha" class="hidden">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Selecione a Campanha</label>
                    <select name="destino_id" id="campanha_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Selecione uma campanha...</option>
                        @foreach($campanhas as $campanha)
                            <option value="{{ $campanha->id }}">{{ $campanha->titulo }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Seleção de Ministério -->
                <div id="selecao-ministerio" class="hidden">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Selecione o Ministério</label>
                    <select name="destino_id" id="ministerio_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Selecione um ministério...</option>
                        @foreach($ministerios as $ministerio)
                            <option value="{{ $ministerio->id }}">{{ $ministerio->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Forma de Pagamento -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Forma de Pagamento</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if(!empty($configuracoes['stripe_key']))
                        <label class="relative cursor-pointer">
                            <input type="radio" name="gateway" value="stripe" class="sr-only" checked>
                            <div class="border-2 border-gray-200 rounded-lg p-4 text-center hover:border-blue-500 transition-colors gateway-option">
                                <i class="fab fa-cc-stripe text-2xl text-blue-600 mb-2"></i>
                                <div class="font-semibold text-gray-900">Cartão de Crédito</div>
                                <div class="text-sm text-gray-600">Visa, Mastercard, etc.</div>
                            </div>
                        </label>
                        @endif
                        
                        @if(!empty($configuracoes['mercadopago_key']))
                        <label class="relative cursor-pointer">
                            <input type="radio" name="gateway" value="mercadopago" class="sr-only">
                            <div class="border-2 border-gray-200 rounded-lg p-4 text-center hover:border-blue-500 transition-colors gateway-option">
                                <i class="fas fa-wallet text-2xl text-green-600 mb-2"></i>
                                <div class="font-semibold text-gray-900">Mercado Pago</div>
                                <div class="text-sm text-gray-600">Cartão, boleto, PIX</div>
                            </div>
                        </label>
                        @endif
                        
                        <!-- Mensagem quando nenhum gateway está configurado -->
                        @if(empty($configuracoes['stripe_key']) && empty($configuracoes['mercadopago_key']))
                        <div class="col-span-2 text-center py-8">
                            <i class="fas fa-exclamation-triangle text-yellow-500 text-3xl mb-4"></i>
                            <p class="text-gray-600">Nenhum gateway de pagamento configurado.</p>
                            <p class="text-sm text-gray-500 mt-2">Entre em contato com o administrador.</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Identificação (Opcional) -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <input type="checkbox" name="identificar" id="identificar" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="identificar" class="ml-2 text-sm font-semibold text-gray-700">Desejo me identificar (opcional)</label>
                    </div>
                    
                    <div id="campos-identificacao" class="hidden space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nome</label>
                                <input type="text" name="nome_doador" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" name="email_doador" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Descrição -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Mensagem (Opcional)</label>
                    <textarea name="descricao" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Deixe uma mensagem ou intenção da sua doação..."></textarea>
                </div>

                <!-- Botão de Envio -->
                <div class="pt-6">
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-green-600 text-white py-4 rounded-lg hover:from-blue-700 hover:to-green-700 transition-all duration-200 font-semibold text-lg shadow-lg">
                        <i class="fas fa-heart mr-2"></i>
                        Fazer Doação
                    </button>
                </div>
            </form>
        </div>

        <!-- Informações Adicionais -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                    <i class="fas fa-shield-alt text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Pagamento Seguro</h3>
                <p class="text-gray-600 text-sm">Seus dados estão protegidos com criptografia SSL e processamento seguro.</p>
            </div>
            
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                    <i class="fas fa-receipt text-green-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Comprovante</h3>
                <p class="text-gray-600 text-sm">Receba um comprovante por email após a confirmação do pagamento.</p>
            </div>
            
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                    <i class="fas fa-church text-purple-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Transparência</h3>
                <p class="text-gray-600 text-sm">Todas as doações são registradas e auditadas para total transparência.</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Controle dos destinos
    const destinoOptions = document.querySelectorAll('input[name="tipo_destino"]');
    const selecaoCampanha = document.getElementById('selecao-campanha');
    const selecaoMinisterio = document.getElementById('selecao-ministerio');
    const campanhaId = document.getElementById('campanha_id');
    const ministerioId = document.getElementById('ministerio_id');
    
    function atualizarSelecao() {
        const valorSelecionado = document.querySelector('input[name="tipo_destino"]:checked')?.value;
        
        // Ocultar todos os campos de seleção
        selecaoCampanha.classList.add('hidden');
        selecaoMinisterio.classList.add('hidden');
        
        // Limpar valores dos selects
        if (campanhaId) campanhaId.value = '';
        if (ministerioId) ministerioId.value = '';
        
        // Mostrar campo apropriado
        if (valorSelecionado === 'campanha') {
            selecaoCampanha.classList.remove('hidden');
        } else if (valorSelecionado === 'ministerio') {
            selecaoMinisterio.classList.remove('hidden');
        }
    }
    
    // Adicionar evento de mudança para todos os radio buttons
    destinoOptions.forEach(option => {
        option.addEventListener('change', atualizarSelecao);
    });
    
    // Controle da identificação
    const identificarCheckbox = document.getElementById('identificar');
    const camposIdentificacao = document.getElementById('campos-identificacao');
    
    identificarCheckbox.addEventListener('change', function() {
        if (this.checked) {
            camposIdentificacao.classList.remove('hidden');
        } else {
            camposIdentificacao.classList.add('hidden');
        }
    });
    
    // Estilização dos botões de opção
    const destinoOptionsDivs = document.querySelectorAll('.destino-option');
    destinoOptions.forEach((option, index) => {
        option.addEventListener('change', function() {
            destinoOptionsDivs.forEach(div => {
                div.classList.remove('border-blue-500', 'bg-blue-50');
                div.classList.add('border-gray-200');
            });
            if (this.checked) {
                destinoOptionsDivs[index].classList.remove('border-gray-200');
                destinoOptionsDivs[index].classList.add('border-blue-500', 'bg-blue-50');
            }
        });
    });
    
    const gatewayOptionsDivs = document.querySelectorAll('.gateway-option');
    const gatewayOptions = document.querySelectorAll('input[name="gateway"]');
    gatewayOptions.forEach((option, index) => {
        option.addEventListener('change', function() {
            gatewayOptionsDivs.forEach(div => {
                div.classList.remove('border-blue-500', 'bg-blue-50');
                div.classList.add('border-gray-200');
            });
            if (this.checked) {
                gatewayOptionsDivs[index].classList.remove('border-gray-200');
                gatewayOptionsDivs[index].classList.add('border-blue-500', 'bg-blue-50');
            }
        });
    });
    
    // Validação do formulário
    const form = document.getElementById('formDoacao');
    form.addEventListener('submit', function(e) {
        const valor = document.getElementById('valor').value;
        const gateway = document.querySelector('input[name="gateway"]:checked');
        
        if (!valor || valor < {{ $configuracoes['doacao_valor_minimo'] ?? 1 }}) {
            e.preventDefault();
            alert('Por favor, insira um valor válido para a doação.');
            return;
        }
        
        if (!gateway) {
            e.preventDefault();
            alert('Por favor, selecione uma forma de pagamento.');
            return;
        }
        
        const tipoDestino = document.querySelector('input[name="tipo_destino"]:checked')?.value;
        if (tipoDestino && tipoDestino !== 'igreja') {
            const destinoId = tipoDestino === 'campanha' ? campanhaId?.value : ministerioId?.value;
            if (!destinoId) {
                e.preventDefault();
                alert('Por favor, selecione o destino da doação.');
                return;
            }
        }
    });
    
    // Executar na carga inicial
    atualizarSelecao();
});
</script>
@endsection 