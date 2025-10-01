@extends('layouts.admin')

@section('title', 'Dashboard do Sistema')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Cabeçalho -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        <i class="fas fa-cogs text-blue-600 mr-3"></i>
                        Dashboard do Sistema
                    </h1>
                    <p class="text-lg text-gray-600 mt-2">Visão geral e estatísticas do sistema</p>
                </div>
                <div class="flex space-x-3">
                    <button onclick="window.location.href='{{ route('admin.system.maintenance.backup') }}'" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        <i class="fas fa-download mr-2"></i>
                        Backup
                    </button>
                    <button onclick="limparCache()" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <i class="fas fa-broom mr-2"></i>
                        Limpar Cache
                    </button>
                </div>
            </div>
        </div>

        <!-- Estatísticas Principais -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total de Usuários</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($estatisticas['total_usuarios'], 0, ',', '.') }}</p>
                        <p class="text-xs text-green-600 font-medium">{{ number_format($estatisticas['usuarios_ativos'], 0, ',', '.') }} ativos</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-bell text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Notificações</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($estatisticas['total_notificacoes'], 0, ',', '.') }}</p>
                        <p class="text-xs text-yellow-600 font-medium">{{ number_format($estatisticas['notificacoes_nao_lidas'], 0, ',', '.') }} não lidas</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-hdd text-purple-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Espaço em Disco</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['espaco_disco']['used'] }}</p>
                        <p class="text-xs text-purple-600 font-medium">{{ $estatisticas['espaco_disco']['percent'] }}% usado</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-cloud-upload-alt text-indigo-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Último Backup</p>
                        <p class="text-2xl font-bold text-gray-900">
                            @if($estatisticas['ultimo_backup'])
                                {{ \Carbon\Carbon::parse($estatisticas['ultimo_backup'])->format('d/m/Y') }}
                            @else
                                N/A
                            @endif
                        </p>
                        <p class="text-xs text-indigo-600 font-medium">
                            @if($estatisticas['ultimo_backup'])
                                {{ \Carbon\Carbon::parse($estatisticas['ultimo_backup'])->diffForHumans() }}
                            @else
                                Nunca
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status do Sistema -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Status do Sistema</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-database text-blue-600 text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-900">Banco de Dados</span>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sistemaStatus['database'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i class="{{ $sistemaStatus['database'] ? 'fas fa-check' : 'fas fa-times' }} mr-1"></i>
                                {{ $sistemaStatus['database'] ? 'Online' : 'Offline' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-bolt text-green-600 text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-900">Cache</span>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sistemaStatus['cache'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i class="{{ $sistemaStatus['cache'] ? 'fas fa-check' : 'fas fa-times' }} mr-1"></i>
                                {{ $sistemaStatus['cache'] ? 'Online' : 'Offline' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-hdd text-purple-600 text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-900">Storage</span>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sistemaStatus['storage'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i class="{{ $sistemaStatus['storage'] ? 'fas fa-check' : 'fas fa-times' }} mr-1"></i>
                                {{ $sistemaStatus['storage'] ? 'Online' : 'Offline' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Ações Rápidas</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="{{ route('admin.system.settings.index') }}" 
                           class="flex items-center p-3 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <i class="fas fa-cog text-blue-600 mr-3"></i>
                            Configurações do Sistema
                        </a>
                        
                        <a href="{{ route('admin.system.notifications.index') }}" 
                           class="flex items-center p-3 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <i class="fas fa-bell text-green-600 mr-3"></i>
                            Gestão de Notificações
                        </a>
                        
                        <a href="{{ route('admin.system.logs.index') }}" 
                           class="flex items-center p-3 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <i class="fas fa-file-alt text-purple-600 mr-3"></i>
                            Logs do Sistema
                        </a>
                        
                        <a href="{{ route('admin.system.maintenance.index') }}" 
                           class="flex items-center p-3 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <i class="fas fa-tools text-orange-600 mr-3"></i>
                            Manutenção
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logs Recentes -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Logs Recentes</h3>
                    <a href="{{ route('admin.system.logs.index') }}" 
                       class="text-sm text-blue-600 hover:text-blue-800 transition-colors duration-200">
                        Ver todos os logs
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if(count($logsRecentes) > 0)
                    <div class="space-y-3">
                        @foreach(array_slice($logsRecentes, 0, 10) as $log)
                            <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900 font-mono">{{ Str::limit($log, 200) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-file-alt text-gray-300 text-4xl mb-4"></i>
                        <p class="text-gray-500">Nenhum log recente encontrado.</p>
                    </div>
                @endif
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
                alert('Erro ao limpar cache: ' + data.message);
            }
        })
        .catch(error => {
            alert('Erro ao limpar cache: ' + error.message);
        });
    }
}
</script>
@endpush

@endsection 