@extends('layouts.member')

@section('title', 'Resultado da Busca - Bíblia Digital')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-search text-blue-600 mr-3"></i>
                    Resultado da Busca
                </h1>
                <p class="text-gray-600">
                    @if(isset($livro) && isset($capitulo))
                        {{ $livro }} {{ $capitulo }}
                        @if(isset($versiculo))
                            :{{ $versiculo }}
                        @endif
                    @elseif(isset($referencia))
                        {{ $referencia }}
                    @endif
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('member.bible.index') }}" 
                   class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Voltar
                </a>
                @if(isset($resultado))
                    <button onclick="adicionarAosFavoritos()" 
                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-heart mr-2"></i>Favoritar
                    </button>
                @endif
            </div>
        </div>
    </div>

    @if(isset($resultado))
        <!-- Resultado da Busca -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-bible text-green-600 mr-2"></i>
                    Texto Bíblico
                </h3>
            </div>
            <div class="p-6">
                @if(isset($resultado['verses']))
                    <!-- Capítulo completo -->
                    <div class="space-y-6">
                        <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-lg p-4">
                            <h4 class="text-xl font-semibold text-gray-900 mb-2">
                                {{ $resultado['book'] ?? 'Livro' }} {{ $resultado['chapter'] ?? 'Capítulo' }}
                            </h4>
                            <p class="text-sm text-gray-600">
                                Versão: {{ $resultado['version'] ?? 'Almeida Revista e Atualizada' }}
                                <span class="ml-4">Total de versículos: {{ $resultado['total_verses'] ?? 0 }}</span>
                            </p>
                        </div>
                        
                        <div class="space-y-4">
                            @foreach($resultado['verses'] as $versiculo)
                                <div class="border-l-4 border-blue-500 pl-4 py-2">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <span class="font-semibold text-gray-700 mr-2">
                                                {{ $versiculo['verse'] ?? '' }}
                                            </span>
                                            <span class="text-gray-800 leading-relaxed">
                                                {{ $versiculo['text'] ?? '' }}
                                            </span>
                                        </div>
                                        <div class="ml-4 flex space-x-2">
                                            <button onclick="favoritarVersiculo('{{ $versiculo['verse'] ?? '' }}', '{{ $versiculo['text'] ?? '' }}')" 
                                                    class="text-red-500 hover:text-red-700 text-sm">
                                                <i class="fas fa-heart"></i>
                                            </button>
                                            <button onclick="compartilharVersiculo('{{ $versiculo['verse'] ?? '' }}', '{{ $versiculo['text'] ?? '' }}')" 
                                                    class="text-blue-500 hover:text-blue-700 text-sm">
                                                <i class="fas fa-share"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                            <div class="flex space-x-3">
                                <button onclick="copiarCapitulo()" 
                                        class="text-green-600 hover:text-green-800 text-sm font-medium">
                                    <i class="fas fa-copy mr-1"></i>Copiar Capítulo
                                </button>
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ now()->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                @elseif(isset($resultado['text']))
                    <!-- Versículo específico -->
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg p-6">
                        <div class="mb-4">
                            <h4 class="text-xl font-semibold text-gray-900 mb-2">
                                {{ $resultado['reference'] ?? 'Referência não disponível' }}
                            </h4>
                            <p class="text-sm text-gray-600">
                                Versão: {{ $resultado['version'] ?? 'Almeida Revista e Atualizada' }}
                            </p>
                        </div>
                        
                        <blockquote class="text-lg text-gray-800 italic mb-6 leading-relaxed">
                            "{{ $resultado['text'] ?? 'Texto não disponível' }}"
                        </blockquote>
                        
                        <div class="flex justify-between items-center">
                            <div class="flex space-x-3">
                                <button onclick="compartilharVersiculo()" 
                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    <i class="fas fa-share mr-1"></i>Compartilhar
                                </button>
                                <button onclick="copiarTexto()" 
                                        class="text-green-600 hover:text-green-800 text-sm font-medium">
                                    <i class="fas fa-copy mr-1"></i>Copiar
                                </button>
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ now()->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Resultado genérico -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mr-3 mt-1"></i>
                            <div>
                                <h4 class="text-lg font-medium text-yellow-800">Resultado não encontrado</h4>
                                <p class="text-yellow-700 mt-1">
                                    Não foi possível encontrar o texto solicitado. Verifique a referência e tente novamente.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @else
        <!-- Nenhum resultado -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                    Nenhum Resultado
                </h3>
            </div>
            <div class="p-6">
                <div class="text-center py-8">
                    <i class="fas fa-search text-gray-400 text-4xl mb-4"></i>
                    <h4 class="text-lg font-medium text-gray-900 mb-2">Nenhum resultado encontrado</h4>
                    <p class="text-gray-600 mb-4">
                        Não foi possível encontrar o texto solicitado. Verifique a referência e tente novamente.
                    </p>
                    <a href="{{ route('member.bible.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-arrow-left mr-2"></i>Voltar à Busca
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
function adicionarAosFavoritos() {
    const referencia = '{{ $resultado["reference"] ?? "" }}';
    const texto = '{{ $resultado["text"] ?? "" }}';
    const versao = '{{ $resultado["version"] ?? "" }}';
    
    fetch('{{ route("member.bible.favorites.add") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            referencia: referencia,
            texto: texto,
            versao: versao
        })
    }).then(response => {
        if (response.ok) {
            showSuccessModal('Versículo adicionado aos favoritos!');
        } else {
            showErrorModal('Erro ao adicionar aos favoritos.');
        }
    });
}

function favoritarVersiculo(numero, texto) {
    const referencia = '{{ $resultado["book"] ?? "" }} {{ $resultado["chapter"] ?? "" }}:' + numero;
    const versao = '{{ $resultado["version"] ?? "" }}';
    
    fetch('{{ route("member.bible.favorites.add") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            referencia: referencia,
            texto: texto,
            versao: versao
        })
    }).then(response => {
        if (response.ok) {
            showSuccessModal('Versículo adicionado aos favoritos!');
        } else {
            showErrorModal('Erro ao adicionar aos favoritos.');
        }
    });
}

function compartilharVersiculo(numero = '', texto = '') {
    const referencia = numero ? '{{ $resultado["book"] ?? "" }} {{ $resultado["chapter"] ?? "" }}:' + numero : '{{ $resultado["reference"] ?? "" }}';
    const textoCompartilhar = texto || '{{ $resultado["text"] ?? "" }}';
    const versao = '{{ $resultado["version"] ?? "" }}';
    
    fetch('{{ route("member.bible.share") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            referencia: referencia,
            texto: textoCompartilhar,
            versao: versao
        })
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            // Copiar link para clipboard
            navigator.clipboard.writeText(data.link).then(() => {
                showSuccessModal('Link copiado para a área de transferência!');
            }).catch(() => {
                showErrorModal('Erro ao copiar link para a área de transferência');
            });
        } else {
            showErrorModal('Erro ao gerar link de compartilhamento.');
        }
    });
}

function copiarTexto() {
    const texto = '{{ $resultado["text"] ?? "" }}';
    const referencia = '{{ $resultado["reference"] ?? "" }}';
    const textoCompleto = `${referencia}\n\n"${texto}"`;
    
    navigator.clipboard.writeText(textoCompleto).then(() => {
        showSuccessModal('Texto copiado para a área de transferência!');
    }).catch(() => {
        showErrorModal('Erro ao copiar texto para a área de transferência');
    });
}

function copiarCapitulo() {
    const livro = '{{ $resultado["book"] ?? "" }}';
    const capitulo = '{{ $resultado["chapter"] ?? "" }}';
    const versiculos = @json($resultado['verses'] ?? []);
    
    let textoCompleto = `${livro} ${capitulo}\n\n`;
    versiculos.forEach(versiculo => {
        textoCompleto += `${versiculo.verse} ${versiculo.text}\n\n`;
    });
    
    navigator.clipboard.writeText(textoCompleto).then(() => {
        showSuccessModal('Capítulo copiado para a área de transferência!');
    }).catch(() => {
        showErrorModal('Erro ao copiar capítulo para a área de transferência');
    });
}
</script>
@endsection 