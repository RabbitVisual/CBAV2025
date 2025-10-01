
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

<?php if(Auth::check() && Auth::user()->hasPermissionTo('ebd.quiz.access')): ?>
<div class="sidebar-section">
    <!-- Seção Principal -->
    <button @click="toggleSection('quiz')" 
            class="sidebar-item w-full <?php echo e($currentSection === 'quiz' ? 'active' : ''); ?>"
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
        
        <?php if($currentSection === 'quiz'): ?>
            <div class="sidebar-indicator" 
                 :class="sidebarCollapsed ? 'opacity-0 w-0' : 'opacity-100'"></div>
        <?php endif; ?>
        
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
        <a href="<?php echo e(route('admin.ebd.quiz-biblico.index')); ?>" 
           class="sidebar-subitem <?php echo e($currentSubsection === 'quiz' ? 'active' : ''); ?>">
            <i class="sidebar-subitem-icon fas fa-cogs"></i>
            <span class="sidebar-subitem-text">Gerenciar</span>
            <?php if($currentSubsection === 'quiz'): ?>
                <div class="sidebar-subitem-indicator"></div>
            <?php endif; ?>
        </a>
        
        <!-- Estatísticas -->
        <a href="<?php echo e(route('admin.ebd.quiz-biblico.estatisticas')); ?>" 
           class="sidebar-subitem <?php echo e($currentSubsection === 'quiz-stats' ? 'active' : ''); ?>">
            <i class="sidebar-subitem-icon fas fa-chart-line"></i>
            <span class="sidebar-subitem-text">Estatísticas</span>
            <?php if($currentSubsection === 'quiz-stats'): ?>
                <div class="sidebar-subitem-indicator"></div>
            <?php endif; ?>
        </a>
        
        <!-- Configurações -->
        <a href="<?php echo e(route('admin.ebd.quiz-biblico.configuracoes')); ?>" 
           class="sidebar-subitem <?php echo e($currentSubsection === 'quiz-settings' ? 'active' : ''); ?>">
            <i class="sidebar-subitem-icon fas fa-sliders-h"></i>
            <span class="sidebar-subitem-text">Configurações</span>
            <?php if($currentSubsection === 'quiz-settings'): ?>
                <div class="sidebar-subitem-indicator"></div>
            <?php endif; ?>
        </a>
    </div>
</div>
<?php endif; ?><?php /**PATH C:\wamp64\www\CBAV2025\resources\views/components/admin/sidebar/quiz.blade.php ENDPATH**/ ?>