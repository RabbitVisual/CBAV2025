@extends('layouts.admin')

@section('title', 'Configurações do Sistema')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Cabeçalho com orientações -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        <i class="fas fa-cog text-blue-600 mr-3"></i>
                        Configurações do Sistema
                    </h1>
                    <p class="text-lg text-gray-600 mt-2">
                        Configure as configurações gerais do sistema. As alterações são aplicadas imediatamente.
                    </p>
                    
                    <!-- Guia de orientação -->
                    <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">
                                    Guia de Configuração
                                </h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p><strong>Geral:</strong> Informações básicas, fuso horário e idioma</p>
                                    <p><strong>Pagamento:</strong> Gateways de pagamento e configurações de doação</p>
                                    <p><strong>Email:</strong> Configurações de servidor SMTP</p>
                                    <p><strong>Segurança:</strong> 2FA, sessões e proteções</p>
                                    <p><strong>Cache & Backup:</strong> Otimização e backup automático</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <button type="button" onclick="limparCache()" 
                            class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition flex items-center">
                        <i class="fas fa-broom mr-2"></i>Limpar Cache
                    </button>
                    <button type="button" onclick="testarConfiguracoes()" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center">
                        <i class="fas fa-check mr-2"></i>Testar Configurações
                    </button>
                    <a href="{{ route('admin.system.home-config.index') }}" 
                       class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition flex items-center">
                        <i class="fas fa-home mr-2"></i>Configurar Homepage
                    </a>
                </div>
            </div>
        </div>

        <!-- Abas de Navegação -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6" aria-label="Tabs">
                    <button type="button" onclick="showTab('geral', event)" 
                            class="tab-button active py-4 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600 transition-all duration-200">
                        <i class="fas fa-cog mr-2"></i>Geral
                    </button>
                    <button type="button" onclick="showTab('pagamento', event)" 
                            class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 transition-all duration-200">
                        <i class="fas fa-credit-card mr-2"></i>Pagamento
                    </button>
                    <button type="button" onclick="showTab('email', event)" 
                            class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 transition-all duration-200">
                        <i class="fas fa-envelope mr-2"></i>Email
                    </button>
                    <button type="button" onclick="showTab('seguranca', event)" 
                            class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 transition-all duration-200">
                        <i class="fas fa-shield-alt mr-2"></i>Segurança
                    </button>
                    <button type="button" onclick="showTab('cache', event)" 
                            class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 transition-all duration-200">
                        <i class="fas fa-database mr-2"></i>Cache & Backup
                    </button>
                </nav>
            </div>

            <!-- Conteúdo das Abas -->
            <div class="p-6">
                <!-- Aba Geral -->
                <div id="tab-geral" class="tab-content">
                    <form action="{{ route('admin.system.settings.update') }}" method="POST" enctype="multipart/form-data" id="form-geral">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="active_tab" value="geral">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Informações Básicas -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Informações Básicas</h3>
                                
                                <div>
                                    <label for="app_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nome da Aplicação *
                                    </label>
                                    <input type="text" 
                                           id="app_name" 
                                           name="app_name" 
                                           value="{{ old('app_name', $configuracoes['app_name'] ?? config('app.name') ?? 'CBAV') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           required>
                                </div>
                                
                                <div>
                                    <label for="app_description" class="block text-sm font-medium text-gray-700 mb-2">
                                        Descrição da Aplicação
                                    </label>
                                    <textarea id="app_description" 
                                              name="app_description" 
                                              rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('app_description', $configuracoes['app_description'] ?? '') }}</textarea>
                                </div>
                                
                                <div>
                                    <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email de Contato
                                    </label>
                                    <input type="email" 
                                           id="contact_email" 
                                           name="contact_email" 
                                           value="{{ old('contact_email', $configuracoes['contact_email'] ?? '') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div>
                                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        Telefone de Contato
                                    </label>
                                    <input type="text" 
                                           id="contact_phone" 
                                           name="contact_phone" 
                                           value="{{ old('contact_phone', $configuracoes['contact_phone'] ?? '') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                        Endereço
                                    </label>
                                    <textarea id="address" 
                                              name="address" 
                                              rows="2"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('address', $configuracoes['address'] ?? '') }}</textarea>
                                </div>
                                
                                <div>
                                    <label for="timezone" class="block text-sm font-medium text-gray-700 mb-2">
                                        Fuso Horário *
                                    </label>
                                    <select id="timezone" 
                                            name="timezone" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            required>
                                        <option value="">Selecione um fuso horário</option>
                                        <optgroup label="Brasil">
                                            <option value="America/Sao_Paulo" {{ (old('timezone', $configuracoes['timezone'] ?? '') == 'America/Sao_Paulo') ? 'selected' : '' }}>
                                                America/Sao_Paulo (Brasília, São Paulo, Rio de Janeiro)
                                            </option>
                                            <option value="America/Manaus" {{ (old('timezone', $configuracoes['timezone'] ?? '') == 'America/Manaus') ? 'selected' : '' }}>
                                                America/Manaus (Manaus, Amazonas)
                                            </option>
                                            <option value="America/Belem" {{ (old('timezone', $configuracoes['timezone'] ?? '') == 'America/Belem') ? 'selected' : '' }}>
                                                America/Belem (Belém, Pará)
                                            </option>
                                            <option value="America/Fortaleza" {{ (old('timezone', $configuracoes['timezone'] ?? '') == 'America/Fortaleza') ? 'selected' : '' }}>
                                                America/Fortaleza (Fortaleza, Ceará)
                                            </option>
                                            <option value="America/Recife" {{ (old('timezone', $configuracoes['timezone'] ?? '') == 'America/Recife') ? 'selected' : '' }}>
                                                America/Recife (Recife, Pernambuco)
                                            </option>
                                            <option value="America/Maceio" {{ (old('timezone', $configuracoes['timezone'] ?? '') == 'America/Maceio') ? 'selected' : '' }}>
                                                America/Maceio (Maceió, Alagoas)
                                            </option>
                                            <option value="America/Aracaju" {{ (old('timezone', $configuracoes['timezone'] ?? '') == 'America/Aracaju') ? 'selected' : '' }}>
                                                America/Aracaju (Aracaju, Sergipe)
                                            </option>
                                            <option value="America/Bahia" {{ (old('timezone', $configuracoes['timezone'] ?? '') == 'America/Bahia') ? 'selected' : '' }}>
                                                America/Bahia (Salvador, Bahia)
                                            </option>
                                            <option value="America/Maceio" {{ (old('timezone', $configuracoes['timezone'] ?? '') == 'America/Maceio') ? 'selected' : '' }}>
                                                America/Maceio (Maceió, Alagoas)
                                            </option>
                                            <option value="America/Recife" {{ (old('timezone', $configuracoes['timezone'] ?? '') == 'America/Recife') ? 'selected' : '' }}>
                                                America/Recife (Recife, Pernambuco)
                                            </option>
                                            <option value="America/Noronha" {{ (old('timezone', $configuracoes['timezone'] ?? '') == 'America/Noronha') ? 'selected' : '' }}>
                                                America/Noronha (Fernando de Noronha)
                                            </option>
                                        </optgroup>
                                        <optgroup label="Internacional">
                                            <option value="UTC" {{ (old('timezone', $configuracoes['timezone'] ?? '') == 'UTC') ? 'selected' : '' }}>
                                                UTC (Tempo Universal Coordenado)
                                            </option>
                                            <option value="America/New_York" {{ (old('timezone', $configuracoes['timezone'] ?? '') == 'America/New_York') ? 'selected' : '' }}>
                                                America/New_York (Nova York, EUA)
                                            </option>
                                            <option value="America/Los_Angeles" {{ (old('timezone', $configuracoes['timezone'] ?? '') == 'America/Los_Angeles') ? 'selected' : '' }}>
                                                America/Los_Angeles (Los Angeles, EUA)
                                            </option>
                                            <option value="Europe/London" {{ (old('timezone', $configuracoes['timezone'] ?? '') == 'Europe/London') ? 'selected' : '' }}>
                                                Europe/London (Londres, Reino Unido)
                                            </option>
                                            <option value="Europe/Paris" {{ (old('timezone', $configuracoes['timezone'] ?? '') == 'Europe/Paris') ? 'selected' : '' }}>
                                                Europe/Paris (Paris, França)
                                            </option>
                                            <option value="Asia/Tokyo" {{ (old('timezone', $configuracoes['timezone'] ?? '') == 'Asia/Tokyo') ? 'selected' : '' }}>
                                                Asia/Tokyo (Tóquio, Japão)
                                            </option>
                                        </optgroup>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <strong>Atual:</strong> {{ now()->format('Y-m-d H:i:s') }} ({{ config('app.timezone') }})
                                    </p>
                                </div>
                                
                                <div>
                                    <label for="locale" class="block text-sm font-medium text-gray-700 mb-2">
                                        Idioma *
                                    </label>
                                    <select id="locale" 
                                            name="locale" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            required>
                                        <option value="">Selecione um idioma</option>
                                        <option value="pt_BR" {{ (old('locale', $configuracoes['locale'] ?? '') == 'pt_BR') ? 'selected' : '' }}>
                                            Português (Brasil)
                                        </option>
                                        <option value="en" {{ (old('locale', $configuracoes['locale'] ?? '') == 'en') ? 'selected' : '' }}>
                                            English
                                        </option>
                                        <option value="es" {{ (old('locale', $configuracoes['locale'] ?? '') == 'es') ? 'selected' : '' }}>
                                            Español
                                        </option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Imagens e Redes Sociais -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Imagens e Redes Sociais</h3>
                                
                                <div>
                                    <label for="app_logo" class="block text-sm font-medium text-gray-700 mb-2">
                                        Logo da Aplicação
                                    </label>
                                    <input type="file" 
                                           id="app_logo" 
                                           name="app_logo" 
                                           accept="image/*"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @if($configuracoes['app_logo'] ?? false)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $configuracoes['app_logo']) }}" 
                                                 alt="Logo atual" 
                                                 class="h-12 w-auto">
                                        </div>
                                    @endif
                                </div>
                                
                                <div>
                                    <label for="app_favicon" class="block text-sm font-medium text-gray-700 mb-2">
                                        Favicon
                                    </label>
                                    <input type="file" 
                                           id="app_favicon" 
                                           name="app_favicon" 
                                           accept="image/*"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @if($configuracoes['app_favicon'] ?? false)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $configuracoes['app_favicon']) }}" 
                                                 alt="Favicon atual" 
                                                 class="h-8 w-8">
                                        </div>
                                    @endif
                                </div>
                                
                                <div>
                                    <label for="social_facebook" class="block text-sm font-medium text-gray-700 mb-2">
                                        Facebook
                                    </label>
                                    <input type="url" 
                                           id="social_facebook" 
                                           name="social_facebook" 
                                           value="{{ old('social_facebook', $configuracoes['social_facebook'] ?? '') }}"
                                           placeholder="https://facebook.com/suaigreja"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div>
                                    <label for="social_instagram" class="block text-sm font-medium text-gray-700 mb-2">
                                        Instagram
                                    </label>
                                    <input type="url" 
                                           id="social_instagram" 
                                           name="social_instagram" 
                                           value="{{ old('social_instagram', $configuracoes['social_instagram'] ?? '') }}"
                                           placeholder="https://instagram.com/suaigreja"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div>
                                    <label for="social_youtube" class="block text-sm font-medium text-gray-700 mb-2">
                                        YouTube
                                    </label>
                                    <input type="url" 
                                           id="social_youtube" 
                                           name="social_youtube" 
                                           value="{{ old('social_youtube', $configuracoes['social_youtube'] ?? '') }}"
                                           placeholder="https://youtube.com/suaigreja"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end">
                            <button type="submit" 
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-save mr-2"></i>Salvar Configurações Gerais
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Aba Pagamento -->
                <div id="tab-pagamento" class="tab-content hidden">
                    <form action="{{ route('admin.system.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="active_tab" value="pagamento">
                        
                        <div class="space-y-6">
                            <!-- Stripe -->
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                    <i class="fab fa-stripe text-blue-600 mr-2"></i>
                                    Stripe
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="stripe_key" class="block text-sm font-medium text-gray-700 mb-2">
                                            Chave Pública (Publishable Key)
                                        </label>
                                        <input type="text" 
                                               id="stripe_key" 
                                               name="stripe_key" 
                                               value="{{ old('stripe_key', $configuracoes['stripe_key'] ?? '') }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="pk_test_...">
                                    </div>
                                    <div>
                                        <label for="stripe_secret" class="block text-sm font-medium text-gray-700 mb-2">
                                            Chave Secreta (Secret Key)
                                        </label>
                                        <input type="password" 
                                               id="stripe_secret" 
                                               name="stripe_secret" 
                                               value="{{ old('stripe_secret', $configuracoes['stripe_secret'] ?? '') }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="sk_test_...">
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Configure suas chaves do Stripe para processar pagamentos com cartão de crédito.</p>
                            </div>

                            <!-- Mercado Pago -->
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-wallet text-green-600 mr-2"></i>
                                    Mercado Pago
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="mercadopago_key" class="block text-sm font-medium text-gray-700 mb-2">
                                            Chave Pública (Public Key)
                                        </label>
                                        <input type="text" 
                                               id="mercadopago_key" 
                                               name="mercadopago_key" 
                                               value="{{ old('mercadopago_key', $configuracoes['mercadopago_key'] ?? '') }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="TEST-...">
                                    </div>
                                    <div>
                                        <label for="mercadopago_token" class="block text-sm font-medium text-gray-700 mb-2">
                                            Token de Acesso (Access Token)
                                        </label>
                                        <input type="password" 
                                               id="mercadopago_token" 
                                               name="mercadopago_token" 
                                               value="{{ old('mercadopago_token', $configuracoes['mercadopago_token'] ?? '') }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="TEST-...">
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Configure suas credenciais do Mercado Pago para processar pagamentos.</p>
                            </div>

                            <!-- PIX -->
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-qrcode text-purple-600 mr-2"></i>
                                    PIX (Apenas para Membros)
                                </h3>
                                <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-info-circle text-yellow-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700">
                                                <strong>Nota:</strong> O PIX está disponível apenas para membros logados. 
                                                Para doações públicas, apenas Stripe e Mercado Pago são permitidos.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="pix_chave" class="block text-sm font-medium text-gray-700 mb-2">
                                            Chave PIX
                                        </label>
                                        <input type="text" 
                                               id="pix_chave" 
                                               name="pix_chave" 
                                               value="{{ old('pix_chave', $configuracoes['pix_chave'] ?? '') }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="chave@exemplo.com">
                                    </div>
                                    <div>
                                        <label for="pix_beneficiario" class="block text-sm font-medium text-gray-700 mb-2">
                                            Nome do Beneficiário
                                        </label>
                                        <input type="text" 
                                               id="pix_beneficiario" 
                                               name="pix_beneficiario" 
                                               value="{{ old('pix_beneficiario', $configuracoes['pix_beneficiario'] ?? '') }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="Nome da Igreja">
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Configure sua chave PIX para receber transferências instantâneas (apenas para membros logados).</p>
                            </div>

                            <!-- Configurações de Doação -->
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-heart text-red-600 mr-2"></i>
                                    Configurações de Doação
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="doacao_valor_minimo" class="block text-sm font-medium text-gray-700 mb-2">
                                            Valor Mínimo de Doação (R$)
                                        </label>
                                        <input type="number" 
                                               id="doacao_valor_minimo" 
                                               name="doacao_valor_minimo" 
                                               value="{{ old('doacao_valor_minimo', $configuracoes['doacao_valor_minimo'] ?? 1) }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               min="0.01" step="0.01">
                                    </div>
                                    <div>
                                        <label for="doacao_valor_maximo" class="block text-sm font-medium text-gray-700 mb-2">
                                            Valor Máximo de Doação (R$)
                                        </label>
                                        <input type="number" 
                                               id="doacao_valor_maximo" 
                                               name="doacao_valor_maximo" 
                                               value="{{ old('doacao_valor_maximo', $configuracoes['doacao_valor_maximo'] ?? 10000) }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               min="0.01" step="0.01">
                                    </div>
                                </div>
                                <div class="mt-4 space-y-2">
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               id="doacao_sem_login" 
                                               name="doacao_sem_login" 
                                               value="1"
                                               {{ ($configuracoes['doacao_sem_login'] ?? '1') == '1' ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <label for="doacao_sem_login" class="ml-2 text-sm text-gray-700">
                                            Permitir doação sem login
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               id="doacao_ativa" 
                                               name="doacao_ativa" 
                                               value="1"
                                               {{ ($configuracoes['doacao_ativa'] ?? '1') == '1' ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <label for="doacao_ativa" class="ml-2 text-sm text-gray-700">
                                            Ativar sistema de doações
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Botões de Ação -->
                            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                                <button type="button" 
                                        onclick="window.location.href='{{ route('admin.system.index') }}'"
                                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                                    Cancelar
                                </button>
                                <button type="submit" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center">
                                    <i class="fas fa-save mr-2"></i>
                                    Salvar Configurações
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Aba Email -->
                <div id="tab-email" class="tab-content hidden">
                    <form action="{{ route('admin.system.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="active_tab" value="email">
                        
                        <div class="space-y-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Configurações de Email</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="mail_host" class="block text-sm font-medium text-gray-700 mb-2">
                                        Servidor SMTP
                                    </label>
                                    <input type="text" 
                                           id="mail_host" 
                                           name="mail_host" 
                                           value="{{ old('mail_host', $configuracoes['mail_host'] ?? '') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="smtp.gmail.com">
                                </div>
                                <div>
                                    <label for="mail_port" class="block text-sm font-medium text-gray-700 mb-2">
                                        Porta
                                    </label>
                                    <input type="number" 
                                           id="mail_port" 
                                           name="mail_port" 
                                           value="{{ old('mail_port', $configuracoes['mail_port'] ?? '587') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="mail_username" class="block text-sm font-medium text-gray-700 mb-2">
                                        Usuário
                                    </label>
                                    <input type="email" 
                                           id="mail_username" 
                                           name="mail_username" 
                                           value="{{ old('mail_username', $configuracoes['mail_username'] ?? '') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="mail_password" class="block text-sm font-medium text-gray-700 mb-2">
                                        Senha
                                    </label>
                                    <input type="password" 
                                           id="mail_password" 
                                           name="mail_password" 
                                           value="{{ old('mail_password', $configuracoes['mail_password'] ?? '') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <!-- Botões de Ação -->
                            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                                <button type="button" 
                                        onclick="window.location.href='{{ route('admin.system.index') }}'"
                                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                                    Cancelar
                                </button>
                                <button type="submit" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center">
                                    <i class="fas fa-save mr-2"></i>
                                    Salvar Configurações
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Aba Segurança -->
                <div id="tab-seguranca" class="tab-content hidden">
                    <form action="{{ route('admin.system.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="active_tab" value="seguranca">
                        
                        <div class="space-y-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Configurações de Segurança</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="session_lifetime" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tempo de Sessão (minutos)
                                    </label>
                                    <input type="number" 
                                           id="session_lifetime" 
                                           name="session_lifetime" 
                                           value="{{ old('session_lifetime', $configuracoes['session_lifetime'] ?? 120) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           min="30" max="1440">
                                </div>
                                <div>
                                    <label for="max_login_attempts" class="block text-sm font-medium text-gray-700 mb-2">
                                        Máximo de Tentativas de Login
                                    </label>
                                    <input type="number" 
                                           id="max_login_attempts" 
                                           name="max_login_attempts" 
                                           value="{{ old('max_login_attempts', $configuracoes['max_login_attempts'] ?? 5) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           min="3" max="10">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="force_ssl" 
                                           name="force_ssl" 
                                           value="1"
                                           {{ ($configuracoes['force_ssl'] ?? '0') == '1' ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <label for="force_ssl" class="ml-2 text-sm text-gray-700">
                                        Forçar HTTPS
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="enable_2fa" 
                                           name="enable_2fa" 
                                           value="1"
                                           {{ ($configuracoes['enable_2fa'] ?? '0') == '1' ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                           onchange="toggle2FASettings()">
                                    <label for="enable_2fa" class="ml-2 text-sm text-gray-700">
                                        Ativar Autenticação de Dois Fatores
                                    </label>
                                </div>
                                
                                <!-- Configurações de 2FA -->
                                <div id="2fa-settings" class="mt-4 p-4 bg-gray-50 rounded-lg {{ ($configuracoes['enable_2fa'] ?? '0') == '1' ? '' : 'hidden' }}">
                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Configurações de 2FA</h4>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="2fa_method" class="block text-sm font-medium text-gray-700 mb-2">
                                                Método de Autenticação
                                            </label>
                                            <select id="2fa_method" 
                                                    name="2fa_method" 
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                <option value="totp" {{ (old('2fa_method', $configuracoes['2fa_method'] ?? '') == 'totp') ? 'selected' : '' }}>
                                                    TOTP (Google Authenticator)
                                                </option>
                                                <option value="sms" {{ (old('2fa_method', $configuracoes['2fa_method'] ?? '') == 'sms') ? 'selected' : '' }}>
                                                    SMS
                                                </option>
                                                <option value="email" {{ (old('2fa_method', $configuracoes['2fa_method'] ?? '') == 'email') ? 'selected' : '' }}>
                                                    Email
                                                </option>
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label for="2fa_grace_period" class="block text-sm font-medium text-gray-700 mb-2">
                                                Período de Graça (dias)
                                            </label>
                                            <input type="number" 
                                                   id="2fa_grace_period" 
                                                   name="2fa_grace_period" 
                                                   value="{{ old('2fa_grace_period', $configuracoes['2fa_grace_period'] ?? 7) }}"
                                                   min="0" max="30"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 space-y-2">
                                        <div class="flex items-center">
                                            <input type="checkbox" 
                                                   id="2fa_remember_device" 
                                                   name="2fa_remember_device" 
                                                   value="1"
                                                   {{ ($configuracoes['2fa_remember_device'] ?? '1') == '1' ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <label for="2fa_remember_device" class="ml-2 text-sm text-gray-700">
                                                Permitir "Lembrar este dispositivo"
                                            </label>
                                        </div>
                                        
                                        <div class="flex items-center">
                                            <input type="checkbox" 
                                                   id="2fa_backup_codes" 
                                                   name="2fa_backup_codes" 
                                                   value="1"
                                                   {{ ($configuracoes['2fa_backup_codes'] ?? '1') == '1' ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <label for="2fa_backup_codes" class="ml-2 text-sm text-gray-700">
                                                Gerar códigos de backup
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-info-circle text-blue-400"></i>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-blue-800">
                                                    Configuração de 2FA
                                                </h3>
                                                <div class="mt-2 text-sm text-blue-700">
                                                    <p>• <strong>TOTP:</strong> Usuários usam apps como Google Authenticator</p>
                                                    <p>• <strong>SMS:</strong> Código enviado por SMS (requer gateway SMS)</p>
                                                    <p>• <strong>Email:</strong> Código enviado por email</p>
                                                    <p>• <strong>Período de Graça:</strong> Tempo para usuários configurarem 2FA</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botões de Ação -->
                            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                                <button type="button" 
                                        onclick="window.location.href='{{ route('admin.system.index') }}'"
                                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                                    Cancelar
                                </button>
                                <button type="submit" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center">
                                    <i class="fas fa-save mr-2"></i>
                                    Salvar Configurações
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Aba Cache & Backup -->
                <div id="tab-cache" class="tab-content hidden">
                    <form action="{{ route('admin.system.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="active_tab" value="cache">
                        
                        <div class="space-y-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Configurações de Cache & Backup</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="cache_driver" class="block text-sm font-medium text-gray-700 mb-2">
                                        Driver de Cache
                                    </label>
                                    <select id="cache_driver" 
                                            name="cache_driver" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="file" {{ (old('cache_driver', $configuracoes['cache_driver'] ?? '') == 'file') ? 'selected' : '' }}>File</option>
                                        <option value="redis" {{ (old('cache_driver', $configuracoes['cache_driver'] ?? '') == 'redis') ? 'selected' : '' }}>Redis</option>
                                        <option value="memcached" {{ (old('cache_driver', $configuracoes['cache_driver'] ?? '') == 'memcached') ? 'selected' : '' }}>Memcached</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="session_driver" class="block text-sm font-medium text-gray-700 mb-2">
                                        Driver de Sessão
                                    </label>
                                    <select id="session_driver" 
                                            name="session_driver" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="file" {{ (old('session_driver', $configuracoes['session_driver'] ?? '') == 'file') ? 'selected' : '' }}>File</option>
                                        <option value="redis" {{ (old('session_driver', $configuracoes['session_driver'] ?? '') == 'redis') ? 'selected' : '' }}>Redis</option>
                                        <option value="database" {{ (old('session_driver', $configuracoes['session_driver'] ?? '') == 'database') ? 'selected' : '' }}>Database</option>
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="backup_enabled" 
                                           name="backup_enabled" 
                                           value="1"
                                           {{ ($configuracoes['backup_enabled'] ?? '1') == '1' ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <label for="backup_enabled" class="ml-2 text-sm text-gray-700">
                                        Ativar backup automático
                                    </label>
                                </div>
                            </div>

                            <!-- Botões de Ação -->
                            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                                <button type="button" 
                                        onclick="window.location.href='{{ route('admin.system.index') }}'"
                                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                                    Cancelar
                                </button>
                                <button type="submit" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center">
                                    <i class="fas fa-save mr-2"></i>
                                    Salvar Configurações
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Sistema de abas melhorado
let currentTab = 'geral';

function showTab(tabName, event) {
    // Ocultar todas as abas
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(tab => {
        tab.classList.add('hidden');
    });
    
    // Remover classe active de todos os botões
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.classList.remove('active', 'border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Mostrar aba selecionada
    const targetTab = document.getElementById('tab-' + tabName);
    if (targetTab) {
        targetTab.classList.remove('hidden');
    }
    
    // Ativar botão selecionado
    const targetButton = event ? event.target : document.querySelector(`[onclick*="${tabName}"]`);
    if (targetButton && targetButton.classList) {
        targetButton.classList.add('active', 'border-blue-500', 'text-blue-600');
        targetButton.classList.remove('border-transparent', 'text-gray-500');
    }
    
    // Salvar aba atual
    currentTab = tabName;
}

// Função para mostrar/ocultar configurações de 2FA
function toggle2FASettings() {
    const checkbox = document.getElementById('enable_2fa');
    const settingsDiv = document.getElementById('2fa-settings');
    
    if (checkbox.checked) {
        settingsDiv.classList.remove('hidden');
    } else {
        settingsDiv.classList.add('hidden');
    }
}

function limparCache() {
    if (typeof showConfirmModal === 'function') {
        showConfirmModal(
            'Limpar Cache',
            'Tem certeza que deseja limpar todo o cache do sistema? Isso pode afetar temporariamente a performance.',
            function() {
                fetch('{{ route("admin.system.clearCache") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (typeof showSuccessModal === 'function') {
                            showSuccessModal('Cache limpo com sucesso! O sistema foi otimizado.');
                        } else {
                            alert('Cache limpo com sucesso!');
                        }
                    } else {
                        if (typeof showErrorModal === 'function') {
                            showErrorModal(data.message || 'Erro ao limpar cache.');
                        } else {
                            alert('Erro ao limpar cache: ' + (data.message || 'Erro desconhecido'));
                        }
                    }
                }).catch(error => {
                    console.error('Erro:', error);
                    if (typeof showErrorModal === 'function') {
                        showErrorModal('Erro ao limpar cache. Tente novamente.');
                    } else {
                        alert('Erro ao limpar cache. Tente novamente.');
                    }
                });
            }
        );
    } else {
        alert('Função de confirmação não disponível');
    }
}

function testarConfiguracoes() {
    if (typeof showInfoModal === 'function') {
        showInfoModal(
            'Testando Configurações',
            'Testando conectividade e configurações do sistema...'
        );
    }
    
    fetch('{{ route("admin.system.test") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof showSuccessModal === 'function') {
                showSuccessModal('Configurações testadas com sucesso! Todos os serviços estão funcionando.');
            } else {
                alert('Configurações testadas com sucesso!');
            }
        } else {
            if (typeof showErrorModal === 'function') {
                showErrorModal(data.message || 'Erro ao testar configurações.');
            } else {
                alert('Erro ao testar configurações: ' + (data.message || 'Erro desconhecido'));
            }
        }
    }).catch(error => {
        console.error('Erro:', error);
        if (typeof showErrorModal === 'function') {
            showErrorModal('Erro ao testar configurações. Tente novamente.');
        } else {
            alert('Erro ao testar configurações. Tente novamente.');
        }
    });
}

// Submissão de formulários com feedback
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                const originalText = submitButton.innerHTML;
                
                // Mostrar loading
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Salvando...';
                submitButton.disabled = true;
                
                // Reset após 5 segundos se houver erro
                setTimeout(() => {
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                }, 5000);
            }
        });
    });
    
    // Verificar se há erros de validação
    @if($errors->any())
        let errorMessages = [];
        @foreach($errors->all() as $error)
            errorMessages.push('{{ $error }}');
        @endforeach
        
        if (errorMessages.length > 0) {
            if (typeof showErrorModal === 'function') {
                showErrorModal('Erro de Validação', errorMessages.join('\n'));
            } else {
                alert('Erros de validação:\n' + errorMessages.join('\n'));
            }
        }
    @endif
    
    // Mostrar mensagens de sucesso do Laravel automaticamente
    @if(session('success'))
        if (typeof showSuccessModal === 'function') {
            showSuccessModal('{{ session("success") }}');
        } else {
            alert('{{ session("success") }}');
        }
    @endif

    @if(session('error'))
        if (typeof showErrorModal === 'function') {
            showErrorModal('{{ session("error") }}');
        } else {
            alert('{{ session("error") }}');
        }
    @endif

    @if(session('info'))
        if (typeof showInfoModal === 'function') {
            showInfoModal('{{ session("info") }}', '{{ session("info") }}');
        } else {
            alert('{{ session("info") }}');
        }
    @endif
    
    // Restaurar aba ativa se especificada
    @if(old('active_tab'))
        showTab('{{ old("active_tab") }}', null);
    @elseif(session('active_tab'))
        showTab('{{ session("active_tab") }}', null);
    @endif
});
</script>

<!-- Incluir modais -->
@include('components.modals')
@endsection 