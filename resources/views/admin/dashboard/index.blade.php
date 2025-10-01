@extends('layouts.admin')

@section('title', 'Painel Principal - CBAV CRM Ministerial')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header de Boas-vindas -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center">
            <div class="mr-4">
                @if(file_exists(public_path('img/logo.png')))
                    <img src="{{ asset('img/logo.png') }}" alt="CBAV Logo" class="h-16 w-auto">
                @else
                    <div class="h-16 w-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-church text-white text-2xl"></i>
                    </div>
                @endif
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Painel Principal</h1>
                <p class="text-gray-600 dark:text-gray-300 mt-2">Sistema completo de gestão para a Congregação Batista Avenida</p>
                <div class="flex items-center mt-2 text-sm text-gray-500 dark:text-gray-400">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    {{ now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-4">
            <div class="text-right">
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Logado como</p>
                <p class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ auth()->user()->name }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-300">{{ auth()->user()->email }}</p>
            </div>
            @if(auth()->user()->foto_existe)
                <div class="w-12 h-12 rounded-full overflow-hidden ring-2 ring-blue-100 dark:ring-blue-800">
                    <img src="{{ auth()->user()->foto_url }}" 
                         alt="{{ auth()->user()->name }}" 
                         class="w-full h-full object-cover">
                </div>
            @else
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center ring-2 ring-blue-100 dark:ring-blue-800">
                    <span class="text-white text-lg font-bold">{{ auth()->user()->iniciais }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Estatísticas Principais -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900/20 p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Membros Ativos</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ isset($estatisticas['membros_ativos']) ? $estatisticas['membros_ativos'] : 0 }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Total: {{ isset($estatisticas['total_membros']) ? $estatisticas['total_membros'] : 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900/20 p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                    <i class="fas fa-hands-praying text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Ministérios</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ isset($estatisticas['ministerios_ativos']) ? $estatisticas['ministerios_ativos'] : 0 }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Em funcionamento</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900/20 p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400">
                    <i class="fas fa-hand-holding-heart text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Doações do Mês</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">R$ {{ isset($estatisticas['valor_mes']) ? number_format($estatisticas['valor_mes'], 0, ',', '.') : '0' }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ isset($estatisticas['doacoes_mes']) ? $estatisticas['doacoes_mes'] : 0 }} transações</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900/20 p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400">
                    <i class="fas fa-bullhorn text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Campanhas Ativas</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ isset($estatisticas['campanhas_ativas']) ? $estatisticas['campanhas_ativas'] : 0 }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Arrecadações especiais</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos e Atividades Recentes -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Gráfico de Doações -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900/20 p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Doações dos Últimos 6 Meses</h3>
            <canvas id="doacoesChart" width="400" height="200"></canvas>
        </div>

        <!-- Atividades Recentes -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900/20 p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Atividades Recentes</h3>
            <div class="space-y-4">
                @if(isset($atividades_recentes) && count($atividades_recentes) > 0)
                    @foreach($atividades_recentes as $atividade)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                    <i class="fas fa-{{ $atividade['icone'] ?? 'info' }} text-blue-600 dark:text-blue-400 text-sm"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $atividade['titulo'] }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $atividade['descricao'] }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">{{ $atividade['data'] }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-inbox text-gray-400 dark:text-gray-500 text-3xl mb-2"></i>
                        <p class="text-gray-500 dark:text-gray-400">Nenhuma atividade recente</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Principais Funcionalidades -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900/20 mb-8 border border-gray-200 dark:border-gray-700">
        <div class="p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6">Principais Funcionalidades</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Gestão de Pessoas -->
                <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-users text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Gestão de Pessoas</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 mb-4 text-sm">
                        Cadastro completo de membros, controle de aniversariantes e gestão de ministérios.
                    </p>
                    <div class="space-y-2">
                        <a href="{{ route('admin.people.members.index') }}" class="block text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm">
                            <i class="fas fa-arrow-right mr-1"></i>Gerenciar Membros
                        </a>
                        <a href="{{ route('admin.people.ministries.index') }}" class="block text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm">
                            <i class="fas fa-arrow-right mr-1"></i>Ministérios
                        </a>
                        <a href="{{ route('admin.people.birthdays.index') }}" class="block text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm">
                            <i class="fas fa-arrow-right mr-1"></i>Aniversariantes
                        </a>
                    </div>
                </div>

                <!-- Gestão Financeira -->
                <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-chart-line text-green-600 dark:text-green-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Gestão Financeira</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 mb-4 text-sm">
                        Controle de doações, campanhas especiais e relatórios financeiros detalhados.
                    </p>
                    <div class="space-y-2">
                        <a href="{{ route('admin.finance.transactions.index') }}" class="block text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 text-sm">
                            <i class="fas fa-arrow-right mr-1"></i>Transações
                        </a>
                        <a href="{{ route('admin.finance.campaigns.index') }}" class="block text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 text-sm">
                            <i class="fas fa-arrow-right mr-1"></i>Campanhas
                        </a>
                        <a href="{{ route('admin.finance.reports.index') }}" class="block text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 text-sm">
                            <i class="fas fa-arrow-right mr-1"></i>Relatórios
                        </a>
                    </div>
                </div>

                <!-- Escola Bíblica -->
                <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-graduation-cap text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Escola Bíblica</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 mb-4 text-sm">
                        Gestão completa da EBD, turmas, alunos e sistema de quiz bíblico interativo.
                    </p>
                    <div class="space-y-2">
                        <a href="{{ route('admin.ebd.turmas.index') }}" class="block text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 text-sm">
                            <i class="fas fa-arrow-right mr-1"></i>Turmas EBD
                        </a>
                        <a href="{{ route('admin.ebd.quiz-biblico.index') }}" class="block text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 text-sm">
                            <i class="fas fa-arrow-right mr-1"></i>Quiz Bíblico
                        </a>
                        <a href="{{ route('admin.ebd.certificados.index') }}" class="block text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 text-sm">
                            <i class="fas fa-arrow-right mr-1"></i>Certificados
                        </a>
                    </div>
                </div>

                <!-- Eventos e Comunicação -->
                <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-calendar-alt text-orange-600 dark:text-orange-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Eventos e Comunicação</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 mb-4 text-sm">
                        Gestão de eventos, notificações e comunicação com a comunidade.
                    </p>
                    <div class="space-y-2">
                        <a href="{{ route('admin.eventos.index') }}" class="block text-orange-600 dark:text-orange-400 hover:text-orange-800 dark:hover:text-orange-300 text-sm">
                            <i class="fas fa-arrow-right mr-1"></i>Eventos
                        </a>
                        <a href="{{ route('admin.system.notifications.index') }}" class="block text-orange-600 dark:text-orange-400 hover:text-orange-800 dark:hover:text-orange-300 text-sm">
                            <i class="fas fa-arrow-right mr-1"></i>Notificações
                        </a>
                        <a href="{{ route('admin.chat.index') }}" class="block text-orange-600 dark:text-orange-400 hover:text-orange-800 dark:hover:text-orange-300 text-sm">
                            <i class="fas fa-arrow-right mr-1"></i>Chat
                        </a>
                    </div>
                </div>

                <!-- Relatórios e Estatísticas -->
                <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-chart-bar text-indigo-600 dark:text-indigo-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Relatórios e Estatísticas</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 mb-4 text-sm">
                        Análises detalhadas, relatórios personalizados e insights sobre a igreja.
                    </p>
                    <div class="space-y-2">
                        <a href="{{ route('admin.people.reports.index') }}" class="block text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-sm">
                            <i class="fas fa-arrow-right mr-1"></i>Relatórios Pessoas
                        </a>
                        <a href="{{ route('admin.ebd.quiz-biblico.estatisticas') }}" class="block text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-sm">
                            <i class="fas fa-arrow-right mr-1"></i>Estatísticas Quiz
                        </a>
                        <a href="{{ route('admin.finance.reports.index') }}" class="block text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-sm">
                            <i class="fas fa-arrow-right mr-1"></i>Relatórios Financeiros
                        </a>
                    </div>
                </div>

                <!-- Configurações do Sistema -->
                <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-cogs text-gray-600 dark:text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Configurações</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 mb-4 text-sm">
                        Configurações do sistema, permissões de usuários e manutenção.
                    </p>
                    <div class="space-y-2">
                        <a href="{{ route('admin.system.settings.index') }}" class="block text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-300 text-sm">
                            <i class="fas fa-arrow-right mr-1"></i>Configurações
                        </a>
                        <a href="{{ route('admin.permissions.index') }}" class="block text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-300 text-sm">
                            <i class="fas fa-arrow-right mr-1"></i>Permissões
                        </a>
                        <a href="{{ route('admin.system.logs.index') }}" class="block text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-300 text-sm">
                            <i class="fas fa-arrow-right mr-1"></i>Logs do Sistema
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ações Rápidas -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900/20 mb-8 border border-gray-200 dark:border-gray-700">
        <div class="p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6">Ações Rápidas</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.people.members.create') }}" class="block border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-md transition-shadow text-center">
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-user-plus text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">Novo Membro</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Cadastrar nova pessoa</p>
                </a>

                <a href="{{ route('admin.finance.campaigns.create') }}" class="block border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-md transition-shadow text-center">
                    <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-bullhorn text-green-600 dark:text-green-400"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">Nova Campanha</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Criar arrecadação</p>
                </a>

                <a href="{{ route('admin.people.ministries.create') }}" class="block border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-md transition-shadow text-center">
                    <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-users text-purple-600 dark:text-purple-400"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">Novo Ministério</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Criar grupo de trabalho</p>
                </a>

                <a href="{{ route('admin.system.notifications.create') }}" class="block border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-md transition-shadow text-center">
                    <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-bell text-orange-600 dark:text-orange-400"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">Notificação</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Enviar aviso geral</p>
                </a>
            </div>
        </div>
    </div>

    <!-- Informações do Sistema -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900/20 border border-gray-200 dark:border-gray-700">
        <div class="p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6">Sobre o Sistema</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-blue-600 dark:text-blue-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Seguro e Confiável</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">
                        Sistema desenvolvido com as melhores práticas de segurança e backup automático.
                    </p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-mobile-alt text-green-600 dark:text-green-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Responsivo</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">
                        Interface adaptável para computadores, tablets e smartphones.
                    </p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-headset text-purple-600 dark:text-purple-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Suporte Completo</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">
                        Suporte técnico especializado e documentação detalhada.
                    </p>
                </div>
            </div>
            
            <div class="text-center pt-6 border-t border-gray-200 dark:border-gray-600">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                    <strong>CBAV CRM Ministerial</strong> - Desenvolvido por 
                    <a href="https://vertexsolutions.com.br" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">Vertex Solutions</a>
                </p>
                <p class="text-xs text-gray-400 dark:text-gray-500">
                    Versão 2.0 - © 2025 Todos os direitos reservados
                </p>
            </div>
        </div>
    </div>
</div>
@endsection