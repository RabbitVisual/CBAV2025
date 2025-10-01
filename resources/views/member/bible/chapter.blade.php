@extends('layouts.member')

@section('title', __('Capítulo') . ' ' . $capitulo . ' - ' . ($livroCapitalizado ?? $livro))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Cabeçalho -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        {{ $livroCapitalizado ?? $livro }} {{ __('Capítulo') }} {{ $capitulo }}
                    </h1>
                    <p class="text-gray-600 mt-2">{{ __('Versão') }}: {{ $nomeVersao ?? $versao }}</p>
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

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8" id="main-container">
            <!-- Sidebar de Navegação -->
            <div class="lg:col-span-1 transition-all duration-300 ease-in-out" id="sidebar">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Navegação') }}</h2>

                    <!-- Navegação de Capítulos -->
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-900 mb-3">{{ __('Capítulos') }}</h3>
                        <div class="grid grid-cols-5 gap-2">
                            @php
                                $capituloAtual = $capitulo;
                                $capitulosAnteriores = max(1, $capituloAtual - 5);
                                $capitulosPosteriores = min(50, $capituloAtual + 5);
                            @endphp
                            
                            @for($i = $capitulosAnteriores; $i <= $capitulosPosteriores; $i++)
                                <a href="{{ route('member.bible.read', ['livro' => $livro, 'capitulo' => $i, 'versao' => $versao]) }}" 
                                   class="text-center p-2 text-sm rounded-md {{ $i == $capituloAtual ? 'bg-blue-100 text-blue-800 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                                    {{ $i }}
                                </a>
                            @endfor
                        </div>
                    </div>

                    <!-- Versão -->
                    <div class="mb-6">
                        <label for="versao" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Versão') }}
                        </label>
                        <select id="versao" class="form-select w-full" onchange="trocarVersao()">
                            @foreach($versoesDisponiveis ?? [] as $versaoInfo)
                                <option value="{{ $versaoInfo['code'] }}" {{ $versao == $versaoInfo['code'] ? 'selected' : '' }}>
                                    {{ $versaoInfo['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Ações Rápidas -->
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-900 mb-3">{{ __('Ações') }}</h3>
                        <div class="space-y-2">
                            <button onclick="adicionarAosFavoritos()" class="w-full btn btn-outline btn-sm">
                                <i class="fas fa-heart mr-2"></i>{{ __('Adicionar aos Favoritos') }}
                            </button>
                            <button onclick="compartilharCapitulo()" class="w-full btn btn-outline btn-sm">
                                <i class="fas fa-share mr-2"></i>{{ __('Compartilhar') }}
                            </button>
                            <button onclick="imprimirCapitulo()" class="w-full btn btn-outline btn-sm">
                                <i class="fas fa-print mr-2"></i>{{ __('Imprimir') }}
                            </button>
                        </div>
                    </div>

                    <!-- Navegação Rápida -->
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-900 mb-3">{{ __('Navegação Rápida') }}</h3>
                        <div class="space-y-2">
                            <a href="{{ route('member.bible.read', ['livro' => 'salmo', 'capitulo' => 23, 'versao' => $versao]) }}" 
                               class="block p-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md">
                                <div class="font-medium">Salmo 23</div>
                                <div class="text-xs text-gray-500">O Senhor é meu pastor</div>
                            </a>
                            <a href="{{ route('member.bible.read', ['livro' => 'joao', 'capitulo' => 3, 'versao' => $versao]) }}" 
                               class="block p-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md">
                                <div class="font-medium">João 3</div>
                                <div class="text-xs text-gray-500">Nicodemos e o novo nascimento</div>
                            </a>
                            <a href="{{ route('member.bible.read', ['livro' => 'mateus', 'capitulo' => 5, 'versao' => $versao]) }}" 
                               class="block p-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md">
                                <div class="font-medium">Mateus 5</div>
                                <div class="text-xs text-gray-500">As bem-aventuranças</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conteúdo Principal -->
            <div class="lg:col-span-3 transition-all duration-300 ease-in-out" id="main-content">
                <div class="bg-white rounded-lg shadow-md p-8">
                                        <!-- Cabeçalho do Capítulo -->
                    <div class="mb-8 pb-6 border-b border-gray-200">
                                        <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-4">
                            <!-- Botão Toggle Sidebar -->
                            <button onclick="toggleSidebar()" class="lg:hidden sidebar-toggle bg-gray-100 hover:bg-gray-200 rounded-lg p-2 transition-colors duration-200" title="{{ __('Mostrar/Ocultar Navegação') }}">
                                <i class="fas fa-bars text-gray-600"></i>
                            </button>
                            <div class="bg-blue-100 rounded-full p-3">
                                <i class="fas fa-book-open text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">
                                    {{ $livroCapitalizado ?? $livro }} {{ __('Capítulo') }} {{ $capitulo }}
                                </h2>
                                <div class="flex items-center space-x-4 mt-2">
                                    <span class="text-gray-600">
                                        <i class="fas fa-bible mr-1"></i>
                                        {{ __('Versão') }}: {{ $nomeVersao ?? $versao }}
                                    </span>
                                    <span class="text-gray-500">
                                        <i class="fas fa-list-ol mr-1"></i>
                                        {{ count($resultado['verses'] ?? []) }} {{ __('versículos') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <!-- Botão Toggle Sidebar Desktop -->
                        <button onclick="toggleSidebar()" class="hidden lg:flex sidebar-toggle bg-gray-100 hover:bg-gray-200 rounded-lg p-2 transition-colors duration-200" title="{{ __('Mostrar/Ocultar Navegação') }}">
                            <i class="fas fa-bars text-gray-600"></i>
                        </button>
                        <button onclick="anteriorCapitulo()" class="btn btn-outline btn-sm hover:bg-blue-50">
                            <i class="fas fa-chevron-left mr-1"></i>
                            {{ __('Anterior') }}
                        </button>
                        <button onclick="proximoCapitulo()" class="btn btn-outline btn-sm hover:bg-blue-50">
                            {{ __('Próximo') }}
                            <i class="fas fa-chevron-right ml-1"></i>
                        </button>
                    </div>
                </div>
                    </div>

                    <!-- Texto do Capítulo -->
                    <div id="conteudo-capitulo" class="prose prose-lg max-w-none">
                        @if(isset($resultado['verses']) && is_array($resultado['verses']))
                            <div class="bg-white rounded-lg border border-gray-100 overflow-hidden">
                                @foreach($resultado['verses'] as $versiculo)
                                                                    <div class="versiculo group border-b border-gray-50 last:border-b-0 hover:bg-gray-25 transition-colors">
                                    <div class="flex items-start p-8">
                                            <!-- Número do versículo -->
                                            <div class="flex-shrink-0 mr-6">
                                                <div class="w-8 h-8 bg-gray-100 border border-gray-200 rounded-sm flex items-center justify-center">
                                                    <span class="text-gray-600 font-medium text-xs">{{ $versiculo['verse'] ?? $versiculo['number'] ?? '' }}</span>
                                                </div>
                                            </div>
                                            
                                            <!-- Texto do versículo -->
                                            <div class="flex-1 min-w-0">
                                                <div class="text-gray-900 leading-relaxed text-lg font-serif">
                                                    {{ $versiculo['text'] ?? $versiculo['content'] ?? '' }}
                                                </div>
                                                
                                                <!-- Referência do versículo -->
                                                <div class="mt-3 text-xs text-gray-400 font-normal">
                                                    {{ $livroCapitalizado ?? $livro }} {{ $capitulo }}:{{ $versiculo['verse'] ?? $versiculo['number'] ?? '' }}
                                                </div>
                                            </div>
                                            
                                            <!-- Ações do versículo -->
                                            <div class="flex-shrink-0 ml-6 flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <button onclick="adicionarVersiculoFavoritos('{{ $livroCapitalizado ?? $livro }} {{ $capitulo }}:{{ $versiculo['verse'] ?? $versiculo['number'] ?? '' }}', '{{ $versiculo['text'] ?? $versiculo['content'] ?? '' }}')" 
                                                        class="p-1.5 text-gray-400 hover:text-red-500 transition-colors" 
                                                        title="{{ __('Adicionar aos favoritos') }}">
                                                    <i class="fas fa-heart text-sm"></i>
                                                </button>
                                                <button onclick="compartilharVersiculo('{{ $livroCapitalizado ?? $livro }} {{ $capitulo }}:{{ $versiculo['verse'] ?? $versiculo['number'] ?? '' }}', '{{ $versiculo['text'] ?? $versiculo['content'] ?? '' }}')" 
                                                        class="p-1.5 text-gray-400 hover:text-blue-500 transition-colors" 
                                                        title="{{ __('Compartilhar') }}">
                                                    <i class="fas fa-share text-sm"></i>
                                                </button>
                                                <button onclick="copiarVersiculo('{{ $livroCapitalizado ?? $livro }} {{ $capitulo }}:{{ $versiculo['verse'] ?? $versiculo['number'] ?? '' }}', '{{ $versiculo['text'] ?? $versiculo['content'] ?? '' }}')" 
                                                        class="p-1.5 text-gray-400 hover:text-green-500 transition-colors" 
                                                        title="{{ __('Copiar versículo') }}">
                                                    <i class="fas fa-copy text-sm"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @elseif(isset($resultado['text']))
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                                <div class="text-gray-800 leading-relaxed text-lg font-serif">
                                    {!! nl2br(e($resultado['text'])) !!}
                                </div>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                                    <i class="fas fa-book-open text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-gray-600 text-lg">{{ __('Capítulo não encontrado ou não disponível.') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Navegação Inferior -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex space-x-4">
                                <button onclick="anteriorCapitulo()" class="btn btn-outline">
                                    <i class="fas fa-chevron-left mr-2"></i>{{ __('Capítulo Anterior') }}
                                </button>
                                <button onclick="proximoCapitulo()" class="btn btn-outline">
                                    {{ __('Próximo Capítulo') }}<i class="fas fa-chevron-right ml-2"></i>
                                </button>
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ __('Capítulo') }} {{ $capitulo }} de {{ $livroCapitalizado ?? $livro }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
let modoLeitura = false;
let versaoAtual = '{{ $versao }}';
let livroAtual = '{{ $livroCapitalizado ?? $livro }}';
let capituloAtual = {{ $capitulo }};
let sidebarCollapsed = false;

// Verificar estado do sidebar no localStorage
if (localStorage.getItem('sidebarCollapsed') === 'true') {
    sidebarCollapsed = true;
    document.addEventListener('DOMContentLoaded', function() {
        collapseSidebar();
    });
}

function toggleSidebar() {
    sidebarCollapsed = !sidebarCollapsed;
    
    if (sidebarCollapsed) {
        collapseSidebar();
    } else {
        expandSidebar();
    }
    
    // Salvar estado no localStorage
    localStorage.setItem('sidebarCollapsed', sidebarCollapsed);
}

function collapseSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const container = document.getElementById('main-container');
    
    if (sidebar && mainContent && container) {
        // Em telas grandes, esconder sidebar e expandir conteúdo
        if (window.innerWidth >= 1024) {
            sidebar.style.display = 'none';
            mainContent.className = 'lg:col-span-4 transition-all duration-300 ease-in-out';
        } else {
            // Em telas pequenas, esconder com animação
            sidebar.classList.remove('show');
        }
        
        // Remover overlay em mobile
        removeMobileOverlay();
    }
}

function expandSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const container = document.getElementById('main-container');
    
    if (sidebar && mainContent && container) {
        // Em telas grandes, restaurar layout original
        if (window.innerWidth >= 1024) {
            sidebar.style.display = '';
            mainContent.className = 'lg:col-span-3 transition-all duration-300 ease-in-out';
        } else {
            // Em telas pequenas, mostrar com animação
            sidebar.classList.add('show');
            addMobileOverlay();
        }
    }
}

function addMobileOverlay() {
    // Remover overlay existente se houver
    const existingOverlay = document.getElementById('mobile-overlay');
    if (existingOverlay) {
        existingOverlay.remove();
    }
    
    // Criar overlay
    const overlay = document.createElement('div');
    overlay.id = 'mobile-overlay';
    overlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden';
    overlay.onclick = function() {
        toggleSidebar();
    };
    
    document.body.appendChild(overlay);
}

function removeMobileOverlay() {
    const overlay = document.getElementById('mobile-overlay');
    if (overlay) {
        overlay.remove();
    }
}

function toggleModoLeitura() {
    modoLeitura = !modoLeitura;
    const conteudo = document.getElementById('conteudo-capitulo');
    const container = document.querySelector('.container');
    const sidebar = document.querySelector('.lg\\:col-span-1');
    const header = document.querySelector('.mb-8');
    const navigation = document.querySelector('.mt-8');
    
    if (modoLeitura) {
        // Ativar modo tela cheia
        document.body.classList.add('modo-leitura-fullscreen');
        container.classList.add('modo-leitura-container');
        conteudo.classList.add('modo-leitura-conteudo');
        
        // Ocultar elementos desnecessários
        if (sidebar) sidebar.style.display = 'none';
        if (header) header.style.display = 'none';
        if (navigation) navigation.style.display = 'none';
        
        // Adicionar botão de sair
        const exitButton = document.createElement('button');
        exitButton.id = 'exit-fullscreen';
        exitButton.className = 'fixed top-4 right-4 z-50 bg-white bg-opacity-90 hover:bg-opacity-100 rounded-full p-3 shadow-lg transition-all duration-300';
        exitButton.innerHTML = '<i class="fas fa-times text-gray-600 text-xl"></i>';
        exitButton.onclick = toggleModoLeitura;
        document.body.appendChild(exitButton);
        
        // Adicionar navegação flutuante
        const floatingNav = document.createElement('div');
        floatingNav.id = 'floating-navigation';
        floatingNav.className = 'fixed bottom-4 left-1/2 transform -translate-x-1/2 z-50 bg-white bg-opacity-90 hover:bg-opacity-100 rounded-full px-6 py-3 shadow-lg transition-all duration-300';
        floatingNav.innerHTML = `
            <div class="flex items-center space-x-4">
                <button onclick="anteriorCapitulo()" class="text-gray-600 hover:text-blue-600 transition-colors">
                    <i class="fas fa-chevron-left mr-1"></i>Anterior
                </button>
                <span class="text-gray-500">|</span>
                <span class="text-gray-700 font-medium">{{ $livroCapitalizado ?? $livro }} {{ $capitulo }}</span>
                <span class="text-gray-500">|</span>
                <button onclick="proximoCapitulo()" class="text-gray-600 hover:text-blue-600 transition-colors">
                    Próximo<i class="fas fa-chevron-right ml-1"></i>
                </button>
            </div>
        `;
        document.body.appendChild(floatingNav);
        
        // Adicionar indicador de progresso
        const progressBar = document.createElement('div');
        progressBar.id = 'reading-progress';
        progressBar.className = 'fixed top-0 left-0 w-full h-1 bg-gray-200 z-50';
        progressBar.innerHTML = '<div class="h-full bg-blue-500 transition-all duration-300" style="width: 0%"></div>';
        document.body.appendChild(progressBar);
        
        // Atualizar progresso de leitura
        updateReadingProgress();
        
    } else {
        // Desativar modo tela cheia
        document.body.classList.remove('modo-leitura-fullscreen');
        container.classList.remove('modo-leitura-container');
        conteudo.classList.remove('modo-leitura-conteudo');
        
        // Mostrar elementos novamente
        if (sidebar) sidebar.style.display = '';
        if (header) header.style.display = '';
        if (navigation) navigation.style.display = '';
        
        // Remover botão de sair
        const exitButton = document.getElementById('exit-fullscreen');
        if (exitButton) exitButton.remove();
        
        // Remover navegação flutuante
        const floatingNav = document.getElementById('floating-navigation');
        if (floatingNav) floatingNav.remove();
        
        // Remover indicador de progresso
        const progressBar = document.getElementById('reading-progress');
        if (progressBar) progressBar.remove();
    }
}

function updateReadingProgress() {
    const conteudo = document.getElementById('conteudo-capitulo');
    const progressBar = document.getElementById('reading-progress');
    
    if (!conteudo || !progressBar) return;
    
    const scrollTop = conteudo.scrollTop;
    const scrollHeight = conteudo.scrollHeight - conteudo.clientHeight;
    const progress = (scrollTop / scrollHeight) * 100;
    
    const progressElement = progressBar.querySelector('div');
    if (progressElement) {
        progressElement.style.width = progress + '%';
    }
}

// Atualizar progresso quando scrollar
document.addEventListener('scroll', function() {
    if (modoLeitura) {
        updateReadingProgress();
    }
}, { passive: true });

// Listener para redimensionamento da janela
window.addEventListener('resize', function() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    
    if (window.innerWidth >= 1024) {
        // Em desktop, restaurar layout se sidebar estava colapsado
        if (sidebarCollapsed) {
            sidebar.style.display = 'none';
            mainContent.className = 'lg:col-span-4 transition-all duration-300 ease-in-out';
        } else {
            sidebar.style.display = '';
            mainContent.className = 'lg:col-span-3 transition-all duration-300 ease-in-out';
        }
        removeMobileOverlay();
    } else {
        // Em mobile, esconder sidebar se estava aberto
        if (!sidebarCollapsed) {
            sidebar.classList.remove('show');
            removeMobileOverlay();
        }
    }
});

function trocarVersao() {
    const novaVersao = document.getElementById('versao').value;
    if (novaVersao !== versaoAtual) {
        window.location.href = '{{ route("member.bible.read") }}?livro={{ $livro }}&capitulo={{ $capitulo }}&versao=' + novaVersao;
    }
}

function anteriorCapitulo() {
    if (capituloAtual > 1) {
        window.location.href = '{{ route("member.bible.read") }}?livro={{ $livro }}&capitulo=' + (capituloAtual - 1) + '&versao={{ $versao }}';
    }
}

function proximoCapitulo() {
    window.location.href = '{{ route("member.bible.read") }}?livro={{ $livro }}&capitulo=' + (capituloAtual + 1) + '&versao={{ $versao }}';
}

function adicionarAosFavoritos() {
    const referencia = livroAtual + ' ' + capituloAtual;
    const texto = document.getElementById('conteudo-capitulo').textContent.trim();
    
    fetch('{{ route("member.bible.favorites.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            reference: referencia,
            text: texto,
            version: versaoAtual
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarAlerta('success', data.message);
        } else {
            mostrarAlerta('error', data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        mostrarAlerta('error', 'Erro ao adicionar aos favoritos');
    });
}

function adicionarVersiculoFavoritos(referencia, texto) {
    fetch('{{ route("member.bible.favorites.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            reference: referencia,
            text: texto,
            version: versaoAtual
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarAlerta('success', data.message);
        } else {
            mostrarAlerta('error', data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        mostrarAlerta('error', 'Erro ao adicionar aos favoritos');
    });
}

function compartilharCapitulo() {
    const referencia = livroAtual + ' ' + capituloAtual;
    const texto = document.getElementById('conteudo-capitulo').textContent.trim();
    
    if (navigator.share) {
        navigator.share({
            title: referencia,
            text: texto,
            url: window.location.href
        });
    } else {
        // Fallback para copiar link
        navigator.clipboard.writeText(window.location.href).then(() => {
            mostrarAlerta('success', 'Link copiado para a área de transferência!');
        });
    }
}

function compartilharVersiculo(referencia, texto) {
    if (navigator.share) {
        navigator.share({
            title: referencia,
            text: texto,
            url: window.location.href
        });
    } else {
        // Fallback para copiar texto
        navigator.clipboard.writeText(referencia + ': ' + texto).then(() => {
            mostrarAlerta('success', 'Versículo copiado para a área de transferência!');
        });
    }
}

function copiarVersiculo(referencia, texto) {
    const textoCompleto = referencia + ': ' + texto;
    navigator.clipboard.writeText(textoCompleto).then(() => {
        mostrarAlerta('success', 'Versículo copiado para a área de transferência!');
    }).catch(() => {
        // Fallback para navegadores mais antigos
        const textArea = document.createElement('textarea');
        textArea.value = textoCompleto;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        mostrarAlerta('success', 'Versículo copiado para a área de transferência!');
    });
}

function imprimirCapitulo() {
    window.print();
}

function mostrarAlerta(tipo, mensagem) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${tipo} fixed top-4 right-4 z-50`;
    alertDiv.innerHTML = `
        <i class="fas fa-${tipo === 'success' ? 'check' : 'exclamation'}-circle mr-2"></i>
        ${mensagem}
    `;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}

// Adicionar funcionalidades de teclado para modo leitura
document.addEventListener('keydown', function(e) {
    if (modoLeitura) {
        switch(e.key) {
            case 'Escape':
                e.preventDefault();
                toggleModoLeitura();
                break;
            case 'ArrowLeft':
                e.preventDefault();
                anteriorCapitulo();
                break;
            case 'ArrowRight':
                e.preventDefault();
                proximoCapitulo();
                break;
            case ' ':
                e.preventDefault();
                // Scroll suave
                const conteudo = document.getElementById('conteudo-capitulo');
                conteudo.scrollBy({ top: 100, behavior: 'smooth' });
                break;
        }
    }
});

// Adicionar estilos para modo leitura em tela cheia
const style = document.createElement('style');
style.textContent = `
    /* Modo leitura em tela cheia */
    .modo-leitura-fullscreen {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%) !important;
        overflow: hidden !important;
    }
    
    .modo-leitura-container {
        max-width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
        height: 100vh !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    
    .modo-leitura-conteudo {
        max-width: 900px !important;
        width: 100% !important;
        height: 100vh !important;
        overflow-y: auto !important;
        padding: 4rem 2rem !important;
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(10px) !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        border: none !important;
    }
    
    .modo-leitura-conteudo .versiculo {
        margin-bottom: 2.5rem !important;
        padding: 0 !important;
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        transition: all 0.4s ease !important;
    }
    
    .modo-leitura-conteudo .versiculo:hover {
        background: rgba(59, 130, 246, 0.05) !important;
        transform: translateX(10px) !important;
    }
    
    .modo-leitura-conteudo .text-lg {
        font-size: 1.4rem !important;
        line-height: 2.2 !important;
        font-family: 'Georgia', 'Times New Roman', serif !important;
        color: #1f2937 !important;
        text-align: justify !important;
        text-justify: inter-word !important;
    }
    
    .modo-leitura-conteudo .w-8 {
        width: 2rem !important;
        height: 2rem !important;
        opacity: 0.7 !important;
    }
    
    .modo-leitura-conteudo .text-xs {
        font-size: 0.75rem !important;
        opacity: 0.6 !important;
    }
    
    /* Scrollbar personalizada */
    .modo-leitura-conteudo::-webkit-scrollbar {
        width: 8px !important;
    }
    
    .modo-leitura-conteudo::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.05) !important;
    }
    
    .modo-leitura-conteudo::-webkit-scrollbar-thumb {
        background: rgba(59, 130, 246, 0.3) !important;
        border-radius: 4px !important;
    }
    
    .modo-leitura-conteudo::-webkit-scrollbar-thumb:hover {
        background: rgba(59, 130, 246, 0.5) !important;
    }
    
    /* Animações suaves */
    .modo-leitura-conteudo {
        animation: fadeIn 0.5s ease-in-out !important;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Navegação flutuante */
    #floating-navigation {
        backdrop-filter: blur(10px) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
    }
    
    #floating-navigation:hover {
        transform: translateX(-50%) scale(1.05) !important;
    }
    
    /* Botão de sair */
    #exit-fullscreen {
        backdrop-filter: blur(10px) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
    }
    
    #exit-fullscreen:hover {
        transform: scale(1.1) !important;
    }
    
    /* Estilos para melhor legibilidade */
    .font-serif {
        font-family: 'Georgia', 'Times New Roman', serif !important;
    }
    
    .versiculo .text-gray-800 {
        color: #1f2937 !important;
    }
    
    .versiculo .text-gray-500 {
        color: #6b7280 !important;
    }
    
    /* Animação suave para hover */
    .versiculo {
        transition: all 0.3s ease !important;
    }
    
    /* Estilo elegante para o número do versículo */
    .w-8.h-8 {
        background: #f9fafb !important;
        border: 1px solid #e5e7eb !important;
        color: #6b7280 !important;
        font-weight: 500 !important;
        transition: all 0.2s ease !important;
    }
    
    .w-8.h-8:hover {
        background: #f3f4f6 !important;
        border-color: #d1d5db !important;
    }
    
    @media print {
        .sidebar, .btn, .navegacao, .flex-shrink-0:last-child { display: none !important; }
        .conteudo-principal { width: 100% !important; }
        .versiculo { break-inside: avoid !important; }
    }
    
    /* Estilos para sidebar responsivo */
    @media (max-width: 1023px) {
        #sidebar {
            position: fixed !important;
            top: 0 !important;
            left: -100% !important;
            width: 320px !important;
            height: 100vh !important;
            z-index: 50 !important;
            transition: left 0.3s ease-in-out !important;
            background: white !important;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1) !important;
        }
        
        #sidebar.show {
            left: 0 !important;
        }
        
        #main-content {
            width: 100% !important;
        }
    }
    
    /* Animações suaves para o sidebar */
    #sidebar {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    
    #main-content {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    
    /* Botão toggle com animação */
    .sidebar-toggle {
        transition: all 0.2s ease-in-out !important;
    }
    
    .sidebar-toggle:hover {
        transform: scale(1.05) !important;
    }
    
    .sidebar-toggle:active {
        transform: scale(0.95) !important;
    }
`;
document.head.appendChild(style);
</script>

<style>
.versiculo {
    transition: all 0.3s ease;
}

.versiculo:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.prose {
    font-family: 'Georgia', serif;
    line-height: 1.8;
}

.prose p {
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .grid-cols-5 {
        grid-template-columns: repeat(3, 1fr);
    }
}
</style>
@endsection 