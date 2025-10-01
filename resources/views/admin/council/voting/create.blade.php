@extends('layouts.admin')

@section('title', __('Nova Votação'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Nova Votação') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Conselho:') }} <strong>{{ $conselho->titulo }}</strong></p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.council.voting.index', $conselho) }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('Voltar') }}
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('admin.council.voting.store', $conselho) }}" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Título -->
                <div class="md:col-span-2">
                    <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Título da Votação') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="titulo" 
                           name="titulo" 
                           value="{{ old('titulo') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="{{ __('Ex: Aprovação do orçamento') }}"
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
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="{{ __('Descrição detalhada da votação...') }}">{{ old('descricao') }}</textarea>
                    @error('descricao')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipo de Votação -->
                <div>
                    <label for="tipo_votacao" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Tipo de Votação') }}
                    </label>
                    <select id="tipo_votacao" 
                            name="tipo_votacao"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="simples" {{ old('tipo_votacao') == 'simples' ? 'selected' : '' }}>{{ __('Simples') }}</option>
                        <option value="qualificada" {{ old('tipo_votacao') == 'qualificada' ? 'selected' : '' }}>{{ __('Qualificada') }}</option>
                        <option value="secreta" {{ old('tipo_votacao') == 'secreta' ? 'selected' : '' }}>{{ __('Secreta') }}</option>
                    </select>
                    @error('tipo_votacao')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pauta Relacionada -->
                <div>
                    <label for="pauta_id" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Pauta Relacionada') }}
                    </label>
                    <select id="pauta_id" 
                            name="pauta_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('Nenhuma') }}</option>
                        @foreach($conselho->pautas as $pauta)
                            <option value="{{ $pauta->id }}" {{ old('pauta_id') == $pauta->id ? 'selected' : '' }}>
                                {{ $pauta->titulo }}
                            </option>
                        @endforeach
                    </select>
                    @error('pauta_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tempo Limite -->
                <div>
                    <label for="tempo_limite" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Tempo Limite (minutos)') }}
                    </label>
                    <input type="number" 
                           id="tempo_limite" 
                           name="tempo_limite" 
                           value="{{ old('tempo_limite', $configuracoes['tempo_votacao'] ?? 5) }}"
                           min="1" 
                           max="60"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('tempo_limite')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Quórum Mínimo -->
                <div>
                    <label for="quorum_minimo" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Quórum Mínimo') }}
                    </label>
                    <input type="number" 
                           id="quorum_minimo" 
                           name="quorum_minimo" 
                           value="{{ old('quorum_minimo', $conselho->quorum_minimo ?? $configuracoes['quorum_padrao'] ?? 50) }}"
                           min="1" 
                           max="{{ $conselho->participantes->count() }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('quorum_minimo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Opções de Votação -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Opções de Votação') }}</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="permitir_abstencao" 
                                   value="1"
                                   {{ old('permitir_abstencao', $configuracoes['permitir_abstencao'] ?? true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">{{ __('Permitir abstenção') }}</span>
                        </label>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="justificativa_obrigatoria" 
                                   value="1"
                                   {{ old('justificativa_obrigatoria', $configuracoes['justificativa_obrigatoria'] ?? false) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">{{ __('Justificativa obrigatória para votos contrários') }}</span>
                        </label>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="voto_anonimo" 
                                   value="1"
                                   {{ old('voto_anonimo', $configuracoes['voto_secreto_padrao'] ?? false) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">{{ __('Voto anônimo') }}</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Observações -->
            <div class="border-t border-gray-200 pt-6">
                <label for="observacoes" class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('Observações') }}
                </label>
                <textarea id="observacoes" 
                          name="observacoes" 
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="{{ __('Observações adicionais...') }}">{{ old('observacoes') }}</textarea>
                @error('observacoes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botões de Ação -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.council.voting.index', $conselho) }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                    {{ __('Cancelar') }}
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    {{ __('Criar Votação') }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validação do tempo limite
    const tempoLimite = document.getElementById('tempo_limite');
    tempoLimite.addEventListener('change', function() {
        if (this.value < 1 || this.value > 60) {
            alert('{{ __("Tempo limite deve estar entre 1 e 60 minutos") }}');
            this.value = 10;
        }
    });
    
    // Validação do quórum
    const quorumMinimo = document.getElementById('quorum_minimo');
    const totalParticipantes = {{ $conselho->participantes->count() }};
    
    quorumMinimo.addEventListener('change', function() {
        if (this.value < 1 || this.value > totalParticipantes) {
            alert('{{ __("Quórum deve estar entre 1 e") }} ' + totalParticipantes);
            this.value = {{ $conselho->quorum_minimo }};
        }
    });
});
</script>
@endpush
@endsection 