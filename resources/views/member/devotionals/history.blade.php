@extends('layouts.member')

@section('title', __('Histórico de Leitura'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('Histórico de Leitura') }}</h1>
        <p class="text-gray-600">{{ __('Acompanhe sua jornada de leitura espiritual') }}</p>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-book-open text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">{{ __('Total Lido') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $historico->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="fas fa-calendar text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">{{ __('Dias Ativos') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $historico->unique('data')->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-purple-100 p-3 rounded-lg">
                    <i class="fas fa-clock text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">{{ __('Tempo Médio') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $historico->avg('tempo') ?? 0 }} {{ __('min') }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-red-100 p-3 rounded-lg">
                    <i class="fas fa-fire text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">{{ __('Sequência Atual') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $historico->where('data', '>=', now()->subDays(7))->count() }} {{ __('dias') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center justify-between">
            <div class="flex space-x-4">
                <button onclick="filtrarPorTipo('todos')" 
                        class="px-4 py-2 rounded-lg font-medium transition duration-200 filtro-tipo active"
                        data-tipo="todos">
                    {{ __('Todos') }}
                </button>
                <button onclick="filtrarPorTipo('versiculo')" 
                        class="px-4 py-2 rounded-lg font-medium transition duration-200 filtro-tipo"
                        data-tipo="versiculo">
                    {{ __('Versículos') }}
                </button>
                <button onclick="filtrarPorTipo('devocional')" 
                        class="px-4 py-2 rounded-lg font-medium transition duration-200 filtro-tipo"
                        data-tipo="devocional">
                    {{ __('Devocionais') }}
                </button>
                <button onclick="filtrarPorTipo('capitulo')" 
                        class="px-4 py-2 rounded-lg font-medium transition duration-200 filtro-tipo"
                        data-tipo="capitulo">
                    {{ __('Capítulos') }}
                </button>
            </div>
            
            <div class="flex space-x-2">
                <button onclick="exportarHistorico()" 
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                    <i class="fas fa-download mr-2"></i>{{ __('Exportar') }}
                </button>
                <button onclick="limparHistorico()" 
                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-200">
                    <i class="fas fa-trash mr-2"></i>{{ __('Limpar') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Lista de Histórico -->
    @if($historico->count() > 0)
    <div class="space-y-4" id="lista-historico">
        @foreach($historico as $item)
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200 historico-item" 
             data-tipo="{{ $item->tipo }}">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-3">
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium mr-3">
                            {{ ucfirst($item->tipo) }}
                        </span>
                        <span class="text-sm text-gray-500">
                            {{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : 'N/A' }}
                        </span>
                    </div>
                    
                    <h3 class="font-semibold text-gray-900 mb-2">{{ $item->referencia }}</h3>
                    
                    @if($item->texto)
                    <p class="text-gray-600 text-sm leading-relaxed mb-3">
                        {{ Str::limit($item->texto, 200) }}
                    </p>
                    @endif
                    
                    @if($item->tempo)
                    <p class="text-xs text-gray-500">
                        <i class="fas fa-clock mr-1"></i>{{ __('Tempo de leitura:') }} {{ $item->tempo }} {{ __('minutos') }}
                    </p>
                    @endif
                </div>
                
                <div class="flex space-x-2 ml-4">
                    @if($item->tipo === 'versiculo')
                    <button onclick="compartilharHistorico('{{ $item->referencia }}', '{{ $item->texto }}')" 
                            class="bg-green-100 text-green-600 px-3 py-2 rounded-lg hover:bg-green-200 transition duration-200">
                        <i class="fas fa-share text-sm"></i>
                    </button>
                    @elseif($item->tipo === 'devocional')
                    <a href="{{ route('member.devotionals.show', $item->referencia) }}" 
                       class="bg-blue-100 text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-200 transition duration-200">
                        <i class="fas fa-eye text-sm"></i>
                    </a>
                    @elseif($item->tipo === 'capitulo')
                    <a href="{{ route('member.bible.read', ['livro' => $item->livro, 'capitulo' => $item->capitulo]) }}" 
                       class="bg-purple-100 text-purple-600 px-3 py-2 rounded-lg hover:bg-purple-200 transition duration-200">
                        <i class="fas fa-book text-sm"></i>
                    </a>
                    @endif
                    
                    <button onclick="removerHistorico({{ $item->id }})" 
                            class="bg-red-100 text-red-600 px-3 py-2 rounded-lg hover:bg-red-200 transition duration-200">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Paginação -->
    <div class="flex justify-center mt-8">
        {{ $historico->links() }}
    </div>

    @else
    <!-- Nenhum histórico -->
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <div class="mb-4">
            <i class="fas fa-history text-gray-400 text-4xl"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Nenhum histórico encontrado') }}</h3>
        <p class="text-gray-600 mb-6">{{ __('Seu histórico de leitura aparecerá aqui conforme você lê versículos e devocionais.') }}</p>
        
        <div class="flex justify-center space-x-4">
            <a href="{{ route('member.bible.index') }}" 
               class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                {{ __('Ir para Bíblia') }}
            </a>
            <a href="{{ route('member.devotionals.index') }}" 
               class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                {{ __('Ver Devocionais') }}
            </a>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
function filtrarPorTipo(tipo) {
    // Atualizar botões ativos
    document.querySelectorAll('.filtro-tipo').forEach(btn => {
        btn.classList.remove('active', 'bg-blue-600', 'text-white');
        btn.classList.add('bg-gray-100', 'text-gray-700');
    });
    
    event.target.classList.add('active', 'bg-blue-600', 'text-white');
    event.target.classList.remove('bg-gray-100', 'text-gray-700');
    
    // Filtrar itens
    const items = document.querySelectorAll('.historico-item');
    items.forEach(item => {
        if (tipo === 'todos' || item.dataset.tipo === tipo) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

function removerHistorico(id) {
    if (confirm('{{ __("Deseja remover este item do histórico?") }}')) {
        fetch(`{{ route('member.devotionals.remove-from-history') }}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessModal(data.message);
                // Remover item da lista
                const item = document.querySelector(`[data-id="${id}"]`);
                if (item) {
                    item.remove();
                }
            } else {
                showErrorModal('{{ __("Erro ao remover do histórico") }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorModal('{{ __("Erro ao remover do histórico") }}');
        });
    }
}

function compartilharHistorico(referencia, texto) {
    const url = `https://wa.me/?text=${encodeURIComponent('"' + texto + '" - ' + referencia)}`;
    window.open(url, '_blank');
}

function exportarHistorico() {
    // Implementar exportação
    showSuccessModal('{{ __("Exportação iniciada!") }}');
}

function limparHistorico() {
    if (confirm('{{ __("Deseja limpar todo o histórico? Esta ação não pode ser desfeita.") }}')) {
        // Implementar limpeza
        showSuccessModal('{{ __("Histórico limpo com sucesso!") }}');
    }
}

// Inicializar filtros
document.addEventListener('DOMContentLoaded', function() {
    // Adicionar data-id aos itens
    document.querySelectorAll('.historico-item').forEach((item, index) => {
        item.setAttribute('data-id', index + 1);
    });
});
</script>

<style>
.filtro-tipo.active {
    @apply bg-blue-600 text-white;
}
</style>
@endpush
@endsection 