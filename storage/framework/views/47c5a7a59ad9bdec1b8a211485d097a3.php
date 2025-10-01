
<?php
use App\Helpers\PermissionHelper;
?>

<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'currentSection' => '',
    'currentSubsection' => '',
    'sidebarCollapsed' => false
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'currentSection' => '',
    'currentSubsection' => '',
    'sidebarCollapsed' => false
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if(Auth::check() && Auth::user()->hasPermissionTo('ebd.access')): ?>
<div class="sidebar-section">
    <!-- Seção Principal -->
    <button @click="toggleSection('ebd')" 
            class="sidebar-item w-full <?php echo e($currentSection === 'ebd' ? 'active' : ''); ?>"
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
        
        <?php if($currentSection === 'ebd'): ?>
            <div class="sidebar-indicator" 
                 :class="sidebarCollapsed ? 'opacity-0 w-0' : 'opacity-100'"></div>
        <?php endif; ?>
        
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
        <a href="<?php echo e(route('admin.ebd.turmas.index')); ?>" 
           class="sidebar-subitem <?php echo e($currentSubsection === 'turmas' ? 'active' : ''); ?>">
            <i class="sidebar-subitem-icon fas fa-graduation-cap"></i>
            <span class="sidebar-subitem-text">Turmas</span>
            <?php if($currentSubsection === 'turmas'): ?>
                <div class="sidebar-subitem-indicator"></div>
            <?php endif; ?>
        </a>
        
        <!-- Professores -->
        <a href="<?php echo e(route('admin.ebd.professores.index')); ?>" 
           class="sidebar-subitem <?php echo e($currentSubsection === 'professores' ? 'active' : ''); ?>">
            <i class="sidebar-subitem-icon fas fa-chalkboard-teacher"></i>
            <span class="sidebar-subitem-text">Professores</span>
            <?php if($currentSubsection === 'professores'): ?>
                <div class="sidebar-subitem-indicator"></div>
            <?php endif; ?>
        </a>
        
        <!-- Alunos -->
        <a href="<?php echo e(route('admin.ebd.alunos.index')); ?>" 
           class="sidebar-subitem <?php echo e($currentSubsection === 'alunos' ? 'active' : ''); ?>">
            <i class="sidebar-subitem-icon fas fa-user-graduate"></i>
            <span class="sidebar-subitem-text">Alunos</span>
            <?php if($currentSubsection === 'alunos'): ?>
                <div class="sidebar-subitem-indicator"></div>
            <?php endif; ?>
        </a>
        
        <!-- Lições -->
        <a href="<?php echo e(route('admin.ebd.licoes.index')); ?>" 
           class="sidebar-subitem <?php echo e($currentSubsection === 'licoes' ? 'active' : ''); ?>">
            <i class="sidebar-subitem-icon fas fa-book-open"></i>
            <span class="sidebar-subitem-text">Lições</span>
            <?php if($currentSubsection === 'licoes'): ?>
                <div class="sidebar-subitem-indicator"></div>
            <?php endif; ?>
        </a>
        
        <!-- Aulas -->
        <a href="<?php echo e(route('admin.ebd.aulas.index')); ?>" 
           class="sidebar-subitem <?php echo e($currentSubsection === 'aulas' ? 'active' : ''); ?>">
            <i class="sidebar-subitem-icon fas fa-calendar-alt"></i>
            <span class="sidebar-subitem-text">Aulas</span>
            <?php if($currentSubsection === 'aulas'): ?>
                <div class="sidebar-subitem-indicator"></div>
            <?php endif; ?>
        </a>
        
        <!-- Avaliações -->
        <a href="<?php echo e(route('admin.ebd.avaliacoes.index')); ?>" 
           class="sidebar-subitem <?php echo e($currentSubsection === 'avaliacoes' ? 'active' : ''); ?>">
            <i class="sidebar-subitem-icon fas fa-clipboard-check"></i>
            <span class="sidebar-subitem-text">Avaliações</span>
            <?php if($currentSubsection === 'avaliacoes'): ?>
                <div class="sidebar-subitem-indicator"></div>
            <?php endif; ?>
        </a>
        
        <!-- Questões -->
        <a href="<?php echo e(route('admin.ebd.questoes.index')); ?>" 
           class="sidebar-subitem <?php echo e($currentSubsection === 'questoes' ? 'active' : ''); ?>">
            <i class="sidebar-subitem-icon fas fa-question-circle"></i>
            <span class="sidebar-subitem-text">Questões</span>
            <?php if($currentSubsection === 'questoes'): ?>
                <div class="sidebar-subitem-indicator"></div>
            <?php endif; ?>
        </a>
        
        <!-- Certificados -->
        <a href="<?php echo e(route('admin.ebd.certificados.index')); ?>" 
           class="sidebar-subitem <?php echo e($currentSubsection === 'certificados' ? 'active' : ''); ?>">
            <i class="sidebar-subitem-icon fas fa-certificate"></i>
            <span class="sidebar-subitem-text">Certificados</span>
            <?php if($currentSubsection === 'certificados'): ?>
                <div class="sidebar-subitem-indicator"></div>
            <?php endif; ?>
        </a>
        
        <!-- Relatórios -->
        <a href="<?php echo e(route('admin.ebd.relatorios.index')); ?>" 
           class="sidebar-subitem <?php echo e($currentSubsection === 'relatorios' ? 'active' : ''); ?>">
            <i class="sidebar-subitem-icon fas fa-chart-bar"></i>
            <span class="sidebar-subitem-text">Relatórios</span>
            <?php if($currentSubsection === 'relatorios'): ?>
                <div class="sidebar-subitem-indicator"></div>
            <?php endif; ?>
        </a>
        
        <!-- Grupos de Estudo -->
        <a href="<?php echo e(route('admin.ebd.grupos-estudo.index')); ?>" 
           class="sidebar-subitem <?php echo e($currentSubsection === 'grupos-estudo' ? 'active' : ''); ?>">
            <i class="sidebar-subitem-icon fas fa-users"></i>
            <span class="sidebar-subitem-text">Grupos de Estudo</span>
            <?php if($currentSubsection === 'grupos-estudo'): ?>
                <div class="sidebar-subitem-indicator"></div>
            <?php endif; ?>
        </a>
        
        <!-- Avaliações em Grupo -->
        <a href="<?php echo e(route('admin.ebd.avaliacoes-grupo.index')); ?>" 
           class="sidebar-subitem <?php echo e($currentSubsection === 'avaliacoes-grupo' ? 'active' : ''); ?>">
            <i class="sidebar-subitem-icon fas fa-clipboard-list"></i>
            <span class="sidebar-subitem-text">Avaliações em Grupo</span>
            <?php if($currentSubsection === 'avaliacoes-grupo'): ?>
                <div class="sidebar-subitem-indicator"></div>
            <?php endif; ?>
        </a>

    </div>
</div>
<?php endif; ?><?php /**PATH C:\wamp64\www\CBAV2025\resources\views/components/admin/sidebar/ebd.blade.php ENDPATH**/ ?>