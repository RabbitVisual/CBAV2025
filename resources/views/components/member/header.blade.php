<!-- Header -->
<header class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40">
    <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
        <!-- Left side -->
        <div class="flex items-center space-x-4">
            <!-- Mobile menu button -->
            <button @click="sidebarOpen = !sidebarOpen" 
                    class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500 transition-colors duration-200">
                <span class="sr-only">Abrir sidebar</span>
                <i class="fas fa-bars h-5 w-5"></i>
            </button>
            
            <!-- Breadcrumb -->
            <nav class="hidden sm:flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li>
                        <div class="flex items-center">
                            <a href="{{ route('member.dashboard') }}" class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 transition-colors duration-200">
                                <i class="fas fa-home h-4 w-4"></i>
                                <span class="sr-only">Dashboard</span>
                            </a>
                        </div>
                    </li>
                    @if(request()->route()->getName() !== 'member.dashboard')
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right h-3 w-3 text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-900 dark:text-white capitalize">
                                @php
                                    $routeName = request()->route()->getName();
                                    $segments = explode('.', $routeName);
                                    $lastSegment = end($segments);
                                    
                                    $breadcrumbNames = [
                                        'profile' => 'Meu Perfil',
                                        'devotionals' => 'Devocionais',
                                        'bible' => 'Bíblia Online',
                                        'prayer' => 'Pedidos de Oração',
                                        'events' => 'Eventos',
                                        'ministries' => 'Ministérios',
                                        'ebd' => 'EBD',
                                        'donations' => 'Contribuições',
                                        'chat' => 'Comunicação',
                                        'notifications' => 'Notificações'
                                    ];
                                    
                                    echo $breadcrumbNames[$segments[1]] ?? ucfirst($lastSegment);
                                @endphp
                            </span>
                        </div>
                    </li>
                    @endif
                </ol>
            </nav>
        </div>
        
        <!-- Right side -->
        <div class="flex items-center space-x-4">
            <!-- Theme Toggle -->
            <button @click="toggleTheme()" 
                    class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500 transition-colors duration-200"
                    title="Alternar tema">
                <i class="fas fa-sun h-5 w-5 dark:hidden"></i>
                <i class="fas fa-moon h-5 w-5 hidden dark:block"></i>
            </button>
            
            <!-- Notifications -->
            <div class="relative" x-data="{ notificationsOpen: false }">
                <button @click="notificationsOpen = !notificationsOpen" 
                        class="relative p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500 transition-colors duration-200">
                    <span class="sr-only">Ver notificações</span>
                    <i class="fas fa-bell h-5 w-5"></i>
                    @if($unreadNotificationsCount > 0)
                    <span class="absolute -top-0.5 -right-0.5 h-4 w-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                        {{ $unreadNotificationsCount > 9 ? '9+' : $unreadNotificationsCount }}
                    </span>
                    @endif
                </button>
                
                <!-- Notifications dropdown -->
                <div x-show="notificationsOpen" 
                     @click.away="notificationsOpen = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                    
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">Notificações</h3>
                            @if($unreadNotificationsCount > 0)
                            <a href="{{ route('member.notifications.mark-all-read') }}" 
                               class="text-xs text-primary-600 dark:text-primary-400 hover:text-primary-500 dark:hover:text-primary-300">
                                Marcar todas como lidas
                            </a>
                            @endif
                        </div>
                    </div>
                    
                    <div class="max-h-64 overflow-y-auto">
                        @forelse($latestNotifications as $notification)
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700 last:border-b-0 {{ $notification->read_at ? '' : 'bg-primary-50 dark:bg-primary-900/20' }}">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                                        <i class="fas fa-bell h-4 w-4 text-primary-600 dark:text-primary-400"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900 dark:text-white">
                                        {{ $notification->data['title'] ?? 'Nova notificação' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                @if(!$notification->read_at)
                                <div class="flex-shrink-0">
                                    <div class="h-2 w-2 bg-primary-500 rounded-full"></div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="p-4 text-center">
                            <i class="fas fa-bell-slash h-8 w-8 text-gray-400 mx-auto mb-2"></i>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Nenhuma notificação</p>
                        </div>
                        @endforelse
                    </div>
                    
                    @if($latestNotifications->count() > 5)
                    <div class="p-3 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('member.notifications.index') }}" 
                           class="block text-center text-sm text-primary-600 dark:text-primary-400 hover:text-primary-500 dark:hover:text-primary-300">
                            Ver todas as notificações
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- User menu -->
            <div class="relative" x-data="{ profileOpen: false }">
                <button @click="profileOpen = !profileOpen" 
                        class="flex items-center space-x-3 p-2 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500 transition-colors duration-200 hover:bg-gray-100 dark:hover:bg-gray-800">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center relative">
                            @if($membro && $membro->foto_existe)
                                <img src="{{ $membro->foto_url }}" alt="Avatar" class="h-8 w-8 rounded-full object-cover">
                            @else
                                <span class="text-xs font-medium text-primary-600 dark:text-primary-400">
                                    {{ $membro ? $membro->iniciais : Auth::user()->iniciais }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ Auth::user()->name }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Membro
                        </p>
                    </div>
                    <i class="fas fa-chevron-down h-3 w-3 text-gray-400"></i>
                </button>
                
                <!-- Profile dropdown -->
                <div x-show="profileOpen" 
                     @click.away="profileOpen = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                    
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                                    @if($membro && $membro->foto_existe)
                                        <img src="{{ $membro->foto_url }}" alt="Avatar" class="h-10 w-10 rounded-full object-cover">
                                    @else
                                        <span class="text-sm font-medium text-primary-600 dark:text-primary-400">
                                            {{ $membro ? $membro->iniciais : Auth::user()->iniciais }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                    {{ Auth::user()->name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                    {{ Auth::user()->email }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="py-2">
                        <a href="{{ route('member.profile.index') }}" 
                           class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                            <i class="fas fa-user mr-3 h-4 w-4 text-gray-400"></i>
                            Meu Perfil
                        </a>
                        
                        <a href="{{ route('member.profile.edit') }}" 
                           class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                            <i class="fas fa-cog mr-3 h-4 w-4 text-gray-400"></i>
                            Configurações
                        </a>
                        
                        @if(auth()->user()->hasAnyPermission(['admin master', 'people.access', 'finance.access', 'system.access', 'devotionals.access', 'council.access', 'ebd.access', 'intercessor.access']))
                        <div class="border-t border-gray-200 dark:border-gray-700 my-2"></div>
                        
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                            <i class="fas fa-cogs mr-3 h-4 w-4 text-gray-400"></i>
                            Painel Admin
                        </a>
                        @endif
                        
                        <div class="border-t border-gray-200 dark:border-gray-700 my-2"></div>
                        
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    onclick="return confirm('Tem certeza que deseja sair?')"
                                    class="w-full flex items-center px-4 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-200">
                                <i class="fas fa-sign-out-alt mr-3 h-4 w-4 text-red-500"></i>
                                Sair
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>