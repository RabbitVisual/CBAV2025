<?php $__env->startSection('content'); ?>
<div class="p-4 sm:p-6 lg:p-8 transition-all duration-300 ease-in-out">
    <!-- Mensagens de Feedback -->
    <?php if(session('success')): ?>
        <div class="mb-4 sm:mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg shadow-sm animate-fade-in transition-all duration-300">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2 text-green-600 dark:text-green-400"></i>
                <span class="font-medium"><?php echo e(session('success')); ?></span>
            </div>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="mb-4 sm:mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg shadow-sm animate-fade-in transition-all duration-300">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2 text-red-600 dark:text-red-400"></i>
                <span class="font-medium"><?php echo e(session('error')); ?></span>
            </div>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="mb-4 sm:mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg shadow-sm animate-fade-in transition-all duration-300">
            <div class="flex items-start mb-2">
                <i class="fas fa-exclamation-triangle mr-2 mt-0.5 text-red-600 dark:text-red-400 flex-shrink-0"></i>
                <div>
                    <strong class="font-semibold">Erro!</strong> 
                    <span class="text-sm">Verifique os campos abaixo:</span>
                </div>
            </div>
            <ul class="list-disc list-inside text-sm space-y-1 ml-6">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="text-red-700 dark:text-red-300"><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Conteúdo Principal -->
    <div class="transition-all duration-300 ease-in-out">
        <?php echo $__env->yieldContent('page-content'); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\CBAV2025\resources\views/layouts/admin.blade.php ENDPATH**/ ?>