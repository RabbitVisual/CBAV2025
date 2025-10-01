{{-- Componente da Aba Email - Configurações do Sistema --}}
<div id="email">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h5 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                <i class="fas fa-envelope mr-2"></i>Configurações de Email
            </h5>
            <p class="text-gray-600 dark:text-gray-400 text-sm">Configure o servidor SMTP para envio de emails do sistema, notificações e comunicações</p>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.system.settings.update') }}" method="POST" id="form-email">
                @csrf
                @method('PUT')
                <input type="hidden" name="active_tab" value="email">

                {{-- Configurações SMTP --}}
                <div class="mb-8">
                    <div class="mb-6">
                        <h6 class="text-base font-semibold text-blue-600 dark:text-blue-400 mb-4">
                            <i class="fas fa-server mr-2"></i>Servidor SMTP
                        </h6>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="mail_host" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Servidor SMTP *</label>
                            <input type="text" id="mail_host" name="mail_host" 
                                   value="{{ old('mail_host', $configuracoes['mail_host'] ?? '') }}" 
                                   placeholder="smtp.gmail.com" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white @error('mail_host') border-red-500 @enderror">
                            @error('mail_host')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-sm mt-1">Ex: smtp.gmail.com, smtp.outlook.com, smtp.yahoo.com</p>
                        </div>

                        <div>
                            <label for="mail_port" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Porta *</label>
                            <select id="mail_port" name="mail_port" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white @error('mail_port') border-red-500 @enderror">
                                <option value="">Selecione a porta</option>
                                <option value="25" {{ old('mail_port', $configuracoes['mail_port'] ?? '') == '25' ? 'selected' : '' }}>25 (Padrão - sem criptografia)</option>
                                <option value="465" {{ old('mail_port', $configuracoes['mail_port'] ?? '') == '465' ? 'selected' : '' }}>465 (SSL)</option>
                                <option value="587" {{ old('mail_port', $configuracoes['mail_port'] ?? '') == '587' ? 'selected' : '' }}>587 (TLS) - Recomendado</option>
                                <option value="2525" {{ old('mail_port', $configuracoes['mail_port'] ?? '') == '2525' ? 'selected' : '' }}>2525 (TLS Alternativo)</option>
                            </select>
                            @error('mail_port')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="mail_username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Usuário/Email *</label>
                            <input type="email" id="mail_username" name="mail_username" 
                                   value="{{ old('mail_username', $configuracoes['mail_username'] ?? '') }}" 
                                   placeholder="seu-email@gmail.com" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white @error('mail_username') border-red-500 @enderror">
                            @error('mail_username')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="mail_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Senha *</label>
                            <div class="flex">
                                <input type="password" id="mail_password" name="mail_password" 
                                       value="{{ old('mail_password', $configuracoes['mail_password'] ?? '') }}" 
                                       placeholder="Senha ou App Password" required
                                       class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-l-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white @error('mail_password') border-red-500 @enderror">
                                <button class="px-3 py-2 border border-l-0 border-gray-300 dark:border-gray-600 rounded-r-lg bg-gray-50 dark:bg-gray-600 hover:bg-gray-100 dark:hover:bg-gray-500 text-gray-600 dark:text-gray-300" type="button" id="togglePassword">
                                    <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                </button>
                            </div>
                            @error('mail_password')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-sm mt-1">Para Gmail, use App Password em vez da senha normal</p>
                        </div>

                        <div>
                            <label for="mail_encryption" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Criptografia *</label>
                            <select id="mail_encryption" name="mail_encryption" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white @error('mail_encryption') border-red-500 @enderror">
                                <option value="">Selecione a criptografia</option>
                                <option value="tls" {{ old('mail_encryption', $configuracoes['mail_encryption'] ?? '') === 'tls' ? 'selected' : '' }}>TLS (Recomendado)</option>
                                <option value="ssl" {{ old('mail_encryption', $configuracoes['mail_encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                                <option value="none" {{ old('mail_encryption', $configuracoes['mail_encryption'] ?? '') === 'none' ? 'selected' : '' }}>Nenhuma</option>
                            </select>
                            @error('mail_encryption')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="mail_from_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nome do Remetente *</label>
                            <input type="text" id="mail_from_name" name="mail_from_name" 
                                   value="{{ old('mail_from_name', $configuracoes['mail_from_name'] ?? config('app.name')) }}" 
                                   placeholder="Nome da Empresa" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white @error('mail_from_name') border-red-500 @enderror">
                            @error('mail_from_name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-sm mt-1">Nome que aparecerá como remetente dos emails</p>
                        </div>
                </div>

                {{-- Configurações Avançadas --}}
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                        <i class="fas fa-cogs mr-2"></i>Configurações Avançadas
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="mail_timeout" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Timeout (segundos)</label>
                            <input type="number" id="mail_timeout" name="mail_timeout" 
                                   value="{{ old('mail_timeout', $configuracoes['mail_timeout'] ?? '30') }}" 
                                   min="10" max="120"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white @error('mail_timeout') border-red-500 @enderror">
                            @error('mail_timeout')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-sm mt-1">Tempo limite para conexão SMTP (10-120 segundos)</p>
                        </div>

                        <div>
                            <div class="flex items-center mt-6">
                                <input type="checkbox" id="mail_verify_peer" name="mail_verify_peer" value="1" 
                                       {{ old('mail_verify_peer', $configuracoes['mail_verify_peer'] ?? true) ? 'checked' : '' }}
                                       class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="mail_verify_peer" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Verificar certificado SSL
                                </label>
                            </div>
                            <p class="text-gray-500 text-sm mt-1">Desative apenas se houver problemas de certificado</p>
                        </div>
                    </div>
                </div>

                {{-- Teste de Configuração --}}
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                        <i class="fas fa-paper-plane mr-2"></i>Teste de Configuração
                    </h3>
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mt-0.5 mr-3"></i>
                            <p class="text-blue-800 dark:text-blue-200 text-sm">
                                Envie um email de teste para verificar se as configurações estão funcionando corretamente.
                            </p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <label for="test_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email para Teste</label>
                            <input type="email" id="test_email" 
                                   placeholder="email@exemplo.com" 
                                   value="{{ auth()->user()->email ?? '' }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white">
                            <p class="text-gray-500 text-sm mt-1">Email onde será enviado o teste</p>
                        </div>
                        
                        <div class="flex items-end">
                            <button type="button" id="btn-test-email"
                                    class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 dark:bg-blue-600 dark:hover:bg-blue-700">
                                <i class="fas fa-paper-plane mr-2"></i>Enviar Teste
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Informações Importantes --}}
                <div class="mt-8">
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                        <div class="flex items-start mb-4">
                            <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mt-0.5 mr-3"></i>
                            <h4 class="text-blue-800 dark:text-blue-200 font-medium">Configurações Populares:</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <h5 class="font-semibold text-blue-800 dark:text-blue-200 mb-2">Gmail:</h5>
                                <div class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                                    <p>Host: smtp.gmail.com</p>
                                    <p>Porta: 587</p>
                                    <p>Criptografia: TLS</p>
                                    <p class="italic">Use App Password</p>
                                </div>
                            </div>
                            <div>
                                <h5 class="font-semibold text-blue-800 dark:text-blue-200 mb-2">Outlook/Hotmail:</h5>
                                <div class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                                    <p>Host: smtp-mail.outlook.com</p>
                                    <p>Porta: 587</p>
                                    <p>Criptografia: TLS</p>
                                </div>
                            </div>
                            <div>
                                <h5 class="font-semibold text-blue-800 dark:text-blue-200 mb-2">Yahoo:</h5>
                                <div class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                                    <p>Host: smtp.mail.yahoo.com</p>
                                    <p>Porta: 587</p>
                                    <p>Criptografia: TLS</p>
                                    <p class="italic">Use App Password</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Botões de Ação --}}
                <div class="mt-8">
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="window.location.reload()"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-gray-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                            <i class="fas fa-times mr-2"></i>Cancelar
                        </button>
                        <button type="submit" id="submitEmailSettings"
                                class="px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-lg hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 dark:bg-primary-600 dark:hover:bg-primary-700">
                            <i class="fas fa-save mr-2"></i>Salvar Configurações
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Scripts para funcionalidades da aba Email --}}
<script>
// Toggle para mostrar/ocultar senha
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordField = document.getElementById('mail_password');
    const toggleIcon = document.getElementById('togglePasswordIcon');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
});

// Função para testar configurações de email
document.getElementById('btn-test-email').addEventListener('click', function() {
    const testEmail = document.getElementById('test_email').value;
    const button = this;
    
    if (!testEmail) {
        alert('Por favor, informe um email para teste.');
        return;
    }
    
    if (!validateEmail(testEmail)) {
        alert('Por favor, informe um email válido.');
        return;
    }
    
    // Verificar se as configurações básicas estão preenchidas
    const requiredFields = ['mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption'];
    let missingFields = [];
    
    requiredFields.forEach(field => {
        const value = document.getElementById(field).value;
        if (!value) {
            missingFields.push(field);
        }
    });
    
    if (missingFields.length > 0) {
        alert('Por favor, preencha todas as configurações SMTP antes de testar.');
        return;
    }
    
    // Desabilitar botão e mostrar loading
    button.disabled = true;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enviando...';
    
    // Preparar dados para envio
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    formData.append('test_email', testEmail);
    formData.append('mail_host', document.getElementById('mail_host').value);
    formData.append('mail_port', document.getElementById('mail_port').value);
    formData.append('mail_username', document.getElementById('mail_username').value);
    formData.append('mail_password', document.getElementById('mail_password').value);
    formData.append('mail_encryption', document.getElementById('mail_encryption').value);
    formData.append('mail_from_name', document.getElementById('mail_from_name').value);
    
    // Enviar requisição AJAX
    fetch('{{ route("admin.system.settings.test-email") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', 'Email de teste enviado com sucesso!', data.message);
        } else {
            showAlert('error', 'Erro ao enviar email de teste', data.message || 'Verifique as configurações e tente novamente.');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showAlert('error', 'Erro de conexão', 'Não foi possível conectar ao servidor. Tente novamente.');
    })
    .finally(() => {
        // Restaurar botão
        button.disabled = false;
        button.innerHTML = originalText;
    });
});

// Função para validar email
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Função para mostrar alertas
function showAlert(type, title, message) {
    // Remover alertas existentes
    const existingAlerts = document.querySelectorAll('.alert-test-email');
    existingAlerts.forEach(alert => alert.remove());
    
    // Criar novo alerta
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
    
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show alert-test-email" role="alert">
            <i class="fas ${iconClass} me-2"></i>
            <strong>${title}</strong><br>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Inserir alerta antes do formulário
    const form = document.getElementById('form-email');
    form.insertAdjacentHTML('beforebegin', alertHtml);
    
    // Auto-remover após 10 segundos
    setTimeout(() => {
        const alert = document.querySelector('.alert-test-email');
        if (alert) {
            alert.remove();
        }
    }, 10000);
}

// Validação do formulário
document.getElementById('form-email').addEventListener('submit', function(e) {
    const requiredFields = {
        'mail_host': 'Servidor SMTP',
        'mail_port': 'Porta',
        'mail_username': 'Usuário/Email',
        'mail_password': 'Senha',
        'mail_encryption': 'Criptografia',
        'mail_from_name': 'Nome do Remetente'
    };
    
    let errors = [];
    
    Object.keys(requiredFields).forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (!field.value.trim()) {
            errors.push(requiredFields[fieldId]);
            field.classList.add('is-invalid');
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    if (errors.length > 0) {
        e.preventDefault();
        alert('Por favor, preencha os seguintes campos obrigatórios:\n\n' + errors.join('\n'));
        return false;
    }
    
    // Validar email do usuário
    const username = document.getElementById('mail_username').value;
    if (!validateEmail(username)) {
        e.preventDefault();
        alert('Por favor, informe um email válido no campo Usuário/Email.');
        document.getElementById('mail_username').focus();
        return false;
    }
});

// Auto-completar configurações baseadas no provedor
document.getElementById('mail_username').addEventListener('blur', function() {
    const email = this.value.toLowerCase();
    const hostField = document.getElementById('mail_host');
    const portField = document.getElementById('mail_port');
    const encryptionField = document.getElementById('mail_encryption');
    
    // Só preencher se os campos estiverem vazios
    if (!hostField.value) {
        if (email.includes('@gmail.com')) {
            hostField.value = 'smtp.gmail.com';
            portField.value = '587';
            encryptionField.value = 'tls';
        } else if (email.includes('@outlook.com') || email.includes('@hotmail.com') || email.includes('@live.com')) {
            hostField.value = 'smtp-mail.outlook.com';
            portField.value = '587';
            encryptionField.value = 'tls';
        } else if (email.includes('@yahoo.com')) {
            hostField.value = 'smtp.mail.yahoo.com';
            portField.value = '587';
            encryptionField.value = 'tls';
        }
    }
});
</script>