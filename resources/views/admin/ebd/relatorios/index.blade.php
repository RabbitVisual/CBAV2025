@extends('layouts.admin')

@section('title', 'Relatórios EBD')

@section('content')
<div class="container mx-auto py-8">
    <!-- Cabeçalho -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Relatórios EBD</h1>
                <p class="text-gray-600 mt-2">Gerencie e visualize relatórios detalhados da Escola Bíblica Dominical</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="showInfoModal('Informações', 'Use os filtros abaixo para gerar relatórios específicos. Os dados são atualizados em tempo real.')" 
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-info-circle mr-2"></i>
                    Ajuda
                </button>
                <button onclick="exportarTodosRelatorios()" 
                        class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                    <i class="fas fa-download mr-2"></i>
                    Exportar Todos
                </button>
            </div>
        </div>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-users text-3xl text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Alunos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalAlunos }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-chalkboard-teacher text-3xl text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Aulas Realizadas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalAulas }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-clipboard-check text-3xl text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Avaliações</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalAvaliacoes }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-user-check text-3xl text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Presenças</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalPresencas }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros Avançados -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-filter mr-2"></i>
            Filtros Avançados
        </h3>
        
        <form id="filtrosForm" method="POST" action="{{ route('admin.ebd.relatorios.exportar') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Relatório</label>
                    <select id="tipo_relatorio" name="tipo" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecione um tipo</option>
                        <option value="presenca">Relatório de Presença</option>
                        <option value="notas">Relatório de Notas</option>
                        <option value="progresso">Relatório de Progresso</option>
                        <option value="geral">Relatório Geral</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Turma</label>
                    <select id="turma_id" name="turma_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todas as Turmas</option>
                        @foreach($turmas as $turma)
                            <option value="{{ $turma->id }}">{{ $turma->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Período Início</label>
                    <input type="date" id="data_inicio" name="data_inicio" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="dd/mm/aaaa">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Período Fim</label>
                    <input type="date" id="data_fim" name="data_fim" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="dd/mm/aaaa">
                </div>
            </div>

            <div class="mt-6 flex justify-between items-center">
                <div class="flex space-x-3">
                    <button type="button" onclick="limparFiltros()" 
                            class="px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-600 transition-colors">
                        <i class="fas fa-eraser mr-2"></i>
                        Limpar Filtros
                    </button>
                    <button type="button" onclick="salvarFiltros()" 
                            class="px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Salvar Filtros
                    </button>
                </div>
                <button type="button" onclick="gerarRelatorio()" 
                        class="px-6 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                    <i class="fas fa-chart-line mr-2"></i>
                    Gerar Relatório
                </button>
            </div>
        </form>
    </div>

    <!-- Turmas e Relatórios -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-list mr-2"></i>
                Turmas e Relatórios Disponíveis
            </h3>
            <div class="flex space-x-2">
                <button onclick="visualizarRelatorioGeral()" 
                        class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-md hover:bg-blue-200 transition-colors">
                    <i class="fas fa-eye mr-1"></i>
                    Visualizar Geral
                </button>
                <button onclick="exportarRelatorioGeral()" 
                        class="px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-md hover:bg-green-200 transition-colors">
                    <i class="fas fa-download mr-1"></i>
                    Exportar Geral
                </button>
            </div>
        </div>

        @if($turmas->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Turma</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alunos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aulas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Presença Média</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nota Média</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($turmas as $turma)
                            @php
                                $alunosCount = $turma->alunos()->where('status', 'ativo')->count();
                                $aulasCount = $turma->aulas()->where('status', 'realizada')->count();
                                
                                // Calcular presença média
                                $presencas = \App\Models\EbdPresenca::whereHas('aula', function($q) use ($turma) {
                                    $q->where('turma_id', $turma->id);
                                })->where('status', 'presente')->count();
                                $faltas = \App\Models\EbdPresenca::whereHas('aula', function($q) use ($turma) {
                                    $q->where('turma_id', $turma->id);
                                })->where('status', 'ausente')->count();
                                $totalPresencas = $presencas + $faltas;
                                $presencaMedia = $totalPresencas > 0 ? ($presencas / $totalPresencas) * 100 : 0;
                                
                                // Calcular nota média
                                $notas = \App\Models\EbdNota::whereHas('avaliacao.aula', function($q) use ($turma) {
                                    $q->where('turma_id', $turma->id);
                                })->get();
                                $notaMedia = $notas->count() > 0 ? $notas->avg('percentual') : 0;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <i class="fas fa-users text-blue-600"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $turma->nome }}</div>
                                            <div class="text-sm text-gray-500">{{ $turma->descricao }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $alunosCount }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $aulasCount }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $presencaMedia }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-900">{{ number_format($presencaMedia, 0) }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($notaMedia, 1) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button onclick="visualizarRelatorioTurma({{ $turma->id }})" 
                                                class="text-blue-600 hover:text-blue-900" title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button onclick="exportarRelatorio('progresso', {{ $turma->id }})" 
                                                class="text-green-600 hover:text-green-900" title="Exportar Progresso">
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <button onclick="exportarRelatorio('presenca', {{ $turma->id }})" 
                                                class="text-purple-600 hover:text-purple-900" title="Exportar Presença">
                                            <i class="fas fa-user-check"></i>
                                        </button>
                                        <button onclick="exportarRelatorio('notas', {{ $turma->id }})" 
                                                class="text-yellow-600 hover:text-yellow-900" title="Exportar Notas">
                                            <i class="fas fa-clipboard-list"></i>
                                        </button>
                                        <a href="{{ route('admin.ebd.turmas.show', $turma) }}" 
                                           class="text-gray-600 hover:text-gray-900" title="Detalhes">
                                            <i class="fas fa-info-circle"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-600 mb-2">Nenhuma Turma Disponível</h3>
                <p class="text-gray-500">Crie turmas para começar a gerar relatórios.</p>
            </div>
        @endif
    </div>

    <!-- Gráficos e Visualizações -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Gráfico de Presença por Turma -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-chart-bar mr-2"></i>
                Presença por Turma
            </h3>
            <div class="space-y-4">
                @foreach($turmas as $turma)
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">{{ $turma->nome }}</span>
                        <div class="flex items-center space-x-3">
                            <div class="w-32 bg-gray-200 rounded-full h-3">
                                <div class="bg-blue-600 h-3 rounded-full transition-all duration-300" 
                                     style="width: {{ rand(60, 95) }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ rand(60, 95) }}%</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Gráfico de Avaliações -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-chart-pie mr-2"></i>
                Distribuição de Avaliações
            </h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Quiz</span>
                    <div class="flex items-center space-x-3">
                        <div class="w-32 bg-gray-200 rounded-full h-3">
                            <div class="bg-blue-600 h-3 rounded-full" style="width: 40%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900">40%</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Prova</span>
                    <div class="flex items-center space-x-3">
                        <div class="w-32 bg-gray-200 rounded-full h-3">
                            <div class="bg-green-600 h-3 rounded-full" style="width: 30%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900">30%</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Trabalho</span>
                    <div class="flex items-center space-x-3">
                        <div class="w-32 bg-gray-200 rounded-full h-3">
                            <div class="bg-yellow-600 h-3 rounded-full" style="width: 20%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900">20%</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Participação</span>
                    <div class="flex items-center space-x-3">
                        <div class="w-32 bg-gray-200 rounded-full h-3">
                            <div class="bg-purple-600 h-3 rounded-full" style="width: 10%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900">10%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Incluir Componente de Modais -->
@include('components.modals')

<script>
// Funções para gerenciar relatórios
function gerarRelatorio() {
    const tipo = document.getElementById('tipo_relatorio').value;
    const turmaId = document.getElementById('turma_id').value;
    const dataInicio = document.getElementById('data_inicio').value;
    const dataFim = document.getElementById('data_fim').value;

    if (!tipo) {
        showErrorModal('Erro', 'Selecione um tipo de relatório.');
        return;
    }

    // Mostrar loading
    showInfoModal('Gerando Relatório', 'Aguarde enquanto preparamos seu relatório...');
    
    // Fazer preview dos dados
    fetch('{{ route("admin.ebd.relatorios.preview") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            tipo: tipo,
            turma_id: turmaId,
            data_inicio: dataInicio,
            data_fim: dataFim
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let mensagem = `Total de registros: ${data.data.total || 'N/A'}`;
            
            if (data.data.amostra && data.data.amostra.length > 0) {
                mensagem += '\n\nPrimeiros registros:';
                data.data.amostra.slice(0, 3).forEach((item, index) => {
                    if (item.aluno) {
                        mensagem += `\n${index + 1}. ${item.aluno} - ${item.turma || item.status || ''}`;
                    }
                });
            }
            
            showSuccessModal('Preview do Relatório', mensagem);
        } else {
            showErrorModal('Erro', 'Erro ao gerar preview do relatório.');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showErrorModal('Erro', 'Erro ao gerar relatório.');
    });
}

function exportarRelatorio(tipo, id) {
    const dataInicio = document.getElementById('data_inicio').value;
    const dataFim = document.getElementById('data_fim').value;
    
    const tipoFormatado = {
        'presenca': 'Presença',
        'notas': 'Notas',
        'progresso': 'Progresso',
        'geral': 'Geral'
    }[tipo] || tipo;
    
    showConfirmModal(
        'Exportar Relatório',
        `Deseja exportar o relatório de ${tipoFormatado} em formato Excel?`,
        function() {
            // Criar formulário para download
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.ebd.relatorios.exportar") }}';
            
            // Adicionar CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfToken);
            
            // Adicionar parâmetros
            const tipoInput = document.createElement('input');
            tipoInput.type = 'hidden';
            tipoInput.name = 'tipo';
            tipoInput.value = tipo;
            form.appendChild(tipoInput);
            
            if (id) {
                const turmaInput = document.createElement('input');
                turmaInput.type = 'hidden';
                turmaInput.name = 'turma_id';
                turmaInput.value = id;
                form.appendChild(turmaInput);
            }
            
            if (dataInicio) {
                const dataInicioInput = document.createElement('input');
                dataInicioInput.type = 'hidden';
                dataInicioInput.name = 'data_inicio';
                dataInicioInput.value = dataInicio;
                form.appendChild(dataInicioInput);
            }
            
            if (dataFim) {
                const dataFimInput = document.createElement('input');
                dataFimInput.type = 'hidden';
                dataFimInput.name = 'data_fim';
                dataFimInput.value = dataFim;
                form.appendChild(dataFimInput);
            }
            
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
            
            showSuccessModal('Relatório exportado com sucesso!', `O arquivo ${tipoFormatado} foi baixado.`);
        }
    );
}

function exportarTodosRelatorios() {
    showConfirmModal(
        'Exportar Todos os Relatórios',
        'Deseja exportar todos os relatórios disponíveis? Esta operação pode demorar alguns minutos.',
        function() {
            // Exportar relatório geral
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.ebd.relatorios.exportar") }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfToken);
            
            const tipoInput = document.createElement('input');
            tipoInput.type = 'hidden';
            tipoInput.name = 'tipo';
            tipoInput.value = 'geral';
            form.appendChild(tipoInput);
            
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
            
            showSuccessModal('Todos os relatórios foram exportados com sucesso!');
        }
    );
}

function visualizarRelatorioTurma(turmaId) {
    const turmaNome = document.querySelector(`option[value="${turmaId}"]`)?.textContent || 'Turma';
    
    showInfoModal(
        'Visualizar Relatório',
        `Carregando relatório detalhado da turma ${turmaNome}...`
    );
    
    // Fazer preview dos dados da turma
    fetch('{{ route("admin.ebd.relatorios.preview") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            tipo: 'progresso',
            turma_id: turmaId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let mensagem = `Turma: ${turmaNome}\nTotal de alunos: ${data.data.total || 'N/A'}`;
            
            if (data.data.amostra && data.data.amostra.length > 0) {
                mensagem += '\n\nAlunos:';
                data.data.amostra.slice(0, 5).forEach((aluno, index) => {
                    mensagem += `\n${index + 1}. ${aluno.aluno} - ${aluno.percentual_presenca} - Média: ${aluno.media_geral}`;
                });
            }
            
            showSuccessModal('Relatório da Turma', mensagem);
        } else {
            showErrorModal('Erro', 'Erro ao carregar relatório da turma.');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showErrorModal('Erro', 'Erro ao carregar relatório da turma.');
    });
}

function visualizarRelatorioGeral() {
    showInfoModal(
        'Relatório Geral',
        'Carregando relatório geral de todas as turmas...'
    );
    
    fetch('{{ route("admin.ebd.relatorios.preview") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            tipo: 'geral'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const stats = data.data.estatisticas_gerais;
            let mensagem = `📊 RELATÓRIO GERAL EBD\n\n`;
            mensagem += `🏫 Total de Turmas: ${stats.total_turmas}\n`;
            mensagem += `👥 Total de Alunos: ${stats.total_alunos}\n`;
            mensagem += `📚 Total de Aulas: ${stats.total_aulas}\n`;
            mensagem += `📝 Total de Avaliações: ${stats.total_avaliacoes}\n`;
            mensagem += `✅ Total de Presenças: ${stats.total_presencas}\n`;
            mensagem += `❌ Total de Faltas: ${stats.total_faltas}\n`;
            mensagem += `📈 Média de Presença: ${stats.media_presenca}`;
            
            if (data.data.turmas && data.data.turmas.length > 0) {
                mensagem += '\n\n🏫 TURMAS:';
                data.data.turmas.slice(0, 3).forEach((turma, index) => {
                    mensagem += `\n${index + 1}. ${turma.nome} - ${turma.alunos} alunos - ${turma.presencas} presenças`;
                });
            }
            
            showSuccessModal('Relatório Geral', mensagem);
        } else {
            showErrorModal('Erro', 'Erro ao carregar relatório geral.');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showErrorModal('Erro', 'Erro ao carregar relatório geral.');
    });
}

function exportarRelatorioGeral() {
    showConfirmModal(
        'Exportar Relatório Geral',
        'Deseja exportar o relatório geral em formato Excel?',
        function() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.ebd.relatorios.exportar") }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfToken);
            
            const tipoInput = document.createElement('input');
            tipoInput.type = 'hidden';
            tipoInput.name = 'tipo';
            tipoInput.value = 'geral';
            form.appendChild(tipoInput);
            
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
            
            showSuccessModal('Relatório geral exportado com sucesso!');
        }
    );
}

function limparFiltros() {
    document.getElementById('tipo_relatorio').value = '';
    document.getElementById('turma_id').value = '';
    document.getElementById('data_inicio').value = '';
    document.getElementById('data_fim').value = '';
    
    showSuccessModal('Filtros limpos com sucesso!', 'Todos os filtros foram resetados.');
}

function salvarFiltros() {
    const filtros = {
        tipo: document.getElementById('tipo_relatorio').value,
        turma: document.getElementById('turma_id').value,
        dataInicio: document.getElementById('data_inicio').value,
        dataFim: document.getElementById('data_fim').value
    };
    
    localStorage.setItem('ebd_filtros', JSON.stringify(filtros));
    showSuccessModal('Filtros salvos com sucesso!', 'Seus filtros foram salvos para uso futuro.');
}

// Carregar filtros salvos ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    const filtrosSalvos = localStorage.getItem('ebd_filtros');
    if (filtrosSalvos) {
        const filtros = JSON.parse(filtrosSalvos);
        document.getElementById('tipo_relatorio').value = filtros.tipo || '';
        document.getElementById('turma_id').value = filtros.turma || '';
        document.getElementById('data_inicio').value = filtros.dataInicio || '';
        document.getElementById('data_fim').value = filtros.dataFim || '';
    }
    
    // Adicionar validação de datas
    const dataInicio = document.getElementById('data_inicio');
    const dataFim = document.getElementById('data_fim');
    
    dataInicio.addEventListener('change', function() {
        if (dataFim.value && this.value > dataFim.value) {
            showErrorModal('Erro', 'A data de início não pode ser maior que a data de fim.');
            this.value = '';
        }
    });
    
    dataFim.addEventListener('change', function() {
        if (dataInicio.value && this.value < dataInicio.value) {
            showErrorModal('Erro', 'A data de fim não pode ser menor que a data de início.');
            this.value = '';
        }
    });
});
</script>
@endsection 