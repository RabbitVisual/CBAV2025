@extends('layouts.admin')

@section('title', 'Editar Avaliação EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Editar Avaliação EBD</h1>
                <p class="text-gray-600">Edite os dados da avaliação</p>
            </div>
            <a href="{{ route('admin.ebd.avaliacoes.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.ebd.avaliacoes.update', $avaliacao) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Aula -->
                <div class="md:col-span-2">
                    <label for="aula_id" class="block text-sm font-medium text-gray-700 mb-2">Aula *</label>
                    <select name="aula_id" id="aula_id" required 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecione uma aula</option>
                        @foreach($aulas as $aula)
                            <option value="{{ $aula->id }}" {{ old('aula_id', $avaliacao->aula_id) == $aula->id ? 'selected' : '' }}>
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
                    <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $avaliacao->titulo) }}" required
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
                              placeholder="Descrição detalhada da avaliação...">{{ old('descricao', $avaliacao->descricao) }}</textarea>
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
                        <option value="quiz" {{ old('tipo', $avaliacao->tipo) == 'quiz' ? 'selected' : '' }}>Quiz</option>
                        <option value="prova" {{ old('tipo', $avaliacao->tipo) == 'prova' ? 'selected' : '' }}>Prova</option>
                        <option value="trabalho" {{ old('tipo', $avaliacao->tipo) == 'trabalho' ? 'selected' : '' }}>Trabalho</option>
                        <option value="participacao" {{ old('tipo', $avaliacao->tipo) == 'participacao' ? 'selected' : '' }}>Participação</option>
                    </select>
                    @error('tipo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pontuação Máxima -->
                <div>
                    <label for="pontuacao_maxima" class="block text-sm font-medium text-gray-700 mb-2">Pontuação Máxima *</label>
                    <input type="number" name="pontuacao_maxima" id="pontuacao_maxima" 
                           value="{{ old('pontuacao_maxima', $avaliacao->pontuacao_maxima) }}" 
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
                               {{ old('obrigatoria', $avaliacao->obrigatoria) ? 'checked' : '' }}
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
                <a href="{{ route('admin.ebd.avaliacoes.show', $avaliacao) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i>
                    Atualizar Avaliação
                </button>
            </div>
        </form>
    </div>

    <!-- Informações Adicionais -->
    <div class="mt-8 bg-yellow-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">⚠️ Aviso Importante</h3>
        
        <div class="space-y-3">
            <div class="flex items-start">
                <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                <div>
                    <p class="text-sm text-gray-700 font-medium">Alterações em Avaliações Aplicadas</p>
                    <p class="text-sm text-gray-600">Se esta avaliação já foi aplicada aos alunos, as alterações podem afetar os resultados existentes.</p>
                </div>
            </div>
            
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                <div>
                    <p class="text-sm text-gray-700 font-medium">Questões</p>
                    <p class="text-sm text-gray-600">Para adicionar ou editar questões, use a seção específica de questões após salvar as alterações.</p>
                </div>
            </div>
            
            <div class="flex items-start">
                <i class="fas fa-chart-line text-green-600 mt-1 mr-3"></i>
                <div>
                    <p class="text-sm text-gray-700 font-medium">Relatórios</p>
                    <p class="text-sm text-gray-600">As alterações serão refletidas nos relatórios e estatísticas da avaliação.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 