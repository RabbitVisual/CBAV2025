@extends('layouts.admin')

@section('title', __('Devocionais'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Devocionais') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Gerencie os devocionais da igreja') }}</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="verificarStatusBiblia()" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ __('Status da Bíblia') }}
            </button>
            <a href="{{ route('admin.devotionals.batch') }}" 
               class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-layer-group mr-2"></i>
                {{ __('Criar em Lote') }}
            </a>
            <a href="{{ route('admin.devotionals.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i>
                {{ __('Novo Devocional') }}
            </a>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-book text-blue-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">{{ __('Total') }}</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check text-green-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">{{ __('Ativos') }}</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $stats['ativos'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i class="fas fa-pray text-purple-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">{{ __('Devocionais') }}</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $stats['devocionais'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-quote-right text-yellow-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">{{ __('Versículos') }}</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $stats['versiculos'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <i class="fas fa-hands text-red-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">{{ __('Orações') }}</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $stats['oracoes'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="p-2 bg-indigo-100 rounded-lg">
                    <i class="fas fa-calendar-day text-indigo-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">{{ __('Hoje') }}</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $stats['hoje'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="busca" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Buscar') }}</label>
                <input type="text" 
                       id="busca" 
                       name="busca" 
                       value="{{ request('busca') }}"
                       placeholder="{{ __('Título, texto ou versículo...') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Tipo') }}</label>
                <select id="tipo" 
                        name="tipo"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">{{ __('Todos os tipos') }}</option>
                    <option value="devocional" {{ request('tipo') == 'devocional' ? 'selected' : '' }}>{{ __('Devocional') }}</option>
                    <option value="versiculo" {{ request('tipo') == 'versiculo' ? 'selected' : '' }}>{{ __('Versículo') }}</option>
                    <option value="oracao" {{ request('tipo') == 'oracao' ? 'selected' : '' }}>{{ __('Oração') }}</option>
                </select>
            </div>
            
            <div>
                <label for="data" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Data') }}</label>
                <input type="date" 
                       id="data" 
                       name="data" 
                       value="{{ request('data') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <label for="ativo" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Status') }}</label>
                <select id="ativo" 
                        name="ativo"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">{{ __('Todos') }}</option>
                    <option value="1" {{ request('ativo') == '1' ? 'selected' : '' }}>{{ __('Ativos') }}</option>
                    <option value="0" {{ request('ativo') == '0' ? 'selected' : '' }}>{{ __('Inativos') }}</option>
                </select>
            </div>
            
            <div class="md:col-span-4 flex justify-end space-x-3">
                <a href="{{ route('admin.devotionals.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    {{ __('Limpar') }}
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                    {{ __('Filtrar') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de Devocionais -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('Devocionais') }}</h2>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.devotionals.export') }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm transition duration-200">
                        <i class="fas fa-download mr-1"></i>
                        {{ __('Exportar') }}
                    </a>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Título') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Tipo') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Data') }}
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
                    @forelse($devocionais as $devocional)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $devocional->titulo }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($devocional->texto, 100) }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($devocional->tipo == 'devocional') bg-purple-100 text-purple-800
                                @elseif($devocional->tipo == 'versiculo') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($devocional->tipo) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $devocional->data->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $devocional->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $devocional->ativo ? __('Ativo') : __('Inativo') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.devotionals.show', $devocional) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.devotionals.edit', $devocional) }}" 
                                   class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.devotionals.toggle', $devocional) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="text-yellow-600 hover:text-yellow-900"
                                            onclick="return confirm('{{ __('Tem certeza que deseja alterar o status?') }}')">
                                        <i class="fas fa-toggle-on"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.devotionals.delete', $devocional) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('{{ __('Tem certeza que deseja excluir este devocional?') }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            {{ __('Nenhum devocional encontrado') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($devocionais->hasPages())
        <div class="px-6 py-3 border-t border-gray-200">
            {{ $devocionais->links() }}
        </div>
        @endif
    </div>
</div>

@include('components.modals')

@endsection

@push('scripts')
<script>
// Função para verificar status da Bíblia
function verificarStatusBiblia() {
    fetch('{{ route("admin.devotionals.status") }}', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro na requisição: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showStatusModal('success', 'Sistema funcionando normalmente', data.message);
        } else {
            showStatusModal('error', 'Erro no sistema', data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showErrorModal('Erro ao verificar status: ' + error.message);
    });
}
</script>
@endpush 