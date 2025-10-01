@extends('layouts.app')

@section('page-title', 'Relatórios')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-2xl shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white">Relatórios</h1>
                    <p class="text-purple-100 mt-2">Análises e estatísticas detalhadas do sistema</p>
                </div>
                <div class="flex space-x-3">
                    <button onclick="exportarRelatorioGeral()" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-all duration-200">
                        <i class="fas fa-download mr-2"></i>Exportar Geral
                    </button>
                </div>
            </div>
        </div>

        <!-- Cards de Relatórios -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Relatório de Membros -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                    <span class="text-sm text-gray-500">Relatório</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Membros por Ministério</h3>
                <p class="text-gray-600 text-sm mb-4">Distribuição de membros por ministérios e departamentos</p>
                <a href="{{ route('relatorios.membros-por-ministerio') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                    Ver relatório <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>

            <!-- Relatório de Crescimento -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-green-600 text-xl"></i>
                    </div>
                    <span class="text-sm text-gray-500">Relatório</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Crescimento Mensal</h3>
                <p class="text-gray-600 text-sm mb-4">Evolução do número de membros ao longo do tempo</p>
                <a href="{{ route('relatorios.crescimento-mensal') }}" class="inline-flex items-center text-green-600 hover:text-green-800 font-medium">
                    Ver relatório <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>

            <!-- Relatório Demográfico -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-pie text-purple-600 text-xl"></i>
                    </div>
                    <span class="text-sm text-gray-500">Relatório</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Demografia</h3>
                <p class="text-gray-600 text-sm mb-4">Perfil demográfico dos membros da igreja</p>
                <a href="{{ route('relatorios.demografia') }}" class="inline-flex items-center text-purple-600 hover:text-purple-800 font-medium">
                    Ver relatório <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>

            <!-- Relatório de Batismos -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-water text-yellow-600 text-xl"></i>
                    </div>
                    <span class="text-sm text-gray-500">Relatório</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Batismos</h3>
                <p class="text-gray-600 text-sm mb-4">Histórico e estatísticas de batismos</p>
                <a href="{{ route('relatorios.batismos') }}" class="inline-flex items-center text-yellow-600 hover:text-yellow-800 font-medium">
                    Ver relatório <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>

            <!-- Relatório Financeiro -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-red-600 text-xl"></i>
                    </div>
                    <span class="text-sm text-gray-500">Relatório</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Financeiro</h3>
                <p class="text-gray-600 text-sm mb-4">Análise detalhada das finanças da igreja</p>
                <a href="{{ route('relatorios.financeiro') }}" class="inline-flex items-center text-red-600 hover:text-red-800 font-medium">
                    Ver relatório <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>

            <!-- Relatório de Frequência -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar-check text-indigo-600 text-xl"></i>
                    </div>
                    <span class="text-sm text-gray-500">Relatório</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Frequência</h3>
                <p class="text-gray-600 text-sm mb-4">Controle de presença e frequência</p>
                <a href="{{ route('relatorios.frequencia') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium">
                    Ver relatório <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>

        <!-- Estatísticas Rápidas -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Resumo Geral -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-chart-bar mr-3 text-blue-600"></i>
                    Resumo Geral
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-users text-blue-600 text-sm"></i>
                            </div>
                            <span class="text-gray-700">Total de Membros</span>
                        </div>
                        <span class="font-semibold text-gray-900">{{ \App\Models\Membro::where('ativo', true)->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-church text-green-600 text-sm"></i>
                            </div>
                            <span class="text-gray-700">Ministérios Ativos</span>
                        </div>
                        <span class="font-semibold text-gray-900">{{ \App\Models\Ministerio::where('ativo', true)->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-bullhorn text-purple-600 text-sm"></i>
                            </div>
                            <span class="text-gray-700">Campanhas Ativas</span>
                        </div>
                        <span class="font-semibold text-gray-900">{{ \App\Models\Campanha::where('ativo', true)->where('status', 'ativa')->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-dollar-sign text-yellow-600 text-sm"></i>
                            </div>
                            <span class="text-gray-700">Arrecadação do Mês</span>
                        </div>
                        <span class="font-semibold text-gray-900">R$ {{ number_format(\App\Models\Transacao::where('tipo', '!=', 'saida')->whereMonth('data', now()->month)->sum('valor'), 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-bolt mr-3 text-orange-600"></i>
                    Ações Rápidas
                </h3>
                <div class="space-y-3">
                    <button onclick="gerarRelatorioMembros()" class="w-full flex items-center justify-between p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors duration-200">
                        <div class="flex items-center">
                            <i class="fas fa-users text-blue-600 mr-3"></i>
                            <span class="text-gray-700">Exportar Lista de Membros</span>
                        </div>
                        <i class="fas fa-download text-blue-600"></i>
                    </button>
                    <button onclick="gerarRelatorioFinanceiro()" class="w-full flex items-center justify-between p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors duration-200">
                        <div class="flex items-center">
                            <i class="fas fa-chart-line text-green-600 mr-3"></i>
                            <span class="text-gray-700">Relatório Financeiro Mensal</span>
                        </div>
                        <i class="fas fa-download text-green-600"></i>
                    </button>
                    <button onclick="gerarRelatorioAniversariantes()" class="w-full flex items-center justify-between p-3 bg-pink-50 hover:bg-pink-100 rounded-lg transition-colors duration-200">
                        <div class="flex items-center">
                            <i class="fas fa-birthday-cake text-pink-600 mr-3"></i>
                            <span class="text-gray-700">Lista de Aniversariantes</span>
                        </div>
                        <i class="fas fa-download text-pink-600"></i>
                    </button>
                    <button onclick="gerarRelatorioPermissoes()" class="w-full flex items-center justify-between p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors duration-200">
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt text-purple-600 mr-3"></i>
                            <span class="text-gray-700">Relatório de Permissões</span>
                        </div>
                        <i class="fas fa-download text-purple-600"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function exportarRelatorioGeral() {
    // Implementar exportação geral
    alert('Funcionalidade de exportação geral será implementada');
}

function gerarRelatorioMembros() {
    window.open('{{ route("membros.exportar") }}', '_blank');
}

function gerarRelatorioFinanceiro() {
    window.open('{{ route("tesouraria.exportar") }}', '_blank');
}

function gerarRelatorioAniversariantes() {
    window.open('{{ route("admin.people.birthdays.export") }}', '_blank');
}

function gerarRelatorioPermissoes() {
                    window.open('{{ route("admin.people.export") }}', '_blank');
}
</script>
@endsection 