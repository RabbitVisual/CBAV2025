@extends('layouts.member')

@section('title', 'Busca por Livro e Capítulo - Bíblia Digital')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-book-open text-blue-600 mr-3"></i>
                    Busca por Livro e Capítulo
                </h1>
                <p class="text-gray-600">Selecione o livro e capítulo para ler a passagem completa.</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('member.bible.index') }}" 
                   class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Voltar
                </a>
            </div>
        </div>
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
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Formulário de Busca -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-search text-blue-600 mr-2"></i>
                        Selecionar Passagem
                    </h3>
                    
                    <form action="{{ route('member.bible.search-book') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <!-- Seleção de Livro -->
                        <div>
                            <label for="livro" class="block text-sm font-medium text-gray-700 mb-2">
                                Livro <span class="text-red-500">*</span>
                            </label>
                            <select id="livro" name="livro" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    onchange="carregarCapitulos()">
                                <option value="">Selecione um livro</option>
                                @foreach($livros as $abreviacao => $livro)
                                    <option value="{{ $abreviacao }}" {{ request('livro') == $abreviacao ? 'selected' : '' }}>
                                        {{ $livro['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Seleção de Capítulo -->
                        <div>
                            <label for="capitulo" class="block text-sm font-medium text-gray-700 mb-2">
                                Capítulo <span class="text-red-500">*</span>
                            </label>
                            <select id="capitulo" name="capitulo" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Selecione um capítulo</option>
                            </select>
                        </div>

                        <!-- Seleção de Versículo (Opcional) -->
                        <div>
                            <label for="versiculo" class="block text-sm font-medium text-gray-700 mb-2">
                                Versículo (opcional)
                            </label>
                            <input type="number" id="versiculo" name="versiculo" min="1" 
                                   placeholder="Deixe em branco para ler o capítulo completo"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Deixe em branco para ler o capítulo completo</p>
                        </div>

                        <!-- Versão da Bíblia -->
                        <div>
                            <label for="versao" class="block text-sm font-medium text-gray-700 mb-2">
                                Versão
                            </label>
                            <select id="versao" name="versao" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @foreach($versoesDisponiveis as $abreviacao => $versao)
                                    <option value="{{ $abreviacao }}" {{ $versaoAtual == $abreviacao ? 'selected' : '' }}>
                                        {{ $versao['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Botão de Busca -->
                        <button type="submit" 
                                class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors">
                            <i class="fas fa-search mr-2"></i>Buscar Passagem
                        </button>
                    </form>

                    <!-- Busca Rápida -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Passagens Populares</h4>
                        <div class="space-y-2">
                            <button onclick="buscaRapida('sl', 23)" 
                                    class="w-full text-left p-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md">
                                <div class="font-medium">Salmo 23</div>
                                <div class="text-xs text-gray-500">O Senhor é meu pastor</div>
                            </button>
                            <button onclick="buscaRapida('jo', 3)" 
                                    class="w-full text-left p-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md">
                                <div class="font-medium">João 3</div>
                                <div class="text-xs text-gray-500">Nicodemos e o novo nascimento</div>
                            </button>
                            <button onclick="buscaRapida('mt', 5)" 
                                    class="w-full text-left p-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md">
                                <div class="font-medium">Mateus 5</div>
                                <div class="text-xs text-gray-500">As bem-aventuranças</div>
                            </button>
                            <button onclick="buscaRapida('1co', 13)" 
                                    class="w-full text-left p-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md">
                                <div class="font-medium">1 Coríntios 13</div>
                                <div class="text-xs text-gray-500">O capítulo do amor</div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resultado da Busca -->
            <div class="lg:col-span-2">
                @if(isset($resultado))
                    <div class="bg-white rounded-lg shadow-md">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-800">
                                    <i class="fas fa-bible text-green-600 mr-2"></i>
                                    @if(isset($resultado['verses']))
                                        {{ $resultado['book'] }} {{ $resultado['chapter'] }}
                                    @else
                                        {{ $resultado['reference'] ?? 'Passagem Bíblica' }}
                                    @endif
                                </h3>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-500">
                                        {{ $resultado['version'] ?? 'Almeida Revista e Atualizada' }}
                                    </span>
                                    @if(isset($resultado['total_verses']))
                                        <span class="text-sm text-gray-500">
                                            {{ $resultado['total_verses'] }} versículos
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            @if(isset($resultado['verses']))
                                <!-- Capítulo completo -->
                                <div class="space-y-4">
                                    @foreach($resultado['verses'] as $versiculo)
                                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <span class="font-semibold text-gray-700 mr-2">
                                                        {{ $versiculo['verse'] }}
                                                    </span>
                                                    <span class="text-gray-800 leading-relaxed">
                                                        {{ $versiculo['text'] }}
                                                    </span>
                                                </div>
                                                <div class="ml-4 flex space-x-2">
                                                    <button onclick="favoritarVersiculo('{{ $versiculo['verse'] }}', '{{ $versiculo['text'] }}')" 
                                                            class="text-red-500 hover:text-red-700 text-sm">
                                                        <i class="fas fa-heart"></i>
                                                    </button>
                                                    <button onclick="compartilharVersiculo('{{ $versiculo['verse'] }}', '{{ $versiculo['text'] }}')" 
                                                            class="text-blue-500 hover:text-blue-700 text-sm">
                                                        <i class="fas fa-share"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <div class="flex justify-between items-center pt-4 border-t border-gray-200 mt-6">
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
                            @elseif(isset($resultado['text']))
                                <!-- Versículo específico -->
                                <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg p-6">
                                    <blockquote class="text-lg text-gray-800 italic mb-6 leading-relaxed">
                                        "{{ $resultado['text'] }}"
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
                            @endif
                        </div>
                    </div>
                @else
                    <!-- Instruções -->
                    <div class="bg-white rounded-lg shadow-md p-8 text-center">
                        <i class="fas fa-book-open text-gray-400 text-6xl mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Selecione uma Passagem</h3>
                        <p class="text-gray-600 mb-6">
                            Escolha o livro e capítulo que deseja ler. Você também pode selecionar um versículo específico.
                        </p>
                        <div class="bg-blue-50 p-4 rounded-lg text-left max-w-md mx-auto">
                            <h4 class="font-medium text-blue-900 mb-2">Como usar:</h4>
                            <ul class="text-sm text-blue-800 space-y-1">
                                <li>• Selecione o <strong>livro</strong> desejado</li>
                                <li>• Escolha o <strong>capítulo</strong> para ler completo</li>
                                <li>• Opcional: digite o <strong>versículo</strong> específico</li>
                                <li>• Clique em "Buscar Passagem"</li>
                            </ul>
                        </div>
                    </div>
                @endif
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
// Dados dos livros para carregar capítulos
const livros = @json($livros);

function carregarCapitulos() {
    const livroSelect = document.getElementById('livro');
    const capituloSelect = document.getElementById('capitulo');
    const livroSelecionado = livroSelect.value;
    
    // Limpar opções de capítulo
    capituloSelect.innerHTML = '<option value="">Selecione um capítulo</option>';
    
    if (livroSelecionado && livros[livroSelecionado]) {
        const totalCapitulos = livros[livroSelecionado].chapters;
        
        for (let i = 1; i <= totalCapitulos; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = `Capítulo ${i}`;
            capituloSelect.appendChild(option);
        }
    }
}

function buscaRapida(livro, capitulo) {
    document.getElementById('livro').value = livro;
    carregarCapitulos();
    document.getElementById('capitulo').value = capitulo;
    document.querySelector('form').submit();
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

// Carregar capítulos se já há um livro selecionado
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('livro').value) {
        carregarCapitulos();
    }
});
</script>
@endsection 