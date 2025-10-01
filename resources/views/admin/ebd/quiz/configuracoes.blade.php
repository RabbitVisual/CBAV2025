@extends('layouts.admin')

@section('page-content')
<div class="space-y-6">
    <!-- Cabeçalho -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Configurações do Quiz Bíblico</h1>
            <p class="text-gray-600">Personalize o comportamento do sistema de quiz e notificações</p>
        </div>
        <a href="{{ route('admin.ebd.quiz-biblico.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Voltar
        </a>
    </div>

    <!-- Abas de Configuração -->
    <div class="bg-white rounded-lg shadow">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                <button onclick="showTab('geral')" 
                        class="tab-button active border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                        data-tab="geral">
                    <i class="fas fa-cogs mr-2"></i>
                    Configurações Gerais
                </button>
                <button onclick="showTab('email')" 
                        class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                        data-tab="email">
                    <i class="fas fa-envelope mr-2"></i>
                    Configurações de E-mail
                </button>
                <button onclick="showTab('template')" 
                        class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                        data-tab="template">
                    <i class="fas fa-palette mr-2"></i>
                    Template de E-mail
                </button>
                <button onclick="showTab('teste')" 
                        class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                        data-tab="teste">
                    <i class="fas fa-vial mr-2"></i>
                    Testar Configurações
                </button>
            </nav>
        </div>

        <!-- Conteúdo das Abas -->
        <div class="p-6">
            <!-- Aba: Configurações Gerais -->
            <div id="tab-geral" class="tab-content">
                <form action="{{ route('admin.ebd.quiz-biblico.configuracoes.atualizar') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="tab" value="geral">
                    
                    <!-- Tempo Limite -->
                    <div>
                        <label for="tempo_limite" class="block text-sm font-medium text-gray-700">
                            Tempo Limite por Pergunta (segundos)
                        </label>
                        <input type="number" 
                               id="tempo_limite" 
                               name="tempo_limite" 
                               value="{{ $configuracoes['tempo_limite'] }}"
                               min="10" 
                               max="120"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-sm text-gray-500">Tempo máximo para responder cada pergunta</p>
                    </div>

                    <!-- Perguntas por Sessão -->
                    <div>
                        <label for="perguntas_por_sessao" class="block text-sm font-medium text-gray-700">
                            Perguntas por Sessão
                        </label>
                        <input type="number" 
                               id="perguntas_por_sessao" 
                               name="perguntas_por_sessao" 
                               value="{{ $configuracoes['perguntas_por_sessao'] }}"
                               min="5" 
                               max="50"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-sm text-gray-500">Número de perguntas em cada sessão de quiz</p>
                    </div>

                    <!-- Pontuações por Nível -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="pontuacao_facil" class="block text-sm font-medium text-gray-700">
                                Pontuação - Fácil
                            </label>
                            <input type="number" 
                                   id="pontuacao_facil" 
                                   name="pontuacao_facil" 
                                   value="{{ $configuracoes['pontuacao_facil'] }}"
                                   min="1" 
                                   max="100"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label for="pontuacao_medio" class="block text-sm font-medium text-gray-700">
                                Pontuação - Médio
                            </label>
                            <input type="number" 
                                   id="pontuacao_medio" 
                                   name="pontuacao_medio" 
                                   value="{{ $configuracoes['pontuacao_medio'] }}"
                                   min="1" 
                                   max="100"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label for="pontuacao_dificil" class="block text-sm font-medium text-gray-700">
                                Pontuação - Difícil
                            </label>
                            <input type="number" 
                                   id="pontuacao_dificil" 
                                   name="pontuacao_dificil" 
                                   value="{{ $configuracoes['pontuacao_dificil'] }}"
                                   min="1" 
                                   max="100"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Notificações -->
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="notificar_novos_recordes" 
                                   name="notificar_novos_recordes" 
                                   value="1"
                                   {{ $configuracoes['notificar_novos_recordes'] ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="notificar_novos_recordes" class="ml-2 block text-sm text-gray-900">
                                Notificar novos recordes
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="notificar_recordes_pessoais" 
                                   name="notificar_recordes_pessoais" 
                                   value="1"
                                   {{ $configuracoes['notificar_recordes_pessoais'] ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="notificar_recordes_pessoais" class="ml-2 block text-sm text-gray-900">
                                Notificar recordes pessoais
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="notificar_admins_recordes" 
                                   name="notificar_admins_recordes" 
                                   value="1"
                                   {{ $configuracoes['notificar_admins_recordes'] ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="notificar_admins_recordes" class="ml-2 block text-sm text-gray-900">
                                Notificar admins sobre recordes globais
                            </label>
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="flex justify-end space-x-3">
                        <button type="submit" 
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Salvar Configurações Gerais
                        </button>
                    </div>
                </form>
            </div>

            <!-- Aba: Configurações de E-mail -->
            <div id="tab-email" class="tab-content hidden">
                <form action="{{ route('admin.ebd.quiz-biblico.configuracoes.atualizar') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="tab" value="email">
                    
                    <!-- Configurações SMTP -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Configurações SMTP</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="mail_host" class="block text-sm font-medium text-gray-700">
                                    Servidor SMTP
                                </label>
                                <input type="text" 
                                       id="mail_host" 
                                       name="mail_host" 
                                       value="{{ $configuracoes['mail_host'] ?? 'smtp.gmail.com' }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label for="mail_port" class="block text-sm font-medium text-gray-700">
                                    Porta SMTP
                                </label>
                                <input type="number" 
                                       id="mail_port" 
                                       name="mail_port" 
                                       value="{{ $configuracoes['mail_port'] ?? '587' }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label for="mail_username" class="block text-sm font-medium text-gray-700">
                                    E-mail
                                </label>
                                <input type="email" 
                                       id="mail_username" 
                                       name="mail_username" 
                                       value="{{ $configuracoes['mail_username'] ?? '' }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label for="mail_password" class="block text-sm font-medium text-gray-700">
                                    Senha do App
                                </label>
                                <input type="password" 
                                       id="mail_password" 
                                       name="mail_password" 
                                       value="{{ $configuracoes['mail_password'] ?? '' }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-sm text-gray-500">Use senha de app do Gmail</p>
                            </div>
                        </div>
                    </div>

                    <!-- Configurações de Remetente -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Configurações de Remetente</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="mail_from_address" class="block text-sm font-medium text-gray-700">
                                    E-mail Remetente
                                </label>
                                <input type="email" 
                                       id="mail_from_address" 
                                       name="mail_from_address" 
                                       value="{{ $configuracoes['mail_from_address'] ?? 'admin@cbav.com' }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label for="mail_from_name" class="block text-sm font-medium text-gray-700">
                                    Nome Remetente
                                </label>
                                <input type="text" 
                                       id="mail_from_name" 
                                       name="mail_from_name" 
                                       value="{{ $configuracoes['mail_from_name'] ?? 'CBAV Sistema' }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Configurações de Jobs -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Configurações de Jobs</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="queue_retry_after" class="block text-sm font-medium text-gray-700">
                                    Retry After (segundos)
                                </label>
                                <input type="number" 
                                       id="queue_retry_after" 
                                       name="queue_retry_after" 
                                       value="{{ $configuracoes['queue_retry_after'] ?? '90' }}"
                                       min="30" 
                                       max="300"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label for="queue_timeout" class="block text-sm font-medium text-gray-700">
                                    Timeout (segundos)
                                </label>
                                <input type="number" 
                                       id="queue_timeout" 
                                       name="queue_timeout" 
                                       value="{{ $configuracoes['queue_timeout'] ?? '60' }}"
                                       min="30" 
                                       max="300"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label for="job_tries" class="block text-sm font-medium text-gray-700">
                                    Tentativas de Job
                                </label>
                                <input type="number" 
                                       id="job_tries" 
                                       name="job_tries" 
                                       value="{{ $configuracoes['job_tries'] ?? '3' }}"
                                       min="1" 
                                       max="10"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="flex justify-end space-x-3">
                        <button type="submit" 
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Salvar Configurações de E-mail
                        </button>
                    </div>
                </form>
            </div>

            <!-- Aba: Template de E-mail -->
            <div id="tab-template" class="tab-content hidden">
                <div class="space-y-6">
                    <!-- Preview do Template -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Preview do Template de E-mail</h4>
                        
                        <div class="bg-white border rounded-lg p-4 max-w-2xl">
                            <div class="text-center mb-4">
                                <h2 class="text-xl font-bold text-blue-600">🏆 Novo Recorde Global no Quiz Bíblico!</h2>
                                <p class="text-gray-600">Sistema CBAV - Quiz Bíblico</p>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="text-center">
                                    <div class="inline-block bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-6 py-3 rounded-full font-bold">
                                        🏆 RECORDE GLOBAL
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                    <p class="text-lg">Olá, <strong>Nome do Usuário</strong>!</p>
                                    <p class="text-gray-700">Parabéns! Você estabeleceu um <strong>novo recorde global</strong> no Quiz Bíblico!</p>
                                </div>
                                
                                <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4 text-center">
                                    <h3 class="font-bold text-gray-900">Pontuação Total</h3>
                                    <div class="text-3xl font-bold text-blue-600">160 pontos</div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div class="bg-gray-100 p-3 rounded text-center">
                                        <div class="text-gray-500 uppercase text-xs">Nível</div>
                                        <div class="font-bold">Médio</div>
                                    </div>
                                    <div class="bg-gray-100 p-3 rounded text-center">
                                        <div class="text-gray-500 uppercase text-xs">Categoria</div>
                                        <div class="font-bold">Antigo Testamento</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Configurações do Template -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Configurações do Template</h4>
                        
                        <form action="{{ route('admin.ebd.quiz-biblico.configuracoes.atualizar') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="tab" value="template">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="template_primary_color" class="block text-sm font-medium text-gray-700">
                                        Cor Primária
                                    </label>
                                    <input type="color" 
                                           id="template_primary_color" 
                                           name="template_primary_color" 
                                           value="{{ $configuracoes['template_primary_color'] ?? '#2563eb' }}"
                                           class="mt-1 block w-full h-10 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div>
                                    <label for="template_secondary_color" class="block text-sm font-medium text-gray-700">
                                        Cor Secundária
                                    </label>
                                    <input type="color" 
                                           id="template_secondary_color" 
                                           name="template_secondary_color" 
                                           value="{{ $configuracoes['template_secondary_color'] ?? '#fbbf24' }}"
                                           class="mt-1 block w-full h-10 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            
                            <div>
                                <label for="template_logo_url" class="block text-sm font-medium text-gray-700">
                                    URL do Logo
                                </label>
                                <input type="url" 
                                       id="template_logo_url" 
                                       name="template_logo_url" 
                                       value="{{ $configuracoes['template_logo_url'] ?? '' }}"
                                       placeholder="https://exemplo.com/logo.png"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label for="template_footer_text" class="block text-sm font-medium text-gray-700">
                                    Texto do Rodapé
                                </label>
                                <textarea id="template_footer_text" 
                                          name="template_footer_text" 
                                          rows="3"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ $configuracoes['template_footer_text'] ?? 'Continue estudando a Palavra de Deus e testando seus conhecimentos. Cada sessão é uma oportunidade de crescimento espiritual!' }}</textarea>
                            </div>
                            
                            <div class="flex justify-end">
                                <button type="submit" 
                                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Salvar Configurações do Template
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Aba: Testar Configurações -->
            <div id="tab-teste" class="tab-content hidden">
                <div class="space-y-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Teste de Configurações</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>Use os comandos abaixo para testar as configurações de e-mail:</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Comandos de Teste</h4>
                        
                        <div class="space-y-3">
                            <div class="bg-white border rounded p-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <strong class="text-gray-900">Teste de E-mail Direto</strong>
                                        <p class="text-sm text-gray-600">Envia e-mail diretamente para testar configurações SMTP</p>
                                    </div>
                                    <button onclick="executarTeste('direct')" 
                                            class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                                        Executar
                                    </button>
                                </div>
                            </div>
                            
                            <div class="bg-white border rounded p-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <strong class="text-gray-900">Teste de Job de Recorde</strong>
                                        <p class="text-sm text-gray-600">Testa o envio de e-mail através de job em background</p>
                                    </div>
                                    <button onclick="executarTeste('job')" 
                                            class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                                        Executar
                                    </button>
                                </div>
                            </div>
                            
                            <div class="bg-white border rounded p-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <strong class="text-gray-900">Teste de Recorde Global</strong>
                                        <p class="text-sm text-gray-600">Testa notificação de recorde global para admins</p>
                                    </div>
                                    <button onclick="executarTeste('global')" 
                                            class="px-3 py-1 bg-purple-600 text-white text-sm rounded hover:bg-purple-700">
                                        Executar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Resultado do Teste -->
                    <div id="teste-resultado" class="hidden">
                        <div class="bg-white border rounded-lg p-4">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Resultado do Teste</h4>
                            <div id="teste-output" class="bg-gray-100 p-3 rounded font-mono text-sm"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informações Adicionais -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Informações</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>As configurações são aplicadas imediatamente após salvar</li>
                        <li>Para Gmail, use senha de app em vez da senha normal</li>
                        <li>Teste as configurações de e-mail antes de usar em produção</li>
                        <li>Os jobs são processados em background para melhor performance</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Esconder todas as abas
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });
    
    // Remover classe active de todos os botões
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Mostrar aba selecionada
    document.getElementById('tab-' + tabName).classList.remove('hidden');
    
    // Ativar botão selecionado
    document.querySelector(`[data-tab="${tabName}"]`).classList.add('active', 'border-blue-500', 'text-blue-600');
    document.querySelector(`[data-tab="${tabName}"]`).classList.remove('border-transparent', 'text-gray-500');
}

function executarTeste(tipo) {
    const resultadoDiv = document.getElementById('teste-resultado');
    const outputDiv = document.getElementById('teste-output');
    
    resultadoDiv.classList.remove('hidden');
    outputDiv.innerHTML = 'Executando teste...';
    
    // Simular execução do comando
    fetch(`{{ route('admin.ebd.quiz-biblico.testar-email') }}?tipo=${tipo}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        outputDiv.innerHTML = data.mensagem;
    })
    .catch(error => {
        outputDiv.innerHTML = 'Erro ao executar teste: ' + error.message;
    });
}
</script>
@endsection 