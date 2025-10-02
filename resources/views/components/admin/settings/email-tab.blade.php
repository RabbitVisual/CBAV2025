@props(['configuracoes'])

<div x-show="activeTab === 'email'" class="space-y-8"
     x-data="{
        testEmail: '{{ auth()->user()->email }}',
        testing: false,
        testResult: null,
        testMessage: ''
     }">

    <!-- Bloco de Configuração SMTP -->
    <x-admin.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Servidor SMTP</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Configure o servidor para envio de emails do sistema.</p>
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-admin.label for="mail_host" value="Servidor SMTP (Host) *" />
                <x-admin.input id="mail_host" name="mail_host" type="text" class="mt-1 block w-full"
                               :value="old('mail_host', $configuracoes['mail_host'])" required placeholder="smtp.example.com" />
            </div>
            <div>
                <x-admin.label for="mail_port" value="Porta *" />
                <x-admin.input id="mail_port" name="mail_port" type="number" class="mt-1 block w-full"
                               :value="old('mail_port', $configuracoes['mail_port'])" required placeholder="587" />
            </div>
            <div>
                <x-admin.label for="mail_username" value="Usuário (Email) *" />
                <x-admin.input id="mail_username" name="mail_username" type="email" class="mt-1 block w-full"
                               :value="old('mail_username', $configuracoes['mail_username'])" required placeholder="seu_email@example.com" />
            </div>
            <div x-data="{ show: false }">
                <x-admin.label for="mail_password" value="Senha *" />
                <div class="relative mt-1">
                    <x-admin.input id="mail_password" name="mail_password" ::type="show ? 'text' : 'password'" class="block w-full pr-10"
                                   :value="old('mail_password', $configuracoes['mail_password'])" required placeholder="Sua senha ou App Password" />
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500">
                        <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
            </div>
            <div>
                <x-admin.label for="mail_encryption" value="Criptografia *" />
                <x-admin.select id="mail_encryption" name="mail_encryption" class="mt-1 block w-full">
                    <option value="tls" @selected(old('mail_encryption', $configuracoes['mail_encryption']) === 'tls')>TLS</option>
                    <option value="ssl" @selected(old('mail_encryption', $configuracoes['mail_encryption']) === 'ssl')>SSL</option>
                    <option value="none" @selected(old('mail_encryption', $configuracoes['mail_encryption']) === 'none')>Nenhuma</option>
                </x-admin.select>
            </div>
            <div>
                <x-admin.label for="mail_from_address" value="Email do Remetente *" />
                <x-admin.input id="mail_from_address" name="mail_from_address" type="email" class="mt-1 block w-full"
                               :value="old('mail_from_address', $configuracoes['mail_from_address'])" required placeholder="nao-responda@example.com" />
            </div>
            <div class="md:col-span-2">
                <x-admin.label for="mail_from_name" value="Nome do Remetente *" />
                <x-admin.input id="mail_from_name" name="mail_from_name" type="text" class="mt-1 block w-full"
                               :value="old('mail_from_name', $configuracoes['mail_from_name'])" required :placeholder="$configuracoes['app_name']" />
            </div>
        </div>
    </x-admin.card>

    <!-- Bloco de Teste de Email -->
    <x-admin.card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Teste de Envio</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Verifique se suas configurações SMTP estão corretas.</p>
        </x-slot>

        <div class="flex items-end gap-4">
            <div class="flex-grow">
                <x-admin.label for="test_email_input" value="Enviar email de teste para:" />
                <x-admin.input id="test_email_input" type="email" x-model="testEmail" class="mt-1 block w-full" />
            </div>
            <x-admin.button-secondary type="button" @click="sendTestEmail()" :disabled="testing">
                <span x-show="!testing"><i class="fas fa-paper-plane mr-2"></i>Enviar Teste</span>
                <span x-show="testing"><i class="fas fa-spinner fa-spin mr-2"></i>Enviando...</span>
            </x-admin.button-secondary>
        </div>

        <template x-if="testResult">
            <div class="mt-4 p-4 rounded-md"
                 :class="{ 'bg-green-100 dark:bg-green-800/50 text-green-800 dark:text-green-200': testResult === 'success', 'bg-red-100 dark:bg-red-800/50 text-red-800 dark:text-red-200': testResult === 'error' }">
                <p class="text-sm" x-text="testMessage"></p>
            </div>
        </template>
    </x-admin.card>
</div>

<script>
    function sendTestEmail() {
        this.testing = true;
        this.testResult = null;
        this.testMessage = '';

        fetch('{{ route("admin.system.settings.test-email") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                test_email: this.testEmail
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                this.testResult = 'success';
            } else {
                this.testResult = 'error';
            }
            this.testMessage = data.message;
        })
        .catch(err => {
            this.testResult = 'error';
            this.testMessage = 'Falha na conexão ao tentar enviar o email de teste.';
            console.error(err);
        })
        .finally(() => {
            this.testing = false;
        });
    }
</script>