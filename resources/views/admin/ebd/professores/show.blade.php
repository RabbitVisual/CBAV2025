@extends('layouts.admin')

@section('title', 'Detalhes do Professor EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Detalhes do Professor EBD</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.ebd.professores.edit', $professor) }}" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <a href="{{ route('admin.ebd.professores.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informações do Professor -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 h-16 w-16">
                        <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-blue-600 font-bold text-xl">{{ substr($professor->nome, 0, 2) }}</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $professor->nome }}</h2>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $professor->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $professor->ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Email</h3>
                        <p class="text-gray-900">{{ $professor->email ?? 'Não informado' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Telefone</h3>
                        <p class="text-gray-900">{{ $professor->telefone ?? 'Não informado' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Turma</h3>
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full mr-3" style="background-color: {{ $professor->turma->cor ?? '#3B82F6' }}"></div>
                            <p class="text-gray-900">{{ $professor->turma->nome }}</p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Tipo</h3>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $professor->tipo === 'principal' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $professor->tipo === 'principal' ? 'Professor Principal' : 'Professor Auxiliar' }}
                        </span>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Data de Início</h3>
                        <p class="text-gray-900">{{ $professor->data_inicio->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Data de Fim</h3>
                        <p class="text-gray-900">{{ $professor->data_fim ? $professor->data_fim->format('d/m/Y') : 'Não definida' }}</p>
                    </div>
                </div>

                @if($professor->membro)
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Informações do Membro</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-900"><strong>Nome:</strong> {{ $professor->membro->nome }}</p>
                            <p class="text-gray-900"><strong>Email:</strong> {{ $professor->membro->email ?? 'Não informado' }}</p>
                            <p class="text-gray-900"><strong>Telefone:</strong> {{ $professor->membro->telefone ?? 'Não informado' }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Estatísticas -->
            <div class="bg-white shadow-md rounded-lg p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Estatísticas</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $professor->total_aulas }}</div>
                        <div class="text-sm text-gray-500">Total de Aulas</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $professor->aulas_realizadas }}</div>
                        <div class="text-sm text-gray-500">Aulas Realizadas</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-yellow-600">{{ $professor->aulas_agendadas }}</div>
                        <div class="text-sm text-gray-500">Aulas Agendadas</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações Rápidas -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ações Rápidas</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.ebd.aulas.create') }}?professor_id={{ $professor->id }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-calendar-plus mr-2"></i>Agendar Aula
                    </a>
                    <a href="{{ route('admin.ebd.aulas.index') }}?professor_id={{ $professor->id }}" 
                       class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-list mr-2"></i>Ver Aulas
                    </a>
                </div>
            </div>

            <!-- Status -->
            <div class="bg-white shadow-md rounded-lg p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Status:</span>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $professor->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $professor->ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Cadastrado em:</span>
                        <span class="text-sm text-gray-900">{{ $professor->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Atualizado em:</span>
                        <span class="text-sm text-gray-900">{{ $professor->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 