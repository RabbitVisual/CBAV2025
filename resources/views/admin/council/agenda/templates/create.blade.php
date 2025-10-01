@extends('layouts.admin')

@section('title', __('Criar Template de Pauta'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Criar Template de Pauta') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Crie um template reutilizável para pautas de reuniões') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.council.agenda.template.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('Voltar') }}
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.council.agenda.template.store') }}" class="space-y-6">
        @csrf
        
        <!-- Informações Básicas -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('Informações Básicas') }}</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Nome do Template') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nome" 
                           name="nome" 
                           value="{{ old('nome') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="{{ __('Ex: Reunião Ordinária Mensal') }}"
                           required>
                    @error('nome')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="categoria" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Categoria') }} <span class="text-red-500">*</span>
                    </label>
                    <select id="categoria" 
                            name="categoria"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">{{ __('Selecione uma categoria') }}</option>
                        <option value="reuniao_ordinaria" {{ old('categoria') == 'reuniao_ordinaria' ? 'selected' : '' }}>{{ __('Reunião Ordinária') }}</option>
                        <option value="reuniao_extraordinaria" {{ old('categoria') == 'reuniao_extraordinaria' ? 'selected' : '' }}>{{ __('Reunião Extraordinária') }}</option>
                        <option value="votacao" {{ old('categoria') == 'votacao' ? 'selected' : '' }}>{{ __('Votação') }}</option>
                        <option value="evento" {{ old('categoria') == 'evento' ? 'selected' : '' }}>{{ __('Evento') }}</option>
                        <option value="geral" {{ old('categoria') == 'geral' ? 'selected' : '' }}>{{ __('Geral') }}</option>
                    </select>
                    @error('categoria')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Descrição') }}
                    </label>
                    <textarea id="descricao" 
                              name="descricao" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="{{ __('Descreva o propósito deste template...') }}">{{ old('descricao') }}</textarea>
                    @error('descricao')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Status') }} <span class="text-red-500">*</span>
                    </label>
                    <select id="status" 
                            name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="rascunho" {{ old('status') == 'rascunho' ? 'selected' : '' }}>{{ __('Rascunho') }}</option>
                        <option value="ativo" {{ old('status') == 'ativo' ? 'selected' : '' }}>{{ __('Ativo') }}</option>
                        <option value="inativo" {{ old('status') == 'inativo' ? 'selected' : '' }}>{{ __('Inativo') }}</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Itens da Pauta -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-900">{{ __('Itens da Pauta') }}</h2>
                <button type="button" 
                        onclick="adicionarItem()"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    {{ __('Adicionar Item') }}
                </button>
            </div>

            <div id="itens-container" class="space-y-4">
                <!-- Itens serão adicionados aqui via JavaScript -->
            </div>

            <div class="mt-4 text-sm text-gray-600">
                <p>{{ __('Dica:') }} {{ __('Organize os itens em ordem de prioridade e tempo estimado.') }}</p>
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.council.agenda.template.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                {{ __('Cancelar') }}
            </a>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-save mr-2"></i>
                {{ __('Salvar Template') }}
            </button>
        </div>
    </form>
</div>

@include('components.modals')

@push('scripts')
<script>
let itemCounter = 0;

// Função para adicionar novo item
function adicionarItem() {
    itemCounter++;
    
    const container = document.getElementById('itens-container');
    const itemHtml = `
        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50" id="item-${itemCounter}">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">{{ __('Item') }} ${itemCounter}</h3>
                <button type="button" 
                        onclick="removerItem(${itemCounter})"
                        class="text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Título') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="itens[${itemCounter}][titulo]" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="{{ __('Título do item') }}"
                           required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Tipo') }} <span class="text-red-500">*</span>
                    </label>
                    <select name="itens[${itemCounter}][tipo]" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">{{ __('Selecione o tipo') }}</option>
                        <option value="informativo">{{ __('Informativo') }}</option>
                        <option value="deliberativo">{{ __('Deliberativo') }}</option>
                        <option value="votacao">{{ __('Votação') }}</option>
                        <option value="discussao">{{ __('Discussão') }}</option>
                        <option value="apresentacao">{{ __('Apresentação') }}</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Prioridade') }} <span class="text-red-500">*</span>
                    </label>
                    <select name="itens[${itemCounter}][prioridade]" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">{{ __('Selecione a prioridade') }}</option>
                        <option value="baixa">{{ __('Baixa') }}</option>
                        <option value="media">{{ __('Média') }}</option>
                        <option value="alta">{{ __('Alta') }}</option>
                        <option value="urgente">{{ __('Urgente') }}</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Ordem') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="itens[${itemCounter}][ordem]" 
                           value="${itemCounter}"
                           min="1"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Tempo Estimado (minutos)') }}
                    </label>
                    <input type="number" 
                           name="itens[${itemCounter}][tempo_estimado]" 
                           min="1"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="{{ __('Ex: 15') }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Responsável') }}
                    </label>
                    <select name="itens[${itemCounter}][responsavel_id]" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('Selecione o responsável') }}</option>
                        @foreach($membros as $membro)
                            <option value="{{ $membro->id }}">{{ $membro->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('Descrição') }}
                </label>
                <textarea name="itens[${itemCounter}][descricao]" 
                          rows="2"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="{{ __('Descrição detalhada do item...') }}"></textarea>
            </div>
            
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('Observações') }}
                </label>
                <textarea name="itens[${itemCounter}][observacoes]" 
                          rows="2"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="{{ __('Observações adicionais...') }}"></textarea>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', itemHtml);
}

// Função para remover item
function removerItem(itemId) {
    const item = document.getElementById(`item-${itemId}`);
    if (item) {
        item.remove();
    }
}

// Adicionar primeiro item automaticamente
document.addEventListener('DOMContentLoaded', function() {
    adicionarItem();
});

// Validação do formulário
document.querySelector('form').addEventListener('submit', function(e) {
    const itens = document.querySelectorAll('[id^="item-"]');
    
    if (itens.length === 0) {
        e.preventDefault();
        showErrorModal('{{ __("Adicione pelo menos um item à pauta.") }}');
        return;
    }
    
    // Validar campos obrigatórios
    const requiredFields = document.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add('border-red-500');
        } else {
            field.classList.remove('border-red-500');
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        showErrorModal('{{ __("Preencha todos os campos obrigatórios.") }}');
    }
});
</script>
@endpush
@endsection 