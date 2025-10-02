@props(['configuracoes'])

<div x-show="activeTab === 'cache'" class="space-y-8"
     x-data="{
        cache_driver: '{{ old('cache_driver', $configuracoes['cache_driver'] ?? 'file') }}',
        backup_enabled: {{ old('backup_enabled', $configuracoes['backup_enabled'] ?? false) ? 'true' : 'false' }}
     }">

    <!-- Bloco de Cache -->
    <x-admin.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Configurações de Cache</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Otimize a performance da aplicação com um sistema de cache eficiente.</p>
        </x-slot>

        <div>
            <x-admin.label for="cache_driver" value="Driver de Cache *" />
            <x-admin.select id="cache_driver" name="cache_driver" x-model="cache_driver" class="mt-1 block w-full">
                <option value="file">File (Arquivo)</option>
                <option value="redis">Redis</option>
                <option value="memcached">Memcached</option>
                <option value="database">Database (Banco de Dados)</option>
                <option value="array">Array (Apenas para desenvolvimento)</option>
            </x-admin.select>
        </div>

        <!-- Campos específicos para Redis -->
        <div x-show="cache_driver === 'redis'" x-transition class="mt-6 space-y-6 border-t border-gray-200 dark:border-gray-700 pt-6">
            <h4 class="text-base font-medium text-gray-800 dark:text-gray-200">Configurações do Redis</h4>
             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-admin.label for="redis_host" value="Host" />
                    <x-admin.input id="redis_host" name="redis_host" :value="old('redis_host', $configuracoes['redis_host'] ?? '127.0.0.1')" class="mt-1 block w-full" />
                </div>
                 <div>
                    <x-admin.label for="redis_port" value="Porta" />
                    <x-admin.input id="redis_port" name="redis_port" type="number" :value="old('redis_port', $configuracoes['redis_port'] ?? '6379')" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-admin.label for="redis_password" value="Senha" />
                    <x-admin.input id="redis_password" name="redis_password" type="password" :value="old('redis_password', $configuracoes['redis_password'] ?? '')" class="mt-1 block w-full" />
                </div>
             </div>
        </div>
    </x-admin.card>

    <!-- Bloco de Backup -->
    <x-admin.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Configurações de Backup</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Configure backups automáticos para proteger os dados do sistema.</p>
        </x-slot>

        <div class="space-y-6">
            <x-admin.toggle-switch name="backup_enabled" x-model="backup_enabled">
                Ativar Backups Automáticos
            </x-admin.toggle-switch>

            <div x-show="backup_enabled" x-transition class="space-y-6 pl-6 border-l-2 border-blue-200 dark:border-gray-700">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-admin.label for="backup_frequency" value="Frequência" />
                        <x-admin.select id="backup_frequency" name="backup_frequency" class="mt-1 block w-full">
                            <option value="daily" @selected(old('backup_frequency', $configuracoes['backup_frequency']) === 'daily')>Diariamente</option>
                            <option value="weekly" @selected(old('backup_frequency', $configuracoes['backup_frequency']) === 'weekly')>Semanalmente</option>
                            <option value="monthly" @selected(old('backup_frequency', $configuracoes['backup_frequency']) === 'monthly')>Mensalmente</option>
                        </x-admin.select>
                    </div>
                     <div>
                        <x-admin.label for="backup_retention" value="Período de Retenção (dias)" />
                        <x-admin.input id="backup_retention" name="backup_retention" type="number" min="1" max="365" class="mt-1 block w-full"
                                       :value="old('backup_retention', $configuracoes['backup_retention'])" required />
                    </div>
                </div>
            </div>
        </div>
    </x-admin.card>
</div>