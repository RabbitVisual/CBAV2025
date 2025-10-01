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

<!-- Sistema de Notificações Toast -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-comments mr-3" style="color: {{ $room->cor ?? '#3B82F6' }}"></i>
                    {{ $room->nome }}
                </h1>
                <p class="text-gray-600 mt-2">{{ $room->descricao ?? 'Sala de chat da igreja' }}</p>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Botão de Tour -->
                <button id="start-tour" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                    <i class="fas fa-play mr-2"></i>
                    Iniciar Tour
                </button>
                
                <a href="{{ route('member.chat.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Lista de Salas -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4" data-tour="minhas-salas">
                    <i class="fas fa-list text-blue-600 mr-2"></i>
                    Minhas Salas
                </h2>
                
                @if($userRooms->count() > 0)
                    <div class="space-y-3">
                        @foreach($userRooms as $userRoom)
                            <a href="{{ route('member.chat.show', $userRoom->id) }}" 
                               class="block border rounded-lg p-3 hover:bg-gray-50 transition-colors {{ $userRoom->id == $room->id ? 'bg-blue-50 border-blue-300' : '' }}">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm bg-blue-500">
                                        <i class="fas fa-comments"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-medium text-gray-900">{{ $userRoom->nome }}</h3>
                                        <p class="text-xs text-gray-500">{{ ucfirst($userRoom->tipo) }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-gray-500 text-sm">Nenhuma sala disponível</p>
                    </div>
                @endif
            </div>

            <!-- Participantes -->
            <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4" data-tour="participantes">
                    <i class="fas fa-users text-green-600 mr-2"></i>
                    Participantes ({{ $participants->count() }})
                </h2>
                
                <div class="space-y-3">
                    @foreach($participants as $participant)
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                                <i class="fas fa-user text-gray-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $participant->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ ucfirst($participant->tipo) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Chat Principal -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-lg shadow-lg">
                <!-- Header do Chat -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white bg-blue-500">
                                <i class="fas fa-comments"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $room->nome }}</h3>
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <i class="fas fa-users mr-1"></i>
                                        {{ $participants->count() }} participantes
                                    </span>
                                    <span class="flex items-center">
                                        <i class="fas fa-comment mr-1"></i>
                                        {{ $messages->count() }} mensagens
                                    </span>
                                    <span class="flex items-center">
                                        <i class="fas fa-clock mr-1"></i>
                                        Criada em {{ $room->created_at->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($room->tipo === 'publico') bg-green-100 text-green-800
                                @elseif($room->tipo === 'privado') bg-yellow-100 text-yellow-800
                                @elseif($room->tipo === 'ministerio') bg-blue-100 text-blue-800
                                @else bg-purple-100 text-purple-800
                                @endif">
                                {{ ucfirst($room->tipo) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Área de Mensagens -->
                <div id="chat-messages" class="px-6 py-4 h-96 overflow-y-auto" data-tour="area-mensagens">
                    @foreach($messages as $message)
                        <div class="flex space-x-3 {{ $message->user_id == Auth::id() ? 'justify-end' : 'justify-start' }} mb-4">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600 text-sm"></i>
                                </div>
                            </div>
                            <div class="flex-1 max-w-xs lg:max-w-md">
                                <div class="{{ $message->user_id == Auth::id() ? 'bg-blue-100' : 'bg-gray-100' }} rounded-lg px-4 py-2">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-sm font-medium text-gray-900">{{ $message->user->name }}</span>
                                        <span class="text-xs text-gray-500">{{ $message->created_at->format('H:i') }}</span>
                                    </div>
                                    <p class="text-sm text-gray-700">{{ $message->mensagem }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Indicador de Digitação -->
                <div id="typing-indicator" class="hidden px-6 py-2 border-t border-gray-100">
                    <div class="flex items-center space-x-2">
                        <div class="flex space-x-1">
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        </div>
                        <span class="text-sm text-gray-500">Alguém está digitando...</span>
                    </div>
                </div>

                <!-- Input de Mensagem -->
                <div class="px-6 py-4 border-t border-gray-200">
                    @php
                        $participant = $room->participants()->where('user_id', Auth::id())->where('ativo', true)->first();
                        $isMuted = $participant && $participant->isMuted();
                    @endphp
                    
                    @if($isMuted)
                        <!-- Aviso de Mute -->
                        <div class="mb-4 p-3 bg-yellow-100 border border-yellow-300 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-volume-mute text-yellow-600 mr-2"></i>
                                <span class="text-yellow-800 font-medium">Você está mutado nesta sala</span>
                            </div>
                            <p class="text-yellow-700 text-sm mt-1">Você não pode enviar mensagens até ser desmutado por um administrador.</p>
                        </div>
                    @endif
                    
                    <form id="message-form" action="{{ route('member.chat.send', $room->id) }}" method="POST" class="flex space-x-3">
                        @csrf
                        <div class="flex-1 relative">
                            <input type="text" 
                                   id="message-input" 
                                   name="mensagem" 
                                   placeholder="{{ $isMuted ? 'Você está mutado e não pode enviar mensagens' : 'Digite sua mensagem...' }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $isMuted ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                   {{ $isMuted ? 'disabled' : 'required' }}>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <button type="button" id="emoji-button" class="text-gray-400 hover:text-gray-600 {{ $isMuted ? 'cursor-not-allowed' : '' }}">
                                    <i class="fas fa-smile"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed {{ $isMuted ? 'bg-gray-400 cursor-not-allowed' : '' }}"
                                {{ $isMuted ? 'disabled' : '' }}>
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                    
                    <!-- Painel de Emojis -->
                    <div id="emoji-panel" class="hidden mt-2 p-3 bg-gray-50 rounded-lg">
                        <div class="grid grid-cols-8 gap-2">
                            <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1" data-emoji="😊">😊</button>
                            <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1" data-emoji="🙏">🙏</button>
                            <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1" data-emoji="❤️">❤️</button>
                            <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1" data-emoji="👍">👍</button>
                            <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1" data-emoji="🎉">🎉</button>
                            <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1" data-emoji="🙌">🙌</button>
                            <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1" data-emoji="💪">💪</button>
                            <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1" data-emoji="✨">✨</button>
                            <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1" data-emoji="🌟">🌟</button>
                            <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1" data-emoji="💯">💯</button>
                            <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1" data-emoji="🔥">🔥</button>
                            <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1" data-emoji="💙">💙</button>
                            <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1" data-emoji="🙂">🙂</button>
                            <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1" data-emoji="😄">😄</button>
                            <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1" data-emoji="🤗">🤗</button>
                            <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1" data-emoji="👏">👏</button>
                        </div>
                    </div>
                    
                    <!-- Dicas de Uso -->
                    <div class="mt-2 text-xs text-gray-500">
                        <p>💡 <strong>Dicas:</strong> Seja respeitoso e edificante em suas mensagens. Use emojis para expressar sentimentos!</p>
                    </div>
                </div>
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
            description: 'Aqui você vê todas as salas onde você é participante. Clique em qualquer sala para navegar rapidamente entre elas.',
            position: 'right'
        },
        {
            target: '[data-tour="participantes"]',
            title: 'Lista de Participantes',
            description: 'Veja quem está participando desta sala. Você pode ver o nome e tipo de cada participante (membro, moderador, admin).',
            position: 'right'
        },
        {
            target: '[data-tour="area-mensagens"]',
            title: 'Área de Mensagens',
            description: 'Aqui aparecem todas as mensagens da sala. As suas mensagens aparecem à direita (azul) e as dos outros à esquerda (cinza).',
            position: 'top'
        },
        {
            target: '#message-input',
            title: 'Campo de Mensagem',
            description: 'Digite sua mensagem aqui. Use Ctrl+Enter para enviar rapidamente. Se você estiver mutado, este campo ficará desabilitado.',
            position: 'top'
        },
        {
            target: '#emoji-button',
            title: 'Emojis',
            description: 'Clique aqui para abrir o painel de emojis e adicionar expressões às suas mensagens.',
            position: 'top'
        },
        {
            target: '#start-tour',
            title: 'Tour Interativo',
            description: 'Sempre que precisar de ajuda, clique neste botão para iniciar o tour novamente.',
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
        localStorage.setItem('chat-room-tour-completed', 'true');
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
    const tourCompleted = localStorage.getItem('chat-room-tour-completed');
    if (!tourCompleted) {
        // Mostrar tour automaticamente após 3 segundos
        setTimeout(() => {
            startTour();
        }, 3000);
    }

    // Atalho de teclado para iniciar tour (Ctrl+T)
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 't') {
            e.preventDefault();
            startTour();
        }
    });

    // Verificar se estamos na página de chat específica
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const chatMessages = document.getElementById('chat-messages');
    const sendButton = document.querySelector('button[type="submit"]');
    
    // Se não estamos na página de chat, não executar o JavaScript
    if (!messageForm || !messageInput || !chatMessages) {
        return; // Sair silenciosamente
    }
    
    let lastMessageId = 0;
    let isTyping = false;
    let autoRefreshInterval;
    
    try {
        // Auto-scroll para o final
        function scrollToBottom() {
            if (chatMessages && chatMessages.scrollHeight) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        }
        
        // Scroll inicial
        scrollToBottom();
        
        // Função para atualizar mensagens
        function updateMessages() {
            fetch('{{ route("member.chat.messages", $room->id) }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.messages.length > 0) {
                        let hasNewMessages = false;
                        
                        data.messages.forEach(message => {
                            if (message.id > lastMessageId) {
                                // Verificar se a mensagem já existe
                                const existingMessage = document.querySelector(`[data-message-id="${message.id}"]`);
                                if (!existingMessage) {
                                    const messageElement = createMessageElement(message);
                                    chatMessages.appendChild(messageElement);
                                    hasNewMessages = true;
                                    lastMessageId = Math.max(lastMessageId, message.id);
                                }
                            }
                        });
                        
                        if (hasNewMessages) {
                            scrollToBottom();
                            updateUnreadCount();
                        }
                    }
                })
                .catch(error => {
                    console.error('Erro ao atualizar mensagens:', error);
                });
        }
        
        // Função para criar elemento de mensagem
        function createMessageElement(message) {
            const messageElement = document.createElement('div');
            messageElement.className = `flex space-x-3 ${message.user_id == {{ Auth::id() }} ? 'justify-end' : 'justify-start'} mb-4`;
            messageElement.setAttribute('data-message-id', message.id);
            
            const isOwnMessage = message.user_id == {{ Auth::id() }};
            
            messageElement.innerHTML = `
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                        <i class="fas fa-user text-gray-600 text-sm"></i>
                    </div>
                </div>
                <div class="flex-1 max-w-xs lg:max-w-md">
                    <div class="${isOwnMessage ? 'bg-blue-100' : 'bg-gray-100'} rounded-lg px-4 py-2">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-gray-900">${message.user.name}</span>
                            <span class="text-xs text-gray-500">${formatTime(message.created_at)}</span>
                        </div>
                        <p class="text-sm text-gray-700">${escapeHtml(message.mensagem)}</p>
                        ${message.editado ? '<p class="text-xs text-gray-500 mt-1">(editado)</p>' : ''}
                    </div>
                </div>
            `;
            
            return messageElement;
        }
        
        // Função para escapar HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        // Função para formatar tempo
        function formatTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diff = now - date;
            
            if (diff < 60000) { // Menos de 1 minuto
                return 'Agora';
            } else if (diff < 3600000) { // Menos de 1 hora
                const minutes = Math.floor(diff / 60000);
                return `${minutes}m atrás`;
            } else {
                return date.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
            }
        }
        
        // Função para atualizar contador de não lidas
        function updateUnreadCount() {
            const unreadBadge = document.querySelector('.unread-badge');
            if (unreadBadge) {
                const currentCount = parseInt(unreadBadge.textContent) || 0;
                unreadBadge.textContent = currentCount + 1;
                unreadBadge.classList.remove('hidden');
            }
        }
        
        // Função para mostrar indicador de digitação
        function showTypingIndicator() {
            const typingIndicator = document.getElementById('typing-indicator');
            if (typingIndicator) {
                typingIndicator.classList.remove('hidden');
            }
        }
        
        // Função para esconder indicador de digitação
        function hideTypingIndicator() {
            const typingIndicator = document.getElementById('typing-indicator');
            if (typingIndicator) {
                typingIndicator.classList.add('hidden');
            }
        }
        
        // Enviar mensagem com AJAX
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Verificar se o usuário está mutado
            const isMuted = messageInput.disabled;
            if (isMuted) {
                showNotification('Você está mutado nesta sala e não pode enviar mensagens.', 'warning');
                return;
            }
            
            const message = messageInput.value.trim();
            if (!message) return;
            
            // Desabilitar botão durante envio
            if (sendButton) {
                sendButton.disabled = true;
                sendButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            }
            
            // Criar elemento de mensagem temporário
            const messageElement = document.createElement('div');
            messageElement.className = 'flex space-x-3 justify-end mb-4';
            messageElement.innerHTML = `
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                        <i class="fas fa-user text-gray-600 text-sm"></i>
                    </div>
                </div>
                <div class="flex-1 max-w-xs lg:max-w-md">
                    <div class="bg-blue-100 rounded-lg px-4 py-2">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-gray-900">Você</span>
                            <span class="text-xs text-gray-500">Enviando...</span>
                        </div>
                        <p class="text-sm text-gray-700">${escapeHtml(message)}</p>
                    </div>
                </div>
            `;
            
            if (chatMessages) {
                chatMessages.appendChild(messageElement);
                scrollToBottom();
            }
            
            // Limpar input
            messageInput.value = '';
            
            // Enviar via AJAX
            fetch(messageForm.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    mensagem: message
                })
            })
            .then(response => {
                if (!response.ok) {
                    // Tentar ler a resposta JSON para obter a mensagem de erro
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || `Erro ${response.status}: ${response.statusText}`);
                    }).catch(() => {
                        throw new Error(`Erro ${response.status}: ${response.statusText}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Remover mensagem temporária e adicionar a real
                    messageElement.remove();
                    if (data.message) {
                        const realMessageElement = createMessageElement(data.message);
                        chatMessages.appendChild(realMessageElement);
                        scrollToBottom();
                        lastMessageId = Math.max(lastMessageId, data.message.id);
                    }
                    
                    // Mostrar notificação de sucesso
                    showNotification('Mensagem enviada com sucesso!', 'success');
                } else {
                    // Remover mensagem temporária se falhou
                    messageElement.remove();
                    showNotification('Erro ao enviar mensagem: ' + (data.message || 'Erro desconhecido'), 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                messageElement.remove();
                
                // Verificar se é erro de mute
                if (error.message.includes('mutado') || error.message.includes('403')) {
                    showNotification('Você está mutado nesta sala e não pode enviar mensagens.', 'warning');
                } else {
                    showNotification('Erro ao enviar mensagem: ' + error.message, 'error');
                }
            })
            .finally(() => {
                // Reabilitar botão
                if (sendButton) {
                    sendButton.disabled = false;
                    sendButton.innerHTML = '<i class="fas fa-paper-plane"></i>';
                }
            });
        });
        
        // Função para mostrar notificações
        function showNotification(message, type = 'info') {
            const toastContainer = document.getElementById('toast-container');
            const notification = document.createElement('div');
            notification.className = `p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                type === 'warning' ? 'bg-yellow-500 text-white' :
                'bg-blue-500 text-white'
            }`;
            
            // Ícone específico para mute
            let icon = 'info-circle';
            if (type === 'success') icon = 'check-circle';
            else if (type === 'error') icon = 'exclamation-circle';
            else if (type === 'warning') icon = 'exclamation-triangle';
            else if (message.includes('mutado')) icon = 'volume-mute';
            
            notification.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-${icon} mr-2"></i>
                        <span>${message}</span>
                    </div>
                    <button class="ml-4 text-white hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            toastContainer.appendChild(notification);
            
            // Animar entrada
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Remover após 8 segundos para notificações de mute
            const duration = message.includes('mutado') ? 8000 : 5000;
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, duration);
        }
        
        // Sistema de Emojis
        const emojiButton = document.getElementById('emoji-button');
        const emojiPanel = document.getElementById('emoji-panel');
        const emojiBtns = document.querySelectorAll('.emoji-btn');
        
        if (emojiButton && emojiPanel) {
            emojiButton.addEventListener('click', function() {
                emojiPanel.classList.toggle('hidden');
            });
            
            emojiBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const emoji = this.getAttribute('data-emoji');
                    const currentValue = messageInput.value;
                    const cursorPos = messageInput.selectionStart;
                    
                    messageInput.value = currentValue.slice(0, cursorPos) + emoji + currentValue.slice(cursorPos);
                    messageInput.focus();
                    messageInput.setSelectionRange(cursorPos + emoji.length, cursorPos + emoji.length);
                    
                    emojiPanel.classList.add('hidden');
                });
            });
            
            // Fechar painel ao clicar fora
            document.addEventListener('click', function(e) {
                if (!emojiButton.contains(e.target) && !emojiPanel.contains(e.target)) {
                    emojiPanel.classList.add('hidden');
                }
            });
        }
        
        // Atalhos de teclado
        document.addEventListener('keydown', function(e) {
            // Ctrl+Enter para enviar mensagem
            if (e.ctrlKey && e.key === 'Enter') {
                e.preventDefault();
                messageForm.dispatchEvent(new Event('submit'));
            }
            
            // Escape para fechar painel de emojis
            if (e.key === 'Escape') {
                emojiPanel.classList.add('hidden');
            }
        });
        
        // Atualizar mensagens a cada 3 segundos
        autoRefreshInterval = setInterval(updateMessages, 3000);
        
        // Pausar atualização quando a página não está visível
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                clearInterval(autoRefreshInterval);
            } else {
                autoRefreshInterval = setInterval(updateMessages, 3000);
            }
        });
        
        // Mostrar notificação de boas-vindas
        setTimeout(() => {
            showNotification('Bem-vindo ao chat! Use Ctrl+Enter para enviar mensagens rapidamente.', 'info');
        }, 1000);
        
        // Inicializar última mensagem ID
        const existingMessages = chatMessages.querySelectorAll('[data-message-id]');
        existingMessages.forEach(element => {
            const messageId = parseInt(element.getAttribute('data-message-id'));
            if (messageId > lastMessageId) {
                lastMessageId = messageId;
            }
        });
        
    } catch (error) {
        console.error('Erro ao inicializar chat:', error);
    }
});
</script>
@endpush
@endsection 