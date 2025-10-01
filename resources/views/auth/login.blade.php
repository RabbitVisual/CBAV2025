<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ \App\Models\Configuracao::get('app_name', 'CBAV CRM') }} - Login</title>

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
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-blue-50 via-white to-purple-50">
    @php
        $logo = \App\Models\Configuracao::get('logo');
        $appName = \App\Models\Configuracao::get('app_name', 'CBAV CRM');
        $appDescription = \App\Models\Configuracao::get('app_description', 'Sistema de Gestão Ministerial');
    @endphp
    
    <div class="min-h-screen flex">
        <!-- Sidebar Decorativa -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700 relative overflow-hidden">
            <div class="absolute inset-0 bg-black bg-opacity-20"></div>
            <div class="relative z-10 flex flex-col justify-center items-center text-white p-12">
                <div class="text-center max-w-md">
                    <div class="w-24 h-24 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center mb-8 backdrop-blur-sm">
                        @if($logo)
                            <img src="{{ Storage::url($logo) }}" alt="Logo {{ $appName }}" 
                                 class="w-16 h-16 object-contain" 
                                 onerror="this.onerror=null;this.parentElement.innerHTML='<i class=\'fas fa-church text-4xl text-white\'></i>'">
                        @else
                            <i class="fas fa-church text-4xl text-white"></i>
                        @endif
                    </div>
                    <h1 class="text-4xl font-bold mb-4">{{ $appName }}</h1>
                    <p class="text-xl mb-6 text-blue-100">{{ $appDescription }}</p>
                    <div class="space-y-4 text-blue-100">
                        <div class="flex items-center">
                            <i class="fas fa-users mr-3 text-blue-200"></i>
                            <span>Gestão completa de membros</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-hands-helping mr-3 text-blue-200"></i>
                            <span>Controle de ministérios</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-chart-line mr-3 text-blue-200"></i>
                            <span>Relatórios financeiros</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-birthday-cake mr-3 text-blue-200"></i>
                            <span>Aniversariantes do mês</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Elementos decorativos -->
            <div class="absolute top-20 left-20 w-32 h-32 bg-white bg-opacity-10 rounded-full"></div>
            <div class="absolute bottom-20 right-20 w-24 h-24 bg-white bg-opacity-10 rounded-full"></div>
            <div class="absolute top-1/2 left-10 w-16 h-16 bg-white bg-opacity-10 rounded-full"></div>
        </div>

        <!-- Formulário de Login -->
        <div class="flex-1 flex items-center justify-center p-8">
            <div class="w-full max-w-md space-y-8 animate-fade-in">
                <div class="text-center animate-slide-up">
                    <div class="mx-auto w-20 h-20 bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg mb-6">
                        @if($logo)
                            <img src="{{ Storage::url($logo) }}" alt="Logo {{ $appName }}" 
                                 class="w-12 h-12 object-contain" 
                                 onerror="this.onerror=null;this.parentElement.innerHTML='<i class=\'fas fa-church text-2xl text-white\'></i>'">
                        @else
                            <i class="fas fa-church text-2xl text-white"></i>
                        @endif
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">
                        Bem-vindo de volta
                    </h2>
                    <p class="text-gray-600">
                        Acesse o {{ $appDescription }}
                    </p>
                </div>

                <div class="bg-white rounded-2xl shadow-xl p-8 animate-slide-up">
                    <form class="space-y-6" action="{{ route('login') }}" method="POST">
                        @csrf
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-2 text-blue-500"></i>Email
                            </label>
                            <input id="email" name="email" type="email" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                   placeholder="seu@email.com" value="{{ old('email') }}">
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2 text-blue-500"></i>Senha
                            </label>
                            <div class="relative">
                                <input id="password" name="password" type="password" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 pr-12"
                                       placeholder="Sua senha">
                                <button type="button" onclick="togglePassword()" 
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i id="passwordIcon" class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg" role="alert">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <div>
                                        <p class="font-medium">Erro no login</p>
                                        <ul class="list-disc list-inside text-sm mt-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember" name="remember" type="checkbox" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="remember" class="ml-2 block text-sm text-gray-700">
                                    Lembrar de mim
                                </label>
                            </div>
                            <div class="text-sm">
                                <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                                    Esqueceu a senha?
                                </a>
                            </div>
                        </div>

                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-4 rounded-lg hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 font-semibold text-lg shadow-lg">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Entrar no Sistema
                        </button>
                    </form>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="text-center">
                            <p class="text-sm text-gray-600">
                                Precisa de ajuda? Entre em contato com o administrador
                            </p>
                            <div class="mt-4 flex justify-center space-x-4">
                                <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-500 text-sm">
                                    <i class="fas fa-home mr-1"></i>Página Inicial
                                </a>
                                <a href="{{ route('termos-uso') }}" class="text-blue-600 hover:text-blue-500 text-sm">
                                    <i class="fas fa-file-contract mr-1"></i>Termos de Uso
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-center text-sm text-gray-500 animate-slide-up">
                    <p>&copy; {{ date('Y') }} {{ $appName }}. Todos os direitos reservados.</p>
                    <p class="mt-1">Desenvolvido com <i class="fas fa-heart text-red-500"></i> para a igreja</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('passwordIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }

        // Adicionar efeito de foco nos inputs
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-blue-500');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-blue-500');
            });
        });
    </script>
</body>
</html> 