@extends('layouts.admin')

@section('page-content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-chart-bar text-purple-600 mr-3"></i>
                    Estatísticas do Chat
                </h1>
                <p class="text-gray-600 mt-2">Relatórios e análises do sistema de chat</p>
            </div>
            <a href="{{ route('admin.chat.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar
            </a>
        </div>
    </div>

    <!-- Estatísticas Gerais -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
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
        
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-volume-mute text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $mutedUsers }}</div>
                    <div class="text-sm text-gray-500">Usuários Mutados</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Mensagens por Tipo -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-chart-pie text-blue-600 mr-2"></i>
                Mensagens por Tipo
            </h3>
            
            <div class="space-y-4">
                @foreach($messagesByType as $type)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded-full mr-3
                                @if($type->tipo === 'texto') bg-blue-500
                                @elseif($type->tipo === 'imagem') bg-green-500
                                @elseif($type->tipo === 'arquivo') bg-purple-500
                                @else bg-gray-500
                                @endif"></div>
                            <span class="text-sm font-medium text-gray-900">
                                {{ ucfirst($type->tipo) }}
                            </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-bold text-gray-900">{{ $type->total }}</span>
                            <span class="text-xs text-gray-500">
                                ({{ $totalMessages > 0 ? round(($type->total / $totalMessages) * 100, 1) : 0 }}%)
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Salas por Tipo -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-chart-pie text-green-600 mr-2"></i>
                Salas por Tipo
            </h3>
            
            <div class="space-y-4">
                @foreach($roomsByType as $type)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded-full mr-3
                                @if($type->tipo === 'publico') bg-green-500
                                @elseif($type->tipo === 'privado') bg-yellow-500
                                @elseif($type->tipo === 'ministerio') bg-blue-500
                                @else bg-red-500
                                @endif"></div>
                            <span class="text-sm font-medium text-gray-900">
                                {{ ucfirst($type->tipo) }}
                            </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-bold text-gray-900">{{ $type->total }}</span>
                            <span class="text-xs text-gray-500">
                                ({{ $totalRooms > 0 ? round(($type->total / $totalRooms) * 100, 1) : 0 }}%)
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Gráfico de Mensagens por Dia -->
    <div class="bg-white rounded-lg shadow-lg p-6 mt-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-chart-line text-purple-600 mr-2"></i>
            Mensagens por Dia (Últimos 30 dias)
        </h3>
        
        <div class="h-64">
            <canvas id="messagesChart"></canvas>
        </div>
        
        @if($messagesByDay->isEmpty())
            <div class="text-center py-8">
                <i class="fas fa-chart-line text-gray-400 text-4xl mb-4"></i>
                <p class="text-gray-500">Nenhuma mensagem nos últimos 30 dias</p>
            </div>
        @endif
    </div>

    <!-- Top Participantes -->
    <div class="bg-white rounded-lg shadow-lg p-6 mt-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-trophy text-yellow-600 mr-2"></i>
            Top Participantes
        </h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Posição
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Usuário
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Mensagens
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Salas Ativas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Última Atividade
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Aqui você pode adicionar dados dos top participantes -->
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <div class="flex items-center">
                                <span class="w-8 h-8 bg-yellow-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">1</span>
                                -
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">-</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">-</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">-</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">-</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Salas Mais Ativas -->
    <div class="bg-white rounded-lg shadow-lg p-6 mt-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-fire text-red-600 mr-2"></i>
            Salas Mais Ativas
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($topRooms as $room)
                <div class="border rounded-lg p-4">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white">
                            <i class="fas fa-comments text-sm"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $room->nome }}</h4>
                            <p class="text-xs text-gray-500">{{ ucfirst($room->tipo) }}</p>
                        </div>
                    </div>
                    <div class="text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Mensagens:</span>
                            <span class="font-medium">{{ $room->messages_count }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Participantes:</span>
                            <span class="font-medium">{{ $room->participants()->where('ativo', true)->count() }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-8">
                    <i class="fas fa-comments text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-500">Nenhuma sala ativa encontrada</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Usuários Mais Ativos -->
    <div class="bg-white rounded-lg shadow-lg p-6 mt-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-user-friends text-green-600 mr-2"></i>
            Usuários Mais Ativos
        </h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Usuário
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Mensagens
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Participação
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($topUsers as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-gray-600 text-sm"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $user->user->name ?? 'Usuário Desconhecido' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $user->user->email ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $user->message_count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $totalMessages > 0 ? round(($user->message_count / $totalMessages) * 100, 1) : 0 }}%
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                Nenhum usuário ativo encontrado
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    try {
        // Dados para o gráfico
        const messagesData = @json($messagesByDay ?? collect());
        
        if (messagesData && messagesData.length > 0) {
            const labels = messagesData.map(item => item.date);
            const data = messagesData.map(item => item.total);
            
            // Criar gráfico
            const ctx = document.getElementById('messagesChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Mensagens',
                            data: data,
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
        }
    } catch (error) {
        console.error('Erro ao carregar gráfico:', error);
    }
});
</script>
@endpush
@endsection 