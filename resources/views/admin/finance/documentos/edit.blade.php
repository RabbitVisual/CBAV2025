@extends('layouts.admin')

@section('title', 'Editar Documento de Baixa - Gestão Financeira')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Editar Documento de Baixa</h1>
        <p class="text-gray-600">Edite as informações do documento para comprovante de IR</p>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Informações do Documento</h3>
        </div>
        
        <form action="{{ route('admin.finance.documentos.update', $documento) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            
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
                            <option value="{{ $transacao->id }}" {{ old('transacao_id', $documento->transacao_id) == $transacao->id ? 'selected' : '' }}>
                                #{{ $transacao->id }} - {{ $transacao->membro->nome ?? 'Anônimo' }} - 
                                R$ {{ number_format($transacao->valor, 2, ',', '.') }} 
                                ({{ $transacao->campanha->nome ?? 'Doação Geral' }})
                            </option>
                        @endforeach
                    </select>
                    @error('transacao_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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
                            <option value="{{ $key }}" {{ old('tipo_documento', $documento->tipo_documento) == $key ? 'selected' : '' }}>
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
                           value="{{ old('numero_documento', $documento->numero_documento) }}" required
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
                            <option value="{{ $ano }}" {{ old('ano_exercicio', $documento->ano_exercicio) == $ano ? 'selected' : '' }}>
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
                           value="{{ old('data_emissao', $documento->data_emissao->format('Y-m-d')) }}" required
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
                           value="{{ old('data_vencimento', $documento->data_vencimento ? $documento->data_vencimento->format('Y-m-d') : '') }}"
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
                               value="{{ old('valor_documento', $documento->valor_documento) }}" step="0.01" min="0.01" required
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0,00">
                    </div>
                    @error('valor_documento')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Valor Pago -->
                <div>
                    <label for="valor_pago" class="block text-sm font-medium text-gray-700 mb-2">
                        Valor Pago
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">R$</span>
                        <input type="number" name="valor_pago" id="valor_pago" 
                               value="{{ old('valor_pago', $documento->valor_pago) }}" step="0.01" min="0"
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0,00">
                    </div>
                    @error('valor_pago')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach(App\Models\DocumentoBaixa::STATUS as $key => $value)
                            <option value="{{ $key }}" {{ old('status', $documento->status) == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Arquivo Comprovante -->
                <div class="md:col-span-2">
                    <label for="arquivo_comprovante" class="block text-sm font-medium text-gray-700 mb-2">
                        Arquivo Comprovante
                    </label>
                    @if($documento->arquivo_comprovante)
                        <div class="mb-3 p-3 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-2">Arquivo atual:</p>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-file-pdf text-red-500"></i>
                                <span class="text-sm font-medium">{{ basename($documento->arquivo_comprovante) }}</span>
                                <a href="{{ Storage::url($documento->arquivo_comprovante) }}" target="_blank"
                                   class="text-blue-600 hover:text-blue-800 text-sm">
                                    <i class="fas fa-external-link-alt mr-1"></i>Visualizar
                                </a>
                            </div>
                        </div>
                    @endif
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
                              placeholder="Observações adicionais sobre o documento...">{{ old('observacoes', $documento->observacoes) }}</textarea>
                    @error('observacoes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Informações do Sistema -->
            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <h4 class="text-sm font-medium text-blue-900 mb-2">Informações do Sistema</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-gray-700">Protocolo RF:</span>
                        <span class="text-gray-900">{{ $documento->protocolo_receita }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Hash do Documento:</span>
                        <span class="text-gray-900 font-mono text-xs">{{ substr($documento->hash_documento, 0, 16) }}...</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Criado em:</span>
                        <span class="text-gray-900">{{ $documento->created_at->format('d/m/Y H:i:s') }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Última atualização:</span>
                        <span class="text-gray-900">{{ $documento->updated_at->format('d/m/Y H:i:s') }}</span>
                    </div>
                </div>
            </div>

            <!-- Botões -->
            <div class="flex justify-end space-x-4 mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.finance.documentos.show', $documento) }}" 
                   class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>Atualizar Documento
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
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
});

// Formatação do valor
document.getElementById('valor_documento').addEventListener('input', function() {
    let value = this.value.replace(/[^\d.,]/g, '');
    value = value.replace(',', '.');
    this.value = value;
});

document.getElementById('valor_pago').addEventListener('input', function() {
    let value = this.value.replace(/[^\d.,]/g, '');
    value = value.replace(',', '.');
    this.value = value;
});
</script>
@endpush
@endsection 