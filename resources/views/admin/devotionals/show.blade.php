@extends('layouts.admin')

@section('title', __('Visualizar Devocional'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Visualizar Devocional') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Detalhes do devocional selecionado') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.devotionals.edit', $devocional) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-edit mr-2"></i>
                {{ __('Editar') }}
            </a>
            <a href="{{ route('admin.devotionals.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('Voltar') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informações Principais -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $devocional->titulo }}</h2>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $devocional->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $devocional->ativo ? __('Ativo') : __('Inativo') }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst($devocional->tipo) }}
                        </span>
                    </div>
                </div>

                <!-- Versículo -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Versículo') }}</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700 font-medium mb-2">{{ $devocional->versiculo }}</p>
                        @if($devocional->texto_versiculo)
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <p class="text-gray-600 text-sm italic">{{ __('Texto do versículo:') }}</p>
                                <p class="text-gray-700 mt-1">{{ $devocional->texto_versiculo }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Texto -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Texto') }}</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700 leading-relaxed">{{ $devocional->texto }}</p>
                    </div>
                </div>

                <!-- Reflexão -->
                @if($devocional->reflexao)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Reflexão') }}</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700 leading-relaxed">{{ $devocional->reflexao }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Informações Secundárias -->
        <div class="space-y-6">
            <!-- Detalhes -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Detalhes') }}</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ __('Data') }}:</span>
                        <span class="font-medium">{{ $devocional->data->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ __('Tipo') }}:</span>
                        <span class="font-medium">{{ ucfirst($devocional->tipo) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ __('Ordem') }}:</span>
                        <span class="font-medium">{{ $devocional->ordem }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ __('Status') }}:</span>
                        <span class="font-medium {{ $devocional->ativo ? 'text-green-600' : 'text-red-600' }}">
                            {{ $devocional->ativo ? __('Ativo') : __('Inativo') }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ __('Criado em') }}:</span>
                        <span class="font-medium">{{ $devocional->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ __('Atualizado em') }}:</span>
                        <span class="font-medium">{{ $devocional->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Ações -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Ações') }}</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.devotionals.edit', $devocional) }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-edit mr-2"></i>
                        {{ __('Editar Devocional') }}
                    </a>
                    
                    <form action="{{ route('admin.devotionals.toggle', $devocional) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                            <i class="fas fa-toggle-on mr-2"></i>
                            {{ $devocional->ativo ? __('Desativar') : __('Ativar') }}
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.devotionals.duplicate', $devocional) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                            <i class="fas fa-copy mr-2"></i>
                            {{ __('Duplicar') }}
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.devotionals.delete', $devocional) }}" method="POST" class="w-full" 
                          onsubmit="return confirm('{{ __('Tem certeza que deseja excluir este devocional?') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                            <i class="fas fa-trash mr-2"></i>
                            {{ __('Excluir') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Preview -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Preview') }}</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-sm text-gray-600 mb-2">{{ __('Como aparece para os membros:') }}</div>
                    <div class="border-l-4 border-blue-500 pl-4">
                        <h4 class="font-semibold text-gray-900">{{ $devocional->titulo }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ $devocional->versiculo }}</p>
                        <p class="text-gray-700 mt-2 text-sm">{{ Str::limit($devocional->texto, 150) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 