@extends('layouts.admin')

@section('title', __('Usuários do Sistema'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ __('Usuários do Sistema') }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.people.users.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-user-plus mr-2"></i>{{ __('Novo Usuário') }}
            </a>
            <button onclick="exportarUsuarios()" 
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-download mr-2"></i>{{ __('Exportar') }}
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-blue-500 text-white">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $usuarios->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-green-500 text-white">
                    <i class="fas fa-user-check text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Ativos') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $usuariosAtivos ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-purple-500 text-white">
                    <i class="fas fa-user-shield text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Administradores') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $administradores ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-blue-500 text-white">
                    <i class="fas fa-users-cog text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Membros') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $membrosComUsuario ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-orange-500 text-white">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Online Hoje') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $onlineHoje ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Filtros e Busca') }}</h3>
        <form method="GET" action="{{ route('admin.people.users.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Buscar') }}</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="{{ __('Nome, email...') }}">
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Status') }}</label>
                    <select id="status" 
                            name="status"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">{{ __('Todos') }}</option>
                        <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>{{ __('Ativos') }}</option>
                        <option value="inativo" {{ request('status') == 'inativo' ? 'selected' : '' }}>{{ __('Inativos') }}</option>
                    </select>
                </div>

                <div>
                    <label for="tipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Tipo') }}</label>
                    <select id="tipo" 
                            name="tipo"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="">{{ __('Todos') }}</option>
                        <option value="admin" {{ request('tipo') == 'admin' ? 'selected' : '' }}>{{ __('Administradores') }}</option>
                        <option value="membro" {{ request('tipo') == 'membro' ? 'selected' : '' }}>{{ __('Membros') }}</option>
                    </select>
                </div>

                <div>
                    <label for="ultimo_login" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Último Login') }}</label>
                    <select id="ultimo_login" 
                            name="ultimo_login"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">{{ __('Qualquer') }}</option>
                        <option value="hoje" {{ request('ultimo_login') == 'hoje' ? 'selected' : '' }}>{{ __('Hoje') }}</option>
                        <option value="semana" {{ request('ultimo_login') == 'semana' ? 'selected' : '' }}>{{ __('Esta Semana') }}</option>
                        <option value="mes" {{ request('ultimo_login') == 'mes' ? 'selected' : '' }}>{{ __('Este Mês') }}</option>
                        <option value="nunca" {{ request('ultimo_login') == 'nunca' ? 'selected' : '' }}>{{ __('Nunca') }}</option>
                    </select>
                </div>

                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Ordenar por') }}</label>
                    <select id="sort" 
                            name="sort"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>{{ __('Nome') }}</option>
                        <option value="email" {{ request('sort') == 'email' ? 'selected' : '' }}>{{ __('Email') }}</option>
                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>{{ __('Data de Criação') }}</option>
                        <option value="last_login_at" {{ request('sort') == 'last_login_at' ? 'selected' : '' }}>{{ __('Último Login') }}</option>
                    </select>
                </div>
            </div>

            <div class="flex space-x-3">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-search mr-2"></i>{{ __('Filtrar') }}
                </button>
                <a href="{{ route('admin.people.users.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-times mr-2"></i>{{ __('Limpar') }}
                </a>
            </div>
        </form>
    </div>

    <!-- Lista de Usuários -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
        @if($usuarios->count() > 0)
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Lista de Usuários') }} ({{ $usuarios->count() }})</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Usuário') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Email') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Tipo') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Status') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Último Login') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Ações') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                        @foreach($usuarios as $usuario)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($usuario->foto_existe)
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ $usuario->foto_url }}" alt="{{ $usuario->name }}">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                                    <span class="text-white font-bold text-sm">{{ $usuario->iniciais }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $usuario->name }}</div>
                                            @if($usuario->membro)
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $usuario->membro->nome }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $usuario->email }}</div>
                                    @if($usuario->email_verified_at)
                                        <div class="text-sm text-green-600 dark:text-green-400">{{ __('Verificado') }}</div>
                                    @else
                                        <div class="text-sm text-red-600 dark:text-red-400">{{ __('Não verificado') }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($usuario->is_admin)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ __('Administrador') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ __('Membro') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($usuario->ativo)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ __('Ativo') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ __('Inativo') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    @if($usuario->last_login_at)
                                        <div>
                                            <div>{{ $usuario->last_login_at->format('d/m/Y') }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $usuario->last_login_at->format('H:i') }}</div>
                                        </div>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">{{ __('Nunca') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.people.users.show', $usuario) }}" 
                                           class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" 
                                           title="{{ __('Visualizar') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.people.users.edit', $usuario) }}" 
                                           class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300" 
                                           title="{{ __('Editar') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if(!$usuario->ativo)
                                            <button onclick="ativarUsuario({{ $usuario->id }})" 
                                                    class="text-green-600 hover:text-green-900" 
                                                    title="{{ __('Ativar') }}">
                                                <i class="fas fa-user-check"></i>
                                            </button>
                                        @else
                                            <button onclick="desativarUsuario({{ $usuario->id }})" 
                                                    class="text-orange-600 hover:text-orange-900" 
                                                    title="{{ __('Desativar') }}">
                                                <i class="fas fa-user-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Paginação -->
            @if(method_exists($usuarios, 'hasPages') && $usuarios->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $usuarios->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-blue-500 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('Nenhum usuário encontrado') }}</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-4">{{ __('Ajuste os filtros ou crie um novo usuário.') }}</p>
                <a href="{{ route('admin.people.users.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-user-plus mr-2"></i>{{ __('Criar Primeiro Usuário') }}
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function exportarUsuarios() {
    const form = document.querySelector('form');
    const formData = new FormData(form);
    formData.append('export', '1');
    
    const params = new URLSearchParams(formData);
    const exportUrl = '{{ route("admin.people.users.export") }}?' + params.toString();
    
    window.open(exportUrl, '_blank');
}

function ativarUsuario(id) {
    if (confirm('{{ __("Tem certeza que deseja ativar este usuário?") }}')) {
        fetch(`{{ url('admin/people/users') }}/${id}/activate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('{{ __("Erro ao ativar usuário") }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("Erro ao ativar usuário") }}');
        });
    }
}

function desativarUsuario(id) {
    if (confirm('{{ __("Tem certeza que deseja desativar este usuário? Ele perderá acesso ao sistema.") }}')) {
        fetch(`{{ url('admin/people/users') }}/${id}/deactivate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('{{ __("Erro ao desativar usuário") }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("Erro ao desativar usuário") }}');
        });
    }
}
</script>
@endpush
@endsection