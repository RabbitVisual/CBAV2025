@extends('layouts.admin')

@section('title', 'Gestão de Membros')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Gestão de Membros</h1>
        <x-admin.button href="{{ route('admin.members.create') }}">
            <i class="fas fa-plus mr-2"></i> Novo Membro
        </x-admin.button>
    </div>

    @include('includes.session-messages')

    <x-admin.card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Membro</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Contato</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ $user->foto_url }}" alt="{{ $user->name }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">ID: #{{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $user->email }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->profile->telefone ?? 'Não informado' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <x-admin.status-badge :type="$user->ativo ? 'success' : 'danger'">
                                    {{ $user->ativo ? 'Ativo' : 'Inativo' }}
                                </x-admin.status-badge>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <x-admin.button href="{{ route('admin.members.show', $user) }}" variant="secondary" size="sm">
                                    <i class="fas fa-eye"></i>
                                </x-admin.button>
                                <x-admin.button href="{{ route('admin.members.edit', $user) }}" variant="secondary" size="sm">
                                    <i class="fas fa-edit"></i>
                                </x-admin.button>
                                <form action="{{ route('admin.members.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Tem certeza que deseja excluir este membro?');">
                                    @csrf
                                    @method('DELETE')
                                    <x-admin.button type="submit" variant="danger" size="sm">
                                        <i class="fas fa-trash"></i>
                                    </x-admin.button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                Nenhum membro encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30">
                {{ $users->links() }}
            </div>
        @endif
    </x-admin.card>
@endsection