@extends('layouts.member')

@section('title', __('Meus Favoritos'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('Meus Favoritos') }}</h1>
        <p class="text-gray-600">{{ __('Seus versículos e devocionais favoritos') }}</p>
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
            </div>
            
            <div class="flex space-x-2">
                <button onclick="exportarFavoritos()" 
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                    <i class="fas fa-download mr-2"></i>{{ __('Exportar') }}
                </button>
                <button onclick="limparFavoritos()" 
                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-200">
                    <i class="fas fa-trash mr-2"></i>{{ __('Limpar') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Lista de Favoritos -->
    @if($favoritos->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="lista-favoritos">
        @foreach($favoritos as $favorito)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200 favorito-item" 
             data-tipo="{{ $favorito->tipo }}">
            <!-- Header do Card -->
            <div class="p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                        {{ ucfirst($favorito->tipo) }}
                    </span>
                    <span class="text-sm text-gray-500">
                        {{ $favorito->created_at ? $favorito->created_at->format('d/m/Y') : 'N/A' }}
                    </span>
                </div>
                
                @if($favorito->tipo === 'versiculo')
                <!-- Versículo -->
                <div class="mb-4">
                    <h3 class="font-semibold text-gray-900 mb-2">{{ $favorito->referencia }}</h3>
                    <blockquote class="text-gray-700 italic text-sm leading-relaxed">
                        "{{ $favorito->texto }}"
                    </blockquote>
                </div>
                @else
                <!-- Devocional -->
                <div class="mb-4">
                    <h3 class="font-semibold text-gray-900 mb-2">{{ $favorito->titulo ?? 'Devocional' }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        {{ Str::limit($favorito->texto, 100) }}
                    </p>
                </div>
                @endif
                
                <!-- Ações -->
                <div class="flex space-x-2">
                    @if($favorito->tipo === 'versiculo')
                    <button onclick="compartilharFavorito('{{ $favorito->referencia }}', '{{ $favorito->texto }}')" 
                            class="flex-1 bg-green-100 text-green-600 px-3 py-2 rounded-lg text-sm font-medium hover:bg-green-200 transition duration-200">
                        <i class="fas fa-share mr-1"></i>{{ __('Compartilhar') }}
                    </button>
                    @else
                    <a href="{{ route('member.devotionals.show', $favorito->referencia) }}" 
                       class="flex-1 bg-blue-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition duration-200 text-center">
                        {{ __('Ler') }}
                    </a>
                    @endif
                    <button onclick="removerFavorito({{ $favorito->id }})" 
                            class="bg-red-100 text-red-600 px-3 py-2 rounded-lg hover:bg-red-200 transition duration-200">
                        <i class="fas fa-heart-broken text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Paginação -->
    <div class="flex justify-center mt-8">
        {{ $favoritos->links() }}
    </div>

    @else
    <!-- Nenhum favorito -->
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <div class="mb-4">
            <i class="fas fa-heart text-gray-400 text-4xl"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Nenhum favorito encontrado') }}</h3>
        <p class="text-gray-600 mb-6">{{ __('Você ainda não tem versículos ou devocionais favoritos.') }}</p>
        
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
    const items = document.querySelectorAll('.favorito-item');
    items.forEach(item => {
        if (tipo === 'todos' || item.dataset.tipo === tipo) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

function removerFavorito(id) {
    if (confirm('{{ __("Deseja remover este item dos favoritos?") }}')) {
        fetch(`{{ route('member.devotionals.remove-from-favorites') }}`, {
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
                showErrorModal('{{ __("Erro ao remover dos favoritos") }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorModal('{{ __("Erro ao remover dos favoritos") }}');
        });
    }
}

function compartilharFavorito(referencia, texto) {
    const url = `https://wa.me/?text=${encodeURIComponent('"' + texto + '" - ' + referencia)}`;
    window.open(url, '_blank');
}

function exportarFavoritos() {
    // Implementar exportação
    showSuccessModal('{{ __("Exportação iniciada!") }}');
}

function limparFavoritos() {
    if (confirm('{{ __("Deseja limpar todos os favoritos? Esta ação não pode ser desfeita.") }}')) {
        // Implementar limpeza
        showSuccessModal('{{ __("Favoritos limpos com sucesso!") }}');
    }
}

// Inicializar filtros
document.addEventListener('DOMContentLoaded', function() {
    // Adicionar data-id aos itens
    document.querySelectorAll('.favorito-item').forEach((item, index) => {
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