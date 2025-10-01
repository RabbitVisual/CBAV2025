@extends('layouts.member')

@section('title', 'Verificar Comprovante - ' . \App\Models\Configuracao::get('app_name', 'CBAV'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            Verificar Comprovante
        </h1>
        <p class="text-lg text-gray-600">
            Verifique a autenticidade dos seus comprovantes de doação
        </p>
    </div>

    <!-- Formulário de Validação -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form method="GET" action="{{ route('member.donations.verificar-comprovante') }}" class="space-y-6">
            <div>
                <label for="codigo" class="block text-sm font-medium text-gray-700 mb-2">
                    Código de Verificação
                </label>
                <div class="flex">
                    <input 
                        type="text" 
                        id="codigo" 
                        name="codigo" 
                        value="{{ $codigo ?? '' }}"
                        placeholder="Digite o código de verificação (ex: ED9200CA)"
                        class="flex-1 rounded-l-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        maxlength="8"
                        style="font-family: 'Courier New', monospace; letter-spacing: 1px;"
                    >
                    <button 
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-r-md transition-colors duration-200"
                    >
                        Verificar
                    </button>
                </div>
                <p class="text-sm text-gray-500 mt-2">
                    Digite o código de 8 caracteres que aparece no comprovante
                </p>
            </div>
        </form>
    </div>

    <!-- Resultado da Validação -->
    @if(isset($codigo))
        @if($valido && $transacao)
            <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-green-800">
                            Comprovante Válido
                        </h3>
                        <p class="text-sm text-green-600">
                            Este comprovante foi emitido pelo sistema {{ \App\Models\Configuracao::get('app_name', 'CBAV') }}
                        </p>
                    </div>
                </div>

                <!-- Detalhes da Transação -->
                <div class="bg-white rounded-lg border border-green-200 p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Detalhes da Transação</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Informações da Transação -->
                        <div>
                            <h5 class="font-medium text-gray-900 mb-3">Informações da Transação</h5>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">ID da Transação:</span>
                                    <span class="font-medium">#{{ $transacao->id }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Data:</span>
                                    <span class="font-medium">{{ $transacao->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Valor:</span>
                                    <span class="font-medium text-green-600">
                                        {{ $transacao->tipo == 'entrada' ? '+' : '-' }}R$ {{ number_format($transacao->valor, 2, ',', '.') }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="font-medium">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium
                                            @if($transacao->status == 'confirmado') bg-green-100 text-green-800
                                            @elseif($transacao->status == 'pendente') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($transacao->status) }}
                                        </span>
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tipo:</span>
                                    <span class="font-medium">{{ ucfirst($transacao->tipo) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Descrição:</span>
                                    <span class="font-medium">{{ $transacao->descricao }}</span>
                                </div>
                                @if($transacao->campanha)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Campanha:</span>
                                    <span class="font-medium">{{ $transacao->campanha->titulo }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Informações do Doador -->
                        <div>
                            <h5 class="font-medium text-gray-900 mb-3">Informações do Doador</h5>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Nome:</span>
                                    <span class="font-medium">{{ $transacao->membro->user->name ?? 'Doador Anônimo' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Email:</span>
                                    <span class="font-medium">{{ $transacao->membro->user->email ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Membro ID:</span>
                                    <span class="font-medium">#{{ $transacao->membro->id ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Método de Pagamento:</span>
                                    <span class="font-medium">{{ ucfirst($transacao->dados_extras['gateway'] ?? 'N/A') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Código de Verificação -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <div class="text-center">
                            <h6 class="text-sm font-medium text-gray-700 mb-2">Código de Verificação</h6>
                            <div class="font-mono text-lg font-bold text-blue-600 tracking-wider">
                                {{ strtoupper(substr(md5($transacao->id . $transacao->created_at), 0, 8)) }}
                            </div>
                            <p class="text-xs text-gray-500 mt-2">
                                Este código confirma a autenticidade do comprovante
                            </p>
                        </div>
                    </div>

                    <!-- Ações -->
                    <div class="mt-6 flex justify-center space-x-4">
                        <a href="{{ route('member.donations.transaction.comprovante', $transacao->id) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors duration-200">
                            Baixar Comprovante
                        </a>
                        <a href="{{ route('member.donations.transaction.details', $transacao->id) }}" 
                           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition-colors duration-200">
                            Ver Detalhes
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-8">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-red-800">
                            Comprovante Inválido
                        </h3>
                        <p class="text-sm text-red-600">
                            {{ $mensagem }}
                        </p>
                    </div>
                </div>
            </div>
        @endif
    @endif

    <!-- Minhas Transações Recentes -->
    @if($minhasTransacoes->isNotEmpty())
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Minhas Transações Recentes</h3>
        <p class="text-sm text-gray-600 mb-4">
            Aqui estão os códigos de verificação das suas transações mais recentes:
        </p>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ID
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Data
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Valor
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Código de Verificação
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($minhasTransacoes as $transacao)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ $transacao['id'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $transacao['data'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            R$ {{ number_format($transacao['valor'], 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                @if($transacao['status'] == 'confirmado') bg-green-100 text-green-800
                                @elseif($transacao['status'] == 'pendente') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($transacao['status']) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-sm font-mono text-blue-600 bg-blue-50 px-2 py-1 rounded">
                                {{ $transacao['codigo'] }}
                            </code>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Informações Adicionais -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Como Verificar um Comprovante</h3>
        <div class="prose prose-sm text-gray-600">
            <ol class="list-decimal list-inside space-y-2">
                <li>Localize o código de verificação no comprovante (8 caracteres alfanuméricos)</li>
                <li>Digite o código no campo acima</li>
                <li>Clique em "Verificar"</li>
                <li>O sistema confirmará se o comprovante é autêntico</li>
            </ol>
            
            <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                <h4 class="font-medium text-blue-900 mb-2">Informações Importantes</h4>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• Esta verificação é específica para suas transações</li>
                    <li>• Todos os seus comprovantes possuem um código único</li>
                    <li>• Comprovantes válidos mostram todos os detalhes da transação</li>
                    <li>• Você pode baixar o comprovante após a verificação</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Links Úteis -->
    <div class="mt-8 flex justify-center space-x-4">
        <a href="{{ route('member.donations.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition-colors duration-200">
            Voltar para Doações
        </a>
        <a href="{{ route('member.donations.history') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors duration-200">
            Ver Histórico
        </a>
    </div>
</div>
@endsection 