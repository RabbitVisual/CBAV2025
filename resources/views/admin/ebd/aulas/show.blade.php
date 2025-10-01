@extends('layouts.admin')

@section('title', 'Detalhes da Aula EBD')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Detalhes da Aula</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.ebd.aulas.edit', $aula) }}" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">Editar</a>
            <a href="{{ route('admin.ebd.aulas.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Voltar</a>
        </div>
    </div>
    
    <div class="bg-white shadow rounded-lg p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informações da Aula -->
            <div class="lg:col-span-2">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informações da Aula</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Data da Aula</label>
                        <p class="text-gray-900">{{ $aula->data_aula->format('d/m/Y') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $aula->status === 'agendada' ? 'bg-blue-100 text-blue-800' : 
                               ($aula->status === 'realizada' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($aula->status) }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Horário de Início</label>
                        <p class="text-gray-900">{{ $aula->horario_inicio ? $aula->horario_inicio->format('H:i') : 'Não definido' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Horário de Fim</label>
                        <p class="text-gray-900">{{ $aula->horario_fim ? $aula->horario_fim->format('H:i') : 'Não definido' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Duração</label>
                        <p class="text-gray-900">{{ $aula->duracao ?? 'Não calculada' }}</p>
                    </div>
                </div>

                <!-- Lição -->
                @if($aula->licao)
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Lições</h3>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Título</label>
                                    <p class="text-gray-900">{{ $aula->licao->titulo }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Dificuldade</label>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $aula->licao->cor_dificuldade }}">
                                        {{ $aula->licao->dificuldade_formatada }}
                                    </span>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Descrição</label>
                                    <p class="text-gray-900">{{ $aula->licao->descricao ?? 'Sem descrição' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Professor -->
                @if($aula->professor)
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Professor</h3>
                        
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nome</label>
                                    <p class="text-gray-900">{{ $aula->professor->nome }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tipo</label>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $aula->professor->tipo === 'principal' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ ucfirst($aula->professor->tipo) }}
                                    </span>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <p class="text-gray-900">{{ $aula->professor->email ?? 'Não informado' }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Telefone</label>
                                    <p class="text-gray-900">{{ $aula->professor->telefone ?? 'Não informado' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Observações -->
                @if($aula->observacoes)
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Observações</h3>
                        <p class="text-gray-900">{{ $aula->observacoes }}</p>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Informações da Turma -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Turma</h3>
                    
                    <div class="space-y-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nome da Turma</label>
                            <p class="text-gray-900">{{ $aula->turma->nome }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Faixa Etária</label>
                            <p class="text-gray-900">{{ $aula->turma->faixa_etaria ?? 'Não especificada' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Capacidade</label>
                            <p class="text-gray-900">{{ $aula->turma->capacidade_maxima ?? 'Ilimitada' }} alunos</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Alunos Matriculados</label>
                            <p class="text-gray-900">{{ $aula->turma->total_alunos }}</p>
                        </div>
                    </div>
                </div>

                <!-- Estatísticas de Presença -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Presença</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total de Presenças</span>
                            <span class="font-medium">{{ $aula->total_presencas }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total de Ausências</span>
                            <span class="font-medium">{{ $aula->total_ausencias }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Percentual de Presença</span>
                            <span class="font-medium">{{ number_format($aula->percentual_presenca, 1) }}%</span>
                        </div>
                    </div>
                </div>

                <!-- Avaliações -->
                @if($aula->avaliacoes->count() > 0)
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Avaliações</h3>
                        
                        <div class="space-y-2">
                            @foreach($aula->avaliacoes as $avaliacao)
                                <div class="border-l-4 border-blue-500 pl-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $avaliacao->titulo }}</div>
                                    <div class="text-xs text-gray-500">{{ $avaliacao->tipo_formatado }} - {{ $avaliacao->pontuacao_maxima }} pontos</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Lista de Presenças -->
        @if($aula->presencas->count() > 0)
            <div class="mt-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Lista de Presenças</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">Aluno</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Observações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($aula->presencas as $presenca)
                                <tr>
                                    <td class="border px-4 py-2">{{ $presenca->aluno->nome_completo }}</td>
                                    <td class="border px-4 py-2">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $presenca->status === 'presente' ? 'bg-green-100 text-green-800' : 
                                               ($presenca->status === 'ausente' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ ucfirst($presenca->status) }}
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
</div>
@endsection 