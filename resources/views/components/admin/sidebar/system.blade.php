@php
use App\Helpers\PermissionHelper;
@endphp

@props([
    'currentSection' => '',
    'currentSubsection' => '',
    'sidebarCollapsed' => false
])

@if(PermissionHelper::canAccessSystem())
<div class="sidebar-section">
    <!-- Seção Principal -->
    <button @click="toggleSection('sistema')" 
            class="sidebar-item w-full {{ $currentSection === 'sistema' ? 'active' : '' }}"
            :class="sidebarCollapsed ? 'collapsed' : ''"
            :aria-expanded="expandedSections.sistema"
            aria-label="Gestão do Sistema">
        
        <i class="sidebar-icon fas fa-cogs"></i>
        
        <span class="sidebar-text flex-1 text-left" 
              :class="sidebarCollapsed ? 'opacity-0 w-0' : 'opacity-100'"
              x-transition:enter="transition-all duration-300 delay-100"
              x-transition:enter-start="opacity-0 w-0"
              x-transition:enter-end="opacity-100"
              x-transition:leave="transition-all duration-200"
              x-transition:leave-start="opacity-100"
              x-transition:leave-end="opacity-0 w-0">
            Gestão do Sistema
        </span>
        
        @if($currentSection === 'sistema')
            <div class="sidebar-indicator" 
                 :class="sidebarCollapsed ? 'opacity-0 w-0' : 'opacity-100'"></div>
        @endif
        
        <i class="fas fa-chevron-down text-xs transition-transform duration-300 ml-2"
           :class="{
               'rotate-180': expandedSections.sistema,
               'opacity-0 w-0': sidebarCollapsed,
               'opacity-100': !sidebarCollapsed
           }"></i>
        
        <!-- Tooltip para sidebar colapsado -->
        <div class="sidebar-tooltip" 
             :class="{
                 'opacity-100 visible': sidebarCollapsed,
                 'opacity-0 invisible': !sidebarCollapsed
             }">
            Administração do Sistema
        </div>
    </button>
    
    <!-- Subitens -->
    <div x-show="expandedSections.sistema && !sidebarCollapsed" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 max-h-0"
         x-transition:enter-end="opacity-100 max-h-96"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 max-h-96"
         x-transition:leave-end="opacity-0 max-h-0"
         class="sidebar-items ml-4 mt-1 space-y-1 overflow-hidden">
        
        <!-- Dashboard -->
        <a href="{{ route('admin.system.index') }}" 
           class="sidebar-subitem {{ $currentSubsection === '' && $currentSection === 'system' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-chart-line"></i>
            <span class="sidebar-subitem-text">Dashboard</span>
            @if($currentSubsection === '' && $currentSection === 'system')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
        
        <!-- Configurações -->
        <a href="{{ route('admin.system.settings.index') }}" 
           class="sidebar-subitem {{ $currentSubsection === 'settings' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-cog"></i>
            <span class="sidebar-subitem-text">Configurações</span>
            @if($currentSubsection === 'settings')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
        
        <!-- Config. Home -->
        <a href="{{ route('admin.system.home-config.index') }}" 
           class="sidebar-subitem {{ $currentSubsection === 'home-config' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-home"></i>
            <span class="sidebar-subitem-text">Config. Home</span>
            @if($currentSubsection === 'home-config')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
        
        <!-- Chat da Igreja -->
        <a href="{{ route('admin.chat.index') }}" 
           class="sidebar-subitem {{ $currentSubsection === 'chat' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-comments"></i>
            <span class="sidebar-subitem-text">Chat da Igreja</span>
            @if($currentSubsection === 'chat')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
        
        <!-- Notificações -->
        <a href="{{ route('admin.system.notifications.index') }}" 
           class="sidebar-subitem {{ $currentSubsection === 'notifications' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-bell"></i>
            <span class="sidebar-subitem-text">Notificações</span>
            @if($currentSubsection === 'notifications')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
        
        <!-- Logs -->
        <a href="{{ route('admin.system.logs.index') }}" 
           class="sidebar-subitem {{ $currentSubsection === 'logs' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-file-alt"></i>
            <span class="sidebar-subitem-text">Logs</span>
            @if($currentSubsection === 'logs')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
        
        <!-- Permissões -->
        <a href="{{ route('admin.permissions.index') }}" 
           class="sidebar-subitem {{ $currentSubsection === 'permissions' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-shield-alt"></i>
            <span class="sidebar-subitem-text">Permissões</span>
            @if($currentSubsection === 'permissions')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
        
        <!-- Devocionais -->
        <a href="{{ route('admin.devotionals.index') }}" 
           class="sidebar-subitem {{ $currentSubsection === 'devotionals' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-book-open"></i>
            <span class="sidebar-subitem-text">Devocionais</span>
            @if($currentSubsection === 'devotionals')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
    </div>
</div>
@endif