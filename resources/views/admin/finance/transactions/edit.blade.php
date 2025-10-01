@extends('layouts.admin')

@section('title', __('Editar Transação'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Editar Transação') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Atualize as informações da transação') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.finance.transactions.show', $transacao) }}" 
               class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-eye mr-2"></i>
                {{ __('Visualizar') }}
            </a>
            <a href="{{ route('admin.finance.transactions.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('Voltar') }}
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.finance.transactions.update', $transacao) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Tipo de Transação -->
            <div>
                <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Tipo de Transação') }} <span class="text-red-500">*</span>
                </label>
                <select id="tipo" 
                        name="tipo"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tipo') border-red-500 @enderror"
                        required>
                    <option value="">{{ __('Selecione o tipo') }}</option>
                    <option value="entrada" {{ old('tipo', $transacao->tipo) == 'entrada' ? 'selected' : '' }}>{{ __('Entrada (Receita)') }}</option>
                    <option value="saida" {{ old('tipo', $transacao->tipo) == 'saida' ? 'selected' : '' }}>{{ __('Saída (Despesa)') }}</option>
                </select>
                @error('tipo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Valor -->
            <div>
                <label for="valor" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Valor') }} <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">R$</span>
                    </span>
                    <input type="text" 
                           id="valor" 
                           name="valor" 
                           value="{{ old('valor', number_format($transacao->valor, 2, ',', '.')) }}"
                           class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('valor') border-red-500 @enderror"
                           placeholder="0,00"
                           required>
                </div>
                @error('valor')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Descrição -->
            <div>
                <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Descrição') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="descricao" 
                       name="descricao" 
                       value="{{ old('descricao', $transacao->descricao) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('descricao') border-red-500 @enderror"
                       placeholder="{{ __('Ex: Dízimo, Oferta, Despesa com manutenção...') }}"
                       required>
                @error('descricao')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Categoria -->
            <div>
                <label for="categoria" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Categoria') }}
                </label>
                <select id="categoria" 
                        name="categoria"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('categoria') border-red-500 @enderror">
                    <option value="">{{ __('Selecione uma categoria') }}</option>
                    <option value="dizimo" {{ old('categoria', $transacao->categoria) == 'dizimo' ? 'selected' : '' }}>{{ __('Dízimo') }}</option>
                    <option value="oferta" {{ old('categoria', $transacao->categoria) == 'oferta' ? 'selected' : '' }}>{{ __('Oferta') }}</option>
                    <option value="campanha" {{ old('categoria', $transacao->categoria) == 'campanha' ? 'selected' : '' }}>{{ __('Campanha') }}</option>
                    <option value="doacao" {{ old('categoria', $transacao->categoria) == 'doacao' ? 'selected' : '' }}>{{ __('Doação') }}</option>
                    <option value="manutencao" {{ old('categoria', $transacao->categoria) == 'manutencao' ? 'selected' : '' }}>{{ __('Manutenção') }}</option>
                    <option value="utilitarios" {{ old('categoria', $transacao->categoria) == 'utilitarios' ? 'selected' : '' }}>{{ __('Utilitários') }}</option>
                    <option value="equipamentos" {{ old('categoria', $transacao->categoria) == 'equipamentos' ? 'selected' : '' }}>{{ __('Equipamentos') }}</option>
                    <option value="outros" {{ old('categoria', $transacao->categoria) == 'outros' ? 'selected' : '' }}>{{ __('Outros') }}</option>
                </select>
                @error('categoria')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campanha (se aplicável) -->
            <div id="campanha_field" class="{{ old('categoria', $transacao->categoria) == 'campanha' ? '' : 'hidden' }}">
                <label for="campanha_id" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Campanha') }}
                </label>
                <select id="campanha_id" 
                        name="campanha_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('campanha_id') border-red-500 @enderror">
                    <option value="">{{ __('Selecione uma campanha') }}</option>
                    @foreach($campanhas as $campanha)
                        <option value="{{ $campanha->id }}" {{ old('campanha_id', $transacao->campanha_id) == $campanha->id ? 'selected' : '' }}>
                            {{ $campanha->titulo }} ({{ $campanha->status }})
                        </option>
                    @endforeach
                </select>
                @error('campanha_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Membro (se aplicável) -->
            <div>
                <label for="membro_id" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Membro') }}
                </label>
                <select id="membro_id" 
                        name="membro_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('membro_id') border-red-500 @enderror">
                    <option value="">{{ __('Selecione um membro (opcional)') }}</option>
                    @foreach($membros as $membro)
                        <option value="{{ $membro->id }}" {{ old('membro_id', $transacao->membro_id) == $membro->id ? 'selected' : '' }}>
                            {{ $membro->nome }} ({{ $membro->email }})
                        </option>
                    @endforeach
                </select>
                @error('membro_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Data da Transação -->
            <div>
                <label for="data_transacao" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Data da Transação') }} <span class="text-red-500">*</span>
                </label>
                <input type="date" 
                       id="data_transacao" 
                       name="data_transacao" 
                                               value="{{ old('data_transacao', $transacao->data ? $transacao->data->format('Y-m-d') : '') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('data_transacao') border-red-500 @enderror"
                       required>
                @error('data_transacao')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Método de Pagamento -->
            <div>
                <label for="metodo_pagamento" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Método de Pagamento') }}
                </label>
                <select id="metodo_pagamento" 
                        name="metodo_pagamento"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('metodo_pagamento') border-red-500 @enderror">
                    <option value="">{{ __('Selecione o método') }}</option>
                    <option value="dinheiro" {{ old('metodo_pagamento', $transacao->metodo_pagamento) == 'dinheiro' ? 'selected' : '' }}>{{ __('Dinheiro') }}</option>
                    <option value="pix" {{ old('metodo_pagamento', $transacao->metodo_pagamento) == 'pix' ? 'selected' : '' }}>{{ __('PIX') }}</option>
                    <option value="cartao_credito" {{ old('metodo_pagamento', $transacao->metodo_pagamento) == 'cartao_credito' ? 'selected' : '' }}>{{ __('Cartão de Crédito') }}</option>
                    <option value="cartao_debito" {{ old('metodo_pagamento', $transacao->metodo_pagamento) == 'cartao_debito' ? 'selected' : '' }}>{{ __('Cartão de Débito') }}</option>
                    <option value="transferencia" {{ old('metodo_pagamento', $transacao->metodo_pagamento) == 'transferencia' ? 'selected' : '' }}>{{ __('Transferência Bancária') }}</option>
                    <option value="cheque" {{ old('metodo_pagamento', $transacao->metodo_pagamento) == 'cheque' ? 'selected' : '' }}>{{ __('Cheque') }}</option>
                    <option value="outros" {{ old('metodo_pagamento', $transacao->metodo_pagamento) == 'outros' ? 'selected' : '' }}>{{ __('Outros') }}</option>
                </select>
                @error('metodo_pagamento')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Status') }} <span class="text-red-500">*</span>
                </label>
                <select id="status" 
                        name="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror"
                        required>
                    <option value="">{{ __('Selecione o status') }}</option>
                    <option value="confirmado" {{ old('status', $transacao->status) == 'confirmado' ? 'selected' : '' }}>{{ __('Confirmado') }}</option>
                    <option value="pendente" {{ old('status', $transacao->status) == 'pendente' ? 'selected' : '' }}>{{ __('Pendente') }}</option>
                    <option value="cancelado" {{ old('status', $transacao->status) == 'cancelado' ? 'selected' : '' }}>{{ __('Cancelado') }}</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Observações -->
            <div>
                <label for="observacoes" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Observações') }}
                </label>
                <textarea id="observacoes" 
                          name="observacoes" 
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('observacoes') border-red-500 @enderror"
                          placeholder="{{ __('Informações adicionais sobre a transação...') }}">{{ old('observacoes', $transacao->observacoes) }}</textarea>
                @error('observacoes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Informações do Sistema -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-sm font-medium text-gray-700 mb-3">{{ __('Informações do Sistema') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">{{ __('Criado em:') }}</span>
                        <span class="text-gray-900">{{ $transacao->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">{{ __('Última atualização:') }}</span>
                        <span class="text-gray-900">{{ $transacao->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">{{ __('ID da Transação:') }}</span>
                        <span class="text-gray-900 font-mono">{{ $transacao->id }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">{{ __('Criado por:') }}</span>
                        <span class="text-gray-900">{{ $transacao->criado_por ?? 'Sistema' }}</span>
                    </div>
                </div>
            </div>

            <!-- Botões -->
            <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                <div class="flex space-x-3">
                    <button type="button" 
                            onclick="confirmarExclusao()"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-trash mr-2"></i>
                        {{ __('Excluir Transação') }}
                    </button>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('admin.finance.transactions.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                        {{ __('Cancelar') }}
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        {{ __('Salvar Alterações') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div id="modalExclusao" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Confirmar Exclusão') }}</h3>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-6">
                    {{ __('Tem certeza que deseja excluir a transação') }} <strong>"{{ $transacao->descricao }}"</strong>? 
                    {{ __('Esta ação não pode ser desfeita.') }}
                </p>
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="cancelarExclusao()"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        {{ __('Cancelar') }}
                    </button>
                    <form action="{{ route('admin.finance.transactions.delete', $transacao) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200">
                            {{ __('Excluir') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Máscara para valor
    const valorInput = document.getElementById('valor');
    
    // Formatar valor inicial
    if (valorInput.value) {
        let value = valorInput.value.replace(/\./g, '').replace(',', '.');
        value = parseFloat(value).toFixed(2).replace('.', ',');
        value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        valorInput.value = value;
    }
    
    valorInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        value = (value / 100).toFixed(2);
        value = value.replace('.', ',');
        value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        e.target.value = value;
    });

    // Mostrar/ocultar campo de campanha baseado na categoria
    const categoriaSelect = document.getElementById('categoria');
    const campanhaField = document.getElementById('campanha_field');
    const campanhaSelect = document.getElementById('campanha_id');
    
    if (categoriaSelect) {
        categoriaSelect.addEventListener('change', function() {
            if (this.value === 'campanha') {
                campanhaField.classList.remove('hidden');
                campanhaSelect.required = true;
            } else {
                campanhaField.classList.add('hidden');
                campanhaSelect.required = false;
                campanhaSelect.value = '';
            }
        });
    }

    // Validação em tempo real
    const descricaoInput = document.getElementById('descricao');
    descricaoInput.addEventListener('input', function() {
        if (this.value.length < 3) {
            this.classList.add('border-red-500');
        } else {
            this.classList.remove('border-red-500');
        }
    });

    // Confirmação antes de sair se houver mudanças
    let formChanged = false;
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input, textarea, select');
    
    inputs.forEach(input => {
        input.addEventListener('change', () => formChanged = true);
        input.addEventListener('input', () => formChanged = true);
    });

    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
});

function confirmarExclusao() {
    document.getElementById('modalExclusao').classList.remove('hidden');
}

function cancelarExclusao() {
    document.getElementById('modalExclusao').classList.add('hidden');
}
</script>
@endpush
@endsection 