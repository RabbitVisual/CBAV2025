@extends('layouts.admin')

@section('title', __('Detalhes da Transação'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Detalhes da Transação') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Visualize informações completas da transação') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.finance.transactions.edit', $transacao) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-edit mr-2"></i>
                {{ __('Editar') }}
            </a>
            <a href="{{ route('admin.finance.transactions.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('Voltar') }}
            </a>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="mb-6">
        @if($transacao->status == 'confirmado')
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                <i class="fas fa-check-circle mr-2"></i>
                {{ __('Confirmado') }}
            </span>
        @elseif($transacao->status == 'pendente')
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                <i class="fas fa-clock mr-2"></i>
                {{ __('Pendente') }}
            </span>
        @else
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                <i class="fas fa-times-circle mr-2"></i>
                {{ __('Cancelado') }}
            </span>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informações Principais -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Card de Informações Básicas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle mr-3 text-blue-500"></i>
                    {{ __('Informações Básicas') }}
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">{{ __('Descrição') }}</label>
                        <p class="text-gray-900 font-medium">{{ $transacao->descricao }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">{{ __('Valor') }}</label>
                        <p class="text-2xl font-bold {{ $transacao->tipo == 'entrada' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $transacao->tipo == 'entrada' ? '+' : '-' }}R$ {{ number_format($transacao->valor, 2, ',', '.') }}
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">{{ __('Tipo') }}</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $transacao->tipo == 'entrada' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $transacao->tipo == 'entrada' ? __('Entrada') : __('Saída') }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">{{ __('Categoria') }}</label>
                        <p class="text-gray-900">{{ ucfirst($transacao->categoria ?? __('Não definida')) }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">{{ __('Data da Transação') }}</label>
                        <p class="text-gray-900">{{ $transacao->data ? $transacao->data->format('d/m/Y') : 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">{{ __('Método de Pagamento') }}</label>
                        <p class="text-gray-900">{{ ucfirst($transacao->metodo_pagamento ?? __('Não informado')) }}</p>
                    </div>
                </div>
            </div>

            <!-- Card de Campanha (se aplicável) -->
            @if($transacao->campanha)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-bullhorn mr-3 text-purple-500"></i>
                    {{ __('Campanha Relacionada') }}
                </h2>
                
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $transacao->campanha->titulo }}</h3>
                            <p class="text-gray-600 text-sm mt-1">{{ $transacao->campanha->descricao }}</p>
                            <div class="flex items-center mt-2 space-x-4 text-sm">
                                <span class="text-gray-500">{{ __('Meta:') }} R$ {{ number_format($transacao->campanha->meta_valor, 2, ',', '.') }}</span>
                                <span class="text-gray-500">{{ __('Arrecadado:') }} R$ {{ number_format($transacao->campanha->valor_arrecadado, 2, ',', '.') }}</span>
                                <span class="text-gray-500">{{ __('Progresso:') }} {{ number_format($transacao->campanha->progresso, 1) }}%</span>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.finance.campaigns.show', $transacao->campanha) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Card de Membro (se aplicável) -->
            @if($transacao->membro)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-user mr-3 text-green-500"></i>
                    {{ __('Membro Relacionado') }}
                </h2>
                
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        @if($transacao->membro->foto_existe)
                            <img class="h-12 w-12 rounded-full" src="{{ $transacao->membro->foto_url }}" alt="{{ $transacao->membro->nome }}">
                        @else
                            <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center">
                                <span class="text-gray-600 font-medium text-lg">{{ $transacao->membro->iniciais }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-lg font-medium text-gray-900">{{ $transacao->membro->nome }}</h3>
                        <p class="text-gray-600">{{ $transacao->membro->email }}</p>
                        @if($transacao->membro->telefone)
                            <p class="text-gray-500 text-sm">{{ $transacao->membro->telefone }}</p>
                        @endif
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.people.members.show', $transacao->membro) }}" 
                           class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Card de Observações -->
            @if($transacao->observacoes)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-sticky-note mr-3 text-yellow-500"></i>
                    {{ __('Observações') }}
                </h2>
                
                <p class="text-gray-700">{{ $transacao->observacoes }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar com Estatísticas -->
        <div class="space-y-6">
            <!-- Card de Estatísticas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-chart-bar mr-3 text-indigo-500"></i>
                    {{ __('Estatísticas') }}
                </h2>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">{{ __('Valor Total') }}</span>
                        <span class="font-semibold text-gray-900">R$ {{ number_format($transacao->valor, 2, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">{{ __('Tipo') }}</span>
                        <span class="font-semibold {{ $transacao->tipo == 'entrada' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $transacao->tipo == 'entrada' ? __('Receita') : __('Despesa') }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">{{ __('Status') }}</span>
                        <span class="font-semibold text-gray-900">{{ ucfirst($transacao->status) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">{{ __('Método') }}</span>
                        <span class="font-semibold text-gray-900">{{ ucfirst($transacao->metodo_pagamento ?? __('N/A')) }}</span>
                    </div>
                </div>
            </div>

            <!-- Card de Informações do Sistema -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle mr-3 text-gray-500"></i>
                    {{ __('Informações do Sistema') }}
                </h2>
                
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-gray-600">{{ __('Criado em:') }}</span>
                        <p class="text-gray-900">{{ $transacao->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    <div>
                        <span class="text-gray-600">{{ __('Última atualização:') }}</span>
                        <p class="text-gray-900">{{ $transacao->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    <div>
                        <span class="text-gray-600">{{ __('ID da Transação:') }}</span>
                        <p class="text-gray-900 font-mono">{{ $transacao->id }}</p>
                    </div>
                    
                    <div>
                        <span class="text-gray-600">{{ __('Criado por:') }}</span>
                        <p class="text-gray-900">{{ $transacao->criado_por ?? 'Sistema' }}</p>
                    </div>
                </div>
            </div>

            <!-- Card de Ações Rápidas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-bolt mr-3 text-yellow-500"></i>
                    {{ __('Ações Rápidas') }}
                </h2>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.finance.transactions.edit', $transacao) }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-edit mr-2"></i>
                        {{ __('Editar Transação') }}
                    </a>
                    
                    <button onclick="duplicarTransacao()" 
                            class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-copy mr-2"></i>
                        {{ __('Duplicar Transação') }}
                    </button>
                    
                    <button onclick="exportarTransacao()" 
                            class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-download mr-2"></i>
                        {{ __('Exportar Comprovante') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function duplicarTransacao() {
    if (confirm('{{ __("Deseja duplicar esta transação?") }}')) {
        window.location.href = '{{ route("admin.finance.transactions.create") }}?duplicate={{ $transacao->id }}';
    }
}

function exportarTransacao() {
    window.open('{{ route("admin.finance.transactions.comprovante", $transacao) }}', '_blank');
}
</script>
@endpush
@endsection 