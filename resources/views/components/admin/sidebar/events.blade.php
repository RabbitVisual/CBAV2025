@php
use App\Helpers\PermissionHelper;
@endphp

@props([
    'currentSection' => '',
    'currentSubsection' => '',
    'sidebarCollapsed' => false
])

@if(PermissionHelper::canAccessEventos())
<div class="sidebar-section">
    <!-- Seção Principal -->
    <button @click="toggleSection('eventos')" 
            class="sidebar-item w-full {{ $currentSection === 'eventos' ? 'active' : '' }}"
            :class="sidebarCollapsed ? 'collapsed' : ''"
            :aria-expanded="expandedSections.eventos"
            aria-label="Eventos">
        
        <i class="sidebar-icon fas fa-calendar-check"></i>
        
        <span class="sidebar-text flex-1 text-left" 
              :class="sidebarCollapsed ? 'opacity-0 w-0' : 'opacity-100'"
              x-transition:enter="transition-all duration-300 delay-100"
              x-transition:enter-start="opacity-0 w-0"
              x-transition:enter-end="opacity-100"
              x-transition:leave="transition-all duration-200"
              x-transition:leave-start="opacity-100"
              x-transition:leave-end="opacity-0 w-0">
            Eventos
        </span>
        
        @if($currentSection === 'eventos')
            <div class="sidebar-indicator" 
                 :class="sidebarCollapsed ? 'opacity-0 w-0' : 'opacity-100'"></div>
        @endif
        
        <i class="fas fa-chevron-down text-xs transition-transform duration-300 ml-2"
           :class="{
               'rotate-180': expandedSections.eventos,
               'opacity-0 w-0': sidebarCollapsed,
               'opacity-100': !sidebarCollapsed
           }"></i>
        
        <!-- Tooltip para sidebar colapsado -->
        <div class="sidebar-tooltip" 
             :class="{
                 'opacity-100 visible': sidebarCollapsed,
                 'opacity-0 invisible': !sidebarCollapsed
             }">
            Gestão de Eventos
        </div>
    </button>
    
    <!-- Subitens -->
    <div x-show="expandedSections.eventos && !sidebarCollapsed" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 max-h-0"
         x-transition:enter-end="opacity-100 max-h-96"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 max-h-96"
         x-transition:leave-end="opacity-0 max-h-0"
         class="sidebar-items ml-4 mt-1 space-y-1 overflow-hidden">
        
        <!-- Todos os Eventos -->
        <a href="{{ route('admin.eventos.index') }}" 
           class="sidebar-subitem {{ $currentSubsection === 'eventos' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-calendar-alt"></i>
            <span class="sidebar-subitem-text">Todos os Eventos</span>
            @if($currentSubsection === 'eventos')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
        
        <!-- Criar Evento -->
        <a href="{{ route('admin.eventos.create') }}" 
           class="sidebar-subitem {{ $currentSubsection === 'criar' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-plus-circle"></i>
            <span class="sidebar-subitem-text">Criar Evento</span>
            @if($currentSubsection === 'criar')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
    </div>
</div>
@endif