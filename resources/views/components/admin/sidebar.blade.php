<!-- Static sidebar for desktop -->
<div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
    <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white dark:bg-gray-800 px-6 pb-4 border-r border-gray-200 dark:border-gray-700">
        <div class="flex h-16 shrink-0 items-center">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-x-3">
                @if(config('app.logo_path'))
                    <img class="h-8 w-auto" src="{{ Storage::url(config('app.logo_path')) }}" alt="Logo">
                @else
                    <i class="fas fa-church text-2xl text-blue-600 dark:text-blue-400"></i>
                @endif
                <span class="text-lg font-bold text-gray-800 dark:text-white">{{ config('app.name', 'Painel Admin') }}</span>
            </a>
        </div>
        <nav class="flex flex-1 flex-col">
            <ul role="list" class="flex flex-1 flex-col gap-y-7">
                <li>
                    <ul role="list" class="-mx-2 space-y-1">
                        <x-admin.nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            <i class="fas fa-home"></i>
                            Dashboard
                        </x-admin.nav-link>
                        <x-admin.nav-link :href="route('admin.people.index')" :active="request()->routeIs('admin.people.*')">
                            <i class="fas fa-users"></i>
                            Pessoas
                        </x-admin.nav-link>
                        <x-admin.nav-link :href="route('admin.ebd.index')" :active="request()->routeIs('admin.ebd.*')">
                            <i class="fas fa-book-open"></i>
                            EBD
                        </x-admin.nav-link>
                        <x-admin.nav-link :href="route('admin.finance.dashboard')" :active="request()->routeIs('admin.finance.*')">
                            <i class="fas fa-wallet"></i>
                            Financeiro
                        </x-admin.nav-link>
                        <x-admin.nav-link :href="route('admin.council.index')" :active="request()->routeIs('admin.council.*')">
                            <i class="fas fa-gavel"></i>
                            Conselho
                        </x-admin.nav-link>
                         <x-admin.nav-link :href="route('admin.system.index')" :active="request()->routeIs('admin.system.*')">
                            <i class="fas fa-cogs"></i>
                            Sistema
                        </x-admin.nav-link>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</div>

<!-- Mobile sidebar -->
<div class="relative z-50 lg:hidden" role="dialog" aria-modal="true" x-show="sidebarOpen" @click.away="sidebarOpen = false">
    <div class="fixed inset-0 bg-gray-900/80" x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

    <div class="fixed inset-0 flex">
        <div class="relative mr-16 flex w-full max-w-xs flex-1" x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
            <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                <button type="button" class="-m-2.5 p-2.5" @click="sidebarOpen = false">
                    <span class="sr-only">Fechar sidebar</span>
                    <i class="fas fa-times h-6 w-6 text-white"></i>
                </button>
            </div>
            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white dark:bg-gray-800 px-6 pb-4">
                 <div class="flex h-16 shrink-0 items-center">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-x-3">
                        <i class="fas fa-church text-2xl text-blue-600 dark:text-blue-400"></i>
                        <span class="text-lg font-bold text-gray-800 dark:text-white">{{ config('app.name', 'Painel') }}</span>
                    </a>
                </div>
                <nav class="flex flex-1 flex-col">
                    <ul role="list" class="flex flex-1 flex-col gap-y-7">
                        <li>
                            <ul role="list" class="-mx-2 space-y-1">
                                <!-- Mobile Nav Links Here -->
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>