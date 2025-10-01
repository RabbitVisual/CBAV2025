@extends('layouts.admin')

@section('title', 'Editar Atividade')

@section('content')
    <div class="flex justify-between items-center mb-2">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Editar Atividade</h1>
    </div>
    <p class="text-gray-600 dark:text-gray-400 mb-6">Para a lição: <span class="font-semibold">{{ $licao->titulo }}</span></p>

    <x-admin.card>
        <form action="{{ route('admin.ebd.atividades.update', $atividade) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Título da Atividade --}}
                <div class="md:col-span-2">
                    <x-admin.label for="titulo">Título da Atividade</x-admin.label>
                    <x-admin.input name="titulo" id="titulo" value="{{ old('titulo', $atividade->titulo) }}" required />
                    @error('titulo') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Descrição --}}
                <div class="md:col-span-2">
                    <x-admin.label for="descricao">Instruções / Descrição</x-admin.label>
                    <x-admin.textarea name="descricao" id="descricao" rows="5">{{ old('descricao', $atividade->descricao) }}</x-admin.textarea>
                    @error('descricao') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Tipo de Atividade --}}
                <div>
                    <x-admin.label for="tipo">Tipo de Atividade</x-admin.label>
                    <select name="tipo" id="tipo" required class="mt-1 block w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="leitura_dirigida" {{ old('tipo', $atividade->tipo) == 'leitura_dirigida' ? 'selected' : '' }}>Leitura Dirigida</option>
                        <option value="reflexao_pessoal" {{ old('tipo', $atividade->tipo) == 'reflexao_pessoal' ? 'selected' : '' }}>Reflexão Pessoal</option>
                        <option value="pesquisa_biblica" {{ old('tipo', $atividade->tipo) == 'pesquisa_biblica' ? 'selected' : '' }}>Pesquisa Bíblica</option>
                        <option value="trabalho_em_grupo" {{ old('tipo', $atividade->tipo) == 'trabalho_em_grupo' ? 'selected' : '' }}>Trabalho em Grupo</option>
                        <option value="memorizacao" {{ old('tipo', $atividade->tipo) == 'memorizacao' ? 'selected' : '' }}>Memorização</option>
                        <option value="projeto_especial" {{ old('tipo', $atividade->tipo) == 'projeto_especial' ? 'selected' : '' }}>Projeto Especial</option>
                    </select>
                    @error('tipo') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Pontuação Máxima --}}
                <div>
                    <x-admin.label for="pontuacao_maxima">Pontuação Máxima</x-admin.label>
                    <x-admin.input type="number" name="pontuacao_maxima" id="pontuacao_maxima" value="{{ old('pontuacao_maxima', $atividade->pontuacao_maxima) }}" required />
                    @error('pontuacao_maxima') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Data de Entrega --}}
                <div>
                    <x-admin.label for="data_entrega">Data de Entrega</x-admin.label>
                    <x-admin.input type="date" name="data_entrega" id="data_entrega" value="{{ old('data_entrega', $atividade->data_entrega ? \Carbon\Carbon::parse($atividade->data_entrega)->format('Y-m-d') : '') }}" />
                    @error('data_entrega') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Status --}}
                <div class="flex items-center pt-6">
                    <input id="ativo" name="ativo" type="checkbox" value="1" {{ old('ativo', $atividade->ativo) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <x-admin.label for="ativo" class="ml-2">Atividade Ativa</x-admin.label>
                </div>
            </div>

            <x-slot name="footer">
                <div class="flex justify-end gap-x-4">
                    <x-admin.button href="{{ route('admin.ebd.licoes.show', $licao) }}" variant="secondary">
                        Cancelar
                    </x-admin.button>
                    <x-admin.button type="submit">
                        Atualizar Atividade
                    </x-admin.button>
                </div>
            </x-slot>
        </form>
    </x-admin.card>
@endsection