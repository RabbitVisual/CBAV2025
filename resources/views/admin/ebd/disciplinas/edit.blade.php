@extends('layouts.admin')

@section('title', 'Editar Disciplina')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Editar Disciplina: {{ $disciplina->nome }}</h1>
    </div>

    <x-admin.card>
        <form action="{{ route('admin.ebd.disciplinas.update', $disciplina) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nome da Disciplina --}}
                <div class="md:col-span-2">
                    <x-admin.label for="nome">Nome da Disciplina</x-admin.label>
                    <x-admin.input name="nome" id="nome" value="{{ old('nome', $disciplina->nome) }}" required />
                    @error('nome') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Descrição --}}
                <div class="md:col-span-2">
                    <x-admin.label for="descricao">Descrição</x-admin.label>
                    <x-admin.textarea name="descricao" id="descricao">{{ old('descricao', $disciplina->descricao) }}</x-admin.textarea>
                    @error('descricao') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Professor Responsável --}}
                <div>
                    <x-admin.label for="professor_responsavel_id">Professor Responsável</x-admin.label>
                    <select name="professor_responsavel_id" id="professor_responsavel_id" class="mt-1 block w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Selecione um professor</option>
                        @foreach($professores as $professor)
                            <option value="{{ $professor->id }}" {{ old('professor_responsavel_id', $disciplina->professor_responsavel_id) == $professor->id ? 'selected' : '' }}>{{ $professor->name }}</option>
                        @endforeach
                    </select>
                    @error('professor_responsavel_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Carga Horária --}}
                <div>
                    <x-admin.label for="carga_horaria">Carga Horária (horas)</x-admin.label>
                    <x-admin.input type="number" name="carga_horaria" id="carga_horaria" value="{{ old('carga_horaria', $disciplina->carga_horaria) }}" />
                    @error('carga_horaria') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Código da Disciplina --}}
                <div>
                    <x-admin.label for="codigo_disciplina">Código da Disciplina</x-admin.label>
                    <x-admin.input name="codigo_disciplina" id="codigo_disciplina" value="{{ old('codigo_disciplina', $disciplina->codigo_disciplina) }}" required />
                    @error('codigo_disciplina') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Status --}}
                <div class="flex items-center pt-6">
                    <input id="ativo" name="ativo" type="checkbox" value="1" {{ old('ativo', $disciplina->ativo) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <x-admin.label for="ativo" class="ml-2">Disciplina Ativa</x-admin.label>
                </div>
            </div>

            <x-slot name="footer">
                <div class="flex justify-end gap-x-4">
                    <x-admin.button href="{{ route('admin.ebd.disciplinas.index') }}" variant="secondary">
                        Cancelar
                    </x-admin.button>
                    <x-admin.button type="submit" variant="primary">
                        Atualizar Disciplina
                    </x-admin.button>
                </div>
            </x-slot>
        </form>
    </x-admin.card>
@endsection