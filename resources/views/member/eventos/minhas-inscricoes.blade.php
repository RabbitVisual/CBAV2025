@extends('layouts.member')

@section('title', 'Minhas Inscrições')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Cabeçalho -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Minhas Inscrições</h1>
            <p class="text-gray-600 mt-2">Gerencie suas inscrições em eventos</p>
        </div>
        <a href="{{ route('member.eventos.index') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-calendar mr-2"></i>Ver Todos os Eventos
        </a>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-ticket-alt text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Inscrições</p>
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
                    <p class="text-sm font-medium text-gray-600">Confirmadas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['confirmadas'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pendentes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['pendentes'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-certificate text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Certificados</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['certificados'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Nome do evento...">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        <option value="pendente" {{ request('status') === 'pendente' ? 'selected' : '' }}>Pendente</option>
                        <option value="confirmada" {{ request('status') === 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                        <option value="cancelada" {{ request('status') === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors">
                        <i class="fas fa-search mr-2"></i>Filtrar
                    </button>
                    <a href="{{ route('member.eventos.minhas-inscricoes') }}" class="ml-2 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition-colors">
                        <i class="fas fa-times mr-2"></i>Limpar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Inscrições -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            @if($inscricoes->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Evento
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Data do Evento
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Presença
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Certificado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($inscricoes as $inscricao)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($inscricao->evento->imagem_url)
                                                <img class="h-10 w-10 rounded-lg object-cover mr-3" src="{{ $inscricao->evento->imagem_url }}" alt="{{ $inscricao->evento->titulo }}">
                                            @else
                                                <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                                                    <i class="fas fa-calendar-alt text-blue-600"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $inscricao->evento->titulo }}</div>
                                                <div class="text-sm text-gray-500">{{ $inscricao->evento->local }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $inscricao->evento->data_inicio->format('d/m/Y') }}
                                        </div>
                                        @if($inscricao->evento->hora_inicio)
                                            <div class="text-sm text-gray-500">
                                                {{ $inscricao->evento->hora_inicio->format('H:i') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $inscricao->status === 'confirmada' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $inscricao->status === 'pendente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $inscricao->status === 'cancelada' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ $inscricao->status_formatado }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($inscricao->presenca !== null)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $inscricao->presenca ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $inscricao->presenca ? 'Presente' : 'Ausente' }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-500">Não registrado</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($inscricao->certificado_emitido)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-certificate mr-1"></i>Disponível
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-500">Não disponível</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('member.eventos.show', $inscricao->evento) }}" 
                                               class="text-blue-600 hover:text-blue-900" title="Ver Evento">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if($inscricao->status === 'pendente')
                                                <form method="POST" action="{{ route('member.eventos.cancelar-inscricao', $inscricao->evento) }}" 
                                                      onsubmit="return confirm('Tem certeza que deseja cancelar sua inscrição?')" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Cancelar Inscrição">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            @if($inscricao->certificado_emitido)
                                                <a href="{{ route('member.eventos.download-certificado', $inscricao->evento) }}" 
                                                   class="text-green-600 hover:text-green-900" title="Baixar Certificado">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <div class="mt-6">
                    {{ $inscricoes->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-ticket-alt text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma inscrição encontrada</h3>
                    <p class="text-gray-600 mb-6">Você ainda não se inscreveu em nenhum evento.</p>
                    <a href="{{ route('member.eventos.index') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        <i class="fas fa-calendar mr-2"></i>Ver Eventos Disponíveis
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 