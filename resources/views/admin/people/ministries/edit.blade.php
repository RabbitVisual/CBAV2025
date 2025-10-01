@extends('layouts.admin')

@section('title', __('Editar Ministério'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('Editar Ministério') }}</h1>
        <a href="{{ route('admin.people.ministries.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>{{ __('Voltar') }}
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('admin.people.ministries.update', $ministerio) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Nome do Ministério') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nome" 
                           name="nome" 
                           value="{{ old('nome', $ministerio->nome) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nome') border-red-500 @enderror"
                           placeholder="{{ __('Ex: Ministério de Música') }}"
                           required>
                    @error('nome')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="responsavel_id" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Responsável') }}
                    </label>
                    <select id="responsavel_id" 
                            name="responsavel_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('responsavel_id') border-red-500 @enderror">
                        <option value="">{{ __('Selecione um responsável') }}</option>
                        @foreach($membros as $membro)
                            @if($membro->user)
                                <option value="{{ $membro->user->id }}" {{ old('responsavel_id', $ministerio->responsavel_id) == $membro->user->id ? 'selected' : '' }}>
                                    {{ $membro->nome }} ({{ $membro->email }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('responsavel_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="cor" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Cor de Identificação') }}
                    </label>
                    <input type="color" 
                           id="cor" 
                           name="cor" 
                           value="{{ old('cor', $ministerio->cor ?? '#6366F1') }}"
                           class="w-full h-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('cor') border-red-500 @enderror">
                    @error('cor')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="data_fundacao" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Data de Fundação') }}
                    </label>
                    <input type="date" 
                           id="data_fundacao" 
                           name="data_fundacao" 
                           value="{{ old('data_fundacao', $ministerio->data_fundacao ? $ministerio->data_fundacao->format('Y-m-d') : '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('data_fundacao') border-red-500 @enderror">
                    @error('data_fundacao')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="reuniao_semanal" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Reunião Semanal') }}
                    </label>
                    <input type="text" 
                           id="reuniao_semanal" 
                           name="reuniao_semanal" 
                           value="{{ old('reuniao_semanal', $ministerio->reuniao_semanal) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('reuniao_semanal') border-red-500 @enderror"
                           placeholder="{{ __('Ex: Quartas-feiras às 19h30') }}">
                    @error('reuniao_semanal')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Descrição') }}
                </label>
                <textarea id="descricao" 
                          name="descricao" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('descricao') border-red-500 @enderror"
                          placeholder="{{ __('Descreva os objetivos e atividades do ministério...') }}">{{ old('descricao', $ministerio->descricao) }}</textarea>
                @error('descricao')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <label for="observacoes" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Observações') }}
                </label>
                <textarea id="observacoes" 
                          name="observacoes" 
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('observacoes') border-red-500 @enderror"
                          placeholder="{{ __('Informações adicionais sobre o ministério...') }}">{{ old('observacoes', $ministerio->observacoes) }}</textarea>
                @error('observacoes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <label class="flex items-center">
                    <input type="checkbox" 
                           id="ativo" 
                           name="ativo" 
                           value="1" 
                           {{ old('ativo', $ministerio->ativo) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">{{ __('Ministério Ativo') }}</span>
                </label>
                <p class="text-sm text-gray-500 mt-1">{{ __('Ministérios inativos não aparecem nas listagens públicas') }}</p>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                <form action="{{ route('admin.people.ministries.destroy', $ministerio) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200"
                            onclick="return confirm('{{ __('Tem certeza que deseja excluir este ministério?') }}')">
                        <i class="fas fa-trash mr-2"></i>{{ __('Excluir') }}
                    </button>
                </form>
                <a href="{{ route('admin.people.ministries.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    {{ __('Cancelar') }}
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>{{ __('Salvar Alterações') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 