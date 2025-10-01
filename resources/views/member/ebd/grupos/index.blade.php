@extends('layouts.member')

@section('title', 'Meus Grupos de Estudo')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Meus Grupos de Estudo</h1>
        <a href="{{ route('member.ebd.grupos.disponiveis') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
            <i class="fas fa-search mr-2"></i>Encontrar Grupos
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <form method="GET" action="{{ route('member.ebd.grupos.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-64">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                <input type="text" 
                       id="search" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Nome do grupo, turma..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="min-w-48">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="status" 
                        name="status" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="ativo" {{ request('status') === 'ativo' ? 'selected' : '' }}>Ativos</option>
                    <option value="inativo" {{ request('status') === 'inativo' ? 'selected' : '' }}>Inativos</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" 
                        class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-search mr-2"></i>Filtrar
                </button>
            </div>
        </form>
    </div>

    @if($meusGrupos->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($meusGrupos as $membro)
                @php $grupo = $membro->grupo; @endphp
                <div class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-200">
                    <!-- Header do Card -->
                    <div class="p-4 border-b" style="background: linear-gradient(135deg, {{ $grupo->cor }}22, {{ $grupo->cor }}11)">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $grupo->cor }}"></div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $grupo->nome }}</h3>
                            </div>
                            @if($membro->ehLider())
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-crown mr-1"></i>Líder
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 mt-1">{{ $grupo->turma->nome }}</p>
                    </div>

                    <!-- Conteúdo do Card -->
                    <div class="p-4">
                        @if($grupo->descricao)
                            <p class="text-sm text-gray-700 mb-3">{{ Str::limit($grupo->descricao, 100) }}</p>
                        @endif

                        <!-- Estatísticas -->
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="text-center">
                                <div class="text-lg font-bold text-blue-600">{{ $grupo->membrosAtivos->count() }}</div>
                                <div class="text-xs text-gray-500">Membros</div>
                            </div>
                            <div class="text-center">
                                <div class="text-lg font-bold text-green-600">{{ $grupo->avaliacoesConcluidas->count() }}</div>
                                <div class="text-xs text-gray-500">Avaliações</div>
                            </div>
                        </div>

                        <!-- Progresso de Ocupação -->
                        <div class="mb-4">
                            <div class="flex justify-between text-xs text-gray-600 mb-1">
                                <span>Ocupação</span>
                                <span>{{ $grupo->membrosAtivos->count() }}/{{ $grupo->capacidade_maxima }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" 
                                     style="width: {{ $grupo->capacidade_maxima > 0 ? ($grupo->membrosAtivos->count() / $grupo->capacidade_maxima) * 100 : 0 }}%"></div>
                            </div>
                        </div>

                        <!-- Status e Tempo no Grupo -->
                        <div class="flex justify-between items-center text-xs text-gray-500 mb-4">
                            <span class="inline-flex items-center px-2 py-1 rounded-full {{ $grupo->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i class="fas fa-{{ $grupo->ativo ? 'check-circle' : 'times-circle' }} mr-1"></i>
                                {{ $grupo->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                            <span>Membro há {{ $membro->tempo_no_grupo_formatado }}</span>
                        </div>

                        <!-- Avaliações Pendentes -->
                        @if($grupo->avaliacoesPendentes->count() > 0)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                                    <span class="text-sm font-medium text-yellow-800">
                                        {{ $grupo->avaliacoesPendentes->count() }} avaliação(ões) pendente(s)
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Ações -->
                    <div class="px-4 py-3 bg-gray-50 border-t">
                        <div class="flex space-x-2">
                            <a href="{{ route('member.ebd.grupos.show', $grupo) }}" 
                               class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center font-bold py-2 px-3 rounded text-sm transition-colors duration-200">
                                <i class="fas fa-eye mr-1"></i>Ver Grupo
                            </a>
                            
                            @if($grupo->avaliacoesPendentes->count() > 0)
                                <a href="{{ route('member.ebd.grupos.avaliacoes', $grupo) }}" 
                                   class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center font-bold py-2 px-3 rounded text-sm transition-colors duration-200">
                                    <i class="fas fa-clipboard-check mr-1"></i>Avaliar
                                </a>
                            @endif
                            
                            @if(!$membro->ehLider())
                                <form action="{{ route('member.ebd.grupos.sair', $grupo) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Tem certeza que deseja sair deste grupo?')" 
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-3 rounded text-sm transition-colors duration-200">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginação -->
        @if($meusGrupos->hasPages())
            <div class="mt-6">
                {{ $meusGrupos->links() }}
            </div>
        @endif
    @else
        <div class="bg-white shadow-md rounded-lg p-8 text-center">
            <i class="fas fa-users text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Você ainda não participa de nenhum grupo</h3>
            <p class="text-gray-500 mb-6">Encontre grupos de estudo da sua turma e comece a participar!</p>
            <a href="{{ route('member.ebd.grupos.disponiveis') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200">
                <i class="fas fa-search mr-2"></i>Encontrar Grupos
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
// Auto-submit do formulário de filtros quando mudar o select
document.getElementById('status').addEventListener('change', function() {
    this.form.submit();
});

// Limpar filtros
function limparFiltros() {
    document.getElementById('search').value = '';
    document.getElementById('status').value = '';
    document.querySelector('form').submit();
}

// Adicionar botão de limpar filtros se houver filtros ativos
if (document.querySelector('input[name="search"]').value || document.querySelector('select[name="status"]').value) {
    const filterForm = document.querySelector('form');
    const clearButton = document.createElement('button');
    clearButton.type = 'button';
    clearButton.className = 'ml-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded-lg transition-colors duration-200';
    clearButton.innerHTML = '<i class="fas fa-times mr-2"></i>Limpar';
    clearButton.onclick = limparFiltros;
    
    const buttonContainer = filterForm.querySelector('.flex.items-end');
    buttonContainer.appendChild(clearButton);
}
</script>
@endpush
@endsection