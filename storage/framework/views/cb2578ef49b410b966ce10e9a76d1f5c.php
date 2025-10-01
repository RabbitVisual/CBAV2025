
<div class="space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                <i class="fas fa-cog mr-2 text-blue-600"></i>Configurações Gerais
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Configure as informações básicas da aplicação</p>
        </div>
        <div class="p-6">
            <form action="<?php echo e(route('admin.system.settings.update')); ?>" method="POST" enctype="multipart/form-data" id="form-geral">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <input type="hidden" name="active_tab" value="geral">

                
                <div class="mb-8">
                    <h4 class="text-base font-semibold text-blue-600 dark:text-blue-400 mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>Informações Básicas
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="app_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nome da Aplicação *</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white <?php $__errorArgs = ['app_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="app_name" name="app_name" 
                                   value="<?php echo e(old('app_name', $configuracoes['app_name'] ?? config('app.name'))); ?>" 
                                   required>
                            <?php $__errorArgs = ['app_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="app_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descrição</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white <?php $__errorArgs = ['app_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="app_description" name="app_description" 
                                   value="<?php echo e(old('app_description', $configuracoes['app_description'] ?? '')); ?>">
                            <?php $__errorArgs = ['app_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email de Contato *</label>
                            <input type="email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white <?php $__errorArgs = ['contact_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="contact_email" name="contact_email" 
                                   value="<?php echo e(old('contact_email', $configuracoes['contact_email'] ?? '')); ?>" 
                                   required>
                            <?php $__errorArgs = ['contact_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="contact_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Telefone de Contato</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white <?php $__errorArgs = ['contact_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="contact_phone" name="contact_phone" 
                                   value="<?php echo e(old('contact_phone', $configuracoes['contact_phone'] ?? '')); ?>">
                            <?php $__errorArgs = ['contact_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Endereço</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="address" name="address" rows="3"><?php echo e(old('address', $configuracoes['address'] ?? '')); ?></textarea>
                            <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="timezone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fuso Horário *</label>
                            <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white <?php $__errorArgs = ['timezone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="timezone" name="timezone" required>
                                <?php $__currentLoopData = timezone_identifiers_list(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timezone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($timezone); ?>" 
                                            <?php echo e((old('timezone', $configuracoes['timezone'] ?? config('app.timezone')) === $timezone) ? 'selected' : ''); ?>>
                                        <?php echo e($timezone); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['timezone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="locale" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Idioma *</label>
                            <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white <?php $__errorArgs = ['locale'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="locale" name="locale" required>
                                <option value="pt_BR" <?php echo e((old('locale', $configuracoes['locale'] ?? 'pt_BR') === 'pt_BR') ? 'selected' : ''); ?>>Português (Brasil)</option>
                                <option value="en" <?php echo e((old('locale', $configuracoes['locale'] ?? 'pt_BR') === 'en') ? 'selected' : ''); ?>>English</option>
                                <option value="es" <?php echo e((old('locale', $configuracoes['locale'] ?? 'pt_BR') === 'es') ? 'selected' : ''); ?>>Español</option>
                            </select>
                            <?php $__errorArgs = ['locale'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                
                <div class="mb-8">
                    <h4 class="text-base font-semibold text-blue-600 dark:text-blue-400 mb-4 flex items-center">
                        <i class="fas fa-images mr-2"></i>Imagens
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Logo da Aplicação</label>
                            <input type="file" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="logo" name="logo" accept="image/*">
                            <?php if(isset($configuracoes['logo']) && $configuracoes['logo']): ?>
                                <div class="mt-3">
                                    <img src="<?php echo e(asset('storage/' . $configuracoes['logo'])); ?>" 
                                         alt="Logo atual" class="rounded-lg border border-gray-200 dark:border-gray-600" style="max-height: 100px;">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Logo atual</p>
                                </div>
                            <?php endif; ?>
                            <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Formatos aceitos: JPG, PNG, SVG. Tamanho máximo: 2MB</p>
                        </div>

                        <div>
                            <label for="favicon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Favicon</label>
                            <input type="file" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white <?php $__errorArgs = ['favicon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="favicon" name="favicon" accept="image/*">
                            <?php if(isset($configuracoes['favicon']) && $configuracoes['favicon']): ?>
                                <div class="mt-3">
                                    <img src="<?php echo e(asset('storage/' . $configuracoes['favicon'])); ?>" 
                                         alt="Favicon atual" class="rounded border border-gray-200 dark:border-gray-600" style="max-height: 50px;">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Favicon atual</p>
                                </div>
                            <?php endif; ?>
                            <?php $__errorArgs = ['favicon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Formatos aceitos: ICO, PNG. Tamanho recomendado: 32x32px</p>
                        </div>
                    </div>
                </div>

                
                <div class="mb-8">
                    <h4 class="text-base font-semibold text-blue-600 dark:text-blue-400 mb-4 flex items-center">
                        <i class="fas fa-share-alt mr-2"></i>Redes Sociais
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="facebook_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                                <i class="fab fa-facebook text-blue-600 mr-2"></i>Facebook
                            </label>
                            <input type="url" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white <?php $__errorArgs = ['facebook_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="facebook_url" name="facebook_url" 
                                   value="<?php echo e(old('facebook_url', $configuracoes['facebook_url'] ?? '')); ?>" 
                                   placeholder="https://facebook.com/sua-pagina">
                            <?php $__errorArgs = ['facebook_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="instagram_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                                <i class="fab fa-instagram text-pink-600 mr-2"></i>Instagram
                            </label>
                            <input type="url" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white <?php $__errorArgs = ['instagram_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="instagram_url" name="instagram_url" 
                                   value="<?php echo e(old('instagram_url', $configuracoes['instagram_url'] ?? '')); ?>" 
                                   placeholder="https://instagram.com/seu-perfil">
                            <?php $__errorArgs = ['instagram_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="youtube_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                                <i class="fab fa-youtube text-red-600 mr-2"></i>YouTube
                            </label>
                            <input type="url" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white <?php $__errorArgs = ['youtube_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="youtube_url" name="youtube_url" 
                                   value="<?php echo e(old('youtube_url', $configuracoes['youtube_url'] ?? '')); ?>" 
                                   placeholder="https://youtube.com/seu-canal">
                            <?php $__errorArgs = ['youtube_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors duration-200 flex items-center" onclick="window.location.reload()">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 flex items-center" id="btn-submit-geral">
                        <i class="fas fa-save mr-2"></i>Salvar Configurações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div><?php /**PATH C:\wamp64\www\CBAV2025\resources\views/components/admin/settings/general-tab.blade.php ENDPATH**/ ?>