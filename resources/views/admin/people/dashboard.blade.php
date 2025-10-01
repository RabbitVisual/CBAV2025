@extends('layouts.admin')

@section('title', 'Gestão de Pessoas - Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Gestão de Pessoas</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.people.members.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-plus mr-2"></i>Novo Membro
            </a>
            <a href="{{ route('admin.people.users.create') }}" 
               class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-user-plus mr-2"></i>Novo Usuário
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Cards de Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total de Membros -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-blue-500 text-white">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total de Membros</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($estatisticas['total_membros']) }}</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-green-600 font-medium">
                    {{ $estatisticas['membros_ativos'] }} ativos
                </span>
            </div>
        </div>

        <!-- Total de Usuários -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-green-500 text-white">
                    <i class="fas fa-user-cog text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total de Usuários</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($estatisticas['total_usuarios']) }}</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-green-600 font-medium">
                    {{ $estatisticas['usuarios_ativos'] }} ativos
                </span>
            </div>
        </div>

        <!-- Total de Ministérios -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-purple-500 text-white">
                    <i class="fas fa-church text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Ministérios</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($estatisticas['total_ministerios']) }}</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-blue-600 font-medium">
                    Ativos no sistema
                </span>
            </div>
        </div>

        <!-- Total de Departamentos -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-orange-500 text-white">
                    <i class="fas fa-sitemap text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Departamentos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($estatisticas['total_departamentos']) }}</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-orange-600 font-medium">
                    Organizados
                </span>
            </div>
        </div>
    </div>

    <!-- Seções Principais -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Membros Recentes -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Membros Recentes</h3>
            </div>
            <div class="p-6">
                @if($membrosRecentes->count() > 0)
                    <div class="space-y-4">
                        @foreach($membrosRecentes as $membro)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                                        <span class="text-white font-bold">
                                            {{ strtoupper(substr($membro->nome, 0, 2)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $membro->nome }}</p>
                                        <p class="text-sm text-gray-500">{{ $membro->email }}</p>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500 font-medium">
                                    {{ $membro->created_at->diffForHumans() }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.people.members.index') }}" 
                           class="text-blue-600 hover:text-blue-800 font-semibold">
                            Ver todos os membros 
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-users text-gray-300 text-4xl mb-4"></i>
                        <p class="text-gray-500">Nenhum membro cadastrado ainda.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Ministérios com Mais Membros -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Ministérios Populares</h3>
            </div>
            <div class="p-6">
                @if($ministeriosComEstatisticas->count() > 0)
                    <div class="space-y-4">
                        @foreach($ministeriosComEstatisticas as $ministerio)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-church text-white"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $ministerio->nome }}</p>
                                        <p class="text-sm text-gray-500">{{ $ministerio->membros_count }} membros</p>
                                    </div>
                                </div>
                                <div>
                                    <a href="{{ route('admin.people.ministries.show', $ministerio) }}" 
                                       class="bg-purple-100 text-purple-600 hover:bg-purple-200 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                        Ver detalhes
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.people.ministries.index') }}" 
                           class="text-purple-600 hover:text-purple-800 font-semibold">
                            Ver todos os ministérios 
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-church text-gray-300 text-4xl mb-4"></i>
                        <p class="text-gray-500">Nenhum ministério cadastrado ainda.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Ações Rápidas -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Ações Rápidas</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.people.members.index') }}" 
                   class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="p-2 rounded-lg bg-blue-500 text-white mr-3">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Gerenciar Membros</p>
                        <p class="text-sm text-gray-500">Visualizar e editar membros</p>
                    </div>
                </a>

                <a href="{{ route('admin.people.users.index') }}" 
                   class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="p-2 rounded-lg bg-green-500 text-white mr-3">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Gerenciar Usuários</p>
                        <p class="text-sm text-gray-500">Contas de acesso ao sistema</p>
                    </div>
                </a>

                <a href="{{ route('admin.people.ministries.index') }}" 
                   class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="p-2 rounded-lg bg-purple-500 text-white mr-3">
                        <i class="fas fa-church"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Gerenciar Ministérios</p>
                        <p class="text-sm text-gray-500">Organizar ministérios</p>
                    </div>
                </a>

                <a href="{{ route('admin.people.departments.index') }}" 
                   class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="p-2 rounded-lg bg-orange-500 text-white mr-3">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Gerenciar Departamentos</p>
                        <p class="text-sm text-gray-500">Estrutura organizacional</p>
                    </div>
                </a>

                <a href="{{ route('admin.people.birthdays.index') }}" 
                   class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="p-2 rounded-lg bg-pink-500 text-white mr-3">
                        <i class="fas fa-birthday-cake"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Aniversariantes</p>
                        <p class="text-sm text-gray-500">Ver próximos aniversários</p>
                    </div>
                </a>

                <a href="{{ route('admin.people.reports.index') }}" 
                   class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="p-2 rounded-lg bg-indigo-500 text-white mr-3">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Relatórios</p>
                        <p class="text-sm text-gray-500">Relatórios e estatísticas</p>
                    </div>
                </a>

                <a href="{{ route('admin.people.export', ['tipo' => 'membros']) }}" 
                   class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="p-2 rounded-lg bg-teal-500 text-white mr-3">
                        <i class="fas fa-download"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Exportar Dados</p>
                        <p class="text-sm text-gray-500">Baixar relatórios em Excel</p>
                    </div>
                </a>


            </div>
        </div>
    </div>
</div>
@endsection 