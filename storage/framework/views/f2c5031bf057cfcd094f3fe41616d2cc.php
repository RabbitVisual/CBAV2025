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

<?php if(PermissionHelper::canAccessFinance()): ?>
    <div class="sidebar-section">
        <!-- Seção Principal -->
        <button @click="toggleSection('financeiro')"
            class="sidebar-item w-full <?php echo e($currentSection === 'financeiro' ? 'active' : ''); ?>"
            :class="sidebarCollapsed ? 'collapsed' : ''" :aria-expanded="expandedSections.financeiro"
            aria-label="Gestão Financeira">

            <i class="sidebar-icon fas fa-dollar-sign"></i>

            <span class="sidebar-text flex-1 text-left" :class="sidebarCollapsed ? 'opacity-0 w-0' : 'opacity-100'"
                x-transition:enter="transition-all duration-300 delay-100" x-transition:enter-start="opacity-0 w-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition-all duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 w-0">
                Gestão Financeira
            </span>

            <?php if($currentSection === 'financeiro'): ?>
                <div class="sidebar-indicator" :class="sidebarCollapsed ? 'opacity-0 w-0' : 'opacity-100'"></div>
            <?php endif; ?>

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
            <a href="<?php echo e(route('admin.finance.index')); ?>"
                class="sidebar-subitem <?php echo e($currentSubsection === '' && $currentSection === 'financeiro' ? 'active' : ''); ?>">
                <i class="sidebar-subitem-icon fas fa-chart-pie"></i>
                <span class="sidebar-subitem-text">Dashboard</span>
                <?php if($currentSubsection === '' && $currentSection === 'financeiro'): ?>
                    <div class="sidebar-subitem-indicator"></div>
                <?php endif; ?>
            </a>

            <!-- Transações -->
            <a href="<?php echo e(route('admin.finance.transactions.index')); ?>"
                class="sidebar-subitem <?php echo e($currentSubsection === 'transacoes' ? 'active' : ''); ?>">
                <i class="sidebar-subitem-icon fas fa-exchange-alt"></i>
                <span class="sidebar-subitem-text">Transações</span>
                <?php if($currentSubsection === 'transacoes'): ?>
                    <div class="sidebar-subitem-indicator"></div>
                <?php endif; ?>
            </a>

            <!-- Campanhas -->
            <a href="<?php echo e(route('admin.finance.campaigns.index')); ?>"
                class="sidebar-subitem <?php echo e($currentSubsection === 'campanhas' ? 'active' : ''); ?>">
                <i class="sidebar-subitem-icon fas fa-bullhorn"></i>
                <span class="sidebar-subitem-text">Campanhas</span>
                <?php if($currentSubsection === 'campanhas'): ?>
                    <div class="sidebar-subitem-indicator"></div>
                <?php endif; ?>
            </a>

            <!-- Relatórios -->
            <a href="<?php echo e(route('admin.finance.reports.index')); ?>"
                class="sidebar-subitem <?php echo e($currentSubsection === 'relatorios' ? 'active' : ''); ?>">
                <i class="sidebar-subitem-icon fas fa-file-alt"></i>
                <span class="sidebar-subitem-text">Relatórios</span>
                <?php if($currentSubsection === 'relatorios'): ?>
                    <div class="sidebar-subitem-indicator"></div>
                <?php endif; ?>
            </a>

            <!-- Documentos de Baixa -->
            <a href="<?php echo e(route('admin.finance.documentos.index')); ?>"
                class="sidebar-subitem <?php echo e($currentSubsection === 'documentos-baixa' ? 'active' : ''); ?>">
                <i class="sidebar-subitem-icon fas fa-file-download"></i>
                <span class="sidebar-subitem-text">Documentos de Baixa</span>
                <?php if($currentSubsection === 'documentos-baixa'): ?>
                    <div class="sidebar-subitem-indicator"></div>
                <?php endif; ?>
            </a>

            <!-- Declaração Anual -->
            <a href="<?php echo e(route('admin.finance.documentos-declaracao-anual.index')); ?>"
                class="sidebar-subitem <?php echo e($currentSubsection === 'declaracao-anual' ? 'active' : ''); ?>">
                <i class="sidebar-subitem-icon fas fa-calendar-alt"></i>
                <span class="sidebar-subitem-text">Declaração Anual</span>
                <?php if($currentSubsection === 'declaracao-anual'): ?>
                    <div class="sidebar-subitem-indicator"></div>
                <?php endif; ?>
            </a>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH C:\wamp64\www\CBAV2025\resources\views/components/admin/sidebar/financial.blade.php ENDPATH**/ ?>