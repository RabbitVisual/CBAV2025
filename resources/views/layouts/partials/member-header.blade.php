<div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white dark:bg-gray-800 px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
    <button type="button" class="-m-2.5 p-2.5 text-gray-700 dark:text-gray-300 lg:hidden" @click="sidebarOpen = true">
        <span class="sr-only">Abrir sidebar</span>
        <i class="fas fa-bars h-6 w-6"></i>
    </button>

    <!-- Separator -->
    <div class="h-6 w-px bg-gray-200 dark:bg-gray-700 lg:hidden" aria-hidden="true"></div>

    <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6 justify-end">
        <div class="flex items-center gap-x-4 lg:gap-x-6">
            <button type="button" class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-500 dark:text-gray-300 dark:hover:text-gray-200">
                <span class="sr-only">Ver notificações</span>
                <i class="fas fa-bell h-6 w-6"></i>
            </button>

            <!-- Separator -->
            <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-200 dark:bg-gray-700" aria-hidden="true"></div>

            <!-- Profile dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" type="button" class="-m-1.5 flex items-center p-1.5">
                    <span class="sr-only">Abrir menu do usuário</span>
                    <img class="h-8 w-8 rounded-full bg-gray-50" src="{{ auth()->user()->membro->foto_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}" alt="">
                    <span class="hidden lg:flex lg:items-center">
                        <span class="ml-4 text-sm font-semibold leading-6 text-gray-900 dark:text-white" aria-hidden="true">{{ auth()->user()->name }}</span>
                        <i class="fas fa-chevron-down ml-2 h-5 w-5 text-gray-400"></i>
                    </span>
                </button>
                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 z-10 mt-2.5 w-32 origin-top-right rounded-md bg-white dark:bg-gray-800 py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none">
                    <a href="{{ route('member.profile.index') }}" class="block px-3 py-1 text-sm leading-6 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Meu Perfil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-1 text-sm leading-6 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Sair</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>