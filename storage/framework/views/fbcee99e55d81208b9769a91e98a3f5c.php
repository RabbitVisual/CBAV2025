<!-- Header Profissional para Admin -->
<header
    class="bg-white dark:bg-gray-800 shadow-lg border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40 transition-colors duration-200">
    <div class="flex items-center justify-between px-6 py-4">
        <!-- Lado Esquerdo: Menu e Título -->
        <div class="flex items-center space-x-4">
            <!-- Botão do menu mobile -->
            <button @click="sidebarOpen = true"
                class="lg:hidden p-2 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">
                <i class="fas fa-bars text-xl"></i>
            </button>

            <!-- Botão de colapso do sidebar -->
            <div class="relative group">
                <button @click="sidebarCollapsed = !sidebarCollapsed"
                    class="hidden lg:flex relative p-3 text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gradient-to-r hover:from-primary-50 hover:to-blue-50 dark:hover:from-primary-900/20 dark:hover:to-blue-900/20 rounded-xl transition-all duration-300 hover:scale-110 active:scale-95 hover:shadow-lg border border-transparent hover:border-primary-200 dark:hover:border-primary-700 backdrop-blur-sm"
                    :title="sidebarCollapsed ? 'Expandir Menu Lateral' : 'Recolher Menu Lateral'">
                    <!-- Ícone animado -->
                    <div class="relative w-5 h-5 flex items-center justify-center">
                        <!-- Barras do hambúrguer -->
                        <div class="absolute inset-0 flex flex-col justify-center space-y-1 transition-all duration-300"
                            :class="sidebarCollapsed ? 'transform rotate-0' : 'transform rotate-180'">
                            <div class="w-5 h-0.5 bg-current rounded-full transition-all duration-300"
                                :class="sidebarCollapsed ? 'transform rotate-0 translate-y-0' :
                                    'transform rotate-45 translate-y-1.5'">
                            </div>
                            <div class="w-5 h-0.5 bg-current rounded-full transition-all duration-300"
                                :class="sidebarCollapsed ? 'opacity-100' : 'opacity-0'">
                            </div>
                            <div class="w-5 h-0.5 bg-current rounded-full transition-all duration-300"
                                :class="sidebarCollapsed ? 'transform rotate-0 translate-y-0' :
                                    'transform -rotate-45 -translate-y-1.5'">
                            </div>
                        </div>
                    </div>

                    <!-- Indicador de estado -->
                    <div class="absolute -top-1 -right-1 w-2 h-2 rounded-full transition-all duration-300"
                        :class="sidebarCollapsed ? 'bg-green-500 shadow-lg shadow-green-500/50' :
                            'bg-blue-500 shadow-lg shadow-blue-500/50'">
                    </div>
                </button>

                <!-- Tooltip aprimorado -->
                <div
                    class="absolute left-full ml-2 top-1/2 transform -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-all duration-300 pointer-events-none z-50">
                    <div
                        class="bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900 text-xs font-medium px-3 py-2 rounded-lg shadow-xl border border-gray-700 dark:border-gray-300 whitespace-nowrap">
                        <span x-text="sidebarCollapsed ? 'Expandir Menu Lateral' : 'Recolher Menu Lateral'"></span>
                        <div
                            class="absolute right-full top-1/2 transform -translate-y-1/2 border-4 border-transparent border-r-gray-900 dark:border-r-gray-100">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Título da página -->
            <div class="flex-1">
                <div class="flex items-center space-x-3">
                    <!-- Logo/Ícone -->
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-church text-white text-lg"></i>
                    </div>
                    
                    <!-- Títulos -->
                    <div>
                        <h1 class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            <?php echo $__env->yieldContent('page-title', 'Vertex CRM'); ?>
                        </h1>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium tracking-wide">
                            <?php echo $__env->yieldContent('page-description', 'CRM Ministerial por Reinan Rodrigues'); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lado Direito: Ações e Notificações -->
        <div class="flex items-center space-x-6">
            <!-- Botão Dark Mode -->
            <button @click="darkMode = !darkMode"
                class="p-2 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                title="Alternar modo escuro">
                <i class="fas fa-sun text-lg" x-show="darkMode"></i>
                <i class="fas fa-moon text-lg" x-show="!darkMode"></i>
            </button>

            <!-- Estatísticas Rápidas -->
            <div class="hidden md:flex items-center space-x-4">
                <div class="flex items-center space-x-2 px-3 py-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <i class="fas fa-users text-blue-600 dark:text-blue-400"></i>
                    <span
                        class="text-sm font-medium text-blue-900 dark:text-blue-100"><?php echo e(\App\Models\User::count()); ?></span>
                    <span class="text-xs text-blue-600 dark:text-blue-400">Usuários</span>
                </div>

                <div class="flex items-center space-x-2 px-3 py-2 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <i class="fas fa-bell text-green-600 dark:text-green-400"></i>
                    <span
                        class="text-sm font-medium text-green-900 dark:text-green-100"><?php echo e($notificacoesNaoLidas); ?></span>
                    <span class="text-xs text-green-600 dark:text-green-400">Não lidas</span>
                </div>
            </div>

            <!-- Notificações -->
            <?php echo $__env->make('components.notification-dropdown', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Perfil do Usuário -->
            <div class="relative" x-data="{ profileOpen: false }">
                <button @click="profileOpen = !profileOpen"
                    class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">
                    <?php
                        $user = Auth::user();
                        $userPhotoExists = $user->foto && Storage::disk('public')->exists($user->foto);
                    ?>

                    <?php if($userPhotoExists): ?>
                        <img src="<?php echo e(Storage::url($user->foto)); ?>?v=<?php echo e(md5($user->foto . $user->updated_at)); ?>"
                            alt="<?php echo e($user->name); ?>" class="w-8 h-8 rounded-full object-cover shadow-lg">
                    <?php else: ?>
                        <div
                            class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-semibold">
                                <?php echo e(substr($user->name, 0, 1)); ?>

                            </span>
                        </div>
                    <?php endif; ?>

                    <div class="hidden md:block text-left">
                        <p class="text-sm font-medium text-gray-900 dark:text-white"><?php echo e($user->name); ?></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($user->email); ?></p>
                    </div>
                    <i class="fas fa-chevron-down text-gray-400 dark:text-gray-500 text-xs"></i>
                </button>

                <!-- Dropdown do Perfil -->
                <div x-show="profileOpen" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95" @click.away="profileOpen = false"
                    class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                    <div class="py-2">
                        <a href="<?php echo e(route('admin.profile.index')); ?>"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-user mr-2"></i>
                            Meu Perfil
                        </a>
                        <a href="<?php echo e(route('admin.profile.edit')); ?>"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-cog mr-2"></i>
                            Configurações
                        </a>
                        <hr class="my-2 border-gray-200 dark:border-gray-600">
                        <a href="<?php echo e(route('logout')); ?>"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="block px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Sair
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Scripts para as notificações -->
<script>
    function marcarComoLida(id) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch(`/notifications/mark-read/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(r => r.json()).then(data => {
            if (data.success) {
                const notificacaoElement = document.querySelector(`[onclick="marcarComoLida(${id})"]`).closest(
                    '.px-6');
                if (notificacaoElement) {
                    notificacaoElement.classList.remove('bg-blue-50');
                    const button = notificacaoElement.querySelector('.text-green-600');
                    if (button) button.style.display = 'none';
                }
                const badge = document.querySelector('.animate-pulse');
                if (badge) {
                    const count = parseInt(badge.textContent);
                    if (count > 1) badge.textContent = count - 1;
                    else badge.style.display = 'none';
                }
            }
        });
    }

    function marcarTodasComoLidas() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(r => r.json()).then(data => {
            if (data.success) {
                const badge = document.querySelector('.animate-pulse');
                if (badge) badge.style.display = 'none';
                document.querySelectorAll('.text-green-600').forEach(el => el.style.display = 'none');
                document.querySelectorAll('.bg-blue-50').forEach(el => el.classList.remove('bg-blue-50'));
            }
        });
    }

    function limparTodasNotificacoes() {
        if (!confirm('Tem certeza que deseja limpar todas as notificações?')) return;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch('/notifications/clear-all', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(r => r.json()).then(data => {
            if (data.success) {
                const badge = document.querySelector('.animate-pulse');
                if (badge) badge.style.display = 'none';
                const container = document.querySelector('.max-h-80');
                if (container) {
                    container.innerHTML = `
                    <div class="px-6 py-8 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-bell text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-gray-900 font-medium mb-2">Nenhuma notificação</h3>
                        <p class="text-gray-500 text-sm">Você está em dia com todas as notificações</p>
                    </div>
                `;
                }
            }
        });
    }
</script>
<?php /**PATH C:\wamp64\www\CBAV2025\resources\views/layouts/partials/header-admin.blade.php ENDPATH**/ ?>