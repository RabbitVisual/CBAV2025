@extends('layouts.admin')

@section('title', 'Novo Documento de Baixa - Gestão Financeira')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Novo Documento de Baixa</h1>
        <p class="text-gray-600">Crie um novo documento para comprovante de declaração de IR</p>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Informações do Documento</h3>
        </div>
        
        <form action="{{ route('admin.finance.documentos.store') }}" method="POST" enctype="multipart/form-data" class="p-6" id="formDocumento">
            @csrf
            
            <!-- Filtros para Transações -->
            <div class="md:col-span-2 mb-6 p-4 bg-gray-50 rounded-lg">
                <h4 class="text-sm font-medium text-gray-700 mb-3">Filtros para Transações</h4>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Campanha</label>
                        <select id="filtro_campanha" class="w-full px-2 py-1 text-sm border border-gray-300 rounded">
                            <option value="">Todas as campanhas</option>
                            @foreach($campanhas as $campanha)
                                <option value="{{ $campanha->id }}" {{ request('campanha_id') == $campanha->id ? 'selected' : '' }}>
                                    {{ $campanha->titulo }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Membro</label>
                        <select id="filtro_membro" class="w-full px-2 py-1 text-sm border border-gray-300 rounded">
                            <option value="">Todos os membros</option>
                            @foreach($membros as $membro)
                                <option value="{{ $membro->id }}" {{ request('membro_id') == $membro->id ? 'selected' : '' }}>
                                    {{ $membro->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Data Início</label>
                        <input type="date" id="filtro_data_inicio" class="w-full px-2 py-1 text-sm border border-gray-300 rounded" value="{{ request('data_inicio') }}">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Data Fim</label>
                        <input type="date" id="filtro_data_fim" class="w-full px-2 py-1 text-sm border border-gray-300 rounded" value="{{ request('data_fim') }}">
                    </div>
                </div>
                <div class="mt-3 text-xs text-gray-600">
                    <strong>Estatísticas:</strong> {{ $estatisticas['total_transacoes_disponiveis'] }} transações disponíveis | 
                    R$ {{ number_format($estatisticas['valor_total_disponivel'], 2, ',', '.') }} total | 
                    {{ $estatisticas['transacoes_hoje'] }} hoje | {{ $estatisticas['transacoes_semana'] }} esta semana
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Transação -->
                <div class="md:col-span-2">
                    <label for="transacao_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Transação <span class="text-red-500">*</span>
                    </label>
                    <select name="transacao_id" id="transacao_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecione uma transação</option>
                        @foreach($transacoes as $transacao)
                            <option value="{{ $transacao->id }}" 
                                    data-valor="{{ $transacao->valor }}"
                                    data-data="{{ $transacao->created_at->format('Y-m-d') }}"
                                    data-membro="{{ $transacao->membro->nome ?? 'Anônimo' }}"
                                    data-campanha="{{ $transacao->campanha->titulo ?? 'Doação Geral' }}"
                                    {{ old('transacao_id') == $transacao->id ? 'selected' : '' }}>
                                #{{ $transacao->id }} - {{ $transacao->membro->nome ?? 'Anônimo' }} - 
                                R$ {{ number_format($transacao->valor, 2, ',', '.') }} 
                                ({{ $transacao->campanha->titulo ?? 'Doação Geral' }})
                            </option>
                        @endforeach
                    </select>
                    @error('transacao_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    
                    <!-- Informações da Transação Selecionada -->
                    <div id="info-transacao" class="mt-3"></div>
                </div>

                <!-- Tipo de Documento -->
                <div>
                    <label for="tipo_documento" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de Documento <span class="text-red-500">*</span>
                    </label>
                    <select name="tipo_documento" id="tipo_documento" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecione o tipo</option>
                        @foreach(App\Models\DocumentoBaixa::TIPOS_DOCUMENTO as $key => $value)
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

                <!-- Ano Exercício -->
                <div>
                    <label for="ano_exercicio" class="block text-sm font-medium text-gray-700 mb-2">
                        Ano Exercício <span class="text-red-500">*</span>
                    </label>
                    <select name="ano_exercicio" id="ano_exercicio" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecione o ano</option>
                        @for($ano = date('Y'); $ano >= 2020; $ano--)
                            <option value="{{ $ano }}" {{ old('ano_exercicio') == $ano ? 'selected' : '' }}>
                                {{ $ano }}
                            </option>
                        @endfor
                    </select>
                    @error('ano_exercicio')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Data de Emissão -->
                <div>
                    <label for="data_emissao" class="block text-sm font-medium text-gray-700 mb-2">
                        Data de Emissão <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="data_emissao" id="data_emissao" 
                           value="{{ old('data_emissao') }}" required
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

                <!-- Valor do Documento -->
                <div>
                    <label for="valor_documento" class="block text-sm font-medium text-gray-700 mb-2">
                        Valor do Documento <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">R$</span>
                        <input type="number" name="valor_documento" id="valor_documento" 
                               value="{{ old('valor_documento') }}" step="0.01" min="0.01" required
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0,00">
                    </div>
                    @error('valor_documento')
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
                <a href="{{ route('admin.finance.documentos.index') }}" 
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
            <p><strong>•</strong> Este documento será utilizado como comprovante para declaração de IR.</p>
            <p><strong>•</strong> O sistema gera automaticamente um protocolo da Receita Federal.</p>
            <p><strong>•</strong> O hash do documento garante a integridade e autenticidade.</p>
            <p><strong>•</strong> Documentos vencidos terão multa e juros calculados automaticamente.</p>
            <p><strong>•</strong> O código de barras segue o padrão FEBRABAN para pagamento.</p>
        </div>
    </div>
</div>

@include('components.modals')

@push('scripts')
<script>
// Carregar dados automaticamente quando transação for selecionada
document.getElementById('transacao_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    if (selectedOption.value) {
        // Carregar valor automaticamente
        const valorInput = document.getElementById('valor_documento');
        valorInput.value = selectedOption.dataset.valor;
        
        // Carregar data de emissão automaticamente
        const dataEmissaoInput = document.getElementById('data_emissao');
        dataEmissaoInput.value = selectedOption.dataset.data;
        
        // Mostrar informações da transação
        const infoDiv = document.getElementById('info-transacao');
        if (infoDiv) {
            infoDiv.innerHTML = `
                <div class="p-3 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <strong>Membro:</strong> ${selectedOption.dataset.membro}<br>
                        <strong>Campanha:</strong> ${selectedOption.dataset.campanha}<br>
                        <strong>Valor:</strong> R$ ${parseFloat(selectedOption.dataset.valor).toFixed(2).replace('.', ',')}
                    </p>
                </div>
            `;
        }
        
        // Mostrar toast de sucesso
        showToast('Transação selecionada com sucesso!', 'success');
    }
});

// Filtros dinâmicos
function aplicarFiltros() {
    const campanha = document.getElementById('filtro_campanha').value;
    const membro = document.getElementById('filtro_membro').value;
    const dataInicio = document.getElementById('filtro_data_inicio').value;
    const dataFim = document.getElementById('filtro_data_fim').value;
    
    const url = new URL(window.location);
    if (campanha) url.searchParams.set('campanha_id', campanha);
    if (membro) url.searchParams.set('membro_id', membro);
    if (dataInicio) url.searchParams.set('data_inicio', dataInicio);
    if (dataFim) url.searchParams.set('data_fim', dataFim);
    
    showInfoModal('Aplicando Filtros', 'Aguarde, estamos aplicando os filtros...');
    
    setTimeout(() => {
        window.location.href = url.toString();
    }, 500);
}

// Aplicar filtros quando mudarem
document.getElementById('filtro_campanha').addEventListener('change', aplicarFiltros);
document.getElementById('filtro_membro').addEventListener('change', aplicarFiltros);
document.getElementById('filtro_data_inicio').addEventListener('change', aplicarFiltros);
document.getElementById('filtro_data_fim').addEventListener('change', aplicarFiltros);

// Validação do formato do documento
document.getElementById('tipo_documento').addEventListener('change', function() {
    const tipo = this.value;
    const numeroInput = document.getElementById('numero_documento');
    
    // Limpar placeholder anterior
    numeroInput.placeholder = 'Número do documento';
    
    // Definir placeholder baseado no tipo
    switch(tipo) {
        case 'DARF':
            numeroInput.placeholder = 'Ex: 12345678901234 (14 dígitos)';
            break;
        case 'GPS':
            numeroInput.placeholder = 'Ex: 12345678901 (11 dígitos)';
            break;
        case 'DAS':
            numeroInput.placeholder = 'Ex: 1234567890123 (13 dígitos)';
            break;
        default:
            numeroInput.placeholder = 'Ex: 12345678901234';
    }
    
    if (tipo) {
        showToast(`Tipo de documento selecionado: ${this.options[this.selectedIndex].text}`, 'info');
    }
});

// Formatação do valor
document.getElementById('valor_documento').addEventListener('input', function() {
    let value = this.value.replace(/[^\d.,]/g, '');
    value = value.replace(',', '.');
    this.value = value;
});

// Validação em tempo real do número do documento
document.getElementById('numero_documento').addEventListener('input', function() {
    const numero = this.value.replace(/[^0-9]/g, '');
    const tipo = document.getElementById('tipo_documento').value;
    
    let valido = true;
    let mensagem = '';
    
    switch(tipo) {
        case 'DARF':
            valido = numero.length === 14;
            mensagem = 'DARF deve ter 14 dígitos';
            break;
        case 'GPS':
            valido = numero.length === 11;
            mensagem = 'GPS deve ter 11 dígitos';
            break;
        case 'DAS':
            valido = numero.length === 13;
            mensagem = 'DAS deve ter 13 dígitos';
            break;
        default:
            valido = numero.length >= 8;
            mensagem = 'Número deve ter pelo menos 8 dígitos';
    }
    
    if (this.value && !valido) {
        this.classList.add('border-red-500');
        this.classList.remove('border-gray-300');
        showToast(mensagem, 'warning');
    } else {
        this.classList.remove('border-red-500');
        this.classList.add('border-gray-300');
    }
});

// Validação do formulário antes de enviar
document.getElementById('formDocumento').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validar campos obrigatórios
    const camposObrigatorios = [
        { id: 'transacao_id', nome: 'Transação' },
        { id: 'tipo_documento', nome: 'Tipo de Documento' },
        { id: 'numero_documento', nome: 'Número do Documento' },
        { id: 'ano_exercicio', nome: 'Ano Exercício' },
        { id: 'data_emissao', nome: 'Data de Emissão' },
        { id: 'valor_documento', nome: 'Valor do Documento' }
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
    
    // Validar valor do documento
    const valor = parseFloat(document.getElementById('valor_documento').value);
    if (valor <= 0) {
        showErrorModal('Valor Inválido', 'O valor do documento deve ser maior que zero.');
        return false;
    }
    
    // Validar data de emissão
    const dataEmissao = new Date(document.getElementById('data_emissao').value);
    const hoje = new Date();
    if (dataEmissao > hoje) {
        showErrorModal('Data Inválida', 'A data de emissão não pode ser futura.');
        return false;
    }
    
    // Mostrar confirmação
    showConfirmModal(
        'Confirmar Criação',
        'Tem certeza que deseja criar este documento de baixa?\n\nO documento será gerado com protocolo da Receita Federal e hash de validação.',
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