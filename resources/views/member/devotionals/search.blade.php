@extends('layouts.member')

@section('title', __('Buscar Devocionais'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('Buscar Devocionais') }}</h1>
        <p class="text-gray-600">{{ __('Encontre devocionais específicos para sua vida espiritual') }}</p>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form method="GET" action="{{ route('member.devotionals.search') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Busca -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Buscar') }}</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="{{ __('Título, reflexão, oração...') }}">
                </div>

                <!-- Tipo -->
                <div>
                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Tipo') }}</label>
                    <select name="tipo" id="tipo"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('Todos os tipos') }}</option>
                        @foreach($tipos as $key => $tipo)
                        <option value="{{ $key }}" {{ request('tipo') == $key ? 'selected' : '' }}>
                            {{ $tipo }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Categoria -->
                <div>
                    <label for="categoria" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Categoria') }}</label>
                    <select name="categoria" id="categoria"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('Todas as categorias') }}</option>
                        @foreach($categorias as $key => $categoria)
                        <option value="{{ $key }}" {{ request('categoria') == $key ? 'selected' : '' }}>
                            {{ $categoria }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Ordenação -->
                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Ordenar por') }}</label>
                    <select name="sort" id="sort"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="data_desc" {{ request('sort') == 'data_desc' ? 'selected' : '' }}>
                            {{ __('Mais recentes') }}
                        </option>
                        <option value="data_asc" {{ request('sort') == 'data_asc' ? 'selected' : '' }}>
                            {{ __('Mais antigos') }}
                        </option>
                        <option value="titulo" {{ request('sort') == 'titulo' ? 'selected' : '' }}>
                            {{ __('Título A-Z') }}
                        </option>
                        <option value="tipo" {{ request('sort') == 'tipo' ? 'selected' : '' }}>
                            {{ __('Tipo') }}
                        </option>
                    </select>
                </div>
            </div>

            <!-- Botões -->
            <div class="flex space-x-4">
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                    <i class="fas fa-search mr-2"></i>{{ __('Buscar') }}
                </button>
                <a href="{{ route('member.devotionals.search') }}" 
                   class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-200">
                    <i class="fas fa-times mr-2"></i>{{ __('Limpar') }}
                </a>
            </div>
        </form>
    </div>

    <!-- Resultados -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-900">
                {{ __('Resultados') }} 
                @if($devocionais->total() > 0)
                <span class="text-sm font-normal text-gray-500">
                    ({{ $devocionais->total() }} {{ __('devocionais encontrados') }})
                </span>
                @endif
            </h2>
            
            @if($devocionais->total() > 0)
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">{{ __('Mostrando') }}</span>
                <span class="text-sm font-medium">{{ $devocionais->firstItem() }}-{{ $devocionais->lastItem() }}</span>
                <span class="text-sm text-gray-500">{{ __('de') }} {{ $devocionais->total() }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Lista de Devocionais -->
    @if($devocionais->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @foreach($devocionais as $devocional)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200">
            <!-- Header do Card -->
            <div class="p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                        {{ ucfirst($devocional->tipo) }}
                    </span>
                    <span class="text-sm text-gray-500">
                        {{ $devocional->data ? $devocional->data->format('d/m/Y') : 'N/A' }}
                    </span>
                </div>
                
                <h3 class="font-semibold text-gray-900 mb-3 text-lg">{{ $devocional->titulo }}</h3>
                
                @if($devocional->reflexao)
                <p class="text-gray-600 text-sm mb-4 leading-relaxed">
                    {{ Str::limit($devocional->reflexao, 150) }}
                </p>
                @endif
                
                @if($devocional->versiculos)
                <div class="mb-4">
                    <p class="text-xs text-gray-500 mb-2">{{ __('Versículos relacionados:') }}</p>
                    <div class="flex flex-wrap gap-1">
                        @foreach(array_slice($devocional->versiculos, 0, 3) as $versiculo)
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">
                            {{ $versiculo }}
                        </span>
                        @endforeach
                        @if(count($devocional->versiculos) > 3)
                        <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs">
                            +{{ count($devocional->versiculos) - 3 }}
                        </span>
                        @endif
                    </div>
                </div>
                @endif
                
                <!-- Ações -->
                <div class="flex space-x-2">
                    <a href="{{ route('member.devotionals.show', $devocional) }}" 
                       class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition duration-200 text-center">
                        {{ __('Ler') }}
                    </a>
                    <button onclick="adicionarAosFavoritos('devocional', '{{ $devocional->id }}', '{{ $devocional->titulo }}')" 
                            class="bg-red-100 text-red-600 px-3 py-2 rounded-lg hover:bg-red-200 transition duration-200">
                        <i class="fas fa-heart text-sm"></i>
                    </button>
                    <button onclick="compartilharDevocional('{{ $devocional->titulo }}', '{{ route('member.devotionals.show', $devocional) }}')" 
                            class="bg-green-100 text-green-600 px-3 py-2 rounded-lg hover:bg-green-200 transition duration-200">
                        <i class="fas fa-share text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Paginação -->
    <div class="flex justify-center">
        {{ $devocionais->appends(request()->query())->links() }}
    </div>

    @else
    <!-- Nenhum resultado -->
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <div class="mb-4">
            <i class="fas fa-search text-gray-400 text-4xl"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Nenhum devocional encontrado') }}</h3>
        <p class="text-gray-600 mb-6">{{ __('Tente ajustar os filtros de busca para encontrar devocionais.') }}</p>
        
        <div class="flex justify-center space-x-4">
            <a href="{{ route('member.devotionals.index') }}" 
               class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                {{ __('Ver Devocional do Dia') }}
            </a>
            <a href="{{ route('member.bible.index') }}" 
               class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                {{ __('Ir para Bíblia') }}
            </a>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
function adicionarAosFavoritos(tipo, referencia, texto) {
    fetch('{{ route("member.devotionals.add-to-favorites") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            tipo: tipo,
            referencia: referencia,
            texto: texto
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessModal(data.message);
        } else {
            showErrorModal('{{ __("Erro ao adicionar aos favoritos") }}');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorModal('{{ __("Erro ao adicionar aos favoritos") }}');
    });
}

function compartilharDevocional(titulo, url) {
    const texto = `Devocional: ${titulo}`;
    const linkCompleto = `${texto}\n\n${url}`;
    
    if (navigator.share) {
        navigator.share({
            title: titulo,
            text: texto,
            url: url
        });
    } else {
        // Fallback para WhatsApp
        const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(linkCompleto)}`;
        window.open(whatsappUrl, '_blank');
    }
}

// Auto-submit do formulário quando mudar os filtros
document.getElementById('tipo').addEventListener('change', function() {
    document.querySelector('form').submit();
});

document.getElementById('categoria').addEventListener('change', function() {
    document.querySelector('form').submit();
});

document.getElementById('sort').addEventListener('change', function() {
    document.querySelector('form').submit();
});
</script>
@endpush
@endsection 