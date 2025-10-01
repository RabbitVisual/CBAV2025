@extends('layouts.admin')

@section('title', __('Editar Devocional'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Editar Devocional') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Edite o devocional selecionado') }}</p>
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
        <form method="POST" action="{{ route('admin.devotionals.update', $devocional) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Título -->
                <div class="md:col-span-2">
                    <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Título') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="titulo" 
                           name="titulo" 
                           value="{{ old('titulo', $devocional->titulo) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="{{ __('Ex: Devocional do Dia') }}"
                           required>
                    @error('titulo')
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
                        <option value="devocional" {{ old('tipo', $devocional->tipo) == 'devocional' ? 'selected' : '' }}>
                            {{ __('Devocional') }}
                        </option>
                        <option value="versiculo" {{ old('tipo', $devocional->tipo) == 'versiculo' ? 'selected' : '' }}>
                            {{ __('Versículo') }}
                        </option>
                        <option value="oracao" {{ old('tipo', $devocional->tipo) == 'oracao' ? 'selected' : '' }}>
                            {{ __('Oração') }}
                        </option>
                    </select>
                    @error('tipo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Data -->
                <div>
                    <label for="data" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Data') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           id="data" 
                           name="data" 
                           value="{{ old('data', $devocional->data->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('data')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ordem -->
                <div>
                    <label for="ordem" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Ordem') }}
                    </label>
                    <input type="number" 
                           id="ordem" 
                           name="ordem" 
                           value="{{ old('ordem', $devocional->ordem) }}"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('ordem')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="ativo" 
                               value="1"
                               {{ old('ativo', $devocional->ativo) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">{{ __('Ativo') }}</span>
                    </label>
                </div>

                <!-- Busca de Versículo -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Buscar Versículo na Bíblia') }}
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-2 mb-2">
                        <div class="md:col-span-1">
                            <select id="versao_biblia" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="almeida_ra">{{ __('Almeida Revista e Atualizada') }}</option>
                                <option value="almeida_rc">{{ __('Almeida Revista e Corrigida') }}</option>
                                <option value="blivre">{{ __('Bíblia Livre') }}</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <input type="text" 
                                   id="busca_versiculo" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="{{ __('Ex: João 3:16, Rute 1:16, Salmos 23:1') }}">
                        </div>
                        <div class="md:col-span-1 flex space-x-2">
                            <button type="button" 
                                    id="btn_buscar_versiculo"
                                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                                <i class="fas fa-search mr-2"></i>
                                {{ __('Buscar') }}
                            </button>
                            <button type="button" 
                                    id="btn_versiculo_aleatorio"
                                    class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                                <i class="fas fa-random mr-2"></i>
                                {{ __('Aleatório') }}
                            </button>
                        </div>
                    </div>
                    <div id="resultado_busca" class="mt-3 hidden">
                        <div class="bg-gray-50 p-4 rounded-lg border">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900" id="versiculo_referencia"></h4>
                                    <p class="text-gray-700 mt-2" id="versiculo_texto"></p>
                                    <p class="text-sm text-gray-500 mt-1" id="versiculo_versao"></p>
                                </div>
                                <button type="button" 
                                        id="btn_usar_versiculo"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition duration-200">
                                    {{ __('Usar este versículo') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="erro_busca" class="mt-3 hidden">
                        <div class="bg-red-50 border border-red-200 p-4 rounded-lg">
                            <p class="text-red-700" id="mensagem_erro"></p>
                        </div>
                    </div>
                </div>

                <!-- Versículo -->
                <div class="md:col-span-2">
                    <label for="versiculo" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Versículo') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="versiculo" 
                           name="versiculo" 
                           value="{{ old('versiculo', $devocional->versiculo) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="{{ __('Ex: João 3:16') }}"
                           required>
                    @error('versiculo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Texto do Versículo -->
                <div class="md:col-span-2">
                    <label for="texto_versiculo" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Texto do Versículo') }}
                    </label>
                    <textarea id="texto_versiculo" 
                              name="texto_versiculo" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="{{ __('O texto do versículo aparecerá aqui quando você buscar um versículo...') }}">{{ old('texto_versiculo', $devocional->texto_versiculo) }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">{{ __('Este campo será preenchido automaticamente quando você buscar um versículo') }}</p>
                </div>

                <!-- Texto -->
                <div class="md:col-span-2">
                    <label for="texto" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Texto') }} <span class="text-red-500">*</span>
                    </label>
                    <textarea id="texto" 
                              name="texto" 
                              rows="6"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="{{ __('Digite o texto do devocional...') }}"
                              required>{{ old('texto', $devocional->texto) }}</textarea>
                    @error('texto')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Reflexão -->
                <div class="md:col-span-2">
                    <label for="reflexao" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Reflexão') }}
                    </label>
                    <textarea id="reflexao" 
                              name="reflexao" 
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="{{ __('Digite a reflexão ou oração...') }}">{{ old('reflexao', $devocional->reflexao) }}</textarea>
                    @error('reflexao')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Botões -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.devotionals.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                    {{ __('Cancelar') }}
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    {{ __('Atualizar Devocional') }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elementos do DOM
    const buscaVersiculo = document.getElementById('busca_versiculo');
    const btnBuscarVersiculo = document.getElementById('btn_buscar_versiculo');
    const btnVersiculoAleatorio = document.getElementById('btn_versiculo_aleatorio');
    const resultadoBusca = document.getElementById('resultado_busca');
    const erroBusca = document.getElementById('erro_busca');
    const versiculoReferencia = document.getElementById('versiculo_referencia');
    const versiculoTexto = document.getElementById('versiculo_texto');
    const versiculoVersao = document.getElementById('versiculo_versao');
    const btnUsarVersiculo = document.getElementById('btn_usar_versiculo');
    const mensagemErro = document.getElementById('mensagem_erro');
    const campoVersiculo = document.getElementById('versiculo');
    const campoTextoVersiculo = document.getElementById('texto_versiculo');

    // Buscar versículo
    function buscarVersiculo(referencia) {
        const versao = document.getElementById('versao_biblia').value;
        
        // Mostrar loading
        btnBuscarVersiculo.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>{{ __("Buscando...") }}';
        btnBuscarVersiculo.disabled = true;
        
        // Esconder resultados anteriores
        resultadoBusca.classList.add('hidden');
        erroBusca.classList.add('hidden');

        fetch('{{ route("admin.devotionals.buscar-versiculo-offline") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                referencia: referencia,
                versao: versao
            })
        })
        .then(response => response.json())
        .then(data => {
            btnBuscarVersiculo.innerHTML = '<i class="fas fa-search mr-2"></i>{{ __("Buscar") }}';
            btnBuscarVersiculo.disabled = false;

            if (data.success) {
                // Exibir resultado
                versiculoReferencia.textContent = data.versiculo.referencia;
                versiculoTexto.textContent = data.versiculo.texto;
                versiculoVersao.textContent = data.versiculo.versao;
                resultadoBusca.classList.remove('hidden');
                erroBusca.classList.add('hidden');
            } else {
                // Exibir erro
                mensagemErro.textContent = data.message || '{{ __("Erro ao buscar versículo") }}';
                erroBusca.classList.remove('hidden');
                resultadoBusca.classList.add('hidden');
            }
        })
        .catch(error => {
            btnBuscarVersiculo.innerHTML = '<i class="fas fa-search mr-2"></i>{{ __("Buscar") }}';
            btnBuscarVersiculo.disabled = false;
            mensagemErro.textContent = '{{ __("Erro de conexão. Tente novamente.") }}';
            erroBusca.classList.remove('hidden');
            resultadoBusca.classList.add('hidden');
        });
    }

    // Buscar versículo aleatório
    function buscarVersiculoAleatorio() {
        const versao = document.getElementById('versao_biblia').value;
        
        btnVersiculoAleatorio.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>{{ __("Buscando...") }}';
        btnVersiculoAleatorio.disabled = true;
        
        resultadoBusca.classList.add('hidden');
        erroBusca.classList.add('hidden');

        fetch('{{ route("admin.devotionals.buscar-versiculo-aleatorio") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                versao: versao
            })
        })
        .then(response => response.json())
        .then(data => {
            btnVersiculoAleatorio.innerHTML = '<i class="fas fa-random mr-2"></i>{{ __("Aleatório") }}';
            btnVersiculoAleatorio.disabled = false;

            if (data.success) {
                versiculoReferencia.textContent = data.versiculo.referencia;
                versiculoTexto.textContent = data.versiculo.texto;
                versiculoVersao.textContent = data.versiculo.versao;
                resultadoBusca.classList.remove('hidden');
                erroBusca.classList.add('hidden');
            } else {
                mensagemErro.textContent = data.message || '{{ __("Erro ao buscar versículo aleatório") }}';
                erroBusca.classList.remove('hidden');
                resultadoBusca.classList.add('hidden');
            }
        })
        .catch(error => {
            btnVersiculoAleatorio.innerHTML = '<i class="fas fa-random mr-2"></i>{{ __("Aleatório") }}';
            btnVersiculoAleatorio.disabled = false;
            mensagemErro.textContent = '{{ __("Erro de conexão. Tente novamente.") }}';
            erroBusca.classList.remove('hidden');
            resultadoBusca.classList.add('hidden');
        });
    }

    // Usar versículo encontrado
    function usarVersiculo() {
        const referencia = versiculoReferencia.textContent;
        const texto = versiculoTexto.textContent;
        
        campoVersiculo.value = referencia;
        campoTextoVersiculo.value = texto;
        
        // Esconder resultado
        resultadoBusca.classList.add('hidden');
        
        // Mostrar confirmação
        const confirmacao = document.createElement('div');
        confirmacao.className = 'bg-green-50 border border-green-200 p-4 rounded-lg mt-3';
        confirmacao.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                <span class="text-green-700">{{ __("Versículo adicionado com sucesso!") }}</span>
            </div>
        `;
        
        const container = document.getElementById('resultado_busca').parentNode;
        container.appendChild(confirmacao);
        
        // Remover confirmação após 3 segundos
        setTimeout(() => {
            confirmacao.remove();
        }, 3000);
    }

    // Event listeners
    btnBuscarVersiculo.addEventListener('click', function() {
        const referencia = buscaVersiculo.value.trim();
        if (referencia) {
            buscarVersiculo(referencia);
        } else {
            mensagemErro.textContent = '{{ __("Digite uma referência de versículo") }}';
            erroBusca.classList.remove('hidden');
            resultadoBusca.classList.add('hidden');
        }
    });

    btnVersiculoAleatorio.addEventListener('click', buscarVersiculoAleatorio);
    btnUsarVersiculo.addEventListener('click', usarVersiculo);

    // Buscar ao pressionar Enter
    buscaVersiculo.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            btnBuscarVersiculo.click();
        }
    });

    // Validação do formulário
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const titulo = document.getElementById('titulo').value.trim();
        const texto = document.getElementById('texto').value.trim();
        const versiculo = document.getElementById('versiculo').value.trim();
        const tipo = document.getElementById('tipo').value;
        const data = document.getElementById('data').value;
        
        if (!titulo || !texto || !versiculo || !tipo || !data) {
            e.preventDefault();
            alert('{{ __("Por favor, preencha todos os campos obrigatórios") }}');
            return false;
        }
    });
});
</script>
@endpush
@endsection 