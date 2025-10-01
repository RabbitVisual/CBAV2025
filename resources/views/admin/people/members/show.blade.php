@extends('layouts.admin')

@section('title', __('Visualizar Membro'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Cabeçalho -->
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg rounded-2xl border border-gray-200/50 dark:border-gray-700/50 shadow-xl p-6 mb-8">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div class="flex items-center space-x-3">
                <div class="p-3 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl">
                    <i class="fas fa-user text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Visualizar Membro') }}</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Informações detalhadas do membro') }}</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.people.members.edit', $membro) }}" 
                   class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    <i class="fas fa-edit mr-2"></i>{{ __('Editar') }}
                </a>
                <a href="{{ route('admin.people.members.index') }}" 
                   class="inline-flex items-center px-4 py-2.5 bg-gray-600 dark:bg-gray-700 hover:bg-gray-700 dark:hover:bg-gray-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>{{ __('Voltar') }}
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded-xl mb-6">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg rounded-2xl border border-gray-200/50 dark:border-gray-700/50 shadow-xl p-8">
        <!-- Informações do Membro -->
        <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-4 sm:space-y-0 sm:space-x-6 mb-8">
            @if($membro->foto_existe)
                <img src="{{ $membro->foto_url }}" 
                     alt="{{ $membro->nome }}" 
                     class="w-24 h-24 rounded-2xl object-cover border-4 border-white dark:border-gray-600 shadow-lg">
            @else
                <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center border-4 border-white dark:border-gray-600 shadow-lg">
                    <span class="text-white font-bold text-2xl">{{ $membro->iniciais }}</span>
                </div>
            @endif
            <div class="text-center sm:text-left">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $membro->nome }}</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-3">ID: #{{ $membro->id }}</p>
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold {{ $membro->ativo ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' }}">
                    <i class="fas {{ $membro->ativo ? 'fa-check-circle' : 'fa-times-circle' }} mr-2"></i>
                    {{ $membro->ativo ? __('Ativo') : __('Inativo') }}
                </span>
            </div>
        </div>

        <!-- Dados Pessoais -->
        <div class="mb-8">
            <div class="flex items-center mb-6">
                <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg mr-3">
                    <i class="fas fa-user text-blue-600 dark:text-blue-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Dados Pessoais') }}</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl p-4">
                    <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">{{ __('Nome Completo') }}</label>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $membro->nome }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl p-4">
                    <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">{{ __('E-mail') }}</label>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $membro->email }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl p-4">
                    <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">{{ __('Data de Nascimento') }}</label>
                    <p class="text-gray-900 dark:text-white font-medium">
                        {{ $membro->data_nascimento ? $membro->data_nascimento->format('d/m/Y') : __('Não informado') }}
                        @if($membro->data_nascimento)
                            <span class="text-sm text-gray-500 dark:text-gray-400">({{ $membro->idade }} anos)</span>
                        @endif
                    </p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl p-4">
                    <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">{{ __('Telefone') }}</label>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $membro->telefone ?: __('Não informado') }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl p-4">
                    <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">{{ __('Gênero') }}</label>
                    <p class="text-gray-900 dark:text-white font-medium">
                        @if($membro->sexo == 'M')
                            {{ __('Masculino') }}
                        @elseif($membro->sexo == 'F')
                            {{ __('Feminino') }}
                        @else
                            {{ __('Não informado') }}
                        @endif
                    </p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl p-4">
                    <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">{{ __('Estado Civil') }}</label>
                    <p class="text-gray-900 dark:text-white font-medium">
                        @switch($membro->estado_civil)
                            @case('solteiro')
                                {{ __('Solteiro(a)') }}
                                @break
                            @case('casado')
                                {{ __('Casado(a)') }}
                                @break
                            @case('divorciado')
                                {{ __('Divorciado(a)') }}
                                @break
                            @case('viuvo')
                                {{ __('Viúvo(a)') }}
                                @break
                            @default
                                {{ __('Não informado') }}
                        @endswitch
                    </p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl p-4">
                    <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">{{ __('Profissão') }}</label>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $membro->profissao ?: __('Não informado') }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl p-4">
                    <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">{{ __('Escolaridade') }}</label>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $membro->escolaridade ?: __('Não informado') }}</p>
                </div>
            </div>
        </div>

        <!-- Endereço -->
        <div class="mb-8">
            <div class="flex items-center mb-6">
                <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg mr-3">
                    <i class="fas fa-map-marker-alt text-green-600 dark:text-green-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Endereço') }}</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl p-4">
                    <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">{{ __('CEP') }}</label>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $membro->cep ?: __('Não informado') }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl p-4">
                    <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">{{ __('Endereço') }}</label>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $membro->endereco ?: __('Não informado') }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl p-4">
                    <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">{{ __('Bairro') }}</label>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $membro->bairro ?: __('Não informado') }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl p-4">
                    <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">{{ __('Cidade') }}</label>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $membro->cidade ?: __('Não informado') }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl p-4">
                    <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">{{ __('Estado') }}</label>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $membro->estado ?: __('Não informado') }}</p>
                </div>
            </div>
        </div>

        <!-- Informações da Igreja -->
        <div class="mb-8">
            <div class="flex items-center mb-6">
                <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg mr-3">
                    <i class="fas fa-church text-purple-600 dark:text-purple-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Informações da Igreja') }}</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl p-4">
                    <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">{{ __('Data do Batismo') }}</label>
                    <p class="text-gray-900 dark:text-white font-medium">
                        {{ $membro->data_batismo ? $membro->data_batismo->format('d/m/Y') : __('Não informado') }}
                    </p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl p-4">
                    <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">{{ __('Data de Ingresso') }}</label>
                    <p class="text-gray-900 dark:text-white font-medium">
                        {{ $membro->data_ingresso ? $membro->data_ingresso->format('d/m/Y') : __('Não informado') }}
                    </p>
                </div>
            </div>
        </div>

        @if($membro->observacoes)
        <div class="mb-8">
            <div class="flex items-center mb-6">
                <div class="p-2 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg mr-3">
                    <i class="fas fa-sticky-note text-yellow-600 dark:text-yellow-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Observações') }}</h3>
            </div>
            <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl p-6">
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $membro->observacoes }}</p>
            </div>
        </div>
        @endif

        <!-- Informações do Sistema -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
            <div class="flex items-center mb-6">
                <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg mr-3">
                    <i class="fas fa-info-circle text-gray-600 dark:text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Informações do Sistema') }}</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl p-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400 font-medium">{{ __('Cadastrado em:') }}</span>
                        <span class="text-gray-900 dark:text-white font-semibold">{{ $membro->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl p-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400 font-medium">{{ __('Última atualização:') }}</span>
                        <span class="text-gray-900 dark:text-white font-semibold">{{ $membro->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection