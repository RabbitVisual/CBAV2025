<div class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-white dark:bg-gray-800 shadow-xl border-r border-gray-200 dark:border-gray-700"
     x-show="sidebarOpen"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="-translate-x-full"
     x-transition:enter-end="translate-x-0"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="translate-x-0"
     x-transition:leave-end="-translate-x-full">

    <!-- Logo -->
    <div class="flex h-16 shrink-0 items-center px-6 border-b border-gray-200 dark:border-gray-700">
        <h1 class="text-lg font-bold text-gray-800 dark:text-white">Área do Membro</h1>
    </div>

    <!-- Navegação -->
    <nav class="flex flex-1 flex-col overflow-y-auto p-4">
        <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <li>
                <ul role="list" class="-mx-2 space-y-1">
                    <li>
                        <a href="{{ route('member.dashboard') }}" class="flex items-center gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">
                            <i class="fas fa-home h-6 w-6 shrink-0"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('member.ebd.dashboard') }}" class="flex items-center gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">
                            <i class="fas fa-book-reader h-6 w-6 shrink-0"></i>
                            EBD
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('member.donations.index') }}" class="flex items-center gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">
                            <i class="fas fa-heart h-6 w-6 shrink-0"></i>
                            Doações
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('member.ministries.index') }}" class="flex items-center gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">
                            <i class="fas fa-church h-6 w-6 shrink-0"></i>
                            Ministérios
                        </a>
                    </li>
                     <li>
                        <a href="{{ route('member.chat.index') }}" class="flex items-center gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">
                            <i class="fas fa-comments h-6 w-6 shrink-0"></i>
                            Chat
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</div>