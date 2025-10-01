<div class="relative" x-data="notificationDropdown()" x-init="init()">
    <!-- Botão de Notificações -->
    <button 
        @click="toggleDropdown()" 
        class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        :class="{ 'bg-gray-100 text-gray-900': isOpen }"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-3 3-3-3h1v-4a4 4 0 00-8 0v4H6a2 2 0 01-2-2V7a2 2 0 012-2h11a2 2 0 012 2v4.586l-2-2V7H6v8h2v-4a2 2 0 014 0v4h3z"></path>
        </svg>
        
        <!-- Badge de Contagem -->
        <span 
            x-show="unreadCount > 0" 
            x-text="unreadCount > 99 ? '99+' : unreadCount"
            class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center min-w-[20px] animate-pulse"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-0"
            x-transition:enter-end="opacity-100 scale-100"
        ></span>
    </button>

    <!-- Dropdown Menu -->
    <div 
        x-show="isOpen" 
        @click.away="closeDropdown()"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 transform translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 transform translate-y-2"
        class="absolute right-0 mt-2 w-96 bg-white rounded-xl shadow-2xl border border-gray-200 z-50 max-h-[80vh] overflow-hidden"
        style="display: none;"
    >
        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Notificações</h3>
                <div class="flex items-center space-x-2">
                    <span 
                        x-show="unreadCount > 0"
                        class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full"
                        x-text="unreadCount + ' não lida' + (unreadCount > 1 ? 's' : '')"
                    ></span>
                    <button 
                        @click="markAllAsRead()"
                        x-show="unreadCount > 0"
                        class="text-xs text-blue-600 hover:text-blue-800 font-medium transition-colors"
                        title="Marcar todas como lidas"
                    >
                        Marcar todas
                    </button>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="p-8 text-center">
            <div class="inline-flex items-center space-x-2">
                <svg class="animate-spin h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-600">Carregando...</span>
            </div>
        </div>

        <!-- Notifications List -->
        <div x-show="!loading" class="max-h-96 overflow-y-auto">
            <!-- Empty State -->
            <div x-show="notifications.length === 0" class="p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM11 19H6a2 2 0 01-2-2V7a2 2 0 012-2h5m5 0v6m0 0l3-3m-3 3l-3-3"></path>
                </svg>
                <p class="text-gray-500 font-medium">Nenhuma notificação</p>
                <p class="text-gray-400 text-sm mt-1">Você está em dia! 🎉</p>
            </div>

            <!-- Notification Items -->
            <template x-for="notification in notifications" :key="notification.id">
                <div 
                    class="border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer"
                    :class="{ 'bg-blue-50': !notification.is_read }"
                    @click="handleNotificationClick(notification)"
                >
                    <div class="p-4">
                        <div class="flex items-start space-x-3">
                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                <div 
                                    class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm"
                                    :class="getNotificationIconClass(notification.type)"
                                >
                                    <i :class="notification.icon || getDefaultIcon(notification.type)"></i>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <h4 
                                        class="text-sm font-medium text-gray-900 truncate"
                                        :class="{ 'font-semibold': !notification.is_read }"
                                        x-text="notification.title"
                                    ></h4>
                                    <div class="flex items-center space-x-1 ml-2">
                                        <!-- Unread Indicator -->
                                        <div 
                                            x-show="!notification.is_read"
                                            class="w-2 h-2 bg-blue-500 rounded-full"
                                        ></div>
                                        <!-- Priority Badge -->
                                        <span 
                                            x-show="notification.priority === 'urgent' || notification.priority === 'high'"
                                            class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium"
                                            :class="getPriorityClass(notification.priority)"
                                            x-text="getPriorityText(notification.priority)"
                                        ></span>
                                    </div>
                                </div>
                                
                                <p 
                                    class="text-sm text-gray-600 mt-1 line-clamp-2"
                                    x-text="notification.message"
                                ></p>
                                
                                <div class="flex items-center justify-between mt-2">
                                    <span 
                                        class="text-xs text-gray-500"
                                        x-text="notification.time_ago"
                                    ></span>
                                    
                                    <!-- Action Button -->
                                    <button 
                                        x-show="notification.action_url"
                                        @click.stop="handleAction(notification)"
                                        class="text-xs text-blue-600 hover:text-blue-800 font-medium transition-colors"
                                        x-text="notification.action_text || 'Ver mais'"
                                    ></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Footer -->
        <div class="px-4 py-3 border-t border-gray-100 bg-gray-50">
            <div class="flex items-center justify-between">
                <a 
                    href="<?php echo e(route('member.notifications.index')); ?>"
                    class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors"
                >
                    Ver todas as notificações
                </a>
                <button 
                    @click="refreshNotifications()"
                    class="text-sm text-gray-600 hover:text-gray-800 transition-colors"
                    :disabled="loading"
                >
                    <i class="fas fa-sync-alt" :class="{ 'animate-spin': loading }"></i>
                    Atualizar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function notificationDropdown() {
    return {
        isOpen: false,
        loading: false,
        notifications: [],
        unreadCount: 0,
        refreshInterval: null,

        init() {
            this.loadNotifications();
            this.startAutoRefresh();
        },

        toggleDropdown() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.loadNotifications();
            }
        },

        closeDropdown() {
            this.isOpen = false;
        },

        async loadNotifications() {
            this.loading = true;
            try {
                const response = await fetch('<?php echo e(route("member.notifications.header")); ?>', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    this.notifications = data.notifications || [];
                    this.unreadCount = data.unread_count || 0;
                }
            } catch (error) {
                console.error('Erro ao carregar notificações:', error);
            } finally {
                this.loading = false;
            }
        },

        async markAsRead(notificationId) {
            try {
                const response = await fetch(`/member/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    // Atualizar localmente
                    const notification = this.notifications.find(n => n.id === notificationId);
                    if (notification && !notification.is_read) {
                        notification.is_read = true;
                        this.unreadCount = Math.max(0, this.unreadCount - 1);
                    }
                }
            } catch (error) {
                console.error('Erro ao marcar como lida:', error);
            }
        },

        async markAllAsRead() {
            try {
                const response = await fetch('<?php echo e(route("member.notifications.mark-all-read")); ?>', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    this.notifications.forEach(n => n.is_read = true);
                    this.unreadCount = 0;
                }
            } catch (error) {
                console.error('Erro ao marcar todas como lidas:', error);
            }
        },

        handleNotificationClick(notification) {
            // Marcar como lida se não estiver
            if (!notification.is_read) {
                this.markAsRead(notification.id);
            }

            // Redirecionar se houver URL de ação
            if (notification.action_url) {
                window.location.href = notification.action_url;
            }
        },

        handleAction(notification) {
            if (notification.action_url) {
                // Registrar clique na ação
                fetch(`/member/notifications/${notification.id}/action`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        action_type: 'click',
                        url: notification.action_url
                    })
                });

                window.location.href = notification.action_url;
            }
        },

        refreshNotifications() {
            this.loadNotifications();
        },

        startAutoRefresh() {
            // Atualizar a cada 30 segundos
            this.refreshInterval = setInterval(() => {
                if (!this.isOpen) {
                    this.loadNotifications();
                }
            }, 30000);
        },

        getNotificationIconClass(type) {
            const classes = {
                'info': 'bg-blue-500',
                'success': 'bg-green-500',
                'warning': 'bg-yellow-500',
                'error': 'bg-red-500',
                'urgent': 'bg-red-600'
            };
            return classes[type] || 'bg-gray-500';
        },

        getDefaultIcon(type) {
            const icons = {
                'info': 'fas fa-info-circle',
                'success': 'fas fa-check-circle',
                'warning': 'fas fa-exclamation-triangle',
                'error': 'fas fa-times-circle',
                'urgent': 'fas fa-exclamation-triangle'
            };
            return icons[type] || 'fas fa-bell';
        },

        getPriorityClass(priority) {
            const classes = {
                'urgent': 'bg-red-100 text-red-800',
                'high': 'bg-orange-100 text-orange-800',
                'normal': 'bg-blue-100 text-blue-800',
                'low': 'bg-gray-100 text-gray-800'
            };
            return classes[priority] || 'bg-gray-100 text-gray-800';
        },

        getPriorityText(priority) {
            const texts = {
                'urgent': 'Urgente',
                'high': 'Alta',
                'normal': 'Normal',
                'low': 'Baixa'
            };
            return texts[priority] || 'Normal';
        }
    }
}
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style><?php /**PATH C:\wamp64\www\CBAV2025\resources\views/components/notification-dropdown.blade.php ENDPATH**/ ?>