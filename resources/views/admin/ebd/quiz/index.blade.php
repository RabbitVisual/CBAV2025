@extends('layouts.admin')

@section('page-content')
<div class="space-y-6">
    <!-- Cabeçalho -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Quiz Bíblico</h1>
            <p class="text-gray-600">Gerencie as perguntas do quiz bíblico</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.ebd.quiz-biblico.estatisticas') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-chart-bar mr-2"></i>
                Estatísticas
            </a>
            <a href="{{ route('admin.ebd.quiz-biblico.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Nova Pergunta
            </a>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-question-circle text-blue-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Total de Perguntas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['total_perguntas'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Perguntas Ativas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['perguntas_ativas'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i class="fas fa-gamepad text-purple-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Total de Sessões</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['total_sessoes'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-2 bg-orange-100 rounded-lg">
                    <i class="fas fa-calendar-day text-orange-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Sessões Hoje</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['sessoes_hoje'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-0">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                <input type="text" id="search" name="search" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Buscar por pergunta...">
            </div>
            <div class="w-48">
                <label for="nivel" class="block text-sm font-medium text-gray-700 mb-1">Nível</label>
                <select id="nivel" name="nivel" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos</option>
                    <option value="facil">Fácil</option>
                    <option value="medio">Médio</option>
                    <option value="dificil">Difícil</option>
                </select>
            </div>
            <div class="w-48">
                <label for="categoria" class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                <select id="categoria" name="categoria" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todas</option>
                    <option value="geral">Geral</option>
                    <option value="antigo_testamento">Antigo Testamento</option>
                    <option value="novo_testamento">Novo Testamento</option>
                    <option value="personagens">Personagens</option>
                    <option value="milagres">Milagres</option>
                    <option value="parabolas">Parábolas</option>
                    <option value="profetas">Profetas</option>
                    <option value="apostolos">Apóstolos</option>
                </select>
            </div>
            <div class="w-48">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="status" name="status" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos</option>
                    <option value="1">Ativas</option>
                    <option value="0">Inativas</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Lista de Perguntas -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Perguntas</h3>
        </div>
        
        @if($perguntas->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pergunta
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nível
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Categoria
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
                    @foreach($perguntas as $pergunta)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ Str::limit($pergunta->pergunta, 60) }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $pergunta->referencia_biblica ? 'Ref: ' . $pergunta->referencia_biblica : 'Sem referência' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pergunta->cor_nivel }}">
                                {{ $pergunta->nivel_formatado }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-900">{{ $pergunta->categoria_formatada }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-gray-900">{{ $pergunta->pontuacao }} pts</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($pergunta->ativo)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Ativa
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Inativa
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.ebd.quiz-biblico.show', $pergunta) }}" 
                                   class="text-blue-600 hover:text-blue-900" title="Ver detalhes">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.ebd.quiz-biblico.edit', $pergunta) }}" 
                                   class="text-yellow-600 hover:text-yellow-900" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="confirmarExclusao({{ $pergunta->id }})" 
                                        class="text-red-600 hover:text-red-900" title="Excluir">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Paginação -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $perguntas->links() }}
        </div>
        @else
        <div class="px-6 py-12 text-center">
            <i class="fas fa-question-circle text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma pergunta encontrada</h3>
            <p class="text-gray-500 mb-4">Comece criando a primeira pergunta do quiz bíblico.</p>
            <a href="{{ route('admin.ebd.quiz-biblico.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Criar Primeira Pergunta
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Modal de Confirmação -->
@include('components.modals')

<script>
function confirmarExclusao(id) {
    if (confirm('Tem certeza que deseja excluir esta pergunta? Esta ação não pode ser desfeita.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/ebd/quiz-biblico/${id}`;
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = '{{ csrf_token() }}';
        
        form.appendChild(methodInput);
        form.appendChild(tokenInput);
        document.body.appendChild(form);
        form.submit();
    }
}

// Filtros dinâmicos
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const nivelSelect = document.getElementById('nivel');
    const categoriaSelect = document.getElementById('categoria');
    const statusSelect = document.getElementById('status');
    
    function aplicarFiltros() {
        const params = new URLSearchParams();
        
        if (searchInput.value) params.append('search', searchInput.value);
        if (nivelSelect.value) params.append('nivel', nivelSelect.value);
        if (categoriaSelect.value) params.append('categoria', categoriaSelect.value);
        if (statusSelect.value) params.append('ativo', statusSelect.value);
        
        window.location.search = params.toString();
    }
    
    searchInput.addEventListener('input', aplicarFiltros);
    nivelSelect.addEventListener('change', aplicarFiltros);
    categoriaSelect.addEventListener('change', aplicarFiltros);
    statusSelect.addEventListener('change', aplicarFiltros);
});
</script>
@endsection 