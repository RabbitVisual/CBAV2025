@extends('layouts.public')

@section('title', 'Eventos')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Cabeçalho -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-900">Eventos</h1>
        <p class="text-gray-600 mt-2">Confira nossos eventos e participe!</p>
    </div>

    <!-- Eventos em Destaque -->
    @if($eventosDestaque->count() > 0)
        <div class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 text-center">Eventos em Destaque</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($eventosDestaque as $evento)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                        @if($evento->imagem_url)
                            <img src="{{ $evento->imagem_url }}" alt="{{ $evento->titulo }}" 
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-white text-4xl"></i>
                            </div>
                        @endif
                        
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-star mr-1"></i>Destaque
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                    {{ $evento->gratuito ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $evento->valor_formatado }}
                                </span>
                            </div>
                            
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ $evento->titulo }}</h3>
                            <p class="text-gray-600 text-sm mb-4">{{ $evento->descricao_curta ?: Str::limit($evento->descricao, 120) }}</p>
                            
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-calendar mr-2"></i>
                                    {{ $evento->data_inicio->format('d/m/Y') }}
                                    @if($evento->hora_inicio)
                                        às {{ $evento->hora_inicio->format('H:i') }}
                                    @endif
                                </div>
                                
                                @if($evento->local)
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-map-marker-alt mr-2"></i>
                                        {{ $evento->local }}
                                    </div>
                                @endif
                                
                                @if($evento->vagas_totais)
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-users mr-2"></i>
                                        {{ $evento->vagas_disponiveis }} vagas disponíveis
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex items-center justify-between">
                                @if($evento->podeInscricaoPublico())
                                    <a href="{{ route('public.eventos.show', $evento) }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
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
    <div class="bg-white rounded-lg shadow mb-8">
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
                    <a href="{{ route('public.eventos.index') }}" class="ml-2 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition-colors">
                        <i class="fas fa-times mr-2"></i>Limpar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Eventos -->
    <div class="mb-8">
        <h2 class="text-2xl font-semibold text-gray-900 mb-6 text-center">Todos os Eventos</h2>

        @if($eventos->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($eventos as $evento)
                    <div class="bg-white rounded-xl shadow overflow-hidden hover:shadow-lg transition-shadow">
                        @if($evento->imagem_url)
                            <img src="{{ $evento->imagem_url }}" alt="{{ $evento->titulo }}" 
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-white text-4xl"></i>
                            </div>
                        @endif
                        
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                    {{ $evento->gratuito ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $evento->valor_formatado }}
                                </span>
                                
                                @if($evento->destaque)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-star mr-1"></i>Destaque
                                    </span>
                                @endif
                            </div>
                            
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ $evento->titulo }}</h3>
                            <p class="text-gray-600 text-sm mb-4">{{ $evento->descricao_curta ?: Str::limit($evento->descricao, 120) }}</p>
                            
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-calendar mr-2"></i>
                                    {{ $evento->data_inicio->format('d/m/Y') }}
                                    @if($evento->hora_inicio)
                                        às {{ $evento->hora_inicio->format('H:i') }}
                                    @endif
                                </div>
                                
                                @if($evento->local)
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-map-marker-alt mr-2"></i>
                                        {{ $evento->local }}
                                    </div>
                                @endif
                                
                                @if($evento->vagas_totais)
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-users mr-2"></i>
                                        {{ $evento->vagas_disponiveis }} vagas disponíveis
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex items-center justify-between">
                                @if($evento->podeInscricaoPublico())
                                    <a href="{{ route('public.eventos.show', $evento) }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
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
            <div class="mt-12">
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

    <!-- Call to Action -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-8 text-center text-white">
        <h3 class="text-2xl font-bold mb-4">Quer participar dos nossos eventos?</h3>
        <p class="text-blue-100 mb-6">Faça parte da nossa comunidade e participe dos eventos da igreja.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                               <a href="{{ route('home') }}" 
                      class="bg-white text-blue-600 px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition-colors">
                       <i class="fas fa-home mr-2"></i>Voltar ao Início
                   </a>
                   <a href="{{ route('home') }}" 
                      class="bg-transparent border-2 border-white text-white px-6 py-3 rounded-lg font-medium hover:bg-white hover:text-blue-600 transition-colors">
                       <i class="fas fa-info-circle mr-2"></i>Saiba Mais
                   </a>
        </div>
    </div>
</div>
@endsection 