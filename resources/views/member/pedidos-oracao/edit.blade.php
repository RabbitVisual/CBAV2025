@extends('layouts.member')

@section('title', 'Editar Pedido de Oração')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Editar Pedido de Oração</h1>
        <p class="text-gray-600">Atualize as informações do seu pedido de oração</p>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('member.pedidos-oracao.update', $pedido) }}">
                @csrf
                @method('PUT')

                <!-- Título -->
                <div class="mb-6">
                    <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">
                        Título do Pedido <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="titulo" 
                           id="titulo" 
                           value="{{ old('titulo', $pedido->titulo) }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('titulo') border-red-500 @enderror"
                           placeholder="Ex: Cura para minha mãe"
                           required>
                    @error('titulo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descrição -->
                <div class="mb-6">
                    <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">
                        Descrição <span class="text-red-500">*</span>
                    </label>
                    <textarea name="descricao" 
                              id="descricao" 
                              rows="4"
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('descricao') border-red-500 @enderror"
                              placeholder="Descreva sua necessidade ou intenção de oração..."
                              required>{{ old('descricao', $pedido->descricao) }}</textarea>
                    @error('descricao')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Categoria -->
                <div class="mb-6">
                    <label for="categoria" class="block text-sm font-medium text-gray-700 mb-2">
                        Categoria <span class="text-red-500">*</span>
                    </label>
                    <select name="categoria" 
                            id="categoria"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('categoria') border-red-500 @enderror"
                            required>
                        <option value="">Selecione uma categoria</option>
                        <option value="saude" {{ old('categoria', $pedido->categoria) == 'saude' ? 'selected' : '' }}>Saúde</option>
                        <option value="familia" {{ old('categoria', $pedido->categoria) == 'familia' ? 'selected' : '' }}>Família</option>
                        <option value="trabalho" {{ old('categoria', $pedido->categoria) == 'trabalho' ? 'selected' : '' }}>Trabalho</option>
                        <option value="espiritual" {{ old('categoria', $pedido->categoria) == 'espiritual' ? 'selected' : '' }}>Espiritual</option>
                        <option value="outros" {{ old('categoria', $pedido->categoria) == 'outros' ? 'selected' : '' }}>Outros</option>
                    </select>
                    @error('categoria')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Prioridade -->
                <div class="mb-6">
                    <label for="prioridade" class="block text-sm font-medium text-gray-700 mb-2">
                        Prioridade <span class="text-red-500">*</span>
                    </label>
                    <select name="prioridade" 
                            id="prioridade"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('prioridade') border-red-500 @enderror"
                            required>
                        <option value="">Selecione a prioridade</option>
                        <option value="baixa" {{ old('prioridade', $pedido->prioridade) == 'baixa' ? 'selected' : '' }}>Baixa</option>
                        <option value="media" {{ old('prioridade', $pedido->prioridade) == 'media' ? 'selected' : '' }}>Média</option>
                        <option value="alta" {{ old('prioridade', $pedido->prioridade) == 'alta' ? 'selected' : '' }}>Alta</option>
                        <option value="urgente" {{ old('prioridade', $pedido->prioridade) == 'urgente' ? 'selected' : '' }}>Urgente</option>
                    </select>
                    @error('prioridade')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Opções -->
                <div class="mb-6">
                    <div class="flex items-center space-x-6">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="anonimo" 
                                   id="anonimo" 
                                   value="1"
                                   {{ old('anonimo', $pedido->anonimo) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="anonimo" class="ml-2 block text-sm text-gray-700">
                                Pedido anônimo
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="pode_compartilhar" 
                                   id="pode_compartilhar" 
                                   value="1"
                                   {{ old('pode_compartilhar', $pedido->pode_compartilhar) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="pode_compartilhar" class="ml-2 block text-sm text-gray-700">
                                Pode ser compartilhado com a igreja
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Informações -->
                <div class="mb-6 p-4 bg-yellow-50 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Atenção</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Você só pode editar pedidos que ainda estão pendentes. Após o início das intercessões, as alterações não são permitidas.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('member.pedidos-oracao.show', $pedido) }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Atualizar Pedido
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 