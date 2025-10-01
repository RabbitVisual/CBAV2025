@extends('layouts.admin')

@section('title', __('Ministérios'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('Ministérios') }}</h1>
        <a href="{{ route('admin.people.ministries.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
            <i class="fas fa-plus mr-2"></i>{{ __('Novo Ministério') }}
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filtros e Busca -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <form method="GET" action="{{ route('admin.people.ministries.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Busca -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Buscar') }}</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="{{ __('Nome do ministério...') }}">
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Status') }}</label>
                    <select id="status" 
                            name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">{{ __('Todos') }}</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>{{ __('Ativos') }}</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>{{ __('Inativos') }}</option>
                    </select>
                </div>

                <!-- Responsável -->
                <div>
                    <label for="responsavel" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Responsável') }}</label>
                    <select id="responsavel" 
                            name="responsavel"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">{{ __('Todos') }}</option>
                        <option value="com_responsavel" {{ request('responsavel') == 'com_responsavel' ? 'selected' : '' }}>{{ __('Com Responsável') }}</option>
                        <option value="sem_responsavel" {{ request('responsavel') == 'sem_responsavel' ? 'selected' : '' }}>{{ __('Sem Responsável') }}</option>
                    </select>
                </div>

                <!-- Ordenação -->
                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Ordenar por') }}</label>
                    <select id="sort" 
                            name="sort"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="nome" {{ request('sort') == 'nome' ? 'selected' : '' }}>{{ __('Nome') }}</option>
                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>{{ __('Data de Criação') }}</option>
                        <option value="departamentos_count" {{ request('sort') == 'departamentos_count' ? 'selected' : '' }}>{{ __('Número de Departamentos') }}</option>
                    </select>
                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="flex flex-wrap gap-3 pt-4">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-search mr-2"></i>{{ __('Filtrar') }}
                </button>
                <a href="{{ route('admin.people.ministries.index') }}" 
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded-lg transition-colors duration-200 border border-gray-200">
                    <i class="fas fa-times mr-2"></i>{{ __('Limpar') }}
                </a>
                <button type="button" 
                        onclick="exportarMinisterios()"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-download mr-2"></i>{{ __('Exportar') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de Ministérios -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        @if($ministerios->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Ministério') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Responsável') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Departamentos') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Membros') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Status') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Ações') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($ministerios as $ministerio)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full mr-3" style="background-color: {{ $ministerio->cor ?? '#6366F1' }}"></div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $ministerio->nome }}</div>
                                            @if($ministerio->descricao)
                                                <div class="text-sm text-gray-500 truncate max-w-xs">{{ $ministerio->descricao }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($ministerio->responsavel)
                                        <div class="flex items-center">
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
                                                <div class="text-sm font-medium text-gray-900">{{ $ministerio->responsavel->nome }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">{{ __('Não definido') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $ministerio->departamentos_count ?? 0 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $ministerio->membros_count ?? 0 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $ministerio->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $ministerio->ativo ? __('Ativo') : __('Inativo') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.people.ministries.show', $ministerio) }}" 
                                           class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.people.ministries.edit', $ministerio) }}" 
                                           class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.people.ministries.destroy', $ministerio) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" 
                                                    onclick="return confirm('{{ __('Tem certeza que deseja excluir este ministério?') }}')">
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
        @else
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-church text-gray-400 text-4xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Nenhum ministério encontrado') }}</h3>
                <p class="text-gray-500 mb-6">{{ __('Comece criando o primeiro ministério da igreja.') }}</p>
                <a href="{{ route('admin.people.ministries.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>{{ __('Criar Primeiro Ministério') }}
                </a>
            </div>
        @endif
    </div>

    <!-- Paginação -->
    @if(method_exists($ministerios, 'hasPages') && $ministerios->hasPages())
        <div class="bg-white shadow-md rounded-lg p-6 mt-6">
            {{ $ministerios->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
function exportarMinisterios() {
    // Capturar os filtros atuais
    const form = document.querySelector('form');
    const formData = new FormData(form);
    
    // Adicionar parâmetro de exportação
    formData.append('export', '1');
    
    // Criar URL com parâmetros
    const params = new URLSearchParams(formData);
    const exportUrl = '{{ route("admin.people.ministries.export") }}?' + params.toString();
    
    // Abrir em nova aba
    window.open(exportUrl, '_blank');
}
</script>
@endpush
@endsection 