
<div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h5 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                <i class="fas fa-shield-alt mr-2 text-blue-600 dark:text-blue-400"></i>Configurações de Segurança
            </h5>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-0">Configure as proteções e políticas de segurança do sistema</p>
        </div>
        <div class="p-6">
            <form action="<?php echo e(route('admin.system.settings.update')); ?>" method="POST" id="form-seguranca">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <input type="hidden" name="active_tab" value="seguranca">

                
                <div class="mb-6">
                    <div class="mb-4">
                        <h6 class="text-base font-semibold text-blue-600 mb-3">
                            <i class="fas fa-clock mr-2"></i>Configurações de Sessão
                        </h6>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="mb-3">
                            <label for="session_lifetime" class="block text-sm font-medium text-gray-700 mb-2">Tempo de Vida da Sessão (minutos) *</label>
                            <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['session_lifetime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="session_lifetime" name="session_lifetime" 
                                   value="<?php echo e(old('session_lifetime', $configuracoes['session_lifetime'] ?? '120')); ?>" 
                                   min="5" max="1440" required>
                            <?php $__errorArgs = ['session_lifetime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-red-600 text-sm mt-1"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="text-gray-500 text-sm">Tempo em minutos antes da sessão expirar (5-1440 min)</small>
                        </div>

                        <div class="mb-3">
                            <label for="max_login_attempts" class="block text-sm font-medium text-gray-700 mb-2">Máximo de Tentativas de Login *</label>
                            <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['max_login_attempts'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="max_login_attempts" name="max_login_attempts" 
                                   value="<?php echo e(old('max_login_attempts', $configuracoes['max_login_attempts'] ?? '5')); ?>" 
                                   min="3" max="20" required>
                            <?php $__errorArgs = ['max_login_attempts'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-red-600 text-sm mt-1"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="text-gray-500 text-sm">Número de tentativas antes de bloquear temporariamente</small>
                        </div>

                        <div class="mb-3">
                            <label for="lockout_duration" class="block text-sm font-medium text-gray-700 mb-2">Duração do Bloqueio (minutos)</label>
                            <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['lockout_duration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="lockout_duration" name="lockout_duration" 
                                   value="<?php echo e(old('lockout_duration', $configuracoes['lockout_duration'] ?? '15')); ?>" 
                                   min="1" max="1440">
                            <?php $__errorArgs = ['lockout_duration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-red-600 text-sm mt-1"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="text-gray-500 text-sm">Tempo de bloqueio após exceder tentativas</small>
                        </div>

                        <div class="mb-3">
                            <div class="flex items-center mt-4">
                                <input class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" type="checkbox" id="remember_me_enabled" 
                                       name="remember_me_enabled" value="1" 
                                       <?php echo e(old('remember_me_enabled', $configuracoes['remember_me_enabled'] ?? true) ? 'checked' : ''); ?>>
                                <label class="ml-2 text-sm font-medium text-gray-700" for="remember_me_enabled">
                                    Permitir "Lembrar de mim"
                                </label>
                            </div>
                            <small class="text-gray-500 text-sm">Permite que usuários mantenham login por mais tempo</small>
                        </div>
                    </div>
                </div>

                
                <div class="mb-6">
                    <div class="mb-4">
                        <h6 class="text-base font-semibold text-blue-600 mb-3">
                            <i class="fas fa-lock mr-2"></i>Proteções Gerais
                        </h6>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="mb-3">
                            <div class="flex items-center">
                                <input class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" type="checkbox" id="force_https" 
                                       name="force_https" value="1" 
                                       <?php echo e(old('force_https', $configuracoes['force_https'] ?? false) ? 'checked' : ''); ?>>
                                <label class="ml-2 text-sm font-medium text-gray-700" for="force_https">
                                    Forçar HTTPS
                                </label>
                            </div>
                            <small class="text-gray-500 text-sm">Redireciona automaticamente HTTP para HTTPS</small>
                        </div>

                        <div class="mb-3">
                            <div class="flex items-center">
                                <input class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" type="checkbox" id="csrf_protection" 
                                       name="csrf_protection" value="1" 
                                       <?php echo e(old('csrf_protection', $configuracoes['csrf_protection'] ?? true) ? 'checked' : ''); ?>>
                                <label class="ml-2 text-sm font-medium text-gray-700" for="csrf_protection">
                                    Proteção CSRF
                                </label>
                            </div>
                            <small class="text-gray-500 text-sm">Proteção contra ataques Cross-Site Request Forgery</small>
                        </div>

                        <div class="mb-3">
                            <div class="flex items-center">
                                <input class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" type="checkbox" id="rate_limiting" 
                                       name="rate_limiting" value="1" 
                                       <?php echo e(old('rate_limiting', $configuracoes['rate_limiting'] ?? true) ? 'checked' : ''); ?>>
                                <label class="ml-2 text-sm font-medium text-gray-700" for="rate_limiting">
                                    Limitação de Taxa (Rate Limiting)
                                </label>
                            </div>
                            <small class="text-gray-500 text-sm">Limita número de requisições por IP</small>
                        </div>

                        <div class="mb-3">
                            <div class="flex items-center">
                                <input class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" type="checkbox" id="ip_whitelist_enabled" 
                                       name="ip_whitelist_enabled" value="1" 
                                       <?php echo e(old('ip_whitelist_enabled', $configuracoes['ip_whitelist_enabled'] ?? false) ? 'checked' : ''); ?>

                                       onchange="toggleIpWhitelist()">
                                <label class="ml-2 text-sm font-medium text-gray-700" for="ip_whitelist_enabled">
                                    Lista Branca de IPs (Admin)
                                </label>
                            </div>
                            <small class="text-gray-500 text-sm">Restringe acesso admin apenas a IPs específicos</small>
                        </div>
                    </div>

                    <div class="mt-4" id="ip-whitelist-fields" style="display: <?php echo e(old('ip_whitelist_enabled', $configuracoes['ip_whitelist_enabled'] ?? false) ? 'block' : 'none'); ?>;">
                        <label for="admin_ip_whitelist" class="block text-sm font-medium text-gray-700 mb-2">IPs Permitidos (Admin)</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['admin_ip_whitelist'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  id="admin_ip_whitelist" name="admin_ip_whitelist" rows="3" 
                                  placeholder="192.168.1.100&#10;203.0.113.0/24&#10;2001:db8::/32"><?php echo e(old('admin_ip_whitelist', $configuracoes['admin_ip_whitelist'] ?? '')); ?></textarea>
                        <?php $__errorArgs = ['admin_ip_whitelist'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-red-600 text-sm mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="text-gray-500 text-sm">Um IP por linha. Suporta CIDR (ex: 192.168.1.0/24)</small>
                    </div>
                </div>

                
                <div class="mb-6">
                    <div class="mb-4">
                        <h6 class="text-base font-semibold text-blue-600 mb-3">
                            <i class="fas fa-mobile-alt mr-2"></i>Autenticação de Dois Fatores (2FA)
                        </h6>
                    </div>
                    
                    <div class="mb-3">
                        <div class="flex items-center">
                            <input class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" type="checkbox" id="two_factor_enabled" 
                                   name="two_factor_enabled" value="1" 
                                   <?php echo e(old('two_factor_enabled', $configuracoes['two_factor_enabled'] ?? false) ? 'checked' : ''); ?>

                                   onchange="toggle2FA()">
                            <label class="ml-2 text-sm font-medium text-gray-700" for="two_factor_enabled">
                                Ativar Autenticação de Dois Fatores
                            </label>
                        </div>
                        <small class="text-gray-500 text-sm">Adiciona uma camada extra de segurança ao login</small>
                    </div>

                    <div id="two-factor-fields" style="display: <?php echo e(old('two_factor_enabled', $configuracoes['two_factor_enabled'] ?? false) ? 'block' : 'none'); ?>;">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="mb-3">
                                <label for="two_factor_method" class="block text-sm font-medium text-gray-700 mb-2">Método de 2FA *</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['two_factor_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="two_factor_method" name="two_factor_method">
                                    <option value="">Selecione o método</option>
                                    <option value="totp" <?php echo e(old('two_factor_method', $configuracoes['two_factor_method'] ?? '') === 'totp' ? 'selected' : ''); ?>>TOTP (Google Authenticator, Authy)</option>
                                    <option value="sms" <?php echo e(old('two_factor_method', $configuracoes['two_factor_method'] ?? '') === 'sms' ? 'selected' : ''); ?>>SMS</option>
                                    <option value="email" <?php echo e(old('two_factor_method', $configuracoes['two_factor_method'] ?? '') === 'email' ? 'selected' : ''); ?>>Email</option>
                                </select>
                                <?php $__errorArgs = ['two_factor_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-red-600 text-sm mt-1"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="mb-3">
                                <label for="two_factor_grace_period" class="block text-sm font-medium text-gray-700 mb-2">Período de Graça (horas)</label>
                                <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['two_factor_grace_period'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="two_factor_grace_period" name="two_factor_grace_period" 
                                       value="<?php echo e(old('two_factor_grace_period', $configuracoes['two_factor_grace_period'] ?? '24')); ?>" 
                                       min="0" max="168">
                                <?php $__errorArgs = ['two_factor_grace_period'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-red-600 text-sm mt-1"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="text-gray-500 text-sm">Tempo para configurar 2FA após ativação (0-168h)</small>
                            </div>

                            <div class="mb-3">
                                <div class="flex items-center">
                                    <input class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" type="checkbox" id="two_factor_remember_device" 
                                           name="two_factor_remember_device" value="1" 
                                           <?php echo e(old('two_factor_remember_device', $configuracoes['two_factor_remember_device'] ?? true) ? 'checked' : ''); ?>>
                                    <label class="ml-2 text-sm font-medium text-gray-700" for="two_factor_remember_device">
                                        Lembrar Dispositivo Confiável
                                    </label>
                                </div>
                                <small class="text-gray-500 text-sm">Permite marcar dispositivos como confiáveis</small>
                            </div>

                            <div class="mb-3">
                                <label for="two_factor_remember_duration" class="block text-sm font-medium text-gray-700 mb-2">Duração Dispositivo Confiável (dias)</label>
                                <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['two_factor_remember_duration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="two_factor_remember_duration" name="two_factor_remember_duration" 
                                       value="<?php echo e(old('two_factor_remember_duration', $configuracoes['two_factor_remember_duration'] ?? '30')); ?>" 
                                       min="1" max="365">
                                <?php $__errorArgs = ['two_factor_remember_duration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-red-600 text-sm mt-1"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="text-gray-500 text-sm">Quantos dias um dispositivo permanece confiável</small>
                            </div>

                            <div class="mb-3 md:col-span-2">
                                <div class="flex items-center">
                                    <input class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" type="checkbox" id="two_factor_backup_codes" 
                                           name="two_factor_backup_codes" value="1" 
                                           <?php echo e(old('two_factor_backup_codes', $configuracoes['two_factor_backup_codes'] ?? true) ? 'checked' : ''); ?>>
                                    <label class="ml-2 text-sm font-medium text-gray-700" for="two_factor_backup_codes">
                                        Códigos de Backup
                                    </label>
                                </div>
                                <small class="text-gray-500 text-sm">Gera códigos de backup para recuperação de acesso</small>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="mb-6" id="two-factor-info" style="display: <?php echo e(old('two_factor_enabled', $configuracoes['two_factor_enabled'] ?? false) ? 'block' : 'none'); ?>;">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 mr-2 mt-1"></i>
                            <div class="flex-1">
                                <strong class="text-blue-800">Métodos de 2FA Disponíveis:</strong>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-3">
                                    <div>
                                        <strong class="text-blue-700">TOTP (Recomendado):</strong><br>
                                        <small class="text-blue-600">
                                            • Google Authenticator<br>
                                            • Authy<br>
                                            • Microsoft Authenticator<br>
                                            • 1Password
                                        </small>
                                    </div>
                                    <div>
                                        <strong class="text-blue-700">SMS:</strong><br>
                                        <small class="text-blue-600">
                                            • Código via mensagem<br>
                                            • Requer configuração SMS<br>
                                            • Menos seguro que TOTP
                                        </small>
                                    </div>
                                    <div>
                                        <strong class="text-blue-700">Email:</strong><br>
                                        <small class="text-blue-600">
                                            • Código via email<br>
                                            • Usa configuração SMTP<br>
                                            • Backup para outros métodos
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="mb-6">
                    <div class="mb-4">
                        <h6 class="text-base font-semibold text-blue-600 mb-3">
                            <i class="fas fa-key mr-2"></i>Políticas de Senha
                        </h6>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="mb-3">
                            <label for="password_min_length" class="block text-sm font-medium text-gray-700 mb-2">Comprimento Mínimo *</label>
                            <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['password_min_length'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="password_min_length" name="password_min_length" 
                                   value="<?php echo e(old('password_min_length', $configuracoes['password_min_length'] ?? '8')); ?>" 
                                   min="6" max="50" required>
                            <?php $__errorArgs = ['password_min_length'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-red-600 text-sm mt-1"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="text-gray-500 text-sm">Número mínimo de caracteres (6-50)</small>
                        </div>

                        <div class="mb-3">
                            <label for="password_expires_days" class="block text-sm font-medium text-gray-700 mb-2">Expiração da Senha (dias)</label>
                            <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['password_expires_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="password_expires_days" name="password_expires_days" 
                                   value="<?php echo e(old('password_expires_days', $configuracoes['password_expires_days'] ?? '0')); ?>" 
                                   min="0" max="365">
                            <?php $__errorArgs = ['password_expires_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-red-600 text-sm mt-1"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="text-gray-500 text-sm">0 = nunca expira, máximo 365 dias</small>
                        </div>

                        <div class="mb-3">
                            <div class="flex items-center">
                                <input class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" type="checkbox" id="password_require_uppercase" 
                                       name="password_require_uppercase" value="1" 
                                       <?php echo e(old('password_require_uppercase', $configuracoes['password_require_uppercase'] ?? true) ? 'checked' : ''); ?>>
                                <label class="ml-2 text-sm font-medium text-gray-700" for="password_require_uppercase">
                                    Exigir Maiúsculas
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="flex items-center">
                                <input class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" type="checkbox" id="password_require_lowercase" 
                                       name="password_require_lowercase" value="1" 
                                       <?php echo e(old('password_require_lowercase', $configuracoes['password_require_lowercase'] ?? true) ? 'checked' : ''); ?>>
                                <label class="ml-2 text-sm font-medium text-gray-700" for="password_require_lowercase">
                                    Exigir Minúsculas
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="flex items-center">
                                <input class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" type="checkbox" id="password_require_numbers" 
                                       name="password_require_numbers" value="1" 
                                       <?php echo e(old('password_require_numbers', $configuracoes['password_require_numbers'] ?? true) ? 'checked' : ''); ?>>
                                <label class="ml-2 text-sm font-medium text-gray-700" for="password_require_numbers">
                                    Exigir Números
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="flex items-center">
                                <input class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" type="checkbox" id="password_require_symbols" 
                                       name="password_require_symbols" value="1" 
                                       <?php echo e(old('password_require_symbols', $configuracoes['password_require_symbols'] ?? false) ? 'checked' : ''); ?>>
                                <label class="ml-2 text-sm font-medium text-gray-700" for="password_require_symbols">
                                    Exigir Símbolos
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="flex justify-end space-x-3">
                    <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200" onclick="window.location.reload()">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200" id="btn-submit-seguranca">
                        <i class="fas fa-save mr-2"></i>Salvar Configurações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
// Toggle para campos de 2FA
function toggle2FA() {
    const checkbox = document.getElementById('two_factor_enabled');
    const fields = document.getElementById('two-factor-fields');
    const info = document.getElementById('two-factor-info');
    
    if (checkbox.checked) {
        fields.style.display = 'block';
        info.style.display = 'block';
        // Tornar método obrigatório
        document.getElementById('two_factor_method').required = true;
    } else {
        fields.style.display = 'none';
        info.style.display = 'none';
        // Remover obrigatoriedade
        document.getElementById('two_factor_method').required = false;
    }
}

// Toggle para lista branca de IPs
function toggleIpWhitelist() {
    const checkbox = document.getElementById('ip_whitelist_enabled');
    const fields = document.getElementById('ip-whitelist-fields');
    
    if (checkbox.checked) {
        fields.style.display = 'block';
    } else {
        fields.style.display = 'none';
    }
}

// Inicializar estado dos campos ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    toggle2FA();
    toggleIpWhitelist();
});

// Validação do formulário
document.getElementById('form-seguranca').addEventListener('submit', function(e) {
    let errors = [];
    
    // Validar configurações de sessão
    const sessionLifetime = parseInt(document.getElementById('session_lifetime').value);
    if (sessionLifetime < 5 || sessionLifetime > 1440) {
        errors.push('Tempo de vida da sessão deve estar entre 5 e 1440 minutos.');
    }
    
    const maxLoginAttempts = parseInt(document.getElementById('max_login_attempts').value);
    if (maxLoginAttempts < 3 || maxLoginAttempts > 20) {
        errors.push('Máximo de tentativas de login deve estar entre 3 e 20.');
    }
    
    // Validar 2FA se ativado
    const twoFactorEnabled = document.getElementById('two_factor_enabled').checked;
    if (twoFactorEnabled) {
        const method = document.getElementById('two_factor_method').value;
        if (!method) {
            errors.push('Selecione um método de 2FA quando a autenticação de dois fatores estiver ativada.');
        }
        
        const gracePeriod = parseInt(document.getElementById('two_factor_grace_period').value);
        if (gracePeriod < 0 || gracePeriod > 168) {
            errors.push('Período de graça deve estar entre 0 e 168 horas.');
        }
    }
    
    // Validar políticas de senha
    const passwordMinLength = parseInt(document.getElementById('password_min_length').value);
    if (passwordMinLength < 6 || passwordMinLength > 50) {
        errors.push('Comprimento mínimo da senha deve estar entre 6 e 50 caracteres.');
    }
    
    const passwordExpires = parseInt(document.getElementById('password_expires_days').value);
    if (passwordExpires < 0 || passwordExpires > 365) {
        errors.push('Expiração da senha deve estar entre 0 e 365 dias.');
    }
    
    // Validar lista branca de IPs se ativada
    const ipWhitelistEnabled = document.getElementById('ip_whitelist_enabled').checked;
    if (ipWhitelistEnabled) {
        const ipList = document.getElementById('admin_ip_whitelist').value.trim();
        if (!ipList) {
            errors.push('Informe pelo menos um IP na lista branca quando esta opção estiver ativada.');
        } else {
            // Validação básica de IPs
            const ips = ipList.split('\n').filter(ip => ip.trim());
            const ipRegex = /^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(?:\/(?:[0-9]|[1-2][0-9]|3[0-2]))?$|^(?:[0-9a-fA-F]{1,4}:){7}[0-9a-fA-F]{1,4}(?:\/(?:[0-9]|[1-9][0-9]|1[0-1][0-9]|12[0-8]))?$/;
            
            for (let ip of ips) {
                ip = ip.trim();
                if (!ipRegex.test(ip)) {
                    errors.push(`IP inválido na lista branca: ${ip}`);
                    break;
                }
            }
        }
    }
    
    // Mostrar erros se houver
    if (errors.length > 0) {
        e.preventDefault();
        alert('Por favor, corrija os seguintes erros:\n\n' + errors.join('\n'));
        return false;
    }
    
    // Confirmação para configurações críticas
    const criticalChanges = [];
    
    if (document.getElementById('force_https').checked) {
        criticalChanges.push('• Forçar HTTPS');
    }
    
    if (twoFactorEnabled) {
        criticalChanges.push('• Ativar 2FA');
    }
    
    if (ipWhitelistEnabled) {
        criticalChanges.push('• Lista branca de IPs');
    }
    
    if (criticalChanges.length > 0) {
        const confirm = window.confirm(
            'ATENÇÃO: Você está ativando configurações críticas de segurança:\n\n' +
            criticalChanges.join('\n') +
            '\n\nCertifique-se de que você tem acesso alternativo ao sistema caso algo dê errado.\n\n' +
            'Deseja continuar?'
        );
        
        if (!confirm) {
            e.preventDefault();
            return false;
        }
    }
});

// Validação em tempo real do comprimento da senha
document.getElementById('password_min_length').addEventListener('input', function() {
    const value = parseInt(this.value);
    const feedback = this.nextElementSibling;
    
    if (value < 6) {
        this.classList.add('is-invalid');
        feedback.textContent = 'Mínimo 6 caracteres';
    } else if (value > 50) {
        this.classList.add('is-invalid');
        feedback.textContent = 'Máximo 50 caracteres';
    } else {
        this.classList.remove('is-invalid');
        feedback.textContent = 'Número mínimo de caracteres (6-50)';
    }
});

// Aviso sobre HTTPS
document.getElementById('force_https').addEventListener('change', function() {
    if (this.checked && window.location.protocol !== 'https:') {
        alert('ATENÇÃO: Você está ativando HTTPS forçado, mas está acessando via HTTP.\n\n' +
              'Certifique-se de que o certificado SSL está configurado corretamente\n' +
              'ou você pode perder o acesso ao sistema.');
    }
});
</script><?php /**PATH C:\wamp64\www\CBAV2025\resources\views/components/admin/settings/security-tab.blade.php ENDPATH**/ ?>