@extends('layouts.admin')

@section('page-content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-comments text-blue-600 mr-3"></i>
                    Chat da Igreja - Administração
                </h1>
                <p class="text-gray-600 mt-2">Gerencie e monitore o sistema de chat da igreja</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.chat.manage') }}" 
                   class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-broom mr-2"></i>
                    Administração Completa
                </a>
                <a href="{{ route('admin.chat.stats') }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Estatísticas
                </a>
            </div>
        </div>
    </div>

    <!-- Estatísticas Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-comments text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $totalRooms }}</div>
                    <div class="text-sm text-gray-500">Total de Salas</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $activeRooms }}</div>
                    <div class="text-sm text-gray-500">Salas Ativas</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-comment-dots text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $totalMessages }}</div>
                    <div class="text-sm text-gray-500">Total de Mensagens</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-orange-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $totalParticipants }}</div>
                    <div class="text-sm text-gray-500">Participantes</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Salas do Administrador -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-users text-blue-600 mr-2"></i>
                    Minhas Salas
                </h2>
                @if($userRooms->count() > 0)
                    <div class="space-y-3">
                        @foreach($userRooms as $room)
                            <div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white"
                                             style="background-color: {{ $room->cor }}">
                                            <i class="{{ $room->icone }}"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">{{ $room->nome }}</h3>
                                            <p class="text-sm text-gray-500">{{ $room->tipo }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @if($room->unreadMessagesCount(Auth::id()) > 0)
                                            <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                                {{ $room->unreadMessagesCount(Auth::id()) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                @if($room->lastMessage)
                                    <div class="mt-2 text-sm text-gray-600">
                                        <span class="font-medium">{{ $room->lastMessage->user->name }}:</span>
                                        {{ Str::limit($room->lastMessage->mensagem, 50) }}
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1">
                                        {{ $room->lastMessage->created_at->diffForHumans() }}
                                    </div>
                                @endif
                                <div class="mt-3">
                                    <a href="{{ route('admin.chat.show', $room->id) }}"
                                       class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-comment mr-2"></i>
                                        Entrar
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-comments text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">Você ainda não participa de nenhuma sala de chat.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Todas as Salas -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-list text-green-600 mr-2"></i>
                    Todas as Salas
                </h2>
                @if($availableRooms->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($availableRooms as $room)
                            <div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center space-x-3 mb-3">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white"
                                         style="background-color: {{ $room->cor }}">
                                        <i class="{{ $room->icone }}"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $room->nome }}</h3>
                                        <p class="text-sm text-gray-500">{{ $room->tipo }}</p>
                                    </div>
                                </div>
                                @if($room->descricao)
                                    <p class="text-sm text-gray-600 mb-3">{{ $room->descricao }}</p>
                                @endif
                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-gray-500">
                                        <i class="fas fa-users mr-1"></i>
                                        {{ $room->participants->where('ativo', true)->count() }} participantes
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.chat.show', $room->id) }}"
                                           class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                                            <i class="fas fa-eye mr-2"></i>
                                            Ver
                                        </a>
                                        <a href="{{ route('admin.chat.edit', $room->id) }}"
                                           class="inline-flex items-center px-3 py-2 bg-yellow-600 text-white text-sm rounded-lg hover:bg-yellow-700 transition-colors">
                                            <i class="fas fa-edit mr-2"></i>
                                            Editar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-info-circle text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">Não há salas disponíveis no momento.</p>
                    </div>
                @endif
            </div>

            <!-- Ações Rápidas -->
            <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                    Ações Rápidas
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('admin.chat.create') }}" 
                       class="flex items-center p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-plus text-green-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Nova Sala</h3>
                            <p class="text-sm text-gray-500">Criar sala de chat</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.chat.manage') }}" 
                       class="flex items-center p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-cog text-purple-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Gerenciar</h3>
                            <p class="text-sm text-gray-500">Gerenciar salas</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.chat.stats') }}" 
                       class="flex items-center p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-chart-bar text-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Estatísticas</h3>
                            <p class="text-sm text-gray-500">Ver relatórios</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Dicas de Administração -->
    <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg p-6 mt-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-3">
            <i class="fas fa-lightbulb text-yellow-600 mr-2"></i>
            Dicas de Administração do Chat
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
            <div>
                <h4 class="font-semibold text-gray-900 mb-2">✅ Boas Práticas:</h4>
                <ul class="space-y-1">
                    <li>• Monitore regularmente as salas</li>
                    <li>• Crie salas específicas para ministérios</li>
                    <li>• Mantenha salas organizadas por propósito</li>
                    <li>• Use o sistema de moderação quando necessário</li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-gray-900 mb-2">⚠️ Atenção:</h4>
                <ul class="space-y-1">
                    <li>• Verifique conteúdo inadequado</li>
                    <li>• Monitore spam ou propaganda</li>
                    <li>• Gerencie participantes problemáticos</li>
                    <li>• Mantenha backup das conversas importantes</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh das estatísticas a cada 30 segundos
    setInterval(function() {
        // Aqui você pode adicionar uma chamada AJAX para atualizar as estatísticas
        console.log('Atualizando estatísticas...');
    }, 30000);
});
</script>
@endpush
@endsection 