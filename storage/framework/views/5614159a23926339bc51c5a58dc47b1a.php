<?php
use App\Helpers\PermissionHelper;
?>

<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'currentSection' => '',
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
    'sidebarCollapsed' => false
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if(PermissionHelper::canAccessAdminDashboard()): ?>
<div class="sidebar-section">
    <a href="<?php echo e(route('admin.dashboard')); ?>" 
       class="sidebar-item <?php echo e($currentSection === 'dashboard' ? 'active' : ''); ?>"
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
        
        <?php if($currentSection === 'dashboard'): ?>
            <div class="sidebar-indicator" 
                 :class="sidebarCollapsed ? 'opacity-0 w-0' : 'opacity-100'"></div>
        <?php endif; ?>
        
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
<?php endif; ?><?php /**PATH C:\wamp64\www\CBAV2025\resources\views/components/admin/sidebar/dashboard.blade.php ENDPATH**/ ?>