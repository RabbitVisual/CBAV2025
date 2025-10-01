@extends('layouts.member')

@section('title', 'Detalhes da Transação #' . $transacao->id . ' - ' . \App\Models\Configuracao::get('app_name', 'CBAV'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-8">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('member.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ route('member.donations.index') }}" class="hover:text-blue-600">Minhas Doações</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-900 font-medium">Detalhes da Transação #{{ $transacao->id }}</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    <svg class="w-8 h-8 text-blue-600 mr-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Detalhes da Transação
                </h1>
                <p class="text-gray-600">Visualize todos os detalhes da sua transação de doação</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('member.donations.transaction.comprovante', $transacao->id) }}" 
                   class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Baixar Comprovante
                </a>
                <a href="{{ route('member.donations.index') }}" 
                   class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <!-- Status da Transação -->
    <div class="mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">
                        Transação #{{ $transacao->id }}
                    </h2>
                    <p class="text-gray-600">{{ $transacao->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold text-green-600 mb-2">
                        {{ $transacao->tipo == 'entrada' ? '+' : '-' }}R$ {{ number_format($transacao->valor, 2, ',', '.') }}
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-medium
                        @if($transacao->status == 'confirmado') bg-green-100 text-green-800
                        @elseif($transacao->status == 'pendente') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800 @endif">
                        {{ ucfirst($transacao->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Detalhes da Transação -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Informações da Transação -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Informações da Transação
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600 font-medium">ID da Transação:</span>
                    <span class="font-semibold">#{{ $transacao->id }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600 font-medium">Data e Hora:</span>
                    <span class="font-semibold">{{ $transacao->created_at->format('d/m/Y H:i:s') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600 font-medium">Tipo:</span>
                    <span class="font-semibold">{{ ucfirst($transacao->tipo) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600 font-medium">Descrição:</span>
                    <span class="font-semibold">{{ $transacao->descricao }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600 font-medium">Método de Pagamento:</span>
                    <span class="font-semibold">{{ ucfirst($transacao->dados_extras['gateway'] ?? 'N/A') }}</span>
                </div>
                @if($transacao->campanha)
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600 font-medium">Campanha:</span>
                    <span class="font-semibold">{{ $transacao->campanha->titulo }}</span>
                </div>
                @endif
                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-600 font-medium">Categoria:</span>
                    <span class="font-semibold">{{ ucfirst($transacao->categoria ?? 'Não definida') }}</span>
                </div>
            </div>
        </div>

        <!-- Informações do Doador -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Informações do Doador
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600 font-medium">Nome:</span>
                    <span class="font-semibold">{{ $transacao->membro->user->name ?? 'Doador Anônimo' }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600 font-medium">Email:</span>
                    <span class="font-semibold">{{ $transacao->membro->user->email ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600 font-medium">Membro ID:</span>
                    <span class="font-semibold">#{{ $transacao->membro->id ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600 font-medium">Tipo de Doador:</span>
                    <span class="font-semibold">{{ ucfirst($transacao->dados_extras['tipo_doador'] ?? 'Membro') }}</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-600 font-medium">Data de Registro:</span>
                    <span class="font-semibold">{{ $transacao->membro->created_at->format('d/m/Y') ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Código de Verificação -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Código de Verificação
        </h3>
        <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
            <h4 class="text-sm font-medium text-gray-700 mb-2">Código de Verificação</h4>
            <div class="font-mono text-2xl font-bold text-blue-600 tracking-wider mb-2">
                {{ strtoupper(substr(md5($transacao->id . $transacao->created_at), 0, 8)) }}
            </div>
            <p class="text-sm text-gray-500">
                Use este código para verificar a autenticidade do comprovante em: 
                <a href="{{ url('/public/validacao/comprovante') }}" class="text-blue-600 hover:underline" target="_blank">
                    {{ url('/public/validacao/comprovante') }}
                </a>
            </p>
        </div>
    </div>

    <!-- Dados PIX (se aplicável) -->
    @if($pixData && $transacao->status === 'pendente')
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            </svg>
            Pagamento PIX Pendente
        </h3>
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
            <p class="text-yellow-800 text-sm">
                <i class="fas fa-info-circle mr-2"></i>
                Esta transação está aguardando confirmação do pagamento PIX. Use o QR Code abaixo para realizar o pagamento.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- QR Code -->
            <div class="text-center">
                <h4 class="font-medium text-gray-900 mb-3">QR Code para Pagamento</h4>
                <div class="bg-white p-4 rounded-lg border border-gray-200 inline-block">
                    <img src="data:image/png;base64,{{ $pixData['qr_code'] }}" 
                         alt="QR Code PIX" 
                         class="w-48 h-48 mx-auto">
                </div>
            </div>
            
            <!-- Dados PIX -->
            <div>
                <h4 class="font-medium text-gray-900 mb-3">Dados do PIX</h4>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Chave PIX:</label>
                        <div class="flex">
                            <input type="text" 
                                   value="{{ $pixData['chave'] }}" 
                                   readonly 
                                   class="flex-1 bg-gray-50 border border-gray-300 rounded-l-md px-3 py-2 text-sm">
                            <button onclick="copiarTexto('{{ $pixData['chave'] }}')" 
                                    class="bg-blue-600 text-white px-3 py-2 rounded-r-md hover:bg-blue-700 transition-colors">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Código PIX:</label>
                        <div class="flex">
                            <input type="text" 
                                   value="{{ $pixData['codigo_pix'] }}" 
                                   readonly 
                                   class="flex-1 bg-gray-50 border border-gray-300 rounded-l-md px-3 py-2 text-sm font-mono">
                            <button onclick="copiarTexto('{{ $pixData['codigo_pix'] }}')" 
                                    class="bg-blue-600 text-white px-3 py-2 rounded-r-md hover:bg-blue-700 transition-colors">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Valor:</label>
                        <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2 text-sm font-semibold">
                            R$ {{ $pixData['valor'] }}
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Beneficiário:</label>
                        <div class="bg-gray-50 border border-gray-300 rounded-md px-3 py-2 text-sm">
                            {{ $pixData['beneficiario'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <h5 class="font-medium text-blue-900 mb-2">Como pagar:</h5>
            <ol class="text-sm text-blue-800 space-y-1 list-decimal list-inside">
                <li>Abra o app do seu banco</li>
                <li>Escolha a opção PIX</li>
                <li>Escaneie o QR Code ou cole o código PIX</li>
                <li>Confirme o valor e os dados</li>
                <li>Realize o pagamento</li>
                <li>O status será atualizado automaticamente</li>
            </ol>
        </div>
    </div>
    @endif

    <!-- Ações -->
    <div class="flex justify-center space-x-4">
        <a href="{{ route('member.donations.transaction.comprovante', $transacao->id) }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors font-medium">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Baixar Comprovante
        </a>
        <a href="{{ route('member.donations.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg transition-colors font-medium">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Voltar para Doações
        </a>
    </div>
</div>

<script>
function copiarTexto(texto) {
    navigator.clipboard.writeText(texto).then(function() {
        // Mostrar feedback visual
        const button = event.target.closest('button');
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i>';
        button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
        button.classList.add('bg-green-600');
        
        setTimeout(function() {
            button.innerHTML = originalHTML;
            button.classList.remove('bg-green-600');
            button.classList.add('bg-blue-600', 'hover:bg-blue-700');
        }, 2000);
    }).catch(function(err) {
        console.error('Erro ao copiar texto: ', err);
    });
}
</script>
@endsection 