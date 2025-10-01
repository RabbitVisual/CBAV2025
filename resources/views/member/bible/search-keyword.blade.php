@extends('layouts.member')

@section('title', 'Busca por Palavra-chave - Bíblia')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('member.bible.index') }}" class="text-blue-600 hover:text-blue-800 mr-2">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-search text-blue-600 mr-3"></i>
                Busca por Palavra-chave
            </h1>
        </div>
        <p class="text-gray-600">Encontre versículos que contenham palavras específicas.</p>
    </div>

    <!-- Status da Bíblia -->
    <div class="mb-6">
        @if($bibleStatus['available'])
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-600 mr-3"></i>
                    <div>
                        <h3 class="font-medium text-green-900">Bíblia Disponível</h3>
                        <p class="text-sm text-green-700">{{ $bibleStatus['version'] }} - {{ $bibleStatus['books'] }} livros</p>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-red-600 mr-3"></i>
                    <div>
                        <h3 class="font-medium text-red-900">Bíblia Indisponível</h3>
                        <p class="text-sm text-red-700">Os dados da Bíblia não estão disponíveis offline.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if($bibleStatus['available'])
        <!-- Formulário de Busca -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="{{ route('member.bible.search-keyword') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="keyword" class="block text-sm font-medium text-gray-700 mb-2">
                            Palavra-chave <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="keyword" 
                               name="keyword" 
                               value="{{ request('keyword') }}"
                               placeholder="Ex: amor, fé, esperança..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    
                    <div>
                        <label for="version" class="block text-sm font-medium text-gray-700 mb-2">
                            Versão
                        </label>
                        <select id="version" name="version" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @foreach($versions as $version)
                                <option value="{{ $version['code'] }}" 
                                        {{ $version['code'] === ($currentVersion ?? 'almeida_ra') ? 'selected' : '' }}>
                                    {{ $version['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="limit" class="block text-sm font-medium text-gray-700 mb-2">
                            Limite de resultados
                        </label>
                        <select id="limit" name="limit" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="10" {{ request('limit') == '10' ? 'selected' : '' }}>10 resultados</option>
                            <option value="20" {{ request('limit') == '20' ? 'selected' : '' }}>20 resultados</option>
                            <option value="50" {{ request('limit') == '50' ? 'selected' : '' }}>50 resultados</option>
                            <option value="100" {{ request('limit') == '100' ? 'selected' : '' }}>100 resultados</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-center">
                    <button type="submit" 
                            class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-search mr-2"></i>Buscar
                    </button>
                </div>
            </form>
        </div>

        <!-- Resultados da Busca -->
        @if(isset($results) && is_array($results) && count($results) > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">
                        <i class="fas fa-list text-blue-600 mr-2"></i>
                        Resultados da Busca
                    </h2>
                    <span class="text-sm text-gray-600">{{ count($results) }} resultado(s) encontrado(s)</span>
                </div>
                
                <div class="space-y-4">
                    @foreach($results as $result)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-blue-600 mb-2">{{ $result['reference'] }}</h3>
                                    <p class="text-gray-700 leading-relaxed mb-3">{{ $result['text'] }}</p>
                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                        <span><i class="fas fa-book mr-1"></i>{{ $result['book'] ?? 'N/A' }}</span>
                                        <span><i class="fas fa-chapter mr-1"></i>{{ $result['chapter'] ?? 'N/A' }}</span>
                                        <span><i class="fas fa-verse mr-1"></i>{{ $result['verse'] ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2 ml-4">
                                    <button onclick="addToFavorites('{{ addslashes($result['reference']) }}', '{{ addslashes($result['text']) }}')" 
                                            class="text-yellow-600 hover:text-yellow-800" 
                                            title="Adicionar aos favoritos">
                                        <i class="fas fa-star"></i>
                                    </button>
                                    <button onclick="shareVerse('{{ addslashes($result['text']) }}', '{{ addslashes($result['reference']) }}')" 
                                            class="text-green-600 hover:text-green-800" 
                                            title="Compartilhar">
                                        <i class="fas fa-share"></i>
                                    </button>
                                    <a href="{{ route('member.bible.read', [
                                        'reference' => $result['reference'],
                                        'versao' => $currentVersion ?? 'almeida_ra'
                                    ]) }}" 
                                       class="text-blue-600 hover:text-blue-800" 
                                       title="Ler capítulo completo">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @elseif(request('keyword'))
            <!-- Nenhum resultado -->
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <i class="fas fa-search text-gray-400 text-6xl mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Nenhum resultado encontrado</h2>
                <p class="text-gray-600 mb-6">
                    Não foram encontrados versículos contendo a palavra "{{ request('keyword') }}".
                    Tente usar sinônimos ou palavras relacionadas.
                </p>
                <div class="space-y-2">
                    <p class="text-sm text-gray-500">Sugestões:</p>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Verifique a ortografia da palavra</li>
                        <li>• Tente usar palavras mais simples</li>
                        <li>• Use sinônimos da palavra desejada</li>
                        <li>• Tente buscar por partes da palavra</li>
                    </ul>
                </div>
            </div>
        @endif

        <!-- Dicas de Busca -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-4">
                <i class="fas fa-lightbulb text-blue-600 mr-2"></i>
                Dicas para uma busca eficiente
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-800">
                <div>
                    <h4 class="font-medium mb-2">Palavras-chave populares:</h4>
                    <ul class="space-y-1">
                        <li>• amor, caridade, bondade</li>
                        <li>• fé, esperança, confiança</li>
                        <li>• paz, alegria, gratidão</li>
                        <li>• perdão, misericórdia, graça</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-medium mb-2">Como buscar:</h4>
                    <ul class="space-y-1">
                        <li>• Use palavras simples e diretas</li>
                        <li>• Evite artigos e preposições</li>
                        <li>• Tente sinônimos se não encontrar</li>
                        <li>• Use palavras em português</li>
                    </ul>
                </div>
            </div>
        </div>
    @else
        <!-- Mensagem de erro -->
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-exclamation-triangle text-red-500 text-6xl mb-4"></i>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Bíblia Indisponível</h2>
            <p class="text-gray-600 mb-6">
                Os dados da Bíblia não estão disponíveis offline. 
                Entre em contato com o administrador para configurar a Bíblia.
            </p>
            <a href="{{ route('member.bible.index') }}" 
               class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    @endif
</div>

<script>
function addToFavorites(reference, text) {
    const verseData = {
        reference: reference,
        text: text,
        version: '{{ $currentVersion ?? "almeida_ra" }}'
    };
    
    fetch('{{ route("member.bible.favorites.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(verseData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Versículo adicionado aos favoritos!');
        } else {
            alert('Erro ao adicionar aos favoritos: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao adicionar aos favoritos');
    });
}

function shareVerse(text, reference) {
    const shareText = `"${text}" - ${reference}`;
    
    if (navigator.share) {
        navigator.share({
            title: 'Versículo da Bíblia',
            text: shareText
        });
    } else {
        // Fallback para copiar para clipboard
        navigator.clipboard.writeText(shareText).then(() => {
            alert('Versículo copiado para a área de transferência!');
        });
    }
}
</script>
@endsection 