@extends('layouts.admin')

@section('title', __('Conselho'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Cabeçalho -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Conselho') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Gerencie reuniões, votações e agenda do conselho') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.council.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i>
                {{ __('Nova Reunião') }}
            </a>
        </div>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Total de Reuniões') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalReunioes }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-calendar-check text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Reuniões Ativas') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $reunioesAtivas }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-vote-yea text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Votações Pendentes') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $votacoesPendentes }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-list text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Pautas Pendentes') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $pautasPendentes }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Seções Principais -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Reuniões Recentes -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">{{ __('Reuniões Recentes') }}</h3>
                <a href="{{ route('admin.council.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                    {{ __('Ver todas') }} →
                </a>
            </div>
            <div class="p-6">
                @if($reunioesRecentes->count() > 0)
                    <div class="space-y-4">
                        @foreach($reunioesRecentes as $reuniao)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">{{ $reuniao->titulo }}</h4>
                                    <p class="text-sm text-gray-600">
                                        {{ $reuniao->data_reuniao->format('d/m/Y') }} às {{ $reuniao->hora_inicio }}
                                    </p>
                                    <div class="flex items-center mt-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $reuniao->status_color }}">
                                            {{ $reuniao->status_text }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.council.show', $reuniao) }}" 
                                       class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">{{ __('Nenhuma reunião encontrada') }}</p>
                @endif
            </div>
        </div>

        <!-- Próximas Reuniões -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">{{ __('Próximas Reuniões') }}</h3>
                <a href="{{ route('admin.council.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                    {{ __('Ver todas') }} →
                </a>
            </div>
            <div class="p-6">
                @if($proximasReunioes->count() > 0)
                    <div class="space-y-4">
                        @foreach($proximasReunioes as $reuniao)
                            <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">{{ $reuniao->titulo }}</h4>
                                    <p class="text-sm text-gray-600">
                                        {{ $reuniao->data_reuniao->format('d/m/Y') }} às {{ $reuniao->hora_inicio }}
                                    </p>
                                    <p class="text-xs text-blue-600 mt-1">
                                        {{ $reuniao->data_reuniao->diffForHumans() }}
                                    </p>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.council.show', $reuniao) }}" 
                                       class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">{{ __('Nenhuma reunião próxima') }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Módulos Principais -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Reuniões -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center mb-4">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('Reuniões') }}</h3>
                    <p class="text-sm text-gray-600">{{ __('Gerencie reuniões do conselho') }}</p>
                </div>
            </div>
            <div class="space-y-3">
                <a href="{{ route('admin.council.create') }}" 
                   class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition duration-200">
                    <i class="fas fa-plus text-blue-600 mr-3"></i>
                    <div>
                        <p class="font-medium text-gray-900">{{ __('Nova Reunião') }}</p>
                        <p class="text-sm text-gray-600">{{ __('Criar reunião do conselho') }}</p>
                    </div>
                </a>
                <a href="{{ route('admin.council.index') }}" 
                   class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition duration-200">
                    <i class="fas fa-list text-gray-600 mr-3"></i>
                    <div>
                        <p class="font-medium text-gray-900">{{ __('Todas as Reuniões') }}</p>
                        <p class="text-sm text-gray-600">{{ __('Ver e gerenciar reuniões') }}</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Votações -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center mb-4">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-vote-yea text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('Votações') }}</h3>
                    <p class="text-sm text-gray-600">{{ __('Gerencie votações do conselho') }}</p>
                </div>
            </div>
            <div class="space-y-3">
                @if($conselhoAtivo)
                    <a href="{{ route('admin.council.voting.index', $conselhoAtivo) }}" 
                       class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition duration-200">
                        <i class="fas fa-vote-yea text-purple-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-gray-900">{{ __('Votações Ativas') }}</p>
                            <p class="text-sm text-gray-600">{{ __('Gerenciar votações em andamento') }}</p>
                        </div>
                    </a>
                @endif
                <a href="{{ route('admin.council.voting.history') }}" 
                   class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition duration-200">
                    <i class="fas fa-history text-gray-600 mr-3"></i>
                    <div>
                        <p class="font-medium text-gray-900">{{ __('Histórico de Votações') }}</p>
                        <p class="text-sm text-gray-600">{{ __('Ver votações anteriores') }}</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Agenda -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center mb-4">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-list text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('Agenda') }}</h3>
                    <p class="text-sm text-gray-600">{{ __('Gerencie pautas e agenda') }}</p>
                </div>
            </div>
            <div class="space-y-3">
                @if($conselhoAtivo)
                    <a href="{{ route('admin.council.agenda.index', $conselhoAtivo) }}" 
                       class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition duration-200">
                        <i class="fas fa-list text-green-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-gray-900">{{ __('Pautas da Reunião') }}</p>
                            <p class="text-sm text-gray-600">{{ __('Gerenciar pautas atuais') }}</p>
                        </div>
                    </a>
                @endif
                <a href="{{ route('admin.council.agenda.template.index') }}" 
                   class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition duration-200">
                    <i class="fas fa-copy text-gray-600 mr-3"></i>
                    <div>
                        <p class="font-medium text-gray-900">{{ __('Templates de Pauta') }}</p>
                        <p class="text-sm text-gray-600">{{ __('Criar templates reutilizáveis') }}</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Votações em Andamento -->
    @if($votacoesEmAndamento->count() > 0)
        <div class="bg-white rounded-lg shadow-md mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">{{ __('Votações em Andamento') }}</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($votacoesEmAndamento as $votacao)
                        <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $votacao->titulo }}</h4>
                                <p class="text-sm text-gray-600">
                                    {{ __('Reunião') }}: {{ $votacao->conselho->titulo }}
                                </p>
                                <div class="flex items-center mt-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ __('Em Andamento') }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.council.voting.show', [$votacao->conselho, $votacao]) }}" 
                                   class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Minhas Participações -->
    @if($minhasParticipacoes->count() > 0)
        <div class="bg-white rounded-lg shadow-md mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">{{ __('Minhas Participações') }}</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($minhasParticipacoes as $participacao)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $participacao->conselho->titulo }}</h4>
                                <p class="text-sm text-gray-600">
                                    {{ $participacao->conselho->data_reuniao->format('d/m/Y') }}
                                </p>
                                <div class="flex items-center mt-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($participacao->funcao) }}
                                    </span>
                                    @if($participacao->presente)
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ __('Presente') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.council.show', $participacao->conselho) }}" 
                                   class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Ações Rápidas -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">{{ __('Ações Rápidas') }}</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <a href="{{ route('admin.council.create') }}" 
                   class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition duration-200">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-medium text-gray-900">{{ __('Nova Reunião') }}</h4>
                        <p class="text-sm text-gray-600">{{ __('Criar nova reunião do conselho') }}</p>
                    </div>
                </a>

                <a href="{{ route('admin.council.index') }}" 
                   class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition duration-200">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-list"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-medium text-gray-900">{{ __('Todas as Reuniões') }}</h4>
                        <p class="text-sm text-gray-600">{{ __('Ver todas as reuniões') }}</p>
                    </div>
                </a>

                <a href="{{ route('admin.council.export') }}" 
                   class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition duration-200">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-download"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-medium text-gray-900">{{ __('Exportar Dados') }}</h4>
                        <p class="text-sm text-gray-600">{{ __('Exportar relatórios') }}</p>
                    </div>
                </a>

                <a href="{{ route('admin.council.settings') }}" 
                   class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition duration-200">
                    <div class="p-3 rounded-full bg-gray-100 text-gray-600">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-medium text-gray-900">{{ __('Configurações') }}</h4>
                        <p class="text-sm text-gray-600">{{ __('Configurar conselho') }}</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 