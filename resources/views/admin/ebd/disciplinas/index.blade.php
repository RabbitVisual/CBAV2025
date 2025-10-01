@extends('layouts.admin')

@section('title', 'Gestão de Disciplinas da EBD')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Disciplinas da Escola Bíblica</h1>
        <x-admin.button href="{{ route('admin.ebd.disciplinas.create') }}">
            <i class="fas fa-plus mr-2"></i> Criar Nova Disciplina
        </x-admin.button>
    </div>

    @include('includes.session-messages')

    <x-admin.card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Disciplina</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Código</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Professor</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($disciplinas as $disciplina)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $disciplina->nome }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($disciplina->descricao, 50) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $disciplina->codigo_disciplina }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $disciplina->professorResponsavel->name ?? 'Não definido' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <x-admin.status-badge :type="$disciplina->ativo ? 'success' : 'danger'">
                                    {{ $disciplina->ativo ? 'Ativo' : 'Inativo' }}
                                </x-admin.status-badge>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <x-admin.button href="{{ route('admin.ebd.disciplinas.edit', $disciplina) }}" variant="secondary" size="sm">
                                    Editar
                                </x-admin.button>
                                <form action="{{ route('admin.ebd.disciplinas.destroy', $disciplina) }}" method="POST" class="inline-block" onsubmit="return confirm('Tem certeza que deseja excluir esta disciplina?');">
                                    @csrf
                                    @method('DELETE')
                                    <x-admin.button type="submit" variant="danger" size="sm">
                                        Excluir
                                    </x-admin.button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                Nenhuma disciplina encontrada.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-admin.card>
@endsection