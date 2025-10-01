@extends('layouts.admin')

@section('title', 'Próximos Aniversariantes')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Próximos Aniversariantes</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.people.birthdays.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-calendar mr-2"></i>Ver Todos
            </a>
            <a href="{{ route('admin.people.birthdays.upcoming.export') }}" 
               class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-download mr-2"></i>Exportar
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-800 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-pink-500 text-white">
                    <i class="fas fa-birthday-cake text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total de Aniversariantes</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $proximosAniversariantes->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-blue-500 text-white">
                    <i class="fas fa-calendar-day text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Próximos 7 Dias</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $proximosAniversariantes->filter(function($membro) {
                            return $membro->data_nascimento->format('m-d') >= now()->format('m-d') && 
                                   $membro->data_nascimento->format('m-d') <= now()->addDays(7)->format('m-d');
                        })->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-green-500 text-white">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Membros Ativos</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $proximosAniversariantes->where('ativo', true)->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Aniversariantes -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
        @if($proximosAniversariantes->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Nome
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Data de Nascimento
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Dias para Aniversário
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($proximosAniversariantes as $membro)
                            @php
                                $hoje = now()->startOfDay();
                                $aniversarioEsteAno = $membro->data_nascimento->copy()->year(now()->year)->startOfDay();
                                
                                if ($aniversarioEsteAno->lt($hoje)) {
                                    $aniversarioProximoAno = $membro->data_nascimento->copy()->year(now()->year + 1)->startOfDay();
                                    $diasParaAniversario = $hoje->diffInDays($aniversarioProximoAno);
                                } else {
                                    $diasParaAniversario = $hoje->diffInDays($aniversarioEsteAno);
                                }
                                
                                $diasParaAniversario = (int) $diasParaAniversario;
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                                            <span class="text-white font-bold">
                                                {{ strtoupper(substr($membro->nome, 0, 2)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $membro->nome }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $membro->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $membro->data_nascimento->format('d/m/Y') }}</div>
                                    @if($membro->idade)
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $membro->idade }} anos</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($membro->data_nascimento->format('m-d') === now()->format('m-d'))
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            🎉 Hoje!
                                        </span>
                                    @elseif($diasParaAniversario <= 7)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                            Em {{ $diasParaAniversario }} dias
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Em {{ $diasParaAniversario }} dias
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($membro->ativo)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Ativo
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Inativo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.people.members.show', $membro) }}" 
                                       class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Paginação -->
            @if($proximosAniversariantes->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $proximosAniversariantes->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-16">
                <div class="w-16 h-16 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-birthday-cake text-gray-400 dark:text-gray-300 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Nenhum aniversariante nos próximos 30 dias</h3>
                <p class="text-gray-500 dark:text-gray-400">Os aniversariantes aparecerão aqui automaticamente</p>
            </div>
        @endif
    </div>

    <!-- Ações Rápidas -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mt-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Ações Rápidas</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.people.birthdays.index') }}" 
               class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                <div class="p-2 rounded-lg bg-blue-500 text-white mr-3">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-gray-100">Ver Todos</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Todos os aniversariantes</p>
                </div>
            </a>

            <a href="{{ route('admin.people.birthdays.export') }}" 
               class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                <div class="p-2 rounded-lg bg-green-500 text-white mr-3">
                    <i class="fas fa-download"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-gray-100">Exportar</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Baixar em Excel</p>
                </div>
            </a>

            <a href="{{ route('admin.people.members.create') }}" 
               class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                <div class="p-2 rounded-lg bg-purple-500 text-white mr-3">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-gray-100">Novo Membro</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Cadastrar membro</p>
                </div>
            </a>

            <a href="{{ route('admin.people.reports.index') }}" 
               class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                <div class="p-2 rounded-lg bg-indigo-500 text-white mr-3">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-gray-100">Relatórios</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Ver estatísticas</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection