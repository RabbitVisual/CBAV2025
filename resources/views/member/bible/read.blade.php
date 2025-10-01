@extends('layouts.member')

@section('title', __('Leitura Bíblica'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Cabeçalho -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ __('Leitura Bíblica') }}</h1>
                    <p class="text-gray-600 mt-2">{{ __('Leia e estude a Bíblia com ferramentas interativas') }}</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('member.bible.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>{{ __('Voltar') }}
                    </a>
                    <button onclick="toggleModoLeitura()" class="btn btn-primary">
                        <i class="fas fa-eye mr-2"></i>{{ __('Modo Leitura') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Alertas -->
        @if(session('success'))
            <div class="alert alert-success mb-6">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar de Navegação -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Navegação') }}</h2>

                    <!-- Seleção de Livro -->
                    <div class="mb-6">
                        <label for="livro" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Livro') }}
                        </label>
                        <select id="livro" class="form-select w-full" onchange="carregarCapitulos()">
                            <option value="">{{ __('Selecione um livro') }}</option>
                            @foreach($livros as $livro)
                                <option value="{{ $livro['abbrev'] }}" {{ request('livro') == $livro['abbrev'] ? 'selected' : '' }}>
                                    {{ $livro['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Seleção de Capítulo -->
                    <div class="mb-6">
                        <label for="capitulo" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Capítulo') }}
                        </label>
                        <select id="capitulo" class="form-select w-full" onchange="carregarCapitulo()">
                            <option value="">{{ __('Selecione um capítulo') }}</option>
                        </select>
                    </div>

                    <!-- Versão -->
                    <div class="mb-6">
                        <label for="versao" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Versão') }}
                        </label>
                        <select id="versao" class="form-select w-full" onchange="trocarVersao()">
                            @foreach($versoes as $key => $versao)
                                <option value="{{ $key }}" {{ session('bible_version', 'almeida_ra') == $key ? 'selected' : '' }}>
                                    {{ $versao['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Navegação Rápida -->
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-900 mb-3">{{ __('Navegação Rápida') }}</h3>
                        <div class="space-y-2">
                            <button onclick="navegarPara('salmo', 23)" class="w-full text-left p-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md">
                                <div class="font-medium">Salmo 23</div>
                                <div class="text-xs text-gray-500">O Senhor é meu pastor</div>
                            </button>
                            <button onclick="navegarPara('joao', 3)" class="w-full text-left p-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md">
                                <div class="font-medium">João 3</div>
                                <div class="text-xs text-gray-500">Nicodemos e o novo nascimento</div>
                            </button>
                            <button onclick="navegarPara('mateus', 5)" class="w-full text-left p-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md">
                                <div class="font-medium">Mateus 5</div>
                                <div class="text-xs text-gray-500">As bem-aventuranças</div>
                            </button>
                            <button onclick="navegarPara('1corintios', 13)" class="w-full text-left p-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md">
                                <div class="font-medium">1 Coríntios 13</div>
                                <div class="text-xs text-gray-500">O capítulo do amor</div>
                            </button>
                        </div>
                    </div>

                    <!-- Histórico de Leitura -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-3">{{ __('Histórico Recente') }}</h3>
                        <div class="space-y-2">
                            @foreach($historicoLeitura as $leitura)
                                <button onclick="navegarPara('{{ $leitura->livro }}', {{ $leitura->capitulo }})" 
                                        class="w-full text-left p-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md">
                                    <div class="font-medium">{{ $leitura->livro_nome }} {{ $leitura->capitulo }}</div>
                                    <div class="text-xs text-gray-500">{{ $leitura->updated_at->diffForHumans() }}</div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Área de Leitura -->
            <div class="lg:col-span-3">
                @if(isset($capitulo))
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <!-- Cabeçalho do Capítulo -->
                        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">{{ $capitulo['livro'] }} {{ $capitulo['capitulo'] }}</h2>
                                <p class="text-gray-600">{{ $capitulo['versao'] }}</p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center space-x-2">
                                    <button onclick="capituloAnterior()" class="btn btn-sm btn-outline" {{ $capitulo['capitulo'] <= 1 ? 'disabled' : '' }}>
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <span class="text-sm font-medium">{{ $capitulo['capitulo'] }}</span>
                                    <button onclick="proximoCapitulo()" class="btn btn-sm btn-outline">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button onclick="adicionarFavorito()" class="btn btn-sm btn-outline">
                                        <i class="fas fa-star mr-1"></i>{{ __('Favorito') }}
                                    </button>
                                    <button onclick="compartilharCapitulo()" class="btn btn-sm btn-outline">
                                        <i class="fas fa-share mr-1"></i>{{ __('Compartilhar') }}
                                    </button>
                                    <button onclick="imprimirCapitulo()" class="btn btn-sm btn-outline">
                                        <i class="fas fa-print mr-1"></i>{{ __('Imprimir') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Versículos -->
                        <div id="conteudo-capitulo" class="prose max-w-none">
                            @foreach($capitulo['versiculos'] as $versiculo)
                                <div class="versiculo mb-4 p-4 border-l-4 border-blue-500 bg-blue-50 rounded-r-lg">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2 mb-2">
                                                <span class="font-bold text-blue-600">{{ $versiculo['numero'] }}</span>
                                                <button onclick="marcarVersiculo({{ $versiculo['numero'] }})" 
                                                        class="text-gray-400 hover:text-yellow-500" 
                                                        title="{{ __('Marcar versículo') }}">
                                                    <i class="fas fa-bookmark"></i>
                                                </button>
                                                <button onclick="copiarVersiculo({{ $versiculo['numero'] }}, '{{ $versiculo['texto'] }}')" 
                                                        class="text-gray-400 hover:text-blue-500" 
                                                        title="{{ __('Copiar versículo') }}">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                            <p class="text-gray-700 leading-relaxed">{{ $versiculo['texto'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Navegação Inferior -->
                        <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                            <div class="flex items-center space-x-4">
                                @if($capitulo['capitulo'] > 1)
                                    <a href="{{ route('member.bible.chapter', ['livro' => $capitulo['livro_abbrev'], 'capitulo' => $capitulo['capitulo'] - 1]) }}" 
                                       class="btn btn-outline">
                                        <i class="fas fa-chevron-left mr-2"></i>{{ __('Capítulo Anterior') }}
                                    </a>
                                @endif
                                
                                @if(isset($proximoCapitulo))
                                    <a href="{{ route('member.bible.chapter', ['livro' => $capitulo['livro_abbrev'], 'capitulo' => $capitulo['capitulo'] + 1]) }}" 
                                       class="btn btn-outline">
                                        {{ __('Próximo Capítulo') }}<i class="fas fa-chevron-right ml-2"></i>
                                    </a>
                                @endif
                            </div>

                            <div class="flex items-center space-x-4">
                                <button onclick="salvarProgresso()" class="btn btn-primary">
                                    <i class="fas fa-save mr-2"></i>{{ __('Salvar Progresso') }}
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Instruções de Leitura -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="text-center py-12">
                            <div class="text-gray-400 mb-4">
                                <i class="fas fa-book-open text-6xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Selecione um livro e capítulo') }}</h3>
                            <p class="text-gray-500 mb-6">{{ __('Use o painel lateral para navegar pela Bíblia e começar sua leitura.') }}</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-2xl mx-auto">
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-blue-900 mb-2">{{ __('Dicas de Leitura:') }}</h4>
                                    <ul class="text-sm text-blue-800 space-y-1 text-left">
                                        <li>• Leia um capítulo por dia</li>
                                        <li>• Use o modo leitura para foco</li>
                                        <li>• Marque versículos importantes</li>
                                        <li>• Salve seu progresso</li>
                                    </ul>
                                </div>
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-green-900 mb-2">{{ __('Recursos:') }}</h4>
                                    <ul class="text-sm text-green-800 space-y-1 text-left">
                                        <li>• Múltiplas versões</li>
                                        <li>• Histórico de leitura</li>
                                        <li>• Compartilhamento</li>
                                        <li>• Favoritos</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let modoLeitura = false;
let versiculosMarcados = [];

function carregarCapitulos() {
    const livro = document.getElementById('livro').value;
    const capituloSelect = document.getElementById('capitulo');
    
    if (!livro) {
        capituloSelect.innerHTML = '<option value="">{{ __("Selecione um capítulo") }}</option>';
        return;
    }

    fetch(`/member/bible/chapters/${livro}`)
        .then(response => response.json())
        .then(data => {
            capituloSelect.innerHTML = '<option value="">{{ __("Selecione um capítulo") }}</option>';
            data.chapters.forEach(cap => {
                const option = document.createElement('option');
                option.value = cap;
                option.textContent = cap;
                capituloSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Erro ao carregar capítulos:', error);
        });
}

function carregarCapitulo() {
    const livro = document.getElementById('livro').value;
    const capitulo = document.getElementById('capitulo').value;
    
    if (livro && capitulo) {
        window.location.href = `/member/bible/chapter?livro=${livro}&capitulo=${capitulo}`;
    }
}

function trocarVersao() {
    const versao = document.getElementById('versao').value;
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('versao', versao);
    window.location.href = `${window.location.pathname}?${urlParams.toString()}`;
}

function navegarPara(livro, capitulo) {
    document.getElementById('livro').value = livro;
    carregarCapitulos();
    
    setTimeout(() => {
        document.getElementById('capitulo').value = capitulo;
        carregarCapitulo();
    }, 100);
}

function capituloAnterior() {
    const urlParams = new URLSearchParams(window.location.search);
    const livro = urlParams.get('livro');
    const capitulo = parseInt(urlParams.get('capitulo'));
    
    if (capitulo > 1) {
        window.location.href = `/member/bible/chapter?livro=${livro}&capitulo=${capitulo - 1}`;
    }
}

function proximoCapitulo() {
    const urlParams = new URLSearchParams(window.location.search);
    const livro = urlParams.get('livro');
    const capitulo = parseInt(urlParams.get('capitulo'));
    
    window.location.href = `/member/bible/chapter?livro=${livro}&capitulo=${capitulo + 1}`;
}

function toggleModoLeitura() {
    modoLeitura = !modoLeitura;
    const conteudo = document.getElementById('conteudo-capitulo');
    const btn = event.target;
    
    if (modoLeitura) {
        conteudo.classList.add('modo-leitura');
        btn.innerHTML = '<i class="fas fa-times mr-2"></i>{{ __("Sair do Modo Leitura") }}';
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-secondary');
    } else {
        conteudo.classList.remove('modo-leitura');
        btn.innerHTML = '<i class="fas fa-eye mr-2"></i>{{ __("Modo Leitura") }}';
        btn.classList.remove('btn-secondary');
        btn.classList.add('btn-primary');
    }
}

function marcarVersiculo(numero) {
    const index = versiculosMarcados.indexOf(numero);
    if (index > -1) {
        versiculosMarcados.splice(index, 1);
        event.target.classList.remove('text-yellow-500');
        event.target.classList.add('text-gray-400');
    } else {
        versiculosMarcados.push(numero);
        event.target.classList.remove('text-gray-400');
        event.target.classList.add('text-yellow-500');
    }
}

function copiarVersiculo(numero, texto) {
    const conteudo = `${numero}. ${texto}`;
    navigator.clipboard.writeText(conteudo).then(() => {
        showNotification('{{ __("Versículo copiado!") }}', 'success');
    });
}

function adicionarFavorito() {
    const urlParams = new URLSearchParams(window.location.search);
    const livro = urlParams.get('livro');
    const capitulo = urlParams.get('capitulo');
    const referencia = `${livro} ${capitulo}`;
    
    fetch('{{ route("member.bible.favorites.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({
            reference: referencia,
            text: '{{ __("Capítulo completo") }}'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('{{ __("Capítulo adicionado aos favoritos!") }}', 'success');
        } else {
            showNotification(data.message || '{{ __("Erro ao adicionar aos favoritos") }}', 'error');
        }
    });
}

function compartilharCapitulo() {
    const urlParams = new URLSearchParams(window.location.search);
    const livro = urlParams.get('livro');
    const capitulo = urlParams.get('capitulo');
    const referencia = `${livro} ${capitulo}`;
    
    const conteudo = `${referencia}\n\nCompartilhado via {{ config('app.name') }}\n${window.location.href}`;
    
    if (navigator.share) {
        navigator.share({
            title: referencia,
            text: conteudo,
            url: window.location.href
        });
    } else {
        navigator.clipboard.writeText(conteudo).then(() => {
            showNotification('{{ __("Link copiado para compartilhamento!") }}', 'success');
        });
    }
}

function imprimirCapitulo() {
    window.print();
}

function salvarProgresso() {
    const urlParams = new URLSearchParams(window.location.search);
    const livro = urlParams.get('livro');
    const capitulo = urlParams.get('capitulo');
    
    fetch('{{ route("member.bible.history.save") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({
            livro: livro,
            capitulo: capitulo,
            versiculos_marcados: versiculosMarcados
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('{{ __("Progresso salvo com sucesso!") }}', 'success');
        } else {
            showNotification(data.message || '{{ __("Erro ao salvar progresso") }}', 'error');
        }
    });
}

function showNotification(message, type) {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} fixed top-4 right-4 z-50`;
    alert.innerHTML = `<i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle mr-2"></i>${message}`;
    document.body.appendChild(alert);
    
    setTimeout(() => {
        alert.remove();
    }, 3000);
}

// Carregar capítulos se livro já estiver selecionado
document.addEventListener('DOMContentLoaded', function() {
    const livro = document.getElementById('livro').value;
    if (livro) {
        carregarCapitulos();
    }
});
</script>

<style>
.modo-leitura {
    font-size: 1.2em;
    line-height: 1.8;
    max-width: 800px;
    margin: 0 auto;
}

.modo-leitura .versiculo {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: #f8fafc;
    border-left: 4px solid #3b82f6;
}

@media print {
    .sidebar, .header, .footer, .btn {
        display: none !important;
    }
    
    .container {
        max-width: none !important;
        padding: 0 !important;
    }
    
    .prose {
        font-size: 12pt;
        line-height: 1.6;
    }
}
</style>
@endpush
@endsection 