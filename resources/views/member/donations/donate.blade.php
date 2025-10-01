@extends('layouts.member')

@section('title', 'Fazer Doação')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-8">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('member.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ route('member.donations.index') }}" class="hover:text-blue-600">Doações</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-900 font-medium">Nova Doação</li>
        </ol>
    </nav>

    <!-- Header com Frase Bíblica -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    <svg class="w-8 h-8 text-red-600 mr-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    Fazer Doação
                </h1>
                <p class="text-gray-600 mb-2">Escolha uma campanha e faça sua doação de forma segura e rápida</p>
                <div class="bg-gradient-to-r from-green-50 to-blue-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                    <p class="text-sm text-green-800 italic">
                        <i class="fas fa-quote-left mr-2"></i>
                        "Dê, e será dado a vocês: uma boa medida, calcada, sacudida e transbordante será colocada no colo de vocês." 
                        <span class="font-semibold">- Lucas 6:38</span>
                    </p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('member.donations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <!-- Guia de Passos Melhorado -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-600 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-blue-900">{{ __('Como fazer sua doação:') }}</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-medium mr-3">1</div>
                                <span>{{ __('Escolha uma campanha (opcional) ou faça uma doação geral') }}</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-medium mr-3">2</div>
                                <span>{{ __('Digite o valor que deseja doar') }}</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-medium mr-3">3</div>
                                <span>{{ __('Selecione a forma de pagamento mais conveniente') }}</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-medium mr-3">4</div>
                                <span>{{ __('Clique em "Fazer Doação" e siga as instruções') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-sm text-yellow-800">
                        <i class="fas fa-lightbulb text-yellow-600 mr-2"></i>
                        <strong>{{ __('Dica:') }}</strong> {{ __('Todas as doações são processadas com segurança. Você receberá um comprovante por email após a confirmação.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Formulário de Doação Melhorado -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-credit-card text-blue-600 mr-2"></i>
                        {{ __('Informações da Doação') }}
                    </h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('member.donations.donate.process') }}" method="POST" id="donationForm">
                        @csrf
                        
                        <!-- Campanha Selecionada -->
                        @if($campanha)
                            <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg border border-blue-200">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-semibold text-blue-900">{{ __('Campanha Selecionada') }}</h4>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>{{ __('Ativa') }}
                                    </span>
                                </div>
                                <div class="flex items-center">
                                    @if($campanha->imagem)
                                        <img src="{{ asset('storage/' . $campanha->imagem) }}" 
                                             alt="{{ $campanha->titulo }}" 
                                             class="w-16 h-16 object-cover rounded-lg mr-4">
                                    @else
                                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-4">
                                            <i class="fas fa-heart text-white"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <h5 class="font-medium text-gray-900">{{ $campanha->titulo }}</h5>
                                        <p class="text-sm text-gray-600">{{ Str::limit($campanha->descricao, 100) }}</p>
                                        @if($campanha->meta_valor > 0)
                                            <div class="mt-2">
                                                <div class="flex justify-between text-xs text-gray-500 mb-1">
                                                    <span>{{ __('Progresso') }}</span>
                                                    <span>{{ $campanha->progresso }}%</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $campanha->progresso }}%"></div>
                                                </div>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    {{ __('Arrecadado:') }} R$ {{ number_format($campanha->valor_arrecadado, 2, ',', '.') }} / {{ __('Meta:') }} R$ {{ number_format($campanha->meta_valor, 2, ',', '.') }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <input type="hidden" name="campanha_id" value="{{ $campanha->id }}">
                            </div>
                        @else
                            <!-- Seleção de Campanha Melhorada -->
                            <div class="mb-6">
                                <label for="campanha_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('Campanha (Opcional)') }}
                                </label>
                                <select id="campanha_id" name="campanha_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">{{ __('Doação Geral - Sua doação será usada onde for mais necessária') }}</option>
                                    @foreach($campanhas as $camp)
                                        <option value="{{ $camp->id }}" {{ old('campanha_id') == $camp->id ? 'selected' : '' }}>
                                            {{ $camp->titulo }} {{ $camp->meta_valor > 0 ? '(' . $camp->progresso . '% concluída)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-sm text-gray-500 mt-1">
                                    <i class="fas fa-lightbulb text-yellow-500 mr-1"></i>
                                    {{ __('Dica:') }} {{ __('Escolha uma campanha específica ou deixe em branco para doação geral') }}
                                </p>
                            </div>
                        @endif

                        <!-- Valor Melhorado -->
                        <div class="mb-6">
                            <label for="valor" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Valor da Doação') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">R$</span>
                                <input type="text" 
                                       id="valor" 
                                       name="valor" 
                                       value="{{ old('valor') }}"
                                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="0,00"
                                       required>
                            </div>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <button type="button" class="quick-value-btn px-3 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded-full transition-colors" data-value="10.00">R$ 10,00</button>
                                <button type="button" class="quick-value-btn px-3 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded-full transition-colors" data-value="25.00">R$ 25,00</button>
                                <button type="button" class="quick-value-btn px-3 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded-full transition-colors" data-value="50.00">R$ 50,00</button>
                                <button type="button" class="quick-value-btn px-3 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded-full transition-colors" data-value="100.00">R$ 100,00</button>
                                <button type="button" class="quick-value-btn px-3 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded-full transition-colors" data-value="200.00">R$ 200,00</button>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">
                                <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                                {{ __('Clique nos valores acima para preencher automaticamente') }}
                            </p>
                            @error('valor')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Descrição Melhorada -->
                        <div class="mb-6">
                            <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Mensagem (Opcional)') }}
                            </label>
                            <textarea id="descricao" 
                                      name="descricao" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="{{ __('Deixe uma mensagem ou motivo da doação...') }}">{{ old('descricao') }}</textarea>
                            <p class="text-sm text-gray-500 mt-1">
                                <i class="fas fa-comment text-blue-500 mr-1"></i>
                                {{ __('Sua mensagem será vista apenas pelos administradores') }}
                            </p>
                            @error('descricao')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Método de Pagamento Melhorado -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Forma de Pagamento') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Stripe -->
                                <label class="payment-method-card relative flex cursor-pointer rounded-lg border border-gray-300 bg-white p-4 shadow-sm focus:outline-none hover:border-blue-300 transition-colors">
                                    <input type="radio" name="gateway" value="stripe" class="sr-only" {{ old('gateway') == 'stripe' ? 'checked' : '' }} required>
                                    <span class="flex flex-1">
                                        <span class="flex flex-col">
                                            <span class="block text-sm font-medium text-gray-900">{{ __('Cartão de Crédito') }}</span>
                                            <span class="mt-1 flex items-center text-sm text-gray-500">
                                                <i class="fab fa-stripe text-blue-600 mr-2"></i>Stripe
                                            </span>
                                            <span class="mt-1 text-xs text-gray-400">{{ __('Aceita todos os cartões') }}</span>
                                        </span>
                                    </span>
                                    <span class="pointer-events-none absolute -inset-px rounded-lg border-2" aria-hidden="true"></span>
                                </label>

                                <!-- Mercado Pago -->
                                <label class="payment-method-card relative flex cursor-pointer rounded-lg border border-gray-300 bg-white p-4 shadow-sm focus:outline-none hover:border-blue-300 transition-colors">
                                    <input type="radio" name="gateway" value="mercadopago" class="sr-only" {{ old('gateway') == 'mercadopago' ? 'checked' : '' }} required>
                                    <span class="flex flex-1">
                                        <span class="flex flex-col">
                                            <span class="block text-sm font-medium text-gray-900">{{ __('Mercado Pago') }}</span>
                                            <span class="mt-1 flex items-center text-sm text-gray-500">
                                                <i class="fas fa-credit-card text-green-600 mr-2"></i>{{ __('Cartão/PIX') }}
                                            </span>
                                            <span class="mt-1 text-xs text-gray-400">{{ __('Cartão, PIX ou boleto') }}</span>
                                        </span>
                                    </span>
                                    <span class="pointer-events-none absolute -inset-px rounded-lg border-2" aria-hidden="true"></span>
                                </label>

                                <!-- PIX -->
                                <label class="payment-method-card relative flex cursor-pointer rounded-lg border border-gray-300 bg-white p-4 shadow-sm focus:outline-none hover:border-blue-300 transition-colors">
                                    <input type="radio" name="gateway" value="pix" class="sr-only" {{ old('gateway') == 'pix' ? 'checked' : '' }} required>
                                    <span class="flex flex-1">
                                        <span class="flex flex-col">
                                            <span class="block text-sm font-medium text-gray-900">{{ __('PIX') }}</span>
                                            <span class="mt-1 flex items-center text-sm text-gray-500">
                                                <i class="fas fa-qrcode text-purple-600 mr-2"></i>{{ __('Transferência') }}
                                            </span>
                                            <span class="mt-1 text-xs text-gray-400">{{ __('Pagamento instantâneo') }}</span>
                                        </span>
                                    </span>
                                    <span class="pointer-events-none absolute -inset-px rounded-lg border-2" aria-hidden="true"></span>
                                </label>
                            </div>
                            
                            <!-- Dicas de Pagamento Melhoradas -->
                            <div id="payment-tips" class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg hidden">
                                <div class="flex items-start">
                                    <i class="fas fa-lightbulb text-yellow-600 mt-1 mr-2"></i>
                                    <div class="text-sm text-yellow-800">
                                        <h4 class="font-medium mb-1" id="tip-title">{{ __('Dicas de Pagamento') }}</h4>
                                        <p id="tip-content">{{ __('Selecione uma forma de pagamento para ver dicas específicas') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            @error('gateway')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Resumo da Doação Melhorado -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-2">{{ __('Resumo da Doação') }}</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('Campanha:') }}</span>
                                    <span id="summary-campaign" class="font-medium">{{ __('Doação Geral') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('Valor:') }}</span>
                                    <span id="summary-value" class="font-medium text-green-600">R$ 0,00</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('Forma de Pagamento:') }}</span>
                                    <span id="summary-payment" class="font-medium">{{ __('Não selecionado') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Botões Melhorados -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('member.donations.index') }}" 
                               class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                                {{ __('Cancelar') }}
                            </a>
                            <button type="submit" 
                                    id="submit-btn"
                                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-heart mr-2"></i>{{ __('Fazer Doação') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Melhorada -->
        <div class="space-y-6">
            <!-- Campanhas Disponíveis -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-list text-blue-600 mr-2"></i>
                        {{ __('Campanhas Disponíveis') }}
                    </h3>
                </div>
                <div class="p-6">
                    @if($campanhas->count() > 0)
                        <div class="space-y-4">
                            @foreach($campanhas->take(5) as $camp)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                                    <div class="flex items-center">
                                        @if($camp->imagem)
                                            <img src="{{ asset('storage/' . $camp->imagem) }}" 
                                                 alt="{{ $camp->titulo }}" 
                                                 class="w-12 h-12 object-cover rounded-lg mr-3">
                                        @else
                                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-heart text-white text-sm"></i>
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <h5 class="font-medium text-gray-900 text-sm">{{ $camp->titulo }}</h5>
                                            @if($camp->meta_valor > 0)
                                                <div class="mt-1">
                                                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                                                        <span>{{ $camp->progresso }}%</span>
                                                        <span>R$ {{ number_format($camp->valor_arrecadado, 0, ',', '.') }}</span>
                                                    </div>
                                                    <div class="w-full bg-gray-200 rounded-full h-1">
                                                        <div class="bg-blue-600 h-1 rounded-full" style="width: {{ $camp->progresso }}%"></div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ route('member.donations.donate', ['campanha_id' => $camp->id]) }}" 
                                       class="mt-3 w-full inline-flex items-center justify-center px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition-colors">
                                        {{ __('Doar para esta campanha') }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        @if($campanhas->count() > 5)
                            <div class="mt-4 text-center">
                                <a href="{{ route('member.donations.campaigns') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                    {{ __('Ver todas as campanhas') }}
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-6">
                            <i class="fas fa-heart text-3xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500 text-sm">{{ __('Nenhuma campanha ativa no momento') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Informações de Segurança Melhoradas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Segurança e Transparência') }}</h3>
                <div class="space-y-3 text-sm text-gray-600">
                    <div class="flex items-start">
                        <i class="fas fa-shield-alt text-green-600 mr-2 mt-1"></i>
                        <span>{{ __('Sua doação é processada com segurança SSL') }}</span>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-receipt text-blue-600 mr-2 mt-1"></i>
                        <span>{{ __('Você receberá um comprovante por email') }}</span>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-lock text-purple-600 mr-2 mt-1"></i>
                        <span>{{ __('Seus dados estão protegidos e não são compartilhados') }}</span>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-eye text-orange-600 mr-2 mt-1"></i>
                        <span>{{ __('Todas as transações são auditáveis') }}</span>
                    </div>
                </div>
            </div>

            <!-- Dúvidas Frequentes Melhoradas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Dúvidas Frequentes') }}</h3>
                <div class="space-y-3 text-sm">
                    <details class="group">
                        <summary class="flex justify-between items-center cursor-pointer text-gray-700 hover:text-gray-900">
                            <span>{{ __('Como funciona a doação?') }}</span>
                            <i class="fas fa-chevron-down text-gray-400 group-open:rotate-180 transition-transform"></i>
                        </summary>
                        <p class="mt-2 text-gray-600">{{ __('Escolha o valor, a campanha (opcional) e a forma de pagamento. Após o pagamento, você receberá um comprovante por email.') }}</p>
                    </details>
                    <details class="group">
                        <summary class="flex justify-between items-center cursor-pointer text-gray-700 hover:text-gray-900">
                            <span>{{ __('É seguro doar online?') }}</span>
                            <i class="fas fa-chevron-down text-gray-400 group-open:rotate-180 transition-transform"></i>
                        </summary>
                        <p class="mt-2 text-gray-600">{{ __('Sim! Utilizamos gateways de pagamento seguros e certificados. Suas informações estão protegidas.') }}</p>
                    </details>
                    <details class="group">
                        <summary class="flex justify-between items-center cursor-pointer text-gray-700 hover:text-gray-900">
                            <span>{{ __('Posso cancelar uma doação?') }}</span>
                            <i class="fas fa-chevron-down text-gray-400 group-open:rotate-180 transition-transform"></i>
                        </summary>
                        <p class="mt-2 text-gray-600">{{ __('Doações confirmadas não podem ser canceladas. Em caso de erro, entre em contato conosco.') }}</p>
                    </details>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const valorInput = document.getElementById('valor');
    const quickValueBtns = document.querySelectorAll('.quick-value-btn');
    const paymentCards = document.querySelectorAll('.payment-method-card');
    const paymentTips = document.getElementById('payment-tips');
    const tipTitle = document.getElementById('tip-title');
    const tipContent = document.getElementById('tip-content');
    const submitBtn = document.getElementById('submit-btn');
    
    // Formatação do valor
    valorInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        value = (parseFloat(value) / 100).toFixed(2);
        value = value.replace('.', ',');
        value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        e.target.value = value;
        updateSummary();
    });

    // Botões de valor rápido
    quickValueBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const value = this.dataset.value;
            // Converter o valor para formato brasileiro (10.00 -> 10,00)
            const valorFormatado = parseFloat(value).toFixed(2).replace('.', ',');
            valorInput.value = valorFormatado;
            updateSummary();
            
            // Destacar botão selecionado
            quickValueBtns.forEach(b => b.classList.remove('bg-blue-100', 'text-blue-800'));
            this.classList.add('bg-blue-100', 'text-blue-800');
        });
    });

    // Seleção de método de pagamento
    paymentCards.forEach(card => {
        const radio = card.querySelector('input[type="radio"]');
        
        card.addEventListener('click', function() {
            // Marcar o radio button
            radio.checked = true;
            
            // Remover seleção anterior
            paymentCards.forEach(c => {
                c.classList.remove('border-blue-500', 'ring-2', 'ring-blue-500');
                c.classList.add('border-gray-300');
            });
            
            // Selecionar atual
            this.classList.add('border-blue-500', 'ring-2', 'ring-blue-500');
            this.classList.remove('border-gray-300');
            showPaymentTips(radio.value);
        });
        
        // Mostrar dicas se já estiver selecionado
        if (radio.checked) {
            card.classList.add('border-blue-500', 'ring-2', 'ring-blue-500');
            card.classList.remove('border-gray-300');
            showPaymentTips(radio.value);
        }
    });

    // Mostrar dicas de pagamento
    function showPaymentTips(gateway) {
        const tips = {
            stripe: {
                title: '{{ __("Cartão de Crédito - Stripe") }}',
                content: '{{ __("Aceita todos os principais cartões de crédito. Pagamento processado instantaneamente com máxima segurança.") }}'
            },
            mercadopago: {
                title: '{{ __("Mercado Pago") }}',
                content: '{{ __("Aceita cartões, PIX e boleto bancário. Ideal para quem prefere pagamento brasileiro.") }}'
            },
            pix: {
                title: '{{ __("PIX - Pagamento Instantâneo") }}',
                content: '{{ __("Transferência instantânea via PIX. Você receberá um QR Code para escanear com seu app bancário.") }}'
            }
        };
        
        if (tips[gateway]) {
            tipTitle.textContent = tips[gateway].title;
            tipContent.textContent = tips[gateway].content;
            paymentTips.classList.remove('hidden');
        } else {
            paymentTips.classList.add('hidden');
        }
        
        updateSummary();
    }

    // Atualizar resumo
    function updateSummary() {
        const valor = valorInput.value || '0,00';
        const selectedPayment = document.querySelector('input[name="gateway"]:checked');
        const selectedCampaign = document.querySelector('#campanha_id option:checked');
        
        document.getElementById('summary-value').textContent = `R$ ${valor}`;
        
        if (selectedPayment) {
            const paymentLabels = {
                stripe: '{{ __("Cartão de Crédito") }}',
                mercadopago: '{{ __("Mercado Pago") }}',
                pix: '{{ __("PIX") }}'
            };
            document.getElementById('summary-payment').textContent = paymentLabels[selectedPayment.value];
        } else {
            document.getElementById('summary-payment').textContent = '{{ __("Não selecionado") }}';
        }
        
        if (selectedCampaign && selectedCampaign.value) {
            document.getElementById('summary-campaign').textContent = selectedCampaign.textContent;
        } else {
            document.getElementById('summary-campaign').textContent = '{{ __("Doação Geral") }}';
        }
        
        // Habilitar/desabilitar botão
        const valorNum = parseFloat(valor.replace(',', '.'));
        const hasPayment = selectedPayment !== null;
        
        if (valorNum >= 0.01 && hasPayment) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    // Validação do formulário
    document.getElementById('donationForm').addEventListener('submit', function(e) {
        const valor = valorInput.value.replace(/\./g, '').replace(',', '.');
        const valorNum = parseFloat(valor);
        const gateway = document.querySelector('input[name="gateway"]:checked');
        
        if (!gateway) {
            e.preventDefault();
            showNotification('{{ __("Por favor, selecione uma forma de pagamento.") }}', 'error');
            return;
        }
        
        if (valorNum < 0.01) {
            e.preventDefault();
            showNotification('{{ __("Por favor, insira um valor válido (mínimo R$ 0,01).") }}', 'error');
            return;
        }
        
        // Converter valor para formato numérico antes de enviar
        valorInput.value = valorNum.toFixed(2).replace('.', ',');
        
        // Mostrar loading
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>{{ __("Processando...") }}';
        submitBtn.disabled = true;
    });

    // Função para mostrar notificações
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
            type === 'error' ? 'bg-red-600 text-white' : 'bg-blue-600 text-white'
        }`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 5000);
    }

    // Inicializar resumo
    updateSummary();
});
</script>
@endpush
@endsection 