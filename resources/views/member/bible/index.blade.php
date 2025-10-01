@extends('layouts.member')

@section('title', 'Bíblia Digital - Área do Membro')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            <i class="fas fa-bible text-orange-600 mr-3"></i>
            Bíblia Digital
        </h1>
        <p class="text-gray-600">Leia, pesquise e medite na Palavra de Deus.</p>
    </div>

    <!-- Versículo do Dia -->
    @if(isset($versiculoDoDia))
    <div class="bg-gradient-to-r from-orange-500 to-red-600 rounded-lg shadow-md text-white mb-8">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <i class="fas fa-star text-2xl mr-3"></i>
                <h3 class="text-xl font-semibold">Versículo do Dia</h3>
            </div>
            <blockquote class="text-lg mb-4 italic">
                "{{ $versiculoDoDia['texto'] ?? $versiculoDoDia['text'] ?? 'Versículo não disponível' }}"
            </blockquote>
            <p class="text-orange-100 font-medium">{{ $versiculoDoDia['referencia'] ?? $versiculoDoDia['reference'] ?? 'Referência não disponível' }}</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Busca por Referência -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-search text-blue-600 mr-2"></i>
                        Buscar por Referência
                    </h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('member.bible.search-book') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="livro" class="block text-sm font-medium text-gray-700 mb-2">Livro</label>
                                <select id="livro" name="livro" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Selecione um livro</option>
                                    @foreach($livros as $abreviacao => $livro)
                                        <option value="{{ $abreviacao }}" {{ request('livro') == $abreviacao ? 'selected' : '' }}>
                                            {{ $livro['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="capitulo" class="block text-sm font-medium text-gray-700 mb-2">Capítulo</label>
                                <input type="number" id="capitulo" name="capitulo" value="{{ request('capitulo') }}" min="1" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="versiculo" class="block text-sm font-medium text-gray-700 mb-2">Versículo (opcional)</label>
                                <input type="number" id="versiculo" name="versiculo" value="{{ request('versiculo') }}" min="1" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                                <i class="fas fa-search mr-2"></i>Buscar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Busca por Palavra-chave -->
            <div class="bg-white rounded-lg shadow-md mt-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-keyboard text-green-600 mr-2"></i>
                        Buscar por Palavra-chave
                    </h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('member.bible.search-keyword') }}" method="GET" class="space-y-4">
                        <div>
                            <label for="keyword" class="block text-sm font-medium text-gray-700 mb-2">Palavra ou frase</label>
                            <input type="text" id="keyword" name="keyword" value="{{ request('keyword') }}" 
                                   placeholder="Digite uma palavra ou frase para buscar na Bíblia"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="livro_keyword" class="block text-sm font-medium text-gray-700 mb-2">Livro (opcional)</label>
                                <select id="livro_keyword" name="livro" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Todos os livros</option>
                                    @foreach($livros as $abreviacao => $livro)
                                        <option value="{{ $abreviacao }}" {{ request('livro') == $abreviacao ? 'selected' : '' }}>
                                            {{ $livro['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="limit" class="block text-sm font-medium text-gray-700 mb-2">Limite de resultados</label>
                                <select id="limit" name="limit" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="10" {{ request('limit') == '10' ? 'selected' : '' }}>10 resultados</option>
                                    <option value="25" {{ request('limit') == '25' ? 'selected' : '' }}>25 resultados</option>
                                    <option value="50" {{ request('limit') == '50' ? 'selected' : '' }}>50 resultados</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                                <i class="fas fa-search mr-2"></i>Buscar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Versículo Aleatório -->
            <div class="bg-white rounded-lg shadow-md mt-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-random text-purple-600 mr-2"></i>
                        Versículo Aleatório
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 mb-4">Descubra um versículo inspirador aleatoriamente.</p>
                                            <a href="{{ route('member.bible.random-verse') }}" class="inline-flex items-center px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium">
                        <i class="fas fa-dice mr-2"></i>Gerar Versículo
                    </a>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Versão da Bíblia -->
            <div class="bg-white rounded-lg shadow-md mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-cog text-gray-600 mr-2"></i>
                        Versão da Bíblia
                    </h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('member.bible.change-version') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="version" class="block text-sm font-medium text-gray-700 mb-2">Versão</label>
                            <select id="version" name="versao" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @foreach($versoesDisponiveis as $abreviacao => $versao)
                                    <option value="{{ $abreviacao }}" {{ $versaoAtual == $abreviacao ? 'selected' : '' }}>
                                        {{ $versao['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                            <i class="fas fa-save mr-2"></i>Salvar Preferência
                        </button>
                    </form>
                </div>
            </div>

            <!-- Versículos Favoritos -->
            <div class="bg-white rounded-lg shadow-md mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-heart text-red-600 mr-2"></i>
                        Versículos Favoritos
                    </h3>
                </div>
                <div class="p-6">
                    @if(count($versiculosFavoritos) > 0)
                        <div class="space-y-3">
                            @foreach(array_slice($versiculosFavoritos, 0, 5) as $index => $favorito)
                            <div class="border border-gray-200 rounded-lg p-3">
                                <p class="text-sm text-gray-600 mb-1">{{ $favorito['referencia'] }}</p>
                                <p class="text-sm font-medium text-gray-900">{{ Str::limit($favorito['texto'], 80) }}</p>
                                <div class="mt-2 flex justify-between items-center">
                                    <button onclick="verDetalhesFavorito({{ $index }})" 
                                            class="text-blue-600 hover:text-blue-800 text-xs">
                                        <i class="fas fa-eye mr-1"></i>Ver Detalhes
                                    </button>
                                    <button onclick="removerFavorito({{ $index }})" 
                                            class="text-red-600 hover:text-red-800 text-xs">
                                        <i class="fas fa-heart-broken mr-1"></i>Remover
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @if(count($versiculosFavoritos) > 5)
                            <div class="mt-4 text-center">
                                <a href="{{ route('member.bible.favorites') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                    Ver todos os favoritos
                                </a>
                            </div>
                        @endif
                    @else
                        <p class="text-gray-500 text-center py-4">Nenhum versículo favorito ainda.</p>
                    @endif
                </div>
            </div>

            <!-- Histórico de Leitura -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-history text-gray-600 mr-2"></i>
                        Histórico de Leitura
                    </h3>
                </div>
                <div class="p-6">
                    @if(count($historicoLeitura) > 0)
                        <div class="space-y-3">
                            @foreach(array_slice($historicoLeitura, 0, 5) as $leitura)
                            <div class="border border-gray-200 rounded-lg p-3">
                                <p class="text-sm font-medium text-gray-900">{{ $leitura['referencia'] }}</p>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($leitura['data'])->diffForHumans() }}</p>
                                <div class="mt-2">
                                    <a href="#" class="text-blue-600 hover:text-blue-800 text-xs">
                                        Ver Detalhes
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @if(count($historicoLeitura) > 5)
                            <div class="mt-4 text-center">
                                <a href="{{ route('member.bible.history') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                    Ver todo o histórico
                                </a>
                            </div>
                        @endif
                    @else
                        <p class="text-gray-500 text-center py-4">Nenhum histórico de leitura.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalhes do Favorito -->
<div id="favoritoModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full transform transition-all duration-300 scale-95 opacity-0" id="favoritoModalContent">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-gray-900" id="favoritoModalTitle">Detalhes do Versículo</h3>
                    <button onclick="fecharModalFavorito()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="mb-6">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 mb-4">
                        <h4 class="text-lg font-semibold text-gray-800 mb-2" id="favoritoModalReferencia"></h4>
                        <blockquote class="text-gray-700 text-lg leading-relaxed italic" id="favoritoModalTexto"></blockquote>
                        <p class="text-sm text-gray-500 mt-3" id="favoritoModalVersao"></p>
                        <p class="text-xs text-gray-400 mt-1" id="favoritoModalData"></p>
                    </div>
                </div>
                
                <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                    <button onclick="copiarVersiculo()" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-copy mr-2"></i>Copiar Versículo
                    </button>
                    <button onclick="fecharModalFavorito()" 
                            class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Variáveis globais
let favoritoAtual = null;

function verDetalhesFavorito(index) {
    const favoritos = @json($versiculosFavoritos);
    if (favoritos[index]) {
        favoritoAtual = favoritos[index];
        
        // Preencher modal
        document.getElementById('favoritoModalTitle').textContent = 'Detalhes do Versículo';
        document.getElementById('favoritoModalReferencia').textContent = favoritoAtual.referencia;
        document.getElementById('favoritoModalTexto').textContent = favoritoAtual.texto;
        document.getElementById('favoritoModalVersao').textContent = 'Versão: ' + (favoritoAtual.versao || 'N/A');
        document.getElementById('favoritoModalData').textContent = 'Adicionado em: ' + new Date(favoritoAtual.data).toLocaleDateString('pt-BR');
        
        // Mostrar modal
        const modal = document.getElementById('favoritoModal');
        const content = document.getElementById('favoritoModalContent');
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
}

function fecharModalFavorito() {
    const modal = document.getElementById('favoritoModal');
    const content = document.getElementById('favoritoModalContent');
    
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        favoritoAtual = null;
    }, 300);
}

function copiarVersiculo() {
    if (favoritoAtual) {
        const texto = `${favoritoAtual.referencia}\n\n"${favoritoAtual.texto}"\n\n${favoritoAtual.versao || 'Bíblia Digital'}`;
        
        navigator.clipboard.writeText(texto).then(() => {
            showSuccessModal('Versículo copiado para a área de transferência!');
        }).catch(() => {
            // Fallback para navegadores mais antigos
            const textArea = document.createElement('textarea');
            textArea.value = texto;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            showSuccessModal('Versículo copiado para a área de transferência!');
        });
    }
}

function removerFavorito(index) {
    showConfirmModal(
        'Remover Favorito',
        'Tem certeza que deseja remover este versículo dos favoritos?',
        function() {
            fetch(`/member/bible/favorites/remove`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ index: index })
            }).then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || 'Erro na resposta do servidor');
                    });
                }
            }).then(data => {
                if (data.success) {
                    showSuccessModal('Versículo removido dos favoritos com sucesso!');
                    
                    // Remover elemento da página com animação
                    const elemento = document.querySelector(`[data-index="${index}"]`);
                    if (elemento) {
                        elemento.style.transition = 'all 0.3s ease';
                        elemento.style.opacity = '0';
                        elemento.style.transform = 'scale(0.8)';
                        
                        setTimeout(() => {
                            elemento.remove();
                            
                            // Reindexar elementos restantes
                            const elementosRestantes = document.querySelectorAll('[data-index]');
                            elementosRestantes.forEach((el, newIndex) => {
                                el.setAttribute('data-index', newIndex);
                                const btnRemover = el.querySelector('button[onclick*="removerFavorito"]');
                                if (btnRemover) {
                                    btnRemover.setAttribute('onclick', `removerFavorito(${newIndex})`);
                                }
                                const btnDetalhes = el.querySelector('button[onclick*="verDetalhesFavorito"]');
                                if (btnDetalhes) {
                                    btnDetalhes.setAttribute('onclick', `verDetalhesFavorito(${newIndex})`);
                                }
                            });
                            
                            // Se não há mais favoritos, mostrar mensagem
                            if (elementosRestantes.length === 0) {
                                const container = document.querySelector('.space-y-3');
                                if (container) {
                                    container.innerHTML = '<p class="text-gray-500 text-center py-4">Nenhum versículo favorito ainda.</p>';
                                }
                            }
                        }, 300);
                    } else {
                        // Se não encontrou o elemento, recarregar a página
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    }
                } else {
                    showErrorModal(data.message || 'Erro ao remover favorito.');
                }
            }).catch(error => {
                console.error('Erro:', error);
                showErrorModal('Erro ao remover favorito: ' + error.message);
            });
        }
    );
}

// Fechar modal ao clicar fora
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('favoritoModal');
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            fecharModalFavorito();
        }
    });
    
    // Fechar modal com ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            fecharModalFavorito();
        }
    });
});
</script>
@endsection 