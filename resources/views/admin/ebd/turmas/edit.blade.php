@extends('layouts.admin')

@section('title', 'Editar Turma EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Editar Turma EBD</h1>
        <a href="{{ route('admin.ebd.turmas.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Voltar
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('admin.ebd.turmas.update', $turma) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
                        Nome da Turma *
                    </label>
                    <input type="text" name="nome" id="nome" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ old('nome', $turma->nome) }}" required>
                    @error('nome')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="faixa_etaria" class="block text-sm font-medium text-gray-700 mb-2">
                        Faixa Etária
                    </label>
                    <input type="text" name="faixa_etaria" id="faixa_etaria" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ old('faixa_etaria', $turma->faixa_etaria) }}" placeholder="Ex: 12-15 anos">
                    @error('faixa_etaria')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="capacidade_maxima" class="block text-sm font-medium text-gray-700 mb-2">
                        Capacidade Máxima
                    </label>
                    <input type="number" name="capacidade_maxima" id="capacidade_maxima" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ old('capacidade_maxima', $turma->capacidade_maxima) }}" min="1">
                    @error('capacidade_maxima')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="cor" class="block text-sm font-medium text-gray-700 mb-2">
                        Cor da Turma
                    </label>
                    <input type="color" name="cor" id="cor" 
                           class="w-full h-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ old('cor', $turma->cor ?? '#3B82F6') }}">
                    @error('cor')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">
                    Descrição
                </label>
                <textarea name="descricao" id="descricao" rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Descrição da turma...">{{ old('descricao', $turma->descricao) }}</textarea>
                @error('descricao')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <label class="flex items-center">
                    <input type="checkbox" name="ativo" value="1" 
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                           {{ old('ativo', $turma->ativo) ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700">Turma ativa</span>
                </label>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('admin.ebd.turmas.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>Atualizar Turma
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 