@extends('layouts.admin')

@section('title', __('Usar Template de Pauta'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Usar Template de Pauta') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Criar nova reunião usando o template "{{ $template->nome }}"') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.council.agenda.template.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('Voltar') }}
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.council.store') }}" class="space-y-6">
        @csrf
        
        <!-- Informações da Reunião -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('Informações da Reunião') }}</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Título da Reunião') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="titulo" 
                           name="titulo" 
                           value="{{ old('titulo', $template->nome . ' - ' . now()->format('d/m/Y')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('titulo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Tipo de Reunião') }} <span class="text-red-500">*</span>
                    </label>
                    <select id="tipo" 
                            name="tipo"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="reuniao_ordinaria" {{ old('tipo') == 'reuniao_ordinaria' ? 'selected' : '' }}>{{ __('Reunião Ordinária') }}</option>
                        <option value="reuniao_extraordinaria" {{ old('tipo') == 'reuniao_extraordinaria' ? 'selected' : '' }}>{{ __('Reunião Extraordinária') }}</option>
                        <option value="votacao" {{ old('tipo') == 'votacao' ? 'selected' : '' }}>{{ __('Votação') }}</option>
                    </select>
                    @error('tipo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="data_reuniao" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Data da Reunião') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           id="data_reuniao" 
                           name="data_reuniao" 
                           value="{{ old('data_reuniao', now()->addDays(7)->format('Y-m-d')) }}"
                           min="{{ now()->format('Y-m-d') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('data_reuniao')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="hora_inicio" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Hora de Início') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="time" 
                           id="hora_inicio" 
                           name="hora_inicio" 
                           value="{{ old('hora_inicio', '19:00') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('hora_inicio')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="hora_fim" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Hora de Fim') }}
                    </label>
                    <input type="time" 
                           id="hora_fim" 
                           name="hora_fim" 
                           value="{{ old('hora_fim', '21:00') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('hora_fim')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="local" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Local') }}
                    </label>
                    <input type="text" 
                           id="local" 
                           name="local" 
                           value="{{ old('local', 'Sala de Reuniões') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="{{ __('Local da reunião') }}">
                    @error('local')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="quorum_minimo" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Quórum Mínimo') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           id="quorum_minimo" 
                           name="quorum_minimo" 
                           value="{{ old('quorum_minimo', $configuracoes['quorum_padrao'] ?? 50) }}"
                           min="1"
                           max="100"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('quorum_minimo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Descrição') }}
                </label>
                <textarea id="descricao" 
                          name="descricao" 
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="{{ __('Descrição da reunião...') }}">{{ old('descricao', $template->descricao) }}</textarea>
                @error('descricao')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Participantes -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('Participantes') }}</h2>
            
            <div class="mb-4">
                <div class="flex space-x-3 mb-4">
                    <button type="button" 
                            onclick="marcarTodos()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-check-double mr-2"></i>
                        {{ __('Marcar Todos') }}
                    </button>
                    <button type="button" 
                            onclick="desmarcarTodos()"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-times mr-2"></i>
                        {{ __('Desmarcar Todos') }}
                    </button>
                </div>
                <p class="text-sm text-gray-600">
                    {{ __('Selecionados:') }} <span id="contador-selecionados">0</span> {{ __('participantes') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($membros as $membro)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition duration-200">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" 
                                   name="participantes[]" 
                                   value="{{ $membro->id }}"
                                   class="participante-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                   onchange="atualizarContador()">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $membro->name }}</p>
                                <p class="text-sm text-gray-600">
                                    @if($membro->roles->count() > 0)
                                        {{ $membro->roles->first()->name }}
                                    @elseif($membro->cargos->count() > 0)
                                        {{ $membro->cargos->first()->nome }}
                                    @else
                                        {{ __('Membro') }}
                                    @endif
                                </p>
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Pautas do Template -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('Pautas do Template') }}</h2>
            
            <div class="space-y-4">
                @foreach($template->itens as $index => $item)
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-lg font-medium text-gray-900">{{ $item->titulo }}</h3>
                            <div class="flex space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->tipo_color }}">
                                    {{ $item->tipo_text }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->prioridade_color }}">
                                    {{ $item->prioridade_text }}
                                </span>
                            </div>
                        </div>
                        
                        @if($item->descricao)
                            <p class="text-gray-600 mb-2">{{ $item->descricao }}</p>
                        @endif
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-gray-600">
                            <div>
                                <span class="font-medium">{{ __('Ordem:') }}</span> {{ $item->ordem }}
                            </div>
                            @if($item->tempo_estimado)
                                <div>
                                    <span class="font-medium">{{ __('Tempo:') }}</span> {{ $item->tempo_estimado_formatado }}
                                </div>
                            @endif
                            @if($item->responsavel)
                                <div>
                                    <span class="font-medium">{{ __('Responsável:') }}</span> {{ $item->responsavel->name }}
                                </div>
                            @endif
                            @if($item->observacoes)
                                <div>
                                    <span class="font-medium">{{ __('Observações:') }}</span> {{ $item->observacoes }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Campos Ocultos para Pautas -->
        @foreach($template->itens as $index => $item)
            <input type="hidden" name="pautas[{{ $index }}][titulo]" value="{{ $item->titulo }}">
            <input type="hidden" name="pautas[{{ $index }}][descricao]" value="{{ $item->descricao }}">
            <input type="hidden" name="pautas[{{ $index }}][tipo]" value="{{ $item->tipo }}">
            <input type="hidden" name="pautas[{{ $index }}][prioridade]" value="{{ $item->prioridade }}">
            <input type="hidden" name="pautas[{{ $index }}][ordem]" value="{{ $item->ordem }}">
            <input type="hidden" name="pautas[{{ $index }}][tempo_estimado]" value="{{ $item->tempo_estimado }}">
            <input type="hidden" name="pautas[{{ $index }}][responsavel_id]" value="{{ $item->responsavel_id }}">
            <input type="hidden" name="pautas[{{ $index }}][observacoes]" value="{{ $item->observacoes }}">
        @endforeach

        <!-- Botões de Ação -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.council.agenda.template.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                {{ __('Cancelar') }}
            </a>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-save mr-2"></i>
                {{ __('Criar Reunião com Template') }}
            </button>
        </div>
    </form>
</div>

@include('components.modals')

@push('scripts')
<script>
// Função para marcar todos os participantes
function marcarTodos() {
    const checkboxes = document.querySelectorAll('.participante-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    atualizarContador();
}

// Função para desmarcar todos os participantes
function desmarcarTodos() {
    const checkboxes = document.querySelectorAll('.participante-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    atualizarContador();
}

// Função para atualizar contador de selecionados
function atualizarContador() {
    const checkboxes = document.querySelectorAll('.participante-checkbox:checked');
    const contador = document.getElementById('contador-selecionados');
    contador.textContent = checkboxes.length;
}

// Inicializar contador
document.addEventListener('DOMContentLoaded', function() {
    atualizarContador();
});

// Validação do formulário
document.querySelector('form').addEventListener('submit', function(e) {
    const participantes = document.querySelectorAll('.participante-checkbox:checked');
    
    if (participantes.length === 0) {
        e.preventDefault();
        showErrorModal('{{ __("Selecione pelo menos um participante.") }}');
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