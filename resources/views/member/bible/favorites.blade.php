@extends('layouts.member')

@section('title', 'Meus Favoritos - Bíblia Digital')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            <i class="fas fa-heart text-red-600 mr-3"></i>
            Meus Favoritos
        </h1>
        <p class="text-gray-600">Seus versículos favoritos salvos para meditação.</p>
    </div>

    <!-- Ações -->
    <div class="mb-6 flex flex-wrap gap-4">
        <button onclick="exportarFavoritos()" 
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
            <i class="fas fa-download mr-2"></i>Exportar
        </button>
        <button onclick="limparFavoritos()" 
                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
            <i class="fas fa-trash mr-2"></i>Limpar Todos
        </button>
    </div>

    <!-- Lista de Favoritos -->
    @if(count($versiculosFavoritos) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="lista-favoritos">
            @foreach($versiculosFavoritos as $index => $favorito)
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200 hover:shadow-lg transition" 
                 data-index="{{ $index }}">
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $favorito['referencia'] }}</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $favorito['texto'] }}</p>
                    <p class="text-sm text-gray-500 mt-2">Versão: {{ $favorito['versao'] ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::parse($favorito['data'])->format('d/m/Y H:i') }}</p>
                </div>
                
                <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                    <button onclick="verDetalhesFavorito({{ $index }})" 
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        <i class="fas fa-eye mr-1"></i>Ver Detalhes
                    </button>
                    <button onclick="removerFavorito({{ $index }})" 
                            class="text-red-600 hover:text-red-800 text-sm font-medium">
                        <i class="fas fa-heart-broken mr-1"></i>Remover
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-heart text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Nenhum favorito ainda</h3>
            <p class="text-gray-500 mb-6">Adicione versículos aos seus favoritos para encontrá-los aqui facilmente.</p>
            <a href="{{ route('member.bible.index') }}" 
               class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-bible mr-2"></i>Explorar Bíblia
            </a>
        </div>
    @endif
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
                                const container = document.getElementById('lista-favoritos');
                                if (container) {
                                    container.innerHTML = `
                                        <div class="col-span-full text-center py-12">
                                            <i class="fas fa-heart text-gray-300 text-6xl mb-4"></i>
                                            <h3 class="text-xl font-semibold text-gray-600 mb-2">Nenhum favorito</h3>
                                            <p class="text-gray-500 mb-6">Todos os favoritos foram removidos.</p>
                                            <a href="{{ route('member.bible.index') }}" 
                                               class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                                <i class="fas fa-bible mr-2"></i>Explorar Bíblia
                                            </a>
                                        </div>
                                    `;
                                }
                            }
                        }, 300);
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

function exportarFavoritos() {
    const favoritos = @json($versiculosFavoritos);
    if (favoritos.length === 0) {
        showErrorModal('Não há favoritos para exportar.');
        return;
    }
    
    let conteudo = 'Meus Versículos Favoritos\n\n';
    favoritos.forEach((favorito, index) => {
        conteudo += `${index + 1}. ${favorito.referencia}\n`;
        conteudo += `"${favorito.texto}"\n`;
        conteudo += `Versão: ${favorito.versao || 'N/A'}\n`;
        conteudo += `Data: ${new Date(favorito.data).toLocaleDateString('pt-BR')}\n\n`;
    });
    
    const blob = new Blob([conteudo], { type: 'text/plain;charset=utf-8' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'meus-favoritos.txt';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
    
    showSuccessModal('Favoritos exportados com sucesso!');
}

function limparFavoritos() {
    showConfirmModal(
        'Limpar Todos os Favoritos',
        'Tem certeza que deseja remover todos os versículos dos favoritos? Esta ação não pode ser desfeita.',
        function() {
            fetch(`/member/bible/favorites/clear`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            }).then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error('Erro na resposta do servidor');
                }
            }).then(data => {
                if (data.success) {
                    showSuccessModal('Todos os favoritos foram removidos com sucesso!');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showErrorModal(data.message || 'Erro ao limpar favoritos.');
                }
            }).catch(error => {
                console.error('Erro:', error);
                showErrorModal('Erro ao limpar favoritos. Tente novamente.');
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