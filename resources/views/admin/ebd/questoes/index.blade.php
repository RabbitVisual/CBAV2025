@extends('layouts.admin')

@section('title', 'Questões EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Questões EBD</h1>
            <p class="text-gray-600 mt-2">Gerencie as questões das avaliações da Escola Bíblica Dominical</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.ebd.questoes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i>Nova Questão
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.ebd.questoes.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="avaliacao_id" class="block text-sm font-medium text-gray-700 mb-2">Avaliação</label>
                <select name="avaliacao_id" id="avaliacao_id" class="form-select">
                    <option value="">Todas as avaliações</option>
                    @foreach($avaliacoes as $avaliacao)
                        <option value="{{ $avaliacao->id }}" {{ request('avaliacao_id') == $avaliacao->id ? 'selected' : '' }}>
                            {{ $avaliacao->titulo }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                <select name="tipo" id="tipo" class="form-select">
                    <option value="">Todos os tipos</option>
                    <option value="multipla_escolha" {{ request('tipo') == 'multipla_escolha' ? 'selected' : '' }}>Múltipla Escolha</option>
                    <option value="verdadeiro_falso" {{ request('tipo') == 'verdadeiro_falso' ? 'selected' : '' }}>Verdadeiro/Falso</option>
                    <option value="dissertativa" {{ request('tipo') == 'dissertativa' ? 'selected' : '' }}>Dissertativa</option>
                    <option value="completar" {{ request('tipo') == 'completar' ? 'selected' : '' }}>Completar</option>
                    <option value="associacao" {{ request('tipo') == 'associacao' ? 'selected' : '' }}>Associação</option>
                </select>
            </div>
            
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Buscar por pergunta..." class="form-input">
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="btn btn-secondary w-full">
                    <i class="fas fa-search mr-2"></i>Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de Questões -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($questoes->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Questão
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Avaliação
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tipo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pontuação
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($questoes as $questao)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ Str::limit($questao->pergunta, 80) }}
                                    </div>
                                    @if($questao->explicacao)
                                        <div class="text-sm text-gray-500 mt-1">
                                            {{ Str::limit($questao->explicacao, 60) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ $questao->avaliacao->titulo ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $questao->cor_tipo }}">
                                        {{ $questao->tipo_formatado }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ $questao->pontuacao }} pontos
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $questao->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $questao->ativo ? 'Ativa' : 'Inativa' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.ebd.questoes.show', $questao) }}" 
                                           class="text-blue-600 hover:text-blue-900" title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.ebd.questoes.edit', $questao) }}" 
                                           class="text-yellow-600 hover:text-yellow-900" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.ebd.questoes.destroy', $questao) }}" 
                                              method="POST" class="inline" 
                                              onsubmit="return confirm('Tem certeza que deseja excluir esta questão?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Excluir">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Paginação -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $questoes->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-question-circle text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma questão encontrada</h3>
                <p class="text-gray-500 mb-4">Crie a primeira questão para começar a gerenciar as avaliações.</p>
                <a href="{{ route('admin.ebd.questoes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i>Criar Primeira Questão
                </a>
            </div>
        @endif
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-question-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Questões</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $questoes->total() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Questões Ativas</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $questoes->where('ativo', true)->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clipboard-list text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Avaliações</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $avaliacoes->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-star text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pontuação Média</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($questoes->avg('pontuacao'), 1) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 