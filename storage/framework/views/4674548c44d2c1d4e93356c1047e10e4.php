<?php
    use App\Helpers\PermissionHelper;

    // Determinar seção ativa baseada na URL atual
    $currentPath = request()->path();
    $currentSection = '';
    $currentSubsection = '';

    // Mapear URLs para seções de forma mais precisa
    if ($currentPath === 'admin/dashboard' || $currentPath === 'admin') {
        $currentSection = 'dashboard';
    } elseif (str_starts_with($currentPath, 'admin/people')) {
        $currentSection = 'people';
        if (str_contains($currentPath, 'members')) {
            $currentSubsection = 'members';
        } elseif (str_contains($currentPath, 'users')) {
            $currentSubsection = 'users';
        } elseif (str_contains($currentPath, 'ministries')) {
            $currentSubsection = 'ministries';
        } elseif (str_contains($currentPath, 'departments')) {
            $currentSubsection = 'departments';
        } elseif (str_contains($currentPath, 'cargos')) {
            $currentSubsection = 'cargos';
        } elseif (str_contains($currentPath, 'birthdays')) {
            $currentSubsection = 'birthdays';
        }
    } elseif (str_starts_with($currentPath, 'admin/ceps')) {
        $currentSection = 'people';
        $currentSubsection = 'ceps';
    } elseif (str_starts_with($currentPath, 'admin/ebd')) {
        $currentSection = 'ebd';
        if (str_contains($currentPath, 'turmas')) {
            $currentSubsection = 'turmas';
        } elseif (str_contains($currentPath, 'professores')) {
            $currentSubsection = 'professores';
        } elseif (str_contains($currentPath, 'alunos')) {
            $currentSubsection = 'alunos';
        } elseif (str_contains($currentPath, 'licoes')) {
            $currentSubsection = 'licoes';
        } elseif (str_contains($currentPath, 'aulas')) {
            $currentSubsection = 'aulas';
        } elseif (str_contains($currentPath, 'avaliacoes')) {
            $currentSubsection = 'avaliacoes';
        } elseif (str_contains($currentPath, 'questoes')) {
            $currentSubsection = 'questoes';
        } elseif (str_contains($currentPath, 'certificados')) {
            $currentSubsection = 'certificados';
        } elseif (str_contains($currentPath, 'relatorios')) {
            $currentSubsection = 'relatorios';
        }
    } elseif (str_starts_with($currentPath, 'admin/quiz')) {
        $currentSection = 'quiz';
        if (str_contains($currentPath, 'statistics')) {
            $currentSubsection = 'quiz-stats';
        } elseif (str_contains($currentPath, 'settings')) {
            $currentSubsection = 'quiz-settings';
        }
    } elseif (str_starts_with($currentPath, 'admin/intercessor')) {
        $currentSection = 'intercessor';
        if (str_contains($currentPath, 'pedidos')) {
            $currentSubsection = 'pedidos';
        }
    } elseif (str_starts_with($currentPath, 'admin/eventos')) {
        $currentSection = 'eventos';
        if (str_contains($currentPath, 'create')) {
            $currentSubsection = 'create';
        }
    } elseif (str_starts_with($currentPath, 'admin/financeiro')) {
        $currentSection = 'financeiro';
        if (str_contains($currentPath, 'transacoes')) {
            $currentSubsection = 'transacoes';
        } elseif (str_contains($currentPath, 'campanhas')) {
            $currentSubsection = 'campanhas';
        } elseif (str_contains($currentPath, 'relatorios')) {
            $currentSubsection = 'relatorios';
        } elseif (str_contains($currentPath, 'documentos-baixa')) {
            $currentSubsection = 'documentos-baixa';
        } elseif (str_contains($currentPath, 'declaracao-anual')) {
            $currentSubsection = 'declaracao-anual';
        }
    } elseif (str_starts_with($currentPath, 'admin/sistema')) {
        $currentSection = 'sistema';
        if (str_contains($currentPath, 'configuracoes')) {
            $currentSubsection = 'configuracoes';
        } elseif (str_contains($currentPath, 'config-home')) {
            $currentSubsection = 'config-home';
        } elseif (str_contains($currentPath, 'chat')) {
            $currentSubsection = 'chat';
        } elseif (str_contains($currentPath, 'notificacoes')) {
            $currentSubsection = 'notificacoes';
        } elseif (str_contains($currentPath, 'logs')) {
            $currentSubsection = 'logs';
        } elseif (str_contains($currentPath, 'permissoes')) {
            $currentSubsection = 'permissoes';
        } elseif (str_contains($currentPath, 'devocionais')) {
            $currentSubsection = 'devocionais';
        }
    } elseif (str_starts_with($currentPath, 'admin/conselho')) {
        $currentSection = 'conselho';
        if (str_contains($currentPath, 'reunioes')) {
            $currentSubsection = 'reunioes';
        } elseif (str_contains($currentPath, 'votacoes')) {
            $currentSubsection = 'votacoes';
        } elseif (str_contains($currentPath, 'templates')) {
            $currentSubsection = 'templates';
        } elseif (str_contains($currentPath, 'configuracoes')) {
            $currentSubsection = 'configuracoes';
        } elseif (str_contains($currentPath, 'nova-reuniao')) {
            $currentSubsection = 'nova-reuniao';
        } elseif (str_contains($currentPath, 'area-membros')) {
            $currentSubsection = 'area-membros';
        }
    }
?>

<!-- Sidebar Modular -->
<div x-data="{
    expandedSections: {
        dashboard: <?php echo e($currentSection === 'dashboard' ? 'true' : 'false'); ?>,
        people: <?php echo e($currentSection === 'people' ? 'true' : 'false'); ?>,
        ebd: <?php echo e($currentSection === 'ebd' ? 'true' : 'false'); ?>,
        quiz: <?php echo e($currentSection === 'quiz' ? 'true' : 'false'); ?>,
        intercessor: <?php echo e($currentSection === 'intercessor' ? 'true' : 'false'); ?>,
        eventos: <?php echo e($currentSection === 'eventos' ? 'true' : 'false'); ?>,
        financeiro: <?php echo e($currentSection === 'financeiro' ? 'true' : 'false'); ?>,
        sistema: <?php echo e($currentSection === 'sistema' ? 'true' : 'false'); ?>,
        conselho: <?php echo e($currentSection === 'conselho' ? 'true' : 'false'); ?>

    },
    currentSection: '<?php echo e($currentSection); ?>',
    currentSubsection: '<?php echo e($currentSubsection); ?>',
    
    // Função para salvar estado no localStorage
    saveExpandedState() {
        localStorage.setItem('sidebarExpandedSections', JSON.stringify(this.expandedSections));
    },
    
    // Função para carregar estado do localStorage
    loadExpandedState() {
        const saved = localStorage.getItem('sidebarExpandedSections');
        if (saved) {
            try {
                const savedState = JSON.parse(saved);
                Object.assign(this.expandedSections, savedState);
            } catch (e) {
                console.log('Erro ao carregar estado do sidebar:', e);
            }
        }
    },
    
    // Função para alternar seção
    toggleSection(section) {
        this.expandedSections[section] = !this.expandedSections[section];
        this.saveExpandedState();
    }
}" 
x-init="
    // Carregar estado salvo
    loadExpandedState();
    
    // Garantir que a seção atual esteja sempre expandida
    if (currentSection) {
        expandedSections[currentSection] = true;
        saveExpandedState();
    }
" 
class="space-y-2 transition-all duration-300 ease-in-out"

    <!-- Dashboard Principal -->
    <?php if (isset($component)) { $__componentOriginalaaa9b7103685cb833f23c9d02014dc2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalaaa9b7103685cb833f23c9d02014dc2c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar.dashboard','data' => ['currentSection' => $currentSection,'xBind:sidebarCollapsed' => 'sidebarCollapsed']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar.dashboard'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['currentSection' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($currentSection),'x-bind:sidebarCollapsed' => 'sidebarCollapsed']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalaaa9b7103685cb833f23c9d02014dc2c)): ?>
<?php $attributes = $__attributesOriginalaaa9b7103685cb833f23c9d02014dc2c; ?>
<?php unset($__attributesOriginalaaa9b7103685cb833f23c9d02014dc2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalaaa9b7103685cb833f23c9d02014dc2c)): ?>
<?php $component = $__componentOriginalaaa9b7103685cb833f23c9d02014dc2c; ?>
<?php unset($__componentOriginalaaa9b7103685cb833f23c9d02014dc2c); ?>
<?php endif; ?>

    <!-- Gestão de Pessoas -->
    <?php if (isset($component)) { $__componentOriginal75206b869c761454b711c5c690726421 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal75206b869c761454b711c5c690726421 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar.people','data' => ['currentSection' => $currentSection,'currentSubsection' => $currentSubsection,'xBind:sidebarCollapsed' => 'sidebarCollapsed']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar.people'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['currentSection' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($currentSection),'currentSubsection' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($currentSubsection),'x-bind:sidebarCollapsed' => 'sidebarCollapsed']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal75206b869c761454b711c5c690726421)): ?>
<?php $attributes = $__attributesOriginal75206b869c761454b711c5c690726421; ?>
<?php unset($__attributesOriginal75206b869c761454b711c5c690726421); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal75206b869c761454b711c5c690726421)): ?>
<?php $component = $__componentOriginal75206b869c761454b711c5c690726421; ?>
<?php unset($__componentOriginal75206b869c761454b711c5c690726421); ?>
<?php endif; ?>

    <!-- Escola Bíblica -->
    <?php if (isset($component)) { $__componentOriginalc16b223c4aed69e24dcebb0a0dd8ff04 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc16b223c4aed69e24dcebb0a0dd8ff04 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar.ebd','data' => ['currentSection' => $currentSection,'currentSubsection' => $currentSubsection,'xBind:sidebarCollapsed' => 'sidebarCollapsed']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar.ebd'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['currentSection' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($currentSection),'currentSubsection' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($currentSubsection),'x-bind:sidebarCollapsed' => 'sidebarCollapsed']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc16b223c4aed69e24dcebb0a0dd8ff04)): ?>
<?php $attributes = $__attributesOriginalc16b223c4aed69e24dcebb0a0dd8ff04; ?>
<?php unset($__attributesOriginalc16b223c4aed69e24dcebb0a0dd8ff04); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc16b223c4aed69e24dcebb0a0dd8ff04)): ?>
<?php $component = $__componentOriginalc16b223c4aed69e24dcebb0a0dd8ff04; ?>
<?php unset($__componentOriginalc16b223c4aed69e24dcebb0a0dd8ff04); ?>
<?php endif; ?>

    <!-- Quiz Bíblico -->
    <?php if (isset($component)) { $__componentOriginale176f2de691f81cf09b3248ed6c2e90a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale176f2de691f81cf09b3248ed6c2e90a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar.quiz','data' => ['currentSection' => $currentSection,'currentSubsection' => $currentSubsection,'xBind:sidebarCollapsed' => 'sidebarCollapsed']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar.quiz'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['currentSection' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($currentSection),'currentSubsection' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($currentSubsection),'x-bind:sidebarCollapsed' => 'sidebarCollapsed']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale176f2de691f81cf09b3248ed6c2e90a)): ?>
<?php $attributes = $__attributesOriginale176f2de691f81cf09b3248ed6c2e90a; ?>
<?php unset($__attributesOriginale176f2de691f81cf09b3248ed6c2e90a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale176f2de691f81cf09b3248ed6c2e90a)): ?>
<?php $component = $__componentOriginale176f2de691f81cf09b3248ed6c2e90a; ?>
<?php unset($__componentOriginale176f2de691f81cf09b3248ed6c2e90a); ?>
<?php endif; ?>

    <!-- Intercessor -->
    <?php if (isset($component)) { $__componentOriginalf63af46e70eaad3f83ee504386522417 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf63af46e70eaad3f83ee504386522417 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar.intercessor','data' => ['currentSection' => $currentSection,'currentSubsection' => $currentSubsection,'xBind:sidebarCollapsed' => 'sidebarCollapsed']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar.intercessor'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['currentSection' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($currentSection),'currentSubsection' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($currentSubsection),'x-bind:sidebarCollapsed' => 'sidebarCollapsed']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf63af46e70eaad3f83ee504386522417)): ?>
<?php $attributes = $__attributesOriginalf63af46e70eaad3f83ee504386522417; ?>
<?php unset($__attributesOriginalf63af46e70eaad3f83ee504386522417); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf63af46e70eaad3f83ee504386522417)): ?>
<?php $component = $__componentOriginalf63af46e70eaad3f83ee504386522417; ?>
<?php unset($__componentOriginalf63af46e70eaad3f83ee504386522417); ?>
<?php endif; ?>

    <!-- Eventos -->
    <?php if (isset($component)) { $__componentOriginal94fbbd8abdff4723115356e3d820e974 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal94fbbd8abdff4723115356e3d820e974 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar.events','data' => ['currentSection' => $currentSection,'currentSubsection' => $currentSubsection,'xBind:sidebarCollapsed' => 'sidebarCollapsed']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar.events'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['currentSection' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($currentSection),'currentSubsection' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($currentSubsection),'x-bind:sidebarCollapsed' => 'sidebarCollapsed']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal94fbbd8abdff4723115356e3d820e974)): ?>
<?php $attributes = $__attributesOriginal94fbbd8abdff4723115356e3d820e974; ?>
<?php unset($__attributesOriginal94fbbd8abdff4723115356e3d820e974); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal94fbbd8abdff4723115356e3d820e974)): ?>
<?php $component = $__componentOriginal94fbbd8abdff4723115356e3d820e974; ?>
<?php unset($__componentOriginal94fbbd8abdff4723115356e3d820e974); ?>
<?php endif; ?>

    <!-- Gestão Financeira -->
    <?php if (isset($component)) { $__componentOriginald5cd5b2a998f42b97281c1cc67bfa463 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald5cd5b2a998f42b97281c1cc67bfa463 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar.financial','data' => ['currentSection' => $currentSection,'currentSubsection' => $currentSubsection,'xBind:sidebarCollapsed' => 'sidebarCollapsed']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar.financial'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['currentSection' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($currentSection),'currentSubsection' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($currentSubsection),'x-bind:sidebarCollapsed' => 'sidebarCollapsed']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald5cd5b2a998f42b97281c1cc67bfa463)): ?>
<?php $attributes = $__attributesOriginald5cd5b2a998f42b97281c1cc67bfa463; ?>
<?php unset($__attributesOriginald5cd5b2a998f42b97281c1cc67bfa463); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald5cd5b2a998f42b97281c1cc67bfa463)): ?>
<?php $component = $__componentOriginald5cd5b2a998f42b97281c1cc67bfa463; ?>
<?php unset($__componentOriginald5cd5b2a998f42b97281c1cc67bfa463); ?>
<?php endif; ?>

    <!-- Gestão do Sistema -->
    <?php if (isset($component)) { $__componentOriginala7c0cc97bddcda274d6cec1dae41d7fb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala7c0cc97bddcda274d6cec1dae41d7fb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar.system','data' => ['currentSection' => $currentSection,'currentSubsection' => $currentSubsection,'xBind:sidebarCollapsed' => 'sidebarCollapsed']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar.system'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['currentSection' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($currentSection),'currentSubsection' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($currentSubsection),'x-bind:sidebarCollapsed' => 'sidebarCollapsed']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala7c0cc97bddcda274d6cec1dae41d7fb)): ?>
<?php $attributes = $__attributesOriginala7c0cc97bddcda274d6cec1dae41d7fb; ?>
<?php unset($__attributesOriginala7c0cc97bddcda274d6cec1dae41d7fb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala7c0cc97bddcda274d6cec1dae41d7fb)): ?>
<?php $component = $__componentOriginala7c0cc97bddcda274d6cec1dae41d7fb; ?>
<?php unset($__componentOriginala7c0cc97bddcda274d6cec1dae41d7fb); ?>
<?php endif; ?>

    <!-- Conselho da Igreja -->
    <?php if (isset($component)) { $__componentOriginal2ed551e7d6e42d5ecdb7aeddc37041e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ed551e7d6e42d5ecdb7aeddc37041e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar.council','data' => ['currentSection' => $currentSection,'currentSubsection' => $currentSubsection,'xBind:sidebarCollapsed' => 'sidebarCollapsed']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar.council'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['currentSection' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($currentSection),'currentSubsection' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($currentSubsection),'x-bind:sidebarCollapsed' => 'sidebarCollapsed']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ed551e7d6e42d5ecdb7aeddc37041e8)): ?>
<?php $attributes = $__attributesOriginal2ed551e7d6e42d5ecdb7aeddc37041e8; ?>
<?php unset($__attributesOriginal2ed551e7d6e42d5ecdb7aeddc37041e8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ed551e7d6e42d5ecdb7aeddc37041e8)): ?>
<?php $component = $__componentOriginal2ed551e7d6e42d5ecdb7aeddc37041e8; ?>
<?php unset($__componentOriginal2ed551e7d6e42d5ecdb7aeddc37041e8); ?>
<?php endif; ?>

    <!-- Área de Membros (Acessível a todos os usuários) -->
    <?php if(Auth::check()): ?>
        <div class="sidebar-section">
            <a href="<?php echo e(route('member.dashboard')); ?>"
                class="sidebar-item <?php echo e($currentSection === 'area-membros' ? 'active' : ''); ?>">
                <i class="sidebar-icon fas fa-users"></i>
                <span class="sidebar-text">Área de Membros</span>
                <?php if($currentSection === 'area-membros'): ?>
                    <div class="sidebar-indicator"></div>
                <?php endif; ?>
            </a>
        </div>
    <?php endif; ?>

    <!-- Meu Perfil -->
    <?php if(Auth::check()): ?>
        <div class="sidebar-section">
            <a href="<?php echo e(route('admin.profile.index')); ?>"
                class="sidebar-item <?php echo e($currentSection === 'perfil' ? 'active' : ''); ?>">
                <i class="sidebar-icon fas fa-user-circle"></i>
                <span class="sidebar-text">Meu Perfil</span>
                <?php if($currentSection === 'perfil'): ?>
                    <div class="sidebar-indicator"></div>
                <?php endif; ?>
            </a>
        </div>
    <?php endif; ?>

</div>
<?php /**PATH C:\wamp64\www\CBAV2025\resources\views/layouts/partials/sidebar-admin.blade.php ENDPATH**/ ?>