@extends('layouts.member')

@section('title', 'Detalhes da Aula EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $aula->licao->titulo ?? 'Aula sem Lição' }}</h1>
                <p class="text-gray-600">{{ $aula->turma->nome }} - {{ $aula->data_aula->format('d/m/Y') }}</p>
            </div>
            <span class="px-4 py-2 text-sm font-medium rounded-full {{ $aula->cor_status }}">
                {{ $aula->status_formatado }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Conteúdo Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informações da Aula -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informações da Aula</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Turma</label>
                        <p class="text-gray-900">{{ $aula->turma->nome }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Data</label>
                        <p class="text-gray-900">{{ $aula->data_aula->format('d/m/Y') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Horário</label>
                        <p class="text-gray-900">{{ $aula->horario_inicio->format('H:i') }} - {{ $aula->horario_fim->format('H:i') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Duração</label>
                        <p class="text-gray-900">{{ $aula->duracao }}</p>
                    </div>
                    
                    @if($aula->professor)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Professor</label>
                            <p class="text-gray-900">{{ $aula->professor->membro->nome ?? $aula->professor->nome }}</p>
                        </div>
                    @endif
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $aula->cor_status }}">
                            {{ $aula->status_formatado }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Lição Relacionada -->
            @if($aula->licao)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Lição: {{ $aula->licao->titulo }}</h2>
                    
                    <div class="space-y-3">
                        @if($aula->licao->descricao)
                            <p class="text-gray-600">{{ $aula->licao->descricao }}</p>
                        @endif
                        
                        @if($aula->licao->versiculo_chave)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-book-bible mr-2"></i>
                                <span class="italic">{{ $aula->licao->versiculo_chave }}</span>
                            </div>
                        @endif
                        
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-clock mr-2"></i>
                            <span>{{ $aula->licao->duracao_formatada }}</span>
                        </div>
                        
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-signal mr-2"></i>
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $aula->licao->cor_dificuldade }}">
                                {{ $aula->licao->dificuldade_formatada }}
                            </span>
                        </div>
                        
                        @if($aula->licao->objetivos)
                            <div class="text-sm text-gray-600">
                                <strong>Objetivos:</strong> {{ Str::limit($aula->licao->objetivos, 150) }}
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('member.ebd.licoes.show', $aula->licao) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-book-open mr-2"></i>
                            Ver Lição Completa
                        </a>
                    </div>
                </div>
            @endif

            <!-- Observações -->
            @if($aula->observacoes)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Observações</h2>
                    
                    <div class="prose max-w-none">
                        {!! nl2br(e($aula->observacoes)) !!}
                    </div>
                </div>
            @endif

            <!-- Lista de Presença -->
            @if($aula->presencas->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Lista de Presença</h2>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left">Aluno</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                    <th class="px-4 py-2 text-left">Observações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($aula->presencas as $presenca)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $presenca->aluno->nome }}</td>
                                        <td class="border px-4 py-2">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                                {{ $presenca->cor_status }}">
                                                {{ $presenca->status_formatado }}
                                            </span>
                                        </td>
                                        <td class="border px-4 py-2">{{ $presenca->observacoes ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                        <span class="text-gray-600">Total de Alunos</span>
                        <span class="font-medium">{{ $aula->turma->alunos->count() }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Presentes</span>
                        <span class="font-medium">{{ $aula->total_presencas }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ausentes</span>
                        <span class="font-medium">{{ $aula->total_ausencias }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Percentual de Presença</span>
                        <span class="font-medium">{{ number_format($aula->percentual_presenca, 1) }}%</span>
                    </div>
                </div>
            </div>

            <!-- Avaliações da Aula -->
            @if($aula->avaliacoes->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Avaliações</h3>
                    
                    <div class="space-y-3">
                        @foreach($aula->avaliacoes as $avaliacao)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $avaliacao->titulo }}</p>
                                    <p class="text-sm text-gray-600">{{ ucfirst($avaliacao->tipo_formatado) }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $avaliacao->cor_tipo }}">
                                    {{ $avaliacao->pontuacao_maxima }} pts
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Próximas Aulas -->
            @if($aula->turma->aulas->where('data_aula', '>', $aula->data_aula)->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Próximas Aulas</h3>
                    
                    <div class="space-y-3">
                        @foreach($aula->turma->aulas->where('data_aula', '>', $aula->data_aula)->take(3) as $proximaAula)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $proximaAula->licao->titulo ?? 'Aula sem Lição' }}</p>
                                    <p class="text-sm text-gray-600">{{ $proximaAula->data_aula->format('d/m/Y H:i') }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                    {{ $proximaAula->status === 'agendada' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($proximaAula->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Ações -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ações</h3>
                
                <div class="space-y-3">
                    @if($aula->licao)
                        <a href="{{ route('member.ebd.licoes.show', $aula->licao) }}" 
                           class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-book-open mr-2"></i>
                            Ver Lição
                        </a>
                    @endif
                    
                    <a href="{{ route('member.ebd.turmas.show', $aula->turma) }}" 
                       class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                        <i class="fas fa-users mr-2"></i>
                        Ver Turma
                    </a>
                    
                    <a href="{{ route('member.ebd.aulas.index') }}" 
                       class="w-full flex items-center justify-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Voltar às Aulas
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 