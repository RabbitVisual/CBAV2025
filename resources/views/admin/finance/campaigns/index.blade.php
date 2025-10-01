@extends('layouts.admin')

@section('title', 'Campanhas')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Campanhas</h1>
                <p class="text-gray-600 mt-2">Gerencie as campanhas de arrecadação da igreja</p>
            </div>
            <a href="{{ route('admin.finance.campaigns.create') }}" 
               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>
                Nova Campanha
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-bullhorn text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Campanhas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $campanhas->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-play text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Campanhas Ativas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $campanhasAtivas }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-pause text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Campanhas Pausadas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $campanhasPausadas }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-stop text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Campanhas Finalizadas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $campanhasFinalizadas }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Filtros</h2>
        </div>
        <div class="p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Título da campanha...">
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="status" name="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos os status</option>
                        <option value="ativa" {{ request('status') == 'ativa' ? 'selected' : '' }}>Ativa</option>
                        <option value="pausada" {{ request('status') == 'pausada' ? 'selected' : '' }}>Pausada</option>
                        <option value="finalizada" {{ request('status') == 'finalizada' ? 'selected' : '' }}>Finalizada</option>
                    </select>
                </div>

                <div>
                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                    <select id="tipo" name="tipo" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos os tipos</option>
                        <option value="construcao" {{ request('tipo') == 'construcao' ? 'selected' : '' }}>Construção</option>
                        <option value="missao" {{ request('tipo') == 'missao' ? 'selected' : '' }}>Missão</option>
                        <option value="social" {{ request('tipo') == 'social' ? 'selected' : '' }}>Social</option>
                        <option value="equipamentos" {{ request('tipo') == 'equipamentos' ? 'selected' : '' }}>Equipamentos</option>
                        <option value="outros" {{ request('tipo') == 'outros' ? 'selected' : '' }}>Outros</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-search mr-2"></i>
                        Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Campanhas -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Campanhas</h2>
        </div>
        
        @if($campanhas->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Campanha
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Meta
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Arrecadado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Progresso
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Período
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($campanhas as $campanha)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $campanha->titulo }}</div>
                                        <div class="text-sm text-gray-500">{{ $campanha->descricao }}</div>
                                        <div class="text-xs text-gray-400">{{ ucfirst($campanha->tipo) }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">R$ {{ number_format($campanha->meta_valor, 2, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">R$ {{ number_format($campanha->valor_arrecadado, 2, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min($campanha->progresso, 100) }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-600">{{ number_format($campanha->progresso, 1) }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($campanha->status == 'ativa')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-play mr-1"></i>
                                            Ativa
                                        </span>
                                    @elseif($campanha->status == 'pausada')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-pause mr-1"></i>
                                            Pausada
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-stop mr-1"></i>
                                            Finalizada
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $campanha->data_inicio->format('d/m/Y') }}
                                        @if($campanha->data_fim)
                                            - {{ $campanha->data_fim->format('d/m/Y') }}
                                        @endif
                                    </div>
                                    @if($campanha->dias_restantes !== null)
                                        <div class="text-xs text-gray-500">
                                            {{ $campanha->dias_restantes }} dias restantes
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.finance.campaigns.show', $campanha) }}" 
                                           class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.finance.campaigns.edit', $campanha) }}" 
                                           class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.finance.campaigns.delete', $campanha) }}" 
                                              method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Tem certeza que deseja excluir esta campanha?')">
                                                <i class="fas fa-trash"></i>
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
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $campanhas->links() }}
            </div>
        @else
            <div class="p-6 text-center">
                <i class="fas fa-bullhorn text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma campanha encontrada</h3>
                <p class="text-gray-600 mb-4">Crie sua primeira campanha para começar a arrecadar fundos.</p>
                <a href="{{ route('admin.finance.campaigns.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>
                    Criar Primeira Campanha
                </a>
            </div>
        @endif
    </div>
</div>
@endsection 