@extends('layouts.admin')

@section('title', 'Editar Lição EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Editar Lição EBD</h1>
        <a href="{{ route('admin.ebd.licoes.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Voltar
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('admin.ebd.licoes.update', $licao) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">
                        Título da Lição *
                    </label>
                    <input type="text" name="titulo" id="titulo" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ old('titulo', $licao->titulo) }}" required>
                    @error('titulo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="dificuldade" class="block text-sm font-medium text-gray-700 mb-2">
                        Dificuldade *
                    </label>
                    <select name="dificuldade" id="dificuldade" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Selecione...</option>
                        <option value="facil" {{ old('dificuldade', $licao->dificuldade) == 'facil' ? 'selected' : '' }}>Fácil</option>
                        <option value="medio" {{ old('dificuldade', $licao->dificuldade) == 'medio' ? 'selected' : '' }}>Médio</option>
                        <option value="dificil" {{ old('dificuldade', $licao->dificuldade) == 'dificil' ? 'selected' : '' }}>Difícil</option>
                    </select>
                    @error('dificuldade')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="duracao_minutos" class="block text-sm font-medium text-gray-700 mb-2">
                        Duração (minutos) *
                    </label>
                    <input type="number" name="duracao_minutos" id="duracao_minutos" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ old('duracao_minutos', $licao->duracao_minutos) }}" min="15" required>
                    @error('duracao_minutos')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="versiculo_chave" class="block text-sm font-medium text-gray-700 mb-2">
                        Versículo Chave
                    </label>
                    <input type="text" name="versiculo_chave" id="versiculo_chave" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ old('versiculo_chave', $licao->versiculo_chave) }}" placeholder="Ex: João 3:16">
                    @error('versiculo_chave')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">
                    Descrição
                </label>
                <textarea name="descricao" id="descricao" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Breve descrição da lição...">{{ old('descricao', $licao->descricao) }}</textarea>
                @error('descricao')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <label for="objetivos" class="block text-sm font-medium text-gray-700 mb-2">
                    Objetivos da Lição
                </label>
                <textarea name="objetivos" id="objetivos" rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Quais são os objetivos desta lição?">{{ old('objetivos', $licao->objetivos) }}</textarea>
                @error('objetivos')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <label for="conteudo" class="block text-sm font-medium text-gray-700 mb-2">
                    Conteúdo da Lição *
                </label>
                <textarea name="conteudo" id="conteudo" rows="8"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Conteúdo principal da lição..." required>{{ old('conteudo', $licao->conteudo) }}</textarea>
                @error('conteudo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <label for="aplicacao_pratica" class="block text-sm font-medium text-gray-700 mb-2">
                    Aplicação Prática
                </label>
                <textarea name="aplicacao_pratica" id="aplicacao_pratica" rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Como aplicar esta lição na vida prática?">{{ old('aplicacao_pratica', $licao->aplicacao_pratica) }}</textarea>
                @error('aplicacao_pratica')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <label for="oracao" class="block text-sm font-medium text-gray-700 mb-2">
                    Oração da Lição
                </label>
                <textarea name="oracao" id="oracao" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Oração relacionada à lição...">{{ old('oracao', $licao->oracao) }}</textarea>
                @error('oracao')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <label for="material_necessario" class="block text-sm font-medium text-gray-700 mb-2">
                    Material Necessário
                </label>
                <textarea name="material_necessario" id="material_necessario" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Materiais necessários para a lição...">{{ old('material_necessario', $licao->material_necessario) }}</textarea>
                @error('material_necessario')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <label class="flex items-center">
                    <input type="checkbox" name="ativo" value="1" 
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                           {{ old('ativo', $licao->ativo) ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700">Lição ativa</span>
                </label>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('admin.ebd.licoes.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>Atualizar Lição
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 