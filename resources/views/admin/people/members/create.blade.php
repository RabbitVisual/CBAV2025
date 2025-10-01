@extends('layouts.admin')

@section('title', __('Novo Membro'))

@section('content')
<style>
    .glassmorphism {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.18);
    }
    .dark .glassmorphism {
        background: rgba(17, 24, 39, 0.25);
        border: 1px solid rgba(75, 85, 99, 0.18);
    }
</style>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Novo Membro') }}</h1>
        <a href="{{ route('admin.people.members.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>{{ __('Voltar') }}
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="glassmorphism shadow-xl rounded-xl p-8 border border-gray-200 dark:border-gray-700">
        <form action="{{ route('admin.people.members.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Dados pessoais serão criados automaticamente no User -->
                <div class="col-span-2">
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                {{ __('Os dados pessoais (nome, email, telefone) serão solicitados na criação da conta de usuário. Aqui você define apenas informações específicas do membro da igreja.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Usuário Associado') }} <span class="text-red-500">*</span>
                    </label>
                    <select id="user_id" 
                            name="user_id"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('user_id') border-red-500 @enderror"
                            required>
                        <option value="">{{ __('Selecione um usuário') }}</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ __('Selecione o usuário que será associado a este membro') }}
                    </p>
                </div>

                <div>
                    <label for="data_nascimento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Data de Nascimento') }}
                    </label>
                    <input type="date" 
                           id="data_nascimento" 
                           name="data_nascimento" 
                           value="{{ old('data_nascimento') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('data_nascimento') border-red-500 @enderror">
                    @error('data_nascimento')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Telefone será gerenciado no User -->

                <div>
                    <label for="sexo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Gênero') }}
                    </label>
                    <select id="sexo" 
                            name="sexo"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('sexo') border-red-500 @enderror">
                        <option value="">{{ __('Selecione o gênero') }}</option>
                        <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>{{ __('Masculino') }}</option>
                        <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>{{ __('Feminino') }}</option>
                    </select>
                    @error('sexo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="estado_civil" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Estado Civil') }}
                    </label>
                    <select id="estado_civil" 
                            name="estado_civil"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('estado_civil') border-red-500 @enderror">
                        <option value="">{{ __('Selecione o estado civil') }}</option>
                        <option value="solteiro" {{ old('estado_civil') == 'solteiro' ? 'selected' : '' }}>{{ __('Solteiro(a)') }}</option>
                        <option value="casado" {{ old('estado_civil') == 'casado' ? 'selected' : '' }}>{{ __('Casado(a)') }}</option>
                        <option value="divorciado" {{ old('estado_civil') == 'divorciado' ? 'selected' : '' }}>{{ __('Divorciado(a)') }}</option>
                        <option value="viuvo" {{ old('estado_civil') == 'viuvo' ? 'selected' : '' }}>{{ __('Viúvo(a)') }}</option>
                    </select>
                    @error('estado_civil')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>



                <div>
                    <label for="cep" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('CEP') }}
                    </label>
                    <input type="text" 
                           id="cep" 
                           name="cep" 
                           value="{{ old('cep') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('cep') border-red-500 @enderror"
                           placeholder="{{ __('00000-000') }}"
                           maxlength="9">
                    @error('cep')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="endereco" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Endereço') }}
                    </label>
                    <input type="text" 
                           id="endereco" 
                           name="endereco" 
                           value="{{ old('endereco') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('endereco') border-red-500 @enderror"
                           placeholder="{{ __('Rua, Avenida, etc.') }}">
                    @error('endereco')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="bairro" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Bairro') }}
                    </label>
                    <input type="text" 
                           id="bairro" 
                           name="bairro" 
                           value="{{ old('bairro') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('bairro') border-red-500 @enderror"
                           placeholder="{{ __('Nome do bairro') }}">
                    @error('bairro')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="cidade" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Cidade') }}
                    </label>
                    <input type="text" 
                           id="cidade" 
                           name="cidade" 
                           value="{{ old('cidade') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('cidade') border-red-500 @enderror"
                           placeholder="{{ __('Nome da cidade') }}">
                    @error('cidade')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Estado') }}
                    </label>
                    <input type="text" 
                           id="estado" 
                           name="estado" 
                           value="{{ old('estado') }}"
                           placeholder="Estado será preenchido automaticamente"
                           readonly
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('estado') border-red-500 @enderror">
                    @error('estado')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="data_batismo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Data do Batismo') }}
                    </label>
                    <input type="date" 
                           id="data_batismo" 
                           name="data_batismo" 
                           value="{{ old('data_batismo') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('data_batismo') border-red-500 @enderror">
                    @error('data_batismo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="data_ingresso" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Data de Ingresso') }}
                    </label>
                    <input type="date" 
                           id="data_ingresso" 
                           name="data_ingresso" 
                           value="{{ old('data_ingresso') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('data_ingresso') border-red-500 @enderror">
                    @error('data_ingresso')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="profissao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Profissão') }}
                    </label>
                    <input type="text" 
                           id="profissao" 
                           name="profissao" 
                           value="{{ old('profissao') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('profissao') border-red-500 @enderror"
                           placeholder="{{ __('Sua profissão') }}">
                    @error('profissao')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="escolaridade" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Escolaridade') }}
                    </label>
                    <select id="escolaridade" 
                            name="escolaridade"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('escolaridade') border-red-500 @enderror">
                        <option value="">{{ __('Selecione a escolaridade') }}</option>
                        <option value="Fundamental Incompleto" {{ old('escolaridade') == 'Fundamental Incompleto' ? 'selected' : '' }}>{{ __('Fundamental Incompleto') }}</option>
                        <option value="Fundamental Completo" {{ old('escolaridade') == 'Fundamental Completo' ? 'selected' : '' }}>{{ __('Fundamental Completo') }}</option>
                        <option value="Médio Incompleto" {{ old('escolaridade') == 'Médio Incompleto' ? 'selected' : '' }}>{{ __('Médio Incompleto') }}</option>
                        <option value="Médio Completo" {{ old('escolaridade') == 'Médio Completo' ? 'selected' : '' }}>{{ __('Médio Completo') }}</option>
                        <option value="Superior Incompleto" {{ old('escolaridade') == 'Superior Incompleto' ? 'selected' : '' }}>{{ __('Superior Incompleto') }}</option>
                        <option value="Superior Completo" {{ old('escolaridade') == 'Superior Completo' ? 'selected' : '' }}>{{ __('Superior Completo') }}</option>
                        <option value="Pós-graduação" {{ old('escolaridade') == 'Pós-graduação' ? 'selected' : '' }}>{{ __('Pós-graduação') }}</option>
                    </select>
                    @error('escolaridade')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label class="flex items-center">
                    <input type="checkbox" 
                           id="ativo" 
                           name="ativo" 
                           value="1" 
                           {{ old('ativo', true) ? 'checked' : '' }}
                           class="rounded border-gray-300 dark:border-gray-600 text-blue-600 dark:text-blue-400 shadow-sm focus:border-blue-300 dark:focus:border-blue-400 focus:ring focus:ring-blue-200 dark:focus:ring-blue-800 focus:ring-opacity-50 dark:bg-gray-800">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('Membro Ativo') }}</span>
                </label>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Membros inativos não aparecem nas listagens e relatórios') }}</p>
            </div>

            <div class="mt-6">
                <label for="observacoes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Observações') }}
                </label>
                <textarea id="observacoes" 
                          name="observacoes" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('observacoes') border-red-500 @enderror"
                          placeholder="{{ __('Informações adicionais sobre o membro...') }}">{{ old('observacoes') }}</textarea>
                @error('observacoes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Seção de Acesso ao Sistema -->
            <div class="mt-8">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-key mr-2"></i>{{ __('Acesso ao Sistema') }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Senha (opcional)') }}
                        </label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('password') border-red-500 @enderror"
                               placeholder="{{ __('Deixe em branco para senha padrão') }}">
                        <small class="text-gray-500 dark:text-gray-400">{{ __('Se não informada, será criada uma senha padrão que deve ser alterada no primeiro acesso') }}</small>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Confirmar Senha') }}
                        </label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('password_confirmation') border-red-500 @enderror">
                        @error('password_confirmation')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 dark:text-blue-400 mt-1 mr-3"></i>
                    <div>
                        <p class="text-sm text-blue-700 dark:text-blue-300">
                            <strong>{{ __('Importante:') }}</strong> {{ __('Ao criar um membro, automaticamente será criada uma conta de usuário no sistema com as mesmas informações. O membro poderá fazer login usando o email cadastrado.') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('admin.people.members.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    {{ __('Cancelar') }}
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>{{ __('Cadastrar Membro') }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Máscaras para os campos
    const telefoneInput = document.getElementById('telefone');
    const cepInput = document.getElementById('cep');
    
    // Máscara Telefone
    if (telefoneInput) {
        telefoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
        });
    }
    
    // Máscara CEP
    if (cepInput) {
        cepInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
        });
        
        // Buscar endereço por CEP
        cepInput.addEventListener('blur', function() {
            const cep = this.value.replace(/\D/g, '');
            if (cep.length === 8) {
                buscarEnderecoPorCep(cep);
            }
        });
    }
});

function buscarEnderecoPorCep(cep) {
    // Primeiro tenta buscar na base de dados local
    fetch(`{{ url('admin/people/buscar-cep') }}/${cep}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                 // Dados encontrados na base local
                 document.getElementById('endereco').value = ''; // Deixar vazio para preenchimento manual
                 document.getElementById('bairro').value = data.data.bairro || '';
                 document.getElementById('cidade').value = data.data.cidade;
                 document.getElementById('estado').value = data.data.uf;
                
                // Mostrar mensagem de sucesso
                showNotification('CEP encontrado na base de dados local!', 'success');
            } else {
                // Se não encontrar na base local, tenta ViaCEP como fallback
                buscarViaCep(cep);
            }
        })
        .catch(error => {
            console.log('Erro ao buscar CEP na base local:', error);
            // Em caso de erro, tenta ViaCEP como fallback
            buscarViaCep(cep);
        });
}

function buscarViaCep(cep) {
    fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(response => response.json())
        .then(data => {
            if (!data.erro) {
                document.getElementById('endereco').value = ''; // Deixar vazio para preenchimento manual
                document.getElementById('bairro').value = data.bairro || '';
                document.getElementById('cidade').value = data.localidade;
                document.getElementById('estado').value = data.uf;
                
                // Mostrar mensagem informando que foi usado ViaCEP
                showNotification('CEP encontrado via ViaCEP. Considere adicionar à base local.', 'warning');
            } else {
                showNotification('CEP não encontrado.', 'error');
            }
        })
        .catch(error => {
            console.log('Erro ao buscar CEP:', error);
            showNotification('Erro ao buscar CEP.', 'error');
        });
}

function showNotification(message, type) {
    // Criar elemento de notificação
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-md shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'warning' ? 'bg-yellow-500 text-white' :
        'bg-red-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remover após 3 segundos
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endpush
@endsection