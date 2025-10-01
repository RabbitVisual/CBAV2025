@extends('layouts.admin')

@section('title', 'Exportar Relatórios EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Exportar Relatórios EBD</h1>
                <p class="text-gray-600">Exporte relatórios em diferentes formatos</p>
            </div>
            <a href="{{ route('ebd.relatorios.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Relatórios Disponíveis -->
        <div class="space-y-6">
            <!-- Relatório Geral -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Relatório Geral</h3>
                        <p class="text-sm text-gray-600">Estatísticas gerais da EBD</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('ebd.relatorios.exportar') }}?tipo=geral&formato=excel" 
                           class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                            <i class="fas fa-file-excel mr-2"></i>
                            Excel
                        </a>
                        <a href="{{ route('ebd.relatorios.exportar') }}?tipo=geral&formato=pdf" 
                           class="inline-flex items-center px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition-colors">
                            <i class="fas fa-file-pdf mr-2"></i>
                            PDF
                        </a>
                    </div>
                </div>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Total de turmas e alunos</li>
                    <li>• Aulas realizadas e canceladas</li>
                    <li>• Avaliações aplicadas</li>
                    <li>• Média de presença geral</li>
                </ul>
            </div>

            <!-- Relatório de Presença -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Relatório de Presença</h3>
                        <p class="text-sm text-gray-600">Controle de frequência dos alunos</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('ebd.relatorios.exportar') }}?tipo=presenca&formato=excel" 
                           class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                            <i class="fas fa-file-excel mr-2"></i>
                            Excel
                        </a>
                        <a href="{{ route('ebd.relatorios.exportar') }}?tipo=presenca&formato=pdf" 
                           class="inline-flex items-center px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition-colors">
                            <i class="fas fa-file-pdf mr-2"></i>
                            PDF
                        </a>
                    </div>
                </div>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Lista de presença por aula</li>
                    <li>• Percentual de frequência por aluno</li>
                    <li>• Justificativas de ausência</li>
                    <li>• Relatório por turma</li>
                </ul>
            </div>

            <!-- Relatório de Notas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Relatório de Notas</h3>
                        <p class="text-sm text-gray-600">Desempenho dos alunos nas avaliações</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('ebd.relatorios.exportar') }}?tipo=notas&formato=excel" 
                           class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                            <i class="fas fa-file-excel mr-2"></i>
                            Excel
                        </a>
                        <a href="{{ route('ebd.relatorios.exportar') }}?tipo=notas&formato=pdf" 
                           class="inline-flex items-center px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition-colors">
                            <i class="fas fa-file-pdf mr-2"></i>
                            PDF
                        </a>
                    </div>
                </div>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Notas por avaliação</li>
                    <li>• Média geral por aluno</li>
                    <li>• Conceitos e aprovação</li>
                    <li>• Ranking de desempenho</li>
                </ul>
            </div>

            <!-- Relatório de Progresso -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Relatório de Progresso</h3>
                        <p class="text-sm text-gray-600">Acompanhamento do desenvolvimento</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('ebd.relatorios.exportar') }}?tipo=progresso&formato=excel" 
                           class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                            <i class="fas fa-file-excel mr-2"></i>
                            Excel
                        </a>
                        <a href="{{ route('ebd.relatorios.exportar') }}?tipo=progresso&formato=pdf" 
                           class="inline-flex items-center px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition-colors">
                            <i class="fas fa-file-pdf mr-2"></i>
                            PDF
                        </a>
                    </div>
                </div>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Evolução do aluno</li>
                    <li>• Participação em atividades</li>
                    <li>• Certificados emitidos</li>
                    <li>• Recomendações pedagógicas</li>
                </ul>
            </div>
        </div>

        <!-- Filtros e Configurações -->
        <div class="space-y-6">
            <!-- Filtros Avançados -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtros Avançados</h3>
                
                <form action="{{ route('ebd.relatorios.exportar') }}" method="GET">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Relatório</label>
                            <select name="tipo" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="geral">Relatório Geral</option>
                                <option value="presenca">Relatório de Presença</option>
                                <option value="notas">Relatório de Notas</option>
                                <option value="progresso">Relatório de Progresso</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Turma</label>
                            <select name="turma_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Todas as Turmas</option>
                                @foreach($turmas ?? [] as $turma)
                                    <option value="{{ $turma->id }}">{{ $turma->nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Data Início</label>
                                <input type="date" name="data_inicio" 
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Data Fim</label>
                                <input type="date" name="data_fim" 
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Formato</label>
                            <div class="flex space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" name="formato" value="excel" checked 
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">Excel</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="formato" value="pdf" 
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">PDF</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <i class="fas fa-download mr-2"></i>
                                Exportar Relatório
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Histórico de Exportações -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Histórico de Exportações</h3>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">Relatório Geral</p>
                            <p class="text-sm text-gray-600">Exportado em 15/01/2025</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                            Excel
                        </span>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">Relatório de Presença</p>
                            <p class="text-sm text-gray-600">Exportado em 10/01/2025</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                            PDF
                        </span>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">Relatório de Notas</p>
                            <p class="text-sm text-gray-600">Exportado em 05/01/2025</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                            Excel
                        </span>
                    </div>
                </div>
            </div>

            <!-- Informações -->
            <div class="bg-blue-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">ℹ️ Informações</h3>
                
                <div class="space-y-3">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                        <div>
                            <p class="text-sm text-gray-700 font-medium">Formato Excel</p>
                            <p class="text-sm text-gray-600">Ideal para análise de dados e criação de gráficos</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <i class="fas fa-file-pdf text-red-600 mt-1 mr-3"></i>
                        <div>
                            <p class="text-sm text-gray-700 font-medium">Formato PDF</p>
                            <p class="text-sm text-gray-600">Perfeito para impressão e compartilhamento</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <i class="fas fa-clock text-yellow-600 mt-1 mr-3"></i>
                        <div>
                            <p class="text-sm text-gray-700 font-medium">Tempo de Processamento</p>
                            <p class="text-sm text-gray-600">Relatórios grandes podem levar alguns minutos</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 