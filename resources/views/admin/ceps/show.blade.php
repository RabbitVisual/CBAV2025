@extends('layouts.admin')

@section('title', 'Visualizar CEP')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <!-- Cabeçalho -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Visualizar CEP</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $cep->faixa_de_cep }} - {{ $cep->localidade }}, {{ $cep->uf }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.people.ceps.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Voltar
                        </a>
                        <a href="{{ route('admin.people.ceps.edit', $cep) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informações do CEP -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Faixa de CEP -->
                     <div>
                         <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                             Faixa de CEP
                         </label>
                         <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-gray-900 dark:text-white">
                             {{ $cep->faixa_de_cep }}
                         </div>
                     </div>

                     <!-- CEP Inicial -->
                     <div>
                         <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                             CEP Inicial
                         </label>
                         <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-gray-900 dark:text-white">
                             {{ $cep->cep_inicial }}
                         </div>
                     </div>

                     <!-- CEP Final -->
                     <div>
                         <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                             CEP Final
                         </label>
                         <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-gray-900 dark:text-white">
                             {{ $cep->cep_final }}
                         </div>
                     </div>

                     <!-- Região -->
                     <div>
                         <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                             Região
                         </label>
                         <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-gray-900 dark:text-white">
                             {{ $cep->regiao ?: 'Não informado' }}
                         </div>
                     </div>

                    <!-- Localidade -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Localidade
                        </label>
                        <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-gray-900 dark:text-white">
                            {{ $cep->localidade }}
                        </div>
                    </div>

                    <!-- UF -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            UF
                        </label>
                        <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-gray-900 dark:text-white">
                            {{ $cep->uf }}
                        </div>
                    </div>

                    <!-- Código IBGE -->
                     <div>
                         <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                             Código IBGE
                         </label>
                         <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-gray-900 dark:text-white">
                             {{ $cep->cod_ibge ?: 'Não informado' }}
                         </div>
                     </div>

                     <!-- Situação -->
                     <div>
                         <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                             Situação
                         </label>
                         <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-gray-900 dark:text-white">
                             {{ $cep->situacao ?: 'Não informado' }}
                         </div>
                     </div>

                     <!-- Tipo de Faixa -->
                     <div>
                         <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                             Tipo de Faixa
                         </label>
                         <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-gray-900 dark:text-white">
                             {{ $cep->tipo_de_faixa ?: 'Não informado' }}
                         </div>
                     </div>

                     <!-- Altitude -->
                     <div>
                         <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                             Altitude
                         </label>
                         <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-gray-900 dark:text-white">
                             {{ $cep->altitude ? $cep->altitude . 'm' : 'Não informado' }}
                         </div>
                     </div>
                </div>

                <!-- Informações de Data -->
                @if($cep->created_at || $cep->updated_at)
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informações do Sistema</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($cep->created_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Data de Criação
                            </label>
                            <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-gray-900 dark:text-white">
                                {{ $cep->created_at->format('d/m/Y H:i:s') }}
                            </div>
                        </div>
                        @endif

                        @if($cep->updated_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Última Atualização
                            </label>
                            <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-gray-900 dark:text-white">
                                {{ $cep->updated_at->format('d/m/Y H:i:s') }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Botões de Ação -->
                <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.people.ceps.index') }}" 
                       class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-md transition-colors duration-200">
                        Voltar à Lista
                    </a>
                    <a href="{{ route('admin.people.ceps.edit', $cep) }}" 
                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors duration-200">
                        Editar CEP
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection