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

<?php if(PermissionHelper::canAccessSystem()): ?>
<div class="sidebar-section">
    <!-- Seção Principal -->
    <button @click="toggleSection('sistema')" 
            class="sidebar-item w-full <?php echo e($currentSection === 'sistema' ? 'active' : ''); ?>"
            :class="sidebarCollapsed ? 'collapsed' : ''"
            :aria-expanded="expandedSections.sistema"
            aria-label="Gestão do Sistema">
        
        <i class="sidebar-icon fas fa-cogs"></i>
        
        <span class="sidebar-text flex-1 text-left" 
              :class="sidebarCollapsed ? 'opacity-0 w-0' : 'opacity-100'"
              x-transition:enter="transition-all duration-300 delay-100"
              x-transition:enter-start="opacity-0 w-0"
              x-transition:enter-end="opacity-100"
              x-transition:leave="transition-all duration-200"
              x-transition:leave-start="opacity-100"
              x-transition:leave-end="opacity-0 w-0">
            Gestão do Sistema
        </span>
        
        <?php if($currentSection === 'sistema'): ?>
            <div class="sidebar-indicator" 
                 :class="sidebarCollapsed ? 'opacity-0 w-0' : 'opacity-100'"></div>
        <?php endif; ?>
        
        <i class="fas fa-chevron-down text-xs transition-transform duration-300 ml-2"
           :class="{
               'rotate-180': expandedSections.sistema,
               'opacity-0 w-0': sidebarCollapsed,
               'opacity-100': !sidebarCollapsed
           }"></i>
        
        <!-- Tooltip para sidebar colapsado -->
        <div class="sidebar-tooltip" 
             :class="{
                 'opacity-100 visible': sidebarCollapsed,
                 'opacity-0 invisible': !sidebarCollapsed
             }">
            Administração do Sistema
        </div>
    </button>
    
    <!-- Subitens -->
    <div x-show="expandedSections.sistema && !sidebarCollapsed" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 max-h-0"
         x-transition:enter-end="opacity-100 max-h-96"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 max-h-96"
         x-transition:leave-end="opacity-0 max-h-0"
         class="sidebar-items ml-4 mt-1 space-y-1 overflow-hidden">
        
        <!-- Dashboard -->
        <a href="<?php echo e(route('admin.system.index')); ?>" 
           class="sidebar-subitem <?php echo e($currentSubsection === '' && $currentSection === 'system' ? 'active' : ''); ?>">
            <i class="sidebar-subitem-icon fas fa-chart-line"></i>
            <span class="sidebar-subitem-text">Dashboard</span>
            <?php if($currentSubsection === '' && $currentSection === 'system'): ?>
                <div class="sidebar-subitem-indicator"></div>
            <?php endif; ?>
        </a>
        
        <!-- Configurações -->
        <a href="<?php echo e(route('admin.system.settings.index')); ?>" 
           class="sidebar-subitem <?php echo e($currentSubsection === 'settings' ? 'active' : ''); ?>">
            <i class="sidebar-subitem-icon fas fa-cog"></i>
            <span class="sidebar-subitem-text">Configurações</span>
            <?php if($currentSubsection === 'settings'): ?>
                <div class="sidebar-subitem-indicator"></div>
            <?php endif; ?>
        </a>
        
        <!-- Config. Home -->
        <a href="<?php echo e(route('admin.system.home-config.index')); ?>" 
           class="sidebar-subitem <?php echo e($currentSubsection === 'home-config' ? 'active' : ''); ?>">
            <i class="sidebar-subitem-icon fas fa-home"></i>
            <span class="sidebar-subitem-text">Config. Home</span>
            <?php if($currentSubsection === 'home-config'): ?>
                <div class="sidebar-subitem-indicator"></div>
            <?php endif; ?>
        </a>
        
        <!-- Chat da Igreja -->
        <a href="<?php echo e(route('admin.chat.index')); ?>" 
           class="sidebar-subitem <?php echo e($currentSubsection === 'chat' ? 'active' : ''); ?>">
            <i class="sidebar-subitem-icon fas fa-comments"></i>
            <span class="sidebar-subitem-text">Chat da Igreja</span>
            <?php if($currentSubsection === 'chat'): ?>
                <div class="sidebar-subitem-indicator"></div>
            <?php endif; ?>
        </a>
        
        <!-- Notificações -->
        <a href="<?php echo e(route('admin.system.notifications.index')); ?>" 
           class="sidebar-subitem <?php echo e($currentSubsection === 'notifications' ? 'active' : ''); ?>">
            <i class="sidebar-subitem-icon fas fa-bell"></i>
            <span class="sidebar-subitem-text">Notificações</span>
            <?php if($currentSubsection === 'notifications'): ?>
                <div class="sidebar-subitem-indicator"></div>
            <?php endif; ?>
        </a>
        
        <!-- Logs -->
        <a href="<?php echo e(route('admin.system.logs.index')); ?>" 
           class="sidebar-subitem <?php echo e($currentSubsection === 'logs' ? 'active' : ''); ?>">
            <i class="sidebar-subitem-icon fas fa-file-alt"></i>
            <span class="sidebar-subitem-text">Logs</span>
            <?php if($currentSubsection === 'logs'): ?>
                <div class="sidebar-subitem-indicator"></div>
            <?php endif; ?>
        </a>
        
        <!-- Permissões -->
        <a href="<?php echo e(route('admin.permissions.index')); ?>" 
           class="sidebar-subitem <?php echo e($currentSubsection === 'permissions' ? 'active' : ''); ?>">
            <i class="sidebar-subitem-icon fas fa-shield-alt"></i>
            <span class="sidebar-subitem-text">Permissões</span>
            <?php if($currentSubsection === 'permissions'): ?>
                <div class="sidebar-subitem-indicator"></div>
            <?php endif; ?>
        </a>
        
        <!-- Devocionais -->
        <a href="<?php echo e(route('admin.devotionals.index')); ?>" 
           class="sidebar-subitem <?php echo e($currentSubsection === 'devotionals' ? 'active' : ''); ?>">
            <i class="sidebar-subitem-icon fas fa-book-open"></i>
            <span class="sidebar-subitem-text">Devocionais</span>
            <?php if($currentSubsection === 'devotionals'): ?>
                <div class="sidebar-subitem-indicator"></div>
            <?php endif; ?>
        </a>
    </div>
</div>
<?php endif; ?><?php /**PATH C:\wamp64\www\CBAV2025\resources\views/components/admin/sidebar/system.blade.php ENDPATH**/ ?>