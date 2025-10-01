@extends('layouts.admin')

@section('title', __('Editar Reunião do Conselho'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Editar Reunião do Conselho') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Edite os dados da reunião') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.council.show', $conselho) }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('Voltar') }}
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('admin.council.update', $conselho) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Título -->
                <div class="md:col-span-2">
                    <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Título da Reunião') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="titulo" 
                           name="titulo" 
                           value="{{ old('titulo', $conselho->titulo) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="{{ __('Ex: Reunião Ordinária do Conselho') }}"
                           required>
                    @error('titulo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descrição -->
                <div class="md:col-span-2">
                    <label for="descricao" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Descrição') }}
                    </label>
                    <textarea id="descricao" 
                              name="descricao" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="{{ __('Descrição detalhada da reunião...') }}">{{ old('descricao', $conselho->descricao) }}</textarea>
                    @error('descricao')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Data da Reunião -->
                <div>
                    <label for="data_reuniao" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Data da Reunião') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           id="data_reuniao" 
                           name="data_reuniao" 
                           value="{{ old('data_reuniao', $conselho->data_reuniao->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('data_reuniao')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hora Início -->
                <div>
                    <label for="hora_inicio" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Hora de Início') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="time" 
                           id="hora_inicio" 
                           name="hora_inicio" 
                           value="{{ old('hora_inicio', $conselho->hora_inicio ? $conselho->hora_inicio->format('H:i') : '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('hora_inicio')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hora Fim -->
                <div>
                    <label for="hora_fim" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Hora de Fim') }}
                    </label>
                    <input type="time" 
                           id="hora_fim" 
                           name="hora_fim" 
                           value="{{ old('hora_fim', $conselho->hora_fim ? $conselho->hora_fim->format('H:i') : '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('hora_fim')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Local -->
                <div>
                    <label for="local" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Local') }}
                    </label>
                    <input type="text" 
                           id="local" 
                           name="local" 
                           value="{{ old('local', $conselho->local) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="{{ __('Ex: Sala de Reuniões') }}">
                    @error('local')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipo -->
                <div>
                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Tipo de Reunião') }} <span class="text-red-500">*</span>
                    </label>
                    <select id="tipo" 
                            name="tipo"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="reuniao_ordinaria" {{ old('tipo', $conselho->tipo) == 'reuniao_ordinaria' ? 'selected' : '' }}>{{ __('Reunião Ordinária') }}</option>
                        <option value="reuniao_extraordinaria" {{ old('tipo', $conselho->tipo) == 'reuniao_extraordinaria' ? 'selected' : '' }}>{{ __('Reunião Extraordinária') }}</option>
                        <option value="votacao" {{ old('tipo', $conselho->tipo) == 'votacao' ? 'selected' : '' }}>{{ __('Votação') }}</option>
                    </select>
                    @error('tipo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Presidente -->
                <div>
                    <label for="presidente_id" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Presidente') }}
                    </label>
                    <select id="presidente_id" 
                            name="presidente_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('Selecione...') }}</option>
                        @foreach($membros as $membro)
                            <option value="{{ $membro->id }}" {{ old('presidente_id', $conselho->presidente_id) == $membro->id ? 'selected' : '' }}>
                                {{ $membro->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('presidente_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Secretário -->
                <div>
                    <label for="secretario_id" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Secretário') }}
                    </label>
                    <select id="secretario_id" 
                            name="secretario_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('Selecione...') }}</option>
                        @foreach($membros as $membro)
                            <option value="{{ $membro->id }}" {{ old('secretario_id', $conselho->secretario_id) == $membro->id ? 'selected' : '' }}>
                                {{ $membro->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('secretario_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Quórum Mínimo -->
                <div>
                    <label for="quorum_minimo" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Quórum Mínimo (%)') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           id="quorum_minimo" 
                           name="quorum_minimo" 
                           value="{{ old('quorum_minimo', $conselho->quorum_minimo) }}"
                           min="1" 
                           max="100"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('quorum_minimo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Participantes -->
            <div class="border-t border-gray-200 pt-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('Participantes') }}</h3>
                    <div class="flex space-x-2">
                        <button type="button" 
                                onclick="marcarTodos()" 
                                class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            {{ __('Marcar Todos') }}
                        </button>
                        <button type="button" 
                                onclick="desmarcarTodos()" 
                                class="text-sm text-gray-600 hover:text-gray-800 font-medium">
                            {{ __('Desmarcar Todos') }}
                        </button>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <p class="text-sm text-gray-600 mb-3">{{ __('Selecione os participantes da reunião:') }}</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-64 overflow-y-auto">
                        @foreach($membros as $membro)
                            <div class="flex items-center p-2 bg-white rounded border hover:bg-gray-50">
                                <input type="checkbox" 
                                       id="membro_{{ $membro->id }}" 
                                       name="participantes[]" 
                                       value="{{ $membro->id }}"
                                       {{ in_array($membro->id, old('participantes', $participantes)) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="membro_{{ $membro->id }}" class="ml-3 text-sm text-gray-700 flex-1 cursor-pointer">
                                    <div class="font-medium">{{ $membro->name }}</div>
                                    <div class="text-xs text-gray-500">
                                        @if($membro->roles->count() > 0)
                                            {{ $membro->roles->pluck('display_name')->implode(', ') }}
                                        @endif
                                        @if($membro->cargos->count() > 0)
                                            @if($membro->roles->count() > 0) • @endif
                                            {{ $membro->cargos->pluck('nome')->implode(', ') }}
                                        @endif
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-3 text-xs text-gray-500">
                        {{ __('Total selecionado:') }} <span id="total-selecionados">0</span> {{ __('participantes') }}
                    </div>
                </div>
                
                @error('participantes')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Observações -->
            <div class="border-t border-gray-200 pt-6">
                <label for="observacoes" class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('Observações') }}
                </label>
                <textarea id="observacoes" 
                          name="observacoes" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="{{ __('Observações adicionais sobre a reunião...') }}">{{ old('observacoes', $conselho->observacoes) }}</textarea>
                @error('observacoes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botões de Ação -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.council.show', $conselho) }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                    {{ __('Cancelar') }}
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    {{ __('Atualizar Reunião') }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validação da hora fim
    const horaInicio = document.getElementById('hora_inicio');
    const horaFim = document.getElementById('hora_fim');
    
    horaInicio.addEventListener('change', function() {
        if (horaFim.value && horaFim.value <= horaInicio.value) {
            alert('{{ __("A hora de fim deve ser posterior à hora de início") }}');
            horaFim.value = '';
        }
    });
    
    // Validação do quórum
    const quorum = document.getElementById('quorum_minimo');
    quorum.addEventListener('change', function() {
        if (this.value < 1 || this.value > 100) {
            alert('{{ __("O quórum deve estar entre 1 e 100%") }}');
            this.value = 50;
        }
    });
    
    // Atualizar contador de participantes
    atualizarContadorParticipantes();
    
    // Adicionar listeners para checkboxes
    const checkboxes = document.querySelectorAll('input[name="participantes[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', atualizarContadorParticipantes);
    });
});

// Função para marcar todos os participantes
function marcarTodos() {
    const checkboxes = document.querySelectorAll('input[name="participantes[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    atualizarContadorParticipantes();
}

// Função para desmarcar todos os participantes
function desmarcarTodos() {
    const checkboxes = document.querySelectorAll('input[name="participantes[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    atualizarContadorParticipantes();
}

// Função para atualizar o contador de participantes selecionados
function atualizarContadorParticipantes() {
    const checkboxes = document.querySelectorAll('input[name="participantes[]"]');
    const selecionados = document.querySelectorAll('input[name="participantes[]"]:checked');
    const contador = document.getElementById('total-selecionados');
    
    contador.textContent = selecionados.length;
    
    // Destacar visualmente se há selecionados
    if (selecionados.length > 0) {
        contador.classList.add('font-bold', 'text-blue-600');
    } else {
        contador.classList.remove('font-bold', 'text-blue-600');
    }
}
</script>
@endpush
@endsection 