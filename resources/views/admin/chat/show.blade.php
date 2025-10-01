@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-comments mr-3" style="color: {{ $room->cor }}"></i>
                    {{ $room->nome }} - Administração
                </h1>
                <p class="text-gray-600 mt-2">{{ $room->descricao }}</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.chat.manage') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar
                </a>
                <a href="{{ route('admin.chat.manage') }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-cog mr-2"></i>
                    Administração Completa
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Lista de Salas -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-list text-blue-600 mr-2"></i>
                    Todas as Salas
                </h2>
                
                @if($userRooms->count() > 0)
                    <div class="space-y-3">
                        @foreach($userRooms as $userRoom)
                            <a href="{{ route('admin.chat.show', $userRoom->id) }}" 
                               class="block border rounded-lg p-3 hover:bg-gray-50 transition-colors {{ $userRoom->id == $room->id ? 'bg-blue-50 border-blue-300' : '' }}">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm"
                                         style="background-color: {{ $userRoom->cor }}">
                                        <i class="{{ $userRoom->icone }}"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-medium text-gray-900">{{ $userRoom->nome }}</h3>
                                        <p class="text-xs text-gray-500">{{ $userRoom->tipo }}</p>
                                    </div>
                                    @php
                                        $unreadCount = 0;
                                        try {
                                            $unreadCount = $userRoom->unreadMessagesCount(Auth::id());
                                        } catch (\Exception $e) {
                                            $unreadCount = 0;
                                        }
                                    @endphp
                                    @if($unreadCount > 0)
                                        <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                            {{ $unreadCount }}
                                        </span>
                                    @endif
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

            <!-- Informações da Sala -->
            <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-info-circle text-green-600 mr-2"></i>
                    Informações da Sala
                </h2>
                
                <div class="space-y-3">
                    <div>
                        <span class="text-sm text-gray-600">Nome:</span>
                        <p class="font-medium">{{ $room->nome }}</p>
                    </div>
                    
                    <div>
                        <span class="text-sm text-gray-600">Tipo:</span>
                        <p class="font-medium">{{ ucfirst($room->tipo) }}</p>
                    </div>
                    
                    <div>
                        <span class="text-sm text-gray-600">Status:</span>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                            @if($room->ativo) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                            {{ $room->ativo ? 'Ativa' : 'Inativa' }}
                        </span>
                    </div>
                    
                    <div>
                        <span class="text-sm text-gray-600">Participantes:</span>
                        <p class="font-medium">{{ $participants->count() }}</p>
                    </div>
                    
                    @if($room->max_participantes)
                        <div>
                            <span class="text-sm text-gray-600">Limite:</span>
                            <p class="font-medium">{{ $participants->count() }}/{{ $room->max_participantes }}</p>
                        </div>
                    @endif
                    
                    <div>
                        <span class="text-sm text-gray-600">Criada em:</span>
                        <p class="font-medium">{{ $room->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Participantes -->
            <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-users text-purple-600 mr-2"></i>
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
                            @if($participant->isMuted())
                                <i class="fas fa-volume-mute text-orange-500 text-sm"></i>
                            @endif
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
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white"
                                 style="background-color: {{ $room->cor }}">
                                <i class="{{ $room->icone }}"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $room->nome }}</h3>
                                <p class="text-sm text-gray-500">{{ $participants->count() }} participantes</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($room->tipo === 'publico') bg-green-100 text-green-800
                                @elseif($room->tipo === 'privado') bg-yellow-100 text-yellow-800
                                @elseif($room->tipo === 'ministerio') bg-blue-100 text-blue-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($room->tipo) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Mensagens -->
                <div id="chat-messages" class="px-6 py-4 h-96 overflow-y-auto">
                    @foreach($messages as $message)
                        <div class="flex space-x-3 {{ $message->user_id == Auth::id() ? 'justify-end' : 'justify-start' }} mb-4 message-item" data-message-id="{{ $message->id }}">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600 text-sm"></i>
                                </div>
                            </div>
                            <div class="flex-1 max-w-xs lg:max-w-md">
                                <div class="{{ $message->user_id == Auth::id() ? 'bg-blue-100' : 'bg-gray-100' }} rounded-lg px-4 py-2 relative group">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-sm font-medium text-gray-900">{{ $message->user->name }}</span>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-xs text-gray-500">{{ $message->created_at->format('H:i') }}</span>
                                            @if(Auth::user()->hasRole(['admin', 'super_admin']) || $message->user_id == Auth::id())
                                                <button onclick="deleteMessage({{ $room->id }}, {{ $message->id }})" 
                                                        class="text-red-600 hover:text-red-800 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <i class="fas fa-trash text-xs"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-700">{{ $message->mensagem }}</p>
                                    @if($message->editado)
                                        <p class="text-xs text-gray-500 mt-1">(editado)</p>
                                    @endif
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
                    <form id="message-form" action="{{ route('admin.chat.send', $room->id) }}" method="POST" class="flex space-x-3">
                        @csrf
                        <div class="flex-1 relative">
                            <input type="text" 
                                   id="message-input" 
                                   name="mensagem" 
                                   placeholder="Digite sua mensagem..."
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <button type="button" id="emoji-button" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-smile"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const chatMessages = document.getElementById('chat-messages');
    const emojiButton = document.getElementById('emoji-button');
    const emojiPanel = document.getElementById('emoji-panel');
    const emojiBtns = document.querySelectorAll('.emoji-btn');
    
    // Sistema de Emojis
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
});

// Função para excluir mensagem
function deleteMessage(roomId, messageId) {
    if (!confirm('Tem certeza que deseja excluir esta mensagem? Esta ação não pode ser desfeita.')) {
        return;
    }
    
    fetch(`/admin/chat/${roomId}/messages/${messageId}`, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remover a mensagem do DOM
            const messageElement = document.querySelector(`[data-message-id="${messageId}"]`);
            if (messageElement) {
                messageElement.remove();
            }
            
            // Mostrar notificação de sucesso
            showNotification('Mensagem excluída com sucesso!', 'success');
        } else {
            showNotification('Erro: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showNotification('Erro ao excluir mensagem.', 'error');
    });
}

// Função para mostrar notificações
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-md ${
        type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' :
        type === 'error' ? 'bg-red-100 border border-red-400 text-red-700' :
        'bg-blue-100 border border-blue-400 text-blue-700'
    }`;
    
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} mr-2"></i>
            <span>${message}</span>
            <button class="ml-auto text-gray-400 hover:text-gray-600" onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remover após 5 segundos
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}
</script>
@endpush
@endsection 