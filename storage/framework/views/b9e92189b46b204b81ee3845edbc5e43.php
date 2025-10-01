<?php
use App\Models\Configuracao;
?>
<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'CBAV CRM Ministerial')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS -->
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
                        'slide-in': 'slideIn 0.3s ease-out',
                        'slide-out': 'slideOut 0.3s ease-in',
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-in': 'bounceIn 0.6s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideIn: {
                            '0%': { transform: 'translateX(-100%)' },
                            '100%': { transform: 'translateX(0)' },
                        },
                        slideOut: {
                            '0%': { transform: 'translateX(0)' },
                            '100%': { transform: 'translateX(-100%)' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        bounceIn: {
                            '0%': { transform: 'scale(0.3)', opacity: '0' },
                            '50%': { transform: 'scale(1.05)' },
                            '70%': { transform: 'scale(0.9)' },
                            '100%': { transform: 'scale(1)', opacity: '1' },
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Estilos personalizados */
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .text-shadow {
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>

    <?php echo $__env->yieldContent('styles'); ?>
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="<?php echo e(route('home')); ?>" class="flex items-center space-x-3">
                        <?php
                            $logo = Configuracao::get('logo');
                        ?>
                        <?php if($logo): ?>
                            <img src="<?php echo e(Storage::url($logo)); ?>" alt="Logo" class="h-10 w-auto">
                        <?php else: ?>
                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-church text-white text-xl"></i>
                            </div>
                        <?php endif; ?>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">
                                <?php echo e(\App\Models\Configuracao::get('app_name', 'CBAV CRM Ministerial')); ?>

                            </h1>
                            <p class="text-sm text-gray-500"><?php echo e(\App\Models\Configuracao::get('app_description', 'Sistema de Gestão Ministerial')); ?></p>
                        </div>
                    </a>
                </div>

                <!-- Navegação -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="<?php echo e(route('home')); ?>" class="text-gray-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-home mr-2"></i>Início
                    </a>
                    <a href="<?php echo e(route('doacao.index')); ?>" class="text-gray-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-heart mr-2"></i>Doação
                    </a>
                    <a href="<?php echo e(route('creditos')); ?>" class="text-gray-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-info-circle mr-2"></i>Sobre
                    </a>
                </nav>

                <!-- Botões de ação -->
                <div class="flex items-center space-x-4">
                    <?php if(auth()->guard()->check()): ?>
                        <!-- Usuário logado -->
                        <div class="relative" x-data="{ profileOpen: false }">
                            <button @click="profileOpen = !profileOpen" 
                                    class="flex items-center space-x-3 text-gray-700 hover:text-gray-900 transition-colors">
                                <?php
                                    $user = Auth::user();
                                    $membro = $user ? $user->membro : null;
                                    $userPhotoExists = $membro && $membro->foto && Storage::disk('public')->exists($membro->foto);
                                ?>
                                <?php if($userPhotoExists): ?>
                                    <img src="<?php echo e(Storage::url($membro->foto)); ?>?v=<?php echo e(time()); ?>" 
                                         alt="<?php echo e($user->name); ?>" 
                                         class="w-8 h-8 rounded-full object-cover shadow-lg">
                                <?php else: ?>
                                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-medium">
                                            <?php echo e($user ? $user->iniciais : 'U'); ?>

                                        </span>
                                    </div>
                                <?php endif; ?>
                                <span class="hidden md:block text-sm font-medium"><?php echo e($user ? $user->name : 'Usuário'); ?></span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            
                            <!-- Dropdown do perfil -->
                            <div x-show="profileOpen" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 @click.away="profileOpen = false"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                <div class="py-2">
                                    <?php if($user && $user->hasRole('Super Admin')): ?>
                                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-tachometer-alt mr-2"></i>Painel Admin
                                        </a>
                                    <?php elseif($user && $user->hasRole('Membro')): ?>
                                        <a href="<?php echo e(route('member.dashboard')); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-user mr-2"></i>Área do Membro
                                        </a>
                                    <?php endif; ?>
                                    <div class="border-t border-gray-200 my-1"></div>
                                    <a href="<?php echo e(route('logout')); ?>" 
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                       class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Sair
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Usuário não logado -->
                        <a href="<?php echo e(route('login')); ?>" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-sign-in-alt mr-2"></i>Entrar
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Conteúdo da página -->
    <main>
        <?php if(session('success')): ?>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <?php echo e(session('success')); ?>

                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <?php echo e(session('error')); ?>

                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Erro!</strong>
                    </div>
                    <ul class="list-disc list-inside text-sm">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Informações da Igreja -->
                <div>
                    <h3 class="text-lg font-semibold mb-4"><?php echo e(\App\Models\Configuracao::get('app_name', 'CBAV CRM Ministerial')); ?></h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        <?php echo e(\App\Models\Configuracao::get('app_description', 'Sistema de gestão ministerial desenvolvido para facilitar a administração e organização da igreja.')); ?>

                    </p>
                </div>

                <!-- Links Úteis -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Links Úteis</h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="<?php echo e(route('home')); ?>" class="hover:text-white transition-colors">Início</a></li>
                        <li><a href="<?php echo e(route('doacao.index')); ?>" class="hover:text-white transition-colors">Doação</a></li>
                        <li><a href="<?php echo e(route('creditos')); ?>" class="hover:text-white transition-colors">Sobre</a></li>
                    </ul>
                </div>

                <!-- Contato -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contato</h3>
                    <div class="space-y-2 text-sm text-gray-400">
                        <?php if(\App\Models\Configuracao::get('contact_email')): ?>
                        <p><i class="fas fa-envelope mr-2"></i><?php echo e(\App\Models\Configuracao::get('contact_email')); ?></p>
                        <?php endif; ?>
                        <?php if(\App\Models\Configuracao::get('contact_phone')): ?>
                        <p><i class="fas fa-phone mr-2"></i><?php echo e(\App\Models\Configuracao::get('contact_phone')); ?></p>
                        <?php endif; ?>
                        <?php if(\App\Models\Configuracao::get('address')): ?>
                        <p><i class="fas fa-map-marker-alt mr-2"></i><?php echo e(\App\Models\Configuracao::get('address')); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p class="text-sm text-gray-400">
                    © <?php echo e(date('Y')); ?> <?php echo e(\App\Models\Configuracao::get('app_name', 'CBAV CRM Ministerial')); ?>. 
                    Desenvolvido por <a href="#" class="text-blue-400 hover:text-blue-300">Vertex Solutions</a>.
                </p>
            </div>
        </div>
    </footer>

    <!-- Formulário de logout -->
    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="hidden">
        <?php echo csrf_field(); ?>
    </form>

    <!-- Scripts -->
    <script>
        // Funções utilitárias
        function showLoading(button) {
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processando...';
            button.disabled = true;
            return originalText;
        }

        function hideLoading(button, originalText) {
            button.innerHTML = originalText;
            button.disabled = false;
        }

        function showAlert(message, type = 'success') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg ${
                type === 'success' ? 'bg-green-500 text-white' : 
                type === 'error' ? 'bg-red-500 text-white' : 
                'bg-blue-500 text-white'
            }`;
            alertDiv.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} mr-2"></i>
                    ${message}
                </div>
            `;
            document.body.appendChild(alertDiv);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }
    </script>

    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html> <?php /**PATH C:\wamp64\www\CBAV2025\resources\views/layouts/public.blade.php ENDPATH**/ ?>