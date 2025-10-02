@props(['configuracoes'])

<div x-show="activeTab === 'pagamento'" class="space-y-8">
    <!-- Bloco de Doações -->
    <x-admin.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sistema de Doações</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Configure as regras e limites para doações.</p>
        </x-slot>
        <div class="space-y-4">
            <x-admin.toggle-switch name="doacao_ativa" :checked="old('doacao_ativa', $configuracoes['doacao_ativa'] ?? false)">
                Ativar Sistema de Doações
            </x-admin.toggle-switch>
            <x-admin.toggle-switch name="doacao_sem_login" :checked="old('doacao_sem_login', $configuracoes['doacao_sem_login'] ?? false)">
                Permitir doações de usuários não logados
            </x-admin.toggle-switch>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div>
                <x-admin.label for="doacao_valor_minimo" value="Valor Mínimo (R$)" />
                <x-admin.input id="doacao_valor_minimo" name="doacao_valor_minimo" type="number" step="0.01" min="1" class="mt-1 block w-full"
                               :value="old('doacao_valor_minimo', $configuracoes['doacao_valor_minimo'])" />
            </div>
            <div>
                <x-admin.label for="doacao_valor_maximo" value="Valor Máximo (R$)" />
                <x-admin.input id="doacao_valor_maximo" name="doacao_valor_maximo" type="number" step="0.01" min="1" class="mt-1 block w-full"
                               :value="old('doacao_valor_maximo', $configuracoes['doacao_valor_maximo'])" />
            </div>
        </div>
    </x-admin.card>

    <!-- Gateway Stripe -->
    <x-admin.settings.gateway-card name="stripe" title="Gateway Stripe" icon="fab fa-stripe" :enabled="old('stripe_enabled', $configuracoes['stripe_enabled'] ?? false)">
        <div>
            <x-admin.label for="stripe_key" value="Chave Pública (Publishable Key)" />
            <x-admin.input id="stripe_key" name="stripe_key" type="text" class="mt-1 block w-full"
                           :value="old('stripe_key', $configuracoes['stripe_key'])" placeholder="pk_test_..." />
        </div>
        <div>
            <x-admin.label for="stripe_secret" value="Chave Secreta (Secret Key)" />
            <x-admin.input id="stripe_secret" name="stripe_secret" type="password" class="mt-1 block w-full"
                           :value="old('stripe_secret', $configuracoes['stripe_secret'])" placeholder="sk_test_..." />
        </div>
    </x-admin.settings.gateway-card>

    <!-- Gateway Mercado Pago -->
    <x-admin.settings.gateway-card name="mercadopago" title="Gateway Mercado Pago" icon="fas fa-hand-holding-usd" :enabled="old('mercadopago_enabled', $configuracoes['mercadopago_enabled'] ?? false)">
        <div>
            <x-admin.label for="mercadopago_key" value="Chave Pública (Public Key)" />
            <x-admin.input id="mercadopago_key" name="mercadopago_key" type="text" class="mt-1 block w-full"
                           :value="old('mercadopago_key', $configuracoes['mercadopago_key'])" placeholder="APP_USR-..." />
        </div>
        <div>
            <x-admin.label for="mercadopago_token" value="Token de Acesso (Access Token)" />
            <x-admin.input id="mercadopago_token" name="mercadopago_token" type="password" class="mt-1 block w-full"
                           :value="old('mercadopago_token', $configuracoes['mercadopago_token'])" placeholder="APP_USR-..." />
        </div>
    </x-admin.settings.gateway-card>

    <!-- Gateway PIX -->
    <x-admin.settings.gateway-card name="pix" title="Gateway PIX Manual" icon="fas fa-qrcode" :enabled="old('pix_enabled', $configuracoes['pix_enabled'] ?? false)">
        <div class="md:col-span-2">
            <x-admin.alert type="warning" message="O PIX manual requer confirmação manual dos pagamentos no painel financeiro. Para automação, use Mercado Pago ou Stripe." />
        </div>
        <div>
            <x-admin.label for="pix_beneficiario" value="Nome do Beneficiário" />
            <x-admin.input id="pix_beneficiario" name="pix_beneficiario" type="text" class="mt-1 block w-full"
                           :value="old('pix_beneficiario', $configuracoes['pix_beneficiario'])" />
        </div>
        <div>
            <x-admin.label for="pix_chave" value="Chave PIX" />
            <x-admin.input id="pix_chave" name="pix_chave" type="text" class="mt-1 block w-full"
                           :value="old('pix_chave', $configuracoes['pix_chave'])" placeholder="Email, CPF/CNPJ ou Chave Aleatória" />
        </div>
    </x-admin.settings.gateway-card>
</div>