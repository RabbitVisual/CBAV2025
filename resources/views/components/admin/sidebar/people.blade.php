{{-- Componente Gestão de Pessoas do Sidebar Admin --}}
@php
use App\Helpers\PermissionHelper;
@endphp

@props([
    'currentSection' => '',
    'currentSubsection' => '',
    'sidebarCollapsed' => false
])

@if(PermissionHelper::canAccessPeople())
<div class="sidebar-section">
    <!-- Seção Principal -->
    <button @click="toggleSection('people')" 
            class="sidebar-item w-full {{ $currentSection === 'people' ? 'active' : '' }}"
            :class="sidebarCollapsed ? 'collapsed' : ''"
            :aria-expanded="expandedSections.people"
            aria-label="Gestão de Pessoas">
        
        <i class="sidebar-icon fas fa-users"></i>
        
        <span class="sidebar-text flex-1 text-left" 
              :class="sidebarCollapsed ? 'opacity-0 w-0' : 'opacity-100'"
              x-transition:enter="transition-all duration-300 delay-100"
              x-transition:enter-start="opacity-0 w-0"
              x-transition:enter-end="opacity-100"
              x-transition:leave="transition-all duration-200"
              x-transition:leave-start="opacity-100"
              x-transition:leave-end="opacity-0 w-0">
            Gestão de Pessoas
        </span>
        
        @if($currentSection === 'people')
            <div class="sidebar-indicator" 
                 :class="sidebarCollapsed ? 'opacity-0 w-0' : 'opacity-100'"></div>
        @endif
        
        <i class="fas fa-chevron-down text-xs transition-transform duration-300 ml-2"
           :class="{
               'rotate-180': expandedSections.people,
               'opacity-0 w-0': sidebarCollapsed,
               'opacity-100': !sidebarCollapsed
           }"></i>
        
        <!-- Tooltip para sidebar colapsado -->
        <div class="sidebar-tooltip" 
             :class="{
                 'opacity-100 visible': sidebarCollapsed,
                 'opacity-0 invisible': !sidebarCollapsed
             }">
            Gerenciar Membros e Visitantes
        </div>
    </button>
    
    <!-- Subitens -->
    <div x-show="expandedSections.people && !sidebarCollapsed" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 max-h-0"
         x-transition:enter-end="opacity-100 max-h-96"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 max-h-96"
         x-transition:leave-end="opacity-0 max-h-0"
         class="sidebar-items ml-4 mt-1 space-y-1 overflow-hidden">
        
        <!-- Dashboard -->
        <a href="{{ route('admin.people.index') }}" 
           class="sidebar-subitem {{ $currentSubsection === '' && $currentSection === 'people' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-chart-line"></i>
            <span class="sidebar-subitem-text">Dashboard</span>
            @if($currentSubsection === '' && $currentSection === 'people')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
        
        <!-- Membros -->
        @if(PermissionHelper::canAccessMembers())
        <a href="{{ route('admin.people.members.index') }}" 
           class="sidebar-subitem {{ $currentSubsection === 'members' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-user-friends"></i>
            <span class="sidebar-subitem-text">Membros</span>
            @if($currentSubsection === 'members')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
        @endif
        
        <!-- Usuários -->
        @if(PermissionHelper::canAccessUsers())
        <a href="{{ route('admin.people.users.index') }}" 
           class="sidebar-subitem {{ $currentSubsection === 'users' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-user-cog"></i>
            <span class="sidebar-subitem-text">Usuários</span>
            @if($currentSubsection === 'users')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
        @endif
        
        <!-- Ministérios -->
        @if(PermissionHelper::canAccessMinistries())
        <a href="{{ route('admin.people.ministries.index') }}" 
           class="sidebar-subitem {{ $currentSubsection === 'ministries' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-church"></i>
            <span class="sidebar-subitem-text">Ministérios</span>
            @if($currentSubsection === 'ministries')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
        @endif
        
        <!-- Departamentos -->
        @if(PermissionHelper::canAccessMinistries())
        <a href="{{ route('admin.people.departments.index') }}" 
           class="sidebar-subitem {{ $currentSubsection === 'departments' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-sitemap"></i>
            <span class="sidebar-subitem-text">Departamentos</span>
            @if($currentSubsection === 'departments')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
        @endif
        
        <!-- Cargos -->
        @if(PermissionHelper::canAccessMembers())
        <a href="{{ route('admin.people.cargos.index') }}" 
           class="sidebar-subitem {{ $currentSubsection === 'cargos' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-briefcase"></i>
            <span class="sidebar-subitem-text">Cargos</span>
            @if($currentSubsection === 'cargos')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
        @endif
        
        <!-- CEPs -->
        @if(PermissionHelper::canAccessMembers())
        <a href="{{ route('admin.people.ceps.index') }}" 
           class="sidebar-subitem {{ $currentSubsection === 'ceps' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-map-marker-alt"></i>
            <span class="sidebar-subitem-text">CEPs</span>
            @if($currentSubsection === 'ceps')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
        @endif
        
        <!-- Aniversariantes -->
        @if(PermissionHelper::canAccessMembers())
        <a href="{{ route('admin.people.birthdays.index') }}" 
           class="sidebar-subitem {{ $currentSubsection === 'birthdays' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-birthday-cake"></i>
            <span class="sidebar-subitem-text">Aniversariantes</span>
            @if($currentSubsection === 'birthdays')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
        @endif
    </div>
</div>
@endif