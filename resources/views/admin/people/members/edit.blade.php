@extends('layouts.admin')

@section('title', __('Editar Membro'))

@push('styles')
<style>
.glassmorphism {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.18);
}
.dark .glassmorphism {
    background: rgba(31, 41, 55, 0.25);
    border: 1px solid rgba(75, 85, 99, 0.18);
}
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Editar Membro') }}</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.people.members.show', $membro) }}" 
               class="bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-eye mr-2"></i>{{ __('Visualizar') }}
            </a>
            <a href="{{ route('admin.people.members.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>{{ __('Voltar') }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="glassmorphism bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <form action="{{ route('admin.people.members.update', $membro) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informações do Usuário Associado -->
                <div class="col-span-2">
                    <div class="bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
                            <i class="fas fa-user mr-2"></i>{{ __('Dados do Usuário') }}
                        </h3>
                        @if($membro->user)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Nome') }}</label>
                                    <p class="text-gray-900 dark:text-white bg-white dark:bg-gray-800 px-3 py-2 rounded border">{{ $membro->user->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('E-mail') }}</label>
                                    <p class="text-gray-900 dark:text-white bg-white dark:bg-gray-800 px-3 py-2 rounded border">{{ $membro->user->email }}</p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('admin.people.users.edit', $membro->user) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/50 hover:bg-blue-200 dark:hover:bg-blue-900 transition-colors">
                                    <i class="fas fa-edit mr-2"></i>{{ __('Editar Dados do Usuário') }}
                                </a>
                            </div>
                        @else
                            <div class="text-yellow-700 dark:text-yellow-300">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                {{ __('Este membro não possui usuário associado.') }}
                                <a href="{{ route('admin.people.users.create') }}" class="ml-2 text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ __('Criar usuário') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <div>
                    <label for="data_nascimento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Data de Nascimento') }}
                    </label>
                    <input type="date" 
                           id="data_nascimento" 
                           name="data_nascimento" 
                           value="{{ old('data_nascimento', $membro->data_nascimento ? $membro->data_nascimento->format('Y-m-d') : '') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('data_nascimento') border-red-500 @enderror">
                    @error('data_nascimento')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Telefone será gerenciado no User -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Telefone') }}
                    </label>
                    <p class="text-gray-600 dark:text-gray-400 text-sm bg-gray-50 dark:bg-gray-800 px-3 py-2 rounded border">
                        {{ $membro->user ? $membro->user->telefone ?? __('Não informado') : __('Usuário não associado') }}
                        @if($membro->user)
                            <a href="{{ route('admin.people.users.edit', $membro->user) }}" class="ml-2 text-blue-600 dark:text-blue-400 hover:underline text-xs">
                                {{ __('Editar') }}
                            </a>
                        @endif
                    </p>
                </div>

                <div>
                    <label for="sexo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Gênero') }}
                    </label>
                    <select id="sexo" 
                            name="sexo"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('sexo') border-red-500 @enderror">
                        <option value="">{{ __('Selecione o gênero') }}</option>
                        <option value="M" {{ old('sexo', $membro->sexo) == 'M' ? 'selected' : '' }}>{{ __('Masculino') }}</option>
                        <option value="F" {{ old('sexo', $membro->sexo) == 'F' ? 'selected' : '' }}>{{ __('Feminino') }}</option>
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
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('estado_civil') border-red-500 @enderror">
                        <option value="">{{ __('Selecione o estado civil') }}</option>
                        <option value="solteiro" {{ old('estado_civil', $membro->estado_civil) == 'solteiro' ? 'selected' : '' }}>{{ __('Solteiro(a)') }}</option>
                        <option value="casado" {{ old('estado_civil', $membro->estado_civil) == 'casado' ? 'selected' : '' }}>{{ __('Casado(a)') }}</option>
                        <option value="divorciado" {{ old('estado_civil', $membro->estado_civil) == 'divorciado' ? 'selected' : '' }}>{{ __('Divorciado(a)') }}</option>
                        <option value="viuvo" {{ old('estado_civil', $membro->estado_civil) == 'viuvo' ? 'selected' : '' }}>{{ __('Viúvo(a)') }}</option>
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
                           value="{{ old('cep', $membro->cep) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('cep') border-red-500 @enderror"
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
                           value="{{ old('endereco', $membro->endereco) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('endereco') border-red-500 @enderror"
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
                           value="{{ old('bairro', $membro->bairro) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('bairro') border-red-500 @enderror"
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
                           value="{{ old('cidade', $membro->cidade) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('cidade') border-red-500 @enderror"
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
                           value="{{ old('estado', $membro->estado) }}"
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
                           value="{{ old('data_batismo', $membro->data_batismo ? $membro->data_batismo->format('Y-m-d') : '') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('data_batismo') border-red-500 @enderror">
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
                           value="{{ old('data_ingresso', $membro->data_ingresso ? $membro->data_ingresso->format('Y-m-d') : '') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('data_ingresso') border-red-500 @enderror">
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
                           value="{{ old('profissao', $membro->profissao) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('profissao') border-red-500 @enderror"
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
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('escolaridade') border-red-500 @enderror">
                        <option value="">{{ __('Selecione a escolaridade') }}</option>
                        <option value="Fundamental Incompleto" {{ old('escolaridade', $membro->escolaridade) == 'Fundamental Incompleto' ? 'selected' : '' }}>{{ __('Fundamental Incompleto') }}</option>
                        <option value="Fundamental Completo" {{ old('escolaridade', $membro->escolaridade) == 'Fundamental Completo' ? 'selected' : '' }}>{{ __('Fundamental Completo') }}</option>
                        <option value="Médio Incompleto" {{ old('escolaridade', $membro->escolaridade) == 'Médio Incompleto' ? 'selected' : '' }}>{{ __('Médio Incompleto') }}</option>
                        <option value="Médio Completo" {{ old('escolaridade', $membro->escolaridade) == 'Médio Completo' ? 'selected' : '' }}>{{ __('Médio Completo') }}</option>
                        <option value="Superior Incompleto" {{ old('escolaridade', $membro->escolaridade) == 'Superior Incompleto' ? 'selected' : '' }}>{{ __('Superior Incompleto') }}</option>
                        <option value="Superior Completo" {{ old('escolaridade', $membro->escolaridade) == 'Superior Completo' ? 'selected' : '' }}>{{ __('Superior Completo') }}</option>
                        <option value="Pós-graduação" {{ old('escolaridade', $membro->escolaridade) == 'Pós-graduação' ? 'selected' : '' }}>{{ __('Pós-graduação') }}</option>
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
                           {{ old('ativo', $membro->ativo) ? 'checked' : '' }}
                           class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
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
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('observacoes') border-red-500 @enderror"
                          placeholder="{{ __('Informações adicionais sobre o membro...') }}">{{ old('observacoes', $membro->observacoes) }}</textarea>
                @error('observacoes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Seção de Acesso ao Sistema -->
            <div class="mt-8 p-6 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-key mr-2"></i>{{ __('Acesso ao Sistema') }}
                </h3>
                
                @if($membro->user)
                    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-600 dark:text-green-400 mr-2"></i>
                            <div>
                                <strong class="text-green-800 dark:text-green-200">{{ __('Usuário vinculado:') }}</strong>
                                <span class="text-green-700 dark:text-green-300">{{ $membro->user->email }}</span>
                                <p class="text-sm text-green-600 dark:text-green-400 mt-1">{{ __('Este membro possui acesso ao sistema') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Nova Senha (opcional)') }}
                        </label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('password') border-red-500 @enderror"
                               placeholder="{{ __('Deixe em branco para manter a atual') }}">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Preencha apenas se desejar alterar a senha') }}</p>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Confirmar Nova Senha') }}
                        </label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('password_confirmation') border-red-500 @enderror">
                        @error('password_confirmation')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-4 p-4 bg-blue-100 dark:bg-blue-900 border border-blue-400 dark:border-blue-600 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-sync-alt text-blue-600 dark:text-blue-400 mr-2"></i>
                        <div>
                            <strong class="text-blue-800 dark:text-blue-200">{{ __('Sincronização:') }}</strong>
                            <span class="text-blue-700 dark:text-blue-300">{{ __('As alterações nos dados pessoais serão automaticamente sincronizadas com a conta de usuário do sistema.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-between items-center">
                <button type="button" 
                        onclick="confirmarExclusao()"
                        class="bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-trash mr-2"></i>{{ __('Excluir Membro') }}
                </button>
                <div class="flex space-x-4">
                    <a href="{{ route('admin.people.members.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                        {{ __('Cancelar') }}
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i>{{ __('Salvar Alterações') }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div id="modalExclusao" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg max-w-md w-full">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-500 dark:text-red-400 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white">{{ __('Confirmar Exclusão') }}</h3>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        {{ __('Tem certeza que deseja excluir o membro') }} <strong>"{{ $membro->nome }}"</strong>? 
                        {{ __('Esta ação não pode ser desfeita e removerá todos os dados relacionados.') }}
                    </p>
                    <div class="flex justify-end space-x-3">
                        <button type="button" 
                                onclick="cancelarExclusao()"
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            {{ __('Cancelar') }}
                        </button>
                        <form action="{{ route('admin.people.members.destroy', $membro) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800 text-white px-4 py-2 rounded-lg transition-colors">
                                {{ __('Excluir') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarExclusao() {
    document.getElementById('modalExclusao').classList.remove('hidden');
}

function cancelarExclusao() {
    document.getElementById('modalExclusao').classList.add('hidden');
}

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
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'warning' ? 'bg-yellow-500' : 
        'bg-red-500'
    } text-white`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endpush
@endsection