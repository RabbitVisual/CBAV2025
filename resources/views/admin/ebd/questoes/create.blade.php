@extends('layouts.admin')

@section('title', 'Nova Questão EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Nova Questão EBD</h1>
                <p class="text-gray-600">Crie uma nova questão para avaliações da Escola Bíblica Dominical</p>
            </div>
            <a href="{{ route('admin.ebd.questoes.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.ebd.questoes.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Avaliação -->
                <div class="md:col-span-2">
                    <label for="avaliacao_id" class="block text-sm font-medium text-gray-700 mb-2">Avaliação *</label>
                    <select name="avaliacao_id" id="avaliacao_id" required 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecione uma avaliação</option>
                        @foreach($avaliacoes as $avaliacao)
                            <option value="{{ $avaliacao->id }}" 
                                {{ old('avaliacao_id', $avaliacaoSelecionada ? $avaliacaoSelecionada->id : '') == $avaliacao->id ? 'selected' : '' }}>
                                {{ $avaliacao->titulo }}
                            </option>
                        @endforeach
                    </select>
                    @error('avaliacao_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pergunta -->
                <div class="md:col-span-2">
                    <label for="pergunta" class="block text-sm font-medium text-gray-700 mb-2">Pergunta *</label>
                    <textarea name="pergunta" id="pergunta" rows="4" required
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Digite a pergunta da questão...">{{ old('pergunta') }}</textarea>
                    @error('pergunta')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipo -->
                <div>
                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Questão *</label>
                    <select name="tipo" id="tipo" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecione o tipo</option>
                        @foreach($tipos as $valor => $nome)
                            <option value="{{ $valor }}" {{ old('tipo') == $valor ? 'selected' : '' }}>{{ $nome }}</option>
                        @endforeach
                    </select>
                    @error('tipo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dificuldade -->
                <div>
                    <label for="dificuldade" class="block text-sm font-medium text-gray-700 mb-2">Dificuldade *</label>
                    <select name="dificuldade" id="dificuldade" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecione a dificuldade</option>
                        <option value="facil" {{ old('dificuldade') == 'facil' ? 'selected' : '' }}>Fácil</option>
                        <option value="medio" {{ old('dificuldade') == 'medio' ? 'selected' : '' }}>Médio</option>
                        <option value="dificil" {{ old('dificuldade') == 'dificil' ? 'selected' : '' }}>Difícil</option>
                    </select>
                    @error('dificuldade')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pontuação -->
                <div>
                    <label for="pontuacao" class="block text-sm font-medium text-gray-700 mb-2">Pontuação *</label>
                    <input type="number" name="pontuacao" id="pontuacao" value="{{ old('pontuacao', 1) }}" 
                           min="1" max="100" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('pontuacao')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Resposta Correta -->
                <div>
                    <label for="resposta_correta" class="block text-sm font-medium text-gray-700 mb-2">Resposta Correta</label>
                    <input type="text" name="resposta_correta" id="resposta_correta" value="{{ old('resposta_correta') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Resposta correta para a questão">
                    @error('resposta_correta')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Opções (para múltipla escolha) -->
                <div id="opcoes-container" class="md:col-span-2 hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Opções de Resposta</label>
                    <div id="opcoes-list" class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <input type="text" name="opcoes[]" 
                                   class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Opção A">
                            <button type="button" onclick="removeOpcao(this)" 
                                    class="px-3 py-2 text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="text" name="opcoes[]" 
                                   class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Opção B">
                            <button type="button" onclick="removeOpcao(this)" 
                                    class="px-3 py-2 text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="text" name="opcoes[]" 
                                   class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Opção C">
                            <button type="button" onclick="removeOpcao(this)" 
                                    class="px-3 py-2 text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="text" name="opcoes[]" 
                                   class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Opção D">
                            <button type="button" onclick="removeOpcao(this)" 
                                    class="px-3 py-2 text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" onclick="addOpcao()" 
                            class="mt-2 px-4 py-2 text-blue-600 hover:text-blue-800 text-sm">
                        <i class="fas fa-plus mr-1"></i> Adicionar Opção
                    </button>
                </div>

                <!-- Explicação -->
                <div class="md:col-span-2">
                    <label for="explicacao" class="block text-sm font-medium text-gray-700 mb-2">Explicação da Resposta</label>
                    <textarea name="explicacao" id="explicacao" rows="3"
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Explicação detalhada da resposta correta...">{{ old('explicacao') }}</textarea>
                    @error('explicacao')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ativo -->
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" name="ativo" id="ativo" value="1" 
                               {{ old('ativo', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="ativo" class="ml-2 block text-sm text-gray-900">
                            Questão ativa
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Criar Questão
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoSelect = document.getElementById('tipo');
    const opcoesContainer = document.getElementById('opcoes-container');
    
    tipoSelect.addEventListener('change', function() {
        if (this.value === 'multipla_escolha') {
            opcoesContainer.classList.remove('hidden');
        } else {
            opcoesContainer.classList.add('hidden');
        }
    });
});

function addOpcao() {
    const opcoesList = document.getElementById('opcoes-list');
    const newOpcao = document.createElement('div');
    newOpcao.className = 'flex items-center space-x-2';
    newOpcao.innerHTML = `
        <input type="text" name="opcoes[]" 
               class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
               placeholder="Nova opção">
        <button type="button" onclick="removeOpcao(this)" 
                class="px-3 py-2 text-red-600 hover:text-red-800">
            <i class="fas fa-trash"></i>
        </button>
    `;
    opcoesList.appendChild(newOpcao);
}

function removeOpcao(button) {
    const opcoesList = document.getElementById('opcoes-list');
    if (opcoesList.children.length > 1) {
        button.parentElement.remove();
    }
}
</script>
@endsection 