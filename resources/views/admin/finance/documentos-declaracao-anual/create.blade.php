@extends('layouts.admin')

@section('title', 'Novo Documento de Declaração Anual - Gestão Financeira')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Novo Documento de Declaração Anual</h1>
        <p class="text-gray-600">Crie um novo documento para declaração anual da igreja</p>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Informações do Documento</h3>
        </div>
        
        <form action="{{ route('admin.finance.documentos-declaracao-anual.store') }}" method="POST" enctype="multipart/form-data" class="p-6" id="formDocumento">
            @csrf
            
            <!-- Seleção da Igreja -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h4 class="text-sm font-medium text-gray-700 mb-3">Seleção da Igreja</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="igreja_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Igreja <span class="text-red-500">*</span>
                        </label>
                        <select name="igreja_id" id="igreja_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Selecione uma igreja</option>
                            @foreach($igrejas as $igreja)
                                <option value="{{ $igreja->id }}" 
                                        data-cnpj="{{ $igreja->cnpj }}"
                                        data-endereco="{{ $igreja->endereco_completo }}"
                                        data-pastor="{{ $igreja->pastor_responsavel }}"
                                        {{ old('igreja_id') == $igreja->id ? 'selected' : '' }}>
                                    {{ $igreja->nome }} - {{ $igreja->cidade }}/{{ $igreja->estado }}
                                </option>
                            @endforeach
                        </select>
                        @error('igreja_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="ano_exercicio" class="block text-sm font-medium text-gray-700 mb-2">
                            Ano Exercício <span class="text-red-500">*</span>
                        </label>
                        <select name="ano_exercicio" id="ano_exercicio" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Selecione o ano</option>
                            @for($ano = date('Y'); $ano >= 2020; $ano--)
                                <option value="{{ $ano }}" {{ old('ano_exercicio', $ano) == $ano ? 'selected' : '' }}>
                                    {{ $ano }}
                                </option>
                            @endfor
                        </select>
                        @error('ano_exercicio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Informações da Igreja Selecionada -->
                <div id="info-igreja" class="mt-3"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tipo de Documento -->
                <div>
                    <label for="tipo_documento" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de Documento <span class="text-red-500">*</span>
                    </label>
                    <select name="tipo_documento" id="tipo_documento" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecione o tipo</option>
                        @foreach(App\Models\DocumentoDeclaracaoAnual::TIPOS_DOCUMENTO as $key => $value)
                            <option value="{{ $key }}" {{ old('tipo_documento') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipo_documento')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Número do Documento -->
                <div>
                    <label for="numero_documento" class="block text-sm font-medium text-gray-700 mb-2">
                        Número do Documento <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="numero_documento" id="numero_documento" 
                           value="{{ old('numero_documento') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ex: 12345678901234">
                    @error('numero_documento')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Data de Emissão -->
                <div>
                    <label for="data_emissao" class="block text-sm font-medium text-gray-700 mb-2">
                        Data de Emissão <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="data_emissao" id="data_emissao" 
                           value="{{ old('data_emissao', date('Y-m-d')) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('data_emissao')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Data de Vencimento -->
                <div>
                    <label for="data_vencimento" class="block text-sm font-medium text-gray-700 mb-2">
                        Data de Vencimento
                    </label>
                    <input type="date" name="data_vencimento" id="data_vencimento" 
                           value="{{ old('data_vencimento') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('data_vencimento')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Valor Total -->
                <div>
                    <label for="valor_total" class="block text-sm font-medium text-gray-700 mb-2">
                        Valor Total <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">R$</span>
                        <input type="number" name="valor_total" id="valor_total" 
                               value="{{ old('valor_total') }}" step="0.01" min="0.01" required
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0,00">
                    </div>
                    @error('valor_total')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Valor de Doações -->
                <div>
                    <label for="valor_doacoes" class="block text-sm font-medium text-gray-700 mb-2">
                        Valor de Doações
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">R$</span>
                        <input type="number" name="valor_doacoes" id="valor_doacoes" 
                               value="{{ old('valor_doacoes', 0) }}" step="0.01" min="0"
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0,00">
                    </div>
                    @error('valor_doacoes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Valor de Dízimos -->
                <div>
                    <label for="valor_dizimos" class="block text-sm font-medium text-gray-700 mb-2">
                        Valor de Dízimos
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">R$</span>
                        <input type="number" name="valor_dizimos" id="valor_dizimos" 
                               value="{{ old('valor_dizimos', 0) }}" step="0.01" min="0"
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0,00">
                    </div>
                    @error('valor_dizimos')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Valor Outros -->
                <div>
                    <label for="valor_outros" class="block text-sm font-medium text-gray-700 mb-2">
                        Valor Outros
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">R$</span>
                        <input type="number" name="valor_outros" id="valor_outros" 
                               value="{{ old('valor_outros', 0) }}" step="0.01" min="0"
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0,00">
                    </div>
                    @error('valor_outros')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Arquivo Comprovante -->
                <div class="md:col-span-2">
                    <label for="arquivo_comprovante" class="block text-sm font-medium text-gray-700 mb-2">
                        Arquivo Comprovante
                    </label>
                    <input type="file" name="arquivo_comprovante" id="arquivo_comprovante"
                           accept=".pdf,.jpg,.jpeg,.png"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="mt-1 text-sm text-gray-500">
                        Formatos aceitos: PDF, JPG, JPEG, PNG (máximo 2MB)
                    </p>
                    @error('arquivo_comprovante')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Observações -->
                <div class="md:col-span-2">
                    <label for="observacoes" class="block text-sm font-medium text-gray-700 mb-2">
                        Observações
                    </label>
                    <textarea name="observacoes" id="observacoes" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Observações adicionais sobre o documento...">{{ old('observacoes') }}</textarea>
                    @error('observacoes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Botões -->
            <div class="flex justify-end space-x-4 mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.finance.documentos-declaracao-anual.index') }}" 
                   class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    Cancelar
                </a>
                <button type="submit" id="btnSalvar"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>Salvar Documento
                </button>
            </div>
        </form>
    </div>

    <!-- Informações Legais -->
    <div class="mt-6 bg-blue-50 rounded-lg p-6">
        <h4 class="text-lg font-medium text-blue-900 mb-4">
            <i class="fas fa-info-circle mr-2"></i>Informações Importantes
        </h4>
        <div class="text-sm text-blue-800 space-y-2">
            <p><strong>•</strong> Este documento será utilizado para declaração anual da igreja.</p>
            <p><strong>•</strong> O sistema gera automaticamente um protocolo da Receita Federal.</p>
            <p><strong>•</strong> O hash do documento garante a integridade e autenticidade.</p>
            <p><strong>•</strong> Documentos vencidos terão multa e juros calculados automaticamente.</p>
            <p><strong>•</strong> O código de barras segue o padrão FEBRABAN para pagamento.</p>
            <p><strong>•</strong> O certificado digital garante a autenticidade perante a Receita Federal.</p>
        </div>
    </div>
</div>

@include('components.modals')

@push('scripts')
<script>
// Carregar informações da igreja quando selecionada
document.getElementById('igreja_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const infoDiv = document.getElementById('info-igreja');
    
    if (selectedOption.value) {
        infoDiv.innerHTML = `
            <div class="p-3 bg-blue-50 rounded-lg">
                <p class="text-sm text-blue-800">
                    <strong>CNPJ:</strong> ${selectedOption.dataset.cnpj}<br>
                    <strong>Endereço:</strong> ${selectedOption.dataset.endereco}<br>
                    <strong>Pastor Responsável:</strong> ${selectedOption.dataset.pastor || 'Não informado'}
                </p>
            </div>
        `;
        
        showToast('Igreja selecionada com sucesso!', 'success');
    } else {
        infoDiv.innerHTML = '';
    }
});

// Validação do tipo de documento
document.getElementById('tipo_documento').addEventListener('change', function() {
    const tipo = this.value;
    const numeroInput = document.getElementById('numero_documento');
    
    // Limpar placeholder anterior
    numeroInput.placeholder = 'Número do documento';
    
    // Definir placeholder baseado no tipo
    switch(tipo) {
        case 'DECLARACAO_ANUAL':
            numeroInput.placeholder = 'Ex: 12345678901234 (14 dígitos)';
            break;
        case 'CERTIDAO_NEGATIVA':
            numeroInput.placeholder = 'Ex: 12345678901 (11 dígitos)';
            break;
        case 'COMPROVANTE_DOACOES':
            numeroInput.placeholder = 'Ex: 1234567890123 (13 dígitos)';
            break;
        default:
            numeroInput.placeholder = 'Ex: 12345678901234';
    }
    
    if (tipo) {
        showToast(`Tipo de documento selecionado: ${this.options[this.selectedIndex].text}`, 'info');
    }
});

// Formatação dos valores
document.querySelectorAll('input[type="number"]').forEach(input => {
    input.addEventListener('input', function() {
        let value = this.value.replace(/[^\d.,]/g, '');
        value = value.replace(',', '.');
        this.value = value;
    });
});

// Validação do formulário antes de enviar
document.getElementById('formDocumento').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validar campos obrigatórios
    const camposObrigatorios = [
        { id: 'igreja_id', nome: 'Igreja' },
        { id: 'ano_exercicio', nome: 'Ano Exercício' },
        { id: 'tipo_documento', nome: 'Tipo de Documento' },
        { id: 'numero_documento', nome: 'Número do Documento' },
        { id: 'data_emissao', nome: 'Data de Emissão' },
        { id: 'valor_total', nome: 'Valor Total' }
    ];
    
    let camposVazios = [];
    
    camposObrigatorios.forEach(campo => {
        const elemento = document.getElementById(campo.id);
        if (!elemento.value.trim()) {
            camposVazios.push(campo.nome);
            elemento.classList.add('border-red-500');
        } else {
            elemento.classList.remove('border-red-500');
        }
    });
    
    if (camposVazios.length > 0) {
        showErrorModal(
            'Campos Obrigatórios',
            `Por favor, preencha os seguintes campos:\n\n• ${camposVazios.join('\n• ')}`
        );
        return false;
    }
    
    // Validar valor total
    const valorTotal = parseFloat(document.getElementById('valor_total').value);
    if (valorTotal <= 0) {
        showErrorModal('Valor Inválido', 'O valor total deve ser maior que zero.');
        return false;
    }
    
    // Validar data de emissão
    const dataEmissao = new Date(document.getElementById('data_emissao').value);
    const hoje = new Date();
    if (dataEmissao > hoje) {
        showErrorModal('Data Inválida', 'A data de emissão não pode ser futura.');
        return false;
    }
    
    // Validar valores menores que o total
    const valorDoacoes = parseFloat(document.getElementById('valor_doacoes').value) || 0;
    const valorDizimos = parseFloat(document.getElementById('valor_dizimos').value) || 0;
    const valorOutros = parseFloat(document.getElementById('valor_outros').value) || 0;
    
    const somaValores = valorDoacoes + valorDizimos + valorOutros;
    if (somaValores > valorTotal) {
        showErrorModal('Valores Inválidos', 'A soma dos valores (doações + dízimos + outros) não pode ser maior que o valor total.');
        return false;
    }
    
    // Mostrar confirmação
    showConfirmModal(
        'Confirmar Criação',
        'Tem certeza que deseja criar este documento de declaração anual?\n\nO documento será gerado com:\n• Protocolo da Receita Federal\n• Hash de validação\n• Certificado digital\n• QR Code para validação',
        function() {
            // Mostrar loading
            const btnSalvar = document.getElementById('btnSalvar');
            btnSalvar.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Salvando...';
            btnSalvar.disabled = true;
            
            // Submeter formulário
            document.getElementById('formDocumento').submit();
        }
    );
});

// Mostrar toast de sucesso se houver mensagem de sucesso
@if(session('success'))
    showToast('{{ session('success') }}', 'success');
@endif

// Mostrar toast de erro se houver mensagem de erro
@if(session('error'))
    showToast('{{ session('error') }}', 'error');
@endif

// Mostrar toast de aviso se houver mensagem de aviso
@if(session('warning'))
    showToast('{{ session('warning') }}', 'warning');
@endif

// Mostrar toast de informação se houver mensagem de info
@if(session('info'))
    showToast('{{ session('info') }}', 'info');
@endif

// Mostrar erros de validação como toasts
@if($errors->any())
    @foreach($errors->all() as $error)
        showToast('{{ $error }}', 'error');
    @endforeach
@endif
</script>
@endpush
@endsection 