
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

<?php if(PermissionHelper::canAccessPeople()): ?>
<div class="sidebar-section">
    <!-- Seção Principal -->
    <button @click="toggleSection('people')" 
            class="sidebar-item w-full <?php echo e($currentSection === 'people' ? 'active' : ''); ?>"
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
        
        <?php if($currentSection === 'people'): ?>
            <div class="sidebar-indicator" 
                 :class="sidebarCollapsed ? 'opacity-0 w-0' : 'opacity-100'"></div>
        <?php endif; ?>
        
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
        <a href="<?php echo e(route('admin.people.index')); ?>" 
           class="sidebar-subitem <?php echo e($currentSubsection === '' && $currentSection === 'people' ? 'active' : ''); ?>">
            <i class="sidebar-subitem-icon fas fa-chart-line"></i>
            <span class="sidebar-subitem-text">Dashboard</span>
            <?php if($currentSubsection === '' && $currentSection === 'people'): ?>
                <div class="sidebar-subitem-indicator"></div>
            <?php endif; ?>
        </a>
        
        <!-- Membros -->
        <?php if(PermissionHelper::canAccessMembers()): ?>
        <a href="<?php echo e(route('admin.people.members.index')); ?>" 
           class="sidebar-subitem <?php echo e($currentSubsection === 'members' ? 'active' : ''); ?>">
            <i class="sidebar-subitem-icon fas fa-user-friends"></i>
            <span class="sidebar-subitem-text">Membros</span>
            <?php if($currentSubsection === 'members'): ?>
                <div class="sidebar-subitem-indicator"></div>
            <?php endif; ?>
        </a>
        <?php endif; ?>
        
        <!-- Usuários -->
        <?php if(PermissionHelper::canAccessUsers()): ?>
        <a href="<?php echo e(route('admin.people.users.index')); ?>" 
           class="sidebar-subitem <?php echo e($currentSubsection === 'users' ? 'active' : ''); ?>">
            <i class="sidebar-subitem-icon fas fa-user-cog"></i>
            <span class="sidebar-subitem-text">Usuários</span>
            <?php if($currentSubsection === 'users'): ?>
                <div class="sidebar-subitem-indicator"></div>
            <?php endif; ?>
        </a>
        <?php endif; ?>
        
        <!-- Ministérios -->
        <?php if(PermissionHelper::canAccessMinistries()): ?>
        <a href="<?php echo e(route('admin.people.ministries.index')); ?>" 
           class="sidebar-subitem <?php echo e($currentSubsection === 'ministries' ? 'active' : ''); ?>">
            <i class="sidebar-subitem-icon fas fa-church"></i>
            <span class="sidebar-subitem-text">Ministérios</span>
            <?php if($currentSubsection === 'ministries'): ?>
                <div class="sidebar-subitem-indicator"></div>
            <?php endif; ?>
        </a>
        <?php endif; ?>
        
        <!-- Departamentos -->
        <?php if(PermissionHelper::canAccessMinistries()): ?>
        <a href="<?php echo e(route('admin.people.departments.index')); ?>" 
           class="sidebar-subitem <?php echo e($currentSubsection === 'departments' ? 'active' : ''); ?>">
            <i class="sidebar-subitem-icon fas fa-sitemap"></i>
            <span class="sidebar-subitem-text">Departamentos</span>
            <?php if($currentSubsection === 'departments'): ?>
                <div class="sidebar-subitem-indicator"></div>
            <?php endif; ?>
        </a>
        <?php endif; ?>
        
        <!-- Cargos -->
        <?php if(PermissionHelper::canAccessMembers()): ?>
        <a href="<?php echo e(route('admin.people.cargos.index')); ?>" 
           class="sidebar-subitem <?php echo e($currentSubsection === 'cargos' ? 'active' : ''); ?>">
            <i class="sidebar-subitem-icon fas fa-briefcase"></i>
            <span class="sidebar-subitem-text">Cargos</span>
            <?php if($currentSubsection === 'cargos'): ?>
                <div class="sidebar-subitem-indicator"></div>
            <?php endif; ?>
        </a>
        <?php endif; ?>
        
        <!-- CEPs -->
        <?php if(PermissionHelper::canAccessMembers()): ?>
        <a href="<?php echo e(route('admin.people.ceps.index')); ?>" 
           class="sidebar-subitem <?php echo e($currentSubsection === 'ceps' ? 'active' : ''); ?>">
            <i class="sidebar-subitem-icon fas fa-map-marker-alt"></i>
            <span class="sidebar-subitem-text">CEPs</span>
            <?php if($currentSubsection === 'ceps'): ?>
                <div class="sidebar-subitem-indicator"></div>
            <?php endif; ?>
        </a>
        <?php endif; ?>
        
        <!-- Aniversariantes -->
        <?php if(PermissionHelper::canAccessMembers()): ?>
        <a href="<?php echo e(route('admin.people.birthdays.index')); ?>" 
           class="sidebar-subitem <?php echo e($currentSubsection === 'birthdays' ? 'active' : ''); ?>">
            <i class="sidebar-subitem-icon fas fa-birthday-cake"></i>
            <span class="sidebar-subitem-text">Aniversariantes</span>
            <?php if($currentSubsection === 'birthdays'): ?>
                <div class="sidebar-subitem-indicator"></div>
            <?php endif; ?>
        </a>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?><?php /**PATH C:\wamp64\www\CBAV2025\resources\views/components/admin/sidebar/people.blade.php ENDPATH**/ ?>