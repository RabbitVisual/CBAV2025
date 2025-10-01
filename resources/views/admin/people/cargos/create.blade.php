@extends('layouts.admin')

@section('title', 'Criar Cargo')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Criar Novo Cargo</h1>
        <a href="{{ url()->previous() }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Voltar
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Formulário -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <form action="{{ route('admin.people.cargos.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nome do Cargo *
                    </label>
                    <input type="text" name="nome" id="nome" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 @error('nome') border-red-500 @enderror"
                           value="{{ old('nome') }}" 
                           placeholder="Ex: Líder de Louvor"
                           required>
                    @error('nome')
                        <p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="departamento_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Departamento *
                    </label>
                    <select name="departamento_id" id="departamento_id" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 @error('departamento_id') border-red-500 @enderror" required>
                        <option value="">Selecione um departamento</option>
                        @foreach($departamentos as $departamento)
                            <option value="{{ $departamento->id }}" 
                                    {{ old('departamento_id', $departamentoId) == $departamento->id ? 'selected' : '' }}>
                                {{ $departamento->nome }} - {{ $departamento->ministerio->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('departamento_id')
                        <p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="ativo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Status
                    </label>
                    <select name="ativo" id="ativo" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 @error('ativo') border-red-500 @enderror">
                        <option value="1" {{ old('ativo', 1) == 1 ? 'selected' : '' }}>Ativo</option>
                        <option value="0" {{ old('ativo', 1) == 0 ? 'selected' : '' }}>Inativo</option>
                    </select>
                    @error('ativo')
                        <p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Descrição
                    </label>
                    <textarea name="descricao" id="descricao" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 @error('descricao') border-red-500 @enderror"
                              placeholder="Descreva as responsabilidades e atribuições do cargo...">{{ old('descricao') }}</textarea>
                    @error('descricao')
                        <p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="sistema" value="1" id="sistema"
                               class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700"
                               {{ old('sistema') ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Cargo para usuários do sistema</span>
                    </label>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Marque esta opção se este cargo é destinado a usuários que terão acesso administrativo ao sistema</p>
                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="mt-8 flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ url()->previous() }}" 
                   class="bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>Criar Cargo
                </button>
            </div>
        </form>
    </div>
</div>
@endsection