@extends('layouts.member')

@section('title', 'Notificações')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Notificações</h1>
        <p class="text-gray-600">Acompanhe suas notificações e alertas importantes</p>
    </div>

    <!-- Filtros e Estatísticas -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
        <!-- Estatísticas -->
        <div class="lg:col-span-3">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <i class="fas fa-bell text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $notifications->count() ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100 text-red-600">
                            <i class="fas fa-exclamation-circle text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Não Lidas</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['unread'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Lidas</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['read'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtros</h3>
            <form method="GET" action="{{ route('member.notifications.index') }}" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                    <select name="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todos os tipos</option>
                        <option value="system" {{ request('type') == 'system' ? 'selected' : '' }}>Sistema</option>
                        <option value="quiz" {{ request('type') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                        <option value="achievement" {{ request('type') == 'achievement' ? 'selected' : '' }}>Conquista</option>
                        <option value="reminder" {{ request('type') == 'reminder' ? 'selected' : '' }}>Lembrete</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todos</option>
                        <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Não lidas</option>
                        <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Lidas</option>
                    </select>
                </div>
                
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors">
                    Aplicar Filtros
                </button>
            </form>
        </div>
    </div>

    <!-- Lista de Notificações -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">Suas Notificações</h2>
                @if(isset($paginatedNotifications) && $paginatedNotifications->count() > 0)
                    <button onclick="markAllAsRead()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Marcar todas como lidas
                    </button>
                @endif
            </div>
        </div>
        
        <div class="divide-y divide-gray-200">
            @forelse($paginatedNotifications ?? [] as $notification)
                <div class="p-6 hover:bg-gray-50 transition-colors {{ !$notification->is_read ? 'bg-blue-50' : '' }}">
                    <div class="flex items-start space-x-4">
                        <!-- Ícone -->
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $notification->type_color ?? 'bg-gray-100' }}">
                                <i class="{{ $notification->type_icon ?? 'fas fa-bell' }} text-white"></i>
                            </div>
                        </div>
                        
                        <!-- Conteúdo -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-medium text-gray-900 truncate">
                                    {{ $notification->title }}
                                </h3>
                                <div class="flex items-center space-x-2">
                                    @if(!$notification->is_read)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Nova
                                        </span>
                                    @endif
                                    <span class="text-xs text-gray-500">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                            
                            <p class="mt-1 text-sm text-gray-600">
                                {{ $notification->message }}
                            </p>
                            
                            @if($notification->type)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $notification->type_badge_class ?? 'bg-gray-100 text-gray-800' }} mt-2">
                                    {{ ucfirst($notification->type) }}
                                </span>
                            @endif
                        </div>
                        
                        <!-- Ações -->
                        <div class="flex-shrink-0">
                            @if(!$notification->is_read)
                                <button onclick="markAsRead({{ $notification->id }})" 
                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Marcar como lida
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 text-gray-400">
                        <i class="fas fa-bell-slash text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma notificação encontrada</h3>
                    <p class="text-gray-600">Você não possui notificações no momento.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Paginação -->
    @if(isset($paginatedNotifications) && $paginatedNotifications->count() > 0)
        <div class="mt-6 flex justify-center">
            <!-- Implementar paginação manual se necessário -->
        </div>
    @endif
</div>

@push('scripts')
<script>
function markAsRead(notificationId) {
    fetch(`/member/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Erro:', error);
    });
}

function markAllAsRead() {
    fetch('/member/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Erro:', error);
    });
}
</script>
@endpush
@endsection