<?php
    use App\Helpers\PermissionHelper;
?>

<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'currentSection' => '',
    'currentSubsection' => '',
    'sidebarCollapsed' => false,
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
    'sidebarCollapsed' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if(PermissionHelper::canAccessCouncil()): ?>
    <div class="sidebar-section">
        <!-- Seção Principal -->
        <button @click="toggleSection('conselho')"
            class="sidebar-item w-full <?php echo e($currentSection === 'conselho' ? 'active' : ''); ?>"
            :class="sidebarCollapsed ? 'collapsed' : ''" :aria-expanded="expandedSections.conselho"
            aria-label="Conselho da Igreja">

            <i class="sidebar-icon fas fa-users-cog"></i>

            <span class="sidebar-text flex-1 text-left" :class="sidebarCollapsed ? 'opacity-0 w-0' : 'opacity-100'"
                x-transition:enter="transition-all duration-300 delay-100" x-transition:enter-start="opacity-0 w-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition-all duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 w-0">
                Conselho da Igreja
            </span>

            <?php if($currentSection === 'conselho'): ?>
                <div class="sidebar-indicator" :class="sidebarCollapsed ? 'opacity-0 w-0' : 'opacity-100'"></div>
            <?php endif; ?>

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
            <a href="<?php echo e(route('admin.council.dashboard')); ?>"
                class="sidebar-subitem <?php echo e($currentSubsection === '' && $currentSection === 'conselho' ? 'active' : ''); ?>">
                <i class="sidebar-subitem-icon fas fa-chart-line"></i>
                <span class="sidebar-subitem-text">Dashboard</span>
                <?php if($currentSubsection === '' && $currentSection === 'conselho'): ?>
                    <div class="sidebar-subitem-indicator"></div>
                <?php endif; ?>
            </a>

            <!-- Reuniões -->
            <a href="<?php echo e(route('admin.council.index')); ?>"
                class="sidebar-subitem <?php echo e($currentSubsection === 'reunioes' ? 'active' : ''); ?>">
                <i class="sidebar-subitem-icon fas fa-handshake"></i>
                <span class="sidebar-subitem-text">Reuniões</span>
                <?php if($currentSubsection === 'reunioes'): ?>
                    <div class="sidebar-subitem-indicator"></div>
                <?php endif; ?>
            </a>

            <!-- Votações -->
            <a href="<?php echo e(route('admin.council.voting.history')); ?>"
                class="sidebar-subitem <?php echo e($currentSubsection === 'votacoes' ? 'active' : ''); ?>">
                <i class="sidebar-subitem-icon fas fa-vote-yea"></i>
                <span class="sidebar-subitem-text">Votações</span>
                <?php if($currentSubsection === 'votacoes'): ?>
                    <div class="sidebar-subitem-indicator"></div>
                <?php endif; ?>
            </a>

            <!-- Templates -->
            <a href="<?php echo e(route('admin.council.agenda.template.index')); ?>"
                class="sidebar-subitem <?php echo e($currentSubsection === 'templates' ? 'active' : ''); ?>">
                <i class="sidebar-subitem-icon fas fa-file-contract"></i>
                <span class="sidebar-subitem-text">Templates</span>
                <?php if($currentSubsection === 'templates'): ?>
                    <div class="sidebar-subitem-indicator"></div>
                <?php endif; ?>
            </a>

            <!-- Configurações -->
            <a href="<?php echo e(route('admin.council.settings')); ?>"
                class="sidebar-subitem <?php echo e($currentSubsection === 'configuracoes' ? 'active' : ''); ?>">
                <i class="sidebar-subitem-icon fas fa-cog"></i>
                <span class="sidebar-subitem-text">Configurações</span>
                <?php if($currentSubsection === 'configuracoes'): ?>
                    <div class="sidebar-subitem-indicator"></div>
                <?php endif; ?>
            </a>

            <!-- Nova Reunião -->
            <a href="<?php echo e(route('admin.council.create')); ?>"
                class="sidebar-subitem <?php echo e($currentSubsection === 'nova-reuniao' ? 'active' : ''); ?>">
                <i class="sidebar-subitem-icon fas fa-plus-circle"></i>
                <span class="sidebar-subitem-text">Nova Reunião</span>
                <?php if($currentSubsection === 'nova-reuniao'): ?>
                    <div class="sidebar-subitem-indicator"></div>
                <?php endif; ?>
            </a>

            <!-- Área de Membros -->
            <a href="<?php echo e(route('member.dashboard')); ?>"
                class="sidebar-subitem <?php echo e($currentSubsection === 'area-membros' ? 'active' : ''); ?>">
                <i class="sidebar-subitem-icon fas fa-user-friends"></i>
                <span class="sidebar-subitem-text">Área de Membros</span>
                <?php if($currentSubsection === 'area-membros'): ?>
                    <div class="sidebar-subitem-indicator"></div>
                <?php endif; ?>
            </a>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH C:\wamp64\www\CBAV2025\resources\views/components/admin/sidebar/council.blade.php ENDPATH**/ ?>