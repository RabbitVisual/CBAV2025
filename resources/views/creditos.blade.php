<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Créditos - Vertex Solutions</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased">
    <!-- Header com navegação -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                        <i class="fas fa-arrow-left mr-2"></i>Voltar ao Sistema
                    </a>
                </div>
                <div class="text-center">
                    <h1 class="text-xl font-bold text-gray-900">Créditos - Vertex Solutions</h1>
                </div>
                <div class="w-20"></div> <!-- Espaçador para centralizar o título -->
            </div>
        </div>
    </header>

    <main>
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <!-- Header Hero -->
    <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-blue-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <div class="flex justify-center mb-8">
                    <div class="w-24 h-24 bg-white bg-opacity-20 rounded-3xl flex items-center justify-center">
                        <i class="fas fa-church text-white text-4xl"></i>
                    </div>
                </div>
                <h1 class="text-5xl font-bold mb-4">Sistema CBAV</h1>
                <p class="text-xl text-blue-100 mb-4">CRM Ministerial Completo</p>
                <p class="text-lg text-blue-200 mb-6">Desenvolvido com dedicação para gestão ministerial</p>
                <div class="bg-white bg-opacity-10 rounded-2xl p-4 mb-8 backdrop-blur-sm">
                    <p class="text-white text-lg italic">"Tudo posso naquele que me fortalece"</p>
                    <p class="text-blue-200 text-sm">Filipenses 4:13</p>
                </div>
                <div class="w-24 h-1 bg-white mx-auto rounded-full"></div>
            </div>
        </div>
    </div>

    <!-- Sobre o Sistema -->
    <div class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Sobre o Sistema CBAV</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Um sistema completo de gestão ministerial desenvolvido com tecnologia moderna para atender às necessidades específicas de igrejas e organizações religiosas.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Gestão de Membros</h3>
                    <p class="text-gray-600">Cadastro completo, perfis, cargos e ministérios com controle total</p>
                </div>
                
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-calendar-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Sistema de Eventos</h3>
                    <p class="text-gray-600">Criação, inscrições e gestão completa de eventos</p>
                </div>
                
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-violet-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-dollar-sign text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Gestão Financeira</h3>
                    <p class="text-gray-600">Doações, campanhas e controle financeiro integrado</p>
                </div>
                
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-graduation-cap text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">EBD Digital</h3>
                    <p class="text-gray-600">Escola Bíblica Dominical completa com certificados</p>
                </div>
                
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-chart-line text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Relatórios Avançados</h3>
                    <p class="text-gray-600">Análises detalhadas e relatórios personalizados</p>
                </div>
                
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-mobile-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Responsivo</h3>
                    <p class="text-gray-600">Interface adaptável para todos os dispositivos</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CEO Section -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- Foto do CEO -->
                <div class="text-center lg:text-left">
                    <div class="relative inline-block">
                        <img src="{{ asset('img/reinanrodrigues.jpg') }}" 
                             alt="{{ $dados['ceo']['nome'] }}" 
                             class="w-80 h-80 rounded-3xl object-cover shadow-2xl mx-auto lg:mx-0">
                        <div class="absolute -bottom-4 -right-4 w-20 h-20 bg-gradient-to-br from-green-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-crown text-white text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Informações do Desenvolvedor -->
                <div>
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">{{ $dados['ceo']['nome'] }}</h2>
                    <p class="text-2xl text-blue-600 font-semibold mb-6">Desenvolvedor Full Stack</p>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">Desenvolvedor apaixonado por tecnologia com experiência em criação de sistemas web modernos. Especialista em Laravel, React e outras tecnologias que garantem a qualidade e performance do sistema CBAV.</p>
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-6 mb-8 border-l-4 border-blue-500">
                        <p class="text-lg text-gray-700 italic leading-relaxed">"Entre os maiores eu sou o menor dos menores pois o maior em mim é Deus, Jesus Cristo."</p>
                        <p class="text-sm text-gray-500 mt-2">- {{ $dados['ceo']['nome'] }}</p>
                    </div>

                    <!-- Contatos -->
                    <div class="space-y-4 mb-8">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-envelope text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <a href="mailto:r.rodriguesjs@gmail.com" class="text-blue-600 font-semibold hover:text-blue-800">
                                    r.rodriguesjs@gmail.com
                                </a>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                                <i class="fab fa-whatsapp text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">WhatsApp</p>
                                <a href="https://wa.me/5575992034656" 
                                   class="text-green-600 font-semibold hover:text-green-800">
                                    (75) 99203-4656
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Redes Sociais -->
                    <div class="flex space-x-4">
                        <a href="{{ $dados['ceo']['linkedin'] }}" 
                           class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center text-white hover:bg-blue-700 transition-colors">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="{{ $dados['ceo']['github'] }}" 
                           class="w-12 h-12 bg-gray-800 rounded-xl flex items-center justify-center text-white hover:bg-gray-900 transition-colors">
                            <i class="fab fa-github"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tecnologias do Projeto -->
    <div class="py-20 bg-gradient-to-r from-gray-50 to-blue-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Tecnologias Utilizadas</h2>
                <p class="text-xl text-gray-600">Stack tecnológico que torna o sistema robusto e escalável</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl p-6 text-center shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fab fa-laravel text-white text-xl"></i>
                    </div>
                    <p class="font-semibold text-gray-900">Laravel</p>
                </div>
                
                <div class="bg-white rounded-xl p-6 text-center shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fab fa-php text-white text-xl"></i>
                    </div>
                    <p class="font-semibold text-gray-900">PHP 8.2</p>
                </div>
                
                <div class="bg-white rounded-xl p-6 text-center shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fab fa-js text-white text-xl"></i>
                    </div>
                    <p class="font-semibold text-gray-900">JavaScript</p>
                </div>
                
                <div class="bg-white rounded-xl p-6 text-center shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fab fa-css3-alt text-white text-xl"></i>
                    </div>
                    <p class="font-semibold text-gray-900">Tailwind CSS</p>
                </div>
                
                <div class="bg-white rounded-xl p-6 text-center shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-database text-white text-xl"></i>
                    </div>
                    <p class="font-semibold text-gray-900">MySQL</p>
                </div>
                
                <div class="bg-white rounded-xl p-6 text-center shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="w-12 h-12 bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fab fa-github text-white text-xl"></i>
                    </div>
                    <p class="font-semibold text-gray-900">Git</p>
                </div>
                
                <div class="bg-white rounded-xl p-6 text-center shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-white text-xl"></i>
                    </div>
                    <p class="font-semibold text-gray-900">Spatie Permissions</p>
                </div>
                
                <div class="bg-white rounded-xl p-6 text-center shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-credit-card text-white text-xl"></i>
                    </div>
                    <p class="font-semibold text-gray-900">Gateways de Pagamento</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recursos Avançados -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Recursos Avançados</h2>
                <p class="text-xl text-gray-600 mb-6">Funcionalidades que tornam o sistema único</p>
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-6 max-w-2xl mx-auto">
                    <p class="text-gray-700 text-lg italic">"Porque para Deus nada é impossível"</p>
                    <p class="text-gray-500 text-sm">Lucas 1:37</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl p-8 text-center hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-shield-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Sistema de Permissões</h3>
                    <p class="text-gray-600">Controle granular de acesso com Spatie Permissions</p>
                </div>
                
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-8 text-center hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-credit-card text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Múltiplos Gateways</h3>
                    <p class="text-gray-600">Stripe, Mercado Pago e PIX integrados</p>
                </div>
                
                <div class="bg-gradient-to-br from-purple-50 to-violet-50 rounded-2xl p-8 text-center hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-violet-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-certificate text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Certificados Digitais</h3>
                    <p class="text-gray-600">Geração automática de certificados EBD</p>
                </div>
                
                <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-2xl p-8 text-center hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-chart-bar text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Dashboard Inteligente</h3>
                    <p class="text-gray-600">Métricas e análises em tempo real</p>
                </div>
                
                <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-2xl p-8 text-center hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-mobile-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Design Responsivo</h3>
                    <p class="text-gray-600">Interface adaptável para todos os dispositivos</p>
                </div>
                
                <div class="bg-gradient-to-br from-teal-50 to-cyan-50 rounded-2xl p-8 text-center hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-bell text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Sistema de Notificações</h3>
                    <p class="text-gray-600">Alertas e comunicações em tempo real</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sistema CBAV -->
    <div class="py-20 bg-gradient-to-r from-blue-600 via-purple-600 to-blue-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="w-20 h-20 bg-white bg-opacity-20 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-church text-white text-3xl"></i>
                </div>
                <h2 class="text-4xl font-bold mb-4">{{ $dados['sistema']['nome'] }}</h2>
                <p class="text-xl text-blue-100 mb-2">Versão {{ $dados['sistema']['versao'] }}</p>
                <p class="text-lg text-blue-200 mb-6">Sistema completo de gestão ministerial</p>
                <div class="w-24 h-1 bg-white mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 mb-16">
                <!-- Tecnologias -->
                <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl p-8">
                    <h3 class="text-2xl font-bold mb-6 flex items-center">
                        <i class="fas fa-cogs mr-3 text-blue-300"></i>
                        Tecnologias
                    </h3>
                    <div class="space-y-4">
                        @foreach($dados['sistema']['tecnologias'] as $tech)
                        <div class="flex items-center p-3 bg-white bg-opacity-5 rounded-xl">
                            <div class="w-3 h-3 bg-blue-300 rounded-full mr-4"></div>
                            <span class="text-blue-100 font-medium">{{ $tech }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Funcionalidades -->
                <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl p-8">
                    <h3 class="text-2xl font-bold mb-6 flex items-center">
                        <i class="fas fa-star mr-3 text-purple-300"></i>
                        Funcionalidades
                    </h3>
                    <div class="space-y-4">
                        @foreach($dados['sistema']['funcionalidades'] as $func)
                        <div class="flex items-center p-3 bg-white bg-opacity-5 rounded-xl">
                            <div class="w-3 h-3 bg-purple-300 rounded-full mr-4"></div>
                            <span class="text-purple-100 font-medium">{{ $func }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recursos -->
                <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl p-8">
                    <h3 class="text-2xl font-bold mb-6 flex items-center">
                        <i class="fas fa-gift mr-3 text-green-300"></i>
                        Recursos
                    </h3>
                    <div class="space-y-4">
                        @foreach($dados['sistema']['recursos'] as $recurso)
                        <div class="flex items-center p-3 bg-white bg-opacity-5 rounded-xl">
                            <div class="w-3 h-3 bg-green-300 rounded-full mr-4"></div>
                            <span class="text-green-100 font-medium">{{ $recurso }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Módulos Principais -->
            <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-3xl p-8">
                <h3 class="text-3xl font-bold text-center mb-8">Módulos Principais</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="text-center p-6 bg-white bg-opacity-5 rounded-2xl">
                        <div class="w-16 h-16 bg-blue-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-white text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold mb-2">Gestão de Membros</h4>
                        <p class="text-sm text-blue-200">Cadastro completo, perfis, cargos e ministérios</p>
                    </div>
                    
                    <div class="text-center p-6 bg-white bg-opacity-5 rounded-2xl">
                        <div class="w-16 h-16 bg-green-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar-alt text-white text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold mb-2">Sistema de Eventos</h4>
                        <p class="text-sm text-green-200">Criação, inscrições e gestão de eventos</p>
                    </div>
                    
                    <div class="text-center p-6 bg-white bg-opacity-5 rounded-2xl">
                        <div class="w-16 h-16 bg-purple-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-dollar-sign text-white text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold mb-2">Gestão Financeira</h4>
                        <p class="text-sm text-purple-200">Doações, campanhas e controle financeiro</p>
                    </div>
                    
                    <div class="text-center p-6 bg-white bg-opacity-5 rounded-2xl">
                        <div class="w-16 h-16 bg-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-graduation-cap text-white text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold mb-2">EBD Digital</h4>
                        <p class="text-sm text-orange-200">Escola Bíblica Dominical completa</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estatísticas do Projeto -->
    <div class="py-20 bg-gradient-to-r from-gray-50 to-blue-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Estatísticas do Projeto</h2>
                <p class="text-xl text-gray-600">Números que mostram o sucesso do sistema</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-white rounded-2xl p-8 text-center shadow-lg">
                    <div class="w-16 h-16 bg-blue-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ \App\Models\Membro::where('ativo', true)->count() }}+</h3>
                    <p class="text-gray-600">Membros Cadastrados</p>
                </div>

                <div class="bg-white rounded-2xl p-8 text-center shadow-lg">
                    <div class="w-16 h-16 bg-green-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ \App\Models\Evento::where('ativo', true)->count() }}+</h3>
                    <p class="text-gray-600">Eventos Criados</p>
                </div>

                <div class="bg-white rounded-2xl p-8 text-center shadow-lg">
                    <div class="w-16 h-16 bg-purple-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-dollar-sign text-white text-2xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ \App\Models\Transacao::where('status', 'confirmado')->count() }}+</h3>
                    <p class="text-gray-600">Doações Processadas</p>
                </div>

                <div class="bg-white rounded-2xl p-8 text-center shadow-lg">
                    <div class="w-16 h-16 bg-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-graduation-cap text-white text-2xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ \App\Models\EbdAluno::where('status', 'ativo')->count() }}+</h3>
                    <p class="text-gray-600">Alunos EBD</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tecnologias Utilizadas -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Tecnologias Utilizadas</h2>
                <p class="text-xl text-gray-600">Stack tecnológico que torna o sistema robusto e escalável</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-center text-white">
                    <i class="fab fa-laravel text-3xl mb-3"></i>
                    <p class="font-semibold">Laravel</p>
                </div>
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-center text-white">
                    <i class="fab fa-php text-3xl mb-3"></i>
                    <p class="font-semibold">PHP 8.2</p>
                </div>
                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl p-6 text-center text-white">
                    <i class="fab fa-js text-3xl mb-3"></i>
                    <p class="font-semibold">JavaScript</p>
                </div>
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-center text-white">
                    <i class="fab fa-css3-alt text-3xl mb-3"></i>
                    <p class="font-semibold">Tailwind CSS</p>
                </div>
                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-6 text-center text-white">
                    <i class="fas fa-database text-3xl mb-3"></i>
                    <p class="font-semibold">MySQL</p>
                </div>
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl p-6 text-center text-white">
                    <i class="fab fa-github text-3xl mb-3"></i>
                    <p class="font-semibold">Git</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="py-20 bg-gradient-to-r from-blue-600 to-purple-700 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold mb-6">Sistema CBAV - Excelência em Gestão Ministerial</h2>
            <p class="text-xl text-blue-100 mb-8">Desenvolvido com dedicação e tecnologia de ponta para transformar a gestão da sua igreja. Um sistema completo que une funcionalidade, segurança e facilidade de uso.</p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="mailto:r.rodriguesjs@gmail.com" 
                   class="bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold hover:bg-gray-100 transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-envelope mr-2"></i>
                    Contato
                </a>
                <a href="https://wa.me/5575992034656" 
                   class="bg-green-600 text-white px-8 py-4 rounded-xl font-semibold hover:bg-green-700 transition-all duration-300 transform hover:scale-105">
                    <i class="fab fa-whatsapp mr-2"></i>
                    WhatsApp
                </a>
            </div>
        </div>
    </div>

    <!-- Mensagem Espiritual -->
    <div class="py-16 bg-gradient-to-r from-blue-50 to-purple-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="bg-white rounded-3xl p-12 shadow-2xl">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-8">
                    <i class="fas fa-heart text-white text-3xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-6">"Tudo posso naquele que me fortalece"</h3>
                <p class="text-xl text-gray-600 mb-6">Filipenses 4:13</p>
                <p class="text-lg text-gray-700 leading-relaxed">Este sistema foi desenvolvido com a missão de servir e fortalecer a comunidade cristã. Que cada funcionalidade seja um instrumento para glorificar a Deus e edificar vidas através da tecnologia.</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-church text-white text-3xl"></i>
                </div>
            </div>
            <p class="text-gray-400 mb-4">&copy; {{ date('Y') }} Sistema CBAV. Todos os direitos reservados.</p>
            <p class="text-gray-500">Desenvolvido com dedicação por {{ $dados['ceo']['nome'] }}</p>
        </div>
    </div>
</div>
    </main>

    <style>
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }

    .animate-float {
        animation: float 3s ease-in-out infinite;
    }

    @keyframes fadeInOut {
        0%, 100% { opacity: 0; transform: translateY(20px); }
        15%, 85% { opacity: 1; transform: translateY(0); }
    }

    .floating-message {
        position: fixed;
        z-index: 1000;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.2rem 1.8rem;
        border-radius: 1.2rem;
        box-shadow: 0 15px 40px rgba(0,0,0,0.4);
        font-size: 1rem;
        max-width: 350px;
        text-align: center;
        animation: fadeInOut 12s ease-in-out;
        pointer-events: none;
        line-height: 1.4;
    }

    .floating-message .verse {
        font-weight: bold;
        margin-bottom: 0.8rem;
        font-size: 1.1rem;
        line-height: 1.5;
    }

    .floating-message .reference {
        font-size: 0.9rem;
        opacity: 0.95;
        font-style: italic;
    }
    </style>

    <script>
    // Adicionar efeitos de animação
    document.addEventListener('DOMContentLoaded', function() {
        // Animar elementos ao scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observar todos os cards
        document.querySelectorAll('.grid > div').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });

        // Mensagens bíblicas flutuantes
        const verses = [
            // Versículos sobre Jesus como o caminho
            {
                verse: "Eu sou o caminho, a verdade e a vida",
                reference: "João 14:6"
            },
            {
                verse: "Ninguém vem ao Pai senão por mim",
                reference: "João 14:6"
            },
            {
                verse: "Porque Deus amou o mundo de tal maneira que deu seu Filho unigênito",
                reference: "João 3:16"
            },
            {
                verse: "Deu seu Filho unigênito para que todo aquele que nele crê tenha vida eterna",
                reference: "João 3:16"
            },
            {
                verse: "Jesus Cristo é o mesmo ontem, hoje e eternamente",
                reference: "Hebreus 13:8"
            },
            
            // Versículos sobre arrependimento
            {
                verse: "Bem-aventurados os que têm fome e sede de justiça",
                reference: "Mateus 5:6"
            },
            {
                verse: "Porque eles serão fartos",
                reference: "Mateus 5:6"
            },
            {
                verse: "Há alegria no céu por um pecador que se arrepende",
                reference: "Lucas 15:7"
            },
            {
                verse: "Se confessarmos os nossos pecados, ele é fiel",
                reference: "1 João 1:9"
            },
            {
                verse: "E justo para nos perdoar os pecados",
                reference: "1 João 1:9"
            },
            {
                verse: "O Senhor não retarda a sua promessa",
                reference: "2 Pedro 3:9"
            },
            {
                verse: "Mas é longânimo para convosco, não querendo que pereça",
                reference: "2 Pedro 3:9"
            },
            {
                verse: "Bem-aventurados os misericordiosos, porque eles alcançarão misericórdia",
                reference: "Mateus 5:7"
            },
            
            // Versículos sobre a bondade de Deus
            {
                verse: "O Senhor é bom para todos e as suas misericórdias são sobre todas as suas obras",
                reference: "Salmos 145:9"
            },
            {
                verse: "Bom é o Senhor para todos e as suas misericórdias sobre todas as suas obras",
                reference: "Salmos 145:9"
            },
            {
                verse: "O Senhor é misericordioso e piedoso",
                reference: "Salmos 103:8"
            },
            {
                verse: "Lento para a ira e grande em benignidade",
                reference: "Salmos 103:8"
            },
            {
                verse: "Porque o Senhor é bom",
                reference: "Salmos 100:5"
            },
            {
                verse: "A sua misericórdia dura para sempre",
                reference: "Salmos 100:5"
            },
            {
                verse: "E a sua verdade de geração em geração",
                reference: "Salmos 100:5"
            },
            {
                verse: "Deus é amor",
                reference: "1 João 4:8"
            },
            {
                verse: "Aquele que não ama não conhece a Deus",
                reference: "1 João 4:8"
            },
            {
                verse: "Porque Deus amou o mundo",
                reference: "João 3:16"
            },
            {
                verse: "De tal maneira que deu o seu Filho unigênito",
                reference: "João 3:16"
            },
            
            // Versículos de encorajamento
            {
                verse: "Tudo posso naquele que me fortalece",
                reference: "Filipenses 4:13"
            },
            {
                verse: "Porque para Deus nada é impossível",
                reference: "Lucas 1:37"
            },
            {
                verse: "Confia no Senhor de todo o teu coração e não te estribes no teu próprio entendimento",
                reference: "Provérbios 3:5"
            },
            {
                verse: "O Senhor é meu pastor, nada me faltará",
                reference: "Salmos 23:1"
            },
            {
                verse: "Buscai primeiro o reino de Deus e a sua justiça, e todas estas coisas vos serão acrescentadas",
                reference: "Mateus 6:33"
            },
            {
                verse: "Amarás o Senhor teu Deus de todo o teu coração, de toda a tua alma e de todo o teu pensamento",
                reference: "Mateus 22:37"
            },
            {
                verse: "Vinde a mim todos os que estais cansados e oprimidos, e eu vos aliviarei",
                reference: "Mateus 11:28"
            },
            {
                verse: "A fé vem pelo ouvir, e o ouvir pela palavra de Deus",
                reference: "Romanos 10:17"
            },
            {
                verse: "Graça e paz da parte de Deus e da parte do Senhor Jesus Cristo",
                reference: "Efésios 1:2"
            },
            {
                verse: "Porque Deus não nos deu o espírito de temor, mas de fortaleza, e de amor, e de moderação",
                reference: "2 Timóteo 1:7"
            },
            {
                verse: "O Senhor é a minha luz e a minha salvação, a quem temerei?",
                reference: "Salmos 27:1"
            },
            {
                verse: "O Senhor é a força da minha vida, de quem me recearei?",
                reference: "Salmos 27:1"
            },
            {
                verse: "Bem-aventurados os pacificadores, porque eles serão chamados filhos de Deus",
                reference: "Mateus 5:9"
            },
            {
                verse: "Alegrai-vos sempre no Senhor, outra vez vos digo: alegrai-vos",
                reference: "Filipenses 4:4"
            },
            {
                verse: "A paz de Deus, que excede todo o entendimento, guardará os vossos corações e os vossos pensamentos",
                reference: "Filipenses 4:7"
            },
            {
                verse: "O Senhor é o meu pastor, nada me faltará",
                reference: "Salmos 23:1"
            },
            {
                verse: "Bendize, ó minha alma, ao Senhor, e tudo o que há em mim bendiga o seu santo nome",
                reference: "Salmos 103:1"
            }
        ];

        let currentVerseIndex = 0;

        function showFloatingMessage() {
            if (currentVerseIndex >= verses.length) {
                currentVerseIndex = 0;
            }

            const verse = verses[currentVerseIndex];
            const message = document.createElement('div');
            message.className = 'floating-message';
            message.innerHTML = `
                <div class="verse">"${verse.verse}"</div>
                <div class="reference">${verse.reference}</div>
            `;

            // Posição aleatória
            const x = Math.random() * (window.innerWidth - 300);
            const y = Math.random() * (window.innerHeight - 100) + 50;
            
            message.style.left = x + 'px';
            message.style.top = y + 'px';

            document.body.appendChild(message);

            // Remover após a animação
            setTimeout(() => {
                if (message.parentNode) {
                    message.parentNode.removeChild(message);
                }
            }, 12000);

            currentVerseIndex++;
        }

        // Mostrar primeira mensagem após 5 segundos
        setTimeout(showFloatingMessage, 5000);

        // Mostrar mensagens a cada 25 segundos
        setInterval(showFloatingMessage, 25000);

        // Mostrar mensagem quando o usuário rolar
        let lastScrollTime = 0;
        let userActivity = 0;
        
        window.addEventListener('scroll', () => {
            userActivity++;
            const now = Date.now();
            if (now - lastScrollTime > 15000) { // Máximo uma mensagem a cada 15 segundos
                showFloatingMessage();
                lastScrollTime = now;
            }
        });

        // Mostrar mensagem quando o usuário parar de ler (não rolar por 30 segundos)
        let readingTimeout;
        window.addEventListener('scroll', () => {
            clearTimeout(readingTimeout);
            readingTimeout = setTimeout(() => {
                if (userActivity > 2) { // Só se o usuário estiver ativo
                    showFloatingMessage();
                }
            }, 30000);
        });

        // Mostrar mensagem quando o usuário clicar (interação)
        document.addEventListener('click', () => {
            const now = Date.now();
            if (now - lastScrollTime > 20000) {
                showFloatingMessage();
                lastScrollTime = now;
            }
        });
    });
    </script>
</body>
</html> 