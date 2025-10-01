@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-comments text-blue-600 mr-3"></i>
                    Administração do Chat
                </h1>
                <p class="text-gray-600 mt-2">Gerencie salas, mensagens e execute limpezas em massa</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.chat.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Nova Sala
                </a>
                <a href="{{ route('admin.chat.stats') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Estatísticas
                </a>
            </div>
        </div>
    </div>

    <!-- Painel de Limpeza em Massa -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">
            <i class="fas fa-broom text-red-600 mr-2"></i>
            Limpeza em Massa
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Estatísticas Rápidas -->
            <div class="bg-blue-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold text-blue-600" id="last24h">-</div>
                        <div class="text-sm text-blue-600">Últimas 24h</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-green-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-week text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold text-green-600" id="last7d">-</div>
                        <div class="text-sm text-green-600">Últimos 7 dias</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-yellow-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold text-yellow-600" id="last30d">-</div>
                        <div class="text-sm text-yellow-600">Últimos 30 dias</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-red-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-database text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold text-red-600" id="total">-</div>
                        <div class="text-sm text-red-600">Total</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Formulário de Limpeza -->
        <div class="mt-6 border-t pt-6">
            <form id="bulk-clear-form" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Período</label>
                        <select name="period" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="24h">Últimas 24 horas</option>
                            <option value="7d">Últimos 7 dias</option>
                            <option value="30d">Últimos 30 dias</option>
                            <option value="all">Todas as mensagens</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Sala</label>
                        <select name="room_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Todas as salas</option>
                            <option value="publico">Salas Públicas</option>
                            <option value="privado">Salas Privadas</option>
                            <option value="ministerio">Salas de Ministério</option>
                            <option value="admin">Salas Administrativas</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="button" id="preview-clear" 
                                class="w-full bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors">
                            <i class="fas fa-eye mr-2"></i>
                            Visualizar
                        </button>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4 hidden" id="preview-results">
                    <h4 class="font-semibold text-gray-900 mb-2">Prévia da Limpeza</h4>
                    <p class="text-sm text-gray-600" id="preview-text">Clique em "Visualizar" para ver quantas mensagens serão removidas.</p>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <input type="checkbox" id="confirm-clear" name="confirm" class="mr-2">
                        <label for="confirm-clear" class="text-sm text-gray-700">Confirmo que desejo executar esta limpeza</label>
                    </div>
                    
                    <button type="submit" id="execute-clear" 
                            class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled>
                        <i class="fas fa-trash mr-2"></i>
                        Executar Limpeza
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Painel de Backup -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">
            <i class="fas fa-archive text-blue-600 mr-2"></i>
            Backup de Mensagens
        </h2>
        
        <div class="flex justify-between items-center mb-4">
            <p class="text-gray-600">Crie backups das mensagens do chat ou gerencie backups existentes.</p>
            <a href="{{ route('admin.chat.backups') }}" 
               class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                <i class="fas fa-cog mr-2"></i>Gerenciar Backups
            </a>
        </div>
        
        <form id="backup-form" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Período</label>
                    <select name="backup_period" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="24h">Últimas 24 horas</option>
                        <option value="7d">Últimos 7 dias</option>
                        <option value="30d">Últimos 30 dias</option>
                        <option value="all">Todas as mensagens</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Sala</label>
                    <select name="backup_room_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todas as salas</option>
                        <option value="publico">Salas Públicas</option>
                        <option value="privado">Salas Privadas</option>
                        <option value="ministerio">Salas de Ministério</option>
                        <option value="admin">Salas Administrativas</option>
                    </select>
                </div>
            </div>
            
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-download mr-2"></i>
                Criar Backup
            </button>
        </form>
    </div>

    <!-- Lista de Salas -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">
            <i class="fas fa-list text-blue-600 mr-2"></i>
            Salas de Chat
        </h2>
        
        @if($rooms->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sala
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tipo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Participantes
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Mensagens
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($rooms as $room)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white">
                                            <i class="fas fa-comments"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $room->nome }}</div>
                                            <div class="text-sm text-gray-500">{{ $room->descricao }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if($room->tipo === 'publico') bg-green-100 text-green-800
                                        @elseif($room->tipo === 'privado') bg-yellow-100 text-yellow-800
                                        @elseif($room->tipo === 'ministerio') bg-blue-100 text-blue-800
                                        @else bg-purple-100 text-purple-800
                                        @endif">
                                        {{ ucfirst($room->tipo) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $room->participants()->where('ativo', true)->count() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $room->messages()->count() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if($room->ativo) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                        {{ $room->ativo ? 'Ativa' : 'Inativa' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.chat.show', $room->id) }}" 
                                           class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.chat.edit', $room->id) }}" 
                                           class="text-yellow-600 hover:text-yellow-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="clearRoomChat({{ $room->id }})" 
                                                class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-comments text-gray-400 text-4xl mb-4"></i>
                <p class="text-gray-500">Nenhuma sala de chat encontrada.</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Carregar estatísticas iniciais
    loadClearStats();
    
    // Preview de limpeza
    document.getElementById('preview-clear').addEventListener('click', function() {
        const period = document.querySelector('select[name="period"]').value;
        const roomType = document.querySelector('select[name="room_type"]').value;
        const button = this;
        
        // Mostrar loading
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Carregando...';
        document.getElementById('preview-text').innerHTML = '<div class="text-gray-500">Calculando preview...</div>';
        document.getElementById('preview-results').classList.remove('hidden');
        
        // Fazer requisição AJAX para obter preview real
        fetch('{{ route("admin.chat.bulk-clear-preview") }}', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                period: period,
                room_type: roomType
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let previewHtml = `
                    <div class="mb-4">
                        <p class="text-sm text-gray-700 mb-2">${data.preview_text}</p>
                        <div class="text-xs text-gray-500">
                            <strong>Período:</strong> ${data.period_text}<br>
                            ${data.room_type_text ? '<strong>Tipo de sala:</strong>' + data.room_type_text + '<br>' : ''}
                            <strong>Total de mensagens:</strong> ${data.message_count.toLocaleString()}
                        </div>
                    </div>
                `;
                
                if (data.sample_messages && data.sample_messages.length > 0) {
                    previewHtml += `
                        <div class="mt-3">
                            <h5 class="text-xs font-semibold text-gray-700 mb-2">Exemplos de mensagens que serão removidas:</h5>
                            <div class="space-y-2 max-h-32 overflow-y-auto">
                    `;
                    
                    data.sample_messages.forEach(message => {
                        previewHtml += `
                            <div class="bg-white p-2 rounded border text-xs">
                                <div class="font-medium text-gray-600">${message.user_name} - ${message.room_name}</div>
                                <div class="text-gray-800">${message.message}</div>
                                <div class="text-gray-400 text-xs">${message.created_at}</div>
                            </div>
                        `;
                    });
                    
                    previewHtml += `
                            </div>
                        </div>
                    `;
                } else if (data.message_count === 0) {
                    previewHtml += `
                        <div class="mt-3 text-center py-4">
                            <i class="fas fa-info-circle text-blue-500 text-2xl mb-2"></i>
                            <p class="text-sm text-gray-600">Nenhuma mensagem encontrada para os filtros selecionados.</p>
                        </div>
                    `;
                }
                
                document.getElementById('preview-text').innerHTML = previewHtml;
            } else {
                document.getElementById('preview-text').innerHTML = `
                    <div class="text-red-600">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Erro: ${data.message}
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            document.getElementById('preview-text').innerHTML = `
                <div class="text-red-600">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Erro ao carregar preview. Tente novamente.
                </div>
            `;
        })
        .finally(() => {
            // Restaurar botão
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-eye mr-2"></i>Visualizar';
        });
    });
    
    // Habilitar/desabilitar botão de limpeza
    document.getElementById('confirm-clear').addEventListener('change', function() {
        document.getElementById('execute-clear').disabled = !this.checked;
    });
    
    // Executar limpeza em massa
    document.getElementById('bulk-clear-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!confirm('Tem certeza que deseja executar esta limpeza? Esta ação não pode ser desfeita.')) {
            return;
        }
        
        const formData = new FormData(this);
        formData.append('confirm', true);
        
        fetch('{{ route("admin.chat.bulk-clear") }}', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                period: formData.get('period'),
                room_type: formData.get('room_type'),
                confirm: true
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Limpeza executada com sucesso! ' + data.message);
                loadClearStats();
                this.reset();
                document.getElementById('preview-results').classList.add('hidden');
            } else {
                alert('Erro: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao executar limpeza.');
        });
    });
    
    // Backup de mensagens
    document.getElementById('backup-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route("admin.chat.backup") }}', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                period: formData.get('backup_period'),
                room_type: formData.get('backup_room_type')
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Backup criado com sucesso! ' + data.message);
            } else {
                alert('Erro: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao criar backup.');
        });
    });
});

// Função para carregar estatísticas
function loadClearStats() {
    fetch('{{ route("admin.chat.clear-stats") }}', {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            document.getElementById('last24h').textContent = data.stats.last_24h;
            document.getElementById('last7d').textContent = data.stats.last_7d;
            document.getElementById('last30d').textContent = data.stats.last_30d;
            document.getElementById('total').textContent = data.stats.total;
        } else {
            console.error('Erro ao carregar estatísticas:', data.message);
        }
    })
    .catch(error => {
        console.error('Erro ao carregar estatísticas:', error);
    });
}

// Função para limpar chat de uma sala específica
function clearRoomChat(roomId) {
    if (!confirm('Tem certeza que deseja limpar todas as mensagens desta sala? Esta ação não pode ser desfeita.')) {
        return;
    }
    
    fetch(`/admin/chat/${roomId}/clear`, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Chat limpo com sucesso! ' + data.message);
            location.reload();
        } else {
            alert('Erro: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao limpar chat.');
    });
}
</script>
@endpush
@endsection 