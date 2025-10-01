@extends('layouts.admin')

@section('title', 'Gerenciar Eventos')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Eventos</h1>
            <p class="text-gray-600 mt-2">Gerencie todos os eventos da igreja</p>
        </div>
        <a href="{{ route('admin.eventos.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
            <i class="fas fa-plus mr-2"></i>Novo Evento
        </a>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-calendar-alt text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Eventos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Eventos Ativos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['ativos'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Eventos Futuros</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['futuros'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-gray-100 text-gray-600">
                    <i class="fas fa-history text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Eventos Passados</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['passados'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Título, descrição, local...">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        <option value="rascunho" {{ request('status') === 'rascunho' ? 'selected' : '' }}>Rascunho</option>
                        <option value="ativo" {{ request('status') === 'ativo' ? 'selected' : '' }}>Ativo</option>
                        <option value="cancelado" {{ request('status') === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                        <option value="finalizado" {{ request('status') === 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                    </select>
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

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Público</label>
                    <select name="tipo_publico" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        <option value="membros" {{ request('tipo_publico') === 'membros' ? 'selected' : '' }}>Apenas Membros</option>
                        <option value="publico" {{ request('tipo_publico') === 'publico' ? 'selected' : '' }}>Público Geral</option>
                        <option value="ambos" {{ request('tipo_publico') === 'ambos' ? 'selected' : '' }}>Membros e Público</option>
                    </select>
                </div>

                <div class="md:col-span-4 flex gap-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors">
                        <i class="fas fa-search mr-2"></i>Filtrar
                    </button>
                    <a href="{{ route('admin.eventos.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition-colors">
                        <i class="fas fa-times mr-2"></i>Limpar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Eventos -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            @if($eventos->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Evento
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Data
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Público
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Inscrições
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($eventos as $evento)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($evento->imagem_url)
                                                <img class="h-10 w-10 rounded-lg object-cover mr-3" src="{{ $evento->imagem_url }}" alt="{{ $evento->titulo }}">
                                            @else
                                                <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                                                    <i class="fas fa-calendar-alt text-blue-600"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $evento->titulo }}
                                                    @if($evento->destaque)
                                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            <i class="fas fa-star mr-1"></i>Destaque
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="text-sm text-gray-500">{{ $evento->local }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $evento->data_inicio->format('d/m/Y') }}
                                            @if($evento->data_fim && $evento->data_fim != $evento->data_inicio)
                                                - {{ $evento->data_fim->format('d/m/Y') }}
                                            @endif
                                        </div>
                                        @if($evento->hora_inicio)
                                            <div class="text-sm text-gray-500">
                                                {{ $evento->hora_inicio->format('H:i') }}
                                                @if($evento->hora_fim)
                                                    - {{ $evento->hora_fim->format('H:i') }}
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $evento->status === 'ativo' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $evento->status === 'rascunho' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $evento->status === 'cancelado' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $evento->status === 'finalizado' ? 'bg-gray-100 text-gray-800' : '' }}">
                                            {{ $evento->status_formatado }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $evento->tipo_publico_formatado }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $evento->inscricoes_confirmadas }} confirmadas
                                        </div>
                                        @if($evento->vagas_totais)
                                            <div class="text-sm text-gray-500">
                                                {{ $evento->vagas_disponiveis }} vagas disponíveis
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.eventos.show', $evento) }}" 
                                               class="text-blue-600 hover:text-blue-900" title="Visualizar">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.eventos.edit', $evento) }}" 
                                               class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.eventos.inscricoes', $evento) }}" 
                                               class="text-green-600 hover:text-green-900" title="Inscrições">
                                                <i class="fas fa-users"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.eventos.toggle-status', $evento) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-yellow-600 hover:text-yellow-900" title="Toggle Status">
                                                    <i class="fas fa-toggle-on"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.eventos.toggle-destaque', $evento) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-purple-600 hover:text-purple-900" title="Toggle Destaque">
                                                    <i class="fas fa-star"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <div class="mt-6">
                    {{ $eventos->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-calendar-times text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum evento encontrado</h3>
                    <p class="text-gray-600 mb-6">Crie seu primeiro evento para começar.</p>
                    <a href="{{ route('admin.eventos.create') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        <i class="fas fa-plus mr-2"></i>Criar Primeiro Evento
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 