{{-- Componente Escola Bíblica do Sidebar Admin --}}
@php
use App\Helpers\PermissionHelper;
@endphp

@props([
    'currentSection' => '',
    'currentSubsection' => '',
    'sidebarCollapsed' => false
])

@if(Auth::check() && Auth::user()->hasPermissionTo('ebd.access'))
<div class="sidebar-section">
    <!-- Seção Principal -->
    <button @click="toggleSection('ebd')" 
            class="sidebar-item w-full {{ $currentSection === 'ebd' ? 'active' : '' }}"
            :class="sidebarCollapsed ? 'collapsed' : ''"
            :aria-expanded="expandedSections.ebd"
            aria-label="Escola Bíblica">
        
        <i class="sidebar-icon fas fa-graduation-cap"></i>
        
        <span class="sidebar-text flex-1 text-left" 
              :class="sidebarCollapsed ? 'opacity-0 w-0' : 'opacity-100'"
              x-transition:enter="transition-all duration-300 delay-100"
              x-transition:enter-start="opacity-0 w-0"
              x-transition:enter-end="opacity-100"
              x-transition:leave="transition-all duration-200"
              x-transition:leave-start="opacity-100"
              x-transition:leave-end="opacity-0 w-0">
            Escola Bíblica
        </span>
        
        @if($currentSection === 'ebd')
            <div class="sidebar-indicator" 
                 :class="sidebarCollapsed ? 'opacity-0 w-0' : 'opacity-100'"></div>
        @endif
        
        <i class="fas fa-chevron-down text-xs transition-transform duration-300 ml-2"
           :class="{
               'rotate-180': expandedSections.ebd,
               'opacity-0 w-0': sidebarCollapsed,
               'opacity-100': !sidebarCollapsed
           }"></i>
        
        <!-- Tooltip para sidebar colapsado -->
        <div class="sidebar-tooltip" 
             :class="{
                 'opacity-100 visible': sidebarCollapsed,
                 'opacity-0 invisible': !sidebarCollapsed
             }">
            Escola Bíblica Dominical
        </div>
    </button>
    
    <!-- Subitens -->
    <div x-show="expandedSections.ebd && !sidebarCollapsed" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 max-h-0"
         x-transition:enter-end="opacity-100 max-h-96"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 max-h-96"
         x-transition:leave-end="opacity-0 max-h-0"
         class="sidebar-items ml-4 mt-1 space-y-1 overflow-hidden">
        
        <!-- Turmas -->
        <a href="{{ route('admin.ebd.turmas.index') }}" 
           class="sidebar-subitem {{ $currentSubsection === 'turmas' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-graduation-cap"></i>
            <span class="sidebar-subitem-text">Turmas</span>
            @if($currentSubsection === 'turmas')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
        
        <!-- Professores -->
        <a href="{{ route('admin.ebd.professores.index') }}" 
           class="sidebar-subitem {{ $currentSubsection === 'professores' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-chalkboard-teacher"></i>
            <span class="sidebar-subitem-text">Professores</span>
            @if($currentSubsection === 'professores')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
        
        <!-- Alunos -->
        <a href="{{ route('admin.ebd.alunos.index') }}" 
           class="sidebar-subitem {{ $currentSubsection === 'alunos' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-user-graduate"></i>
            <span class="sidebar-subitem-text">Alunos</span>
            @if($currentSubsection === 'alunos')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
        
        <!-- Lições -->
        <a href="{{ route('admin.ebd.licoes.index') }}" 
           class="sidebar-subitem {{ $currentSubsection === 'licoes' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-book-open"></i>
            <span class="sidebar-subitem-text">Lições</span>
            @if($currentSubsection === 'licoes')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
        
        <!-- Aulas -->
        <a href="{{ route('admin.ebd.aulas.index') }}" 
           class="sidebar-subitem {{ $currentSubsection === 'aulas' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-calendar-alt"></i>
            <span class="sidebar-subitem-text">Aulas</span>
            @if($currentSubsection === 'aulas')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
        
        <!-- Avaliações -->
        <a href="{{ route('admin.ebd.avaliacoes.index') }}" 
           class="sidebar-subitem {{ $currentSubsection === 'avaliacoes' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-clipboard-check"></i>
            <span class="sidebar-subitem-text">Avaliações</span>
            @if($currentSubsection === 'avaliacoes')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
        
        <!-- Questões -->
        <a href="{{ route('admin.ebd.questoes.index') }}" 
           class="sidebar-subitem {{ $currentSubsection === 'questoes' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-question-circle"></i>
            <span class="sidebar-subitem-text">Questões</span>
            @if($currentSubsection === 'questoes')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
        
        <!-- Certificados -->
        <a href="{{ route('admin.ebd.certificados.index') }}" 
           class="sidebar-subitem {{ $currentSubsection === 'certificados' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-certificate"></i>
            <span class="sidebar-subitem-text">Certificados</span>
            @if($currentSubsection === 'certificados')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
        
        <!-- Relatórios -->
        <a href="{{ route('admin.ebd.relatorios.index') }}" 
           class="sidebar-subitem {{ $currentSubsection === 'relatorios' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-chart-bar"></i>
            <span class="sidebar-subitem-text">Relatórios</span>
            @if($currentSubsection === 'relatorios')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
        
        <!-- Grupos de Estudo -->
        <a href="{{ route('admin.ebd.grupos-estudo.index') }}" 
           class="sidebar-subitem {{ $currentSubsection === 'grupos-estudo' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-users"></i>
            <span class="sidebar-subitem-text">Grupos de Estudo</span>
            @if($currentSubsection === 'grupos-estudo')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
        
        <!-- Avaliações em Grupo -->
        <a href="{{ route('admin.ebd.avaliacoes-grupo.index') }}" 
           class="sidebar-subitem {{ $currentSubsection === 'avaliacoes-grupo' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-clipboard-list"></i>
            <span class="sidebar-subitem-text">Avaliações em Grupo</span>
            @if($currentSubsection === 'avaliacoes-grupo')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>

    </div>
</div>
@endif