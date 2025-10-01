@extends('layouts.admin')

@section('title', __('Criar Devocionais em Lote'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Criar Devocionais em Lote') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Crie múltiplos devocionais de uma vez') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.devotionals.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('Voltar') }}
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('admin.devotionals.batch.create') }}" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Data Início -->
                <div>
                    <label for="data_inicio" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Data de Início') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           id="data_inicio" 
                           name="data_inicio" 
                           value="{{ old('data_inicio') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('data_inicio')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Data Fim -->
                <div>
                    <label for="data_fim" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Data de Fim') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           id="data_fim" 
                           name="data_fim" 
                           value="{{ old('data_fim') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('data_fim')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipo -->
                <div>
                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Tipo') }} <span class="text-red-500">*</span>
                    </label>
                    <select id="tipo" 
                            name="tipo"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">{{ __('Selecione o tipo') }}</option>
                        <option value="devocional" {{ old('tipo') == 'devocional' ? 'selected' : '' }}>
                            {{ __('Devocional') }}
                        </option>
                        <option value="versiculo" {{ old('tipo') == 'versiculo' ? 'selected' : '' }}>
                            {{ __('Versículo') }}
                        </option>
                        <option value="oracao" {{ old('tipo') == 'oracao' ? 'selected' : '' }}>
                            {{ __('Oração') }}
                        </option>
                    </select>
                    @error('tipo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Padrão -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="padrao" 
                               value="1"
                               {{ old('padrao') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">{{ __('Usar modelo padrão') }}</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1">{{ __('Se marcado, usará o modelo padrão do sistema') }}</p>
                </div>
            </div>

            <!-- Informações -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-blue-900 mb-2">{{ __('Informações') }}</h3>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• {{ __('Serão criados devocionais para cada dia no período selecionado') }}</li>
                    <li>• {{ __('Se já existir um devocional para a data, ele será ignorado') }}</li>
                    <li>• {{ __('Todos os devocionais criados estarão ativos por padrão') }}</li>
                    <li>• {{ __('Você pode editar os devocionais individualmente após a criação') }}</li>
                </ul>
            </div>

            <!-- Botões -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.devotionals.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                    {{ __('Cancelar') }}
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    {{ __('Criar Devocionais') }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-preenchimento da data atual
    if (!document.getElementById('data_inicio').value) {
        document.getElementById('data_inicio').value = new Date().toISOString().split('T')[0];
    }
    
    // Validação do formulário
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const dataInicio = document.getElementById('data_inicio').value;
        const dataFim = document.getElementById('data_fim').value;
        const tipo = document.getElementById('tipo').value;
        
        if (!dataInicio || !dataFim || !tipo) {
            e.preventDefault();
            alert('{{ __("Por favor, preencha todos os campos obrigatórios") }}');
            return false;
        }
        
        if (dataFim < dataInicio) {
            e.preventDefault();
            alert('{{ __("A data de fim deve ser posterior à data de início") }}');
            return false;
        }
        
        // Calcular quantos dias serão criados
        const inicio = new Date(dataInicio);
        const fim = new Date(dataFim);
        const diffTime = Math.abs(fim - inicio);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        
        if (diffDays > 30) {
            if (!confirm(`{{ __("Você está prestes a criar") }} ${diffDays} {{ __("devocionais. Continuar?") }}`)) {
                e.preventDefault();
                return false;
            }
        }
    });
});
</script>
@endpush
@endsection 