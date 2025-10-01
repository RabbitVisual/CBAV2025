@extends('layouts.admin')

@section('title', 'Detalhes da Turma EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Detalhes da Turma EBD</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.ebd.turmas.edit', $turma) }}" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <a href="{{ route('admin.ebd.turmas.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informações da Turma -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $turma->cor ?? '#3B82F6' }}"></div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $turma->nome }}</h2>
                    <span class="ml-4 inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $turma->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $turma->ativo ? 'Ativa' : 'Inativa' }}
                    </span>
                </div>

                @if($turma->descricao)
                    <p class="text-gray-600 mb-4">{{ $turma->descricao }}</p>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Faixa Etária</h3>
                        <p class="text-gray-900">{{ $turma->faixa_etaria ?? 'Não definida' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Capacidade Máxima</h3>
                        <p class="text-gray-900">{{ $turma->capacidade_maxima ?? 'Ilimitada' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Total de Alunos</h3>
                        <p class="text-gray-900">{{ $turma->alunos_count ?? 0 }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Total de Professores</h3>
                        <p class="text-gray-900">{{ $turma->professores_count ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="bg-white shadow-md rounded-lg p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Estatísticas</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $turma->aulas_count ?? 0 }}</div>
                        <div class="text-sm text-gray-500">Total de Aulas</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $turma->aulas_realizadas_count ?? 0 }}</div>
                        <div class="text-sm text-gray-500">Aulas Realizadas</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-yellow-600">{{ $turma->aulas_agendadas_count ?? 0 }}</div>
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
                    <a href="{{ route('admin.ebd.alunos.create') }}?turma_id={{ $turma->id }}" 
                       class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-user-plus mr-2"></i>Adicionar Aluno
                    </a>
                    <a href="{{ route('admin.ebd.professores.create') }}?turma_id={{ $turma->id }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-chalkboard-teacher mr-2"></i>Adicionar Professor
                    </a>
                    <a href="{{ route('admin.ebd.aulas.create') }}?turma_id={{ $turma->id }}" 
                       class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-calendar-plus mr-2"></i>Agendar Aula
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 