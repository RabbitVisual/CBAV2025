@extends('layouts.admin')

@section('title', __('Nova Transação'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Nova Transação') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Registre uma nova transação financeira') }}</p>
        </div>
        <a href="{{ route('admin.finance.transactions.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            {{ __('Voltar') }}
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.finance.transactions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
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
                    <option value="entrada" {{ old('tipo') == 'entrada' ? 'selected' : '' }}>{{ __('Entrada (Receita)') }}</option>
                    <option value="saida" {{ old('tipo') == 'saida' ? 'selected' : '' }}>{{ __('Saída (Despesa)') }}</option>
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
                           value="{{ old('valor') }}"
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
                       value="{{ old('descricao') }}"
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
                    <option value="dizimo" {{ old('categoria') == 'dizimo' ? 'selected' : '' }}>{{ __('Dízimo') }}</option>
                    <option value="oferta" {{ old('categoria') == 'oferta' ? 'selected' : '' }}>{{ __('Oferta') }}</option>
                    <option value="campanha" {{ old('categoria') == 'campanha' ? 'selected' : '' }}>{{ __('Campanha') }}</option>
                    <option value="doacao" {{ old('categoria') == 'doacao' ? 'selected' : '' }}>{{ __('Doação') }}</option>
                    <option value="manutencao" {{ old('categoria') == 'manutencao' ? 'selected' : '' }}>{{ __('Manutenção') }}</option>
                    <option value="utilitarios" {{ old('categoria') == 'utilitarios' ? 'selected' : '' }}>{{ __('Utilitários') }}</option>
                    <option value="equipamentos" {{ old('categoria') == 'equipamentos' ? 'selected' : '' }}>{{ __('Equipamentos') }}</option>
                    <option value="outros" {{ old('categoria') == 'outros' ? 'selected' : '' }}>{{ __('Outros') }}</option>
                </select>
                @error('categoria')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campanha (se aplicável) -->
            <div id="campanha_field" class="hidden">
                <label for="campanha_id" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Campanha') }}
                </label>
                <select id="campanha_id" 
                        name="campanha_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('campanha_id') border-red-500 @enderror">
                    <option value="">{{ __('Selecione uma campanha') }}</option>
                    @foreach($campanhas as $campanha)
                        <option value="{{ $campanha->id }}" {{ old('campanha_id') == $campanha->id ? 'selected' : '' }}>
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
                        <option value="{{ $membro->id }}" {{ old('membro_id') == $membro->id ? 'selected' : '' }}>
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
                       value="{{ old('data_transacao', now()->format('Y-m-d')) }}"
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
                    <option value="dinheiro" {{ old('metodo_pagamento') == 'dinheiro' ? 'selected' : '' }}>{{ __('Dinheiro') }}</option>
                    <option value="pix" {{ old('metodo_pagamento') == 'pix' ? 'selected' : '' }}>{{ __('PIX') }}</option>
                    <option value="cartao_credito" {{ old('metodo_pagamento') == 'cartao_credito' ? 'selected' : '' }}>{{ __('Cartão de Crédito') }}</option>
                    <option value="cartao_debito" {{ old('metodo_pagamento') == 'cartao_debito' ? 'selected' : '' }}>{{ __('Cartão de Débito') }}</option>
                    <option value="transferencia" {{ old('metodo_pagamento') == 'transferencia' ? 'selected' : '' }}>{{ __('Transferência Bancária') }}</option>
                    <option value="cheque" {{ old('metodo_pagamento') == 'cheque' ? 'selected' : '' }}>{{ __('Cheque') }}</option>
                    <option value="outros" {{ old('metodo_pagamento') == 'outros' ? 'selected' : '' }}>{{ __('Outros') }}</option>
                </select>
                @error('metodo_pagamento')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Status') }}
                </label>
                <select id="status" 
                        name="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                    <option value="confirmado" {{ old('status', 'confirmado') == 'confirmado' ? 'selected' : '' }}>{{ __('Confirmado') }}</option>
                    <option value="pendente" {{ old('status') == 'pendente' ? 'selected' : '' }}>{{ __('Pendente') }}</option>
                    <option value="cancelado" {{ old('status') == 'cancelado' ? 'selected' : '' }}>{{ __('Cancelado') }}</option>
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
                          placeholder="{{ __('Informações adicionais sobre a transação...') }}">{{ old('observacoes') }}</textarea>
                @error('observacoes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botões -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.finance.transactions.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                    {{ __('Cancelar') }}
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    {{ __('Criar Transação') }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Máscara para valor
    const valorInput = document.getElementById('valor');
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
</script>
@endpush
@endsection 