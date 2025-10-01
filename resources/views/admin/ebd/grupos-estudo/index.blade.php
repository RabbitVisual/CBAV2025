@extends('layouts.admin')

@section('title', 'Grupos de Estudo EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Grupos de Estudo EBD</h1>
        <a href="{{ route('admin.ebd.grupos-estudo.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
            <i class="fas fa-plus mr-2"></i>Novo Grupo
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <form method="GET" action="{{ route('admin.ebd.grupos-estudo.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="turma_id" class="block text-sm font-medium text-gray-700 mb-2">Turma</label>
                <select name="turma_id" id="turma_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todas as turmas</option>
                    @foreach($turmas as $turma)
                        <option value="{{ $turma->id }}" {{ request('turma_id') == $turma->id ? 'selected' : '' }}>
                            {{ $turma->nome }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                    <option value="inativo" {{ request('status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Nome do grupo..."
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg mr-2">
                    <i class="fas fa-search mr-2"></i>Filtrar
                </button>
                <a href="{{ route('admin.ebd.grupos-estudo.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded-lg">
                    <i class="fas fa-times mr-2"></i>Limpar
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Grupo
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Turma
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Líder
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Membros
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($grupos as $grupo)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $grupo->cor }}"></div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $grupo->nome }}</div>
                                    @if($grupo->descricao)
                                        <div class="text-sm text-gray-500">{{ Str::limit($grupo->descricao, 50) }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $grupo->turma->nome }}</div>
                            <div class="text-sm text-gray-500">{{ $grupo->turma->faixa_etaria }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($grupo->lider)
                                <div class="text-sm text-gray-900">{{ $grupo->lider->nome }}</div>
                                <div class="text-sm text-gray-500">Líder</div>
                            @else
                                <span class="text-sm text-gray-400">Sem líder</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $grupo->membros_ativos_count }}/{{ $grupo->capacidade_maxima }}</div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                <div class="bg-blue-600 h-2 rounded-full" 
                                     style="width: {{ $grupo->capacidade_maxima > 0 ? ($grupo->membros_ativos_count / $grupo->capacidade_maxima) * 100 : 0 }}%"></div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($grupo->ativo)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Ativo
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>Inativo
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.ebd.grupos-estudo.show', $grupo) }}" 
                                   class="text-blue-600 hover:text-blue-900" title="Visualizar">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.ebd.grupos-estudo.edit', $grupo) }}" 
                                   class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.ebd.grupos-estudo.toggle-status', $grupo) }}" 
                                      method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="{{ $grupo->ativo ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900' }}" 
                                            title="{{ $grupo->ativo ? 'Desativar' : 'Ativar' }}">
                                        <i class="fas fa-{{ $grupo->ativo ? 'pause' : 'play' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.ebd.grupos-estudo.destroy', $grupo) }}" 
                                      method="POST" class="inline" 
                                      onsubmit="return confirm('Tem certeza que deseja excluir este grupo?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Nenhum grupo de estudo encontrado.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($grupos->hasPages())
        <div class="mt-6">
            {{ $grupos->links() }}
        </div>
    @endif
</div>
@endsection