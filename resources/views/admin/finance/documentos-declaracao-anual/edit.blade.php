@extends('layouts.admin')

@section('title', 'Editar Documento de Declaração Anual')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Editar Documento de Declaração Anual</h1>
            <p class="text-gray-600 mt-2">Atualize as informações do documento</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.finance.documentos-declaracao-anual.show', $documento) }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-eye mr-2"></i>Visualizar
            </a>
            <a href="{{ route('admin.finance.documentos-declaracao-anual.index') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>

    <!-- Alertas -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Formulário de Edição -->
    <div class="bg-white rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Informações do Documento</h2>
            <p class="text-sm text-gray-600 mt-1">Protocolo: {{ $documento->protocolo_receita }}</p>
        </div>

        <form action="{{ route('admin.finance.documentos-declaracao-anual.update', $documento) }}" 
              method="POST" 
              enctype="multipart/form-data" 
              class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Igreja -->
                <div class="md:col-span-2">
                    <label for="igreja_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Igreja <span class="text-red-500">*</span>
                    </label>
                    <select name="igreja_id" id="igreja_id" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Selecione uma igreja</option>
                        @foreach($igrejas as $igreja)
                            <option value="{{ $igreja->id }}" 
                                    {{ old('igreja_id', $documento->igreja_id) == $igreja->id ? 'selected' : '' }}>
                                {{ $igreja->nome }} - {{ $igreja->cnpj_formatado }}
                            </option>
                        @endforeach
                    </select>
                    @error('igreja_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ano de Exercício -->
                <div>
                    <label for="ano_exercicio" class="block text-sm font-medium text-gray-700 mb-2">
                        Ano de Exercício <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="ano_exercicio" 
                           id="ano_exercicio" 
                           value="{{ old('ano_exercicio', $documento->ano_exercicio) }}"
                           min="2020" 
                           max="{{ date('Y') + 1 }}" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('ano_exercicio')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipo de Documento -->
                <div>
                    <label for="tipo_documento" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de Documento <span class="text-red-500">*</span>
                    </label>
                    <select name="tipo_documento" id="tipo_documento" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Selecione o tipo</option>
                        @foreach(App\Models\DocumentoDeclaracaoAnual::TIPOS_DOCUMENTO as $key => $value)
                            <option value="{{ $key }}" 
                                    {{ old('tipo_documento', $documento->tipo_documento) == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipo_documento')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Número do Documento -->
                <div>
                    <label for="numero_documento" class="block text-sm font-medium text-gray-700 mb-2">
                        Número do Documento <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="numero_documento" 
                           id="numero_documento" 
                           value="{{ old('numero_documento', $documento->numero_documento) }}"
                           maxlength="50" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('numero_documento')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Data de Emissão -->
                <div>
                    <label for="data_emissao" class="block text-sm font-medium text-gray-700 mb-2">
                        Data de Emissão <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           name="data_emissao" 
                           id="data_emissao" 
                           value="{{ old('data_emissao', $documento->data_emissao ? $documento->data_emissao->format('Y-m-d') : '') }}"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('data_emissao')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Data de Vencimento -->
                <div>
                    <label for="data_vencimento" class="block text-sm font-medium text-gray-700 mb-2">
                        Data de Vencimento
                    </label>
                    <input type="date" 
                           name="data_vencimento" 
                           id="data_vencimento" 
                           value="{{ old('data_vencimento', $documento->data_vencimento ? $documento->data_vencimento->format('Y-m-d') : '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('data_vencimento')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Valor Total -->
                <div>
                    <label for="valor_total" class="block text-sm font-medium text-gray-700 mb-2">
                        Valor Total <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">R$</span>
                        <input type="number" 
                               name="valor_total" 
                               id="valor_total" 
                               value="{{ old('valor_total', $documento->valor_total) }}"
                               step="0.01" 
                               min="0.01" 
                               required
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    @error('valor_total')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Valor Doações -->
                <div>
                    <label for="valor_doacoes" class="block text-sm font-medium text-gray-700 mb-2">
                        Valor Doações
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">R$</span>
                        <input type="number" 
                               name="valor_doacoes" 
                               id="valor_doacoes" 
                               value="{{ old('valor_doacoes', $documento->valor_doacoes) }}"
                               step="0.01" 
                               min="0"
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    @error('valor_doacoes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Valor Dízimos -->
                <div>
                    <label for="valor_dizimos" class="block text-sm font-medium text-gray-700 mb-2">
                        Valor Dízimos
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">R$</span>
                        <input type="number" 
                               name="valor_dizimos" 
                               id="valor_dizimos" 
                               value="{{ old('valor_dizimos', $documento->valor_dizimos) }}"
                               step="0.01" 
                               min="0"
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    @error('valor_dizimos')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Valor Outros -->
                <div>
                    <label for="valor_outros" class="block text-sm font-medium text-gray-700 mb-2">
                        Valor Outros
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">R$</span>
                        <input type="number" 
                               name="valor_outros" 
                               id="valor_outros" 
                               value="{{ old('valor_outros', $documento->valor_outros) }}"
                               step="0.01" 
                               min="0"
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    @error('valor_outros')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Arquivo Comprovante -->
                <div class="md:col-span-2">
                    <label for="arquivo_comprovante" class="block text-sm font-medium text-gray-700 mb-2">
                        Arquivo Comprovante
                    </label>
                    <input type="file" 
                           name="arquivo_comprovante" 
                           id="arquivo_comprovante"
                           accept=".pdf,.jpg,.jpeg,.png"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @if($documento->arquivo_comprovante)
                        <p class="text-sm text-gray-600 mt-1">
                            Arquivo atual: {{ basename($documento->arquivo_comprovante) }}
                        </p>
                    @endif
                    @error('arquivo_comprovante')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Observações -->
                <div class="md:col-span-2">
                    <label for="observacoes" class="block text-sm font-medium text-gray-700 mb-2">
                        Observações
                    </label>
                    <textarea name="observacoes" 
                              id="observacoes" 
                              rows="4"
                              maxlength="1000"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('observacoes', $documento->observacoes) }}</textarea>
                    @error('observacoes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Informações do Sistema -->
            <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informações do Sistema</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-gray-700">Protocolo RF:</span>
                        <span class="text-gray-600 ml-2">{{ $documento->protocolo_receita }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Status:</span>
                        <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full
                            {{ $documento->status === 'VALIDADO' ? 'bg-green-100 text-green-800' : 
                               ($documento->status === 'PENDENTE' ? 'bg-yellow-100 text-yellow-800' : 
                               ($documento->status === 'CANCELADO' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                            {{ App\Models\DocumentoDeclaracaoAnual::STATUS[$documento->status] ?? $documento->status }}
                        </span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Hash de Validação:</span>
                        <span class="text-gray-600 ml-2 font-mono text-xs">{{ substr($documento->hash_documento, 0, 16) }}...</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Criado em:</span>
                        <span class="text-gray-600 ml-2">{{ $documento->created_at->format('d/m/Y H:i:s') }}</span>
                    </div>
                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.finance.documentos-declaracao-anual.show', $documento) }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition-colors">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i>Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>

@include('components.modals')

@push('scripts')
<script>
// Validação do formulário
document.querySelector('form').addEventListener('submit', function(e) {
    const valorTotal = parseFloat(document.getElementById('valor_total').value) || 0;
    const valorDoacoes = parseFloat(document.getElementById('valor_doacoes').value) || 0;
    const valorDizimos = parseFloat(document.getElementById('valor_dizimos').value) || 0;
    const valorOutros = parseFloat(document.getElementById('valor_outros').value) || 0;
    
    const soma = valorDoacoes + valorDizimos + valorOutros;
    
    if (Math.abs(soma - valorTotal) > 0.01) {
        e.preventDefault();
        showErrorModal(
            'Valores Inconsistentes', 
            'A soma dos valores (doações + dízimos + outros) deve ser igual ao valor total.'
        );
        return false;
    }
    
    showInfoModal('Salvando', 'Aguarde, estamos salvando as alterações...');
});

// Atualizar valor total automaticamente
function atualizarValorTotal() {
    const valorDoacoes = parseFloat(document.getElementById('valor_doacoes').value) || 0;
    const valorDizimos = parseFloat(document.getElementById('valor_dizimos').value) || 0;
    const valorOutros = parseFloat(document.getElementById('valor_outros').value) || 0;
    
    const valorTotal = valorDoacoes + valorDizimos + valorOutros;
    document.getElementById('valor_total').value = valorTotal.toFixed(2);
}

// Event listeners para atualização automática
document.getElementById('valor_doacoes').addEventListener('input', atualizarValorTotal);
document.getElementById('valor_dizimos').addEventListener('input', atualizarValorTotal);
document.getElementById('valor_outros').addEventListener('input', atualizarValorTotal);

// Mostrar toast de sucesso se houver mensagem
@if(session('success'))
    showToast('{{ session('success') }}', 'success');
@endif

// Mostrar toast de erro se houver mensagem
@if(session('error'))
    showToast('{{ session('error') }}', 'error');
@endif
</script>
@endpush
@endsection 