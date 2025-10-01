@extends('layouts.member')
@section('title', 'Dashboard EBD')
@section('content')
<div class="container mx-auto py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Bem-vindo à Escola Bíblica Dominical</h1>
        <p class="text-gray-600">Acompanhe seu progresso e participe das aulas</p>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Turma</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $aluno->turma->nome }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Presença</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $estatisticas['percentual_presenca'] }}%</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-graduation-cap text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Média Geral</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $estatisticas['media_geral'] }}%</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-calendar-alt text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Matrícula</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $aluno->data_matricula->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Próximas Aulas -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Próximas Aulas</h3>
            </div>
            <div class="p-6">
                @forelse($proximasAulas as $aula)
                <div class="border-b border-gray-100 pb-4 mb-4 last:border-b-0 last:pb-0 last:mb-0">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $aula->licao->titulo }}</h4>
                            <p class="text-sm text-gray-600">{{ $aula->data_aula->format('d/m/Y') }} às {{ $aula->horario_inicio->format('H:i') }}</p>
                            @if($aula->professor)
                                <p class="text-xs text-blue-600">Prof. {{ $aula->professor->nome }}</p>
                            @endif
                        </div>
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Agendada</span>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">Nenhuma aula agendada.</p>
                @endforelse
            </div>
        </div>

        <!-- Avaliações Pendentes -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Avaliações Pendentes</h3>
            </div>
            <div class="p-6">
                @forelse($avaliacoesPendentes as $avaliacao)
                <div class="border-b border-gray-100 pb-4 mb-4 last:border-b-0 last:pb-0 last:mb-0">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $avaliacao->titulo }}</h4>
                            <p class="text-sm text-gray-600">{{ $avaliacao->aula->licao->titulo }}</p>
                            <p class="text-xs text-gray-500">{{ $avaliacao->tipo_formatado }}</p>
                        </div>
                        <a href="{{ route('member.ebd.quiz.show', $avaliacao) }}" 
                           class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                            Realizar
                        </a>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">Nenhuma avaliação pendente.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Últimas Aulas -->
    <div class="mt-8 bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Histórico de Aulas</h3>
        </div>
        <div class="p-6">
            @forelse($ultimasAulas as $aula)
            <div class="border-b border-gray-100 pb-4 mb-4 last:border-b-0 last:pb-0 last:mb-0">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-semibold text-gray-900">{{ $aula->licao->titulo }}</h4>
                        <p class="text-sm text-gray-600">{{ $aula->data_aula->format('d/m/Y') }} às {{ $aula->horario_inicio->format('H:i') }}</p>
                        @if($aula->professor)
                            <p class="text-xs text-blue-600">Prof. {{ $aula->professor->nome }}</p>
                        @endif
                    </div>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Realizada</span>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">Nenhuma aula realizada ainda.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection 