@extends('layouts.admin')

@section('title', 'Detalhes do Pedido de Oração')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Detalhes do Pedido</h1>
                <p class="text-gray-600">Visualize e gerencie este pedido de oração</p>
            </div>
            <a href="{{ route('admin.intercessor.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Detalhes do Pedido -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Informações do Pedido</h2>
                </div>
                <div class="p-6">
                    <div class="mb-6">
                        <div class="flex items-center mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                @if($pedido->status === 'pendente') bg-yellow-100 text-yellow-800
                                @elseif($pedido->status === 'em_oracao') bg-blue-100 text-blue-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $pedido->status)) }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ml-3
                                @if($pedido->prioridade === 'alta') bg-red-100 text-red-800
                                @elseif($pedido->prioridade === 'media') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst($pedido->prioridade) }}
                            </span>
                            <span class="ml-3 text-sm text-gray-500">{{ $pedido->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $pedido->titulo }}</h3>
                        <p class="text-gray-700 mb-4">{{ $pedido->descricao }}</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">Solicitante:</span>
                                <span class="text-gray-600">{{ $pedido->membro->nome ?? 'Anônimo' }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Categoria:</span>
                                <span class="text-gray-600">{{ ucfirst($pedido->categoria) }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Data de Criação:</span>
                                <span class="text-gray-600">{{ $pedido->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Última Atualização:</span>
                                <span class="text-gray-600">{{ $pedido->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Intercessões -->
                    @if($pedido->intercessores->count() > 0)
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Intercessões Registradas</h4>
                            <div class="space-y-4">
                                @foreach($pedido->intercessores as $intercessao)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between items-start mb-2">
                                            <div class="flex items-center">
                                                <i class="fas fa-user-circle text-gray-400 mr-2"></i>
                                                <span class="font-medium text-gray-900">{{ $intercessao->user->name }}</span>
                                            </div>
                                            <span class="text-sm text-gray-500">{{ $intercessao->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                        @if($intercessao->observacoes)
                                            <p class="text-gray-700 mb-2">{{ $intercessao->observacoes }}</p>
                                        @endif
                                        <div class="flex items-center text-sm text-gray-500">
                                            <span class="mr-4">
                                                <i class="fas fa-clock mr-1"></i>
                                                {{ $intercessao->tempo_oracao ?? 0 }} min
                                            </span>
                                            <span>
                                                <i class="fas fa-users mr-1"></i>
                                                {{ ucfirst($intercessao->tipo_oracao) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Formulário de Intercessão -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Registrar Intercessão</h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.intercessor.registrar-intercessao', $pedido) }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="tipo_oracao" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Oração *</label>
                            <select name="tipo_oracao" id="tipo_oracao" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Selecione...</option>
                                <option value="individual">Individual</option>
                                <option value="grupo">Em Grupo</option>
                                <option value="igreja">Igreja</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="tempo_oracao" class="block text-sm font-medium text-gray-700 mb-2">Tempo de Oração (minutos)</label>
                            <input type="number" name="tempo_oracao" id="tempo_oracao" min="1" max="480" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ex: 30">
                        </div>

                        <div class="mb-6">
                            <label for="observacoes" class="block text-sm font-medium text-gray-700 mb-2">Observações</label>
                            <textarea name="observacoes" id="observacoes" rows="4" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Descreva como foi a intercessão..."></textarea>
                        </div>

                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                            <i class="fas fa-praying-hands mr-2"></i>
                            Registrar Intercessão
                        </button>
                    </form>
                </div>
            </div>

            <!-- Atualizar Status -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 mt-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Atualizar Status</h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.intercessor.atualizar-status', $pedido) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="novo_status" class="block text-sm font-medium text-gray-700 mb-2">Novo Status</label>
                            <select name="novo_status" id="novo_status" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="pendente" {{ $pedido->status === 'pendente' ? 'selected' : '' }}>Pendente</option>
                                <option value="em_oracao" {{ $pedido->status === 'em_oracao' ? 'selected' : '' }}>Em Oração</option>
                                <option value="atendido" {{ $pedido->status === 'atendido' ? 'selected' : '' }}>Atendido</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                            <i class="fas fa-save mr-2"></i>
                            Atualizar Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 