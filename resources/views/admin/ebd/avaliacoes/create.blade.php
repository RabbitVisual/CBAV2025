@extends('layouts.admin')

@section('title', 'Nova Avaliação EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Nova Avaliação EBD</h1>
                <p class="text-gray-600">Crie uma nova avaliação para a Escola Bíblica Dominical</p>
            </div>
            <a href="{{ route('admin.ebd.avaliacoes.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.ebd.avaliacoes.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Aula -->
                <div class="md:col-span-2">
                    <label for="aula_id" class="block text-sm font-medium text-gray-700 mb-2">Aula *</label>
                    <select name="aula_id" id="aula_id" required 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecione uma aula</option>
                        @foreach($aulas as $aula)
                            <option value="{{ $aula->id }}" {{ old('aula_id') == $aula->id ? 'selected' : '' }}>
                                {{ $aula->licao->titulo ?? 'Sem Lição' }} - {{ $aula->turma->nome }} ({{ $aula->data_aula->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>
                    @error('aula_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Título -->
                <div class="md:col-span-2">
                    <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">Título da Avaliação *</label>
                    <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ex: Quiz sobre a Lição 1">
                    @error('titulo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descrição -->
                <div class="md:col-span-2">
                    <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                    <textarea name="descricao" id="descricao" rows="3"
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Descrição detalhada da avaliação...">{{ old('descricao') }}</textarea>
                    @error('descricao')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipo -->
                <div>
                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Avaliação *</label>
                    <select name="tipo" id="tipo" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecione o tipo</option>
                        <option value="quiz" {{ old('tipo') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                        <option value="prova" {{ old('tipo') == 'prova' ? 'selected' : '' }}>Prova</option>
                        <option value="trabalho" {{ old('tipo') == 'trabalho' ? 'selected' : '' }}>Trabalho</option>
                        <option value="participacao" {{ old('tipo') == 'participacao' ? 'selected' : '' }}>Participação</option>
                    </select>
                    @error('tipo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pontuação Máxima -->
                <div>
                    <label for="pontuacao_maxima" class="block text-sm font-medium text-gray-700 mb-2">Pontuação Máxima *</label>
                    <input type="number" name="pontuacao_maxima" id="pontuacao_maxima" value="{{ old('pontuacao_maxima', 10) }}" 
                           min="1" max="100" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('pontuacao_maxima')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Obrigatória -->
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" name="obrigatoria" id="obrigatoria" value="1" 
                               {{ old('obrigatoria') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="obrigatoria" class="ml-2 block text-sm text-gray-900">
                            Avaliação obrigatória
                        </label>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Marque se esta avaliação é obrigatória para aprovação</p>
                </div>
            </div>

            <!-- Botões -->
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('admin.ebd.avaliacoes.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i>
                    Criar Avaliação
                </button>
            </div>
        </form>
    </div>

    <!-- Informações Adicionais -->
    <div class="mt-8 bg-blue-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações sobre Avaliações</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium text-gray-900 mb-2">Tipos de Avaliação:</h4>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• <strong>Quiz:</strong> Perguntas de múltipla escolha ou verdadeiro/falso</li>
                    <li>• <strong>Prova:</strong> Avaliação tradicional com questões dissertativas</li>
                    <li>• <strong>Trabalho:</strong> Atividade prática ou pesquisa</li>
                    <li>• <strong>Participação:</strong> Avaliação baseada na participação em aula</li>
                </ul>
            </div>
            
            <div>
                <h4 class="font-medium text-gray-900 mb-2">Dicas:</h4>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Escolha uma aula específica para a avaliação</li>
                    <li>• Defina uma pontuação adequada ao tipo de avaliação</li>
                    <li>• Marque como obrigatória apenas avaliações essenciais</li>
                    <li>• Após criar, adicione questões específicas</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection 