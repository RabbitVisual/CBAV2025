@extends('layouts.admin')

@section('page-content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Editar Pergunta</h1>
                <p class="text-gray-600">Edite os detalhes da pergunta do quiz bíblico</p>
            </div>
            <a href="{{ route('admin.ebd.quiz-biblico.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('admin.ebd.quiz-biblico.update', $pergunta) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Pergunta -->
            <div>
                <label for="pergunta" class="block text-sm font-medium text-gray-700 mb-2">
                    Pergunta <span class="text-red-500">*</span>
                </label>
                <textarea id="pergunta" name="pergunta" rows="3" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('pergunta') border-red-500 @enderror"
                          placeholder="Digite a pergunta...">{{ old('pergunta', $pergunta->pergunta) }}</textarea>
                @error('pergunta')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Opções -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="opcao_a" class="block text-sm font-medium text-gray-700 mb-2">
                        Opção A <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="opcao_a" name="opcao_a" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('opcao_a') border-red-500 @enderror"
                           value="{{ old('opcao_a', $pergunta->opcao_a) }}" placeholder="Digite a opção A">
                    @error('opcao_a')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="opcao_b" class="block text-sm font-medium text-gray-700 mb-2">
                        Opção B <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="opcao_b" name="opcao_b" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('opcao_b') border-red-500 @enderror"
                           value="{{ old('opcao_b', $pergunta->opcao_b) }}" placeholder="Digite a opção B">
                    @error('opcao_b')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="opcao_c" class="block text-sm font-medium text-gray-700 mb-2">
                        Opção C <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="opcao_c" name="opcao_c" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('opcao_c') border-red-500 @enderror"
                           value="{{ old('opcao_c', $pergunta->opcao_c) }}" placeholder="Digite a opção C">
                    @error('opcao_c')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="opcao_d" class="block text-sm font-medium text-gray-700 mb-2">
                        Opção D <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="opcao_d" name="opcao_d" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('opcao_d') border-red-500 @enderror"
                           value="{{ old('opcao_d', $pergunta->opcao_d) }}" placeholder="Digite a opção D">
                    @error('opcao_d')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Resposta Correta -->
            <div>
                <label for="resposta_correta" class="block text-sm font-medium text-gray-700 mb-2">
                    Resposta Correta <span class="text-red-500">*</span>
                </label>
                <select id="resposta_correta" name="resposta_correta" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('resposta_correta') border-red-500 @enderror">
                    <option value="">Selecione a resposta correta</option>
                    <option value="a" {{ old('resposta_correta', $pergunta->resposta_correta) == 'a' ? 'selected' : '' }}>Opção A</option>
                    <option value="b" {{ old('resposta_correta', $pergunta->resposta_correta) == 'b' ? 'selected' : '' }}>Opção B</option>
                    <option value="c" {{ old('resposta_correta', $pergunta->resposta_correta) == 'c' ? 'selected' : '' }}>Opção C</option>
                    <option value="d" {{ old('resposta_correta', $pergunta->resposta_correta) == 'd' ? 'selected' : '' }}>Opção D</option>
                </select>
                @error('resposta_correta')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Configurações -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="nivel" class="block text-sm font-medium text-gray-700 mb-2">
                        Nível <span class="text-red-500">*</span>
                    </label>
                    <select id="nivel" name="nivel" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nivel') border-red-500 @enderror">
                        <option value="">Selecione o nível</option>
                        <option value="facil" {{ old('nivel', $pergunta->nivel) == 'facil' ? 'selected' : '' }}>Fácil</option>
                        <option value="medio" {{ old('nivel', $pergunta->nivel) == 'medio' ? 'selected' : '' }}>Médio</option>
                        <option value="dificil" {{ old('nivel', $pergunta->nivel) == 'dificil' ? 'selected' : '' }}>Difícil</option>
                    </select>
                    @error('nivel')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="categoria" class="block text-sm font-medium text-gray-700 mb-2">
                        Categoria <span class="text-red-500">*</span>
                    </label>
                    <select id="categoria" name="categoria" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('categoria') border-red-500 @enderror">
                        <option value="">Selecione a categoria</option>
                        <option value="geral" {{ old('categoria', $pergunta->categoria) == 'geral' ? 'selected' : '' }}>Geral</option>
                        <option value="antigo_testamento" {{ old('categoria', $pergunta->categoria) == 'antigo_testamento' ? 'selected' : '' }}>Antigo Testamento</option>
                        <option value="novo_testamento" {{ old('categoria', $pergunta->categoria) == 'novo_testamento' ? 'selected' : '' }}>Novo Testamento</option>
                        <option value="personagens" {{ old('categoria', $pergunta->categoria) == 'personagens' ? 'selected' : '' }}>Personagens</option>
                        <option value="milagres" {{ old('categoria', $pergunta->categoria) == 'milagres' ? 'selected' : '' }}>Milagres</option>
                        <option value="parabolas" {{ old('categoria', $pergunta->categoria) == 'parabolas' ? 'selected' : '' }}>Parábolas</option>
                        <option value="profetas" {{ old('categoria', $pergunta->categoria) == 'profetas' ? 'selected' : '' }}>Profetas</option>
                        <option value="apostolos" {{ old('categoria', $pergunta->categoria) == 'apostolos' ? 'selected' : '' }}>Apóstolos</option>
                    </select>
                    @error('categoria')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="pontuacao" class="block text-sm font-medium text-gray-700 mb-2">
                        Pontuação <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="pontuacao" name="pontuacao" min="1" max="100" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('pontuacao') border-red-500 @enderror"
                           value="{{ old('pontuacao', $pergunta->pontuacao) }}" placeholder="10">
                    @error('pontuacao')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Referência Bíblica -->
            <div>
                <label for="referencia_biblica" class="block text-sm font-medium text-gray-700 mb-2">
                    Referência Bíblica
                </label>
                <input type="text" id="referencia_biblica" name="referencia_biblica"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('referencia_biblica') border-red-500 @enderror"
                       value="{{ old('referencia_biblica', $pergunta->referencia_biblica) }}" placeholder="Ex: João 3:16">
                @error('referencia_biblica')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Explicação -->
            <div>
                <label for="explicacao" class="block text-sm font-medium text-gray-700 mb-2">
                    Explicação
                </label>
                <textarea id="explicacao" name="explicacao" rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('explicacao') border-red-500 @enderror"
                          placeholder="Digite uma explicação para a resposta correta...">{{ old('explicacao', $pergunta->explicacao) }}</textarea>
                @error('explicacao')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="flex items-center">
                <input type="checkbox" id="ativo" name="ativo" value="1" 
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                       {{ old('ativo', $pergunta->ativo) ? 'checked' : '' }}>
                <label for="ativo" class="ml-2 block text-sm text-gray-900">
                    Pergunta ativa (disponível para os usuários)
                </label>
            </div>

            <!-- Botões -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.ebd.quiz-biblico.index') }}" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i>
                    Atualizar Pergunta
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-preenchimento da referência bíblica baseada na pergunta
    const perguntaInput = document.getElementById('pergunta');
    const referenciaInput = document.getElementById('referencia_biblica');
    
    perguntaInput.addEventListener('input', function() {
        const pergunta = this.value.toLowerCase();
        
        // Sugestões de referências baseadas em palavras-chave
        const sugestoes = {
            'joão': 'João 3:16',
            'mateus': 'Mateus 28:19-20',
            'marcos': 'Marcos 16:15',
            'lucas': 'Lucas 24:46-47',
            'atos': 'Atos 1:8',
            'romanos': 'Romanos 3:23',
            'gênesis': 'Gênesis 1:1',
            'êxodo': 'Êxodo 20:1-17',
            'salmo': 'Salmo 23:1',
            'provérbio': 'Provérbios 3:5-6',
            'isaías': 'Isaías 53:5',
            'jeremias': 'Jeremias 29:11',
            'ezequiel': 'Ezequiel 36:26',
            'daniel': 'Daniel 6:22',
            'amós': 'Amós 3:7',
            'malaquias': 'Malaquias 3:10',
            'apocalipse': 'Apocalipse 3:20'
        };
        
        for (const [palavra, sugestao] of Object.entries(sugestoes)) {
            if (pergunta.includes(palavra) && !referenciaInput.value) {
                referenciaInput.placeholder = `Sugestão: ${sugestao}`;
                break;
            }
        }
    });
});
</script>
@endsection 