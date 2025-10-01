@extends('layouts.admin')

@section('title', __('Nova Reunião do Conselho'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Nova Reunião do Conselho') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Crie uma nova reunião do conselho') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.council.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('Voltar') }}
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <!-- Seção de Ajuda sobre Templates -->
        @if($templates->count() > 0)
        <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-lightbulb text-blue-600 text-lg"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">{{ __('Dica sobre Templates') }}</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>{{ __('Você pode selecionar um template de agenda para pré-carregar automaticamente os itens da pauta da reunião. Isso economiza tempo e garante consistência nas reuniões.') }}</p>
                        <p class="mt-1">{{ __('Templates disponíveis:') }} <strong>{{ $templates->count() }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.council.store') }}" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Título -->
                <div class="md:col-span-2">
                    <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Título da Reunião') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="titulo" 
                           name="titulo" 
                           value="{{ old('titulo') }}"
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
                              placeholder="{{ __('Descrição detalhada da reunião...') }}">{{ old('descricao') }}</textarea>
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
                           value="{{ old('data_reuniao') }}"
                           min="{{ date('Y-m-d') }}"
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
                           value="{{ old('hora_inicio') }}"
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
                           value="{{ old('hora_fim') }}"
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
                           value="{{ old('local') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="{{ __('Ex: Sala de Reuniões') }}">
                    @error('local')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipo de Reunião -->
                <div>
                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Tipo de Reunião') }}
                    </label>
                    <select id="tipo" 
                            name="tipo"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="ordinaria" {{ old('tipo') == 'ordinaria' ? 'selected' : '' }}>{{ __('Ordinária') }}</option>
                        <option value="extraordinaria" {{ old('tipo') == 'extraordinaria' ? 'selected' : '' }}>{{ __('Extraordinária') }}</option>
                        <option value="emergencial" {{ old('tipo') == 'emergencial' ? 'selected' : '' }}>{{ __('Emergencial') }}</option>
                    </select>
                    @error('tipo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Quórum Mínimo -->
                <div>
                    <label for="quorum_minimo" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Quórum Mínimo (%)') }}
                    </label>
                    <input type="number" 
                           id="quorum_minimo" 
                           name="quorum_minimo" 
                           value="{{ old('quorum_minimo', $configuracoes['quorum_padrao'] ?? 50) }}"
                           min="1" 
                           max="100"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('quorum_minimo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Duração Estimada -->
                <div>
                    <label for="duracao_estimada" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Duração Estimada (minutos)') }}
                    </label>
                    <input type="number" 
                           id="duracao_estimada" 
                           name="duracao_estimada" 
                           value="{{ old('duracao_estimada', $configuracoes['duracao_padrao'] ?? 120) }}"
                           min="30" 
                           max="480"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('duracao_estimada')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Templates de Agenda -->
            @if($templates->count() > 0)
            <div class="border-t border-gray-200 pt-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('Template de Agenda') }}</h3>
                    <span class="text-sm text-gray-500">{{ __('Opcional - Economize tempo') }}</span>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-3">{{ __('Selecione um template para pré-carregar a agenda da reunião:') }}</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($templates as $template)
                            <div class="flex items-start p-3 bg-white rounded border hover:bg-gray-50 cursor-pointer template-option transition-all duration-200 hover:shadow-md"
                                 data-template-id="{{ $template->id }}"
                                 data-template-items="{{ $template->itens_pauta ?? '[]' }}">
                                <input type="radio" 
                                       id="template_{{ $template->id }}" 
                                       name="template_id" 
                                       value="{{ $template->id }}"
                                       class="mt-1 rounded-full border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="template_{{ $template->id }}" class="ml-3 text-sm text-gray-700 flex-1 cursor-pointer">
                                    <div class="font-medium text-gray-900">{{ $template->nome }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $template->descricao }}</div>
                                    @if($template->categoria)
                                        <div class="mt-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                @if($template->categoria == 'reuniao_ordinaria') bg-green-100 text-green-800
                                                @elseif($template->categoria == 'reuniao_extraordinaria') bg-yellow-100 text-yellow-800
                                                @elseif($template->categoria == 'votacao') bg-red-100 text-red-800
                                                @else bg-blue-100 text-blue-800 @endif">
                                                @if($template->categoria == 'reuniao_ordinaria')
                                                    <i class="fas fa-calendar-day mr-1"></i>
                                                @elseif($template->categoria == 'reuniao_extraordinaria')
                                                    <i class="fas fa-calendar-plus mr-1"></i>
                                                @elseif($template->categoria == 'votacao')
                                                    <i class="fas fa-vote-yea mr-1"></i>
                                                @else
                                                    <i class="fas fa-file-alt mr-1"></i>
                                                @endif
                                                {{ ucfirst(str_replace('_', ' ', $template->categoria)) }}
                                            </span>
                                        </div>
                                    @endif
                                    @if($template->itens_pauta)
                                        @php
                                            $itens = json_decode($template->itens_pauta, true);
                                            $totalItens = is_array($itens) ? count($itens) : 0;
                                        @endphp
                                        <div class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-list-ul mr-1"></i>
                                            {{ $totalItens }} {{ __('itens na agenda') }}
                                        </div>
                                    @endif
                                </label>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-3 text-xs text-gray-500 flex items-center">
                        <i class="fas fa-info-circle mr-1"></i>
                        {{ __('Selecione um template para ver os itens da agenda e pré-carregar automaticamente') }}
                    </div>
                </div>
                
                <!-- Preview dos itens do template -->
                <div id="template-preview" class="mt-4 hidden">
                    <h4 class="text-md font-medium text-gray-900 mb-2 flex items-center">
                        <i class="fas fa-eye mr-2 text-blue-600"></i>
                        {{ __('Preview da Agenda') }}
                    </h4>
                    <div id="template-items" class="bg-blue-50 rounded-lg p-4 text-sm text-gray-700 border border-blue-200">
                        <!-- Itens serão carregados aqui via JavaScript -->
                    </div>
                    <div class="mt-2 text-xs text-blue-600">
                        <i class="fas fa-check-circle mr-1"></i>
                        {{ __('Estes itens serão criados automaticamente na reunião') }}
                    </div>
                </div>
            </div>
            @endif

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
                                       {{ in_array($membro->id, old('participantes', [])) ? 'checked' : '' }}
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
                          placeholder="{{ __('Observações adicionais sobre a reunião...') }}">{{ old('observacoes') }}</textarea>
                @error('observacoes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botões de Ação -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.council.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                    {{ __('Cancelar') }}
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    {{ __('Criar Reunião') }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validação da data
    const dataReuniao = document.getElementById('data_reuniao');
    const hoje = new Date().toISOString().split('T')[0];
    dataReuniao.min = hoje;
    
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
    
    // Validação da duração
    const duracao = document.getElementById('duracao_estimada');
    duracao.addEventListener('change', function() {
        if (this.value < 30 || this.value > 480) {
            alert('{{ __("A duração deve estar entre 30 e 480 minutos") }}');
            this.value = 120;
        }
    });
    
    // Atualizar contador de participantes
    atualizarContadorParticipantes();
    
    // Adicionar listeners para checkboxes
    const checkboxes = document.querySelectorAll('input[name="participantes[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', atualizarContadorParticipantes);
    });

    // Gerenciar seleção de templates
    const templateOptions = document.querySelectorAll('.template-option');
    const templatePreview = document.getElementById('template-preview');
    const templateItems = document.getElementById('template-items');

    function mostrarItensTemplate(templateItemsData) {
        try {
            const items = JSON.parse(templateItemsData);
            if (items && items.length > 0) {
                let html = '<div class="space-y-2">';
                items.forEach((item, index) => {
                    html += `<div class="flex items-center p-2 bg-white rounded border-l-4 border-blue-500">
                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-medium mr-3">
                            ${index + 1}
                        </span>
                        <span class="text-gray-700">${item}</span>
                    </div>`;
                });
                html += '</div>';
                
                templateItems.innerHTML = html;
                templatePreview.classList.remove('hidden');
                
                // Adicionar efeito de fade in
                templatePreview.style.opacity = '0';
                templatePreview.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    templatePreview.style.transition = 'all 0.3s ease';
                    templatePreview.style.opacity = '1';
                    templatePreview.style.transform = 'translateY(0)';
                }, 10);
            } else {
                templateItems.innerHTML = '<div class="text-center py-4"><i class="fas fa-info-circle text-gray-400 text-2xl mb-2"></i><p class="text-gray-500">{{ __("Este template não possui itens de agenda definidos") }}</p></div>';
                templatePreview.classList.remove('hidden');
            }
        } catch (e) {
            templateItems.innerHTML = '<div class="text-center py-4"><i class="fas fa-exclamation-triangle text-red-400 text-2xl mb-2"></i><p class="text-red-500">{{ __("Erro ao carregar itens do template") }}</p></div>';
            templatePreview.classList.remove('hidden');
        }
    }

    // Adicionar efeitos visuais aos templates
    templateOptions.forEach(option => {
        const radio = option.querySelector('input[type="radio"]');
        const templateId = option.dataset.templateId;
        const templateItemsData = option.dataset.templateItems;
        
        // Adicionar listener para o radio
        radio.addEventListener('change', function() {
            if (this.checked) {
                mostrarItensTemplate(templateItemsData);
            }
        });

        // Adicionar listener para clicar na área toda
        option.addEventListener('click', function(e) {
            if (e.target.type !== 'radio') {
                radio.checked = true;
                radio.dispatchEvent(new Event('change'));
            }
        });
        
        // Efeito hover
        option.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 12px rgba(0,0,0,0.1)';
        });
        
        option.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
        
        // Efeito de seleção
        radio.addEventListener('change', function() {
            // Remover seleção anterior
            templateOptions.forEach(opt => {
                opt.classList.remove('ring-2', 'ring-blue-500', 'border-blue-500');
                opt.classList.add('border-gray-200');
            });
            
            // Marcar seleção atual
            if (this.checked) {
                option.classList.remove('border-gray-200');
                option.classList.add('ring-2', 'ring-blue-500', 'border-blue-500');
            }
        });
    });

    // Limpar preview quando nenhum template for selecionado
    const templateRadios = document.querySelectorAll('input[name="template_id"]');
    templateRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (!document.querySelector('input[name="template_id"]:checked')) {
                templatePreview.classList.add('hidden');
            }
        });
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