@extends('layouts.member')

@section('title', __('Devocionais'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('Devocionais') }}</h1>
        <p class="text-gray-600">{{ __('Alimente sua vida espiritual com devocionais diários') }}</p>
    </div>

    <!-- Devocional do Dia -->
    @if($devocionalHoje)
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg shadow-lg p-6 mb-8 text-white">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-bold">{{ __('Devocional do Dia') }}</h2>
            <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm">
                {{ isset($devocionalHoje['data']) ? \Carbon\Carbon::parse($devocionalHoje['data'])->format('d/m/Y') : now()->format('d/m/Y') }}
            </span>
        </div>
        
        <h3 class="text-xl font-semibold mb-3">{{ $devocionalHoje['titulo'] ?? __('Devocional do Dia') }}</h3>
        
        @if(isset($devocionalHoje['reflexao']) && $devocionalHoje['reflexao'])
        <div class="mb-4">
            <p class="text-blue-100 leading-relaxed">{{ Str::limit($devocionalHoje['reflexao'], 200) }}</p>
        </div>
        @endif
        
        <div class="flex space-x-4">
            @if(isset($devocionalHoje['source']) && $devocionalHoje['source'] === 'banco')
                <a href="{{ route('member.devotionals.show', ['devocional' => $devocionalHoje['id'] ?? 1]) }}" 
                   class="bg-white text-blue-600 px-4 py-2 rounded-lg font-medium hover:bg-blue-50 transition duration-200">
                    {{ __('Ler Completo') }}
                </a>
                <button onclick="marcarComoLido({{ $devocionalHoje['id'] ?? 1 }})" 
                        class="bg-white bg-opacity-20 text-white px-4 py-2 rounded-lg font-medium hover:bg-opacity-30 transition duration-200">
                    {{ __('Marcar como Lido') }}
                </button>
            @else
                <button onclick="mostrarDevocionalCompleto()" 
                        class="bg-white text-blue-600 px-4 py-2 rounded-lg font-medium hover:bg-blue-50 transition duration-200">
                    {{ __('Ler Completo') }}
                </button>
            @endif
        </div>
    </div>
    @endif

    <!-- Versículo do Dia -->
    @if($versiculoDoDia)
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-900">{{ __('Versículo do Dia') }}</h2>
            <span class="text-sm text-gray-500">{{ $versiculoDoDia['referencia'] ?? 'N/A' }}</span>
        </div>
        
        <blockquote class="border-l-4 border-blue-500 pl-4 mb-4">
            <p class="text-lg text-gray-700 italic leading-relaxed">
                "{{ $versiculoDoDia['texto'] ?? 'Versículo não disponível' }}"
            </p>
        </blockquote>
        
        <div class="flex space-x-3">
            <button onclick="adicionarAosFavoritos('versiculo', '{{ $versiculoDoDia['referencia'] ?? '' }}', '{{ $versiculoDoDia['texto'] ?? '' }}')" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                <i class="fas fa-heart mr-2"></i>{{ __('Favoritar') }}
            </button>
            <button onclick="compartilharVersiculo('{{ $versiculoDoDia['referencia'] ?? '' }}', '{{ $versiculoDoDia['texto'] ?? '' }}')" 
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                <i class="fas fa-share mr-2"></i>{{ __('Compartilhar') }}
            </button>
        </div>
    </div>
    @endif

    <!-- Oração do Dia -->
    @if($oracaoDoDia)
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('Oração do Dia') }}</h2>
        
        <div class="bg-gray-50 rounded-lg p-4 mb-4">
            <p class="text-gray-700 leading-relaxed">{{ $oracaoDoDia['texto'] }}</p>
        </div>
        
        <div class="flex space-x-3">
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

    <!-- Estatísticas Pessoais -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-book-open text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">{{ __('Total Lidos') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['total_lidos'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="fas fa-fire text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">{{ __('Sequência Atual') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['sequencia_atual'] }} {{ __('dias') }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-red-100 p-3 rounded-lg">
                    <i class="fas fa-heart text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">{{ __('Favoritos') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['versiculos_favoritos'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">{{ __('Tempo Médio') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['tempo_medio'] }} {{ __('min') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Ações Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <a href="{{ route('member.bible.index') }}" 
           class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200 text-center">
            <div class="bg-blue-100 p-3 rounded-lg inline-block mb-4">
                <i class="fas fa-bible text-blue-600 text-2xl"></i>
            </div>
            <h3 class="font-semibold text-gray-900 mb-2">{{ __('Bíblia') }}</h3>
            <p class="text-sm text-gray-600">{{ __('Leia e pesquise versículos') }}</p>
        </a>
        
        <a href="{{ route('member.devotionals.search') }}" 
           class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200 text-center">
            <div class="bg-green-100 p-3 rounded-lg inline-block mb-4">
                <i class="fas fa-search text-green-600 text-2xl"></i>
            </div>
            <h3 class="font-semibold text-gray-900 mb-2">{{ __('Buscar') }}</h3>
            <p class="text-sm text-gray-600">{{ __('Encontre devocionais específicos') }}</p>
        </a>
        
        <a href="{{ route('member.devotionals.favorites') }}" 
           class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200 text-center">
            <div class="bg-red-100 p-3 rounded-lg inline-block mb-4">
                <i class="fas fa-heart text-red-600 text-2xl"></i>
            </div>
            <h3 class="font-semibold text-gray-900 mb-2">{{ __('Favoritos') }}</h3>
            <p class="text-sm text-gray-600">{{ __('Seus versículos favoritos') }}</p>
        </a>
        
        <a href="{{ route('member.devotionals.history') }}" 
           class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200 text-center">
            <div class="bg-purple-100 p-3 rounded-lg inline-block mb-4">
                <i class="fas fa-history text-purple-600 text-2xl"></i>
            </div>
            <h3 class="font-semibold text-gray-900 mb-2">{{ __('Histórico') }}</h3>
            <p class="text-sm text-gray-600">{{ __('Seu histórico de leitura') }}</p>
        </a>
    </div>

    <!-- Devocionais Recentes -->
    @if($devocionaisRecentes->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">{{ __('Devocionais Recentes') }}</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($devocionaisRecentes as $devocional)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                        {{ ucfirst($devocional->tipo) }}
                    </span>
                    <span class="text-sm text-gray-500">
                        {{ $devocional->data ? $devocional->data->format('d/m/Y') : 'N/A' }}
                    </span>
                </div>
                
                <h3 class="font-semibold text-gray-900 mb-2">{{ $devocional->titulo }}</h3>
                
                @if($devocional->reflexao)
                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($devocional->reflexao, 100) }}</p>
                @endif
                
                <a href="{{ route('member.devotionals.show', $devocional) }}" 
                   class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    {{ __('Ler mais') }} →
                </a>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-6">
            <a href="{{ route('member.devotionals.search') }}" 
               class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                {{ __('Ver Todos os Devocionais') }}
            </a>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
function marcarComoLido(devocionalId) {
    // Implementar marcação como lido
    showSuccessModal('{{ __("Devocional marcado como lido!") }}');
}

function mostrarDevocionalCompleto() {
    // Mostrar modal com devocional completo
    const devocional = @json($devocionalHoje ?? []);
    
    let modalContent = `
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50" onclick="fecharModal(event)">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-96 overflow-hidden" onclick="event.stopPropagation()">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-900">${devocional.titulo || '{{ __("Devocional do Dia") }}'}</h3>
                    <button onclick="fecharModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto max-h-80">
                    <div class="mb-4">
                        <h4 class="font-semibold text-blue-600 mb-2">{{ __('Versículo') }}</h4>
                        <blockquote class="border-l-4 border-blue-500 pl-4 italic text-gray-700">
                            "${devocional.texto || ''}"
                        </blockquote>
                        <cite class="text-sm text-gray-500 mt-2 block">${devocional.versiculo || ''}</cite>
                    </div>
                    
                    ${devocional.reflexao ? `
                        <div class="mb-4">
                            <h4 class="font-semibold text-green-600 mb-2">{{ __('Reflexão') }}</h4>
                            <p class="text-gray-700 leading-relaxed">${devocional.reflexao}</p>
                        </div>
                    ` : ''}
                </div>
                <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
                    <button onclick="fecharModal()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                        {{ __('Fechar') }}
                    </button>
                    <button onclick="copiarDevocional()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-copy mr-2"></i>{{ __('Copiar') }}
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalContent);
}

function fecharModal(event) {
    if (event && event.target !== event.currentTarget) return;
    const modal = document.querySelector('.fixed.inset-0.bg-gray-600');
    if (modal) {
        modal.remove();
    }
}

function copiarDevocional() {
    const devocional = @json($devocionalHoje ?? []);
    const texto = `${devocional.titulo || '{{ __("Devocional do Dia") }}'}\n\n"${devocional.texto || ''}" - ${devocional.versiculo || ''}\n\n${devocional.reflexao || ''}`;
    
    navigator.clipboard.writeText(texto).then(function() {
        showSuccessModal('{{ __("Devocional copiado!") }}');
    });
}

function adicionarAosFavoritos(tipo, referencia, texto) {
    fetch('{{ route("member.devotionals.add-to-favorites") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
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
            showSuccessModal('{{ __("Adicionado aos favoritos!") }}');
        } else {
            showErrorModal('{{ __("Erro ao adicionar aos favoritos.") }}');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showErrorModal('{{ __("Erro ao adicionar aos favoritos.") }}');
    });
}

function compartilharVersiculo(referencia, texto) {
    const textoCompartilhar = `"${texto}" - ${referencia}`;
    
    if (navigator.share) {
        navigator.share({
            title: '{{ __("Versículo do Dia") }}',
            text: textoCompartilhar
        });
    } else {
        navigator.clipboard.writeText(textoCompartilhar).then(function() {
            showSuccessModal('{{ __("Versículo copiado!") }}');
        });
    }
}

function copiarOracao() {
    const oracao = @json($oracaoDoDia ?? []);
    navigator.clipboard.writeText(oracao.texto || '').then(function() {
        showSuccessModal('{{ __("Oração copiada!") }}');
    });
}

function compartilharOracao() {
    const oracao = @json($oracaoDoDia ?? []);
    
    if (navigator.share) {
        navigator.share({
            title: '{{ __("Oração do Dia") }}',
            text: oracao.texto || ''
        });
    } else {
        navigator.clipboard.writeText(oracao.texto || '').then(function() {
            showSuccessModal('{{ __("Oração copiada!") }}');
        });
    }
}

function showSuccessModal(message) {
    // Implementar modal de sucesso ou usar toast
    alert(message);
}

function showErrorModal(message) {
    // Implementar modal de erro ou usar toast
    alert(message);
}
</script>
@endpush
@endsection 