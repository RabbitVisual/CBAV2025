@extends('layouts.member')

@section('title', 'Novo Pedido de Oração')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Novo Pedido de Oração</h1>
        <p class="text-gray-600">Solicite oração para suas necessidades e intenções</p>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('member.pedidos-oracao.store') }}">
                @csrf

                <!-- Título -->
                <div class="mb-6">
                    <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">
                        Título do Pedido <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="titulo" 
                           id="titulo" 
                           value="{{ old('titulo') }}"
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
                              required>{{ old('descricao') }}</textarea>
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
                        <option value="saude" {{ old('categoria') == 'saude' ? 'selected' : '' }}>Saúde</option>
                        <option value="familia" {{ old('categoria') == 'familia' ? 'selected' : '' }}>Família</option>
                        <option value="trabalho" {{ old('categoria') == 'trabalho' ? 'selected' : '' }}>Trabalho</option>
                        <option value="espiritual" {{ old('categoria') == 'espiritual' ? 'selected' : '' }}>Espiritual</option>
                        <option value="outros" {{ old('categoria') == 'outros' ? 'selected' : '' }}>Outros</option>
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
                        <option value="baixa" {{ old('prioridade') == 'baixa' ? 'selected' : '' }}>Baixa</option>
                        <option value="media" {{ old('prioridade') == 'media' ? 'selected' : '' }}>Média</option>
                        <option value="alta" {{ old('prioridade') == 'alta' ? 'selected' : '' }}>Alta</option>
                        <option value="urgente" {{ old('prioridade') == 'urgente' ? 'selected' : '' }}>Urgente</option>
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
                                   {{ old('anonimo') ? 'checked' : '' }}
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
                                   {{ old('pode_compartilhar', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="pode_compartilhar" class="ml-2 block text-sm text-gray-700">
                                Pode ser compartilhado com a igreja
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Informações -->
                <div class="mb-6 p-4 bg-blue-50 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Informações importantes</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Seu pedido será enviado para os intercessores da igreja</li>
                                    <li>Você receberá notificações quando alguém orar por você</li>
                                    <li>Pedidos anônimos não mostram seu nome aos intercessores</li>
                                    <li>Pedidos compartilhados podem ser mencionados em orações coletivas</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('member.pedidos-oracao.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        Enviar Pedido de Oração
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 