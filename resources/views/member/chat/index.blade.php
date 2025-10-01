@extends('layouts.member')

@section('content')
<!-- Sistema de Tour -->
<div id="tour-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div id="tour-highlight" class="absolute border-2 border-blue-500 bg-blue-100 bg-opacity-20 rounded-lg transition-all duration-300"></div>
    <div id="tour-tooltip" class="absolute bg-white rounded-lg shadow-xl p-4 max-w-sm transition-all duration-300">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <h3 id="tour-title" class="text-lg font-semibold text-gray-900 mb-2"></h3>
                <p id="tour-description" class="text-sm text-gray-600 mb-4"></p>
                <div class="flex items-center justify-between">
                    <div class="flex space-x-2">
                        <button id="tour-prev" class="px-3 py-1 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition-colors">
                            <i class="fas fa-chevron-left mr-1"></i> Anterior
                        </button>
                        <button id="tour-next" class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                            Próximo <i class="fas fa-chevron-right ml-1"></i>
                        </button>
                    </div>
                    <button id="tour-close" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-comments text-blue-600 mr-3"></i>
                    Chat da Igreja
                </h1>
                <p class="text-gray-600 mt-2">Conecte-se com outros membros da igreja de forma edificante</p>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Botão de Tour -->
                <button id="start-tour" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                    <i class="fas fa-play mr-2"></i>
                    Iniciar Tour
                </button>
                
                <div class="bg-blue-100 rounded-lg p-3">
                    <div class="text-2xl font-bold text-blue-600">{{ $chatStats['total_rooms'] ?? 0 }}</div>
                    <div class="text-sm text-blue-600">Minhas Salas</div>
                </div>
                <div class="bg-green-100 rounded-lg p-3">
                    <div class="text-2xl font-bold text-green-600">{{ $chatStats['active_rooms'] ?? 0 }}</div>
                    <div class="text-sm text-green-600">Salas Ativas</div>
                </div>
                <div class="bg-purple-100 rounded-lg p-3">
                    <div class="text-2xl font-bold text-purple-600">{{ $chatStats['total_messages'] ?? 0 }}</div>
                    <div class="text-sm text-purple-600">Mensagens</div>
                </div>
            </div>
        </div>
        
        <!-- Guia de Uso -->
        <div class="mt-6 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg p-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                <i class="fas fa-lightbulb text-yellow-600 mr-2"></i>
                Como usar o Chat
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-700">
                <div class="flex items-start space-x-2">
                    <div class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold">1</div>
                    <div>
                        <p class="font-semibold">Encontre uma Sala</p>
                        <p>Escolha uma sala disponível que combine com seus interesses</p>
                    </div>
                </div>
                <div class="flex items-start space-x-2">
                    <div class="w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-xs font-bold">2</div>
                    <div>
                        <p class="font-semibold">Entre na Conversa</p>
                        <p>Clique em "Entrar" para participar da sala de chat</p>
                    </div>
                </div>
                <div class="flex items-start space-x-2">
                    <div class="w-6 h-6 bg-purple-500 text-white rounded-full flex items-center justify-center text-xs font-bold">3</div>
                    <div>
                        <p class="font-semibold">Conecte-se</p>
                        <p>Envie mensagens edificantes e interaja com outros membros</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Minhas Salas -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4" data-tour="minhas-salas">
                    <i class="fas fa-users text-blue-600 mr-2"></i>
                    Minhas Salas
                </h2>
                
                @if($userRooms->count() > 0)
                    <div class="space-y-3">
                        @foreach($userRooms as $room)
                            @php
                                $participant = $room->participants()->where('user_id', Auth::id())->first();
                                $isActive = $participant && $participant->ativo;
                            @endphp
                            <div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors relative overflow-hidden {{ $isActive ? '' : 'bg-gray-50' }}">
                                <!-- Indicador de Status -->
                                @if(!$isActive)
                                    <div class="absolute top-2 right-2">
                                        <span class="bg-gray-500 text-white text-xs px-2 py-1 rounded-full">
                                            Inativo
                                        </span>
                                    </div>
                                @endif
                                
                                <!-- Indicador de Novas Mensagens -->
                                @php
                                    $unreadCount = 0;
                                    try {
                                        $unreadCount = $room->unreadMessagesCount(Auth::id());
                                    } catch (\Exception $e) {
                                        $unreadCount = 0;
                                    }
                                @endphp
                                @if($unreadCount > 0)
                                    <div class="absolute top-2 {{ $isActive ? 'right-2' : 'right-16' }}">
                                        <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                            {{ $unreadCount }}
                                        </span>
                                    </div>
                                @endif
                                
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 rounded-full flex items-center justify-center text-white 
                                            @if($room->tipo === 'publico') bg-green-500
                                            @elseif($room->tipo === 'privado') bg-yellow-500
                                            @elseif($room->tipo === 'ministerio') bg-blue-500
                                            @else bg-purple-500
                                            @endif">
                                            <i class="fas fa-comments text-lg"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">{{ $room->nome }}</h3>
                                            <p class="text-sm text-gray-500">{{ ucfirst($room->tipo) }}</p>
                                        </div>
                                    </div>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if($room->tipo === 'publico') bg-green-100 text-green-800
                                        @elseif($room->tipo === 'privado') bg-yellow-100 text-yellow-800
                                        @elseif($room->tipo === 'ministerio') bg-blue-100 text-blue-800
                                        @else bg-purple-100 text-purple-800
                                        @endif">
                                        {{ ucfirst($room->tipo) }}
                                    </span>
                                </div>
                                
                                @if($room->descricao)
                                    <p class="text-sm text-gray-600 mb-3">{{ $room->descricao }}</p>
                                @endif
                                
                                <!-- Status do Participante -->
                                @if(!$isActive)
                                    <div class="mb-3 p-2 bg-yellow-50 border border-yellow-200 rounded">
                                        <p class="text-xs text-yellow-700">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Você saiu desta sala. Clique em "Entrar" para participar novamente.
                                        </p>
                                    </div>
                                @endif
                                
                                <!-- Última Mensagem -->
                                @php
                                    $lastMessage = $room->messages()->latest()->first();
                                @endphp
                                @if($lastMessage)
                                    <div class="mb-3 p-2 bg-gray-50 rounded">
                                        <p class="text-xs text-gray-500 mb-1">
                                            <i class="fas fa-clock mr-1"></i>
                                            Última mensagem: {{ $lastMessage->created_at->diffForHumans() }}
                                        </p>
                                        <p class="text-sm text-gray-700 truncate">
                                            <strong>{{ $lastMessage->user->name }}:</strong> {{ $lastMessage->mensagem }}
                                        </p>
                                    </div>
                                @endif
                                
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center space-x-4 text-xs text-gray-500">
                                        <span class="flex items-center">
                                            <i class="fas fa-users mr-1"></i>
                                            {{ $room->participants()->where('ativo', true)->count() }} participantes
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-comment mr-1"></i>
                                            {{ $room->messages()->count() }} mensagens
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="flex space-x-2">
                                    <a href="{{ route('member.chat.show', $room->id) }}" 
                                       class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors transform hover:scale-105">
                                        <i class="fas fa-comments mr-2"></i>
                                        {{ $isActive ? 'Entrar' : 'Reentrar' }}
                                    </a>
                                    @if($isActive)
                                        <form action="{{ route('member.chat.leave', $room->id) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors transform hover:scale-105"
                                                    onclick="return confirm('Tem certeza que deseja sair desta sala?')">
                                                <i class="fas fa-sign-out-alt mr-2"></i>
                                                Sair
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-comments text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">Você ainda não participa de nenhuma sala de chat.</p>
                        <p class="text-sm text-gray-400 mt-2">Entre em uma sala disponível para começar!</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Salas Disponíveis -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4" data-tour="salas-disponiveis">
                    <i class="fas fa-plus-circle text-green-600 mr-2"></i>
                    Salas Disponíveis
                </h2>
                
                @if($availableRooms->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($availableRooms as $room)
                            <div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors relative overflow-hidden" data-tour="sala-item">
                                <!-- Indicador de Status -->
                                <div class="absolute top-0 right-0 w-3 h-3 bg-green-400 rounded-full m-2"></div>
                                
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 rounded-full flex items-center justify-center text-white 
                                            @if($room->tipo === 'publico') bg-green-500
                                            @elseif($room->tipo === 'privado') bg-yellow-500
                                            @elseif($room->tipo === 'ministerio') bg-blue-500
                                            @else bg-purple-500
                                            @endif">
                                            <i class="fas fa-comments text-lg"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">{{ $room->nome }}</h3>
                                            <p class="text-sm text-gray-500">{{ ucfirst($room->tipo) }}</p>
                                        </div>
                                    </div>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if($room->tipo === 'publico') bg-green-100 text-green-800
                                        @elseif($room->tipo === 'privado') bg-yellow-100 text-yellow-800
                                        @elseif($room->tipo === 'ministerio') bg-blue-100 text-blue-800
                                        @else bg-purple-100 text-purple-800
                                        @endif">
                                        {{ ucfirst($room->tipo) }}
                                    </span>
                                </div>
                                
                                @if($room->descricao)
                                    <p class="text-sm text-gray-600 mb-3">{{ $room->descricao }}</p>
                                @endif
                                
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center space-x-4 text-xs text-gray-500">
                                        <span class="flex items-center">
                                            <i class="fas fa-users mr-1"></i>
                                            {{ $room->participants()->where('ativo', true)->count() }} participantes
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-comment mr-1"></i>
                                            {{ $room->messages()->count() }} mensagens
                                        </span>
                                    </div>
                                </div>
                                
                                <form action="{{ route('member.chat.join', $room->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors transform hover:scale-105">
                                        <i class="fas fa-sign-in-alt mr-2"></i>
                                        Entrar na Sala
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-comments text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">Não há salas disponíveis no momento.</p>
                        <p class="text-sm text-gray-400 mt-2">Entre em contato com um administrador para criar novas salas.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">
            <i class="fas fa-chart-bar text-purple-600 mr-2"></i>
            Estatísticas do Chat
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-blue-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $chatStats['total_rooms'] ?? 0 }}</div>
                <div class="text-sm text-blue-600">Minhas Salas</div>
            </div>
            <div class="bg-green-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ $chatStats['total_messages'] ?? 0 }}</div>
                <div class="text-sm text-green-600">Total Mensagens</div>
            </div>
            <div class="bg-purple-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-purple-600">{{ $chatStats['active_participants'] ?? 0 }}</div>
                <div class="text-sm text-purple-600">Participantes</div>
            </div>
            <div class="bg-orange-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-orange-600">{{ $chatStats['active_rooms'] ?? 0 }}</div>
                <div class="text-sm text-orange-600">Salas Ativas</div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sistema de Tour
    const tourSteps = [
        {
            target: '[data-tour="minhas-salas"]',
            title: 'Minhas Salas',
            description: 'Aqui você vê todas as salas onde você é participante. As salas mostram informações como última mensagem, número de participantes e contador de mensagens não lidas.',
            position: 'bottom'
        },
        {
            target: '[data-tour="salas-disponiveis"]',
            title: 'Salas Disponíveis',
            description: 'Esta seção mostra todas as salas que você pode entrar. Cada sala tem informações sobre participantes, mensagens e tipo (público, privado, ministério).',
            position: 'bottom'
        },
        {
            target: '[data-tour="sala-item"]',
            title: 'Sala Individual',
            description: 'Cada sala mostra: nome, tipo, descrição, número de participantes e mensagens. Clique em "Entrar na Sala" para participar.',
            position: 'top'
        },
        {
            target: '#start-tour',
            title: 'Tour Interativo',
            description: 'Sempre que precisar de ajuda, clique neste botão para iniciar o tour novamente e relembrar como usar o sistema.',
            position: 'left'
        }
    ];

    let currentStep = 0;
    let isTourActive = false;

    const tourOverlay = document.getElementById('tour-overlay');
    const tourHighlight = document.getElementById('tour-highlight');
    const tourTooltip = document.getElementById('tour-tooltip');
    const tourTitle = document.getElementById('tour-title');
    const tourDescription = document.getElementById('tour-description');
    const tourPrev = document.getElementById('tour-prev');
    const tourNext = document.getElementById('tour-next');
    const tourClose = document.getElementById('tour-close');
    const startTourBtn = document.getElementById('start-tour');

    function showStep(stepIndex) {
        if (stepIndex < 0 || stepIndex >= tourSteps.length) {
            endTour();
            return;
        }

        const step = tourSteps[stepIndex];
        const target = document.querySelector(step.target);
        
        if (!target) {
            currentStep++;
            showStep(currentStep);
            return;
        }

        // Posicionar highlight
        const rect = target.getBoundingClientRect();
        tourHighlight.style.top = rect.top - 10 + 'px';
        tourHighlight.style.left = rect.left - 10 + 'px';
        tourHighlight.style.width = rect.width + 20 + 'px';
        tourHighlight.style.height = rect.height + 20 + 'px';

        // Posicionar tooltip
        const tooltipRect = tourTooltip.getBoundingClientRect();
        let tooltipTop, tooltipLeft;

        switch (step.position) {
            case 'top':
                tooltipTop = rect.top - tooltipRect.height - 20;
                tooltipLeft = rect.left + (rect.width / 2) - (tooltipRect.width / 2);
                break;
            case 'bottom':
                tooltipTop = rect.bottom + 20;
                tooltipLeft = rect.left + (rect.width / 2) - (tooltipRect.width / 2);
                break;
            case 'left':
                tooltipTop = rect.top + (rect.height / 2) - (tooltipRect.height / 2);
                tooltipLeft = rect.left - tooltipRect.width - 20;
                break;
            case 'right':
                tooltipTop = rect.top + (rect.height / 2) - (tooltipRect.height / 2);
                tooltipLeft = rect.right + 20;
                break;
        }

        // Ajustar se tooltip sair da tela
        if (tooltipLeft < 20) tooltipLeft = 20;
        if (tooltipLeft + tooltipRect.width > window.innerWidth - 20) {
            tooltipLeft = window.innerWidth - tooltipRect.width - 20;
        }
        if (tooltipTop < 20) tooltipTop = 20;
        if (tooltipTop + tooltipRect.height > window.innerHeight - 20) {
            tooltipTop = window.innerHeight - tooltipRect.height - 20;
        }

        tourTooltip.style.top = tooltipTop + 'px';
        tourTooltip.style.left = tooltipLeft + 'px';

        // Atualizar conteúdo
        tourTitle.textContent = step.title;
        tourDescription.textContent = step.description;

        // Atualizar botões
        tourPrev.style.display = stepIndex === 0 ? 'none' : 'inline-flex';
        tourNext.textContent = stepIndex === tourSteps.length - 1 ? 'Finalizar' : 'Próximo';
    }

    function startTour() {
        isTourActive = true;
        currentStep = 0;
        tourOverlay.classList.remove('hidden');
        showStep(currentStep);
        
        // Salvar no localStorage que o usuário já viu o tour
        localStorage.setItem('chat-tour-completed', 'true');
    }

    function endTour() {
        isTourActive = false;
        tourOverlay.classList.add('hidden');
    }

    function nextStep() {
        currentStep++;
        showStep(currentStep);
    }

    function prevStep() {
        currentStep--;
        showStep(currentStep);
    }

    // Event listeners
    startTourBtn.addEventListener('click', startTour);
    tourClose.addEventListener('click', endTour);
    tourNext.addEventListener('click', () => {
        if (currentStep === tourSteps.length - 1) {
            endTour();
        } else {
            nextStep();
        }
    });
    tourPrev.addEventListener('click', prevStep);

    // Fechar tour ao clicar fora
    tourOverlay.addEventListener('click', function(e) {
        if (e.target === tourOverlay) {
            endTour();
        }
    });

    // Verificar se é a primeira visita
    const tourCompleted = localStorage.getItem('chat-tour-completed');
    if (!tourCompleted) {
        // Mostrar tour automaticamente após 2 segundos
        setTimeout(() => {
            startTour();
        }, 2000);
    }

    // Atalho de teclado para iniciar tour (Ctrl+T)
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 't') {
            e.preventDefault();
            startTour();
        }
    });
});
</script>
@endpush
@endsection 