@extends('layouts.admin')

@section('title', 'Editar Campanha')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Editar Campanha</h1>
        <p class="text-gray-600 mt-2">Atualize as informações da campanha "{{ $campanha->titulo }}"</p>
    </div>

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

    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Informações da Campanha</h2>
        </div>
        
        <form action="{{ route('admin.finance.campaigns.update', $campanha) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Título -->
                <div class="md:col-span-2">
                    <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">
                        Título da Campanha <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="titulo" name="titulo" value="{{ old('titulo', $campanha->titulo) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('titulo') border-red-500 @enderror"
                           placeholder="Ex: Campanha de Construção">
                    @error('titulo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descrição -->
                <div class="md:col-span-2">
                    <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">
                        Descrição
                    </label>
                    <textarea id="descricao" name="descricao" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('descricao') border-red-500 @enderror"
                              placeholder="Descreva o objetivo da campanha...">{{ old('descricao', $campanha->descricao) }}</textarea>
                    @error('descricao')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Meta -->
                <div>
                    <label for="meta_valor" class="block text-sm font-medium text-gray-700 mb-2">
                        Meta (R$) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="meta_valor" name="meta_valor" value="{{ old('meta_valor', $campanha->meta_valor) }}" 
                           step="0.01" min="0" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('meta_valor') border-red-500 @enderror"
                           placeholder="0,00">
                    @error('meta_valor')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipo -->
                <div>
                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de Campanha <span class="text-red-500">*</span>
                    </label>
                    <select id="tipo" name="tipo" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tipo') border-red-500 @enderror">
                        <option value="">Selecione o tipo...</option>
                        <option value="construcao" {{ old('tipo', $campanha->tipo) == 'construcao' ? 'selected' : '' }}>Construção</option>
                        <option value="missao" {{ old('tipo', $campanha->tipo) == 'missao' ? 'selected' : '' }}>Missão</option>
                        <option value="social" {{ old('tipo', $campanha->tipo) == 'social' ? 'selected' : '' }}>Social</option>
                        <option value="equipamentos" {{ old('tipo', $campanha->tipo) == 'equipamentos' ? 'selected' : '' }}>Equipamentos</option>
                        <option value="outros" {{ old('tipo', $campanha->tipo) == 'outros' ? 'selected' : '' }}>Outros</option>
                    </select>
                    @error('tipo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Data de Início -->
                <div>
                    <label for="data_inicio" class="block text-sm font-medium text-gray-700 mb-2">
                        Data de Início <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="data_inicio" name="data_inicio" value="{{ old('data_inicio', $campanha->data_inicio->format('Y-m-d')) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('data_inicio') border-red-500 @enderror">
                    @error('data_inicio')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Data de Fim -->
                <div>
                    <label for="data_fim" class="block text-sm font-medium text-gray-700 mb-2">
                        Data de Fim
                    </label>
                    <input type="date" id="data_fim" name="data_fim" value="{{ old('data_fim', $campanha->data_fim ? $campanha->data_fim->format('Y-m-d') : '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('data_fim') border-red-500 @enderror">
                    @error('data_fim')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status" name="status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                        <option value="ativa" {{ old('status', $campanha->status) == 'ativa' ? 'selected' : '' }}>Ativa</option>
                        <option value="pausada" {{ old('status', $campanha->status) == 'pausada' ? 'selected' : '' }}>Pausada</option>
                        <option value="finalizada" {{ old('status', $campanha->status) == 'finalizada' ? 'selected' : '' }}>Finalizada</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ativo -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="ativo" value="1" {{ old('ativo', $campanha->ativo) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Campanha Ativa</span>
                    </label>
                </div>
            </div>

            <!-- Estatísticas da Campanha -->
            <div class="mt-8 p-6 bg-gray-50 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Estatísticas da Campanha</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">R$ {{ number_format($campanha->valor_arrecadado, 2, ',', '.') }}</div>
                        <div class="text-sm text-gray-600">Total Arrecadado</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ number_format($campanha->progresso, 1) }}%</div>
                        <div class="text-sm text-gray-600">Progresso</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ $campanha->transacoes->count() }}</div>
                        <div class="text-sm text-gray-600">Total de Transações</div>
                    </div>
                </div>
                
                <!-- Barra de Progresso -->
                <div class="mt-4">
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>Progresso</span>
                        <span>{{ number_format($campanha->progresso, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full" style="width: {{ min($campanha->progresso, 100) }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Botões -->
            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('admin.finance.campaigns.index') }}" 
                   class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>
                    Atualizar Campanha
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Máscara para valor monetário
    const metaValor = document.getElementById('meta_valor');
    if (metaValor) {
        metaValor.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = (parseFloat(value) / 100).toFixed(2);
            e.target.value = value;
        });
    }

    // Validação de datas
    const dataInicio = document.getElementById('data_inicio');
    const dataFim = document.getElementById('data_fim');
    
    if (dataInicio && dataFim) {
        dataInicio.addEventListener('change', function() {
            if (dataFim.value && this.value > dataFim.value) {
                alert('A data de início não pode ser posterior à data de fim');
                this.value = '';
            }
        });
        
        dataFim.addEventListener('change', function() {
            if (dataInicio.value && this.value < dataInicio.value) {
                alert('A data de fim não pode ser anterior à data de início');
                this.value = '';
            }
        });
    }
});
</script>
@endsection 