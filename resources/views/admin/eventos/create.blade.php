@extends('layouts.admin')

@section('title', 'Criar Novo Evento')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Criar Novo Evento</h1>
                <p class="text-gray-600 mt-2">Preencha as informações do evento</p>
            </div>
            <a href="{{ route('admin.eventos.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>

        <div class="bg-white rounded-lg shadow">
            <form method="POST" action="{{ route('admin.eventos.store') }}" enctype="multipart/form-data" class="p-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Informações Básicas -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informações Básicas</h3>
                    </div>

                    <div class="md:col-span-2">
                        <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">Título do Evento *</label>
                        <input type="text" id="titulo" name="titulo" value="{{ old('titulo') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('titulo')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">Descrição *</label>
                        <textarea id="descricao" name="descricao" rows="4" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('descricao') }}</textarea>
                        @error('descricao')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="descricao_curta" class="block text-sm font-medium text-gray-700 mb-2">Descrição Curta</label>
                        <input type="text" id="descricao_curta" name="descricao_curta" value="{{ old('descricao_curta') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('descricao_curta')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tipo_evento" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Evento *</label>
                        <select id="tipo_evento" name="tipo_evento" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Selecione...</option>
                            <option value="culto" {{ old('tipo_evento') === 'culto' ? 'selected' : '' }}>Culto</option>
                            <option value="estudo" {{ old('tipo_evento') === 'estudo' ? 'selected' : '' }}>Estudo Bíblico</option>
                            <option value="reuniao" {{ old('tipo_evento') === 'reuniao' ? 'selected' : '' }}>Reunião</option>
                            <option value="conferencia" {{ old('tipo_evento') === 'conferencia' ? 'selected' : '' }}>Conferência</option>
                            <option value="outro" {{ old('tipo_evento') === 'outro' ? 'selected' : '' }}>Outro</option>
                        </select>
                        @error('tipo_evento')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Data e Hora -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Data e Hora</h3>
                    </div>

                    <div>
                        <label for="data_inicio" class="block text-sm font-medium text-gray-700 mb-2">Data de Início *</label>
                        <input type="date" id="data_inicio" name="data_inicio" value="{{ old('data_inicio') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('data_inicio')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="data_fim" class="block text-sm font-medium text-gray-700 mb-2">Data de Fim</label>
                        <input type="date" id="data_fim" name="data_fim" value="{{ old('data_fim') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('data_fim')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="hora_inicio" class="block text-sm font-medium text-gray-700 mb-2">Hora de Início</label>
                        <input type="time" id="hora_inicio" name="hora_inicio" value="{{ old('hora_inicio') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('hora_inicio')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="hora_fim" class="block text-sm font-medium text-gray-700 mb-2">Hora de Fim</label>
                        <input type="time" id="hora_fim" name="hora_fim" value="{{ old('hora_fim') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('hora_fim')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Local -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Local</h3>
                    </div>

                    <div>
                        <label for="local" class="block text-sm font-medium text-gray-700 mb-2">Local</label>
                        <input type="text" id="local" name="local" value="{{ old('local') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('local')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="endereco" class="block text-sm font-medium text-gray-700 mb-2">Endereço</label>
                        <input type="text" id="endereco" name="endereco" value="{{ old('endereco') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('endereco')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cidade" class="block text-sm font-medium text-gray-700 mb-2">Cidade</label>
                        <input type="text" id="cidade" name="cidade" value="{{ old('cidade') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('cidade')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                        <input type="text" id="estado" name="estado" value="{{ old('estado') }}" maxlength="2"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('estado')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cep" class="block text-sm font-medium text-gray-700 mb-2">CEP</label>
                        <input type="text" id="cep" name="cep" value="{{ old('cep') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('cep')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Configurações -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Configurações</h3>
                    </div>

                    <div>
                        <label for="tipo_publico" class="block text-sm font-medium text-gray-700 mb-2">Público Alvo *</label>
                        <select id="tipo_publico" name="tipo_publico" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Selecione...</option>
                            <option value="membros" {{ old('tipo_publico') === 'membros' ? 'selected' : '' }}>Apenas Membros</option>
                            <option value="publico" {{ old('tipo_publico') === 'publico' ? 'selected' : '' }}>Público Geral</option>
                            <option value="ambos" {{ old('tipo_publico') === 'ambos' ? 'selected' : '' }}>Membros e Público</option>
                        </select>
                        @error('tipo_publico')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select id="status" name="status" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Selecione...</option>
                            <option value="rascunho" {{ old('status') === 'rascunho' ? 'selected' : '' }}>Rascunho</option>
                            <option value="ativo" {{ old('status') === 'ativo' ? 'selected' : '' }}>Ativo</option>
                            <option value="cancelado" {{ old('status') === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                            <option value="finalizado" {{ old('status') === 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                        </select>
                        @error('status')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="organizador_id" class="block text-sm font-medium text-gray-700 mb-2">Organizador</label>
                        <select id="organizador_id" name="organizador_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Selecione...</option>
                            @foreach($organizadores as $organizador)
                                <option value="{{ $organizador->id }}" {{ old('organizador_id') == $organizador->id ? 'selected' : '' }}>
                                    {{ $organizador->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('organizador_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="ministerio_id" class="block text-sm font-medium text-gray-700 mb-2">Ministério</label>
                        <select id="ministerio_id" name="ministerio_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Selecione...</option>
                            @foreach($ministerios as $ministerio)
                                <option value="{{ $ministerio->id }}" {{ old('ministerio_id') == $ministerio->id ? 'selected' : '' }}>
                                    {{ $ministerio->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('ministerio_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Inscrições -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Inscrições</h3>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="inscricao_obrigatoria" name="inscricao_obrigatoria" value="1" 
                               {{ old('inscricao_obrigatoria') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="inscricao_obrigatoria" class="ml-2 block text-sm text-gray-900">
                            Inscrição obrigatória
                        </label>
                    </div>

                    <div>
                        <label for="inscricao_ate" class="block text-sm font-medium text-gray-700 mb-2">Inscrições até</label>
                        <input type="datetime-local" id="inscricao_ate" name="inscricao_ate" value="{{ old('inscricao_ate') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('inscricao_ate')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="gratuito" name="gratuito" value="1" 
                               {{ old('gratuito', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="gratuito" class="ml-2 block text-sm text-gray-900">
                            Evento gratuito
                        </label>
                    </div>

                    <div>
                        <label for="valor_inscricao" class="block text-sm font-medium text-gray-700 mb-2">Valor da Inscrição</label>
                        <input type="number" id="valor_inscricao" name="valor_inscricao" value="{{ old('valor_inscricao') }}" 
                               step="0.01" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('valor_inscricao')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="vagas_totais" class="block text-sm font-medium text-gray-700 mb-2">Vagas Totais</label>
                        <input type="number" id="vagas_totais" name="vagas_totais" value="{{ old('vagas_totais') }}" 
                               min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('vagas_totais')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Opções -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Opções</h3>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="destaque" name="destaque" value="1" 
                               {{ old('destaque') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="destaque" class="ml-2 block text-sm text-gray-900">
                            Evento em destaque
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="ativo" name="ativo" value="1" 
                               {{ old('ativo', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="ativo" class="ml-2 block text-sm text-gray-900">
                            Evento ativo
                        </label>
                    </div>

                    <!-- Imagem -->
                    <div class="md:col-span-2">
                        <label for="imagem" class="block text-sm font-medium text-gray-700 mb-2">Imagem do Evento</label>
                        <input type="file" id="imagem" name="imagem" accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('imagem')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Informações Adicionais -->
                    <div class="md:col-span-2">
                        <label for="regulamento" class="block text-sm font-medium text-gray-700 mb-2">Regulamento</label>
                        <textarea id="regulamento" name="regulamento" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('regulamento') }}</textarea>
                        @error('regulamento')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="informacoes_adicionais" class="block text-sm font-medium text-gray-700 mb-2">Informações Adicionais</label>
                        <textarea id="informacoes_adicionais" name="informacoes_adicionais" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('informacoes_adicionais') }}</textarea>
                        @error('informacoes_adicionais')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('admin.eventos.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        <i class="fas fa-save mr-2"></i>Criar Evento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const gratuitoCheckbox = document.getElementById('gratuito');
    const valorInscricaoInput = document.getElementById('valor_inscricao');
    
    function toggleValorInscricao() {
        if (gratuitoCheckbox.checked) {
            valorInscricaoInput.value = '';
            valorInscricaoInput.disabled = true;
        } else {
            valorInscricaoInput.disabled = false;
        }
    }
    
    gratuitoCheckbox.addEventListener('change', toggleValorInscricao);
    toggleValorInscricao(); // Executar na carga inicial
});
</script>
@endsection 