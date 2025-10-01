@extends('layouts.member')

@section('title', __('Busca Bíblica'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Cabeçalho -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ __('Busca Bíblica') }}</h1>
                    <p class="text-gray-600 mt-2">{{ __('Encontre versículos e passagens específicas na Bíblia') }}</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('member.bible.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>{{ __('Voltar') }}
                    </a>
                    <a href="{{ route('member.bible.random-verse') }}" class="btn btn-primary">
                        <i class="fas fa-random mr-2"></i>{{ __('Versículo Aleatório') }}
                    </a>
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
            <!-- Sidebar de Busca -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Opções de Busca') }}</h2>

                    <!-- Abas de Busca -->
                    <div class="mb-6">
                        <div class="flex space-x-1 bg-gray-100 rounded-lg p-1">
                            <button id="tab-reference" 
                                    class="flex-1 py-2 px-3 text-sm font-medium rounded-md bg-white text-gray-900 shadow-sm"
                                    onclick="trocarAba('reference')">
                                {{ __('Referência') }}
                            </button>
                            <button id="tab-keyword" 
                                    class="flex-1 py-2 px-3 text-sm font-medium rounded-md text-gray-600 hover:text-gray-900"
                                    onclick="trocarAba('keyword')">
                                {{ __('Palavra-chave') }}
                            </button>
                        </div>
                    </div>

                    <!-- Busca por Referência -->
                    <div id="search-reference" class="search-tab">
                        <form method="POST" action="{{ route('member.bible.search') }}" class="space-y-4">
                            @csrf
                            <div>
                                <label for="reference" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('Referência Bíblica') }}
                                </label>
                                <input type="text" 
                                       id="reference" 
                                       name="reference" 
                                       value="{{ request('reference') }}"
                                       placeholder="{{ __('Ex: João 3:16, Salmos 23, Mateus 5:1-12') }}"
                                       class="form-input w-full">
                                <p class="text-xs text-gray-500 mt-1">{{ __('Digite a referência no formato: Livro Capítulo:Versículo') }}</p>
                            </div>

                            <div>
                                <label for="version" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('Versão') }}
                                </label>
                                <select id="version" name="version" class="form-select w-full">
                                    @foreach($versoes as $key => $versao)
                                        <option value="{{ $key }}" {{ request('version', session('bible_version', 'almeida_ra')) == $key ? 'selected' : '' }}>
                                            {{ $versao['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-full">
                                <i class="fas fa-search mr-2"></i>{{ __('Buscar') }}
                            </button>
                        </form>
                    </div>

                    <!-- Busca por Palavra-chave -->
                    <div id="search-keyword" class="search-tab hidden">
                        <form method="GET" action="{{ route('member.bible.search-keyword') }}" class="space-y-4">
                            <div>
                                <label for="keyword" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('Palavra-chave') }}
                                </label>
                                <input type="text" 
                                       id="keyword" 
                                       name="keyword" 
                                       value="{{ request('keyword') }}"
                                       placeholder="{{ __('Ex: amor, fé, esperança, salvação') }}"
                                       class="form-input w-full">
                            </div>

                            <div>
                                <label for="keyword_version" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('Versão') }}
                                </label>
                                <select id="keyword_version" name="version" class="form-select w-full">
                                    @foreach($versoes as $key => $versao)
                                        <option value="{{ $key }}" {{ request('version', session('bible_version', 'almeida_ra')) == $key ? 'selected' : '' }}>
                                            {{ $versao['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="limit" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('Limite de Resultados') }}
                                </label>
                                <select id="limit" name="limit" class="form-select w-full">
                                    <option value="10">10 resultados</option>
                                    <option value="20" selected>20 resultados</option>
                                    <option value="50">50 resultados</option>
                                    <option value="100">100 resultados</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-full">
                                <i class="fas fa-search mr-2"></i>{{ __('Buscar') }}
                            </button>
                        </form>
                    </div>

                    <!-- Busca Rápida -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-sm font-medium text-gray-900 mb-3">{{ __('Busca Rápida') }}</h3>
                        <div class="space-y-2">
                            @foreach($versiculosPopulares as $versiculo)
                                <button onclick="buscaRapida('{{ $versiculo['reference'] }}')" 
                                        class="w-full text-left p-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md">
                                    <div class="font-medium">{{ $versiculo['reference'] }}</div>
                                    <div class="text-xs text-gray-500 truncate">{{ $versiculo['text'] }}</div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resultados -->
            <div class="lg:col-span-3">
                @if(isset($resultados))
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-gray-900">
                                @if(isset($buscaPorReferencia))
                                    {{ __('Resultados da Busca') }}
                                @else
                                    {{ __('Resultados para:') }} "{{ request('keyword') }}"
                                @endif
                            </h2>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-500">{{ count($resultados) }} resultado(s)</span>
                                @if(count($resultados) > 0)
                                    <button onclick="exportarResultados()" class="btn btn-sm btn-outline">
                                        <i class="fas fa-download mr-1"></i>{{ __('Exportar') }}
                                    </button>
                                @endif
                            </div>
                        </div>

                        @if(count($resultados) > 0)
                            <div class="space-y-4">
                                @foreach($resultados as $resultado)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-2 mb-2">
                                                    <h3 class="font-semibold text-blue-600">{{ $resultado['reference'] }}</h3>
                                                    <span class="text-sm text-gray-500">{{ $resultado['version'] }}</span>
                                                </div>
                                                <p class="text-gray-700 leading-relaxed mb-3">{{ $resultado['text'] }}</p>
                                                
                                                @if(isset($resultado['context']))
                                                    <div class="text-sm text-gray-600 bg-gray-50 p-3 rounded">
                                                        <strong>{{ __('Contexto:') }}</strong> {{ $resultado['context'] }}
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="flex items-center space-x-2 ml-4">
                                                <button onclick="adicionarFavorito('{{ $resultado['reference'] }}', '{{ $resultado['text'] }}')" 
                                                        class="text-yellow-600 hover:text-yellow-800" 
                                                        title="{{ __('Adicionar aos favoritos') }}">
                                                    <i class="fas fa-star"></i>
                                                </button>
                                                <button onclick="compartilharVersiculo('{{ $resultado['reference'] }}', '{{ $resultado['text'] }}')" 
                                                        class="text-blue-600 hover:text-blue-800" 
                                                        title="{{ __('Compartilhar') }}">
                                                    <i class="fas fa-share"></i>
                                                </button>
                                                <button onclick="copiarVersiculo('{{ $resultado['reference'] }}', '{{ $resultado['text'] }}')" 
                                                        class="text-gray-600 hover:text-gray-800" 
                                                        title="{{ __('Copiar') }}">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Paginação -->
                            @if(isset($paginacao))
                                <div class="mt-6">
                                    {{ $paginacao->links() }}
                                </div>
                            @endif
                        @else
                            <div class="text-center py-12">
                                <div class="text-gray-400 mb-4">
                                    <i class="fas fa-search text-6xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Nenhum resultado encontrado') }}</h3>
                                <p class="text-gray-500 mb-6">{{ __('Tente ajustar os termos de busca ou usar uma referência diferente.') }}</p>
                                <div class="space-x-4">
                                    <button onclick="limparBusca()" class="btn btn-secondary">
                                        <i class="fas fa-times mr-2"></i>{{ __('Limpar Busca') }}
                                    </button>
                                    <a href="{{ route('member.bible.random-verse') }}" class="btn btn-primary">
                                        <i class="fas fa-random mr-2"></i>{{ __('Versículo Aleatório') }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <!-- Instruções de Busca -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="text-center py-12">
                            <div class="text-gray-400 mb-4">
                                <i class="fas fa-search text-6xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Como fazer uma busca') }}</h3>
                            <div class="max-w-2xl mx-auto space-y-4 text-left">
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-blue-900 mb-2">{{ __('Busca por Referência:') }}</h4>
                                    <ul class="text-sm text-blue-800 space-y-1">
                                        <li>• <strong>João 3:16</strong> - Versículo específico</li>
                                        <li>• <strong>Salmo 23</strong> - Capítulo completo</li>
                                        <li>• <strong>Mateus 5:1-12</strong> - Faixa de versículos</li>
                                        <li>• <strong>1 Coríntios 13</strong> - Capítulo por número</li>
                                    </ul>
                                </div>
                                
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-green-900 mb-2">{{ __('Busca por Palavra-chave:') }}</h4>
                                    <ul class="text-sm text-green-800 space-y-1">
                                        <li>• <strong>amor</strong> - Encontra todos os versículos com "amor"</li>
                                        <li>• <strong>salvação</strong> - Busca por temas específicos</li>
                                        <li>• <strong>esperança fé</strong> - Múltiplas palavras</li>
                                        <li>• <strong>"paz de Deus"</strong> - Frase exata</li>
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
function trocarAba(aba) {
    // Esconder todas as abas
    document.querySelectorAll('.search-tab').forEach(tab => {
        tab.classList.add('hidden');
    });
    
    // Remover classe ativa de todos os botões
    document.querySelectorAll('[id^="tab-"]').forEach(btn => {
        btn.classList.remove('bg-white', 'text-gray-900', 'shadow-sm');
        btn.classList.add('text-gray-600');
    });
    
    // Mostrar aba selecionada
    document.getElementById(`search-${aba}`).classList.remove('hidden');
    
    // Ativar botão selecionado
    document.getElementById(`tab-${aba}`).classList.add('bg-white', 'text-gray-900', 'shadow-sm');
    document.getElementById(`tab-${aba}`).classList.remove('text-gray-600');
}

function buscaRapida(referencia) {
    document.getElementById('reference').value = referencia;
    document.getElementById('tab-reference').click();
    document.querySelector('form').submit();
}

function adicionarFavorito(referencia, texto) {
    fetch('{{ route("member.bible.favorites.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({
            reference: referencia,
            text: texto
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('{{ __("Versículo adicionado aos favoritos!") }}', 'success');
        } else {
            showNotification(data.message || '{{ __("Erro ao adicionar aos favoritos") }}', 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showNotification('{{ __("Erro ao adicionar aos favoritos") }}', 'error');
    });
}

function compartilharVersiculo(referencia, texto) {
    const conteudo = `${referencia}\n\n"${texto}"\n\nCompartilhado via {{ config('app.name') }}`;
    
    if (navigator.share) {
        navigator.share({
            title: referencia,
            text: conteudo,
            url: window.location.href
        });
    } else {
        // Fallback para copiar
        copiarVersiculo(referencia, texto);
    }
}

function copiarVersiculo(referencia, texto) {
    const conteudo = `${referencia}\n\n"${texto}"`;
    
    navigator.clipboard.writeText(conteudo).then(() => {
        showNotification('{{ __("Versículo copiado para a área de transferência!") }}', 'success');
    }).catch(() => {
        showNotification('{{ __("Erro ao copiar versículo") }}', 'error');
    });
}

function exportarResultados() {
    const resultados = @json($resultados ?? []);
    const csv = [
        ['Referência', 'Texto', 'Versão'],
        ...resultados.map(r => [r.reference, r.text, r.version])
    ].map(row => row.map(cell => `"${cell}"`).join(',')).join('\n');
    
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'busca_biblica.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}

function limparBusca() {
    window.location.href = '{{ route("member.bible.search") }}';
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

// Inicializar com aba de referência
document.addEventListener('DOMContentLoaded', function() {
    trocarAba('reference');
});
</script>
@endpush
@endsection 