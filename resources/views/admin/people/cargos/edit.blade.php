@extends('layouts.admin')

@section('title', 'Editar Cargo')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Editar Cargo</h1>
        <a href="{{ url()->previous() }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Voltar
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-800 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Formulário -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <form action="{{ route('admin.people.cargos.update', $cargo) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nome do Cargo *
                    </label>
                    <input type="text" name="nome" id="nome" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 @error('nome') border-red-500 @enderror"
                           value="{{ old('nome', $cargo->nome) }}" 
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
                                    {{ old('departamento_id', $cargo->departamento_id) == $departamento->id ? 'selected' : '' }}>
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
                        <option value="1" {{ old('ativo', $cargo->ativo) == 1 ? 'selected' : '' }}>Ativo</option>
                        <option value="0" {{ old('ativo', $cargo->ativo) == 0 ? 'selected' : '' }}>Inativo</option>
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
                              placeholder="Descreva as responsabilidades e atribuições do cargo...">{{ old('descricao', $cargo->descricao) }}</textarea>
                    @error('descricao')
                        <p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="sistema" value="1" id="sistema"
                               class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700"
                               {{ old('sistema', $cargo->sistema) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Cargo para usuários do sistema</span>
                    </label>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Marque esta opção se este cargo é destinado a usuários que terão acesso administrativo ao sistema</p>
                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="mt-8 flex justify-between items-center">
                <button type="button" 
                        onclick="confirmarExclusao()"
                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-trash mr-2"></i>Excluir Cargo
                </button>
                <div class="flex space-x-4">
                    <a href="{{ url()->previous() }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i>Atualizar Cargo
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div id="modalExclusao" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-bold text-gray-800">Confirmar Exclusão</h3>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-6">
                        Tem certeza que deseja excluir o cargo <strong>"{{ $cargo->nome }}"</strong>? 
                        Esta ação não pode ser desfeita.
                    </p>
                    <div class="flex justify-end space-x-3">
                        <button type="button" 
                                onclick="cancelarExclusao()"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancelar
                        </button>
                        <form action="{{ route('admin.people.cargos.delete', $cargo) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                                Excluir
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarExclusao() {
    document.getElementById('modalExclusao').classList.remove('hidden');
}

function cancelarExclusao() {
    document.getElementById('modalExclusao').classList.add('hidden');
}
</script>
@endsection