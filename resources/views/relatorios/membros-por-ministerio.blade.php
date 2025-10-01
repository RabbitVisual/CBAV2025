@extends('layouts.app')

@section('page-title', 'Relatório - Membros por Ministério')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white">Membros por Ministério</h1>
                    <p class="text-blue-100 mt-2">Distribuição de membros por ministérios</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.people.index') }}" 
                       class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-all duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>Voltar
                    </a>
                    <button onclick="exportarRelatorio()" 
                            class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-all duration-200">
                        <i class="fas fa-download mr-2"></i>Exportar
                    </button>
                </div>
            </div>
        </div>

        <!-- Estatísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total de Ministérios</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $ministerios->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-user-friends text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total de Membros</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $ministerios->sum('membros_count') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-chart-pie text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Média por Ministério</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $ministerios->count() > 0 ? round($ministerios->sum('membros_count') / $ministerios->count(), 1) : 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-chart-bar mr-3 text-blue-600"></i>
                Distribuição de Membros
            </h2>
            <div class="h-96">
                <canvas id="membrosChart"></canvas>
            </div>
        </div>

        <!-- Lista de Ministérios -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-list mr-3 text-indigo-600"></i>
                Detalhamento por Ministério
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($ministerios as $ministerio)
                <div class="bg-gradient-to-r from-indigo-50 to-blue-50 p-6 rounded-xl border border-indigo-200 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-church text-indigo-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $ministerio->nome }}</h3>
                                <p class="text-sm text-gray-500">{{ $ministerio->membros_count }} membros</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-indigo-600">{{ $ministerio->membros_count }}</div>
                            <div class="text-xs text-gray-500">
                                {{ $ministerios->sum('membros_count') > 0 ? round(($ministerio->membros_count / $ministerios->sum('membros_count')) * 100, 1) : 0 }}%
                            </div>
                        </div>
                    </div>
                    
                    @if($ministerio->descricao)
                        <p class="text-sm text-gray-600 mb-4">{{ $ministerio->descricao }}</p>
                    @endif
                    
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Departamentos:</span>
                            <span class="font-medium">{{ $ministerio->departamentos_count }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Status:</span>
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                {{ $ministerio->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Dados para o gráfico
const dados = @json($ministerios->map(function($ministerio) {
    return [
        'nome' => $ministerio->nome,
        'membros' => $ministerio->membros_count
    ];
}));

// Configuração do gráfico
const ctx = document.getElementById('membrosChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: dados.map(item => item.nome),
        datasets: [{
            data: dados.map(item => item.membros),
            backgroundColor: [
                '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
                '#06B6D4', '#84CC16', '#F97316', '#EC4899', '#6366F1'
            ],
            borderWidth: 2,
            borderColor: '#ffffff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((context.parsed / total) * 100).toFixed(1);
                        return `${context.label}: ${context.parsed} membros (${percentage}%)`;
                    }
                }
            }
        }
    }
});

function exportarRelatorio() {
    // Implementar exportação
    alert('Funcionalidade de exportação será implementada');
}
</script>
@endsection 