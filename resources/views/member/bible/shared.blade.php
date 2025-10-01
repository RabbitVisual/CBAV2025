@extends('layouts.member')

@section('title', 'Versículo Compartilhado')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full mb-4">
                <i class="fas fa-share-alt text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Versículo Compartilhado</h1>
            <p class="text-gray-600">Um versículo especial foi compartilhado com você</p>
        </div>

        <!-- Card do Versículo -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header do Card -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-white mb-2">{{ $referencia }}</h2>
                        <p class="text-blue-100">{{ $versao }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button onclick="copiarTexto()" 
                                class="flex items-center space-x-2 px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg text-white transition-all duration-200">
                            <i class="fas fa-copy"></i>
                            <span class="hidden sm:inline">Copiar</span>
                        </button>
                        <button onclick="compartilhar()" 
                                class="flex items-center space-x-2 px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg text-white transition-all duration-200">
                            <i class="fas fa-share-alt"></i>
                            <span class="hidden sm:inline">Compartilhar</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Conteúdo do Versículo -->
            <div class="px-8 py-8">
                <div class="prose prose-lg max-w-none">
                    <blockquote class="border-l-4 border-blue-500 pl-6 py-4 bg-blue-50 rounded-r-lg">
                        <p class="text-xl text-gray-800 leading-relaxed italic">
                            "{{ $texto }}"
                        </p>
                    </blockquote>
                </div>

                <!-- Informações Adicionais -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <i class="fas fa-book text-blue-600 text-2xl mb-2"></i>
                        <h3 class="font-semibold text-gray-900">Referência</h3>
                        <p class="text-gray-600">{{ $referencia }}</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <i class="fas fa-bible text-green-600 text-2xl mb-2"></i>
                        <h3 class="font-semibold text-gray-900">Versão</h3>
                        <p class="text-gray-600">{{ $versao }}</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <i class="fas fa-calendar text-purple-600 text-2xl mb-2"></i>
                        <h3 class="font-semibold text-gray-900">Compartilhado</h3>
                        <p class="text-gray-600">{{ now()->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Ações -->
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0 sm:space-x-4">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('member.bible.index') }}" 
                           class="flex items-center space-x-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                            <i class="fas fa-arrow-left"></i>
                            <span>Voltar à Bíblia</span>
                        </a>
                        <button onclick="adicionarFavoritos()" 
                                class="flex items-center space-x-2 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-heart"></i>
                            <span>Adicionar aos Favoritos</span>
                        </button>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500">Compartilhado via</span>
                        <img src="{{ asset('img/logo.png') }}" alt="CBAV" class="h-6">
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção de Versículos Relacionados -->
        <div class="mt-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Versículos Relacionados</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow">
                    <h4 class="font-semibold text-gray-900 mb-2">João 3:16</h4>
                    <p class="text-gray-600 text-sm italic">"Porque Deus amou o mundo de tal maneira que deu o seu Filho unigênito..."</p>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow">
                    <h4 class="font-semibold text-gray-900 mb-2">Salmos 23:1</h4>
                    <p class="text-gray-600 text-sm italic">"O Senhor é o meu pastor, nada me faltará."</p>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow">
                    <h4 class="font-semibold text-gray-900 mb-2">Filipenses 4:13</h4>
                    <p class="text-gray-600 text-sm italic">"Posso todas as coisas naquele que me fortalece."</p>
                </div>
            </div>
        </div>

        <!-- Seção de Compartilhamento -->
        <div class="mt-8 bg-white rounded-lg p-6 shadow-md">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Compartilhar este Versículo</h3>
            <div class="flex flex-wrap items-center space-x-4">
                <button onclick="compartilharWhatsApp()" 
                        class="flex items-center space-x-2 px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors">
                    <i class="fab fa-whatsapp"></i>
                    <span>WhatsApp</span>
                </button>
                <button onclick="compartilharFacebook()" 
                        class="flex items-center space-x-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    <i class="fab fa-facebook"></i>
                    <span>Facebook</span>
                </button>
                <button onclick="compartilharTwitter()" 
                        class="flex items-center space-x-2 px-4 py-2 bg-blue-400 hover:bg-blue-500 text-white rounded-lg transition-colors">
                    <i class="fab fa-twitter"></i>
                    <span>Twitter</span>
                </button>
                <button onclick="compartilharEmail()" 
                        class="flex items-center space-x-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                    <i class="fas fa-envelope"></i>
                    <span>Email</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
function copiarTexto() {
    const texto = `"{{ $texto }}" - {{ $referencia }} ({{ $versao }})`;
    
    if (navigator.clipboard) {
        navigator.clipboard.writeText(texto).then(() => {
            mostrarNotificacao('Texto copiado para a área de transferência!', 'success');
        }).catch(() => {
            copiarTextoFallback(texto);
        });
    } else {
        copiarTextoFallback(texto);
    }
}

function copiarTextoFallback(texto) {
    const textarea = document.createElement('textarea');
    textarea.value = texto;
    textarea.style.position = 'fixed';
    textarea.style.opacity = '0';
    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand('copy');
    document.body.removeChild(textarea);
    mostrarNotificacao('Texto copiado para a área de transferência!', 'success');
}

function compartilhar() {
    if (navigator.share) {
        navigator.share({
            title: 'Versículo Compartilhado',
            text: `"{{ $texto }}" - {{ $referencia }} ({{ $versao }})`,
            url: window.location.href
        }).then(() => {
            mostrarNotificacao('Compartilhado com sucesso!', 'success');
        }).catch((error) => {
            console.log('Erro ao compartilhar:', error);
        });
    } else {
        copiarTexto();
    }
}

function compartilharWhatsApp() {
    const texto = encodeURIComponent(`"{{ $texto }}" - {{ $referencia }} ({{ $versao }})`);
    const url = `https://wa.me/?text=${texto}`;
    window.open(url, '_blank');
}

function compartilharFacebook() {
    const url = encodeURIComponent(window.location.href);
    const texto = encodeURIComponent(`"{{ $texto }}" - {{ $referencia }} ({{ $versao }})`);
    const facebookUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${texto}`;
    window.open(facebookUrl, '_blank');
}

function compartilharTwitter() {
    const texto = encodeURIComponent(`"{{ $texto }}" - {{ $referencia }} ({{ $versao }})`);
    const url = encodeURIComponent(window.location.href);
    const twitterUrl = `https://twitter.com/intent/tweet?text=${texto}&url=${url}`;
    window.open(twitterUrl, '_blank');
}

function compartilharEmail() {
    const assunto = encodeURIComponent('Versículo Compartilhado');
    const corpo = encodeURIComponent(`Olá!\n\nGostaria de compartilhar este versículo com você:\n\n"{{ $texto }}"\n\nReferência: {{ $referencia }}\nVersão: {{ $versao }}\n\nQue Deus abençoe você!`);
    const emailUrl = `mailto:?subject=${assunto}&body=${corpo}`;
    window.location.href = emailUrl;
}

function adicionarFavoritos() {
    const dados = {
        referencia: '{{ $referencia }}',
        texto: '{{ $texto }}',
        versao: '{{ $versao }}',
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };

    fetch('{{ route("member.bible.favorites.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': dados._token
        },
        body: JSON.stringify(dados)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarNotificacao('Versículo adicionado aos favoritos!', 'success');
        } else {
            mostrarNotificacao(data.message || 'Erro ao adicionar aos favoritos', 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        mostrarNotificacao('Erro ao adicionar aos favoritos', 'error');
    });
}

function mostrarNotificacao(mensagem, tipo) {
    // Criar elemento de notificação
    const notificacao = document.createElement('div');
    notificacao.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
    
    const cores = {
        success: 'bg-green-500 text-white',
        error: 'bg-red-500 text-white',
        warning: 'bg-yellow-500 text-white',
        info: 'bg-blue-500 text-white'
    };
    
    notificacao.className += ` ${cores[tipo] || cores.info}`;
    notificacao.innerHTML = `
        <div class="flex items-center space-x-3">
            <i class="fas ${tipo === 'success' ? 'fa-check-circle' : tipo === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
            <span>${mensagem}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-auto">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notificacao);
    
    // Animar entrada
    setTimeout(() => {
        notificacao.classList.remove('translate-x-full');
    }, 100);
    
    // Remover automaticamente após 5 segundos
    setTimeout(() => {
        notificacao.classList.add('translate-x-full');
        setTimeout(() => {
            if (notificacao.parentElement) {
                notificacao.remove();
            }
        }, 300);
    }, 5000);
}

// Adicionar efeito de digitação ao texto do versículo
document.addEventListener('DOMContentLoaded', function() {
    const versiculo = document.querySelector('blockquote p');
    if (versiculo) {
        const texto = versiculo.textContent;
        versiculo.textContent = '';
        versiculo.style.opacity = '0';
        
        let i = 0;
        const typeWriter = () => {
            if (i < texto.length) {
                versiculo.textContent += texto.charAt(i);
                i++;
                setTimeout(typeWriter, 50);
            } else {
                versiculo.style.opacity = '1';
            }
        };
        
        setTimeout(typeWriter, 500);
    }
});
</script>
@endsection 