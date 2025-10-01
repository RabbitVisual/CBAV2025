@php
    use App\Helpers\PermissionHelper;
@endphp

@props([
    'currentSection' => '',
    'currentSubsection' => '',
    'sidebarCollapsed' => false,
])

@if (PermissionHelper::canAccessIntercessor())
    <div class="sidebar-section">
        <!-- Seção Principal -->
        <button @click="toggleSection('intercessor')"
            class="sidebar-item w-full {{ $currentSection === 'intercessor' ? 'active' : '' }}"
            :class="sidebarCollapsed ? 'collapsed' : ''" :aria-expanded="expandedSections.intercessor"
            aria-label="Intercessor">

            <i class="sidebar-icon fas fa-praying-hands"></i>

            <span class="sidebar-text flex-1 text-left" :class="sidebarCollapsed ? 'opacity-0 w-0' : 'opacity-100'"
                x-transition:enter="transition-all duration-300 delay-100" x-transition:enter-start="opacity-0 w-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition-all duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 w-0">
                Intercessor
            </span>

            @if ($currentSection === 'intercessor')
                <div class="sidebar-indicator" :class="sidebarCollapsed ? 'opacity-0 w-0' : 'opacity-100'"></div>
            @endif

            <i class="fas fa-chevron-down text-xs transition-transform duration-300 ml-2"
                :class="{
                    'rotate-180': expandedSections.intercessor,
                    'opacity-0 w-0': sidebarCollapsed,
                    'opacity-100': !sidebarCollapsed
                }"></i>

            <!-- Tooltip para sidebar colapsado -->
            <div class="sidebar-tooltip"
                :class="{
                    'opacity-100 visible': sidebarCollapsed,
                    'opacity-0 invisible': !sidebarCollapsed
                }">
                Ministério de Intercessão
            </div>
        </button>

        <!-- Subitens -->
        <div x-show="expandedSections.intercessor && !sidebarCollapsed"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 max-h-0"
            x-transition:enter-end="opacity-100 max-h-96" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 max-h-96" x-transition:leave-end="opacity-0 max-h-0"
            class="sidebar-items ml-4 mt-1 space-y-1 overflow-hidden">

            <!-- Dashboard -->
            <a href="{{ route('admin.intercessor.index') }}"
                class="sidebar-subitem {{ $currentSubsection === '' && $currentSection === 'intercessor' ? 'active' : '' }}">
                <i class="sidebar-subitem-icon fas fa-chart-line"></i>
                <span class="sidebar-subitem-text">Dashboard</span>
                @if ($currentSubsection === '' && $currentSection === 'intercessor')
                    <div class="sidebar-subitem-indicator"></div>
                @endif
            </a>

            <!-- Pedidos de Oração -->
            <a href="{{ route('admin.intercessor.index') }}"
                class="sidebar-subitem {{ $currentSubsection === 'pedidos' ? 'active' : '' }}">
                <i class="sidebar-subitem-icon fas fa-hands"></i>
                <span class="sidebar-subitem-text">Pedidos de Oração</span>
                @if ($currentSubsection === 'pedidos')
                    <div class="sidebar-subitem-indicator"></div>
                @endif
            </a>
        </div>
    </div>
@endif
