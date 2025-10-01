@extends('layouts.admin')

@section('title', 'Cargos')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Gestão de Cargos</h1>
        <a href="{{ route('admin.people.cargos.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
            <i class="fas fa-plus mr-2"></i>Novo Cargo
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-800 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Filtros</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Buscar por nome
                </label>
                <input type="text" id="filtro-nome" 
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100"
                       placeholder="Digite o nome do cargo...">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Filtrar por departamento
                </label>
                <select id="filtro-departamento" 
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                    <option value="">Todos os departamentos</option>
                    @foreach($departamentos as $departamento)
                        <option value="{{ $departamento->id }}">{{ $departamento->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Filtrar por status
                </label>
                <select id="filtro-status" 
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                    <option value="">Todos</option>
                    <option value="1">Ativos</option>
                    <option value="0">Inativos</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Lista de Cargos -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
        @if($cargos->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Cargo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Departamento
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Ministério
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Sistema
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($cargos as $cargo)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700" 
                                data-nome="{{ strtolower($cargo->nome) }}"
                                data-departamento="{{ $cargo->departamento->id }}"
                                data-status="{{ $cargo->ativo ? '1' : '0' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full mr-3" style="background-color: {{ $cargo->cor ?? '#3B82F6' }}"></div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $cargo->nome }}</div>
                                            @if($cargo->descricao)
                                                <div class="text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">{{ $cargo->descricao }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $cargo->departamento->nome }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ $cargo->departamento->ministerio->nome }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($cargo->ativo)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200">
                                            Ativo
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-800 text-red-800 dark:text-red-200">
                                            Inativo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($cargo->sistema)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-200">
                                            Sistema
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                            Comum
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.people.cargos.edit', $cargo) }}" 
                                           class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.people.cargos.delete', $cargo) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Tem certeza que deseja excluir este cargo?')">
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
                <div class="w-16 h-16 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-briefcase text-gray-400 dark:text-gray-300 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Nenhum cargo encontrado</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Comece criando o primeiro cargo para sua organização</p>
                <a href="{{ route('admin.people.cargos.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>Criar Primeiro Cargo
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filtroNome = document.getElementById('filtro-nome');
    const filtroDepartamento = document.getElementById('filtro-departamento');
    const filtroStatus = document.getElementById('filtro-status');
    const rows = document.querySelectorAll('tbody tr');

    function filtrarTabela() {
        const nome = filtroNome.value.toLowerCase();
        const departamento = filtroDepartamento.value;
        const status = filtroStatus.value;

        rows.forEach(row => {
            const nomeRow = row.dataset.nome;
            const departamentoRow = row.dataset.departamento;
            const statusRow = row.dataset.status;

            const nomeMatch = !nome || nomeRow.includes(nome);
            const departamentoMatch = !departamento || departamentoRow === departamento;
            const statusMatch = !status || statusRow === status;

            if (nomeMatch && departamentoMatch && statusMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    filtroNome.addEventListener('input', filtrarTabela);
    filtroDepartamento.addEventListener('change', filtrarTabela);
    filtroStatus.addEventListener('change', filtrarTabela);
});
</script>
@endpush
@endsection