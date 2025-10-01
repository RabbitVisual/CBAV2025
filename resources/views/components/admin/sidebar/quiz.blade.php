{{-- Componente Quiz Bíblico do Sidebar Admin --}}
@php
use App\Helpers\PermissionHelper;
@endphp

@props([
    'currentSection' => '',
    'currentSubsection' => '',
    'sidebarCollapsed' => false
])

@if(Auth::check() && Auth::user()->hasPermissionTo('ebd.quiz.access'))
<div class="sidebar-section">
    <!-- Seção Principal -->
    <button @click="toggleSection('quiz')" 
            class="sidebar-item w-full {{ $currentSection === 'quiz' ? 'active' : '' }}"
            :class="sidebarCollapsed ? 'collapsed' : ''"
            :aria-expanded="expandedSections.quiz"
            aria-label="Quiz Bíblico">
        
        <i class="sidebar-icon fas fa-brain"></i>
        
        <span class="sidebar-text flex-1 text-left" 
              :class="sidebarCollapsed ? 'opacity-0 w-0' : 'opacity-100'"
              x-transition:enter="transition-all duration-300 delay-100"
              x-transition:enter-start="opacity-0 w-0"
              x-transition:enter-end="opacity-100"
              x-transition:leave="transition-all duration-200"
              x-transition:leave-start="opacity-100"
              x-transition:leave-end="opacity-0 w-0">
            Quiz Bíblico
        </span>
        
        @if($currentSection === 'quiz')
            <div class="sidebar-indicator" 
                 :class="sidebarCollapsed ? 'opacity-0 w-0' : 'opacity-100'"></div>
        @endif
        
        <i class="fas fa-chevron-down text-xs transition-transform duration-300 ml-2"
           :class="{
               'rotate-180': expandedSections.quiz,
               'opacity-0 w-0': sidebarCollapsed,
               'opacity-100': !sidebarCollapsed
           }"></i>
        
        <!-- Tooltip para sidebar colapsado -->
        <div class="sidebar-tooltip" 
             :class="{
                 'opacity-100 visible': sidebarCollapsed,
                 'opacity-0 invisible': !sidebarCollapsed
             }">
            Quiz Bíblico e Jogos
        </div>
    </button>
    
    <!-- Subitens -->
    <div x-show="expandedSections.quiz && !sidebarCollapsed" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 max-h-0"
         x-transition:enter-end="opacity-100 max-h-96"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 max-h-96"
         x-transition:leave-end="opacity-0 max-h-0"
         class="sidebar-items ml-4 mt-1 space-y-1 overflow-hidden">
        
        <!-- Gerenciar -->
        <a href="{{ route('admin.ebd.quiz-biblico.index') }}" 
           class="sidebar-subitem {{ $currentSubsection === 'quiz' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-cogs"></i>
            <span class="sidebar-subitem-text">Gerenciar</span>
            @if($currentSubsection === 'quiz')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
        
        <!-- Estatísticas -->
        <a href="{{ route('admin.ebd.quiz-biblico.estatisticas') }}" 
           class="sidebar-subitem {{ $currentSubsection === 'quiz-stats' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-chart-line"></i>
            <span class="sidebar-subitem-text">Estatísticas</span>
            @if($currentSubsection === 'quiz-stats')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
        
        <!-- Configurações -->
        <a href="{{ route('admin.ebd.quiz-biblico.configuracoes') }}" 
           class="sidebar-subitem {{ $currentSubsection === 'quiz-settings' ? 'active' : '' }}">
            <i class="sidebar-subitem-icon fas fa-sliders-h"></i>
            <span class="sidebar-subitem-text">Configurações</span>
            @if($currentSubsection === 'quiz-settings')
                <div class="sidebar-subitem-indicator"></div>
            @endif
        </a>
    </div>
</div>
@endif