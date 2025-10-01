@extends('layouts.member')

@section('title', 'Detalhes da Turma EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $turma->nome }}</h1>
                <p class="text-gray-600">{{ $turma->descricao }}</p>
            </div>
            <span class="px-4 py-2 text-sm font-medium rounded-full 
                {{ $turma->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $turma->ativo ? 'Ativa' : 'Inativa' }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informações da Turma -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informações da Turma</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Faixa Etária</label>
                        <p class="text-gray-900">{{ $turma->faixa_etaria ?? 'Não especificada' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Capacidade</label>
                        <p class="text-gray-900">{{ $turma->capacidade ?? 'Ilimitada' }} alunos</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Total de Alunos</label>
                        <p class="text-gray-900">{{ $turma->alunos->count() }} alunos</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Total de Professores</label>
                        <p class="text-gray-900">{{ $turma->professores->count() }} professores</p>
                    </div>
                </div>
            </div>

            <!-- Professores -->
            @if($turma->professores->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Professores</h2>
                    
                    <div class="space-y-3">
                        @foreach($turma->professores as $professor)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-chalkboard-teacher text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $professor->membro->nome ?? $professor->nome }}</p>
                                        <p class="text-sm text-gray-600">{{ ucfirst($professor->tipo) }}</p>
                                    </div>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                    {{ $professor->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $professor->ativo ? 'Ativo' : 'Inativo' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Aulas Recentes -->
            @if($turma->aulas->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Aulas Recentes</h2>
                    
                    <div class="space-y-3">
                        @foreach($turma->aulas->take(5) as $aula)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $aula->licao->titulo }}</p>
                                    <p class="text-sm text-gray-600">{{ $aula->data_aula->format('d/m/Y H:i') }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                    {{ $aula->status === 'realizada' ? 'bg-green-100 text-green-800' : 
                                       ($aula->status === 'agendada' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($aula->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Estatísticas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Estatísticas</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total de Aulas</span>
                        <span class="font-medium">{{ $turma->aulas->count() }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Aulas Realizadas</span>
                        <span class="font-medium">{{ $turma->aulas->where('status', 'realizada')->count() }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Aulas Agendadas</span>
                        <span class="font-medium">{{ $turma->aulas->where('status', 'agendada')->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Ações -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ações</h3>
                
                <div class="space-y-3">
                    <a href="{{ route('member.ebd.licoes.index') }}" 
                       class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-book-open mr-2"></i>
                        Ver Lições
                    </a>
                    
                    <a href="{{ route('member.ebd.aulas.index') }}" 
                       class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Ver Aulas
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 