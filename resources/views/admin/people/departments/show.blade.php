@extends('layouts.admin')

@section('title', $departamento->nome . ' - Departamento')

@push('styles')
<style>
    .show-department-container {
        background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 50%, #ede9fe 100%);
        min-height: 100vh;
    }
    
    .dark .show-department-container {
        background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #475569 100%);
    }
    
    .glassmorphism-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .dark .glassmorphism-card {
        background: rgba(31, 41, 55, 0.9);
        border: 1px solid rgba(75, 85, 99, 0.2);
    }
    
    .hover-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
</style>
@endpush

@section('content')
<div class="show-department-container min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Moderno -->
        <div class="glassmorphism-card rounded-3xl shadow-xl p-8 mb-8 border border-white/20">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center">
                <div class="mb-6 lg:mb-0">
                    <div class="flex items-center mb-4">
                        <div class="p-4 rounded-2xl bg-gradient-to-br from-green-500 to-teal-600 text-white shadow-lg mr-4">
                            <i class="fas fa-sitemap text-3xl"></i>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold bg-gradient-to-r from-green-600 via-teal-600 to-blue-600 bg-clip-text text-transparent mb-2">
                                {{ $departamento->nome }}
                            </h1>
                            <p class="text-xl text-gray-600 dark:text-gray-300">{{ __('Departamento do') }} {{ $departamento->ministerio->nome }}</p>
                            @if($departamento->ativo)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 mt-2">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    {{ __('Ativo') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 mt-2">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    {{ __('Inativo') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.people.departments.edit', $departamento) }}" 
                       class="bg-gradient-to-r from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 text-white px-6 py-3 rounded-xl hover:from-blue-600 hover:to-blue-700 dark:hover:from-blue-700 dark:hover:to-blue-800 transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl flex items-center">
                        <i class="fas fa-edit mr-2"></i>
                        {{ __('Editar') }}
                    </a>
                    <a href="{{ route('admin.people.departments.index') }}" 
                       class="bg-gradient-to-r from-gray-500 to-gray-600 dark:from-gray-600 dark:to-gray-700 text-white px-6 py-3 rounded-xl hover:from-gray-600 hover:to-gray-700 dark:hover:from-gray-700 dark:hover:to-gray-800 transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        {{ __('Voltar') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informações Principais -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Informações Gerais -->
                <div class="glassmorphism-card rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-info-circle mr-3"></i>
                            Informações Gerais
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Nome do Departamento</label>
                                <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $departamento->nome }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Ministério</label>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-blue-500 mr-2"></div>
                                    <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $departamento->ministerio->nome }}</p>
                                </div>
                            </div>
                            
                            @if($departamento->responsavel)
                                <div>
                                    <label class="block text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Responsável</label>
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            @if($departamento->responsavel->foto_existe)
                                                <img class="h-10 w-10 rounded-full" src="{{ $departamento->responsavel->foto_url }}" alt="{{ $departamento->responsavel->nome }}">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-purple-500 flex items-center justify-center">
                                                    <span class="text-white font-medium">{{ $departamento->responsavel->iniciais }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $departamento->responsavel->nome }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-300">{{ $departamento->responsavel->email }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Status</label>
                                @if($departamento->ativo)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        {{ __('Ativo') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        {{ __('Inativo') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        @if($departamento->descricao)
                            <div class="mt-6">
                                <label class="block text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Descrição</label>
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4">
                                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $departamento->descricao }}</p>
                                </div>
                            </div>
                        @endif
                        
                        @if($departamento->observacoes)
                            <div class="mt-6">
                                <label class="block text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Observações</label>
                                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-xl p-4">
                                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $departamento->observacoes }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Membros do Departamento -->
                <div class="glassmorphism-card rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-users mr-3"></i>
                            Membros do Departamento ({{ $departamento->membros->count() }})
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($departamento->membros->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($departamento->membros as $membro)
                                    <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur rounded-xl p-4 hover:bg-white/80 dark:hover:bg-gray-800/80 transition-all duration-300 border border-white/40 dark:border-gray-600/40">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                @if($membro->foto_existe)
                                                    <img class="h-12 w-12 rounded-full" src="{{ $membro->foto_url }}" alt="{{ $membro->nome }}">
                                                @else
                                                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                                        <span class="text-white font-bold">{{ $membro->iniciais }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4 flex-1">
                                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $membro->nome }}</h4>
                                                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $membro->email }}</p>
                                                @if($membro->pivot && $membro->pivot->cargo)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 mt-1">
                                                        {{ $membro->pivot->cargo }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                                    <i class="fas fa-users text-white text-2xl"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Nenhum membro cadastrado</h4>
                                <p class="text-gray-500 dark:text-gray-400">Este departamento ainda não possui membros.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Cargos do Departamento -->
                <div class="glassmorphism-card rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-briefcase mr-3"></i>
                            Cargos do Departamento ({{ $departamento->cargos->count() }})
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($departamento->cargos->count() > 0)
                            <div class="space-y-4">
                                @foreach($departamento->cargos as $cargo)
                                    <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur rounded-xl p-4 hover:bg-white/80 dark:hover:bg-gray-800/80 transition-all duration-300 border border-white/40 dark:border-gray-600/40">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="p-3 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 text-white shadow-lg mr-4">
                                                    <i class="fas fa-briefcase"></i>
                                                </div>
                                                <div>
                                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $cargo->nome }}</h4>
                                                    @if($cargo->descricao)
                                                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $cargo->descricao }}</p>
                                                    @endif
                                                    @if($cargo->sistema)
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200 mt-1">
                                                            <i class="fas fa-cog mr-1"></i>
                                                            Sistema
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                @if($cargo->ativo)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        Ativo
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                                        <i class="fas fa-times-circle mr-1"></i>
                                                        Inativo
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                                    <i class="fas fa-briefcase text-white text-2xl"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Nenhum cargo cadastrado</h4>
                                <p class="text-gray-500 dark:text-gray-400">Este departamento ainda não possui cargos.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Estatísticas Rápidas -->
                <div class="glassmorphism-card rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-chart-bar mr-3"></i>
                            Estatísticas
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-300">Total de Membros:</span>
                            <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $departamento->membros->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-300">Total de Cargos:</span>
                            <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $departamento->cargos->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-300">Cargos Ativos:</span>
                            <span class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $departamento->cargos->where('ativo', true)->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-300">Cargos do Sistema:</span>
                            <span class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $departamento->cargos->where('sistema', true)->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Ações Rápidas -->
                <div class="glassmorphism-card rounded-2xl shadow-xl border border-white/20 dark:border-gray-600/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-700 px-6 py-4">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-bolt mr-3"></i>
                            Ações Rápidas
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('admin.people.cargos.create', ['departamento_id' => $departamento->id]) }}" 
                           class="w-full bg-gradient-to-r from-indigo-500 to-indigo-600 dark:from-indigo-600 dark:to-indigo-700 hover:from-indigo-600 hover:to-indigo-700 dark:hover:from-indigo-700 dark:hover:to-indigo-800 text-white px-4 py-3 rounded-xl transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl flex items-center justify-center">
                            <i class="fas fa-briefcase mr-2"></i>
                            Adicionar Cargo
                        </a>
                        
                        <a href="{{ route('admin.people.members.create', ['departamento_id' => $departamento->id]) }}" 
                           class="w-full bg-gradient-to-r from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-700 hover:from-purple-600 hover:to-purple-700 dark:hover:from-purple-700 dark:hover:to-purple-800 text-white px-4 py-3 rounded-xl transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl flex items-center justify-center">
                            <i class="fas fa-user-plus mr-2"></i>
                            Adicionar Membro
                        </a>
                        
                        <a href="{{ route('admin.people.departments.edit', $departamento) }}" 
                           class="w-full bg-gradient-to-r from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 hover:from-blue-600 hover:to-blue-700 dark:hover:from-blue-700 dark:hover:to-blue-800 text-white px-4 py-3 rounded-xl transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl flex items-center justify-center">
                            <i class="fas fa-edit mr-2"></i>
                            Editar Departamento
                        </a>
                        
                        <a href="{{ route('admin.people.departments.index') }}" 
                           class="w-full bg-gradient-to-r from-gray-500 to-gray-600 dark:from-gray-600 dark:to-gray-700 hover:from-gray-600 hover:to-gray-700 dark:hover:from-gray-700 dark:hover:to-gray-800 text-white px-4 py-3 rounded-xl transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl flex items-center justify-center">
                            <i class="fas fa-list mr-2"></i>
                            Ver Todos Departamentos
                        </a>
                    </div>
                </div>

                <!-- Informações do Sistema -->
                <div class="glassmorphism-card rounded-2xl shadow-xl border border-white/20 dark:border-gray-600/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-500 to-gray-600 dark:from-gray-600 dark:to-gray-700 px-6 py-4">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-info-circle mr-3"></i>
                            Informações do Sistema
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">Criado em:</span>
                                <span class="text-gray-900 dark:text-white font-medium">{{ $departamento->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">Última atualização:</span>
                                <span class="text-gray-900 dark:text-white font-medium">{{ $departamento->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                            @if($departamento->created_by)
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-300">Criado por:</span>
                                    <span class="text-gray-900 dark:text-white font-medium">{{ $departamento->criador->nome ?? 'Sistema' }}</span>
                                </div>
                            @endif
                            @if($departamento->updated_by)
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-300">Última edição:</span>
                                    <span class="text-gray-900 dark:text-white font-medium">{{ $departamento->editor->nome ?? 'Sistema' }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection