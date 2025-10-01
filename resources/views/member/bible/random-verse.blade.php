@extends('layouts.member')

@section('title', 'Versículo Aleatório - Bíblia')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('member.bible.index') }}" class="text-blue-600 hover:text-blue-800 mr-2">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-dice text-blue-600 mr-3"></i>
                Versículo Aleatório
            </h1>
        </div>
        <p class="text-gray-600">Descubra versículos inspiradores da Palavra de Deus.</p>
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
        <!-- Versículo Aleatório -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-6">
            <div class="text-center">
                <div class="mb-6">
                    <i class="fas fa-quote-left text-blue-600 text-4xl mb-4"></i>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $verse['reference'] }}</h2>
                    <p class="text-lg text-gray-700 leading-relaxed">{{ $verse['text'] }}</p>
                </div>
                
                <div class="flex justify-center space-x-4 mb-6">
                    <button onclick="window.location.reload()" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-dice mr-2"></i>Novo Versículo
                    </button>
                    <button onclick="addToFavorites()" 
                            class="px-6 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                        <i class="fas fa-star mr-2"></i>Favoritar
                    </button>
                    <button onclick="shareVerse()" 
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        <i class="fas fa-share mr-2"></i>Compartilhar
                    </button>
                </div>
            </div>
        </div>

        <!-- Informações do Versículo -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Informações
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Versão:</span>
                        <span class="font-medium">{{ $bibleStatus['version'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Livro:</span>
                        <span class="font-medium">{{ $verse['book'] ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Capítulo:</span>
                        <span class="font-medium">{{ $verse['chapter'] ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Versículo:</span>
                        <span class="font-medium">{{ $verse['verse'] ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-cog text-blue-600 mr-2"></i>
                    Configurações
                </h3>
                <div class="space-y-4">
                    <div>
                        <label for="version" class="block text-sm font-medium text-gray-700 mb-2">
                            Versão da Bíblia
                        </label>
                        <select id="version" onchange="changeVersion()" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @foreach($versions as $version)
                                <option value="{{ $version['code'] }}" 
                                        {{ $version['code'] === $currentVersion ? 'selected' : '' }}>
                                    {{ $version['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <button onclick="window.location.reload()" 
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-sync-alt mr-2"></i>Atualizar
                    </button>
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
function addToFavorites() {
    const verseData = {
        reference: '{{ $verse['reference'] ?? '' }}',
        text: '{{ $verse['text'] ?? '' }}',
        version: '{{ $currentVersion ?? $bibleStatus['version'] ?? 'almeida_ra' }}'
    };
    
    fetch('{{ route("member.bible.favorites.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(verseData)
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                console.error('Response text:', text);
                throw new Error('Erro na requisição: ' + response.status);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showSuccessModal('Versículo adicionado aos favoritos!');
        } else {
            showErrorModal('Erro ao adicionar aos favoritos: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showErrorModal('Erro ao adicionar aos favoritos: ' + error.message);
    });
}

function shareVerse() {
    const text = `"{{ $verse['text'] ?? '' }}" - {{ $verse['reference'] ?? '' }}`;
    
    if (navigator.share) {
        navigator.share({
            title: 'Versículo da Bíblia',
            text: text
        });
    } else {
        // Fallback para copiar para clipboard
        navigator.clipboard.writeText(text).then(() => {
            showSuccessModal('Versículo copiado para a área de transferência!');
        }).catch(() => {
            showErrorModal('Erro ao copiar versículo para a área de transferência');
        });
    }
}

function changeVersion() {
    const version = document.getElementById('version').value;
    console.log('Alterando versão para:', version);
    console.log('CSRF Token:', '{{ csrf_token() }}');
    
    fetch('{{ route("member.bible.change-version") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ versao: version })
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (!response.ok) {
            return response.text().then(text => {
                console.error('Response text:', text);
                throw new Error('Erro na requisição: ' + response.status + ' - ' + text);
            });
        }
        
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            return response.text().then(text => {
                console.error('Unexpected response type:', contentType);
                console.error('Response text:', text);
                throw new Error('Resposta inesperada do servidor');
            });
        }
    })
    .then(data => {
        if (data.success) {
            showSuccessModal('Versão alterada com sucesso!');
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showErrorModal('Erro ao alterar versão: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showErrorModal('Erro ao alterar versão: ' + error.message);
    });
}
</script>
@endsection 