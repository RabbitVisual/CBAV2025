@extends('layouts.member')

@section('title', 'Eventos')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Cabeçalho -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Eventos</h1>
        <p class="text-gray-600 mt-2">Confira os eventos disponíveis e faça sua inscrição</p>
    </div>

    <!-- Eventos em Destaque -->
    @if($eventosDestaque->count() > 0)
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Eventos em Destaque</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($eventosDestaque as $evento)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                        @if($evento->imagem_url)
                            <img src="{{ $evento->imagem_url }}" alt="{{ $evento->titulo }}" 
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-white text-4xl"></i>
                            </div>
                        @endif
                        
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-star mr-1"></i>Destaque
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $evento->gratuito ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $evento->valor_formatado }}
                                </span>
                            </div>
                            
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $evento->titulo }}</h3>
                            <p class="text-gray-600 text-sm mb-4">{{ $evento->descricao_curta ?: Str::limit($evento->descricao, 100) }}</p>
                            
                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                <i class="fas fa-calendar mr-2"></i>
                                {{ $evento->data_inicio->format('d/m/Y') }}
                                @if($evento->hora_inicio)
                                    às {{ $evento->hora_inicio->format('H:i') }}
                                @endif
                            </div>
                            
                            @if($evento->local)
                                <div class="flex items-center text-sm text-gray-500 mb-4">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    {{ $evento->local }}
                                </div>
                            @endif
                            
                            <div class="flex items-center justify-between">
                                @if($evento->podeInscricaoUsuario(auth()->user()))
                                    <a href="{{ route('member.eventos.show', $evento) }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                        Ver Detalhes
                                    </a>
                                @else
                                    <span class="text-sm text-gray-500">
                                        @if($evento->esta_cheio)
                                            <i class="fas fa-times-circle mr-1"></i>Evento Lotado
                                        @elseif(!$evento->inscricao_aberta)
                                            <i class="fas fa-clock mr-1"></i>Inscrições Encerradas
                                        @else
                                            <i class="fas fa-info-circle mr-1"></i>Indisponível
                                        @endif
                                    </span>
                                @endif
                                
                                @if($evento->dias_restantes !== null && $evento->dias_restantes > 0)
                                    <span class="text-xs text-gray-500">
                                        {{ $evento->dias_restantes }} dias
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Título, descrição, local...">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Evento</label>
                    <select name="tipo_evento" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        <option value="culto" {{ request('tipo_evento') === 'culto' ? 'selected' : '' }}>Culto</option>
                        <option value="estudo" {{ request('tipo_evento') === 'estudo' ? 'selected' : '' }}>Estudo Bíblico</option>
                        <option value="reuniao" {{ request('tipo_evento') === 'reuniao' ? 'selected' : '' }}>Reunião</option>
                        <option value="conferencia" {{ request('tipo_evento') === 'conferencia' ? 'selected' : '' }}>Conferência</option>
                        <option value="outro" {{ request('tipo_evento') === 'outro' ? 'selected' : '' }}>Outro</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors">
                        <i class="fas fa-search mr-2"></i>Filtrar
                    </button>
                    <a href="{{ route('member.eventos.index') }}" class="ml-2 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition-colors">
                        <i class="fas fa-times mr-2"></i>Limpar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Eventos -->
    <div class="mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900">Todos os Eventos</h2>
            <a href="{{ route('member.eventos.minhas-inscricoes') }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-ticket-alt mr-2"></i>Minhas Inscrições
            </a>
        </div>

        @if($eventos->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($eventos as $evento)
                    <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition-shadow">
                        @if($evento->imagem_url)
                            <img src="{{ $evento->imagem_url }}" alt="{{ $evento->titulo }}" 
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-white text-4xl"></i>
                            </div>
                        @endif
                        
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $evento->gratuito ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $evento->valor_formatado }}
                                </span>
                                
                                @if($evento->destaque)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-star mr-1"></i>Destaque
                                    </span>
                                @endif
                            </div>
                            
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $evento->titulo }}</h3>
                            <p class="text-gray-600 text-sm mb-4">{{ $evento->descricao_curta ?: Str::limit($evento->descricao, 100) }}</p>
                            
                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                <i class="fas fa-calendar mr-2"></i>
                                {{ $evento->data_inicio->format('d/m/Y') }}
                                @if($evento->hora_inicio)
                                    às {{ $evento->hora_inicio->format('H:i') }}
                                @endif
                            </div>
                            
                            @if($evento->local)
                                <div class="flex items-center text-sm text-gray-500 mb-4">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    {{ $evento->local }}
                                </div>
                            @endif
                            
                            @if($evento->vagas_totais)
                                <div class="flex items-center text-sm text-gray-500 mb-4">
                                    <i class="fas fa-users mr-2"></i>
                                    {{ $evento->vagas_disponiveis }} vagas disponíveis
                                </div>
                            @endif
                            
                            <div class="flex items-center justify-between">
                                @if($evento->podeInscricaoUsuario(auth()->user()))
                                    <a href="{{ route('member.eventos.show', $evento) }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                        Ver Detalhes
                                    </a>
                                @else
                                    <span class="text-sm text-gray-500">
                                        @if($evento->esta_cheio)
                                            <i class="fas fa-times-circle mr-1"></i>Evento Lotado
                                        @elseif(!$evento->inscricao_aberta)
                                            <i class="fas fa-clock mr-1"></i>Inscrições Encerradas
                                        @else
                                            <i class="fas fa-info-circle mr-1"></i>Indisponível
                                        @endif
                                    </span>
                                @endif
                                
                                @if($evento->dias_restantes !== null && $evento->dias_restantes > 0)
                                    <span class="text-xs text-gray-500">
                                        {{ $evento->dias_restantes }} dias
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginação -->
            <div class="mt-8">
                {{ $eventos->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-calendar-times text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum evento encontrado</h3>
                <p class="text-gray-600">Não há eventos disponíveis no momento.</p>
            </div>
        @endif
    </div>
</div>
@endsection 