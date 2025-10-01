@extends('layouts.admin')

@section('title', 'Detalhes do Aluno EBD')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Detalhes do Aluno</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.ebd.alunos.edit', $aluno) }}" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">Editar</a>
            <a href="{{ route('admin.ebd.alunos.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Voltar</a>
        </div>
    </div>
    
    <div class="bg-white shadow rounded-lg p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informações do Aluno -->
            <div class="lg:col-span-2">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informações do Aluno</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nome Completo</label>
                        <p class="text-gray-900">{{ $aluno->nome }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $aluno->status === 'ativo' ? 'bg-green-100 text-green-800' : 
                               ($aluno->status === 'inativo' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($aluno->status) }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <p class="text-gray-900">{{ $aluno->email ?? 'Não informado' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Telefone</label>
                        <p class="text-gray-900">{{ $aluno->telefone ?? 'Não informado' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Data de Nascimento</label>
                        <p class="text-gray-900">{{ $aluno->data_nascimento ? $aluno->data_nascimento->format('d/m/Y') : 'Não informada' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Idade</label>
                        <p class="text-gray-900">{{ $aluno->idade ?? 'Não calculada' }} anos</p>
                    </div>
                </div>

                <!-- Informações do Responsável -->
                @if($aluno->nome_responsavel || $aluno->telefone_responsavel)
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Informações do Responsável</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nome do Responsável</label>
                                <p class="text-gray-900">{{ $aluno->nome_responsavel ?? 'Não informado' }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Telefone do Responsável</label>
                                <p class="text-gray-900">{{ $aluno->telefone_responsavel ?? 'Não informado' }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Informações da Matrícula -->
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Informações da Matrícula</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Data de Matrícula</label>
                            <p class="text-gray-900">{{ $aluno->data_matricula->format('d/m/Y') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tempo de Matrícula</label>
                            <p class="text-gray-900">{{ $aluno->tempo_matricula }}</p>
                        </div>
                        
                        @if($aluno->data_saida)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Data de Saída</label>
                                <p class="text-gray-900">{{ $aluno->data_saida->format('d/m/Y') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Observações -->
                @if($aluno->observacoes)
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Observações</h3>
                        <p class="text-gray-900">{{ $aluno->observacoes }}</p>
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
                            <p class="text-gray-900">{{ $aluno->turma->nome }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Faixa Etária</label>
                            <p class="text-gray-900">{{ $aluno->turma->faixa_etaria ?? 'Não especificada' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Capacidade</label>
                            <p class="text-gray-900">{{ $aluno->turma->capacidade_maxima ?? 'Ilimitada' }} alunos</p>
                        </div>
                    </div>
                </div>

                <!-- Estatísticas -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Estatísticas</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total de Presenças</span>
                            <span class="font-medium">{{ $aluno->total_presencas }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total de Ausências</span>
                            <span class="font-medium">{{ $aluno->total_ausencias }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Percentual de Presença</span>
                            <span class="font-medium">{{ number_format($aluno->percentual_presenca, 1) }}%</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Média Geral</span>
                            <span class="font-medium">{{ number_format($aluno->media_geral, 1) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Membro Associado -->
                @if($aluno->membro)
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Membro Associado</h3>
                        
                        <div class="space-y-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nome</label>
                                <p class="text-gray-900">{{ $aluno->membro->nome }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <p class="text-gray-900">{{ $aluno->membro->email ?? 'Não informado' }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Telefone</label>
                                <p class="text-gray-900">{{ $aluno->membro->telefone ?? 'Não informado' }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Histórico de Aulas -->
        @if($aluno->presencas->count() > 0)
            <div class="mt-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Histórico de Aulas</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">Data</th>
                                <th class="px-4 py-2">Lições</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Professor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($aluno->presencas->take(10) as $presenca)
                                <tr>
                                    <td class="border px-4 py-2">{{ $presenca->aula->data_aula->format('d/m/Y') }}</td>
                                    <td class="border px-4 py-2">{{ $presenca->aula->licao->titulo ?? 'N/A' }}</td>
                                    <td class="border px-4 py-2">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $presenca->status === 'presente' ? 'bg-green-100 text-green-800' : 
                                               ($presenca->status === 'ausente' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ ucfirst($presenca->status) }}
                                        </span>
                                    </td>
                                    <td class="border px-4 py-2">{{ $presenca->aula->professor->membro->nome ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Histórico de Notas -->
        @if($aluno->notas->count() > 0)
            <div class="mt-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Histórico de Notas</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">Avaliação</th>
                                <th class="px-4 py-2">Tipo</th>
                                <th class="px-4 py-2">Nota</th>
                                <th class="px-4 py-2">Conceito</th>
                                <th class="px-4 py-2">Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($aluno->notas->take(10) as $nota)
                                <tr>
                                    <td class="border px-4 py-2">{{ $nota->avaliacao->titulo }}</td>
                                    <td class="border px-4 py-2">{{ ucfirst($nota->avaliacao->tipo) }}</td>
                                    <td class="border px-4 py-2">{{ $nota->nota }}/{{ $nota->avaliacao->pontuacao_maxima }}</td>
                                    <td class="border px-4 py-2">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $nota->conceito === 'A' ? 'bg-green-100 text-green-800' : 
                                               ($nota->conceito === 'B' ? 'bg-blue-100 text-blue-800' : 
                                               ($nota->conceito === 'C' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                                            {{ $nota->conceito }}
                                        </span>
                                    </td>
                                    <td class="border px-4 py-2">{{ $nota->created_at->format('d/m/Y') }}</td>
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