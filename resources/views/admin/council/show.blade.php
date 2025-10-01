@extends('layouts.admin')

@section('title', __('Detalhes da Reunião'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Alertas do Conselho -->
    <div class="mb-6">
        <x-council-alerts :conselho="$conselho" />
    </div>
    
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $conselho->titulo }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Detalhes da reunião do conselho') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.council.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('Voltar') }}
            </a>
            <a href="{{ route('admin.council.edit', $conselho) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-edit mr-2"></i>
                {{ __('Editar') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informações Principais -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status e Informações Gerais -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">{{ __('Informações Gerais') }}</h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $conselho->status_color }}">
                        {{ $conselho->status_text }}
                    </span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">{{ __('Data') }}</label>
                        <p class="text-sm text-gray-900">{{ $conselho->data_reuniao->format('d/m/Y') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">{{ __('Horário') }}</label>
                        <p class="text-sm text-gray-900">
                            {{ $conselho->hora_inicio ? $conselho->hora_inicio->format('H:i') : '--' }}
                            @if($conselho->hora_fim)
                                - {{ $conselho->hora_fim->format('H:i') }}
                            @endif
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">{{ __('Tipo') }}</label>
                        <p class="text-sm text-gray-900">{{ $conselho->tipo_text }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">{{ __('Local') }}</label>
                        <p class="text-sm text-gray-900">{{ $conselho->local ?: 'Não informado' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">{{ __('Quórum Mínimo') }}</label>
                        <p class="text-sm text-gray-900">{{ $conselho->quorum_minimo }}%</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">{{ __('Criado por') }}</label>
                        <p class="text-sm text-gray-900">{{ $conselho->criadoPor->name ?? 'Sistema' }}</p>
                    </div>
                </div>
                
                @if($conselho->descricao)
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-500">{{ __('Descrição') }}</label>
                    <p class="text-sm text-gray-900 mt-1">{{ $conselho->descricao }}</p>
                </div>
                @endif
                
                @if($conselho->observacoes)
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-500">{{ __('Observações') }}</label>
                    <p class="text-sm text-gray-900 mt-1">{{ $conselho->observacoes }}</p>
                </div>
                @endif
            </div>

            <!-- Participantes -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('Participantes') }}</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($conselho->participantes as $participante)
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                @if($participante->user->foto_existe)
                                    <img class="h-10 w-10 rounded-full" src="{{ $participante->user->foto_url }}" alt="{{ $participante->user->name }}">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                        <span class="text-white font-medium text-sm">{{ $participante->user->iniciais }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $participante->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $participante->funcao_text }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                @if($participante->presente)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>
                                        {{ __('Presente') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-times mr-1"></i>
                                        {{ __('Ausente') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-4 text-sm text-gray-600">
                    <strong>{{ __('Quórum atual:') }}</strong> {{ $conselho->quorum_atual }}% 
                    @if($conselho->quorum_atingido)
                        <span class="text-green-600">✓ {{ __('Atingido') }}</span>
                    @else
                        <span class="text-red-600">✗ {{ __('Não atingido') }}</span>
                    @endif
                </div>
            </div>

            <!-- Pautas -->
            @if($conselho->pautas->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('Pautas') }}</h2>
                
                <div class="space-y-3">
                    @foreach($conselho->pautas as $pauta)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-medium text-gray-900">{{ $pauta->titulo }}</h3>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $pauta->status_color }}">
                                    {{ $pauta->status_text }}
                                </span>
                            </div>
                            @if($pauta->descricao)
                                <p class="text-sm text-gray-600 mb-2">{{ $pauta->descricao }}</p>
                            @endif
                            <div class="flex items-center text-xs text-gray-500">
                                <span class="mr-4">{{ __('Prioridade:') }} {{ $pauta->prioridade_text }}</span>
                                @if($pauta->responsavel)
                                    <span>{{ __('Responsável:') }} {{ $pauta->responsavel->name }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Votações -->
            @if($conselho->votacoes->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('Votações') }}</h2>
                
                <div class="space-y-3">
                    @foreach($conselho->votacoes as $votacao)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-medium text-gray-900">{{ $votacao->titulo }}</h3>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $votacao->status_color }}">
                                    {{ $votacao->status_text }}
                                </span>
                            </div>
                            @if($votacao->descricao)
                                <p class="text-sm text-gray-600 mb-2">{{ $votacao->descricao }}</p>
                            @endif
                            <div class="flex items-center text-xs text-gray-500">
                                <span class="mr-4">{{ __('Tipo:') }} {{ $votacao->tipo_votacao_text }}</span>
                                <span>{{ __('Votos:') }} {{ $votacao->total_votos }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Ações Rápidas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Ações') }}</h3>
                
                <div class="space-y-3">
                    @if($conselho->podeIniciar())
                        <form method="POST" action="{{ route('admin.council.iniciar', $conselho) }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                                <i class="fas fa-play mr-2"></i>
                                {{ __('Iniciar Reunião') }}
                            </button>
                        </form>
                    @endif
                    
                    @if($conselho->podeFinalizar())
                        <form method="POST" action="{{ route('admin.council.finalizar', $conselho) }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                                <i class="fas fa-stop mr-2"></i>
                                {{ __('Finalizar Reunião') }}
                            </button>
                        </form>
                    @endif
                    
                    @if($conselho->podeCancelar())
                        <form method="POST" action="{{ route('admin.council.cancelar', $conselho) }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center"
                                    onclick="return confirm('{{ __('Tem certeza que deseja cancelar esta reunião?') }}')">
                                <i class="fas fa-times mr-2"></i>
                                {{ __('Cancelar Reunião') }}
                            </button>
                        </form>
                    @endif
                    
                    <a href="{{ route('admin.council.attendance.index', $conselho) }}" 
                       class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-users mr-2"></i>
                        {{ __('Gerenciar Presença') }}
                    </a>
                    
                    <a href="{{ route('admin.council.agenda.index', $conselho) }}" 
                       class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-list mr-2"></i>
                        {{ __('Gerenciar Pautas') }}
                    </a>
                    
                    <a href="{{ route('admin.council.voting.index', $conselho) }}" 
                       class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-vote-yea mr-2"></i>
                        {{ __('Gerenciar Votações') }}
                    </a>
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Estatísticas') }}</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">{{ __('Total de Participantes') }}</span>
                        <span class="text-sm font-medium text-gray-900">{{ $conselho->participantes->count() }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">{{ __('Presentes') }}</span>
                        <span class="text-sm font-medium text-green-600">{{ $conselho->participantes->where('presente', true)->count() }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">{{ __('Pautas') }}</span>
                        <span class="text-sm font-medium text-gray-900">{{ $conselho->pautas->count() }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">{{ __('Votações') }}</span>
                        <span class="text-sm font-medium text-gray-900">{{ $conselho->votacoes->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 