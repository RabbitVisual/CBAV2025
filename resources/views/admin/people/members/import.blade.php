@extends('layouts.admin')

@section('title', 'Importar Membros')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Cabeçalho com Glassmorphism -->
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border border-white/20 dark:border-gray-700/30 rounded-2xl p-6 mb-8 shadow-xl">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div class="flex items-center space-x-3">
                <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl">
                    <i class="fas fa-upload text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent">{{ __('Importar Membros') }}</h1>
                    <p class="text-gray-600 dark:text-gray-300 mt-1">{{ __('Importe membros em lote usando planilhas') }}</p>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('Formatos suportados: .xlsx, .xls, .csv') }}</p>
                </div>
            </div>
            <a href="{{ route('admin.people.members.index') }}" 
               class="inline-flex items-center px-4 py-2.5 bg-gray-600 dark:bg-gray-700 hover:bg-gray-700 dark:hover:bg-gray-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                <i class="fas fa-arrow-left mr-2"></i>{{ __('Voltar') }}
            </a>
        </div>
    </div>

    <!-- Formulário de Upload -->
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border border-white/20 dark:border-gray-700/30 rounded-2xl p-6 mb-8 shadow-xl">
        <div class="flex items-center mb-6">
            <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg mr-3">
                <i class="fas fa-cloud-upload-alt text-blue-600 dark:text-blue-400"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Upload da Planilha') }}</h3>
        </div>
        
        <form action="{{ route('admin.people.members.import.process') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center hover:border-blue-400 dark:hover:border-blue-500 transition-all duration-200 bg-gray-50/50 dark:bg-gray-900/30" id="uploadArea">
                <div class="upload-content">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-cloud-upload-alt text-white text-3xl"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">{{ __('Clique ou arraste sua planilha aqui') }}</h4>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">{{ __('Ou clique no botão abaixo para selecionar') }}</p>
                    
                    <input type="file" name="file" id="fileInput" class="hidden" accept=".xlsx,.xls,.csv" required>
                    
                    <button type="button" onclick="document.getElementById('fileInput').click()" 
                            class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-xl transition-all duration-200 inline-flex items-center shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-folder-open mr-2"></i>
                        {{ __('Selecionar Arquivo') }}
                    </button>
                    
                    <div id="fileInfo" class="mt-4 hidden">
                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4">
                            <div class="flex items-center">
                                <i class="fas fa-file-excel text-green-600 dark:text-green-400 text-2xl mr-3"></i>
                                <div>
                                    <p class="font-semibold text-green-800 dark:text-green-300" id="fileName"></p>
                                    <p class="text-green-600 dark:text-green-400 text-sm" id="fileSize"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            @error('file')
                <div class="mt-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
                    <p class="text-red-600 dark:text-red-400 flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ $message }}
                    </p>
                </div>
            @enderror
            
            <div class="flex justify-end mt-6">
                <button type="submit" id="submitBtn" disabled
                        class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-3 rounded-xl transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-upload mr-2"></i>
                    {{ __('Importar Membros') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Template para Download -->
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border border-white/20 dark:border-gray-700/30 rounded-2xl p-6 mb-8 shadow-xl">
        <div class="flex items-center mb-6">
            <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg mr-3">
                <i class="fas fa-file-excel text-green-600 dark:text-green-400"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Template de Importação') }}</h3>
        </div>
        
        <div class="flex items-start space-x-4">
            <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 dark:from-green-900/30 dark:to-green-800/30 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                <i class="fas fa-file-excel text-green-600 dark:text-green-400 text-xl"></i>
            </div>
            <div class="flex-1">
                <h4 class="font-semibold text-gray-800 dark:text-white mb-2">{{ __('Download do Template') }}</h4>
                <p class="text-gray-600 dark:text-gray-400 mb-4">{{ __('Baixe o template com o formato correto para importação de membros') }}</p>
                <a href="#" 
                   class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-3 rounded-xl transition-all duration-200 inline-flex items-center shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-download mr-2"></i>
                    {{ __('Baixar Template') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Instruções -->
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border border-white/20 dark:border-gray-700/30 rounded-2xl p-6 shadow-xl">
        <div class="flex items-center mb-6">
            <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg mr-3">
                <i class="fas fa-info-circle text-purple-600 dark:text-purple-400"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Instruções de Importação') }}</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <h4 class="font-semibold text-gray-800 dark:text-white flex items-center">
                    <i class="fas fa-list-ol mr-2 text-blue-500 dark:text-blue-400"></i>
                    {{ __('Formato da Planilha') }}
                </h4>
                <ul class="space-y-3 text-gray-600 dark:text-gray-400">
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 dark:text-green-400 mr-2 mt-1 flex-shrink-0"></i>
                        <span>{{ __('Use o template fornecido') }}</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 dark:text-green-400 mr-2 mt-1 flex-shrink-0"></i>
                        <span>{{ __('Mantenha os nomes das colunas') }}</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 dark:text-green-400 mr-2 mt-1 flex-shrink-0"></i>
                        <span>{{ __('Não deixe campos obrigatórios vazios') }}</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 dark:text-green-400 mr-2 mt-1 flex-shrink-0"></i>
                        <span>{{ __('Use formato de data dd/mm/aaaa') }}</span>
                    </li>
                </ul>
            </div>
            
            <div class="space-y-4">
                <h4 class="font-semibold text-gray-800 dark:text-white flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2 text-orange-500 dark:text-orange-400"></i>
                    {{ __('Campos Obrigatórios') }}
                </h4>
                <ul class="space-y-3 text-gray-600 dark:text-gray-400">
                    <li class="flex items-start">
                        <i class="fas fa-asterisk text-red-500 dark:text-red-400 mr-2 mt-1 text-xs flex-shrink-0"></i>
                        <span>{{ __('Nome completo') }}</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-asterisk text-red-500 dark:text-red-400 mr-2 mt-1 text-xs flex-shrink-0"></i>
                        <span>{{ __('Email') }}</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-asterisk text-red-500 dark:text-red-400 mr-2 mt-1 text-xs flex-shrink-0"></i>
                        <span>{{ __('Data de nascimento') }}</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-asterisk text-red-500 dark:text-red-400 mr-2 mt-1 text-xs flex-shrink-0"></i>
                        <span>{{ __('Telefone') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('fileInput');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const submitBtn = document.getElementById('submitBtn');

    // Drag and drop functionality
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('border-blue-400', 'bg-blue-50');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(files[0]);
        }
    });

    // Click to upload
    uploadArea.addEventListener('click', function() {
        fileInput.click();
    });

    // File input change
    fileInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            handleFileSelect(e.target.files[0]);
        }
    });

    function handleFileSelect(file) {
        // Validate file type
        const allowedTypes = [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-excel',
            'text/csv'
        ];
        
        if (!allowedTypes.includes(file.type)) {
            alert('Tipo de arquivo não suportado. Use .xlsx, .xls ou .csv');
            return;
        }

        // Show file info
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        fileInfo.classList.remove('hidden');
        submitBtn.disabled = false;
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
});
</script>
@endpush
@endsection