@extends('layouts.admin')

@section('title', 'Editar Professor EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Editar Professor EBD</h1>
        <a href="{{ route('admin.ebd.professores.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Voltar
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('admin.ebd.professores.update', $professor) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="membro_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Membro (Opcional)
                    </label>
                    <select name="membro_id" id="membro_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecione um membro...</option>
                        @foreach($membros as $membro)
                            <option value="{{ $membro->id }}" {{ old('membro_id', $professor->membro_id) == $membro->id ? 'selected' : '' }}>
                                {{ $membro->nome }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-sm text-gray-500 mt-1">Deixe em branco se o professor não for membro da igreja</p>
                    @error('membro_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="turma_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Turma *
                    </label>
                    <select name="turma_id" id="turma_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Selecione uma turma...</option>
                        @foreach($turmas as $turma)
                            <option value="{{ $turma->id }}" {{ old('turma_id', $professor->turma_id) == $turma->id ? 'selected' : '' }}>
                                {{ $turma->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('turma_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de Professor *
                    </label>
                    <select name="tipo" id="tipo" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Selecione...</option>
                        <option value="principal" {{ old('tipo', $professor->tipo) == 'principal' ? 'selected' : '' }}>Professor Principal</option>
                        <option value="auxiliar" {{ old('tipo', $professor->tipo) == 'auxiliar' ? 'selected' : '' }}>Professor Auxiliar</option>
                    </select>
                    @error('tipo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="data_inicio" class="block text-sm font-medium text-gray-700 mb-2">
                        Data de Início *
                    </label>
                    <input type="date" name="data_inicio" id="data_inicio" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ old('data_inicio', $professor->data_inicio->format('Y-m-d')) }}" required>
                    @error('data_inicio')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="data_fim" class="block text-sm font-medium text-gray-700 mb-2">
                        Data de Fim (Opcional)
                    </label>
                    <input type="date" name="data_fim" id="data_fim" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ old('data_fim', $professor->data_fim ? $professor->data_fim->format('Y-m-d') : '') }}">
                    <p class="text-sm text-gray-500 mt-1">Deixe em branco se não houver data de fim definida</p>
                    @error('data_fim')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label class="flex items-center">
                    <input type="checkbox" name="ativo" value="1" 
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                           {{ old('ativo', $professor->ativo) ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700">Professor ativo</span>
                </label>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('admin.ebd.professores.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>Atualizar Professor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 