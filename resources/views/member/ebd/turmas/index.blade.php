@extends('layouts.member')

@section('title', 'Turmas EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Turmas EBD</h1>
        <p class="text-gray-600">Visualize as turmas disponíveis na Escola Bíblica Dominical</p>
    </div>

    @if($turmas->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($turmas as $turma)
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-gray-900">{{ $turma->nome }}</h3>
                        <span class="px-3 py-1 text-sm font-medium rounded-full 
                            {{ $turma->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $turma->ativo ? 'Ativa' : 'Inativa' }}
                        </span>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-users mr-2"></i>
                            <span>{{ $turma->alunos->count() }} alunos</span>
                        </div>
                        
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-chalkboard-teacher mr-2"></i>
                            <span>{{ $turma->professores->count() }} professores</span>
                        </div>
                        
                        @if($turma->faixa_etaria)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-birthday-cake mr-2"></i>
                                <span>{{ $turma->faixa_etaria }}</span>
                            </div>
                        @endif
                        
                        @if($turma->descricao)
                            <p class="text-sm text-gray-600">{{ Str::limit($turma->descricao, 100) }}</p>
                        @endif
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('member.ebd.turmas.show', $turma) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-eye mr-2"></i>
                            Ver Detalhes
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-graduation-cap text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Nenhuma Turma Disponível</h3>
            <p class="text-gray-500">Não há turmas EBD disponíveis no momento.</p>
        </div>
    @endif
</div>
@endsection 