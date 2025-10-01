@extends('layouts.admin')

@section('title', 'Manutenção do Sistema')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Cabeçalho -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        <i class="fas fa-tools text-orange-600 mr-3"></i>
                        Manutenção do Sistema
                    </h1>
                    <p class="text-lg text-gray-600 mt-2">Ferramentas de manutenção e backup do sistema</p>
                </div>
            </div>
        </div>

        <!-- Status do Sistema -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-database text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Banco de Dados</p>
                        <p class="text-2xl font-bold {{ $status['database_status'] ? 'text-green-600' : 'text-red-600' }}">
                            {{ $status['database_status'] ? 'Online' : 'Offline' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-bolt text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Cache</p>
                        <p class="text-2xl font-bold {{ $status['cache_status'] ? 'text-green-600' : 'text-red-600' }}">
                            {{ $status['cache_status'] ? 'Online' : 'Offline' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-hdd text-purple-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Storage</p>
                        <p class="text-2xl font-bold {{ $status['storage_status'] ? 'text-green-600' : 'text-red-600' }}">
                            {{ $status['storage_status'] ? 'Online' : 'Offline' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ferramentas de Manutenção -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Limpeza do Sistema</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <button onclick="limparCache()" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg transition duration-200 flex items-center justify-center">
                            <i class="fas fa-broom mr-3"></i>
                            Limpar Cache
                        </button>
                        
                        <button onclick="limparLogs()" 
                                class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-3 rounded-lg transition duration-200 flex items-center justify-center">
                            <i class="fas fa-trash mr-3"></i>
                            Limpar Logs
                        </button>
                        
                        <button onclick="otimizarBanco()" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg transition duration-200 flex items-center justify-center">
                            <i class="fas fa-database mr-3"></i>
                            Otimizar Banco de Dados
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Backup e Restauração</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <button onclick="criarBackup()" 
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg transition duration-200 flex items-center justify-center">
                            <i class="fas fa-download mr-3"></i>
                            Criar Backup
                        </button>
                        
                        <button onclick="verificarBackups()" 
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-3 rounded-lg transition duration-200 flex items-center justify-center">
                            <i class="fas fa-list mr-3"></i>
                            Verificar Backups
                        </button>
                        
                        <button onclick="restaurarBackup()" 
                                class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-3 rounded-lg transition duration-200 flex items-center justify-center">
                            <i class="fas fa-upload mr-3"></i>
                            Restaurar Backup
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modo de Manutenção -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Modo de Manutenção</h3>
                <p class="text-sm text-gray-600 mt-1">Ativar ou desativar o modo de manutenção do sistema</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-md font-medium text-gray-900 mb-3">Ativar Modo de Manutenção</h4>
                        <p class="text-sm text-gray-600 mb-4">
                            O modo de manutenção impede o acesso de usuários comuns ao sistema, permitindo apenas acesso administrativo.
                        </p>
                        <form action="{{ route('admin.system.maintenance.enable') }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg transition duration-200 flex items-center">
                                <i class="fas fa-tools mr-2"></i>
                                Ativar Modo de Manutenção
                            </button>
                        </form>
                    </div>
                    
                    <div>
                        <h4 class="text-md font-medium text-gray-900 mb-3">Desativar Modo de Manutenção</h4>
                        <p class="text-sm text-gray-600 mb-4">
                            Desativa o modo de manutenção e permite acesso normal de todos os usuários ao sistema.
                        </p>
                        <form action="{{ route('admin.system.maintenance.disable') }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition duration-200 flex items-center">
                                <i class="fas fa-check mr-2"></i>
                                Desativar Modo de Manutenção
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function limparCache() {
    if (confirm('Deseja limpar o cache do sistema?')) {
        fetch('{{ route("admin.system.maintenance.cache") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Cache limpo com sucesso!');
                location.reload();
            } else {
                alert('Erro ao limpar cache.');
            }
        });
    }
}

function limparLogs() {
    if (confirm('Deseja limpar todos os logs do sistema?')) {
        fetch('{{ route("admin.system.logs.clear") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Logs limpos com sucesso!');
                location.reload();
            } else {
                alert('Erro ao limpar logs.');
            }
        });
    }
}

function otimizarBanco() {
    if (confirm('Deseja otimizar o banco de dados?')) {
        fetch('{{ route("admin.system.maintenance.database") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Banco de dados otimizado com sucesso!');
                location.reload();
            } else {
                alert('Erro ao otimizar banco de dados.');
            }
        });
    }
}

function criarBackup() {
    if (confirm('Deseja criar um backup do sistema?')) {
        fetch('{{ route("admin.system.maintenance.backup") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Backup criado com sucesso!');
                location.reload();
            } else {
                alert('Erro ao criar backup.');
            }
        });
    }
}

function verificarBackups() {
    window.open('{{ route("admin.system.maintenance.backups") }}', '_blank');
}

function restaurarBackup() {
    if (confirm('Deseja restaurar um backup? Esta ação pode sobrescrever dados atuais.')) {
        // Implementar lógica de restauração
        alert('Funcionalidade de restauração será implementada.');
    }
}
</script>
@endpush
@endsection 