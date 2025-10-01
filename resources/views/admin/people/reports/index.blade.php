@extends('layouts.admin')

@section('title', __('Relatórios de Pessoas'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Relatórios de Pessoas') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Dashboard completo de estatísticas e relatórios') }}</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="gerarRelatorioCompleto()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-file-pdf mr-2"></i>{{ __('Relatório Completo') }}
            </button>
            <button onclick="exportarDados()" 
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-download mr-2"></i>{{ __('Exportar Dados') }}
            </button>
        </div>
    </div>

    <!-- Estatísticas Gerais -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-blue-500 text-white">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('Total Membros') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalMembros ?? 0 }}</p>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                <span class="text-sm text-blue-600 font-medium">{{ __('Pessoas cadastradas') }}</span>
            </div>
        </div>
        
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-green-500 text-white">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('Membros Ativos') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $membrosAtivos ?? 0 }}</p>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                <span class="text-sm text-green-600 font-medium">
                    {{ number_format(($membrosAtivos / max($totalMembros, 1)) * 100, 1) }}% {{ __('do total') }}
                </span>
            </div>
        </div>
        
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-purple-500 text-white">
                    <i class="fas fa-church text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('Ministérios') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalMinisterios ?? 0 }}</p>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <div class="w-2 h-2 bg-purple-500 rounded-full mr-2"></div>
                <span class="text-sm text-purple-600 font-medium">{{ __('Organizações ativas') }}</span>
            </div>
        </div>
        
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-orange-500 text-white">
                    <i class="fas fa-sitemap text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('Departamentos') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalDepartamentos ?? 0 }}</p>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <div class="w-2 h-2 bg-orange-500 rounded-full mr-2"></div>
                <span class="text-sm text-orange-600 font-medium">{{ __('Estrutura organizacional') }}</span>
            </div>
        </div>
    </div>

    <!-- Relatórios Disponíveis -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Relatórios de Membros -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-blue-500">
                <h3 class="text-lg font-semibold text-white">{{ __('Relatórios de Membros') }}</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <!-- Relatório Geral de Membros -->
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-list text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ __('Lista Geral de Membros') }}</h4>
                                    <p class="text-sm text-gray-600">{{ __('Relatório completo com todos os dados dos membros') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button onclick="gerarRelatorio('membros-geral')" 
                                        class="bg-blue-100 text-blue-600 hover:bg-blue-200 p-2 rounded-lg transition-colors" 
                                        title="{{ __('Gerar PDF') }}">
                                    <i class="fas fa-file-pdf"></i>
                                </button>
                                <button onclick="exportarRelatorio('membros-geral')" 
                                        class="bg-green-100 text-green-600 hover:bg-green-200 p-2 rounded-lg transition-colors" 
                                        title="{{ __('Exportar Excel') }}">
                                    <i class="fas fa-file-excel"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Relatório por Status -->
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-user-check text-green-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ __('Membros por Status') }}</h4>
                                    <p class="text-sm text-gray-600">{{ __('Divisão entre membros ativos e inativos') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button onclick="gerarRelatorio('membros-status')" 
                                        class="bg-blue-100 text-blue-600 hover:bg-blue-200 p-2 rounded-lg transition-colors" 
                                        title="{{ __('Gerar PDF') }}">
                                    <i class="fas fa-file-pdf"></i>
                                </button>
                                <button onclick="exportarRelatorio('membros-status')" 
                                        class="bg-green-100 text-green-600 hover:bg-green-200 p-2 rounded-lg transition-colors" 
                                        title="{{ __('Exportar Excel') }}">
                                    <i class="fas fa-file-excel"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Relatório de Aniversariantes -->
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-birthday-cake text-pink-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ __('Aniversariantes') }}</h4>
                                    <p class="text-sm text-gray-600">{{ __('Lista de aniversariantes por mês') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button onclick="gerarRelatorio('aniversariantes')" 
                                        class="bg-blue-100 text-blue-600 hover:bg-blue-200 p-2 rounded-lg transition-colors" 
                                        title="{{ __('Gerar PDF') }}">
                                    <i class="fas fa-file-pdf"></i>
                                </button>
                                <button onclick="exportarRelatorio('aniversariantes')" 
                                        class="bg-green-100 text-green-600 hover:bg-green-200 p-2 rounded-lg transition-colors" 
                                        title="{{ __('Exportar Excel') }}">
                                    <i class="fas fa-file-excel"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Relatório por Faixa Etária -->
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-chart-bar text-indigo-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ __('Por Faixa Etária') }}</h4>
                                    <p class="text-sm text-gray-600">{{ __('Distribuição de membros por idade') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button onclick="gerarRelatorio('faixa-etaria')" 
                                        class="bg-blue-100 text-blue-600 hover:bg-blue-200 p-2 rounded-lg transition-colors" 
                                        title="{{ __('Gerar PDF') }}">
                                    <i class="fas fa-file-pdf"></i>
                                </button>
                                <button onclick="exportarRelatorio('faixa-etaria')" 
                                        class="bg-green-100 text-green-600 hover:bg-green-200 p-2 rounded-lg transition-colors" 
                                        title="{{ __('Exportar Excel') }}">
                                    <i class="fas fa-file-excel"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Relatórios de Ministérios -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-500">
                <h3 class="text-lg font-semibold text-white">{{ __('Relatórios de Ministérios') }}</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <!-- Relatório Geral de Ministérios -->
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-church text-purple-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ __('Lista de Ministérios') }}</h4>
                                    <p class="text-sm text-gray-600">{{ __('Relatório completo dos ministérios ativos') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button onclick="gerarRelatorio('ministerios-geral')" 
                                        class="bg-blue-100 text-blue-600 hover:bg-blue-200 p-2 rounded-lg transition-colors" 
                                        title="{{ __('Gerar PDF') }}">
                                    <i class="fas fa-file-pdf"></i>
                                </button>
                                <button onclick="exportarRelatorio('ministerios-geral')" 
                                        class="bg-green-100 text-green-600 hover:bg-green-200 p-2 rounded-lg transition-colors" 
                                        title="{{ __('Exportar Excel') }}">
                                    <i class="fas fa-file-excel"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Relatório por Ministério -->
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-users text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ __('Membros por Ministério') }}</h4>
                                    <p class="text-sm text-gray-600">{{ __('Quantidade de membros em cada ministério') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button onclick="gerarRelatorio('membros-ministerio')" 
                                        class="bg-blue-100 text-blue-600 hover:bg-blue-200 p-2 rounded-lg transition-colors" 
                                        title="{{ __('Gerar PDF') }}">
                                    <i class="fas fa-file-pdf"></i>
                                </button>
                                <button onclick="exportarRelatorio('membros-ministerio')" 
                                        class="bg-green-100 text-green-600 hover:bg-green-200 p-2 rounded-lg transition-colors" 
                                        title="{{ __('Exportar Excel') }}">
                                    <i class="fas fa-file-excel"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Relatório de Departamentos -->
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-sitemap text-orange-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ __('Estrutura de Departamentos') }}</h4>
                                    <p class="text-sm text-gray-600">{{ __('Organização completa por departamentos') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button onclick="gerarRelatorio('departamentos')" 
                                        class="bg-blue-100 text-blue-600 hover:bg-blue-200 p-2 rounded-lg transition-colors" 
                                        title="{{ __('Gerar PDF') }}">
                                    <i class="fas fa-file-pdf"></i>
                                </button>
                                <button onclick="exportarRelatorio('departamentos')" 
                                        class="bg-green-100 text-green-600 hover:bg-green-200 p-2 rounded-lg transition-colors" 
                                        title="{{ __('Exportar Excel') }}">
                                    <i class="fas fa-file-excel"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Relatório de Cargos -->
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-briefcase text-teal-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ __('Cargos e Responsabilidades') }}</h4>
                                    <p class="text-sm text-gray-600">{{ __('Lista completa de cargos ativos') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button onclick="gerarRelatorio('cargos')" 
                                        class="bg-blue-100 text-blue-600 hover:bg-blue-200 p-2 rounded-lg transition-colors" 
                                        title="{{ __('Gerar PDF') }}">
                                    <i class="fas fa-file-pdf"></i>
                                </button>
                                <button onclick="exportarRelatorio('cargos')" 
                                        class="bg-green-100 text-green-600 hover:bg-green-200 p-2 rounded-lg transition-colors" 
                                        title="{{ __('Exportar Excel') }}">
                                    <i class="fas fa-file-excel"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Relatórios Personalizados -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden mt-8">
        <div class="px-6 py-4 bg-gray-500">
            <h3 class="text-lg font-semibold text-white">{{ __('Relatórios Personalizados') }}</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Filtros Personalizados -->
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-900 mb-3">{{ __('Filtros Avançados') }}</h4>
                    
                    <div>
                        <label for="ministerio_filter" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Ministério') }}</label>
                        <select id="ministerio_filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">{{ __('Todos os ministérios') }}</option>
                            @if(isset($ministerios))
                                @foreach($ministerios as $ministerio)
                                    <option value="{{ $ministerio->id }}">{{ $ministerio->nome }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div>
                        <label for="departamento_filter" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Departamento') }}</label>
                        <select id="departamento_filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">{{ __('Todos os departamentos') }}</option>
                            @if(isset($departamentos))
                                @foreach($departamentos as $departamento)
                                    <option value="{{ $departamento->id }}">{{ $departamento->nome }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div>
                        <label for="status_filter" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Status') }}</label>
                        <select id="status_filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">{{ __('Todos') }}</option>
                            <option value="ativo">{{ __('Ativos') }}</option>
                            <option value="inativo">{{ __('Inativos') }}</option>
                        </select>
                    </div>
                </div>

                <!-- Período -->
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-900 mb-3">{{ __('Período') }}</h4>
                    
                    <div>
                        <label for="data_inicio" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Data Início') }}</label>
                        <input type="date" id="data_inicio" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="data_fim" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Data Fim') }}</label>
                        <input type="date" id="data_fim" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="idade_min" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Idade Mínima') }}</label>
                        <input type="number" id="idade_min" placeholder="{{ __('Ex: 18') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="idade_max" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Idade Máxima') }}</label>
                        <input type="number" id="idade_max" placeholder="{{ __('Ex: 65') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- Formato do Relatório -->
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-900 mb-3">{{ __('Configurações') }}</h4>
                    
                    <div>
                        <label for="formato" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Formato') }}</label>
                        <select id="formato" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>

                    <div>
                        <label class="flex items-center mt-4">
                            <input type="checkbox" id="incluir_fotos" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">{{ __('Incluir fotos no relatório') }}</span>
                        </label>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" id="incluir_contatos" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">{{ __('Incluir informações de contato') }}</span>
                        </label>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" id="incluir_enderecos" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">{{ __('Incluir endereços') }}</span>
                        </label>
                    </div>

                    <!-- Botão de Gerar -->
                    <div class="pt-4">
                        <button onclick="gerarRelatorioPersonalizado()" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors duration-200 font-semibold">
                            <i class="fas fa-magic mr-2"></i>{{ __('Gerar Relatório Personalizado') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function gerarRelatorio(tipo) {
    // Mostrar loading
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;
    
    // Simular requisição para gerar relatório
    setTimeout(() => {
        // Abrir o relatório em nova aba
        window.open(`{{ route('admin.people.reports.generate') }}?tipo=${tipo}&formato=pdf`, '_blank');
        
        // Restaurar botão
        button.innerHTML = originalContent;
        button.disabled = false;
    }, 1000);
}

function exportarRelatorio(tipo) {
    // Mostrar loading
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;
    
    // Simular requisição para exportar relatório
    setTimeout(() => {
        // Fazer download do arquivo
        window.open(`{{ route('admin.people.reports.export') }}?tipo=${tipo}&formato=excel`, '_blank');
        
        // Restaurar botão
        button.innerHTML = originalContent;
        button.disabled = false;
    }, 1000);
}

function gerarRelatorioCompleto() {
    if (confirm('{{ __("Deseja gerar um relatório completo com todos os dados? Este processo pode demorar alguns minutos.") }}')) {
        window.open(`{{ route('admin.people.reports.complete') }}`, '_blank');
    }
}

function exportarDados() {
    const formato = prompt('{{ __("Escolha o formato:\n1 - Excel\n2 - CSV\n3 - JSON") }}', '1');
    let tipoFormato = 'excel';
    
    switch(formato) {
        case '2': tipoFormato = 'csv'; break;
        case '3': tipoFormato = 'json'; break;
        default: tipoFormato = 'excel';
    }
    
    window.open(`{{ route('admin.people.reports.export-all') }}?formato=${tipoFormato}`, '_blank');
}

function gerarRelatorioPersonalizado() {
    const filtros = {
        ministerio: document.getElementById('ministerio_filter').value,
        departamento: document.getElementById('departamento_filter').value,
        status: document.getElementById('status_filter').value,
        data_inicio: document.getElementById('data_inicio').value,
        data_fim: document.getElementById('data_fim').value,
        idade_min: document.getElementById('idade_min').value,
        idade_max: document.getElementById('idade_max').value,
        formato: document.getElementById('formato').value,
        incluir_fotos: document.getElementById('incluir_fotos').checked,
        incluir_contatos: document.getElementById('incluir_contatos').checked,
        incluir_enderecos: document.getElementById('incluir_enderecos').checked
    };
    
    // Construir URL com parâmetros
    const params = new URLSearchParams(filtros);
    const url = `{{ route('admin.people.reports.custom') }}?${params.toString()}`;
    
    // Mostrar loading
    const button = event.target;
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>{{ __("Gerando...") }}';
    button.disabled = true;
    
    setTimeout(() => {
        window.open(url, '_blank');
        
        // Restaurar botão
        button.innerHTML = originalContent;
        button.disabled = false;
    }, 1500);
}

document.addEventListener('DOMContentLoaded', function() {
    // Dependência entre filtros
    const ministerioSelect = document.getElementById('ministerio_filter');
    const departamentoSelect = document.getElementById('departamento_filter');
    
    if (ministerioSelect && departamentoSelect) {
        ministerioSelect.addEventListener('change', function() {
            // Aqui você pode implementar lógica para filtrar departamentos baseado no ministério selecionado
            // Por exemplo, fazer uma requisição AJAX para buscar departamentos do ministério
        });
    }
});
</script>
@endpush
@endsection 