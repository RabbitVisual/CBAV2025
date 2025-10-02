@props(['configuracoes'])

<div x-show="activeTab === 'seguranca'" class="space-y-8"
     x-data="{
        force_ssl: {{ old('force_ssl', $configuracoes['force_ssl'] ?? false) ? 'true' : 'false' }},
        enable_2fa: {{ old('enable_2fa', $configuracoes['enable_2fa'] ?? false) ? 'true' : 'false' }}
     }">

    <!-- Bloco de Sessão e Acesso -->
    <x-admin.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sessão e Acesso</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Controle o tempo de sessão e as tentativas de login.</p>
        </x-slot>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-admin.label for="session_lifetime" value="Tempo de Vida da Sessão (minutos)" />
                <x-admin.input id="session_lifetime" name="session_lifetime" type="number" min="5" max="1440" class="mt-1 block w-full"
                               :value="old('session_lifetime', $configuracoes['session_lifetime'])" required />
            </div>
            <div>
                <x-admin.label for="max_login_attempts" value="Máximo de Tentativas de Login" />
                <x-admin.input id="max_login_attempts" name="max_login_attempts" type="number" min="3" max="10" class="mt-1 block w-full"
                               :value="old('max_login_attempts', $configuracoes['max_login_attempts'])" required />
            </div>
        </div>
    </x-admin.card>

    <!-- Bloco de Proteções Gerais -->
     <x-admin.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Proteções Gerais</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Ative proteções essenciais para a segurança da aplicação.</p>
        </x-slot>
        <div class="space-y-4">
            <x-admin.toggle-switch name="force_ssl" x-model="force_ssl">
                Forçar HTTPS
                <x-slot name="description">
                    Redireciona todo o tráfego HTTP para HTTPS. Requer um certificado SSL válido no servidor.
                </x-slot>
            </x-admin.toggle-switch>
        </div>
    </x-admin.card>

    <!-- Bloco de Autenticação de Dois Fatores (2FA) -->
    <x-admin.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Autenticação de Dois Fatores (2FA)</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Adicione uma camada extra de segurança ao login dos usuários.</p>
        </x-slot>
        <div class="space-y-6">
            <x-admin.toggle-switch name="enable_2fa" x-model="enable_2fa">
                Ativar 2FA
            </x-admin.toggle-switch>

            <div x-show="enable_2fa" x-transition class="space-y-6 pl-6 border-l-2 border-blue-200 dark:border-gray-700">
                <div>
                    <x-admin.label for="password_min_length" value="Comprimento Mínimo da Senha" />
                    <x-admin.input id="password_min_length" name="password_min_length" type="number" min="6" max="20" class="mt-1 block w-full"
                                   :value="old('password_min_length', $configuracoes['password_min_length'])" required />
                </div>
                <div>
                    <x-admin.toggle-switch name="password_require_special" :checked="old('password_require_special', $configuracoes['password_require_special'] ?? false)">
                        Exigir Caracteres Especiais na Senha
                    </x-admin.toggle-switch>
                </div>
            </div>
        </div>
    </x-admin.card>
</div>