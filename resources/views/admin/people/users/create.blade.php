@extends('layouts.admin')

@section('title', __('Criar Usuário'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ __('Criar Usuário') }}</h1>
        <a href="{{ route('admin.people.users.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>{{ __('Voltar') }}
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <form action="{{ route('admin.people.users.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Nome') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                           placeholder="{{ __('Digite o nome completo') }}"
                           required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('E-mail') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                           placeholder="{{ __('exemplo@email.com') }}"
                           required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Senha') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                           placeholder="{{ __('Digite a senha') }}"
                           required>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Confirmar Senha') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password_confirmation') border-red-500 @enderror"
                           placeholder="{{ __('Confirme a senha') }}"
                           required>
                    @error('password_confirmation')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="membro_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Membro Associado') }}
                    </label>
                    <select id="membro_id" 
                            name="membro_id"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('membro_id') border-red-500 @enderror">
                        <option value="">{{ __('Criar novo membro automaticamente') }}</option>
                        @foreach(\App\Models\Membro::whereNull('user_id')->get() as $membro)
                            <option value="{{ $membro->id }}" {{ old('membro_id') == $membro->id ? 'selected' : '' }}>
                                {{ $membro->nome }} ({{ $membro->email }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Se não selecionado, será criado um novo membro com os dados informados') }}</p>
                    @error('membro_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Tipo de Usuário') }}
                    </label>
                    <select id="role" 
                            name="role"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('role') border-red-500 @enderror">
                        <option value="member" {{ old('role') == 'member' ? 'selected' : '' }}>{{ __('Membro') }}</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>{{ __('Administrador') }}</option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label class="flex items-center">
                    <input type="checkbox" 
                           id="ativo" 
                           name="ativo" 
                           value="1" 
                           {{ old('ativo', true) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('Usuário Ativo') }}</span>
                </label>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Usuários inativos não podem fazer login') }}</p>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('admin.people.users.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    {{ __('Cancelar') }}
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>{{ __('Criar Usuário') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection