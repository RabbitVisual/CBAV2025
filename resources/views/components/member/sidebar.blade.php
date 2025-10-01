@php
use App\Helpers\PermissionHelper;

// Determinar a seção e subseção atual com base na rota
$currentRoute = request()->route()->getName();
$currentSection = '';
$currentSubsection = '';

// Mapear rotas para seções
if (str_starts_with($currentRoute, 'member.dashboard')) {
    $currentSection = 'dashboard';
} elseif (str_starts_with($currentRoute, 'member.profile')) {
    $currentSection = 'profile';
} elseif (str_starts_with($currentRoute, 'member.devotionals')) {
    $currentSection = 'devotionals';
} elseif (str_starts_with($currentRoute, 'member.bible')) {
    $currentSection = 'bible';
} elseif (str_starts_with($currentRoute, 'member.prayer') || str_contains($currentRoute, 'pedidos-oracao')) {
    $currentSection = 'prayer';
} elseif (str_starts_with($currentRoute, 'member.events') || str_contains($currentRoute, 'eventos')) {
    $currentSection = 'events';
} elseif (str_starts_with($currentRoute, 'member.ministries')) {
    $currentSection = 'ministries';
} elseif (str_starts_with($currentRoute, 'member.ebd')) {
    $currentSection = 'ebd';
} elseif (str_starts_with($currentRoute, 'member.donations')) {
    $currentSection = 'donations';
} elseif (str_starts_with($currentRoute, 'member.chat')) {
    $currentSection = 'chat';
} elseif (str_starts_with($currentRoute, 'member.notifications')) {
    $currentSection = 'notifications';
}

// Definir categorias expansíveis
$expandedCategories = session('sidebar_expanded', ['dashboard', 'profile', 'donations', 'communication', 'spiritual', 'events', 'prayer']);

// Obter dados do membro
$membro = Auth::user()->membro;
$alunoEbd = null;

// Verificar se o usuário está matriculado na EBD
if ($membro) {
    $alunoEbd = \App\Models\EbdAluno::where('membro_id', $membro->id)
                                    ->where('status', 'ativo')
                                    ->with('turma')
                                    ->first();
}

if (!$alunoEbd) {
    $alunoEbd = \App\Models\EbdAluno::where('email', Auth::user()->email)
                                    ->where('status', 'ativo')
                                    ->with('turma')
                                    ->first();
}

// Get unread notifications count
$unreadCount = auth()->user()->unreadNotifications()->count();
@endphp

<!-- Sidebar -->
<div id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700 transform transition-transform duration-300 ease-in-out lg:translate-x-0 flex flex-col h-screen"
     :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }"
     x-show="sidebarOpen || window.innerWidth >= 1024">
    
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <img class="h-8 w-8" src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="h-8 w-8 bg-primary-600 rounded-lg flex items-center justify-center text-white font-bold text-sm" style="display: none;">
                    {{ substr(config('app.name'), 0, 1) }}
                </div>
            </div>
            <div class="hidden lg:block">
                <h1 class="text-lg font-semibold text-gray-900 dark:text-white">{{ config('app.name') }}</h1>
            </div>
        </div>
        
        <!-- Mobile close button -->
        <button @click="sidebarOpen = false" class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500">
            <span class="sr-only">Fechar sidebar</span>
            <i class="fas fa-times h-5 w-5"></i>
        </button>
    </div>
    
    <!-- User Info -->
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <div class="h-10 w-10 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center relative">
                    @if($membro && $membro->foto_existe)
                        <img src="{{ $membro->foto_url }}" alt="Avatar" class="h-10 w-10 rounded-full object-cover">
                    @else
                        <span class="text-sm font-medium text-primary-600 dark:text-primary-400">
                            {{ $membro ? $membro->iniciais : Auth::user()->iniciais }}
                        </span>
                    @endif
                    <div class="absolute -bottom-0.5 -right-0.5 h-3 w-3 bg-green-400 border-2 border-white dark:border-gray-900 rounded-full"></div>
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                    {{ Auth::user()->name }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                    Membro Ativo
                </p>
                @if($membro && $membro->primeiro_ministerio)
                    <p class="text-xs text-primary-600 dark:text-primary-400 truncate">
                        {{ $membro->primeiro_ministerio }}
                    </p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 overflow-hidden">
        <div class="sidebar-scroll-container h-full overflow-y-auto pr-2 custom-scrollbar">
            <div class="space-y-1 pb-6">
        <!-- Dashboard -->
        <div class="mb-6">
            <p class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">
                Área do Membro
            </p>
            <a href="{{ route('member.dashboard') }}"
               class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ $currentSection === 'dashboard' ? 'bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                <i class="fas fa-tachometer-alt mr-3 h-5 w-5 {{ $currentSection === 'dashboard' ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                Dashboard
            </a>
        </div>
        
        <!-- Perfil -->
        <div class="mb-6">
            <p class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">
                Perfil
            </p>
            <a href="{{ route('member.profile.index') }}"
               class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ $currentSection === 'profile' ? 'bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                <i class="fas fa-user mr-3 h-5 w-5 {{ $currentSection === 'profile' ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                Meu Perfil
            </a>
        </div>
        
        <!-- Doações -->
        <div class="mb-6">
            <div class="category-header cursor-pointer" onclick="toggleCategory('donations')">
                <p class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3 flex items-center justify-between hover:text-gray-700 dark:hover:text-gray-300 transition-colors duration-200">
                    <span>Doações & Campanhas</span>
                    <svg class="category-arrow h-4 w-4 transform transition-transform duration-200 {{ in_array('donations', $expandedCategories) ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </p>
            </div>
            <div id="category-donations" class="category-content space-y-1 {{ in_array('donations', $expandedCategories) ? '' : 'hidden' }}">
                <a href="{{ route('member.donations.donate') }}"
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ $currentSection === 'donations' ? 'bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                    <i class="fas fa-heart mr-3 h-5 w-5 {{ $currentSection === 'donations' ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                    Fazer Doação
                </a>
                <a href="{{ route('member.donations.index') }}"
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ $currentSection === 'donations' ? 'bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                    <i class="fas fa-receipt mr-3 h-5 w-5 {{ $currentSection === 'donations' ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                    Minhas Doações
                </a>
                <a href="{{ route('member.donations.campaigns') }}"
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white">
                    <i class="fas fa-bullhorn mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300"></i>
                    Campanhas
                </a>
            </div>
        </div>
        
        <!-- Ministérios -->
        <div class="mb-6">
            <p class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">
                Ministérios
            </p>
            <a href="{{ route('member.ministries.index') }}"
               class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ $currentSection === 'ministries' ? 'bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                <i class="fas fa-hands-helping mr-3 h-5 w-5 {{ $currentSection === 'ministries' ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                Ministérios
            </a>
        </div>
        
        <!-- Comunicação -->
        <div class="mb-6">
            <div class="category-header cursor-pointer" onclick="toggleCategory('communication')">
                <p class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3 flex items-center justify-between hover:text-gray-700 dark:hover:text-gray-300 transition-colors duration-200">
                    <span>Comunicação</span>
                    <svg class="category-arrow h-4 w-4 transform transition-transform duration-200 {{ in_array('communication', $expandedCategories) ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </p>
            </div>
            <div id="category-communication" class="category-content space-y-1 {{ in_array('communication', $expandedCategories) ? '' : 'hidden' }}">
                <a href="{{ route('member.chat.index') }}"
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ $currentSection === 'chat' ? 'bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                    <i class="fas fa-comments mr-3 h-5 w-5 {{ $currentSection === 'chat' ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                    Chat da Igreja
                </a>
                <a href="{{ route('member.notifications.index') }}"
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ $currentSection === 'notifications' ? 'bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                    <i class="fas fa-bell mr-3 h-5 w-5 {{ $currentSection === 'notifications' ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                    Notificações
                    @if(isset($unreadCount) && $unreadCount > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                        </span>
                    @endif
                </a>
            </div>
        </div>
        
        <!-- Escola Bíblica Dominical -->
         @if($alunoEbd)
         <div class="mb-6">
             <div class="category-header cursor-pointer" onclick="toggleCategory('ebd')">
                 <p class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3 flex items-center justify-between hover:text-gray-700 dark:hover:text-gray-300 transition-colors duration-200">
                     <span>Escola Bíblica Dominical</span>
                     <svg class="category-arrow h-4 w-4 transform transition-transform duration-200 {{ in_array('ebd', $expandedCategories) ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                     </svg>
                 </p>
             </div>
             <div id="category-ebd" class="category-content space-y-1 {{ in_array('ebd', $expandedCategories) ? '' : 'hidden' }}">
                 <a href="{{ route('member.ebd.dashboard') }}"
                    class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ $currentSection === 'ebd' ? 'bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                     <i class="fas fa-graduation-cap mr-3 h-5 w-5 {{ $currentSection === 'ebd' ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                     Dashboard EBD
                     @if($alunoEbd && $alunoEbd->turma)
                         <span class="ml-auto text-xs bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400 px-2 py-1 rounded-full">
                             {{ $alunoEbd->turma->nome }}
                         </span>
                     @endif
                 </a>
                 <a href="{{ route('member.ebd.quiz-biblico.index') }}"
                                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ str_contains($currentRoute, 'quiz-biblico') ? 'bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                                    <i class="fas fa-question-circle mr-3 h-5 w-5 {{ str_contains($currentRoute, 'quiz-biblico') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                      Quiz Bíblico
                  </a>
                 <a href="{{ route('member.ebd.grupos.index') }}"
                    class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ str_contains($currentRoute, 'grupos') ? 'bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                     <i class="fas fa-users mr-3 h-5 w-5 {{ str_contains($currentRoute, 'grupos') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                     Grupos de Estudo
                 </a>
             </div>
         </div>
         @endif
        
        <!-- Recursos Espirituais -->
        <div class="mb-6">
            <div class="category-header cursor-pointer" onclick="toggleCategory('spiritual')">
                <p class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3 flex items-center justify-between hover:text-gray-700 dark:hover:text-gray-300 transition-colors duration-200">
                    <span>Recursos Espirituais</span>
                    <svg class="category-arrow h-4 w-4 transform transition-transform duration-200 {{ in_array('spiritual', $expandedCategories) ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </p>
            </div>
            <div id="category-spiritual" class="category-content space-y-1 {{ in_array('spiritual', $expandedCategories) ? '' : 'hidden' }}">
                <a href="{{ route('member.bible.index') }}"
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ $currentSection === 'bible' ? 'bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                    <i class="fas fa-book-open mr-3 h-5 w-5 {{ $currentSection === 'bible' ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                    Bíblia Digital
                </a>
                <a href="{{ route('member.devotionals.index') }}"
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ $currentSection === 'devotionals' ? 'bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                    <i class="fas fa-pray mr-3 h-5 w-5 {{ $currentSection === 'devotionals' ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                    Devocionais
                </a>
            </div>
        </div>
        
        <!-- Eventos -->
        <div class="mb-6">
            <div class="category-header cursor-pointer" onclick="toggleCategory('events')">
                <p class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3 flex items-center justify-between hover:text-gray-700 dark:hover:text-gray-300 transition-colors duration-200">
                    <span>Eventos</span>
                    <svg class="category-arrow h-4 w-4 transform transition-transform duration-200 {{ in_array('events', $expandedCategories) ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </p>
            </div>
            <div id="category-events" class="category-content space-y-1 {{ in_array('events', $expandedCategories) ? '' : 'hidden' }}">
                <a href="{{ route('member.eventos.index') }}"
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ $currentSection === 'events' ? 'bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                    <i class="fas fa-calendar-alt mr-3 h-5 w-5 {{ $currentSection === 'events' ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                    Eventos
                </a>
                <a href="{{ route('member.eventos.minhas-inscricoes') }}"
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white">
                    <i class="fas fa-ticket-alt mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300"></i>
                    Minhas Inscrições
                </a>
            </div>
        </div>
        
        <!-- Pedidos de Oração -->
        <div class="mb-6">
            <div class="category-header cursor-pointer" onclick="toggleCategory('prayer')">
                <p class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3 flex items-center justify-between hover:text-gray-700 dark:hover:text-gray-300 transition-colors duration-200">
                    <span>Pedidos de Oração</span>
                    <svg class="category-arrow h-4 w-4 transform transition-transform duration-200 {{ in_array('prayer', $expandedCategories) ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </p>
            </div>
            <div id="category-prayer" class="category-content space-y-1 {{ in_array('prayer', $expandedCategories) ? '' : 'hidden' }}">
                <a href="{{ route('member.pedidos-oracao.index') }}"
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ $currentSection === 'prayer' ? 'bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                    <i class="fas fa-praying-hands mr-3 h-5 w-5 {{ $currentSection === 'prayer' ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
                    Meus Pedidos
                </a>
                <a href="{{ route('member.pedidos-oracao.create') }}"
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white">
                    <i class="fas fa-plus mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300"></i>
                    Novo Pedido
                </a>
            </div>
        </div>
         
         <!-- Acesso Rápido à Área Admin -->
         @if(PermissionHelper::hasAdminAccess())
         <div class="mb-6">
             <p class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">
                 Acesso Administrativo
             </p>
             <a href="{{ route('admin.dashboard') }}"
                class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 text-gray-700 dark:text-gray-300 hover:bg-green-100 dark:hover:bg-green-900 hover:text-green-900 dark:hover:text-green-100">
                 <i class="fas fa-cog mr-3 h-5 w-5 text-green-600 dark:text-green-400"></i>
                 Painel Admin
             </a>
         </div>
         @endif
         
         <!-- Admin Access (if applicable) -->
         @if(PermissionHelper::hasAdminAccess())
        <div class="pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
            <p class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">
                Áreas Especiais
            </p>
            
            @if(auth()->user()->hasPermissionTo('admin master'))
            <a href="{{ route('admin.dashboard') }}"
               class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white">
                <i class="fas fa-cogs mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300"></i>
                Painel Admin
            </a>
            @endif
            
            @if(auth()->user()->hasPermissionTo('intercessor.access'))
            <a href="{{ route('admin.intercessor.dashboard') }}"
               class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white">
                <i class="fas fa-praying-hands mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300"></i>
                Intercessor
            </a>
            @endif
            
            @if(auth()->user()->hasPermissionTo('council.access'))
            <a href="{{ route('admin.council.dashboard') }}"
               class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white">
                <i class="fas fa-users-cog mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300"></i>
                Conselho
            </a>
            @endif
            
            @if(auth()->user()->hasPermissionTo('people.access'))
            <a href="{{ route('admin.people.index') }}"
               class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white">
                <i class="fas fa-users mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300"></i>
                Pessoas
            </a>
            @endif
            
            @if(auth()->user()->hasPermissionTo('finance.access'))
            <a href="{{ route('admin.finance.dashboard') }}"
               class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white">
                <i class="fas fa-chart-line mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300"></i>
                Financeiro
            </a>
            @endif
            
            @if(auth()->user()->hasPermissionTo('ebd.access'))
            <a href="{{ route('admin.ebd.dashboard') }}"
               class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white">
                <i class="fas fa-graduation-cap mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300"></i>
                EBD Admin
            </a>
            @endif
            
            @if(auth()->user()->hasPermissionTo('devotionals.access'))
            <a href="{{ route('admin.devotionals.index') }}"
               class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white">
                <i class="fas fa-book mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300"></i>
                Devocionais
            </a>
            @endif
            
            @if(auth()->user()->hasPermissionTo('system.access'))
            <a href="{{ route('admin.system.dashboard') }}"
               class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white">
                <i class="fas fa-cog mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300"></i>
                Sistema
            </a>
            @endif
        </div>
        @endif
        </div>
    </nav>
    
    <!-- Logout -->
    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                    onclick="return confirm('Tem certeza que deseja sair?')"
                    class="w-full group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 text-red-700 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-900 dark:hover:text-red-300">
                <i class="fas fa-sign-out-alt mr-3 h-5 w-5 text-red-500 group-hover:text-red-600 dark:group-hover:text-red-400"></i>
                Sair
            </button>
        </form>
    </div>
</div>

<style>
/* Garantir que o sidebar tenha altura fixa e overflow funcional */
#sidebar {
    height: 100vh;
    max-height: 100vh;
    overflow: hidden;
}

.sidebar-scroll-container {
    height: calc(100vh - 200px); /* Ajustar baseado na altura do header e user info */
    overflow-y: scroll !important;
    overflow-x: hidden;
    scrollbar-width: thin;
    scrollbar-color: rgba(156, 163, 175, 0.6) rgba(243, 244, 246, 0.1);
}

/* Barra de rolagem personalizada - sempre visível */
.custom-scrollbar::-webkit-scrollbar {
    width: 10px;
    background: rgba(243, 244, 246, 0.3);
    border-radius: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(243, 244, 246, 0.2);
    border-radius: 6px;
    margin: 2px 0;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, rgba(156, 163, 175, 0.7), rgba(107, 114, 128, 0.9));
    border-radius: 6px;
    border: 1px solid rgba(243, 244, 246, 0.3);
    transition: all 0.2s ease;
    min-height: 30px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, rgba(107, 114, 128, 0.9), rgba(75, 85, 99, 1));
    transform: scaleX(1.2);
}

.custom-scrollbar::-webkit-scrollbar-thumb:active {
    background: linear-gradient(135deg, rgba(75, 85, 99, 1), rgba(55, 65, 81, 1));
}

/* Dark mode */
.dark .sidebar-scroll-container {
    scrollbar-color: rgba(75, 85, 99, 0.8) rgba(31, 41, 55, 0.2);
}

.dark .custom-scrollbar::-webkit-scrollbar {
    background: rgba(31, 41, 55, 0.4);
}

.dark .custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(31, 41, 55, 0.3);
}

.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, rgba(75, 85, 99, 0.8), rgba(107, 114, 128, 1));
    border: 1px solid rgba(31, 41, 55, 0.4);
}

.dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, rgba(107, 114, 128, 1), rgba(156, 163, 175, 1));
}

.dark .custom-scrollbar::-webkit-scrollbar-thumb:active {
    background: linear-gradient(135deg, rgba(156, 163, 175, 1), rgba(209, 213, 219, 1));
}

.category-content {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
    transform-origin: top;
}

.category-content.hidden {
    max-height: 0;
    opacity: 0;
    transform: scaleY(0);
    margin-top: 0;
    margin-bottom: 0;
}

.category-content:not(.hidden) {
    max-height: 500px;
    opacity: 1;
    transform: scaleY(1);
}

.category-header {
    transition: all 0.3s ease;
    border-radius: 0.75rem;
    position: relative;
    overflow: hidden;
}

.category-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, transparent 0%, rgba(59, 130, 246, 0.1) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.category-header:hover {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(147, 197, 253, 0.12) 100%);
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.15);
    transform: translateY(-1px);
}

.category-header:hover::before {
    opacity: 1;
}

.category-header:active {
    transform: translateY(0);
    box-shadow: 0 1px 4px rgba(59, 130, 246, 0.2);
}

.dark .category-header:hover {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(147, 197, 253, 0.08) 100%);
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.25);
}

.category-arrow {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.1));
}

.category-header:hover .category-arrow {
    color: #3b82f6;
    transform: scale(1.1);
}

.dark .category-header:hover .category-arrow {
    color: #60a5fa;
}

/* Melhorar links dentro das categorias */
.category-content a {
    transition: all 0.3s ease;
    border-radius: 0.5rem;
    position: relative;
    overflow: hidden;
}

.category-content a::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: linear-gradient(180deg, #3b82f6 0%, #1d4ed8 100%);
    transform: scaleY(0);
    transition: transform 0.3s ease;
}

.category-content a:hover::before {
    transform: scaleY(1);
}

.category-content a:hover {
    padding-left: 1rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transform: translateX(4px);
}

.dark .category-content a:hover {
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}
</style>

<script>
function toggleCategory(categoryName) {
    const categoryContent = document.getElementById('category-' + categoryName);
    const categoryHeader = document.querySelector(`[onclick="toggleCategory('${categoryName}')"]`);
    const arrow = categoryHeader ? categoryHeader.querySelector('.category-arrow') : null;
    
    if (categoryContent) {
        const isHidden = categoryContent.classList.contains('hidden');
        
        if (isHidden) {
            // Expandir categoria
            categoryContent.classList.remove('hidden');
            if (arrow) {
                arrow.classList.add('rotate-180');
            }
            
            // Adicionar efeito de entrada suave
            categoryContent.style.animation = 'slideDown 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards';
        } else {
            // Colapsar categoria
            categoryContent.style.animation = 'slideUp 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards';
            
            setTimeout(() => {
                categoryContent.classList.add('hidden');
            }, 350);
            
            if (arrow) {
                arrow.classList.remove('rotate-180');
            }
        }
    }
}

// Adicionar animações CSS dinamicamente
const style = document.createElement('style');
style.textContent = `
    @keyframes slideDown {
        from {
            max-height: 0;
            opacity: 0;
            transform: scaleY(0);
        }
        to {
            max-height: 500px;
            opacity: 1;
            transform: scaleY(1);
        }
    }
    
    @keyframes slideUp {
        from {
            max-height: 500px;
            opacity: 1;
            transform: scaleY(1);
        }
        to {
            max-height: 0;
            opacity: 0;
            transform: scaleY(0);
        }
    }
`;
document.head.appendChild(style);

// Inicializar estado das categorias ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    const expandedCategories = @json($expandedCategories);
    
    // Aguardar um pouco para garantir que o DOM esteja totalmente carregado
    setTimeout(() => {
        expandedCategories.forEach(function(category) {
            const categoryContent = document.getElementById('category-' + category);
            const categoryHeader = document.querySelector(`[onclick="toggleCategory('${category}')"]`);
            const arrow = categoryHeader ? categoryHeader.querySelector('.category-arrow') : null;
            
            if (categoryContent) {
                if (expandedCategories.includes(category)) {
                    categoryContent.classList.remove('hidden');
                    if (arrow) arrow.classList.add('rotate-180');
                } else {
                    categoryContent.classList.add('hidden');
                    if (arrow) arrow.classList.remove('rotate-180');
                }
            }
        });
    }, 100);
});
</script>