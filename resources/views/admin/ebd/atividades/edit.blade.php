@extends('layouts.admin')

@section('title', 'Editar Atividade')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Editar Atividade</h1>
    <p class="text-gray-600 dark:text-gray-400 mb-6">Para a lição: <span class="font-semibold">{{ $licao->titulo }}</span></p>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 md:p-8">
        <form action="{{ route('admin.ebd.atividades.update', $atividade) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Título da Atividade -->
                <div class="md:col-span-2">
                    <label for="titulo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Título da Atividade</label>
                    <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $atividade->titulo) }}" required class="mt-1 block w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    @error('titulo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Descrição -->
                <div class="md:col-span-2">
                    <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Instruções / Descrição</label>
                    <textarea name="descricao" id="descricao" rows="5" class="mt-1 block w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('descricao', $atividade->descricao) }}</textarea>
                    @error('descricao') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Tipo de Atividade -->
                <div>
                    <label for="tipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Atividade</label>
                    <select name="tipo" id="tipo" required class="mt-1 block w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="leitura_dirigida" {{ old('tipo', $atividade->tipo) == 'leitura_dirigida' ? 'selected' : '' }}>Leitura Dirigida</option>
                        <option value="reflexao_pessoal" {{ old('tipo', $atividade->tipo) == 'reflexao_pessoal' ? 'selected' : '' }}>Reflexão Pessoal</option>
                        <option value="pesquisa_biblica" {{ old('tipo', $atividade->tipo) == 'pesquisa_biblica' ? 'selected' : '' }}>Pesquisa Bíblica</option>
                        <option value="trabalho_em_grupo" {{ old('tipo', $atividade->tipo) == 'trabalho_em_grupo' ? 'selected' : '' }}>Trabalho em Grupo</option>
                        <option value="memorizacao" {{ old('tipo', $atividade->tipo) == 'memorizacao' ? 'selected' : '' }}>Memorização</option>
                        <option value="projeto_especial" {{ old('tipo', $atividade->tipo) == 'projeto_especial' ? 'selected' : '' }}>Projeto Especial</option>
                    </select>
                    @error('tipo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Pontuação Máxima -->
                <div>
                    <label for="pontuacao_maxima" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pontuação Máxima</label>
                    <input type="number" name="pontuacao_maxima" id="pontuacao_maxima" value="{{ old('pontuacao_maxima', $atividade->pontuacao_maxima) }}" required class="mt-1 block w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    @error('pontuacao_maxima') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Data de Entrega -->
                <div>
                    <label for="data_entrega" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data de Entrega</label>
                    <input type="date" name="data_entrega" id="data_entrega" value="{{ old('data_entrega', $atividade->data_entrega ? \Carbon\Carbon::parse($atividade->data_entrega)->format('Y-m-d') : '') }}" class="mt-1 block w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    @error('data_entrega') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Status -->
                <div class="flex items-center mt-6">
                    <input id="ativo" name="ativo" type="checkbox" value="1" {{ old('ativo', $atividade->ativo) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="ativo" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">Atividade Ativa</label>
                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="mt-8 flex justify-end">
                <a href="{{ route('admin.ebd.licoes.show', $licao) }}" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-bold py-2 px-4 rounded-lg mr-4 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors">
                    Atualizar Atividade
                </button>
            </div>
        </form>
    </div>
</div>
@endsection