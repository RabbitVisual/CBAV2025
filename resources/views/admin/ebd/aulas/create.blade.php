@extends('layouts.admin')

@section('title', 'Nova Aula EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Nova Aula EBD</h1>
                <p class="text-gray-600">Agende uma nova aula para a Escola Bíblica Dominical</p>
            </div>
            <a href="{{ route('admin.ebd.aulas.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.ebd.aulas.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Turma -->
                <div class="md:col-span-2">
                    <label for="turma_id" class="block text-sm font-medium text-gray-700 mb-2">Turma *</label>
                    <select name="turma_id" id="turma_id" required 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecione uma turma</option>
                        @foreach($turmas as $turma)
                            <option value="{{ $turma->id }}" {{ old('turma_id') == $turma->id ? 'selected' : '' }}>
                                {{ $turma->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('turma_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lição -->
                <div class="md:col-span-2">
                    <label for="licao_id" class="block text-sm font-medium text-gray-700 mb-2">Lição *</label>
                    <select name="licao_id" id="licao_id" required 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecione uma lição</option>
                        @foreach($licoes as $licao)
                            <option value="{{ $licao->id }}" {{ old('licao_id') == $licao->id ? 'selected' : '' }}>
                                {{ $licao->titulo }}
                            </option>
                        @endforeach
                    </select>
                    @error('licao_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Professor -->
                <div class="md:col-span-2">
                    <label for="professor_id" class="block text-sm font-medium text-gray-700 mb-2">Professor</label>
                    <select name="professor_id" id="professor_id"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecione um professor (opcional)</option>
                        @foreach($professores as $professor)
                            <option value="{{ $professor->id }}" {{ old('professor_id') == $professor->id ? 'selected' : '' }}>
                                {{ $professor->membro ? $professor->membro->nome : $professor->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('professor_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Data da Aula -->
                <div>
                    <label for="data_aula" class="block text-sm font-medium text-gray-700 mb-2">Data da Aula *</label>
                    <input type="date" name="data_aula" id="data_aula" value="{{ old('data_aula', date('Y-m-d')) }}" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('data_aula')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" id="status" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecione o status</option>
                        <option value="agendada" {{ old('status') == 'agendada' ? 'selected' : '' }}>Agendada</option>
                        <option value="realizada" {{ old('status') == 'realizada' ? 'selected' : '' }}>Realizada</option>
                        <option value="cancelada" {{ old('status') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        <option value="adiada" {{ old('status') == 'adiada' ? 'selected' : '' }}>Adiada</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Horário de Início -->
                <div>
                    <label for="horario_inicio" class="block text-sm font-medium text-gray-700 mb-2">Horário de Início *</label>
                    <input type="time" name="horario_inicio" id="horario_inicio" value="{{ old('horario_inicio', '08:00') }}" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('horario_inicio')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Horário de Fim -->
                <div>
                    <label for="horario_fim" class="block text-sm font-medium text-gray-700 mb-2">Horário de Fim *</label>
                    <input type="time" name="horario_fim" id="horario_fim" value="{{ old('horario_fim', '09:00') }}" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('horario_fim')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Observações -->
                <div class="md:col-span-2">
                    <label for="observacoes" class="block text-sm font-medium text-gray-700 mb-2">Observações</label>
                    <textarea name="observacoes" id="observacoes" rows="4"
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Observações sobre a aula...">{{ old('observacoes') }}</textarea>
                    @error('observacoes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Agendar Aula
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const horarioInicio = document.getElementById('horario_inicio');
    const horarioFim = document.getElementById('horario_fim');
    
    // Validar que o horário de fim seja posterior ao de início
    horarioFim.addEventListener('change', function() {
        if (horarioInicio.value && this.value && this.value <= horarioInicio.value) {
            alert('O horário de fim deve ser posterior ao horário de início.');
            this.value = '';
        }
    });
    
    horarioInicio.addEventListener('change', function() {
        if (horarioFim.value && this.value && horarioFim.value <= this.value) {
            alert('O horário de fim deve ser posterior ao horário de início.');
            horarioFim.value = '';
        }
    });
});
</script>
@endsection 