@extends('layouts.admin')

@section('title', 'Avaliações EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Avaliações EBD</h1>
                <p class="text-gray-600">Gerencie as avaliações da Escola Bíblica Dominical</p>
            </div>
            <a href="{{ route('admin.ebd.avaliacoes.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Nova Avaliação
            </a>
        </div>
    </div>

    @if($avaliacoes->count() > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Avaliação
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aula
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
                        @foreach($avaliacoes as $avaliacao)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $avaliacao->titulo }}</div>
                                        @if($avaliacao->descricao)
                                            <div class="text-sm text-gray-500">{{ Str::limit($avaliacao->descricao, 50) }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $avaliacao->aula->licao->titulo ?? 'Sem Lição' }}</div>
                                        <div class="text-sm text-gray-500">{{ $avaliacao->aula->turma->nome }} - {{ $avaliacao->aula->data_aula->format('d/m/Y') }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $avaliacao->cor_tipo }}">
                                        {{ $avaliacao->tipo_formatado }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $avaliacao->pontuacao_maxima }} pontos
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                                        {{ $avaliacao->obrigatoria ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $avaliacao->obrigatoria ? 'Obrigatória' : 'Opcional' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.ebd.avaliacoes.show', $avaliacao) }}" 
                                           class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.ebd.avaliacoes.edit', $avaliacao) }}" 
                                           class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.ebd.avaliacoes.destroy', $avaliacao) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" 
                                                    onclick="return confirm('Tem certeza que deseja excluir esta avaliação?')">
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
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-clipboard-check text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Nenhuma Avaliação Disponível</h3>
            <p class="text-gray-500">Crie a primeira avaliação para começar a usar o sistema.</p>
        </div>
    @endif

    <!-- Filtros -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtros</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                <select class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="quiz">Quiz</option>
                    <option value="prova">Prova</option>
                    <option value="trabalho">Trabalho</option>
                    <option value="participacao">Participação</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="obrigatoria">Obrigatórias</option>
                    <option value="opcional">Opcionais</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Turma</label>
                <select class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todas</option>
                    @foreach($avaliacoes->pluck('aula.turma')->unique() as $turma)
                        @if($turma)
                            <option value="{{ $turma->id }}">{{ $turma->nome }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input type="text" placeholder="Título da avaliação..." 
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-clipboard-check text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Avaliações</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $avaliacoes->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Obrigatórias</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $avaliacoes->where('obrigatoria', true)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Opcionais</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $avaliacoes->where('obrigatoria', false)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Média Pontuação</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($avaliacoes->avg('pontuacao_maxima'), 1) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 