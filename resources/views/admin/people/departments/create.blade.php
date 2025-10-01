@extends('layouts.admin')

@section('title', __('Criar Departamento'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Criar Departamento') }}</h1>
        <a href="{{ route('admin.people.departments.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>{{ __('Voltar') }}
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-800 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <form action="{{ route('admin.people.departments.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="ministerio_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Ministério') }} <span class="text-red-500">*</span>
                    </label>
                    <select id="ministerio_id" 
                            name="ministerio_id"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('ministerio_id') border-red-500 dark:border-red-400 @enderror"
                            required>
                        <option value="">{{ __('Selecione um ministério') }}</option>
                        @foreach($ministerios as $ministerio)
                            <option value="{{ $ministerio->id }}" 
                                    {{ old('ministerio_id', request('ministerio_id')) == $ministerio->id ? 'selected' : '' }}>
                                {{ $ministerio->nome }} ({{ $ministerio->departamentos_count }} depts.)
                            </option>
                        @endforeach
                    </select>
                    @error('ministerio_id')
                        <p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Nome do Departamento') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nome" 
                           name="nome" 
                           value="{{ old('nome') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('nome') border-red-500 dark:border-red-400 @enderror"
                           placeholder="{{ __('Ex: Departamento de Música') }}"
                           required>
                    @error('nome')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="responsavel_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Responsável pelo Departamento') }}
                    </label>
                    <select id="responsavel_id" 
                            name="responsavel_id"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('responsavel_id') border-red-500 dark:border-red-400 @enderror">
                        <option value="">{{ __('Selecione um responsável') }}</option>
                        @foreach($membros as $membro)
                            <option value="{{ $membro->id }}" 
                                    {{ old('responsavel_id') == $membro->id ? 'selected' : '' }}>
                                {{ $membro->nome }} ({{ $membro->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('responsavel_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" 
                               id="ativo" 
                               name="ativo" 
                               value="1" 
                               {{ old('ativo', true) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('Departamento Ativo') }}</span>
                    </label>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Departamentos inativos não aparecem nas listagens públicas') }}</p>
                </div>
            </div>

            <div class="mt-6">
                <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Descrição') }}
                </label>
                <textarea id="descricao" 
                          name="descricao" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('descricao') border-red-500 dark:border-red-400 @enderror"
                          placeholder="{{ __('Descreva as responsabilidades e objetivos do departamento...') }}">{{ old('descricao') }}</textarea>
                @error('descricao')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <label for="observacoes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Observações') }}
                </label>
                <textarea id="observacoes" 
                          name="observacoes" 
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('observacoes') border-red-500 dark:border-red-400 @enderror"
                          placeholder="{{ __('Informações adicionais sobre o departamento...') }}">{{ old('observacoes') }}</textarea>
                @error('observacoes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('admin.people.departments.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    {{ __('Cancelar') }}
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>{{ __('Criar Departamento') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection