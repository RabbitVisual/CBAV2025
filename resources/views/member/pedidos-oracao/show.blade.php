@extends('layouts.member')

@section('title', 'Detalhes do Pedido de Oração')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Detalhes do Pedido de Oração</h1>
                <p class="text-gray-600">Acompanhe o status e as intercessões do seu pedido</p>
            </div>
            <div class="flex space-x-2">
                @if($pedido->status === 'pendente' && $isProprietario)
                    <a href="{{ route('member.pedidos-oracao.edit', $pedido) }}" 
                       class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Editar
                    </a>
                @endif
                @if($pedido->status === 'em_oracao' && $isProprietario)
                    <form method="POST" action="{{ route('member.pedidos-oracao.marcar-atendido', $pedido) }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Marcar como Atendido
                        </button>
                    </form>
                @endif
                @if($pedido->pode_compartilhar && !$pedido->anonimo && !$isProprietario && $pedido->status !== 'atendido')
                    <button onclick="document.getElementById('participarIntercessaoModal').classList.remove('hidden')" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        Participar da Intercessão
                    </button>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informações do Pedido -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Informações do Pedido</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Título</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $pedido->titulo }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Descrição</label>
                            <p class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $pedido->descricao }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Categoria</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                    {{ $pedido->categoria_text }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Prioridade</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $pedido->prioridade_color }}-100 text-{{ $pedido->prioridade_color }}-800 mt-1">
                                    {{ $pedido->prioridade_text }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $pedido->status_color }}-100 text-{{ $pedido->status_color }}-800 mt-1">
                                    {{ $pedido->status_text }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Data do Pedido</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $pedido->data_pedido->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        @if($pedido->data_atendimento)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Data do Atendimento</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $pedido->data_atendimento->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif

                        @if($pedido->observacoes)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Observações</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $pedido->observacoes }}</p>
                        </div>
                        @endif

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pedido Anônimo</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $pedido->anonimo ? 'Sim' : 'Não' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pode ser Compartilhado</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $pedido->pode_compartilhar ? 'Sim' : 'Não' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Intercessões -->
            <div class="bg-white rounded-lg shadow p-6 mt-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Intercessões</h2>
                
                @if($intercessores->count() > 0)
                    <div class="space-y-4">
                        @foreach($intercessores as $intercessor)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ $intercessor->user->name }}
                                        </span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                            {{ $intercessor->tipo_oracao_text }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-2">
                                        {{ $intercessor->observacoes ?: 'Orou por este pedido.' }}
                                    </p>
                                    <div class="flex items-center space-x-4 text-xs text-gray-500">
                                        <span>{{ $intercessor->data_oracao->format('d/m/Y H:i') }}</span>
                                        @if($intercessor->tempo_oracao)
                                            <span>{{ $intercessor->tempo_oracao_formatado }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma intercessão registrada</h3>
                        <p class="mt-1 text-sm text-gray-500">Ainda não há intercessões registradas para este pedido.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ações</h3>
                
                <div class="space-y-3">
                    <a href="{{ route('member.pedidos-oracao.index') }}" 
                       class="w-full flex items-center px-4 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-50">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Voltar à Lista
                    </a>

                    @if($pedido->status === 'pendente')
                        <a href="{{ route('member.pedidos-oracao.edit', $pedido) }}" 
                           class="w-full flex items-center px-4 py-2 text-sm font-medium text-yellow-700 rounded-md hover:bg-yellow-50">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Editar Pedido
                        </a>

                        <form method="POST" action="{{ route('member.pedidos-oracao.destroy', $pedido) }}" class="inline w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Tem certeza que deseja excluir este pedido?')"
                                    class="w-full flex items-center px-4 py-2 text-sm font-medium text-red-700 rounded-md hover:bg-red-50">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Excluir Pedido
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Status Timeline -->
            <div class="bg-white rounded-lg shadow p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Histórico</h3>
                
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Pedido Criado</p>
                            <p class="text-xs text-gray-500">{{ $pedido->data_pedido->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    @if($pedido->status !== 'pendente')
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Em Oração</p>
                            <p class="text-xs text-gray-500">Intercessores orando</p>
                        </div>
                    </div>
                    @endif

                    @if($pedido->status === 'atendido')
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Atendido</p>
                            <p class="text-xs text-gray-500">{{ $pedido->data_atendimento->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Participar da Intercessão -->
<div id="participarIntercessaoModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Participar da Intercessão</h3>
                <button onclick="document.getElementById('participarIntercessaoModal').classList.add('hidden')" 
                        class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form method="POST" action="{{ route('member.pedidos-oracao.participar-intercessao', $pedido) }}">
                @csrf
                
                <div class="mb-4">
                    <label for="tipo_oracao" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de Oração <span class="text-red-500">*</span>
                    </label>
                    <select name="tipo_oracao" id="tipo_oracao" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecione o tipo</option>
                        <option value="individual">Individual</option>
                        <option value="grupo">Grupo</option>
                        <option value="igreja">Igreja</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="tempo_oracao" class="block text-sm font-medium text-gray-700 mb-2">
                        Tempo de Oração (minutos)
                    </label>
                    <input type="number" name="tempo_oracao" id="tempo_oracao" min="1" max="480"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ex: 30">
                </div>
                
                <div class="mb-6">
                    <label for="observacoes" class="block text-sm font-medium text-gray-700 mb-2">
                        Observações
                    </label>
                    <textarea name="observacoes" id="observacoes" rows="3"
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Descreva como você orou por este pedido..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="document.getElementById('participarIntercessaoModal').classList.add('hidden')"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        Registrar Intercessão
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 