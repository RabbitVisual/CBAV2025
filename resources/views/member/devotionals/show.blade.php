@extends('layouts.member')

@section('title', $devocional->titulo)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-500">
            <li><a href="{{ route('member.devotionals.index') }}" class="hover:text-blue-600">{{ __('Devocionais') }}</a></li>
            <li><i class="fas fa-chevron-right text-xs"></i></li>
            <li class="text-gray-900">{{ $devocional->titulo }}</li>
        </ol>
    </nav>

    <!-- Header do Devocional -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                    {{ ucfirst($devocional->tipo) }}
                </span>
                @if($devocional->categoria)
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium ml-2">
                    {{ ucfirst($devocional->categoria) }}
                </span>
                @endif
            </div>
            <span class="text-sm text-gray-500">
                {{ $devocional->data ? $devocional->data->format('d/m/Y') : 'N/A' }}
            </span>
        </div>
        
        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $devocional->titulo }}</h1>
        
        @if($devocional->descricao)
        <p class="text-gray-600 text-lg mb-6">{{ $devocional->descricao }}</p>
        @endif
        
        <!-- Ações -->
        <div class="flex space-x-3">
            <button onclick="marcarComoLido({{ $devocional->id }})" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                <i class="fas fa-check mr-2"></i>{{ __('Marcar como Lido') }}
            </button>
            <button onclick="adicionarAosFavoritos('devocional', '{{ $devocional->id }}', '{{ $devocional->titulo }}')" 
                    class="bg-red-100 text-red-600 px-4 py-2 rounded-lg hover:bg-red-200 transition duration-200">
                <i class="fas fa-heart mr-2"></i>{{ __('Favoritar') }}
            </button>
            <button onclick="compartilharDevocional('{{ $devocional->titulo }}', '{{ request()->url() }}')" 
                    class="bg-green-100 text-green-600 px-4 py-2 rounded-lg hover:bg-green-200 transition duration-200">
                <i class="fas fa-share mr-2"></i>{{ __('Compartilhar') }}
            </button>
        </div>
    </div>

    <!-- Conteúdo do Devocional -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Conteúdo Principal -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Reflexão -->
            @if($devocional->reflexao)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('Reflexão') }}</h2>
                <div class="prose max-w-none">
                    <p class="text-gray-700 leading-relaxed text-lg">{{ $devocional->reflexao }}</p>
                </div>
            </div>
            @endif

            <!-- Oração -->
            @if($devocional->oracao)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('Oração') }}</h2>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-700 leading-relaxed italic">{{ $devocional->oracao }}</p>
                </div>
                <div class="flex space-x-3 mt-4">
                    <button onclick="copiarOracao()" 
                            class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition duration-200">
                        <i class="fas fa-copy mr-2"></i>{{ __('Copiar') }}
                    </button>
                    <button onclick="compartilharOracao()" 
                            class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-200">
                        <i class="fas fa-share mr-2"></i>{{ __('Compartilhar') }}
                    </button>
                </div>
            </div>
            @endif

            <!-- Meditação -->
            @if($devocional->meditacao)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('Meditação') }}</h2>
                <div class="prose max-w-none">
                    <p class="text-gray-700 leading-relaxed">{{ $devocional->meditacao }}</p>
                </div>
            </div>
            @endif

            <!-- Versículos Relacionados -->
            @if(count($versiculos) > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('Versículos Relacionados') }}</h2>
                <div class="space-y-4">
                    @foreach($versiculos as $versiculo)
                    <div class="border-l-4 border-blue-500 pl-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-blue-600">{{ $versiculo['reference'] ?? 'N/A' }}</span>
                            <div class="flex space-x-2">
                                <button onclick="adicionarAosFavoritos('versiculo', '{{ $versiculo['reference'] ?? '' }}', '{{ $versiculo['text'] ?? '' }}')" 
                                        class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-heart text-sm"></i>
                                </button>
                                <button onclick="compartilharVersiculo('{{ $versiculo['reference'] ?? '' }}', '{{ $versiculo['text'] ?? '' }}')" 
                                        class="text-green-500 hover:text-green-700">
                                    <i class="fas fa-share text-sm"></i>
                                </button>
                            </div>
                        </div>
                        <blockquote class="text-gray-700 italic">
                            "{{ $versiculo['text'] ?? 'Versículo não disponível' }}"
                        </blockquote>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Informações -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Informações') }}</h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm text-gray-500">{{ __('Tipo:') }}</span>
                        <p class="font-medium">{{ ucfirst($devocional->tipo) }}</p>
                    </div>
                    @if($devocional->categoria)
                    <div>
                        <span class="text-sm text-gray-500">{{ __('Categoria:') }}</span>
                        <p class="font-medium">{{ ucfirst($devocional->categoria) }}</p>
                    </div>
                    @endif
                    <div>
                        <span class="text-sm text-gray-500">{{ __('Data:') }}</span>
                        <p class="font-medium">{{ $devocional->data ? $devocional->data->format('d/m/Y') : 'N/A' }}</p>
                    </div>
                    @if($devocional->versiculos)
                    <div>
                        <span class="text-sm text-gray-500">{{ __('Versículos:') }}</span>
                        <p class="font-medium">{{ count($devocional->versiculos) }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Devocionais Relacionados -->
            @if($devocionaisRelacionados->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Devocionais Relacionados') }}</h3>
                <div class="space-y-3">
                    @foreach($devocionaisRelacionados as $relacionado)
                    <div class="border border-gray-200 rounded-lg p-3">
                        <h4 class="font-medium text-gray-900 mb-1">{{ $relacionado->titulo }}</h4>
                        <p class="text-sm text-gray-600 mb-2">{{ Str::limit($relacionado->reflexao, 80) }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">{{ $relacionado->data ? $relacionado->data->format('d/m/Y') : 'N/A' }}</span>
                            <a href="{{ route('member.devotionals.show', $relacionado) }}" 
                               class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                {{ __('Ler') }} →
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Ações Rápidas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Ações Rápidas') }}</h3>
                <div class="space-y-3">
                    <a href="{{ route('member.bible.index') }}" 
                       class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition duration-200">
                        <i class="fas fa-bible text-blue-600 mr-3"></i>
                        <span class="text-blue-900 font-medium">{{ __('Ir para Bíblia') }}</span>
                    </a>
                    <a href="{{ route('member.devotionals.search') }}" 
                       class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition duration-200">
                        <i class="fas fa-search text-green-600 mr-3"></i>
                        <span class="text-green-900 font-medium">{{ __('Buscar Devocionais') }}</span>
                    </a>
                    <a href="{{ route('member.devotionals.favorites') }}" 
                       class="flex items-center p-3 bg-red-50 rounded-lg hover:bg-red-100 transition duration-200">
                        <i class="fas fa-heart text-red-600 mr-3"></i>
                        <span class="text-red-900 font-medium">{{ __('Meus Favoritos') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function marcarComoLido(devocionalId) {
    // Implementar marcação como lido
    showSuccessModal('{{ __("Devocional marcado como lido!") }}');
}

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

function compartilharVersiculo(referencia, texto) {
    const url = `https://wa.me/?text=${encodeURIComponent('"' + texto + '" - ' + referencia)}`;
    window.open(url, '_blank');
}

function copiarOracao() {
    const oracao = document.querySelector('.bg-gray-50 p').textContent;
    navigator.clipboard.writeText(oracao).then(() => {
        showSuccessModal('{{ __("Oração copiada para a área de transferência!") }}');
    });
}

function compartilharOracao() {
    const oracao = document.querySelector('.bg-gray-50 p').textContent;
    const url = `https://wa.me/?text=${encodeURIComponent(oracao)}`;
    window.open(url, '_blank');
}
</script>
@endpush
@endsection 