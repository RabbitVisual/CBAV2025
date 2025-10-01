@php
    use App\Helpers\PermissionHelper;
@endphp

@props([
    'currentSection' => '',
    'currentSubsection' => '',
    'sidebarCollapsed' => false,
])

@if (PermissionHelper::canAccessCouncil())
    <div class="sidebar-section">
        <!-- Seção Principal -->
        <button @click="toggleSection('conselho')"
            class="sidebar-item w-full {{ $currentSection === 'conselho' ? 'active' : '' }}"
            :class="sidebarCollapsed ? 'collapsed' : ''" :aria-expanded="expandedSections.conselho"
            aria-label="Conselho da Igreja">

            <i class="sidebar-icon fas fa-users-cog"></i>

            <span class="sidebar-text flex-1 text-left" :class="sidebarCollapsed ? 'opacity-0 w-0' : 'opacity-100'"
                x-transition:enter="transition-all duration-300 delay-100" x-transition:enter-start="opacity-0 w-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition-all duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 w-0">
                Conselho da Igreja
            </span>

            @if ($currentSection === 'conselho')
                <div class="sidebar-indicator" :class="sidebarCollapsed ? 'opacity-0 w-0' : 'opacity-100'"></div>
            @endif

            <i class="fas fa-chevron-down text-xs transition-transform duration-300 ml-2"
                :class="{
                    'rotate-180': expandedSections.conselho,
                    'opacity-0 w-0': sidebarCollapsed,
                    'opacity-100': !sidebarCollapsed
                }"></i>

            <!-- Tooltip para sidebar colapsado -->
            <div class="sidebar-tooltip"
                :class="{
                    'opacity-100 visible': sidebarCollapsed,
                    'opacity-0 invisible': !sidebarCollapsed
                }">
                Gestão do Conselho
            </div>
        </button>

        <!-- Subitens -->
        <div x-show="expandedSections.conselho && !sidebarCollapsed"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 max-h-0"
            x-transition:enter-end="opacity-100 max-h-96" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 max-h-96" x-transition:leave-end="opacity-0 max-h-0"
            class="sidebar-items ml-4 mt-1 space-y-1 overflow-hidden">

            <!-- Dashboard -->
            <a href="{{ route('admin.council.dashboard') }}"
                class="sidebar-subitem {{ $currentSubsection === '' && $currentSection === 'conselho' ? 'active' : '' }}">
                <i class="sidebar-subitem-icon fas fa-chart-line"></i>
                <span class="sidebar-subitem-text">Dashboard</span>
                @if ($currentSubsection === '' && $currentSection === 'conselho')
                    <div class="sidebar-subitem-indicator"></div>
                @endif
            </a>

            <!-- Reuniões -->
            <a href="{{ route('admin.council.index') }}"
                class="sidebar-subitem {{ $currentSubsection === 'reunioes' ? 'active' : '' }}">
                <i class="sidebar-subitem-icon fas fa-handshake"></i>
                <span class="sidebar-subitem-text">Reuniões</span>
                @if ($currentSubsection === 'reunioes')
                    <div class="sidebar-subitem-indicator"></div>
                @endif
            </a>

            <!-- Votações -->
            <a href="{{ route('admin.council.voting.history') }}"
                class="sidebar-subitem {{ $currentSubsection === 'votacoes' ? 'active' : '' }}">
                <i class="sidebar-subitem-icon fas fa-vote-yea"></i>
                <span class="sidebar-subitem-text">Votações</span>
                @if ($currentSubsection === 'votacoes')
                    <div class="sidebar-subitem-indicator"></div>
                @endif
            </a>

            <!-- Templates -->
            <a href="{{ route('admin.council.agenda.template.index') }}"
                class="sidebar-subitem {{ $currentSubsection === 'templates' ? 'active' : '' }}">
                <i class="sidebar-subitem-icon fas fa-file-contract"></i>
                <span class="sidebar-subitem-text">Templates</span>
                @if ($currentSubsection === 'templates')
                    <div class="sidebar-subitem-indicator"></div>
                @endif
            </a>

            <!-- Configurações -->
            <a href="{{ route('admin.council.settings') }}"
                class="sidebar-subitem {{ $currentSubsection === 'configuracoes' ? 'active' : '' }}">
                <i class="sidebar-subitem-icon fas fa-cog"></i>
                <span class="sidebar-subitem-text">Configurações</span>
                @if ($currentSubsection === 'configuracoes')
                    <div class="sidebar-subitem-indicator"></div>
                @endif
            </a>

            <!-- Nova Reunião -->
            <a href="{{ route('admin.council.create') }}"
                class="sidebar-subitem {{ $currentSubsection === 'nova-reuniao' ? 'active' : '' }}">
                <i class="sidebar-subitem-icon fas fa-plus-circle"></i>
                <span class="sidebar-subitem-text">Nova Reunião</span>
                @if ($currentSubsection === 'nova-reuniao')
                    <div class="sidebar-subitem-indicator"></div>
                @endif
            </a>

            <!-- Área de Membros -->
            <a href="{{ route('member.dashboard') }}"
                class="sidebar-subitem {{ $currentSubsection === 'area-membros' ? 'active' : '' }}">
                <i class="sidebar-subitem-icon fas fa-user-friends"></i>
                <span class="sidebar-subitem-text">Área de Membros</span>
                @if ($currentSubsection === 'area-membros')
                    <div class="sidebar-subitem-indicator"></div>
                @endif
            </a>
        </div>
    </div>
@endif
