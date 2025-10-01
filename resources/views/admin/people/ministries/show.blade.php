@extends('layouts.admin')

@section('title', $ministerio->nome . ' - Ministério')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('Detalhes do Ministério') }}</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.people.ministries.edit', $ministerio) }}" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-edit mr-2"></i>{{ __('Editar') }}
            </a>
            <a href="{{ route('admin.people.ministries.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>{{ __('Voltar') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informações da Turma -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $ministerio->cor ?? '#6366F1' }}"></div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $ministerio->nome }}</h2>
                    <span class="ml-4 inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $ministerio->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $ministerio->ativo ? __('Ativo') : __('Inativo') }}
                    </span>
                </div>

                @if($ministerio->descricao)
                    <p class="text-gray-600 mb-4">{{ $ministerio->descricao }}</p>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($ministerio->responsavel)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Responsável') }}</h3>
                            <div class="flex items-center mt-1">
                                <div class="flex-shrink-0">
                                    @if($ministerio->responsavel->foto_existe)
                                        <img class="h-8 w-8 rounded-full" src="{{ $ministerio->responsavel->foto_url }}" alt="{{ $ministerio->responsavel->nome }}">
                                    @else
                                        <div class="h-8 w-8 rounded-full bg-purple-500 flex items-center justify-center">
                                            <span class="text-white font-medium text-sm">{{ $ministerio->responsavel->iniciais }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $ministerio->responsavel->nome }}</p>
                                    <p class="text-sm text-gray-500">{{ $ministerio->responsavel->email }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if($ministerio->cor)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Cor de Identificação') }}</h3>
                            <div class="flex items-center mt-1">
                                <div class="w-6 h-6 rounded-full border-2 border-gray-300 mr-2" style="background-color: {{ $ministerio->cor }}"></div>
                                <span class="text-sm text-gray-900">{{ $ministerio->cor }}</span>
                            </div>
                        </div>
                    @endif
                    
                    @if($ministerio->data_fundacao)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Data de Fundação') }}</h3>
                            <p class="text-sm text-gray-900 mt-1">
                                {{ $ministerio->data_fundacao->format('d/m/Y') }}
                                <span class="text-gray-500">({{ $ministerio->data_fundacao->diffInYears(now()) }} anos)</span>
                            </p>
                        </div>
                    @endif
                    
                    @if($ministerio->reuniao_semanal)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Reunião Semanal') }}</h3>
                            <p class="text-sm text-gray-900 mt-1">{{ $ministerio->reuniao_semanal }}</p>
                        </div>
                    @endif
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Total de Departamentos') }}</h3>
                        <p class="text-sm text-gray-900 mt-1">{{ $ministerio->departamentos->count() }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Total de Membros') }}</h3>
                        <p class="text-sm text-gray-900 mt-1">{{ $ministerio->membros_count ?? 0 }}</p>
                    </div>
                </div>
                
                @if($ministerio->observacoes)
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Observações') }}</h3>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mt-1">
                            <p class="text-sm text-gray-700">{{ $ministerio->observacoes }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Estatísticas -->
            <div class="bg-white shadow-md rounded-lg p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Estatísticas') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $ministerio->departamentos->count() }}</div>
                        <div class="text-sm text-gray-500">{{ __('Departamentos') }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $ministerio->membros_count ?? 0 }}</div>
                        <div class="text-sm text-gray-500">{{ __('Membros') }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ $ministerio->cargos_count ?? 0 }}</div>
                        <div class="text-sm text-gray-500">{{ __('Cargos') }}</div>
                    </div>
                </div>
            </div>

            <!-- Departamentos do Ministério -->
            @if($ministerio->departamentos->count() > 0)
            <div class="bg-white shadow-md rounded-lg p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Departamentos') }} ({{ $ministerio->departamentos->count() }})</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($ministerio->departamentos as $departamento)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-sitemap text-blue-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">{{ $departamento->nome }}</h4>
                                        <p class="text-xs text-gray-500">{{ $departamento->membros_count ?? 0 }} membros</p>
                                        @if($departamento->responsavel)
                                            <p class="text-xs text-gray-500">{{ $departamento->responsavel->nome }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex flex-col items-end space-y-1">
                                    @if($departamento->ativo)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Ativo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>
                                            Inativo
                                        </span>
                                    @endif
                                    <a href="{{ route('admin.people.departments.show', $departamento) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-xs">
                                        <i class="fas fa-external-link-alt mr-1"></i>
                                        Ver detalhes
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Membros do Ministério -->
            @if(isset($ministerio->membros) && $ministerio->membros->count() > 0)
            <div class="bg-white shadow-md rounded-lg p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Membros do Ministério') }} ({{ $ministerio->membros->count() }})</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($ministerio->membros->take(8) as $membro)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    @if($membro->foto_existe)
                                        <img class="h-10 w-10 rounded-full" src="{{ $membro->foto_url }}" alt="{{ $membro->nome }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">{{ $membro->iniciais }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-3 flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $membro->nome }}</h4>
                                    <p class="text-xs text-gray-500">{{ $membro->email }}</p>
                                    @if($membro->pivot && $membro->pivot->cargo)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 mt-1">
                                            {{ $membro->pivot->cargo }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($ministerio->membros->count() > 8)
                    <div class="text-center mt-4">
                        <a href="{{ route('admin.people.members.index', ['ministerio_id' => $ministerio->id]) }}" 
                           class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                            Ver todos os {{ $ministerio->membros->count() }} membros
                        </a>
                    </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Ações Rápidas -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Ações Rápidas') }}</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.people.departments.create', ['ministerio_id' => $ministerio->id]) }}" 
                       class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-user-plus mr-2"></i>{{ __('Adicionar Departamento') }}
                    </a>
                    <a href="{{ route('admin.people.ministries.edit', $ministerio) }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-chalkboard-teacher mr-2"></i>{{ __('Editar Ministério') }}
                    </a>
                    @if($ministerio->membros_count > 0)
                        <a href="{{ route('admin.people.members.index', ['ministerio_id' => $ministerio->id]) }}" 
                           class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                            <i class="fas fa-calendar-plus mr-2"></i>{{ __('Ver Todos Membros') }}
                        </a>
                    @endif
                    <a href="{{ route('admin.people.ministries.index') }}" 
                       class="w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-list mr-2"></i>{{ __('Ver Todos Ministérios') }}
                    </a>
                </div>
            </div>

            <!-- Informações do Sistema -->
            <div class="bg-white shadow-md rounded-lg p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Informações do Sistema') }}</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ __('Criado em:') }}</span>
                        <span class="text-gray-900 font-medium">{{ $ministerio->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ __('Última atualização:') }}</span>
                        <span class="text-gray-900 font-medium">{{ $ministerio->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ __('ID do Sistema:') }}</span>
                        <span class="text-gray-900 font-mono">#{{ $ministerio->id }}</span>
                    </div>
                    @if($ministerio->created_by)
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('Criado por:') }}</span>
                            <span class="text-gray-900 font-medium">{{ $ministerio->criador->nome ?? 'Sistema' }}</span>
                        </div>
                    @endif
                    @if($ministerio->updated_by)
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('Última edição:') }}</span>
                            <span class="text-gray-900 font-medium">{{ $ministerio->editor->nome ?? 'Sistema' }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 