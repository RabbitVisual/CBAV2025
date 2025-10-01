@extends('layouts.member')

@section('title', 'Minhas Doações')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-8">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('member.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-900 font-medium">Minhas Doações</li>
        </ol>
    </nav>

    <!-- Header com Frase Bíblica -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    <svg class="w-8 h-8 text-blue-600 mr-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    Minhas Doações
                </h1>
                <p class="text-gray-600 mb-2">Acompanhe seu histórico de contribuições e faça novas doações de forma segura e transparente.</p>
                <div class="bg-gradient-to-r from-green-50 to-blue-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                    <p class="text-sm text-green-800 italic">
                        <i class="fas fa-quote-left mr-2"></i>
                        "Cada um deve dar conforme determinou em seu coração, não com pesar ou por obrigação, pois Deus ama quem dá com alegria." 
                        <span class="font-semibold">- 2 Coríntios 9:7</span>
                    </p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('member.donations.verificar-comprovante') }}" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Verificar Comprovante
                </a>
                <a href="{{ route('member.donations.history') }}" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Histórico Completo
                </a>
                <a href="{{ route('member.donations.donate') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nova Doação
                </a>
            </div>
        </div>
    </div>

    <!-- Guia Informativo Melhorado -->
    <div class="bg-gradient-to-r from-blue-50 to-purple-50 border border-blue-200 rounded-lg p-6 mb-8">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-600 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-blue-900">{{ __('Como funciona o sistema de doações:') }}</h3>
                <div class="mt-2 text-sm text-blue-700 space-y-1">
                    <p><i class="fas fa-check-circle text-green-500 mr-2"></i>{{ __('Escolha entre doação geral ou para campanhas específicas') }}</p>
                    <p><i class="fas fa-shield-alt text-blue-500 mr-2"></i>{{ __('Múltiplas formas de pagamento seguras (Cartão, PIX, Mercado Pago)') }}</p>
                    <p><i class="fas fa-envelope text-purple-500 mr-2"></i>{{ __('Comprovante enviado automaticamente por email') }}</p>
                    <p><i class="fas fa-chart-line text-orange-500 mr-2"></i>{{ __('Acompanhe o progresso das campanhas em tempo real') }}</p>
                </div>
                <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-sm text-yellow-800">
                        <i class="fas fa-lightbulb text-yellow-600 mr-2"></i>
                        <strong>{{ __('Dica:') }}</strong> {{ __('Doações pendentes podem ser pagas clicando no ícone do olho para ver os detalhes e QR Code.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Total Doado') }}</p>
                    <p class="text-2xl font-bold text-gray-900">R$ {{ number_format($totalDoado, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Doações Confirmadas') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $doacoesConfirmadas }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Pendentes') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $doacoesPendentes }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-calendar text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Este Mês') }}</p>
                    <p class="text-2xl font-bold text-gray-900">R$ {{ number_format($doacoesMes, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Ações Rápidas Melhoradas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200 hover:border-blue-300 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1">
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-heart text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Fazer Doação') }}</h3>
                <p class="text-gray-600 text-sm mb-4">{{ __('Contribua para campanhas específicas ou faça uma doação geral') }}</p>
                <a href="{{ route('member.donations.donate') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>{{ __('Doar Agora') }}
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200 hover:border-green-300 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1">
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-list text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Ver Campanhas') }}</h3>
                <p class="text-gray-600 text-sm mb-4">{{ __('Conheça as campanhas ativas e seus objetivos') }}</p>
                <a href="{{ route('member.donations.campaigns') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-eye mr-2"></i>{{ __('Ver Campanhas') }}
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200 hover:border-purple-300 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1">
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-history text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Histórico Completo') }}</h3>
                <p class="text-gray-600 text-sm mb-4">{{ __('Visualize todas as suas doações e estatísticas detalhadas') }}</p>
                <a href="{{ route('member.donations.history') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                    <i class="fas fa-chart-bar mr-2"></i>{{ __('Ver Histórico') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Filtros Melhorados -->
    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-filter text-blue-600 mr-2"></i>
                {{ __('Filtrar Doações') }}
            </h3>
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Status') }}</label>
                    <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('Todos') }}</option>
                        <option value="confirmado" {{ request('status') == 'confirmado' ? 'selected' : '' }}>{{ __('Confirmado') }}</option>
                        <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>{{ __('Pendente') }}</option>
                        <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>{{ __('Cancelado') }}</option>
                    </select>
                </div>

                <div>
                    <label for="campanha" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Campanha') }}</label>
                    <select id="campanha" name="campanha" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('Todas') }}</option>
                        @foreach($campanhas as $campanha)
                            <option value="{{ $campanha->id }}" {{ request('campanha') == $campanha->id ? 'selected' : '' }}>
                                {{ $campanha->titulo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="data_inicio" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Data Início') }}</label>
                    <input type="date" id="data_inicio" name="data_inicio" value="{{ request('data_inicio') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="data_fim" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Data Fim') }}</label>
                    <input type="date" id="data_fim" name="data_fim" value="{{ request('data_fim') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="md:col-span-4 flex justify-end space-x-3">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i>{{ __('Filtrar') }}
                    </button>
                    <a href="{{ route('member.donations.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-times mr-2"></i>{{ __('Limpar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Doações Melhorada -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-list text-blue-600 mr-2"></i>
                {{ __('Histórico de Doações') }}
            </h3>
        </div>
        <div class="p-6">
            @if($doacoes->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Data') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Campanha') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Valor') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Método') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Status') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Ações') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($doacoes as $doacao)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ $doacao->created_at->format('d/m/Y') }}</span>
                                        <span class="text-gray-500">{{ $doacao->created_at->format('H:i') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($doacao->campanha)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-heart mr-1"></i>
                                            {{ $doacao->campanha->titulo }}
                                        </span>
                                    @else
                                        <span class="text-gray-500">{{ __('Doação Geral') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    R$ {{ number_format($doacao->valor, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="inline-flex items-center">
                                        @switch($doacao->dados_extras['gateway'] ?? 'N/A')
                                            @case('stripe')
                                                <i class="fab fa-stripe text-blue-600 mr-2"></i>{{ __('Cartão') }}
                                                @break
                                            @case('mercadopago')
                                                <i class="fas fa-credit-card text-green-600 mr-2"></i>{{ __('Mercado Pago') }}
                                                @break
                                            @case('pix')
                                                <i class="fas fa-qrcode text-purple-600 mr-2"></i>{{ __('PIX') }}
                                                @break
                                            @default
                                                <i class="fas fa-money-bill text-gray-600 mr-2"></i>{{ __('N/A') }}
                                        @endswitch
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @switch($doacao->status)
                                        @case('confirmado')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check mr-1"></i>{{ __('Confirmado') }}
                                            </span>
                                            @break
                                        @case('pendente')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i>{{ __('Pendente') }}
                                            </span>
                                            @break
                                        @case('cancelado')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times mr-1"></i>{{ __('Cancelado') }}
                                            </span>
                                            @break
                                        @default
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ ucfirst($doacao->status) }}
                                            </span>
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex space-x-2">
                                        @if($doacao->status == 'confirmado')
                                            <button onclick="baixarComprovante({{ $doacao->id }})" 
                                                    class="text-blue-600 hover:text-blue-900 transition-colors" 
                                                    title="{{ __('Baixar Comprovante') }}">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        @endif
                                        <button onclick="verDetalhes({{ $doacao->id }})" 
                                                class="text-gray-600 hover:text-gray-900 transition-colors" 
                                                title="{{ __('Ver Detalhes') }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <div class="mt-6">
                    {{ $doacoes->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="mb-6">
                        <i class="fas fa-hand-holding-heart text-gray-300 text-6xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Nenhuma doação encontrada') }}</h3>
                    <p class="text-gray-500 mb-6">{{ __('Você ainda não fez nenhuma doação ou não há registros com os filtros aplicados.') }}</p>
                    <div class="space-x-4">
                        <a href="{{ route('member.donations.donate') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>{{ __('Fazer Primeira Doação') }}
                        </a>
                        <a href="{{ route('member.donations.campaigns') }}" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-heart mr-2"></i>{{ __('Ver Campanhas') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Informações Adicionais Melhoradas -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Segurança -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-shield-alt text-green-600 mr-2"></i>
                {{ __('Segurança') }}
            </h3>
            <div class="space-y-3 text-sm text-gray-600">
                <div class="flex items-start">
                    <i class="fas fa-lock text-blue-600 mr-2 mt-1"></i>
                    <span>{{ __('Dados protegidos com criptografia SSL') }}</span>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-credit-card text-green-600 mr-2 mt-1"></i>
                    <span>{{ __('Gateways de pagamento certificados') }}</span>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-receipt text-purple-600 mr-2 mt-1"></i>
                    <span>{{ __('Comprovante automático por email') }}</span>
                </div>
            </div>
        </div>

        <!-- Transparência -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-eye text-blue-600 mr-2"></i>
                {{ __('Transparência') }}
            </h3>
            <div class="space-y-3 text-sm text-gray-600">
                <div class="flex items-start">
                    <i class="fas fa-chart-line text-green-600 mr-2 mt-1"></i>
                    <span>{{ __('Progresso das campanhas em tempo real') }}</span>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-history text-orange-600 mr-2 mt-1"></i>
                    <span>{{ __('Histórico completo de doações') }}</span>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-file-alt text-purple-600 mr-2 mt-1"></i>
                    <span>{{ __('Relatórios detalhados disponíveis') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Detalhes da Transação -->
<div id="transactionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('Detalhes da Doação') }}</h3>
                    <button onclick="fecharModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div id="transactionDetails" class="space-y-4">
                    <!-- Conteúdo será carregado dinamicamente -->
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function baixarComprovante(doacaoId) {
    window.open(`/member/donations/transaction/${doacaoId}/comprovante`, '_blank');
}

function verDetalhes(doacaoId) {
    const modal = document.getElementById('transactionModal');
    const detailsDiv = document.getElementById('transactionDetails');
    
    // Mostrar loading
    detailsDiv.innerHTML = `
        <div class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-600">{{ __('Carregando detalhes...') }}</p>
        </div>
    `;
    
    modal.classList.remove('hidden');
    
    // Buscar detalhes
    fetch(`/member/donations/transaction/${doacaoId}/details`)
        .then(response => response.json())
        .then(data => {
            let html = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">{{ __('Informações da Doação') }}</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('ID da Transação:') }}</span>
                                <span class="font-medium">#${data.transacao.id}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('Valor:') }}</span>
                                <span class="font-medium text-green-600">R$ ${data.transacao.valor}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('Status:') }}</span>
                                <span class="font-medium ${data.transacao.status === 'confirmado' ? 'text-green-600' : data.transacao.status === 'pendente' ? 'text-yellow-600' : 'text-red-600'}">${data.transacao.status}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('Data:') }}</span>
                                <span class="font-medium">${data.transacao.data}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('Método:') }}</span>
                                <span class="font-medium">${data.transacao.gateway}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('Campanha:') }}</span>
                                <span class="font-medium">${data.transacao.campanha}</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">{{ __('Descrição') }}</h4>
                        <p class="text-sm text-gray-600">${data.transacao.descricao}</p>
                    </div>
                </div>
            `;
            
            // Adicionar QR Code se for PIX pendente
            if (data.pix_data && data.transacao.status === 'pendente') {
                html += `
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <h4 class="font-medium text-blue-900 mb-3">{{ __('Pagamento PIX Pendente') }}</h4>
                        <div class="text-center">
                            <img src="data:image/png;base64,${data.pix_data.qr_code}" 
                                 alt="QR Code PIX" 
                                 class="mx-auto border-2 border-gray-200 rounded-lg mb-3">
                            <p class="text-sm text-blue-700 mb-2">{{ __('Escaneie o QR Code com seu app bancário') }}</p>
                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('Chave PIX:') }}</span>
                                    <span class="font-mono">${data.pix_data.chave}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('Valor:') }}</span>
                                    <span class="font-medium">R$ ${data.pix_data.valor}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            detailsDiv.innerHTML = html;
        })
        .catch(error => {
            detailsDiv.innerHTML = `
                <div class="text-center py-8">
                    <div class="text-red-500 mb-4">
                        <i class="fas fa-exclamation-triangle text-3xl"></i>
                    </div>
                    <p class="text-red-600">{{ __('Erro ao carregar detalhes da transação.') }}</p>
                </div>
            `;
        });
}

function fecharModal() {
    document.getElementById('transactionModal').classList.add('hidden');
}

// Fechar modal ao clicar fora
document.getElementById('transactionModal').addEventListener('click', function(e) {
    if (e.target === this) {
        fecharModal();
    }
});

// Animação de entrada
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.bg-white');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endpush
@endsection 