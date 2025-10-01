@extends('layouts.admin')

@section('title', 'Editar Disciplina')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Editar Disciplina: {{ $disciplina->nome }}</h1>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 md:p-8">
        <form action="{{ route('admin.ebd.disciplinas.update', $disciplina) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nome da Disciplina -->
                <div class="md:col-span-2">
                    <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome da Disciplina</label>
                    <input type="text" name="nome" id="nome" value="{{ old('nome', $disciplina->nome) }}" required class="mt-1 block w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    @error('nome') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Descrição -->
                <div class="md:col-span-2">
                    <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição</label>
                    <textarea name="descricao" id="descricao" rows="4" class="mt-1 block w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('descricao', $disciplina->descricao) }}</textarea>
                    @error('descricao') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Professor Responsável -->
                <div>
                    <label for="professor_responsavel_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Professor Responsável</label>
                    <select name="professor_responsavel_id" id="professor_responsavel_id" class="mt-1 block w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Selecione um professor</option>
                        @foreach($professores as $professor)
                            <option value="{{ $professor->id }}" {{ old('professor_responsavel_id', $disciplina->professor_responsavel_id) == $professor->id ? 'selected' : '' }}>{{ $professor->name }}</option>
                        @endforeach
                    </select>
                    @error('professor_responsavel_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Carga Horária -->
                <div>
                    <label for="carga_horaria" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Carga Horária (horas)</label>
                    <input type="number" name="carga_horaria" id="carga_horaria" value="{{ old('carga_horaria', $disciplina->carga_horaria) }}" class="mt-1 block w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    @error('carga_horaria') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Código da Disciplina -->
                <div>
                    <label for="codigo_disciplina" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Código da Disciplina</label>
                    <input type="text" name="codigo_disciplina" id="codigo_disciplina" value="{{ old('codigo_disciplina', $disciplina->codigo_disciplina) }}" required class="mt-1 block w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    @error('codigo_disciplina') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Status -->
                <div class="flex items-center mt-6">
                    <input id="ativo" name="ativo" type="checkbox" value="1" {{ old('ativo', $disciplina->ativo) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="ativo" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">Disciplina Ativa</label>
                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="mt-8 flex justify-end">
                <a href="{{ route('admin.ebd.disciplinas.index') }}" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-bold py-2 px-4 rounded-lg mr-4 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors">
                    Atualizar Disciplina
                </button>
            </div>
        </form>
    </div>
</div>
@endsection