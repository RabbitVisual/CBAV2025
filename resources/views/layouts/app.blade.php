@php
use App\Models\Configuracao;
use App\Helpers\PermissionHelper;
use App\Helpers\SystemConfigHelper;

// Garantir que as variáveis de notificações sempre existam
if (!isset($notificacoes)) {
    $notificacoes = collect();
}
if (!isset($notificacoesNaoLidas)) {
    $notificacoesNaoLidas = 0;
}

// Determinar o tipo de área (admin ou membro)
$isAdminArea = request()->is('admin*') || request()->is('dashboard');
$isMembroArea = request()->is('membro*');
$areaType = $isAdminArea ? 'admin' : 'membro';

// Obter configurações do sistema
$appName = SystemConfigHelper::get('app_name', config('app.name'));
$appDescription = SystemConfigHelper::get('app_description', '');
$faviconUrl = SystemConfigHelper::get('app_favicon') ? asset('storage/' . SystemConfigHelper::get('app_favicon')) : asset('favicon.ico');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches) }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="application-name" content="{{ $appName }}">
    <meta name="description" content="{{ $appDescription }}">

    <title>{{ $appName }} - {{ $areaType === 'admin' ? 'Admin' : 'Área do Membro' }}</title>
    
    <!-- Favicon dinâmico -->
    <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ $faviconUrl }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
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
                            950: '#172554',
                        },
                        gray: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#020617',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-in': 'slideIn 0.3s ease-out',
                        'slide-out': 'slideOut 0.3s ease-in',
                        'scale-in': 'scaleIn 0.2s ease-out',
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
                        scaleIn: {
                            '0%': { transform: 'scale(0.95)', opacity: '0' },
                            '100%': { transform: 'scale(1)', opacity: '1' },
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
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        [x-cloak] { display: none !important; }
        
        /* Scrollbar personalizada */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.5);
            border-radius: 3px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(156, 163, 175, 0.7);
        }
        
        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(75, 85, 99, 0.5);
        }
        
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(75, 85, 99, 0.7);
        }
        
        /* Transições suaves */
        * {
            transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        }
        
        /* Efeitos de glassmorphism */
        .glass {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        /* Animações para sidebar */
        .sidebar-item {
            position: relative;
            overflow: hidden;
        }
        
        .sidebar-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }
        
        .sidebar-item:hover::before {
            left: 100%;
        }
        
        /* Indicador de item ativo */
        .sidebar-item.active {
            background: rgba(59, 130, 246, 0.1);
            border-left: 3px solid #3b82f6;
        }
        
        .dark .sidebar-item.active {
            background: rgba(59, 130, 246, 0.2);
        }
        
        /* Tooltips para modo colapsado */
        .tooltip {
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            margin-left: 12px;
            padding: 8px 12px;
            background: rgba(0, 0, 0, 0.9);
            color: white;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
            pointer-events: none;
        }
        
        .tooltip::before {
            content: '';
            position: absolute;
            left: -4px;
            top: 50%;
            transform: translateY(-50%);
            border: 4px solid transparent;
            border-right-color: rgba(0, 0, 0, 0.9);
        }
        
        .sidebar-item:hover .tooltip {
            opacity: 1;
            visibility: visible;
        }
        
        /* Estilos específicos para sidebar admin */
        .sidebar-section {
            margin-bottom: 0.5rem;
        }
        
        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #6b7280;
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }
        
        .sidebar-item:hover {
            background-color: #f3f4f6;
            color: #374151;
        }
        
        .dark .sidebar-item:hover {
            background-color: #374151;
            color: #d1d5db;
        }
        
        .sidebar-item.collapsed {
            justify-content: center;
            padding: 0.75rem;
        }
        
        .sidebar-icon {
            width: 1.25rem;
            height: 1.25rem;
            margin-right: 0.75rem;
            flex-shrink: 0;
        }
        
        .sidebar-text {
            flex: 1;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .sidebar-indicator {
            width: 0.5rem;
            height: 0.5rem;
            background-color: #3b82f6;
            border-radius: 50%;
            margin-left: auto;
        }
        
        .sidebar-items {
            padding-left: 1rem;
        }
        
        .sidebar-subitem {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            color: #9ca3af;
            text-decoration: none;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
            font-size: 0.8125rem;
            position: relative;
        }
        
        .sidebar-subitem:hover {
            background-color: #f9fafb;
            color: #6b7280;
        }
        
        .dark .sidebar-subitem:hover {
            background-color: #4b5563;
            color: #d1d5db;
        }
        
        .sidebar-subitem.active {
            background-color: #eff6ff;
            color: #2563eb;
        }
        
        .dark .sidebar-subitem.active {
            background-color: #1e40af;
            color: #dbeafe;
        }
        
        .sidebar-subitem-icon {
            width: 1rem;
            height: 1rem;
            margin-right: 0.5rem;
            flex-shrink: 0;
        }
        
        .sidebar-subitem-text {
            flex: 1;
        }
        
        .sidebar-subitem-indicator {
            width: 0.375rem;
            height: 0.375rem;
            background-color: #3b82f6;
            border-radius: 50%;
            margin-left: auto;
        }
        
        .sidebar-tooltip {
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            margin-left: 0.75rem;
            padding: 0.5rem 0.75rem;
            background-color: #1f2937;
            color: white;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
            pointer-events: none;
        }
        
        .sidebar-tooltip::before {
            content: '';
            position: absolute;
            left: -0.25rem;
            top: 50%;
            transform: translateY(-50%);
            border: 0.25rem solid transparent;
            border-right-color: #1f2937;
        }
        
        /* Responsividade aprimorada */
        @media (max-width: 1024px) {
            .sidebar-mobile {
                transform: translateX(-100%);
            }
            
            .sidebar-mobile.open {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 font-sans antialiased transition-colors duration-200" 
      x-data="{ 
          sidebarOpen: window.innerWidth >= 1024, 
          sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
          isMobile: window.innerWidth < 1024,
          expandedSections: {
              system: false,
              people: false,
              school: false,
              intercessor: false,
              events: false,
              financial: false,
              council: false
          }
      }" 
      x-init="
          $watch('sidebarCollapsed', val => {
              localStorage.setItem('sidebarCollapsed', val);
              if (val) {
                  // Colapsar todas as seções quando o sidebar for colapsado
                  Object.keys(expandedSections).forEach(key => {
                      expandedSections[key] = false;
                  });
              }
          });
          window.addEventListener('resize', () => {
              const wasMobile = isMobile;
              isMobile = window.innerWidth < 1024;
              
              // Ajustar comportamento baseado na mudança de tamanho
              if (!isMobile && wasMobile) {
                  sidebarOpen = true;
              } else if (isMobile && !wasMobile) {
                  sidebarOpen = false;
              }
          });
      ">
    
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 transition-all duration-300 ease-in-out transform"
         :class="{
             'w-64': sidebarOpen && !sidebarCollapsed,
             'w-16': sidebarOpen && sidebarCollapsed,
             '-translate-x-full lg:translate-x-0': !sidebarOpen,
             'shadow-2xl': sidebarOpen && isMobile
         }"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full">
        
        <!-- Sidebar Content -->
        <div class="flex h-full flex-col bg-white dark:bg-gray-800 shadow-xl border-r border-gray-200 dark:border-gray-700">
            
            <!-- Logo e Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-3" x-show="!sidebarCollapsed" x-transition>
                    @php
                        $logoPath = Configuracao::get('logo');
                        $logoExists = $logoPath && Storage::disk('public')->exists($logoPath);
                    @endphp
                    @if($logoExists)
                        <img src="{{ Storage::url($logoPath) }}" 
                             alt="Logo" 
                             class="w-8 h-8 object-contain rounded-lg">
                    @else
                        <div class="w-8 h-8 bg-primary-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-church text-white text-sm"></i>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-gray-900 dark:text-white font-semibold text-sm">{{ \App\Models\Configuracao::get('app_name', 'CBAV') }}</h1>
                        <p class="text-gray-500 dark:text-gray-400 text-xs">{{ $areaType === 'admin' ? 'Painel Admin' : 'Área do Membro' }}</p>
                    </div>
                </div>
                
                <!-- Logo colapsado -->
                <div x-show="sidebarCollapsed" x-transition class="mx-auto">
                    @if($logoExists)
                        <img src="{{ Storage::url($logoPath) }}" 
                             alt="Logo" 
                             class="w-8 h-8 object-contain rounded-lg">
                    @else
                        <div class="w-8 h-8 bg-primary-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-church text-white text-sm"></i>
                        </div>
                    @endif
                </div>
                
                <!-- Botão fechar mobile -->
                <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Navegação -->
            <nav class="flex-1 overflow-y-auto custom-scrollbar p-4">
                @if($areaType === 'admin')
                    @include('layouts.partials.sidebar-admin')
                @else
                    @include('layouts.partials.sidebar-membro')
                @endif
            </nav>
            
            <!-- Footer do Sidebar - Informações do Sistema -->
            <div class="border-t border-gray-200 dark:border-gray-700 p-4">
                <!-- Informações expandidas -->
                <div x-show="!sidebarCollapsed" x-transition class="space-y-3">
                    <div class="p-3 bg-gradient-to-r from-primary-50 to-blue-50 dark:from-primary-900/20 dark:to-blue-900/20 rounded-lg border border-primary-100 dark:border-primary-800">
                        <div class="flex items-center space-x-2 mb-2">
                            <i class="fas fa-code text-primary-600 dark:text-primary-400"></i>
                            <span class="text-xs font-semibold text-primary-700 dark:text-primary-300">Sistema CBAV</span>
                        </div>
                        <div class="space-y-1">
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-600 dark:text-gray-400">Versão:</span>
                                <span class="text-xs font-medium text-gray-900 dark:text-white">{{ env('APP_VERSION', '2.0.0') }}</span>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-2 pt-2 border-t border-primary-200 dark:border-primary-700">
                                <div class="flex items-center space-x-1">
                                    <i class="fas fa-heart text-red-500 text-xs"></i>
                                    <span>Desenvolvido por</span>
                                </div>
                                <div class="font-medium text-primary-600 dark:text-primary-400 mt-1">
                                    Reinan Rodrigues
                                </div>
                                <div class="text-xs text-gray-400 dark:text-gray-500">
                                    Desenvolvedor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Informações colapsadas -->
                <div x-show="sidebarCollapsed" x-transition class="flex justify-center">
                    <div class="relative group p-2 rounded-lg hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-all duration-200">
                        <div class="w-8 h-8 bg-gradient-to-r from-primary-500 to-blue-600 rounded-lg flex items-center justify-center shadow-lg">
                            <i class="fas fa-code text-white text-sm"></i>
                        </div>
                        <div class="tooltip">
                            <div class="text-center">
                                <div class="font-semibold">CBAV v{{ env('APP_VERSION', '2.0.0') }}</div>
                                <div class="text-xs mt-1">Por Reinan Rodrigues</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Overlay Mobile -->
    <div x-show="sidebarOpen && isMobile" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden"
         @click="sidebarOpen = false"></div>
    
    <!-- Conteúdo Principal -->
    <div class="transition-all duration-300"
         :class="{
             'lg:ml-64': !sidebarCollapsed,
             'lg:ml-16': sidebarCollapsed
         }">
        
        <!-- Header -->
        @if($areaType === 'admin')
            @include('layouts.partials.header-admin')
        @else
            @include('layouts.partials.header-member')
        @endif
        
        <!-- Conteúdo da Página -->
        <main class="min-h-screen bg-gray-50 dark:bg-gray-900">
            @yield('content')
        </main>
    </div>
    
    <!-- Scripts -->
    <script>
        // Detectar seção ativa
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const sidebarItems = document.querySelectorAll('.sidebar-item');
            
            sidebarItems.forEach(item => {
                const href = item.getAttribute('href');
                if (href && currentPath.includes(href.replace(window.location.origin, ''))) {
                    item.classList.add('active');
                }
            });
        });
        
        // Fechar sidebar em mobile ao clicar em item
        document.addEventListener('click', function(e) {
            if (e.target.closest('.sidebar-item') && window.innerWidth < 1024) {
                document.querySelector('[x-data]').__x.$data.sidebarOpen = false;
            }
        });
        
        // Funções para notificações
        function marcarComoLida(id) {
            fetch(`/notificacoes/${id}/marcar-lida`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Erro:', error));
        }
        
        function marcarTodasComoLidas() {
            fetch('/notificacoes/marcar-todas-lidas', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Erro:', error));
        }
    </script>
</body>
</html>