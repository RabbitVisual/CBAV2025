@props([
    'notification',
    'showActions' => true,
    'compact' => false,
    'interactive' => true
])

<div 
    class="notification-card {{ $compact ? 'p-3' : 'p-4' }} border border-gray-200 rounded-lg transition-all duration-200 hover:shadow-md"
    :class="{
        'bg-blue-50 border-blue-200': !notification.is_read,
        'bg-white': notification.is_read,
        'cursor-pointer': {{ $interactive ? 'true' : 'false' }}
    }"
    x-data="notificationCard({{ json_encode($notification) }})"
    @click="{{ $interactive ? 'handleClick()' : '' }}"
>
    <div class="flex items-start space-x-3">
        <!-- Icon -->
        <div class="flex-shrink-0">
            <div class="w-{{ $compact ? '8' : '10' }} h-{{ $compact ? '8' : '10' }} rounded-full flex items-center justify-center text-white {{ $compact ? 'text-xs' : 'text-sm' }}" 
                 :class="getIconClass()">
                <i :class="getIcon()"></i>
            </div>
        </div>

        <!-- Content -->
        <div class="flex-1 min-w-0">
            <!-- Header -->
            <div class="flex items-start justify-between mb-2">
                <div class="flex items-center space-x-2">
                    <h3 class="{{ $compact ? 'text-sm' : 'text-base' }} font-medium text-gray-900 truncate"
                        :class="{ 'font-semibold': !notification.is_read }"
                        x-text="notification.title">
                    </h3>
                    
                    <!-- Unread Indicator -->
                    <div x-show="!notification.is_read" 
                         class="w-2 h-2 bg-blue-500 rounded-full animate-pulse">
                    </div>
                </div>

                <div class="flex items-center space-x-2 ml-4">
                    <!-- Priority Badge -->
                    <span x-show="notification.priority === 'urgent' || notification.priority === 'high'"
                          class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                          :class="getPriorityClass()"
                          x-text="getPriorityText()">
                    </span>

                    <!-- Type Badge -->
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                          :class="getTypeBadgeClass()"
                          x-text="getTypeText()">
                    </span>

                    <!-- Time -->
                    <span class="text-xs text-gray-500 whitespace-nowrap" 
                          x-text="notification.time_ago">
                    </span>
                </div>
            </div>

            <!-- Message -->
            <p class="{{ $compact ? 'text-xs' : 'text-sm' }} text-gray-600 mb-3"
               :class="{ 'line-clamp-2': {{ $compact ? 'true' : 'false' }} }"
               x-text="notification.message">
            </p>

            <!-- Quiz Data (if applicable) -->
            <div x-show="notification.category === 'quiz' && notification.data" 
                 class="mb-3 p-2 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-100">
                <div class="flex items-center space-x-4 text-xs">
                    <div x-show="notification.data.score" class="flex items-center space-x-1">
                        <i class="fas fa-trophy text-yellow-500"></i>
                        <span class="font-medium">Pontuação:</span>
                        <span x-text="notification.data.score"></span>
                    </div>
                    <div x-show="notification.data.percentage" class="flex items-center space-x-1">
                        <i class="fas fa-percent text-green-500"></i>
                        <span class="font-medium">Acertos:</span>
                        <span x-text="notification.data.percentage + '%'"></span>
                    </div>
                    <div x-show="notification.data.category" class="flex items-center space-x-1">
                        <i class="fas fa-tag text-blue-500"></i>
                        <span class="font-medium">Categoria:</span>
                        <span x-text="notification.data.category"></span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            @if($showActions)
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <!-- Action Button -->
                    <button x-show="notification.action_url"
                            @click.stop="handleAction()"
                            class="inline-flex items-center px-3 py-1 border border-blue-300 rounded-md text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors">
                        <i class="fas fa-external-link-alt mr-1"></i>
                        <span x-text="notification.action_text || 'Ver mais'"></span>
                    </button>

                    <!-- Mark as Read/Unread -->
                    <button @click.stop="toggleRead()"
                            class="inline-flex items-center px-2 py-1 text-xs text-gray-600 hover:text-gray-800 transition-colors"
                            :title="notification.is_read ? 'Marcar como não lida' : 'Marcar como lida'">
                        <i :class="notification.is_read ? 'fas fa-envelope' : 'fas fa-envelope-open'" class="mr-1"></i>
                        <span x-text="notification.is_read ? 'Não lida' : 'Lida'"></span>
                    </button>

                    <!-- Star/Unstar -->
                    <button @click.stop="toggleStar()"
                            class="inline-flex items-center px-2 py-1 text-xs transition-colors"
                            :class="notification.is_starred ? 'text-yellow-600 hover:text-yellow-800' : 'text-gray-600 hover:text-gray-800'"
                            :title="notification.is_starred ? 'Remover dos favoritos' : 'Adicionar aos favoritos'">
                        <i :class="notification.is_starred ? 'fas fa-star' : 'far fa-star'" class="mr-1"></i>
                        <span x-text="notification.is_starred ? 'Favorito' : 'Favoritar'"></span>
                    </button>
                </div>

                <!-- More Actions Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click.stop="open = !open"
                            class="p-1 text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>

                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-1 w-48 bg-white rounded-md shadow-lg border border-gray-200 z-10"
                         style="display: none;">
                        <div class="py-1">
                            <button @click.stop="archiveNotification(); open = false"
                                    class="block w-full text-left px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-archive mr-2"></i>
                                Arquivar
                            </button>
                            <button @click.stop="copyNotification(); open = false"
                                    class="block w-full text-left px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-copy mr-2"></i>
                                Copiar texto
                            </button>
                            <button x-show="notification.action_url"
                                    @click.stop="shareNotification(); open = false"
                                    class="block w-full text-left px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-share mr-2"></i>
                                Compartilhar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Loading Overlay -->
    <div x-show="loading" 
         class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center rounded-lg">
        <div class="flex items-center space-x-2">
            <svg class="animate-spin h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-xs text-gray-600">Processando...</span>
        </div>
    </div>
</div>

<script>
function notificationCard(initialNotification) {
    return {
        notification: initialNotification,
        loading: false,

        handleClick() {
            if (!this.notification.is_read) {
                this.markAsRead();
            }

            if (this.notification.action_url) {
                this.handleAction();
            }
        },

        async markAsRead() {
            if (this.notification.is_read) return;
            
            this.loading = true;
            try {
                const response = await fetch(`/member/notifications/${this.notification.id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    this.notification.is_read = true;
                    this.$dispatch('notification-read', { id: this.notification.id });
                }
            } catch (error) {
                console.error('Erro ao marcar como lida:', error);
            } finally {
                this.loading = false;
            }
        },

        async toggleRead() {
            this.loading = true;
            try {
                const endpoint = this.notification.is_read ? 'unread' : 'read';
                const response = await fetch(`/member/notifications/${this.notification.id}/${endpoint}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    this.notification.is_read = !this.notification.is_read;
                    this.$dispatch('notification-updated', { id: this.notification.id });
                }
            } catch (error) {
                console.error('Erro ao alterar status de leitura:', error);
            } finally {
                this.loading = false;
            }
        },

        async toggleStar() {
            this.loading = true;
            try {
                const response = await fetch(`/member/notifications/${this.notification.id}/star`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    this.notification.is_starred = data.is_starred;
                    this.$dispatch('notification-updated', { id: this.notification.id });
                }
            } catch (error) {
                console.error('Erro ao favoritar:', error);
            } finally {
                this.loading = false;
            }
        },

        async archiveNotification() {
            this.loading = true;
            try {
                const response = await fetch(`/member/notifications/${this.notification.id}/archive`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    this.$dispatch('notification-archived', { id: this.notification.id });
                }
            } catch (error) {
                console.error('Erro ao arquivar:', error);
            } finally {
                this.loading = false;
            }
        },

        handleAction() {
            if (this.notification.action_url) {
                // Registrar clique na ação
                fetch(`/member/notifications/${this.notification.id}/action`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        action_type: 'click',
                        url: this.notification.action_url
                    })
                });

                window.open(this.notification.action_url, '_blank');
            }
        },

        copyNotification() {
            const text = `${this.notification.title}\n\n${this.notification.message}`;
            navigator.clipboard.writeText(text).then(() => {
                this.$dispatch('show-toast', { 
                    message: 'Texto copiado para a área de transferência!', 
                    type: 'success' 
                });
            });
        },

        shareNotification() {
            if (navigator.share && this.notification.action_url) {
                navigator.share({
                    title: this.notification.title,
                    text: this.notification.message,
                    url: this.notification.action_url
                });
            } else {
                this.copyNotification();
            }
        },

        getIconClass() {
            const classes = {
                'info': 'bg-blue-500',
                'success': 'bg-green-500',
                'warning': 'bg-yellow-500',
                'error': 'bg-red-500',
                'urgent': 'bg-red-600'
            };
            return classes[this.notification.type] || 'bg-gray-500';
        },

        getIcon() {
            return this.notification.icon || this.getDefaultIcon();
        },

        getDefaultIcon() {
            const icons = {
                'info': 'fas fa-info-circle',
                'success': 'fas fa-check-circle',
                'warning': 'fas fa-exclamation-triangle',
                'error': 'fas fa-times-circle',
                'urgent': 'fas fa-exclamation-triangle'
            };
            return icons[this.notification.type] || 'fas fa-bell';
        },

        getPriorityClass() {
            const classes = {
                'urgent': 'bg-red-100 text-red-800',
                'high': 'bg-orange-100 text-orange-800',
                'normal': 'bg-blue-100 text-blue-800',
                'low': 'bg-gray-100 text-gray-800'
            };
            return classes[this.notification.priority] || 'bg-gray-100 text-gray-800';
        },

        getPriorityText() {
            const texts = {
                'urgent': 'Urgente',
                'high': 'Alta',
                'normal': 'Normal',
                'low': 'Baixa'
            };
            return texts[this.notification.priority] || 'Normal';
        },

        getTypeBadgeClass() {
            const classes = {
                'info': 'bg-blue-100 text-blue-800',
                'success': 'bg-green-100 text-green-800',
                'warning': 'bg-yellow-100 text-yellow-800',
                'error': 'bg-red-100 text-red-800',
                'urgent': 'bg-red-100 text-red-800'
            };
            return classes[this.notification.type] || 'bg-gray-100 text-gray-800';
        },

        getTypeText() {
            const texts = {
                'info': 'Info',
                'success': 'Sucesso',
                'warning': 'Aviso',
                'error': 'Erro',
                'urgent': 'Urgente'
            };
            return texts[this.notification.type] || 'Info';
        }
    }
}
</script>

<style>
.notification-card {
    position: relative;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>