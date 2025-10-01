

<?php $__env->startSection('title', 'Configurações do Sistema'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
<div class="flex justify-between items-center mb-6">
<h1 class="text-3xl font-bold text-gray-900 dark:text-white">Configurações do Sistema</h1>
<div class="flex space-x-3">
<button type="button" onclick="limparCache()"
class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
<i class="fas fa-broom mr-2"></i>Limpar Cache
</button>
<button type="button" onclick="testarConfiguracoes()"
class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
<i class="fas fa-check mr-2"></i>Testar Configurações
</button>
<a href="<?php echo e(route('admin.system.home-config.index')); ?>"
class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
<i class="fas fa-home mr-2"></i>Configurar Homepage
</a>
</div>
</div>

<?php if(session('success')): ?>
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
<?php echo e(session('success')); ?>

</div>
<?php endif; ?>

<!-- Guia de orientação -->
<div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
<div class="flex items-start">
<div class="flex-shrink-0">
<i class="fas fa-info-circle text-blue-600 text-xl"></i>
</div>
<div class="ml-4">
<h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
Guia de Configuração
</h3>
<div class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
<p><strong>Geral:</strong> Informações básicas, fuso horário e idioma</p>
<p><strong>Pagamento:</strong> Gateways de pagamento e configurações de doação</p>
<p><strong>Email:</strong> Configurações de servidor SMTP</p>
<p><strong>Segurança:</strong> 2FA, sessões e proteções</p>
<p><strong>Cache & Backup:</strong> Otimização e backup automático</p>
</div>
</div>
</div>
</div>

<!-- Abas de Navegação -->
<div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl mb-8 overflow-hidden">
<div class="border-b border-gray-200 dark:border-gray-700">
<nav class="flex flex-wrap lg:flex-nowrap overflow-x-auto px-4 lg:px-6" aria-label="Tabs">
<button type="button" onclick="showTab('geral', event)"
class="tab-button active flex-shrink-0 py-4 px-4 lg:px-6 border-b-2 border-blue-500 font-semibold text-sm text-blue-600 dark:text-blue-400 transition-all duration-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-t-lg">
<i class="fas fa-cog mr-2"></i>
<span class="hidden sm:inline">Geral</span>
<span class="sm:hidden">Geral</span>
</button>
<button type="button" onclick="showTab('pagamento', event)"
class="tab-button flex-shrink-0 py-4 px-4 lg:px-6 border-b-2 border-transparent font-semibold text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-300 rounded-t-lg">
<i class="fas fa-credit-card mr-2"></i>
<span class="hidden sm:inline">Pagamento</span>
<span class="sm:hidden">Pag.</span>
</button>
<button type="button" onclick="showTab('email', event)"
class="tab-button flex-shrink-0 py-4 px-4 lg:px-6 border-b-2 border-transparent font-semibold text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-300 rounded-t-lg">
<i class="fas fa-envelope mr-2"></i>
<span class="hidden sm:inline">Email</span>
<span class="sm:hidden">Email</span>
</button>
<button type="button" onclick="showTab('seguranca', event)"
class="tab-button flex-shrink-0 py-4 px-4 lg:px-6 border-b-2 border-transparent font-semibold text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-300 rounded-t-lg">
<i class="fas fa-shield-alt mr-2"></i>
<span class="hidden sm:inline">Segurança</span>
<span class="sm:hidden">Seg</span>
</button>
<button type="button" onclick="showTab('cache', event)"
class="tab-button flex-shrink-0 py-4 px-4 lg:px-6 border-b-2 border-transparent font-semibold text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-300 rounded-t-lg">
<i class="fas fa-database mr-2"></i>
<span class="hidden sm:inline">Cache & Backup</span>
<span class="sm:hidden">Cache</span>
</button>
</nav>
</div>

<!-- Conteúdo das Abas -->
<div class="p-6 lg:p-8">
<!-- Aba Geral -->
<div id="tab-geral" class="tab-content">
<?php if (isset($component)) { $__componentOriginal8699037133b61207c981a3ae53e67d15 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8699037133b61207c981a3ae53e67d15 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.settings.general-tab','data' => ['configuracoes' => $configuracoes,'activeTab' => $activeTab]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.settings.general-tab'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['configuracoes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($configuracoes),'activeTab' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activeTab)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8699037133b61207c981a3ae53e67d15)): ?>
<?php $attributes = $__attributesOriginal8699037133b61207c981a3ae53e67d15; ?>
<?php unset($__attributesOriginal8699037133b61207c981a3ae53e67d15); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8699037133b61207c981a3ae53e67d15)): ?>
<?php $component = $__componentOriginal8699037133b61207c981a3ae53e67d15; ?>
<?php unset($__componentOriginal8699037133b61207c981a3ae53e67d15); ?>
<?php endif; ?>
</div>

<!-- Aba Pagamento -->
<div id="tab-pagamento" class="tab-content hidden">
<?php if (isset($component)) { $__componentOriginal312d81d33287ef8c97589bfc9dfb1201 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal312d81d33287ef8c97589bfc9dfb1201 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.settings.payment-tab','data' => ['configuracoes' => $configuracoes,'activeTab' => $activeTab]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.settings.payment-tab'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['configuracoes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($configuracoes),'activeTab' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activeTab)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal312d81d33287ef8c97589bfc9dfb1201)): ?>
<?php $attributes = $__attributesOriginal312d81d33287ef8c97589bfc9dfb1201; ?>
<?php unset($__attributesOriginal312d81d33287ef8c97589bfc9dfb1201); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal312d81d33287ef8c97589bfc9dfb1201)): ?>
<?php $component = $__componentOriginal312d81d33287ef8c97589bfc9dfb1201; ?>
<?php unset($__componentOriginal312d81d33287ef8c97589bfc9dfb1201); ?>
<?php endif; ?>
</div>

<!-- Aba Email -->
<div id="tab-email" class="tab-content hidden">
<?php if (isset($component)) { $__componentOriginal84793356aac9f800bf91c2f3d0b2df15 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal84793356aac9f800bf91c2f3d0b2df15 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.settings.email-tab','data' => ['configuracoes' => $configuracoes,'activeTab' => $activeTab]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.settings.email-tab'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['configuracoes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($configuracoes),'activeTab' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activeTab)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal84793356aac9f800bf91c2f3d0b2df15)): ?>
<?php $attributes = $__attributesOriginal84793356aac9f800bf91c2f3d0b2df15; ?>
<?php unset($__attributesOriginal84793356aac9f800bf91c2f3d0b2df15); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal84793356aac9f800bf91c2f3d0b2df15)): ?>
<?php $component = $__componentOriginal84793356aac9f800bf91c2f3d0b2df15; ?>
<?php unset($__componentOriginal84793356aac9f800bf91c2f3d0b2df15); ?>
<?php endif; ?>
</div>

<!-- Aba Segurança -->
<div id="tab-seguranca" class="tab-content hidden">
<div id="seguranca">
<?php if (isset($component)) { $__componentOriginal02eaba4051e2ac8610807f9f85621ab0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal02eaba4051e2ac8610807f9f85621ab0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.settings.security-tab','data' => ['configuracoes' => $configuracoes,'activeTab' => $activeTab]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.settings.security-tab'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['configuracoes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($configuracoes),'activeTab' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activeTab)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal02eaba4051e2ac8610807f9f85621ab0)): ?>
<?php $attributes = $__attributesOriginal02eaba4051e2ac8610807f9f85621ab0; ?>
<?php unset($__attributesOriginal02eaba4051e2ac8610807f9f85621ab0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal02eaba4051e2ac8610807f9f85621ab0)): ?>
<?php $component = $__componentOriginal02eaba4051e2ac8610807f9f85621ab0; ?>
<?php unset($__componentOriginal02eaba4051e2ac8610807f9f85621ab0); ?>
<?php endif; ?>
</div>
</div>

<!-- Aba Cache & Backup -->
<div id="tab-cache" class="tab-content hidden">
<div id="cache-backup">
<?php if (isset($component)) { $__componentOriginal1d7d6f5e737933141c44bd1eb61a03a7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1d7d6f5e737933141c44bd1eb61a03a7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.settings.cache-backup-tab','data' => ['configuracoes' => $configuracoes,'activeTab' => $activeTab]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.settings.cache-backup-tab'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['configuracoes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($configuracoes),'activeTab' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activeTab)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1d7d6f5e737933141c44bd1eb61a03a7)): ?>
<?php $attributes = $__attributesOriginal1d7d6f5e737933141c44bd1eb61a03a7; ?>
<?php unset($__attributesOriginal1d7d6f5e737933141c44bd1eb61a03a7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1d7d6f5e737933141c44bd1eb61a03a7)): ?>
<?php $component = $__componentOriginal1d7d6f5e737933141c44bd1eb61a03a7; ?>
<?php unset($__componentOriginal1d7d6f5e737933141c44bd1eb61a03a7); ?>
<?php endif; ?>
</div>
</div>
</div>

<!-- Botões de Ação -->
<div class="flex flex-col sm:flex-row justify-end gap-3 pt-8 border-t border-gray-200 dark:border-gray-700">
<button type="button" onclick="window.location.reload()"
class="w-full sm:w-auto px-6 py-3 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-all duration-200 font-medium border border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500">
<i class="fas fa-times mr-2"></i>Cancelar
</button>
<button type="submit"
class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg transition-all duration-200 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
<i class="fas fa-save mr-2"></i>Salvar Configurações
</button>
</div>
</div>

<script>
// Função para alternar entre as abas
function showTab(tabName, event) {
    // Esconder todas as abas
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
        tab.classList.remove('block');
        // Fallback: garantir display none
        tab.style.display = 'none';
    });

    // Remover classe ativa de todos os botões
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('border-blue-500', 'text-blue-600', 'dark:text-blue-400', 'active', 'hover:bg-blue-50', 'dark:hover:bg-blue-900/20');
        button.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400', 'hover:bg-gray-50', 'dark:hover:bg-gray-700/50');
    });

    // Mostrar a aba selecionada
    const targetTab = tabName.startsWith('tab-') ? tabName : 'tab-' + tabName;
    const targetEl = document.getElementById(targetTab);
    if (targetEl) {
        targetEl.classList.remove('hidden');
        targetEl.classList.add('block');
        // Fallback: garantir display block
        targetEl.style.display = 'block';
    }

    // Ativar o botão correspondente
    if (event) {
        const activeButton = event.target.closest('.tab-button');
        if (activeButton) {
            activeButton.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400', 'hover:bg-gray-50', 'dark:hover:bg-gray-700/50');
            activeButton.classList.add('border-blue-500', 'text-blue-600', 'dark:text-blue-400', 'active', 'hover:bg-blue-50', 'dark:hover:bg-blue-900/20');
        }
    }
}

// Inicializar com a primeira aba ativa
document.addEventListener('DOMContentLoaded', function() {
    const firstButton = document.querySelector('.tab-button');
    if (firstButton) {
        firstButton.click();
    }
});

// Funções auxiliares para cache
function limparCache() {
    if (confirm('Tem certeza que deseja limpar todo o cache?')) {
        fetch('<?php echo e(route("admin.system.cache.clear")); ?>', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Cache limpo com sucesso!');
            } else {
                alert('Erro ao limpar cache: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao limpar cache');
        });
    }
}

function testarConfiguracoes() {
    alert('Funcionalidade de teste em desenvolvimento');
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\CBAV2025\resources\views/admin/system/settings/index.blade.php ENDPATH**/ ?>