@php
use App\Helpers\PermissionHelper;
@endphp

@props([
    'currentSection' => '',
    'sidebarCollapsed' => false
])

@if(PermissionHelper::canAccessAdminDashboard())
<div class="sidebar-section">
    <a href="{{ route('admin.dashboard') }}" 
       class="sidebar-item {{ $currentSection === 'dashboard' ? 'active' : '' }}"
       :class="sidebarCollapsed ? 'collapsed' : ''"
       title="Painel Principal">
        
        <i class="sidebar-icon fas fa-tachometer-alt"></i>
        
        <span class="sidebar-text" 
              :class="sidebarCollapsed ? 'opacity-0 w-0' : 'opacity-100'"
              x-transition:enter="transition-all duration-300 delay-100"
              x-transition:enter-start="opacity-0 w-0"
              x-transition:enter-end="opacity-100"
              x-transition:leave="transition-all duration-200"
              x-transition:leave-start="opacity-100"
              x-transition:leave-end="opacity-0 w-0">
            Painel Principal
        </span>
        
        @if($currentSection === 'dashboard')
            <div class="sidebar-indicator" 
                 :class="sidebarCollapsed ? 'opacity-0 w-0' : 'opacity-100'"></div>
        @endif
        
        <!-- Tooltip para sidebar colapsado -->
        <div class="sidebar-tooltip" 
             :class="{
                 'opacity-100 visible': sidebarCollapsed,
                 'opacity-0 invisible': !sidebarCollapsed
             }">
            Painel Principal do Sistema
        </div>
    </a>
</div>
@endif