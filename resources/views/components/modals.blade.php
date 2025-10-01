<!-- Modais do Sistema -->

<!-- Modal de Confirmação -->
<div id="confirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-300">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white transform transition-all duration-300 scale-95 opacity-0" id="confirmModalContent">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4" id="confirmModalTitle">
                Confirmação
            </h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500" id="confirmModalMessage">
                    Tem certeza que deseja continuar?
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="confirmModalCancel" 
                        class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors duration-200">
                    Cancelar
                </button>
                <button id="confirmModalConfirm" 
                        class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md w-24 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 transition-colors duration-200">
                    Confirmar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Sucesso -->
<div id="successModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-300">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white transform transition-all duration-300 scale-95 opacity-0" id="successModalContent">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                <i class="fas fa-check text-green-600 text-xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">
                Sucesso!
            </h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500" id="successModalMessage">
                    Operação realizada com sucesso!
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <button onclick="closeSuccessModal()" 
                        class="px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md w-24 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300 transition-colors duration-200">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Erro -->
<div id="errorModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-300">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white transform transition-all duration-300 scale-95 opacity-0" id="errorModalContent">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-times text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4" id="errorModalTitle">
                Erro
            </h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500" id="errorModalMessage">
                    Ocorreu um erro durante a operação.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <button onclick="closeErrorModal()" 
                        class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-24 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300 transition-colors duration-200">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Informação -->
<div id="infoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-300">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white transform transition-all duration-300 scale-95 opacity-0" id="infoModalContent">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                <i class="fas fa-info text-blue-600 text-xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4" id="infoModalTitle">
                Informação
            </h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500" id="infoModalMessage">
                    Informação importante.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <button onclick="closeInfoModal()" 
                        class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md w-24 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 transition-colors duration-200">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Validação de Documento -->
<div id="documentValidationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-300">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white transform transition-all duration-300 scale-95 opacity-0" id="documentValidationModalContent">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                <i class="fas fa-file-check text-green-600 text-xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4" id="documentValidationModalTitle">
                Documento Válido
            </h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500" id="documentValidationModalMessage">
                    O documento foi validado com sucesso!
                </p>
                <div class="mt-3 text-xs text-gray-400" id="documentValidationDetails">
                    <!-- Detalhes da validação serão inseridos aqui -->
                </div>
            </div>
            <div class="items-center px-4 py-3">
                <button onclick="closeDocumentValidationModal()" 
                        class="px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md w-24 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300 transition-colors duration-200">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Aviso de Documento -->
<div id="documentWarningModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-300">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white transform transition-all duration-300 scale-95 opacity-0" id="documentWarningModalContent">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4" id="documentWarningModalTitle">
                Aviso
            </h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500" id="documentWarningModalMessage">
                    Atenção ao documento.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <button onclick="closeDocumentWarningModal()" 
                        class="px-4 py-2 bg-yellow-600 text-white text-base font-medium rounded-md w-24 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-300 transition-colors duration-200">
                    Entendi
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notifications -->
<div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

<script>
// Funções para gerenciar modais
window.confirmCallback = window.confirmCallback || null;

// Função para animar entrada dos modais
function animateModalIn(modalId) {
    const modal = document.getElementById(modalId);
    const content = document.getElementById(modalId + 'Content');
    
    if (modal && content) {
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
}

// Função para animar saída dos modais
function animateModalOut(modalId, callback = null) {
    const modal = document.getElementById(modalId);
    const content = document.getElementById(modalId + 'Content');
    
    if (modal && content) {
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            if (callback) callback();
        }, 300);
    }
}

function showConfirmModal(title, message, callback) {
    document.getElementById('confirmModalTitle').textContent = title;
    document.getElementById('confirmModalMessage').textContent = message;
    animateModalIn('confirmModal');
    window.confirmCallback = callback;
}

function showSuccessModal(message) {
    document.getElementById('successModalMessage').textContent = message;
    animateModalIn('successModal');
}

function showErrorModal(title, message) {
    if (title) {
        document.getElementById('errorModalTitle').textContent = title;
    }
    document.getElementById('errorModalMessage').textContent = message;
    animateModalIn('errorModal');
}

function showInfoModal(title, message) {
    if (title) {
        document.getElementById('infoModalTitle').textContent = title;
    }
    document.getElementById('infoModalMessage').textContent = message;
    animateModalIn('infoModal');
}

// Funções específicas para documentos
function showDocumentValidationModal(title, message, details = null) {
    if (title) {
        document.getElementById('documentValidationModalTitle').textContent = title;
    }
    document.getElementById('documentValidationModalMessage').textContent = message;
    
    if (details) {
        document.getElementById('documentValidationDetails').innerHTML = details;
        document.getElementById('documentValidationDetails').classList.remove('hidden');
    } else {
        document.getElementById('documentValidationDetails').classList.add('hidden');
    }
    
    animateModalIn('documentValidationModal');
}

function showDocumentWarningModal(title, message) {
    if (title) {
        document.getElementById('documentWarningModalTitle').textContent = title;
    }
    document.getElementById('documentWarningModalMessage').textContent = message;
    animateModalIn('documentWarningModal');
}

function closeSuccessModal() {
    animateModalOut('successModal');
}

function closeErrorModal() {
    animateModalOut('errorModal');
}

function closeInfoModal() {
    animateModalOut('infoModal');
}

function closeDocumentValidationModal() {
    animateModalOut('documentValidationModal');
}

function closeDocumentWarningModal() {
    animateModalOut('documentWarningModal');
}

// Sistema de Toast Notifications
function showToast(message, type = 'info', duration = 5000) {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        warning: 'bg-yellow-500',
        info: 'bg-blue-500'
    };
    
    const icons = {
        success: 'fas fa-check',
        error: 'fas fa-times',
        warning: 'fas fa-exclamation-triangle',
        info: 'fas fa-info'
    };
    
    toast.className = `${colors[type]} text-white px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full opacity-0`;
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="${icons[type]} mr-3"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    container.appendChild(toast);
    
    // Animar entrada
    setTimeout(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
        toast.classList.add('translate-x-0', 'opacity-100');
    }, 10);
    
    // Auto-remover
    setTimeout(() => {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 300);
    }, duration);
}

// Event listeners para modais
document.addEventListener('DOMContentLoaded', function() {
    // Modal de confirmação
    document.getElementById('confirmModalCancel').addEventListener('click', function() {
        animateModalOut('confirmModal', () => {
            window.confirmCallback = null;
        });
    });
    
    document.getElementById('confirmModalConfirm').addEventListener('click', function() {
        animateModalOut('confirmModal', () => {
            if (window.confirmCallback) {
                window.confirmCallback();
                window.confirmCallback = null;
            }
        });
    });
    
    // Fechar modais clicando fora
    const modals = ['confirmModal', 'successModal', 'errorModal', 'infoModal', 'documentValidationModal', 'documentWarningModal'];
    
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    if (modalId === 'confirmModal') {
                        animateModalOut(modalId, () => {
                            window.confirmCallback = null;
                        });
                    } else {
                        animateModalOut(modalId);
                    }
                }
            });
        }
    });
    
    // Fechar modais com ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const visibleModal = modals.find(modalId => {
                const modal = document.getElementById(modalId);
                return modal && !modal.classList.contains('hidden');
            });
            
            if (visibleModal) {
                if (visibleModal === 'confirmModal') {
                    animateModalOut(visibleModal, () => {
                        window.confirmCallback = null;
                    });
                } else {
                    animateModalOut(visibleModal);
                }
            }
        }
    });
});

// Funções utilitárias para documentos
function validateDocument(documentId) {
    // Simular validação de documento
    showDocumentValidationModal(
        'Documento Válido',
        'O documento foi validado com sucesso e está pronto para uso.',
        '<div class="mt-2 text-left"><strong>Detalhes:</strong><br>• Código: DOC-' + documentId + '<br>• Status: Aprovado<br>• Data: ' + new Date().toLocaleDateString('pt-BR') + '</div>'
    );
}

function showDocumentError(message) {
    showErrorModal('Erro no Documento', message);
}

function showDocumentInfo(message) {
    showInfoModal('Informação do Documento', message);
}

// Expor funções globalmente
window.showToast = showToast;
window.validateDocument = validateDocument;
window.showDocumentError = showDocumentError;
window.showDocumentInfo = showDocumentInfo;
window.showDocumentValidationModal = showDocumentValidationModal;
window.showDocumentWarningModal = showDocumentWarningModal;
</script> 