@extends('layouts.admin')

@section('title', 'Gerenciamento de Backups - Chat')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gerenciamento de Backups</h1>
            <p class="text-gray-600 mt-1">Visualize, restaure e gerencie backups das mensagens do chat</p>
        </div>
        
        <div class="flex space-x-3">
            <a href="{{ route('admin.chat.manage') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-archive text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Backups</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ count($backups) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-database text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Mensagens</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ array_sum(array_column(array_column($backups, 'metadata'), 'messages_count')) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-hdd text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Espaço Usado</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        @php
                            $totalSize = array_sum(array_column($backups, 'size'));
                            $units = ['B', 'KB', 'MB', 'GB'];
                            for ($i = 0; $totalSize > 1024 && $i < count($units) - 1; $i++) {
                                $totalSize /= 1024;
                            }
                            echo round($totalSize, 2) . ' ' . $units[$i];
                        @endphp
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i class="fas fa-clock text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Último Backup</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ count($backups) > 0 ? $backups[0]['created_formatted'] : 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Backups -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Backups Disponíveis</h3>
        </div>
        
        @if(count($backups) > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Arquivo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data/Hora</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mensagens</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Período</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Criado por</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tamanho</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($backups as $backup)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $backup['filename'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $backup['created_formatted'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ number_format($backup['metadata']['messages_count']) }} msgs
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $backup['metadata']['period'] }}
                                        @if($backup['metadata']['room_type'] !== 'todas' && $backup['metadata']['room_type'] !== 'N/A')
                                            <br><span class="text-xs text-gray-500">({{ $backup['metadata']['room_type'] }})</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $backup['metadata']['admin_name'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $backup['size_formatted'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button onclick="viewBackup('{{ $backup['filename'] }}')" 
                                                class="text-blue-600 hover:text-blue-900" title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button onclick="showRestoreModal('{{ $backup['filename'] }}')" 
                                                class="text-green-600 hover:text-green-900" title="Restaurar">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                        <a href="{{ route('admin.chat.backups.download', $backup['filename']) }}" 
                                           class="text-purple-600 hover:text-purple-900" title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <button onclick="deleteBackup('{{ $backup['filename'] }}')" 
                                                class="text-red-600 hover:text-red-900" title="Deletar">
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
            <div class="text-center py-12">
                <i class="fas fa-archive text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum backup encontrado</h3>
                <p class="text-gray-500">Crie um backup das mensagens do chat para começar.</p>
                <a href="{{ route('admin.chat.manage') }}" class="inline-flex items-center mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>Criar Backup
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Modal de Visualização -->
<div id="viewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-96 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Detalhes do Backup</h3>
                <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="viewModalContent" class="px-6 py-4 overflow-y-auto max-h-80">
                <!-- Conteúdo será carregado via JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Modal de Restauração -->
<div id="restoreModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Restaurar Backup</h3>
            </div>
            <div class="px-6 py-4">
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-4">Como deseja restaurar as mensagens?</p>
                    
                    <div class="space-y-3">
                        <label class="flex items-start">
                            <input type="radio" name="restore_mode" value="add" class="mt-1 mr-3" checked>
                            <div>
                                <div class="font-medium text-gray-900">Adicionar mensagens</div>
                                <div class="text-sm text-gray-500">Adiciona as mensagens do backup sem remover as existentes</div>
                            </div>
                        </label>
                        
                        <label class="flex items-start">
                            <input type="radio" name="restore_mode" value="replace" class="mt-1 mr-3">
                            <div>
                                <div class="font-medium text-gray-900">Substituir todas</div>
                                <div class="text-sm text-gray-500">Remove todas as mensagens atuais e restaura apenas as do backup</div>
                            </div>
                        </label>
                    </div>
                </div>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3 mb-4">
                    <div class="flex">
                        <i class="fas fa-exclamation-triangle text-yellow-400 mr-2 mt-0.5"></i>
                        <div class="text-sm text-yellow-700">
                            <strong>Atenção:</strong> Esta ação não pode ser desfeita. Um backup do estado atual será criado automaticamente.
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" id="confirmRestore" class="mr-2">
                    <label for="confirmRestore" class="text-sm text-gray-700">Confirmo que desejo restaurar este backup</label>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button onclick="closeRestoreModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                    Cancelar
                </button>
                <button id="confirmRestoreBtn" onclick="executeRestore()" disabled 
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-undo mr-2"></i>Restaurar
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentBackupFilename = '';

// Visualizar backup
function viewBackup(filename) {
    document.getElementById('viewModalContent').innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> Carregando...</div>';
    document.getElementById('viewModal').classList.remove('hidden');
    
    fetch(`{{ route('admin.chat.backups.view', '') }}/${filename}`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let html = `
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Arquivo</label>
                            <p class="text-sm text-gray-900">${data.backup.filename}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Data de Criação</label>
                            <p class="text-sm text-gray-900">${new Date(data.backup.created_at).toLocaleString('pt-BR')}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Criado por</label>
                            <p class="text-sm text-gray-900">${data.backup.admin_name}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Período</label>
                            <p class="text-sm text-gray-900">${data.backup.period}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo de Sala</label>
                            <p class="text-sm text-gray-900">${data.backup.room_type}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Total de Mensagens</label>
                            <p class="text-sm text-gray-900">${data.backup.messages_count.toLocaleString()}</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preview das Mensagens (máximo 10)</label>
                        <div class="space-y-2 max-h-40 overflow-y-auto">
            `;
            
            data.backup.messages_preview.forEach(message => {
                html += `
                    <div class="bg-gray-50 p-3 rounded border">
                        <div class="flex justify-between items-start mb-1">
                            <span class="font-medium text-sm text-gray-900">${message.user_name}</span>
                            <span class="text-xs text-gray-500">${message.room_name}</span>
                        </div>
                        <p class="text-sm text-gray-700">${message.mensagem}</p>
                        <div class="text-xs text-gray-400 mt-1">${new Date(message.created_at).toLocaleString('pt-BR')}</div>
                    </div>
                `;
            });
            
            html += `
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('viewModalContent').innerHTML = html;
        } else {
            document.getElementById('viewModalContent').innerHTML = `<div class="text-red-600">${data.message}</div>`;
        }
    })
    .catch(error => {
        document.getElementById('viewModalContent').innerHTML = '<div class="text-red-600">Erro ao carregar backup.</div>';
        console.error('Erro:', error);
    });
}

function closeViewModal() {
    document.getElementById('viewModal').classList.add('hidden');
}

// Restaurar backup
function showRestoreModal(filename) {
    currentBackupFilename = filename;
    document.getElementById('restoreModal').classList.remove('hidden');
    document.getElementById('confirmRestore').checked = false;
    document.getElementById('confirmRestoreBtn').disabled = true;
}

function closeRestoreModal() {
    document.getElementById('restoreModal').classList.add('hidden');
    currentBackupFilename = '';
}

function executeRestore() {
    const restoreMode = document.querySelector('input[name="restore_mode"]:checked').value;
    const confirmBtn = document.getElementById('confirmRestoreBtn');
    
    confirmBtn.disabled = true;
    confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Restaurando...';
    
    fetch(`{{ route('admin.chat.backups.restore', '') }}/${currentBackupFilename}`, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            confirm: true,
            restore_mode: restoreMode
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Sucesso: ' + data.message);
            closeRestoreModal();
            location.reload();
        } else {
            alert('Erro: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao restaurar backup.');
    })
    .finally(() => {
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = '<i class="fas fa-undo mr-2"></i>Restaurar';
    });
}

// Deletar backup
function deleteBackup(filename) {
    if (!confirm('Tem certeza que deseja deletar este backup? Esta ação não pode ser desfeita.')) {
        return;
    }
    
    fetch(`{{ route('admin.chat.backups.delete', '') }}/${filename}`, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Backup deletado com sucesso!');
            location.reload();
        } else {
            alert('Erro: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao deletar backup.');
    });
}

// Event listeners
document.getElementById('confirmRestore').addEventListener('change', function() {
    document.getElementById('confirmRestoreBtn').disabled = !this.checked;
});
</script>
@endpush
@endsection 