@extends('layouts.admin')

@section('title', 'Editar Evento')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Cabeçalho -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Editar Evento</h1>
                <p class="text-gray-600 mt-2">{{ $evento->titulo }}</p>
            </div>
            <a href="{{ route('admin.eventos.show', $evento) }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>

        <!-- Formulário -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <form method="POST" action="{{ route('admin.eventos.update', $evento) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Informações Básicas -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Informações Básicas</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">Título *</label>
                                <input type="text" id="titulo" name="titulo" value="{{ old('titulo', $evento->titulo) }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('titulo')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tipo_evento" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Evento *</label>
                                <select id="tipo_evento" name="tipo_evento" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Selecione...</option>
                                    <option value="culto" {{ old('tipo_evento', $evento->tipo_evento) === 'culto' ? 'selected' : '' }}>Culto</option>
                                    <option value="estudo" {{ old('tipo_evento', $evento->tipo_evento) === 'estudo' ? 'selected' : '' }}>Estudo Bíblico</option>
                                    <option value="reuniao" {{ old('tipo_evento', $evento->tipo_evento) === 'reuniao' ? 'selected' : '' }}>Reunião</option>
                                    <option value="conferencia" {{ old('tipo_evento', $evento->tipo_evento) === 'conferencia' ? 'selected' : '' }}>Conferência</option>
                                    <option value="outro" {{ old('tipo_evento', $evento->tipo_evento) === 'outro' ? 'selected' : '' }}>Outro</option>
                                </select>
                                @error('tipo_evento')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tipo_publico" class="block text-sm font-medium text-gray-700 mb-2">Público Alvo *</label>
                                <select id="tipo_publico" name="tipo_publico" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Selecione...</option>
                                    <option value="publico" {{ old('tipo_publico', $evento->tipo_publico) === 'publico' ? 'selected' : '' }}>Público Geral</option>
                                    <option value="membros" {{ old('tipo_publico', $evento->tipo_publico) === 'membros' ? 'selected' : '' }}>Apenas Membros</option>
                                    <option value="ambos" {{ old('tipo_publico', $evento->tipo_publico) === 'ambos' ? 'selected' : '' }}>Ambos</option>
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
                                    <option value="rascunho" {{ old('status', $evento->status) === 'rascunho' ? 'selected' : '' }}>Rascunho</option>
                                    <option value="ativo" {{ old('status', $evento->status) === 'ativo' ? 'selected' : '' }}>Ativo</option>
                                    <option value="cancelado" {{ old('status', $evento->status) === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                </select>
                                @error('status')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Data e Hora -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Data e Hora</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="data_inicio" class="block text-sm font-medium text-gray-700 mb-2">Data de Início *</label>
                                <input type="date" id="data_inicio" name="data_inicio" 
                                       value="{{ old('data_inicio', $evento->data_inicio ? $evento->data_inicio->format('Y-m-d') : '') }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('data_inicio')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="data_fim" class="block text-sm font-medium text-gray-700 mb-2">Data de Fim</label>
                                <input type="date" id="data_fim" name="data_fim" 
                                       value="{{ old('data_fim', $evento->data_fim ? $evento->data_fim->format('Y-m-d') : '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('data_fim')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="hora_inicio" class="block text-sm font-medium text-gray-700 mb-2">Hora de Início</label>
                                <input type="time" id="hora_inicio" name="hora_inicio" 
                                       value="{{ old('hora_inicio', $evento->hora_inicio ? $evento->hora_inicio->format('H:i') : '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('hora_inicio')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="hora_fim" class="block text-sm font-medium text-gray-700 mb-2">Hora de Fim</label>
                                <input type="time" id="hora_fim" name="hora_fim" 
                                       value="{{ old('hora_fim', $evento->hora_fim ? $evento->hora_fim->format('H:i') : '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('hora_fim')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Localização -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Localização</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="local" class="block text-sm font-medium text-gray-700 mb-2">Local</label>
                                <input type="text" id="local" name="local" value="{{ old('local', $evento->local) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('local')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="endereco" class="block text-sm font-medium text-gray-700 mb-2">Endereço</label>
                                <input type="text" id="endereco" name="endereco" value="{{ old('endereco', $evento->endereco) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('endereco')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Inscrições -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Configurações de Inscrição</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="inscricao_obrigatoria" class="flex items-center">
                                    <input type="checkbox" id="inscricao_obrigatoria" name="inscricao_obrigatoria" value="1"
                                           {{ old('inscricao_obrigatoria', $evento->inscricao_obrigatoria) ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Inscrição Obrigatória</span>
                                </label>
                            </div>

                            <div>
                                <label for="inscricao_ate" class="block text-sm font-medium text-gray-700 mb-2">Inscrições até</label>
                                <input type="datetime-local" id="inscricao_ate" name="inscricao_ate" 
                                       value="{{ old('inscricao_ate', $evento->inscricao_ate ? $evento->inscricao_ate->format('Y-m-d\TH:i') : '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('inscricao_ate')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="vagas_totais" class="block text-sm font-medium text-gray-700 mb-2">Total de Vagas</label>
                                <input type="number" id="vagas_totais" name="vagas_totais" 
                                       value="{{ old('vagas_totais', $evento->vagas_totais) }}" min="0"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('vagas_totais')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="destaque" class="flex items-center">
                                    <input type="checkbox" id="destaque" name="destaque" value="1"
                                           {{ old('destaque', $evento->destaque) ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Evento em Destaque</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Valor -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Valor</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="gratuito" class="flex items-center">
                                    <input type="checkbox" id="gratuito" name="gratuito" value="1"
                                           {{ old('gratuito', $evento->gratuito) ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Evento Gratuito</span>
                                </label>
                            </div>

                            <div>
                                <label for="valor_inscricao" class="block text-sm font-medium text-gray-700 mb-2">Valor da Inscrição</label>
                                <input type="number" id="valor_inscricao" name="valor_inscricao" 
                                       value="{{ old('valor_inscricao', $evento->valor_inscricao) }}" min="0" step="0.01"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('valor_inscricao')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Organização -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Organização</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="organizador_id" class="block text-sm font-medium text-gray-700 mb-2">Organizador</label>
                                <select id="organizador_id" name="organizador_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Selecione...</option>
                                    @foreach($organizadores as $organizador)
                                        <option value="{{ $organizador->id }}" 
                                                {{ old('organizador_id', $evento->organizador_id) == $organizador->id ? 'selected' : '' }}>
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
                                        <option value="{{ $ministerio->id }}" 
                                                {{ old('ministerio_id', $evento->ministerio_id) == $ministerio->id ? 'selected' : '' }}>
                                            {{ $ministerio->nome }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ministerio_id')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Descrições -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Descrições</h2>
                        
                        <div class="space-y-6">
                            <div>
                                <label for="descricao_curta" class="block text-sm font-medium text-gray-700 mb-2">Descrição Curta</label>
                                <input type="text" id="descricao_curta" name="descricao_curta" 
                                       value="{{ old('descricao_curta', $evento->descricao_curta) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('descricao_curta')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">Descrição Completa</label>
                                <textarea id="descricao" name="descricao" rows="6"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('descricao', $evento->descricao) }}</textarea>
                                @error('descricao')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="regulamento" class="block text-sm font-medium text-gray-700 mb-2">Regulamento</label>
                                <textarea id="regulamento" name="regulamento" rows="4"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('regulamento', $evento->regulamento) }}</textarea>
                                @error('regulamento')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="informacoes_adicionais" class="block text-sm font-medium text-gray-700 mb-2">Informações Adicionais</label>
                                <textarea id="informacoes_adicionais" name="informacoes_adicionais" rows="4"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('informacoes_adicionais', $evento->informacoes_adicionais) }}</textarea>
                                @error('informacoes_adicionais')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Imagem -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Imagem</h2>
                        
                        <div>
                            <label for="imagem" class="block text-sm font-medium text-gray-700 mb-2">Imagem do Evento</label>
                            <input type="file" id="imagem" name="imagem" accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('imagem')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            
                            @if($evento->imagem)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600">Imagem atual:</p>
                                    <img src="{{ Storage::url($evento->imagem) }}" alt="Imagem atual" class="w-32 h-32 object-cover rounded-lg mt-2">
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('admin.eventos.show', $evento) }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                            <i class="fas fa-save mr-2"></i>Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle para evento gratuito
    const gratuitoCheckbox = document.getElementById('gratuito');
    const valorInput = document.getElementById('valor_inscricao');
    
    function toggleValorInput() {
        if (gratuitoCheckbox.checked) {
            valorInput.value = '0';
            valorInput.disabled = true;
        } else {
            valorInput.disabled = false;
        }
    }
    
    gratuitoCheckbox.addEventListener('change', toggleValorInput);
    toggleValorInput(); // Executar na carga da página
});
</script>
@endsection 