{{-- Componente da Aba Cache & Backup - Configurações do Sistema --}}
<div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h5 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                <i class="fas fa-database mr-2 text-blue-600 dark:text-blue-400"></i>Configurações de Cache & Backup
            </h5>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-0">Configure o sistema de cache e backup automático</p>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.system.settings.update') }}" method="POST" id="form-cache-backup">
                @csrf
                @method('PUT')
                <input type="hidden" name="active_tab" value="cache-backup">

                {{-- Configurações de Cache --}}
                <div class="mb-8">
                    <div class="mb-6">
                        <h6 class="text-base font-semibold text-blue-600 dark:text-blue-400 mb-4">
                            <i class="fas fa-tachometer-alt mr-2"></i>Configurações de Cache
                        </h6>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="cache_driver" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Driver de Cache *</label>
                            <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white @error('cache_driver') border-red-500 @enderror" 
                                    id="cache_driver" name="cache_driver" required onchange="toggleCacheConfig()">
                                <option value="">Selecione o driver</option>
                                <option value="file" {{ old('cache_driver', $configuracoes['cache_driver'] ?? 'file') === 'file' ? 'selected' : '' }}>File (Arquivo)</option>
                                <option value="redis" {{ old('cache_driver', $configuracoes['cache_driver'] ?? '') === 'redis' ? 'selected' : '' }}>Redis</option>
                                <option value="memcached" {{ old('cache_driver', $configuracoes['cache_driver'] ?? '') === 'memcached' ? 'selected' : '' }}>Memcached</option>
                                <option value="database" {{ old('cache_driver', $configuracoes['cache_driver'] ?? '') === 'database' ? 'selected' : '' }}>Database</option>
                                <option value="array" {{ old('cache_driver', $configuracoes['cache_driver'] ?? '') === 'array' ? 'selected' : '' }}>Array (Desenvolvimento)</option>
                            </select>
                            @error('cache_driver')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-sm mt-1">Driver para armazenamento do cache</p>
                        </div>

                        <div>
                            <label for="cache_default_ttl" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">TTL Padrão (segundos)</label>
                            <input type="number" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white @error('cache_default_ttl') border-red-500 @enderror" 
                                   id="cache_default_ttl" name="cache_default_ttl" 
                                   value="{{ old('cache_default_ttl', $configuracoes['cache_default_ttl'] ?? '3600') }}" 
                                   min="60" max="86400">
                            @error('cache_default_ttl')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-sm mt-1">Tempo de vida padrão do cache (60-86400 seg)</p>
                        </div>
                    </div>

                    {{-- Configurações Redis --}}
                    <div id="redis-config" class="w-full" style="display: {{ old('cache_driver', $configuracoes['cache_driver'] ?? '') === 'redis' ? 'block' : 'none' }};">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="redis_host" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Host Redis</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white @error('redis_host') border-red-500 @enderror" 
                                       id="redis_host" name="redis_host" 
                                       value="{{ old('redis_host', $configuracoes['redis_host'] ?? '127.0.0.1') }}" 
                                       placeholder="127.0.0.1">
                                @error('redis_host')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="redis_port" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Porta Redis</label>
                                <input type="number" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white @error('redis_port') border-red-500 @enderror" 
                                       id="redis_port" name="redis_port" 
                                       value="{{ old('redis_port', $configuracoes['redis_port'] ?? '6379') }}" 
                                       min="1" max="65535">
                                @error('redis_port')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="redis_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Senha Redis</label>
                                <div class="flex">
                                    <input type="password" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-l-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white @error('redis_password') border-red-500 @enderror" 
                                           id="redis_password" name="redis_password" 
                                           value="{{ old('redis_password', $configuracoes['redis_password'] ?? '') }}" 
                                           placeholder="Deixe em branco se não houver senha">
                                    <button class="px-3 py-2 border border-l-0 border-gray-300 dark:border-gray-600 rounded-r-lg bg-gray-50 dark:bg-gray-600 hover:bg-gray-100 dark:hover:bg-gray-500 text-gray-600 dark:text-gray-300" type="button" onclick="togglePassword('redis_password')">
                                        <i class="fas fa-eye" id="redis_password_icon"></i>
                                    </button>
                                </div>
                                @error('redis_password')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="redis_database" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Database Redis</label>
                                <input type="number" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white @error('redis_database') border-red-500 @enderror" 
                                       id="redis_database" name="redis_database" 
                                       value="{{ old('redis_database', $configuracoes['redis_database'] ?? '0') }}" 
                                       min="0" max="15">
                                @error('redis_database')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-gray-500 text-sm mt-1">Número do database Redis (0-15)</p>
                            </div>
                        </div>
                    </div>

                    {{-- Configurações Memcached --}}
                    <div id="memcached-config" class="w-full" style="display: {{ old('cache_driver', $configuracoes['cache_driver'] ?? '') === 'memcached' ? 'block' : 'none' }};">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="memcached_host" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Host Memcached</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white @error('memcached_host') border-red-500 @enderror" 
                                       id="memcached_host" name="memcached_host" 
                                       value="{{ old('memcached_host', $configuracoes['memcached_host'] ?? '127.0.0.1') }}" 
                                       placeholder="127.0.0.1">
                                @error('memcached_host')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="memcached_port" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Porta Memcached</label>
                                <input type="number" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white @error('memcached_port') border-red-500 @enderror" 
                                       id="memcached_port" name="memcached_port" 
                                       value="{{ old('memcached_port', $configuracoes['memcached_port'] ?? '11211') }}" 
                                       min="1" max="65535">
                                @error('memcached_port')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="w-full">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="cache_enabled" 
                                           name="cache_enabled" value="1" 
                                           {{ old('cache_enabled', $configuracoes['cache_enabled'] ?? true) ? 'checked' : '' }}
                                           class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label class="ml-2 text-sm font-semibold text-gray-900 dark:text-gray-300" for="cache_enabled">
                                        Ativar Cache
                                    </label>
                                </div>
                                <p class="text-gray-500 text-sm mt-1">Habilita o sistema de cache</p>
                            </div>

                            <div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="cache_views" 
                                           name="cache_views" value="1" 
                                           {{ old('cache_views', $configuracoes['cache_views'] ?? true) ? 'checked' : '' }}
                                           class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300" for="cache_views">
                                        Cache de Views
                                    </label>
                                </div>
                                <p class="text-gray-500 text-sm mt-1">Cache das templates Blade</p>
                            </div>

                            <div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="cache_routes" 
                                           name="cache_routes" value="1" 
                                           {{ old('cache_routes', $configuracoes['cache_routes'] ?? true) ? 'checked' : '' }}
                                           class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300" for="cache_routes">
                                        Cache de Rotas
                                    </label>
                                </div>
                                <p class="text-gray-500 text-sm mt-1">Cache do sistema de rotas</p>
                            </div>
                        </div>
                    </div>

                    {{-- Ações de Cache --}}
                    <div class="w-full">
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                            <h6 class="text-sm font-semibold text-blue-800 dark:text-blue-200 mb-3"><i class="fas fa-tools mr-2"></i>Ações de Cache</h6>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" class="px-3 py-1.5 text-xs font-medium text-primary-700 bg-white border border-primary-300 rounded-lg hover:bg-primary-50 focus:ring-2 focus:ring-primary-500 dark:bg-gray-800 dark:text-primary-400 dark:border-primary-600 dark:hover:bg-gray-700" onclick="clearCache('all')">
                                    <i class="fas fa-trash mr-1"></i>Limpar Todo Cache
                                </button>
                                <button type="button" class="px-3 py-1.5 text-xs font-medium text-primary-700 bg-white border border-primary-300 rounded-lg hover:bg-primary-50 focus:ring-2 focus:ring-primary-500 dark:bg-gray-800 dark:text-primary-400 dark:border-primary-600 dark:hover:bg-gray-700" onclick="clearCache('views')">
                                    <i class="fas fa-eye mr-1"></i>Limpar Cache Views
                                </button>
                                <button type="button" class="px-3 py-1.5 text-xs font-medium text-primary-700 bg-white border border-primary-300 rounded-lg hover:bg-primary-50 focus:ring-2 focus:ring-primary-500 dark:bg-gray-800 dark:text-primary-400 dark:border-primary-600 dark:hover:bg-gray-700" onclick="clearCache('routes')">
                                    <i class="fas fa-route mr-1"></i>Limpar Cache Rotas
                                </button>
                                <button type="button" class="px-3 py-1.5 text-xs font-medium text-primary-700 bg-white border border-primary-300 rounded-lg hover:bg-primary-50 focus:ring-2 focus:ring-primary-500 dark:bg-gray-800 dark:text-primary-400 dark:border-primary-600 dark:hover:bg-gray-700" onclick="clearCache('config')">
                                    <i class="fas fa-cog mr-1"></i>Limpar Cache Config
                                </button>
                                <button type="button" class="px-3 py-1.5 text-xs font-medium text-green-700 bg-white border border-green-300 rounded-lg hover:bg-green-50 focus:ring-2 focus:ring-green-500 dark:bg-gray-800 dark:text-green-400 dark:border-green-600 dark:hover:bg-gray-700" onclick="testCache()">
                                    <i class="fas fa-vial mr-1"></i>Testar Cache
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Configurações de Backup --}}
                <div class="mb-8">
                    <div class="mb-6">
                        <h6 class="text-base font-semibold text-blue-600 dark:text-blue-400 mb-4">
                            <i class="fas fa-shield-alt mr-2"></i>Configurações de Backup
                        </h6>
                    </div>
                    
                    <div class="mb-6">
                        <div class="flex items-center mb-2">
                            <input type="checkbox" id="backup_enabled" 
                                   name="backup_enabled" value="1" 
                                   {{ old('backup_enabled', $configuracoes['backup_enabled'] ?? false) ? 'checked' : '' }}
                                   onchange="toggleBackupConfig()"
                                   class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label class="ml-2 text-sm font-semibold text-gray-900 dark:text-gray-300" for="backup_enabled">
                                Ativar Backup Automático
                            </label>
                        </div>
                        <p class="text-gray-500 text-sm">Habilita o sistema de backup automático</p>
                    </div>

                    <div id="backup-config" style="display: {{ old('backup_enabled', $configuracoes['backup_enabled'] ?? false) ? 'block' : 'none' }};">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="backup_frequency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Frequência do Backup *</label>
                                <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white @error('backup_frequency') border-red-500 @enderror" 
                                        id="backup_frequency" name="backup_frequency">
                                    <option value="">Selecione a frequência</option>
                                    <option value="daily" {{ old('backup_frequency', $configuracoes['backup_frequency'] ?? '') === 'daily' ? 'selected' : '' }}>Diário</option>
                                    <option value="weekly" {{ old('backup_frequency', $configuracoes['backup_frequency'] ?? '') === 'weekly' ? 'selected' : '' }}>Semanal</option>
                                    <option value="monthly" {{ old('backup_frequency', $configuracoes['backup_frequency'] ?? '') === 'monthly' ? 'selected' : '' }}>Mensal</option>
                                </select>
                                @error('backup_frequency')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="backup_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Horário do Backup</label>
                                <input type="time" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white @error('backup_time') border-red-500 @enderror" 
                                       id="backup_time" name="backup_time" 
                                       value="{{ old('backup_time', $configuracoes['backup_time'] ?? '02:00') }}">
                                @error('backup_time')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-gray-500 text-sm mt-1">Horário para execução do backup automático</p>
                            </div>

                            <div>
                                <label for="backup_retention_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Retenção (dias)</label>
                                <input type="number" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white @error('backup_retention_days') border-red-500 @enderror" 
                                       id="backup_retention_days" name="backup_retention_days" 
                                       value="{{ old('backup_retention_days', $configuracoes['backup_retention_days'] ?? '30') }}" 
                                       min="1" max="365">
                                @error('backup_retention_days')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-gray-500 text-sm mt-1">Quantos dias manter os backups (1-365)</p>
                            </div>

                            <div>
                                <label for="backup_storage_path" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Caminho de Armazenamento</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white @error('backup_storage_path') border-red-500 @enderror" 
                                       id="backup_storage_path" name="backup_storage_path" 
                                       value="{{ old('backup_storage_path', $configuracoes['backup_storage_path'] ?? storage_path('app/backups')) }}" 
                                       placeholder="{{ storage_path('app/backups') }}">
                                @error('backup_storage_path')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-gray-500 text-sm mt-1">Diretório onde os backups serão armazenados</p>
                            </div>

                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Itens para Backup</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="backup_database" 
                                               name="backup_items[]" value="database" 
                                               {{ in_array('database', old('backup_items', $configuracoes['backup_items'] ?? ['database', 'files'])) ? 'checked' : '' }}
                                               class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300" for="backup_database">
                                            <i class="fas fa-database mr-1"></i>Banco de Dados
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="backup_files" 
                                               name="backup_items[]" value="files" 
                                               {{ in_array('files', old('backup_items', $configuracoes['backup_items'] ?? ['database', 'files'])) ? 'checked' : '' }}
                                               class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300" for="backup_files">
                                            <i class="fas fa-folder mr-1"></i>Arquivos do Sistema
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="backup_uploads" 
                                               name="backup_items[]" value="uploads" 
                                               {{ in_array('uploads', old('backup_items', $configuracoes['backup_items'] ?? ['database', 'files'])) ? 'checked' : '' }}
                                               class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300" for="backup_uploads">
                                            <i class="fas fa-upload mr-1"></i>Uploads de Usuários
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <div class="flex items-center mb-2">
                                    <input type="checkbox" id="backup_compress" 
                                           name="backup_compress" value="1" 
                                           {{ old('backup_compress', $configuracoes['backup_compress'] ?? true) ? 'checked' : '' }}
                                           class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300" for="backup_compress">
                                        Compressão ZIP
                                    </label>
                                </div>
                                <p class="text-gray-500 text-sm">Comprime os backups para economizar espaço</p>
                            </div>

                            <div>
                                <div class="flex items-center mb-2">
                                    <input type="checkbox" id="backup_encrypt" 
                                           name="backup_encrypt" value="1" 
                                           {{ old('backup_encrypt', $configuracoes['backup_encrypt'] ?? false) ? 'checked' : '' }}
                                           onchange="toggleBackupEncryption()"
                                           class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300" for="backup_encrypt">
                                        Criptografia
                                    </label>
                                </div>
                                <p class="text-gray-500 text-sm">Criptografa os backups para maior segurança</p>
                            </div>
                        </div>

                        <div id="backup-encryption-key" style="display: {{ old('backup_encrypt', $configuracoes['backup_encrypt'] ?? false) ? 'block' : 'none' }};">
                            <label for="backup_encryption_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Chave de Criptografia</label>
                            <div class="flex">
                                <input type="password" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-l-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white @error('backup_encryption_key') border-red-500 @enderror" 
                                       id="backup_encryption_key" name="backup_encryption_key" 
                                       value="{{ old('backup_encryption_key', $configuracoes['backup_encryption_key'] ?? '') }}" 
                                       placeholder="Chave para criptografar os backups">
                                <button class="px-3 py-2 border border-l-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-600 hover:bg-gray-100 dark:hover:bg-gray-500 text-gray-600 dark:text-gray-300" type="button" onclick="togglePassword('backup_encryption_key')">
                                    <i class="fas fa-eye" id="backup_encryption_key_icon"></i>
                                </button>
                                <button class="px-3 py-2 border border-l-0 border-gray-300 dark:border-gray-600 rounded-r-lg bg-primary-50 dark:bg-primary-900 hover:bg-primary-100 dark:hover:bg-primary-800 text-primary-600 dark:text-primary-400" type="button" onclick="generateEncryptionKey()">
                                    <i class="fas fa-key"></i>
                                </button>
                            </div>
                            @error('backup_encryption_key')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-sm mt-1">Chave segura para criptografar os backups</p>
                        </div>
                        </div>

                        {{-- Ações de Backup --}}
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                            <h6 class="text-sm font-semibold text-yellow-800 dark:text-yellow-200 mb-3"><i class="fas fa-tools mr-2"></i>Ações de Backup</h6>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" class="px-3 py-1.5 text-xs font-medium text-green-700 bg-white border border-green-300 rounded-lg hover:bg-green-50 focus:ring-2 focus:ring-green-500 dark:bg-gray-800 dark:text-green-400 dark:border-green-600 dark:hover:bg-gray-700" onclick="runBackup()">
                                    <i class="fas fa-play mr-1"></i>Executar Backup Agora
                                </button>
                                <button type="button" class="px-3 py-1.5 text-xs font-medium text-blue-700 bg-white border border-blue-300 rounded-lg hover:bg-blue-50 focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-600 dark:hover:bg-gray-700" onclick="listBackups()">
                                    <i class="fas fa-list mr-1"></i>Listar Backups
                                </button>
                                <button type="button" class="px-3 py-1.5 text-xs font-medium text-primary-700 bg-white border border-primary-300 rounded-lg hover:bg-primary-50 focus:ring-2 focus:ring-primary-500 dark:bg-gray-800 dark:text-primary-400 dark:border-primary-600 dark:hover:bg-gray-700" onclick="testBackupConfig()">
                                    <i class="fas fa-vial mr-1"></i>Testar Configuração
                                </button>
                                <button type="button" class="px-3 py-1.5 text-xs font-medium text-red-700 bg-white border border-red-300 rounded-lg hover:bg-red-50 focus:ring-2 focus:ring-red-500 dark:bg-gray-800 dark:text-red-400 dark:border-red-600 dark:hover:bg-gray-700" onclick="cleanOldBackups()">
                                    <i class="fas fa-broom mr-1"></i>Limpar Backups Antigos
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Informações sobre Drivers --}}
                <div class="mb-8">
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-info-circle mr-2 text-blue-600 dark:text-blue-400"></i>
                            <span class="font-semibold text-blue-800 dark:text-blue-200">Informações sobre Drivers de Cache:</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <h6 class="font-semibold text-gray-900 dark:text-white mb-1">File:</h6>
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    • Padrão do Laravel<br>
                                    • Armazena em arquivos<br>
                                    • Adequado para desenvolvimento
                                </div>
                            </div>
                            <div>
                                <h6 class="font-semibold text-gray-900 dark:text-white mb-1">Redis:</h6>
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    • Alta performance<br>
                                    • Suporte a estruturas complexas<br>
                                    • Recomendado para produção
                                </div>
                            </div>
                            <div>
                                <h6 class="font-semibold text-gray-900 dark:text-white mb-1">Memcached:</h6>
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    • Simples e rápido<br>
                                    • Apenas chave-valor<br>
                                    • Boa para cache simples
                                </div>
                            </div>
                            <div>
                                <h6 class="font-semibold text-gray-900 dark:text-white mb-1">Database:</h6>
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    • Usa banco de dados<br>
                                    • Persistente<br>
                                    • Mais lento que outros
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Botões de Ação --}}
                <div class="flex justify-end gap-3">
                    <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-gray-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600" onclick="window.location.reload()">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-lg hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 dark:bg-primary-600 dark:hover:bg-primary-700" id="btn-submit-cache-backup">
                        <i class="fas fa-save mr-2"></i>Salvar Configurações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Scripts para funcionalidades da aba Cache & Backup --}}
<script>
// Toggle para configurações de cache
function toggleCacheConfig() {
    const driver = document.getElementById('cache_driver').value;
    
    // Ocultar todas as configurações específicas
    document.getElementById('redis-config').style.display = 'none';
    document.getElementById('memcached-config').style.display = 'none';
    
    // Mostrar configuração específica do driver selecionado
    if (driver === 'redis') {
        document.getElementById('redis-config').style.display = 'block';
    } else if (driver === 'memcached') {
        document.getElementById('memcached-config').style.display = 'block';
    }
}

// Toggle para configurações de backup
function toggleBackupConfig() {
    const checkbox = document.getElementById('backup_enabled');
    const config = document.getElementById('backup-config');
    
    if (checkbox.checked) {
        config.style.display = 'block';
        // Tornar frequência obrigatória
        document.getElementById('backup_frequency').required = true;
    } else {
        config.style.display = 'none';
        // Remover obrigatoriedade
        document.getElementById('backup_frequency').required = false;
    }
}

// Toggle para criptografia de backup
function toggleBackupEncryption() {
    const checkbox = document.getElementById('backup_encrypt');
    const keyField = document.getElementById('backup-encryption-key');
    
    if (checkbox.checked) {
        keyField.style.display = 'block';
    } else {
        keyField.style.display = 'none';
    }
}

// Toggle para mostrar/ocultar senhas
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '_icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Gerar chave de criptografia
function generateEncryptionKey() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
    let key = '';
    for (let i = 0; i < 32; i++) {
        key += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('backup_encryption_key').value = key;
}

// Funções de ação de cache
function clearCache(type) {
    if (confirm(`Tem certeza que deseja limpar o cache ${type}?`)) {
        // Aqui seria feita a requisição AJAX para limpar o cache
        fetch(`{{ route('admin.system.cache.clear') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ type: type })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Cache ${type} limpo com sucesso!`);
            } else {
                alert(`Erro ao limpar cache: ${data.message}`);
            }
        })
        .catch(error => {
            alert('Erro ao limpar cache: ' + error.message);
        });
    }
}

function testCache() {
    // Teste de conectividade do cache
    fetch(`{{ route('admin.system.cache.test') }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`Teste de cache bem-sucedido!\n\nDriver: ${data.driver}\nStatus: ${data.status}\nTempo de resposta: ${data.response_time}ms`);
        } else {
            alert(`Erro no teste de cache: ${data.message}`);
        }
    })
    .catch(error => {
        alert('Erro ao testar cache: ' + error.message);
    });
}

// Funções de ação de backup
function runBackup() {
    if (confirm('Executar backup agora? Esta operação pode demorar alguns minutos.')) {
        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Executando...';
        btn.disabled = true;
        
        fetch(`{{ route('admin.system.backup.run') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Backup executado com sucesso!\n\nArquivo: ${data.filename}\nTamanho: ${data.size}\nTempo: ${data.duration}`);
            } else {
                alert(`Erro ao executar backup: ${data.message}`);
            }
        })
        .catch(error => {
            alert('Erro ao executar backup: ' + error.message);
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
}

function listBackups() {
    // Listar backups disponíveis
    fetch(`{{ route('admin.system.backup.list') }}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let message = 'Backups Disponíveis:\n\n';
            if (data.backups.length === 0) {
                message += 'Nenhum backup encontrado.';
            } else {
                data.backups.forEach(backup => {
                    message += `• ${backup.name} (${backup.size}) - ${backup.date}\n`;
                });
            }
            alert(message);
        } else {
            alert(`Erro ao listar backups: ${data.message}`);
        }
    })
    .catch(error => {
        alert('Erro ao listar backups: ' + error.message);
    });
}

function testBackupConfig() {
    // Testar configuração de backup
    fetch(`{{ route('admin.system.backup.test') }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`Configuração de backup válida!\n\nDiretório: ${data.path}\nPermissões: ${data.permissions}\nEspaço disponível: ${data.free_space}`);
        } else {
            alert(`Erro na configuração: ${data.message}`);
        }
    })
    .catch(error => {
        alert('Erro ao testar configuração: ' + error.message);
    });
}

function cleanOldBackups() {
    if (confirm('Limpar backups antigos conforme política de retenção?')) {
        fetch(`{{ route('admin.system.backup.clean') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Limpeza concluída!\n\nArquivos removidos: ${data.removed_count}\nEspaço liberado: ${data.freed_space}`);
            } else {
                alert(`Erro na limpeza: ${data.message}`);
            }
        })
        .catch(error => {
            alert('Erro ao limpar backups: ' + error.message);
        });
    }
}

// Inicializar estado dos campos ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    toggleCacheConfig();
    toggleBackupConfig();
    toggleBackupEncryption();
});

// Validação do formulário
document.getElementById('form-cache-backup').addEventListener('submit', function(e) {
    let errors = [];
    
    // Validar configurações de cache
    const cacheDriver = document.getElementById('cache_driver').value;
    if (!cacheDriver) {
        errors.push('Selecione um driver de cache.');
    }
    
    const cacheTtl = parseInt(document.getElementById('cache_default_ttl').value);
    if (cacheTtl < 60 || cacheTtl > 86400) {
        errors.push('TTL do cache deve estar entre 60 e 86400 segundos.');
    }
    
    // Validar configurações específicas do Redis
    if (cacheDriver === 'redis') {
        const redisHost = document.getElementById('redis_host').value.trim();
        if (!redisHost) {
            errors.push('Informe o host do Redis.');
        }
        
        const redisPort = parseInt(document.getElementById('redis_port').value);
        if (redisPort < 1 || redisPort > 65535) {
            errors.push('Porta do Redis deve estar entre 1 e 65535.');
        }
    }
    
    // Validar configurações específicas do Memcached
    if (cacheDriver === 'memcached') {
        const memcachedHost = document.getElementById('memcached_host').value.trim();
        if (!memcachedHost) {
            errors.push('Informe o host do Memcached.');
        }
        
        const memcachedPort = parseInt(document.getElementById('memcached_port').value);
        if (memcachedPort < 1 || memcachedPort > 65535) {
            errors.push('Porta do Memcached deve estar entre 1 e 65535.');
        }
    }
    
    // Validar configurações de backup se ativado
    const backupEnabled = document.getElementById('backup_enabled').checked;
    if (backupEnabled) {
        const frequency = document.getElementById('backup_frequency').value;
        if (!frequency) {
            errors.push('Selecione a frequência do backup.');
        }
        
        const retention = parseInt(document.getElementById('backup_retention_days').value);
        if (retention < 1 || retention > 365) {
            errors.push('Retenção de backup deve estar entre 1 e 365 dias.');
        }
        
        const storagePath = document.getElementById('backup_storage_path').value.trim();
        if (!storagePath) {
            errors.push('Informe o caminho de armazenamento dos backups.');
        }
        
        // Verificar se pelo menos um item está selecionado para backup
        const backupItems = document.querySelectorAll('input[name="backup_items[]"]');
        const checkedItems = Array.from(backupItems).filter(item => item.checked);
        if (checkedItems.length === 0) {
            errors.push('Selecione pelo menos um item para backup.');
        }
        
        // Validar chave de criptografia se ativada
        const encryptEnabled = document.getElementById('backup_encrypt').checked;
        if (encryptEnabled) {
            const encryptionKey = document.getElementById('backup_encryption_key').value.trim();
            if (!encryptionKey) {
                errors.push('Informe a chave de criptografia para backups.');
            } else if (encryptionKey.length < 16) {
                errors.push('Chave de criptografia deve ter pelo menos 16 caracteres.');
            }
        }
    }
    
    // Mostrar erros se houver
    if (errors.length > 0) {
        e.preventDefault();
        alert('Por favor, corrija os seguintes erros:\n\n' + errors.join('\n'));
        return false;
    }
    
    // Confirmação para mudanças críticas
    const criticalChanges = [];
    
    if (cacheDriver !== '{{ $configuracoes["cache_driver"] ?? "file" }}') {
        criticalChanges.push('• Alteração do driver de cache');
    }
    
    if (backupEnabled && !{{ old('backup_enabled', $configuracoes['backup_enabled'] ?? false) ? 'true' : 'false' }}) {
        criticalChanges.push('• Ativação do backup automático');
    }
    
    if (criticalChanges.length > 0) {
        const confirm = window.confirm(
            'ATENÇÃO: Você está fazendo alterações importantes:\n\n' +
            criticalChanges.join('\n') +
            '\n\nEssas alterações podem afetar a performance do sistema.\n\n' +
            'Deseja continuar?'
        );
        
        if (!confirm) {
            e.preventDefault();
            return false;
        }
    }
});
</script>