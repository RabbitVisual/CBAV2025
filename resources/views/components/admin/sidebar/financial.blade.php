@php
    use App\Helpers\PermissionHelper;
@endphp

@props([
    'currentSection' => '',
    'currentSubsection' => '',
    'sidebarCollapsed' => false,
])

@if (PermissionHelper::canAccessFinance())
    <div class="sidebar-section">
        <!-- Seção Principal -->
        <button @click="toggleSection('financeiro')"
            class="sidebar-item w-full {{ $currentSection === 'financeiro' ? 'active' : '' }}"
            :class="sidebarCollapsed ? 'collapsed' : ''" :aria-expanded="expandedSections.financeiro"
            aria-label="Gestão Financeira">

            <i class="sidebar-icon fas fa-dollar-sign"></i>

            <span class="sidebar-text flex-1 text-left" :class="sidebarCollapsed ? 'opacity-0 w-0' : 'opacity-100'"
                x-transition:enter="transition-all duration-300 delay-100" x-transition:enter-start="opacity-0 w-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition-all duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 w-0">
                Gestão Financeira
            </span>

            @if ($currentSection === 'financeiro')
                <div class="sidebar-indicator" :class="sidebarCollapsed ? 'opacity-0 w-0' : 'opacity-100'"></div>
            @endif

            <i class="fas fa-chevron-down text-xs transition-transform duration-300 ml-2"
                :class="{
                    'rotate-180': expandedSections.financeiro,
                    'opacity-0 w-0': sidebarCollapsed,
                    'opacity-100': !sidebarCollapsed
                }"></i>

            <!-- Tooltip para sidebar colapsado -->
            <div class="sidebar-tooltip"
                :class="{
                    'opacity-100 visible': sidebarCollapsed,
                    'opacity-0 invisible': !sidebarCollapsed
                }">
                Controle Financeiro
            </div>
        </button>

        <!-- Subitens -->
        <div x-show="expandedSections.financeiro && !sidebarCollapsed"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 max-h-0"
            x-transition:enter-end="opacity-100 max-h-96" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 max-h-96" x-transition:leave-end="opacity-0 max-h-0"
            class="sidebar-items ml-4 mt-1 space-y-1 overflow-hidden">

            <!-- Dashboard -->
            <a href="{{ route('admin.finance.index') }}"
                class="sidebar-subitem {{ $currentSubsection === '' && $currentSection === 'financeiro' ? 'active' : '' }}">
                <i class="sidebar-subitem-icon fas fa-chart-pie"></i>
                <span class="sidebar-subitem-text">Dashboard</span>
                @if ($currentSubsection === '' && $currentSection === 'financeiro')
                    <div class="sidebar-subitem-indicator"></div>
                @endif
            </a>

            <!-- Transações -->
            <a href="{{ route('admin.finance.transactions.index') }}"
                class="sidebar-subitem {{ $currentSubsection === 'transacoes' ? 'active' : '' }}">
                <i class="sidebar-subitem-icon fas fa-exchange-alt"></i>
                <span class="sidebar-subitem-text">Transações</span>
                @if ($currentSubsection === 'transacoes')
                    <div class="sidebar-subitem-indicator"></div>
                @endif
            </a>

            <!-- Campanhas -->
            <a href="{{ route('admin.finance.campaigns.index') }}"
                class="sidebar-subitem {{ $currentSubsection === 'campanhas' ? 'active' : '' }}">
                <i class="sidebar-subitem-icon fas fa-bullhorn"></i>
                <span class="sidebar-subitem-text">Campanhas</span>
                @if ($currentSubsection === 'campanhas')
                    <div class="sidebar-subitem-indicator"></div>
                @endif
            </a>

            <!-- Relatórios -->
            <a href="{{ route('admin.finance.reports.index') }}"
                class="sidebar-subitem {{ $currentSubsection === 'relatorios' ? 'active' : '' }}">
                <i class="sidebar-subitem-icon fas fa-file-alt"></i>
                <span class="sidebar-subitem-text">Relatórios</span>
                @if ($currentSubsection === 'relatorios')
                    <div class="sidebar-subitem-indicator"></div>
                @endif
            </a>

            <!-- Documentos de Baixa -->
            <a href="{{ route('admin.finance.documentos.index') }}"
                class="sidebar-subitem {{ $currentSubsection === 'documentos-baixa' ? 'active' : '' }}">
                <i class="sidebar-subitem-icon fas fa-file-download"></i>
                <span class="sidebar-subitem-text">Documentos de Baixa</span>
                @if ($currentSubsection === 'documentos-baixa')
                    <div class="sidebar-subitem-indicator"></div>
                @endif
            </a>

            <!-- Declaração Anual -->
            <a href="{{ route('admin.finance.documentos-declaracao-anual.index') }}"
                class="sidebar-subitem {{ $currentSubsection === 'declaracao-anual' ? 'active' : '' }}">
                <i class="sidebar-subitem-icon fas fa-calendar-alt"></i>
                <span class="sidebar-subitem-text">Declaração Anual</span>
                @if ($currentSubsection === 'declaracao-anual')
                    <div class="sidebar-subitem-indicator"></div>
                @endif
            </a>
        </div>
    </div>
@endif
