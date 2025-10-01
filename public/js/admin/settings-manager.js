/**
 * Settings Manager - Gerenciamento de Configurações do Sistema
 * Sistema de Gestão Financeira Pessoal - CBAV2025
 * 
 * Este arquivo contém todas as funcionalidades JavaScript para o gerenciamento
 * das configurações do sistema, incluindo navegação entre abas, validações,
 * e funcionalidades específicas de cada seção.
 */

class SettingsManager {
    constructor() {
        this.currentTab = 'geral';
        this.unsavedChanges = false;
        this.validationRules = {};
        this.init();
    }

    /**
     * Inicializa o gerenciador de configurações
     */
    init() {
        this.setupTabNavigation();
        this.setupFormValidation();
        this.setupUnsavedChangesDetection();
        this.setupGlobalEventListeners();
        this.loadTabFromUrl();
        
        console.log('Settings Manager initialized successfully');
    }

    /**
     * Configura a navegação entre abas
     */
    setupTabNavigation() {
        const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const targetTab = button.getAttribute('data-bs-target').replace('#', '');
                this.switchTab(targetTab, e);
            });
        });

        // Configurar navegação por teclado
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey && e.key >= '1' && e.key <= '5') {
                e.preventDefault();
                const tabIndex = parseInt(e.key) - 1;
                const tabs = ['geral', 'pagamento', 'email', 'seguranca', 'cache-backup'];
                if (tabs[tabIndex]) {
                    this.switchTab(tabs[tabIndex]);
                }
            }
        });
    }

    /**
     * Troca de aba com validação de mudanças não salvas
     */
    switchTab(targetTab, event = null) {
        if (this.unsavedChanges) {
            const confirmSwitch = confirm(
                'Você tem alterações não salvas. Deseja continuar sem salvar?\n\n' +
                'Clique em "OK" para continuar ou "Cancelar" para permanecer na aba atual.'
            );
            
            if (!confirmSwitch) {
                if (event) {
                    event.preventDefault();
                }
                return false;
            }
        }

        this.currentTab = targetTab;
        this.unsavedChanges = false;
        this.updateUrlHash(targetTab);
        this.highlightActiveTab(targetTab);
        
        // Executar callbacks específicos da aba
        this.executeTabCallback(targetTab);
        
        return true;
    }

    /**
     * Carrega aba baseada na URL
     */
    loadTabFromUrl() {
        const hash = window.location.hash.replace('#', '');
        const validTabs = ['geral', 'pagamento', 'email', 'seguranca', 'cache-backup'];
        
        if (hash && validTabs.includes(hash)) {
            this.currentTab = hash;
            this.activateTab(hash);
        }
    }

    /**
     * Ativa uma aba programaticamente
     */
    activateTab(tabName) {
        const tabButton = document.querySelector(`[data-bs-target="#${tabName}"]`);
        if (tabButton) {
            const tab = new bootstrap.Tab(tabButton);
            tab.show();
        }
    }

    /**
     * Atualiza o hash da URL
     */
    updateUrlHash(tabName) {
        if (history.replaceState) {
            history.replaceState(null, null, `#${tabName}`);
        } else {
            window.location.hash = tabName;
        }
    }

    /**
     * Destaca a aba ativa visualmente
     */
    highlightActiveTab(tabName) {
        // Remove destaque de todas as abas
        document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.remove('active');
        });
        
        // Adiciona destaque à aba ativa
        const activeTab = document.querySelector(`[data-bs-target="#${tabName}"]`);
        if (activeTab) {
            activeTab.classList.add('active');
        }
    }

    /**
     * Executa callbacks específicos quando uma aba é ativada
     */
    executeTabCallback(tabName) {
        const callbacks = {
            'geral': () => this.onGeneralTabActivated(),
            'pagamento': () => this.onPaymentTabActivated(),
            'email': () => this.onEmailTabActivated(),
            'seguranca': () => this.onSecurityTabActivated(),
            'cache-backup': () => this.onCacheBackupTabActivated()
        };

        if (callbacks[tabName]) {
            callbacks[tabName]();
        }
    }

    /**
     * Configura validação de formulários
     */
    setupFormValidation() {
        const forms = document.querySelectorAll('form[id^="form-"]');
        
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!this.validateForm(form)) {
                    e.preventDefault();
                    return false;
                }
                
                this.showSubmitLoader(form);
            });

            // Validação em tempo real
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('blur', () => {
                    this.validateField(input);
                });
                
                input.addEventListener('input', () => {
                    this.markAsChanged();
                    this.clearFieldError(input);
                });
            });
        });
    }

    /**
     * Valida um formulário completo
     */
    validateForm(form) {
        let isValid = true;
        const errors = [];
        
        // Validação de campos obrigatórios
        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                this.showFieldError(field, 'Este campo é obrigatório');
                errors.push(`${this.getFieldLabel(field)} é obrigatório`);
                isValid = false;
            }
        });

        // Validação de emails
        const emailFields = form.querySelectorAll('input[type="email"]');
        emailFields.forEach(field => {
            if (field.value && !this.isValidEmail(field.value)) {
                this.showFieldError(field, 'Email inválido');
                errors.push(`${this.getFieldLabel(field)} deve ser um email válido`);
                isValid = false;
            }
        });

        // Validação de URLs
        const urlFields = form.querySelectorAll('input[type="url"]');
        urlFields.forEach(field => {
            if (field.value && !this.isValidUrl(field.value)) {
                this.showFieldError(field, 'URL inválida');
                errors.push(`${this.getFieldLabel(field)} deve ser uma URL válida`);
                isValid = false;
            }
        });

        // Validação de números
        const numberFields = form.querySelectorAll('input[type="number"]');
        numberFields.forEach(field => {
            if (field.value) {
                const min = field.getAttribute('min');
                const max = field.getAttribute('max');
                const value = parseFloat(field.value);
                
                if (min && value < parseFloat(min)) {
                    this.showFieldError(field, `Valor mínimo: ${min}`);
                    errors.push(`${this.getFieldLabel(field)} deve ser pelo menos ${min}`);
                    isValid = false;
                }
                
                if (max && value > parseFloat(max)) {
                    this.showFieldError(field, `Valor máximo: ${max}`);
                    errors.push(`${this.getFieldLabel(field)} deve ser no máximo ${max}`);
                    isValid = false;
                }
            }
        });

        // Mostrar resumo de erros se houver
        if (!isValid && errors.length > 0) {
            this.showValidationSummary(errors);
        }

        return isValid;
    }

    /**
     * Valida um campo específico
     */
    validateField(field) {
        this.clearFieldError(field);
        
        if (field.hasAttribute('required') && !field.value.trim()) {
            this.showFieldError(field, 'Este campo é obrigatório');
            return false;
        }
        
        if (field.type === 'email' && field.value && !this.isValidEmail(field.value)) {
            this.showFieldError(field, 'Email inválido');
            return false;
        }
        
        if (field.type === 'url' && field.value && !this.isValidUrl(field.value)) {
            this.showFieldError(field, 'URL inválida');
            return false;
        }
        
        return true;
    }

    /**
     * Mostra erro em um campo
     */
    showFieldError(field, message) {
        field.classList.add('is-invalid');
        
        let feedback = field.parentNode.querySelector('.invalid-feedback');
        if (!feedback) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            field.parentNode.appendChild(feedback);
        }
        
        feedback.textContent = message;
    }

    /**
     * Remove erro de um campo
     */
    clearFieldError(field) {
        field.classList.remove('is-invalid');
        
        const feedback = field.parentNode.querySelector('.invalid-feedback');
        if (feedback) {
            feedback.textContent = '';
        }
    }

    /**
     * Obtém o label de um campo
     */
    getFieldLabel(field) {
        const label = document.querySelector(`label[for="${field.id}"]`);
        return label ? label.textContent.replace('*', '').trim() : field.name || 'Campo';
    }

    /**
     * Mostra resumo de erros de validação
     */
    showValidationSummary(errors) {
        const message = 'Por favor, corrija os seguintes erros:\n\n' + errors.join('\n');
        alert(message);
    }

    /**
     * Configura detecção de mudanças não salvas
     */
    setupUnsavedChangesDetection() {
        // Detectar mudanças em formulários
        const forms = document.querySelectorAll('form[id^="form-"]');
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('change', () => this.markAsChanged());
            });
        });

        // Aviso ao sair da página
        window.addEventListener('beforeunload', (e) => {
            if (this.unsavedChanges) {
                e.preventDefault();
                e.returnValue = 'Você tem alterações não salvas. Deseja realmente sair?';
                return e.returnValue;
            }
        });
    }

    /**
     * Marca como tendo mudanças não salvas
     */
    markAsChanged() {
        this.unsavedChanges = true;
        this.updateSaveButtonState();
    }

    /**
     * Marca como salvo
     */
    markAsSaved() {
        this.unsavedChanges = false;
        this.updateSaveButtonState();
    }

    /**
     * Atualiza estado dos botões de salvar
     */
    updateSaveButtonState() {
        const saveButtons = document.querySelectorAll('button[type="submit"]');
        saveButtons.forEach(button => {
            if (this.unsavedChanges) {
                button.classList.add('btn-warning');
                button.classList.remove('btn-primary');
                if (!button.textContent.includes('*')) {
                    button.innerHTML = button.innerHTML.replace('Salvar', 'Salvar*');
                }
            } else {
                button.classList.remove('btn-warning');
                button.classList.add('btn-primary');
                button.innerHTML = button.innerHTML.replace('Salvar*', 'Salvar');
            }
        });
    }

    /**
     * Mostra loader no botão de submit
     */
    showSubmitLoader(form) {
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Salvando...';
            submitButton.disabled = true;
            
            // Restaurar após 5 segundos (fallback)
            setTimeout(() => {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }, 5000);
        }
    }

    /**
     * Configura listeners globais
     */
    setupGlobalEventListeners() {
        // Atalhos de teclado
        document.addEventListener('keydown', (e) => {
            // Ctrl+S para salvar
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                this.saveCurrentTab();
            }
            
            // Esc para cancelar
            if (e.key === 'Escape') {
                this.cancelChanges();
            }
        });

        // Auto-save a cada 5 minutos se houver mudanças
        setInterval(() => {
            if (this.unsavedChanges) {
                this.autoSave();
            }
        }, 300000); // 5 minutos
    }

    /**
     * Salva a aba atual
     */
    saveCurrentTab() {
        const currentForm = document.querySelector(`#form-${this.currentTab.replace('-', '-')}`);
        if (currentForm) {
            currentForm.dispatchEvent(new Event('submit', { cancelable: true }));
        }
    }

    /**
     * Cancela mudanças
     */
    cancelChanges() {
        if (this.unsavedChanges) {
            const confirmCancel = confirm('Descartar todas as alterações não salvas?');
            if (confirmCancel) {
                window.location.reload();
            }
        }
    }

    /**
     * Auto-save (salva automaticamente)
     */
    autoSave() {
        console.log('Auto-saving changes...');
        // Implementar auto-save se necessário
    }

    /**
     * Callbacks específicos das abas
     */
    onGeneralTabActivated() {
        console.log('General tab activated');
        // Lógica específica da aba geral
    }

    onPaymentTabActivated() {
        console.log('Payment tab activated');
        // Verificar status dos gateways
        this.checkPaymentGatewaysStatus();
    }

    onEmailTabActivated() {
        console.log('Email tab activated');
        // Verificar configuração SMTP
        this.checkEmailConfiguration();
    }

    onSecurityTabActivated() {
        console.log('Security tab activated');
        // Verificar configurações de segurança
        this.checkSecuritySettings();
    }

    onCacheBackupTabActivated() {
        console.log('Cache & Backup tab activated');
        // Verificar status do cache e backups
        this.checkCacheAndBackupStatus();
    }

    /**
     * Verifica status dos gateways de pagamento
     */
    checkPaymentGatewaysStatus() {
        // Implementar verificação de status dos gateways
        console.log('Checking payment gateways status...');
    }

    /**
     * Verifica configuração de email
     */
    checkEmailConfiguration() {
        // Implementar verificação de configuração SMTP
        console.log('Checking email configuration...');
    }

    /**
     * Verifica configurações de segurança
     */
    checkSecuritySettings() {
        // Implementar verificação de segurança
        console.log('Checking security settings...');
    }

    /**
     * Verifica status do cache e backup
     */
    checkCacheAndBackupStatus() {
        // Implementar verificação de cache e backup
        console.log('Checking cache and backup status...');
    }

    /**
     * Utilitários de validação
     */
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    isValidUrl(url) {
        try {
            new URL(url);
            return true;
        } catch {
            return false;
        }
    }

    /**
     * Utilitários de notificação
     */
    showNotification(message, type = 'info') {
        // Implementar sistema de notificações toast
        console.log(`${type.toUpperCase()}: ${message}`);
        
        // Por enquanto usar alert, mas pode ser substituído por toast
        if (type === 'error') {
            alert(`Erro: ${message}`);
        } else if (type === 'success') {
            alert(`Sucesso: ${message}`);
        } else {
            alert(message);
        }
    }

    /**
     * Utilitários de loading
     */
    showGlobalLoader() {
        // Implementar loader global
        console.log('Showing global loader...');
    }

    hideGlobalLoader() {
        // Implementar hide do loader global
        console.log('Hiding global loader...');
    }
}

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    // Verificar se estamos na página de configurações
    if (document.querySelector('.settings-page') || document.querySelector('#form-geral')) {
        window.settingsManager = new SettingsManager();
    }
});

// Exportar para uso global
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SettingsManager;
}