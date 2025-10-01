{{-- Componente da Aba Pagamento - Configurações do Sistema --}}
<div id="pagamento">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h5 class="text-lg font-semibold text-gray-900 mb-0">
                <i class="fas fa-credit-card mr-2"></i>Configurações de Pagamento
            </h5>
            <p class="text-gray-600 mb-0">Configure os gateways de pagamento e sistema de doações</p>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.system.settings.update') }}" method="POST" id="form-pagamento">
                @csrf
                @method('PUT')
                <input type="hidden" name="active_tab" value="pagamento">

                {{-- Gateway Stripe --}}
                <div class="mb-6">
                    <div class="mb-4">
                        <h6 class="text-base font-semibold text-blue-600 mb-3">
                            <i class="fab fa-stripe mr-2"></i>Gateway Stripe
                        </h6>
                    </div>
                    
                    <div class="mb-4">
                        <div class="flex items-center">
                            <input class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" 
                                   type="checkbox" id="stripe_enabled" 
                                   name="stripe_enabled" value="1" 
                                   {{ old('stripe_enabled', $configuracoes['stripe_enabled'] ?? false) ? 'checked' : '' }}
                                   onchange="toggleGatewayFields('stripe')">
                            <label class="ml-2 text-sm font-semibold text-gray-900" for="stripe_enabled">
                                Ativar Gateway Stripe
                            </label>
                        </div>
                    </div>

                    <div id="stripe-fields" class="w-full" style="display: {{ old('stripe_enabled', $configuracoes['stripe_enabled'] ?? false) ? 'block' : 'none' }};">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="stripe_public_key" class="block text-sm font-medium text-gray-700 mb-2">Chave Pública *</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('stripe_public_key') border-red-500 @enderror" 
                                       id="stripe_public_key" name="stripe_public_key" 
                                       value="{{ old('stripe_public_key', $configuracoes['stripe_public_key'] ?? '') }}" 
                                       placeholder="pk_test_...">
                                @error('stripe_public_key')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="stripe_secret_key" class="block text-sm font-medium text-gray-700 mb-2">Chave Secreta *</label>
                                <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('stripe_secret_key') border-red-500 @enderror" 
                                       id="stripe_secret_key" name="stripe_secret_key" 
                                       value="{{ old('stripe_secret_key', $configuracoes['stripe_secret_key'] ?? '') }}" 
                                       placeholder="sk_test_...">
                                @error('stripe_secret_key')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Informações importantes:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Use chaves de teste (pk_test_ e sk_test_) para desenvolvimento</li>
                                        <li>Configure webhooks no painel do Stripe para confirmação automática</li>
                                        <li>URL do webhook: <code>{{-- {{ route('payment.webhook.stripe') }} --}}[Configurar webhook no Stripe]</code></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Gateway Mercado Pago --}}
                <div class="mb-6">
                    <div class="mb-4">
                        <h6 class="text-base font-semibold text-blue-600 mb-3">
                            <i class="fas fa-money-bill-wave mr-2"></i>Gateway Mercado Pago
                        </h6>
                    </div>
                    
                    <div class="mb-4">
                        <div class="flex items-center">
                            <input class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" 
                                   type="checkbox" id="mercadopago_enabled" 
                                   name="mercadopago_enabled" value="1" 
                                   {{ old('mercadopago_enabled', $configuracoes['mercadopago_enabled'] ?? false) ? 'checked' : '' }}
                                   onchange="toggleGatewayFields('mercadopago')">
                            <label class="ml-2 text-sm font-semibold text-gray-900" for="mercadopago_enabled">
                                Ativar Gateway Mercado Pago
                            </label>
                        </div>
                    </div>

                    <div id="mercadopago-fields" class="w-full" style="display: {{ old('mercadopago_enabled', $configuracoes['mercadopago_enabled'] ?? false) ? 'block' : 'none' }};">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="mercadopago_public_key" class="block text-sm font-medium text-gray-700 mb-2">Chave Pública *</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('mercadopago_public_key') border-red-500 @enderror" 
                                       id="mercadopago_public_key" name="mercadopago_public_key" 
                                       value="{{ old('mercadopago_public_key', $configuracoes['mercadopago_public_key'] ?? '') }}" 
                                       placeholder="APP_USR-...">
                                @error('mercadopago_public_key')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="mercadopago_access_token" class="block text-sm font-medium text-gray-700 mb-2">Access Token *</label>
                                <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('mercadopago_access_token') border-red-500 @enderror" 
                                       id="mercadopago_access_token" name="mercadopago_access_token" 
                                       value="{{ old('mercadopago_access_token', $configuracoes['mercadopago_access_token'] ?? '') }}" 
                                       placeholder="APP_USR-...">
                                @error('mercadopago_access_token')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-full">
                                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-info-circle text-blue-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-blue-800">Informações importantes:</h3>
                                            <div class="mt-2 text-sm text-blue-700">
                                                <ul class="list-disc list-inside space-y-1">
                                                    <li>Suporte para PIX, cartão de crédito e débito</li>
                                                    <li>Configure webhooks para confirmação automática de pagamentos</li>
                                                    <li>URL do webhook: <code class="bg-blue-100 px-1 rounded">{{-- {{ route('payment.webhook.mercadopago') }} --}}[Configurar webhook no Mercado Pago]</code></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Gateway PIX --}}
                <div class="mb-6">
                    <div class="mb-4">
                        <h6 class="text-base font-semibold text-blue-600 mb-3">
                            <i class="fas fa-qrcode mr-2"></i>Gateway PIX
                        </h6>
                    </div>
                    
                    <div class="mb-4">
                        <div class="flex items-center">
                            <input class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" 
                                   type="checkbox" id="pix_enabled" 
                                   name="pix_enabled" value="1" 
                                   {{ old('pix_enabled', $configuracoes['pix_enabled'] ?? false) ? 'checked' : '' }}
                                   onchange="toggleGatewayFields('pix')">
                            <label class="ml-2 text-sm font-semibold text-gray-900" for="pix_enabled">
                                Ativar Gateway PIX
                            </label>
                        </div>
                    </div>

                    <div id="pix-fields" class="w-full" style="display: {{ old('pix_enabled', $configuracoes['pix_enabled'] ?? false) ? 'block' : 'none' }};">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="pix_key" class="block text-sm font-medium text-gray-700 mb-2">Chave PIX *</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('pix_key') border-red-500 @enderror" 
                                       id="pix_key" name="pix_key" 
                                       value="{{ old('pix_key', $configuracoes['pix_key'] ?? '') }}" 
                                       placeholder="email@exemplo.com ou CPF/CNPJ">
                                @error('pix_key')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="pix_key_type" class="block text-sm font-medium text-gray-700 mb-2">Tipo da Chave *</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('pix_key_type') border-red-500 @enderror" 
                                        id="pix_key_type" name="pix_key_type">
                                    <option value="">Selecione o tipo</option>
                                    <option value="email" {{ old('pix_key_type', $configuracoes['pix_key_type'] ?? '') === 'email' ? 'selected' : '' }}>Email</option>
                                    <option value="cpf" {{ old('pix_key_type', $configuracoes['pix_key_type'] ?? '') === 'cpf' ? 'selected' : '' }}>CPF</option>
                                    <option value="cnpj" {{ old('pix_key_type', $configuracoes['pix_key_type'] ?? '') === 'cnpj' ? 'selected' : '' }}>CNPJ</option>
                                    <option value="phone" {{ old('pix_key_type', $configuracoes['pix_key_type'] ?? '') === 'phone' ? 'selected' : '' }}>Telefone</option>
                                    <option value="random" {{ old('pix_key_type', $configuracoes['pix_key_type'] ?? '') === 'random' ? 'selected' : '' }}>Chave Aleatória</option>
                                </select>
                                @error('pix_key_type')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-full">
                                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-yellow-800">Atenção:</h3>
                                            <div class="mt-2 text-sm text-yellow-700">
                                                O PIX manual requer confirmação manual dos pagamentos. 
                                                Para automação completa, use Mercado Pago ou Stripe.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sistema de Doações --}}
                <div class="mb-6">
                    <div class="mb-4">
                        <h6 class="text-base font-semibold text-blue-600 mb-3">
                            <i class="fas fa-heart mr-2"></i>Sistema de Doações
                        </h6>
                    </div>
                    
                    <div class="mb-4">
                        <div class="flex items-center">
                            <input class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" 
                                   type="checkbox" id="donations_enabled" 
                                   name="donations_enabled" value="1" 
                                   {{ old('donations_enabled', $configuracoes['donations_enabled'] ?? false) ? 'checked' : '' }}>
                            <label class="ml-2 text-sm font-semibold text-gray-900" for="donations_enabled">
                                Ativar Sistema de Doações
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <label for="min_donation_amount" class="block text-sm font-medium text-gray-700 mb-2">Valor Mínimo de Doação (R$)</label>
                            <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('min_donation_amount') border-red-500 @enderror" 
                                   id="min_donation_amount" name="min_donation_amount" 
                                   value="{{ old('min_donation_amount', $configuracoes['min_donation_amount'] ?? '5.00') }}" 
                                   step="0.01" min="1">
                            @error('min_donation_amount')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="max_donation_amount" class="block text-sm font-medium text-gray-700 mb-2">Valor Máximo de Doação (R$)</label>
                            <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('max_donation_amount') border-red-500 @enderror" 
                                   id="max_donation_amount" name="max_donation_amount" 
                                   value="{{ old('max_donation_amount', $configuracoes['max_donation_amount'] ?? '10000.00') }}" 
                                   step="0.01" min="1">
                            @error('max_donation_amount')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="donation_access" class="block text-sm font-medium text-gray-700 mb-2">Acesso para Doações</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('donation_access') border-red-500 @enderror" 
                                id="donation_access" name="donation_access">
                            <option value="public" {{ old('donation_access', $configuracoes['donation_access'] ?? 'public') === 'public' ? 'selected' : '' }}>Público (qualquer pessoa)</option>
                            <option value="registered" {{ old('donation_access', $configuracoes['donation_access'] ?? 'public') === 'registered' ? 'selected' : '' }}>Apenas usuários registrados</option>
                            <option value="premium" {{ old('donation_access', $configuracoes['donation_access'] ?? 'public') === 'premium' ? 'selected' : '' }}>Apenas usuários premium</option>
                        </select>
                        @error('donation_access')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-blue-400"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Sobre o Sistema de Doações:</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Permite que usuários façam doações para apoiar a plataforma</li>
                                            <li>Integra com todos os gateways de pagamento configurados</li>
                                            <li>Gera recibos automáticos para os doadores</li>
                                            <li>Relatórios detalhados disponíveis no painel administrativo</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Botões de Ação --}}
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" onclick="window.location.reload()">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="btn-submit-pagamento">
                        <i class="fas fa-save mr-2"></i>Salvar Configurações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script para controle dos campos de gateway --}}
<script>
function toggleGatewayFields(gateway) {
    const checkbox = document.getElementById(gateway + '_enabled');
    const fields = document.getElementById(gateway + '-fields');
    
    if (checkbox.checked) {
        fields.style.display = 'block';
        // Tornar campos obrigatórios quando ativado
        const requiredFields = fields.querySelectorAll('input[placeholder*="*"], select');
        requiredFields.forEach(field => {
            if (field.placeholder && field.placeholder.includes('*')) {
                field.required = true;
            }
        });
    } else {
        fields.style.display = 'none';
        // Remover obrigatoriedade quando desativado
        const requiredFields = fields.querySelectorAll('input, select');
        requiredFields.forEach(field => {
            field.required = false;
        });
    }
}

// Inicializar estado dos campos ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    toggleGatewayFields('stripe');
    toggleGatewayFields('mercadopago');
    toggleGatewayFields('pix');
});

// Validação do formulário
document.getElementById('form-pagamento').addEventListener('submit', function(e) {
    const stripeEnabled = document.getElementById('stripe_enabled').checked;
    const mercadopagoEnabled = document.getElementById('mercadopago_enabled').checked;
    const pixEnabled = document.getElementById('pix_enabled').checked;
    
    if (!stripeEnabled && !mercadopagoEnabled && !pixEnabled) {
        e.preventDefault();
        alert('Pelo menos um gateway de pagamento deve estar ativado.');
        return false;
    }
    
    // Validar campos obrigatórios dos gateways ativos
    let hasErrors = false;
    
    if (stripeEnabled) {
        const publicKey = document.getElementById('stripe_public_key').value;
        const secretKey = document.getElementById('stripe_secret_key').value;
        if (!publicKey || !secretKey) {
            hasErrors = true;
            alert('Preencha todos os campos obrigatórios do Stripe.');
        }
    }
    
    if (mercadopagoEnabled && !hasErrors) {
        const publicKey = document.getElementById('mercadopago_public_key').value;
        const accessToken = document.getElementById('mercadopago_access_token').value;
        if (!publicKey || !accessToken) {
            hasErrors = true;
            alert('Preencha todos os campos obrigatórios do Mercado Pago.');
        }
    }
    
    if (pixEnabled && !hasErrors) {
        const pixKey = document.getElementById('pix_key').value;
        const pixKeyType = document.getElementById('pix_key_type').value;
        if (!pixKey || !pixKeyType) {
            hasErrors = true;
            alert('Preencha todos os campos obrigatórios do PIX.');
        }
    }
    
    if (hasErrors) {
        e.preventDefault();
        return false;
    }
});
</script>