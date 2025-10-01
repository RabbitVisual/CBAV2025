@extends('layouts.member')

@section('title', 'Dashboard - Área do Membro')

@section('content')
<div class="space-y-8">
    <!-- Cabeçalho com Versículo Aleatório -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-700 dark:from-blue-800 dark:to-purple-900 rounded-xl shadow-lg p-8 text-white">
        <div class="flex justify-between items-start">
            <div class="flex-1">
                <h1 class="text-3xl font-bold mb-2">Bem-vindo, {{ auth()->user()->name }}!</h1>
                <p class="text-blue-100 dark:text-blue-200 mb-6">Que Deus abençoe seu dia e fortaleça sua fé</p>
                
                <!-- Versículo Aleatório da Bíblia Offline -->
                <div class="bg-white/10 dark:bg-white/5 backdrop-blur-sm rounded-lg p-6 border border-white/20 dark:border-white/10">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-bible text-2xl text-yellow-300"></i>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-semibold mb-2">Palavra de Deus para Hoje</h3>
                            <blockquote class="text-lg italic mb-3 leading-relaxed">
                                "{{ $versiculoAleatorio['texto'] ?? 'Porque Deus amou o mundo de tal maneira que deu o seu Filho unigênito, para que todo aquele que nele crê não pereça, mas tenha a vida eterna.' }}"
                            </blockquote>
                            <p class="text-sm text-blue-100 dark:text-blue-200">
                                <strong>{{ $versiculoAleatorio['referencia'] ?? 'João 3:16' }}</strong> - 
                                {{ $versiculoAleatorio['versao'] ?? 'Almeida Revista e Atualizada' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex space-x-3 ml-6">
                <a href="{{ route('member.donations.donate') }}" 
                   class="bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-colors flex items-center">
                    <i class="fas fa-heart mr-2"></i>Fazer Doação
                </a>
                <a href="{{ route('member.profile.index') }}" 
                   class="bg-white/20 hover:bg-white/30 dark:bg-white/10 dark:hover:bg-white/20 text-white px-6 py-3 rounded-lg transition-colors flex items-center">
                    <i class="fas fa-user-edit mr-2"></i>Meu Perfil
                </a>
            </div>
        </div>
    </div>

    <!-- Cards de Estatísticas Pessoais -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total de Doações -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">
                    <i class="fas fa-heart text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Minhas Doações</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($estatisticas['total_doacoes'] ?? 0) }}</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-green-600 font-medium">
                    <i class="fas fa-arrow-up mr-1"></i>R$ {{ number_format($estatisticas['valor_total'] ?? 0, 2, ',', '.') }}
                </span>
            </div>
        </div>

        <!-- Ministérios Participando -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400">
                    <i class="fas fa-church text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Ministérios</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($estatisticas['ministerios_participando'] ?? 0) }}</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-purple-600 font-medium">
                    <i class="fas fa-users mr-1"></i>Participando ativamente
                </span>
            </div>
        </div>

        <!-- Solicitações Pendentes -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Solicitações</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($estatisticas['solicitacoes_pendentes'] ?? 0) }}</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-yellow-600 dark:text-yellow-400 font-medium">
                    <i class="fas fa-exclamation-triangle mr-1"></i>Aguardando aprovação
                </span>
            </div>
        </div>

        <!-- Notificações Não Lidas -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400">
                    <i class="fas fa-bell text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Notificações</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($estatisticas['notificacoes_nao_lidas'] ?? 0) }}</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-red-600 dark:text-red-400 font-medium">
                    <i class="fas fa-envelope mr-1"></i>Não lidas
                </span>
            </div>
        </div>
    </div>

    <!-- Seções Principais -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Doações Recentes -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Doações Recentes</h3>
                    <a href="{{ route('member.donations.history') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                        Ver Todas <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if($doacoesRecentes && $doacoesRecentes->count() > 0)
                    <div class="space-y-4">
                        @foreach($doacoesRecentes as $doacao)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <div class="p-2 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">
                                        <i class="fas fa-heart"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-medium text-gray-900 dark:text-white">R$ {{ number_format($doacao->valor, 2, ',', '.') }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $doacao->campanha->titulo ?? 'Doação Geral' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300">
                                        Confirmado
                                    </span>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $doacao->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-heart text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
                        <p class="text-gray-500 dark:text-gray-400">Nenhuma doação registrada ainda</p>
                        <a href="{{ route('member.donations.donate') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 dark:bg-green-700 text-white rounded-lg hover:bg-green-700 dark:hover:bg-green-800">
                            <i class="fas fa-plus mr-2"></i>Fazer Primeira Doação
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Campanhas Ativas -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Campanhas Ativas</h3>
                    <a href="{{ route('member.donations.campaigns') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                        Ver Todas <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if($campanhasAtivas && $campanhasAtivas->count() > 0)
                    <div class="space-y-4">
                        @foreach($campanhasAtivas as $campanha)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-3">
                                    <h4 class="font-medium text-gray-900 dark:text-white">{{ $campanha->titulo }}</h4>
                                    @if($campanha->dias_restantes)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-300">
                                            {{ $campanha->dias_restantes }} dias
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ Str::limit($campanha->descricao, 100) }}</p>
                                <div class="flex justify-between items-center">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        Meta: R$ {{ number_format($campanha->meta, 2, ',', '.') }}
                                    </div>
                                    <a href="{{ route('member.donations.campaigns.show', $campanha) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                                        Participar
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-bullhorn text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
                        <p class="text-gray-500 dark:text-gray-400">Nenhuma campanha ativa no momento</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Ministérios e Devocional -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Ministérios Disponíveis -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ministérios Disponíveis</h3>
                    <a href="{{ route('member.ministries.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                        Ver Todos <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if($ministeriosDisponiveis && $ministeriosDisponiveis->count() > 0)
                    <div class="space-y-4">
                        @foreach($ministeriosDisponiveis->take(3) as $ministerio)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <div class="p-2 rounded-full" style="background-color: {{ $ministerio->cor }}20; color: {{ $ministerio->cor }}">
                                        <i class="fas fa-church"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $ministerio->nome }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $ministerio->total_membros }} membros</p>
                                    </div>
                                </div>
                                <a href="{{ route('member.ministries.show', $ministerio) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                                    Ver Detalhes
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-church text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
                        <p class="text-gray-500 dark:text-gray-400">Nenhum ministério disponível</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Devocional do Dia -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Devocional do Dia</h3>
                    <a href="{{ route('member.devotionals.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                        Ver Todos <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if($devocionalDiario)
                    <div class="bg-gradient-to-br from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 rounded-lg p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-3">{{ $devocionalDiario['titulo'] }}</h4>
                        <p class="text-gray-700 dark:text-gray-300 mb-4">{{ Str::limit($devocionalDiario['reflexao'], 150) }}</p>
                        <div class="flex justify-between items-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300">
                                {{ $devocionalDiario['tipo'] }}
                            </span>
                            <a href="{{ route('member.devotionals.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                                Ler Completo
                            </a>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-book-open text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
                        <p class="text-gray-500 dark:text-gray-400">Nenhum devocional disponível hoje</p>
                        <a href="{{ route('member.devotionals.index') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-700 text-white rounded-lg hover:bg-blue-700 dark:hover:bg-blue-800">
                            <i class="fas fa-search mr-2"></i>Buscar Devocionais
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Acesso Rápido -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Acesso Rápido</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('member.profile.index') }}" class="flex flex-col items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                    <i class="fas fa-user text-2xl text-blue-600 dark:text-blue-400 mb-2"></i>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Meu Perfil</span>
                </a>
                <a href="{{ route('member.donations.index') }}" class="flex flex-col items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                    <i class="fas fa-heart text-2xl text-green-600 dark:text-green-400 mb-2"></i>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Minhas Doações</span>
                </a>
                <a href="{{ route('member.ministries.index') }}" class="flex flex-col items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                    <i class="fas fa-church text-2xl text-purple-600 dark:text-purple-400 mb-2"></i>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Ministérios</span>
                </a>
                <a href="{{ route('member.bible.index') }}" class="flex flex-col items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                    <i class="fas fa-bible text-2xl text-orange-600 dark:text-orange-400 mb-2"></i>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Bíblia Digital</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Dicas Espirituais -->
    <div class="bg-gradient-to-r from-green-600 to-blue-600 dark:from-green-700 dark:to-blue-700 rounded-xl shadow-lg p-8 text-white">
        <div class="text-center mb-6">
            <h3 class="text-2xl font-bold mb-2">Dica Espiritual do Dia</h3>
            <p class="text-green-100 dark:text-green-200">Reflexões para fortalecer sua fé</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white/10 dark:bg-white/5 backdrop-blur-sm rounded-lg p-6 border border-white/20 dark:border-white/10">
                <div class="text-center">
                    <i class="fas fa-pray text-3xl text-yellow-300 dark:text-yellow-400 mb-4"></i>
                    <h4 class="font-semibold mb-2">Oração</h4>
                    <p class="text-sm text-green-100 dark:text-green-200">Dedique tempo para orar e meditar na Palavra de Deus. A oração é nossa linha direta com o Pai.</p>
                </div>
            </div>
            <div class="bg-white/10 dark:bg-white/5 backdrop-blur-sm rounded-lg p-6 border border-white/20 dark:border-white/10">
                <div class="text-center">
                    <i class="fas fa-hands-helping text-3xl text-yellow-300 dark:text-yellow-400 mb-4"></i>
                    <h4 class="font-semibold mb-2">Serviço</h4>
                    <p class="text-sm text-green-100 dark:text-green-200">Use seus dons e talentos para servir ao próximo. O serviço é uma forma de adorar a Deus.</p>
                </div>
            </div>
            <div class="bg-white/10 dark:bg-white/5 backdrop-blur-sm rounded-lg p-6 border border-white/20 dark:border-white/10">
                <div class="text-center">
                    <i class="fas fa-gift text-3xl text-yellow-300 dark:text-yellow-400 mb-4"></i>
                    <h4 class="font-semibold mb-2">Generosidade</h4>
                    <p class="text-sm text-green-100 dark:text-green-200">Seja generoso com seu tempo, recursos e amor. A generosidade abre portas para bênçãos.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Atualizar versículo aleatório a cada hora
setInterval(function() {
    fetch('{{ route("member.bible.random-verse") }}', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.versiculo) {
            const versiculoElement = document.querySelector('blockquote');
            const referenciaElement = document.querySelector('p strong');
            const versaoElement = document.querySelector('p .text-blue-100');
            
            if (versiculoElement) {
                versiculoElement.textContent = `"${data.versiculo.texto}"`;
            }
            if (referenciaElement) {
                referenciaElement.textContent = data.versiculo.referencia;
            }
            if (versaoElement) {
                versaoElement.textContent = ` - ${data.versiculo.versao}`;
            }
        }
    })
    .catch(error => console.error('Erro ao atualizar versículo:', error));
}, 3600000); // 1 hora
</script>
@endsection