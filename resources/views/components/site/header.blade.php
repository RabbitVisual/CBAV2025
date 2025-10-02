@props(['configuracoes'])

<header class="bg-gradient-to-r from-blue-800 to-blue-600 sticky top-0 z-50 shadow-lg transition-all duration-300"
        style="--color-primary: {{ $configuracoes['cor_primaria'] ?? '#1E40AF' }}; --color-secondary: {{ $configuracoes['cor_secundaria'] ?? '#3B82F6' }}; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <div class="flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center space-x-3">
                @if(!empty($configuracoes['logo']))
                    <img src="{{ Storage::url($configuracoes['logo']) }}" alt="Logo" class="h-12 w-12 object-contain bg-white/10 p-1 rounded-xl shadow-md">
                @else
                    <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center">
                        <i class="fas fa-church text-white text-2xl"></i>
                    </div>
                @endif
                <div>
                    <h1 class="text-xl font-bold text-white">{{ $configuracoes['igreja_nome'] ?? 'Congregação Batista' }}</h1>
                    @if(!empty($configuracoes['igreja_slogan']))
                        <p class="text-xs text-white/80 hidden sm:block">{{ $configuracoes['igreja_slogan'] }}</p>
                    @endif
                </div>
            </a>

            <!-- Menu Desktop -->
            <div class="hidden lg:flex items-center space-x-2">
                <a href="#sobre" class="nav-link">Sobre</a>
                <a href="#ministerios" class="nav-link">Ministérios</a>
                <a href="#cultos" class="nav-link">Cultos</a>
                <a href="{{ route('public.events.index') }}" class="nav-link">Eventos</a>
                <a href="{{ route('donation.index') }}" class="nav-link bg-white/20 font-semibold">Contribuir</a>

                @auth
                    <a href="{{ auth()->user()->hasRole('Admin') ? route('admin.dashboard') : route('member.dashboard') }}" class="nav-link-primary">
                        <i class="fas fa-user mr-2"></i>Área Membro
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link-logout" title="Sair">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-link-primary">
                        <i class="fas fa-sign-in-alt mr-2"></i>Entrar
                    </a>
                @endauth
            </div>

            <!-- Botão Menu Mobile -->
            <div class="lg:hidden">
                <button id="mobile-menu-button" class="text-white p-2 rounded-md hover:bg-white/20 transition-colors">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Painel Menu Mobile -->
    <div id="mobile-menu-panel" class="lg:hidden hidden absolute top-full left-0 w-full bg-blue-700/95 backdrop-blur-sm p-4 shadow-xl">
        <div class="flex flex-col space-y-2">
            <a href="#sobre" class="mobile-nav-link">Sobre</a>
            <a href="#ministerios" class="mobile-nav-link">Ministérios</a>
            <a href="#cultos" class="mobile-nav-link">Cultos</a>
            <a href="{{ route('public.events.index') }}" class="mobile-nav-link">Eventos</a>
            <a href="{{ route('donation.index') }}" class="mobile-nav-link">Contribuir</a>
            <div class="border-t border-white/20 my-2"></div>
            @auth
                <a href="{{ auth()->user()->hasRole('Admin') ? route('admin.dashboard') : route('member.dashboard') }}" class="mobile-nav-link-primary">
                    <i class="fas fa-user mr-2"></i>Área Membro
                </a>
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="mobile-nav-link-logout w-full">
                        <i class="fas fa-sign-out-alt mr-2"></i>Sair
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="mobile-nav-link-primary">
                    <i class="fas fa-sign-in-alt mr-2"></i>Entrar
                </a>
            @endauth
        </div>
    </div>
</header>

<style>
    .nav-link { @apply text-white/90 px-4 py-2 rounded-md text-sm font-medium hover:bg-white/20 hover:text-white transition-all duration-200; }
    .nav-link-primary { @apply bg-white text-blue-700 px-5 py-2 rounded-md text-sm font-bold hover:bg-gray-200 transition-all duration-200 flex items-center; }
    .nav-link-logout { @apply text-white/80 px-3 py-2 rounded-md text-sm font-medium hover:bg-red-500/50 hover:text-white transition-all duration-200; }
    .mobile-nav-link { @apply text-white/90 block px-4 py-3 rounded-md text-base font-medium hover:bg-white/20 hover:text-white transition-all duration-200; }
    .mobile-nav-link-primary { @apply bg-white/90 text-blue-700 block px-4 py-3 rounded-md text-base font-bold hover:bg-white text-center flex items-center justify-center; }
    .mobile-nav-link-logout { @apply bg-red-500/80 text-white block px-4 py-3 rounded-md text-base font-bold hover:bg-red-500 text-center flex items-center justify-center; }
</style>