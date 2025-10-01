@extends('layouts.member')

@section('title', 'Aulas EBD')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Aulas da Escola Bíblica Dominical</h1>
        <p class="text-gray-600">Acompanhe o calendário de aulas e atividades</p>
    </div>

    @if($aulas->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($aulas as $aula)
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-gray-900">{{ $aula->licao->titulo ?? 'Aula sem Lição' }}</h3>
                        <span class="px-3 py-1 text-sm font-medium rounded-full 
                            {{ $aula->cor_status }}">
                            {{ $aula->status_formatado }}
                        </span>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-users mr-2"></i>
                            <span>{{ $aula->turma->nome }}</span>
                        </div>

                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-calendar mr-2"></i>
                            <span>{{ $aula->data_aula->format('d/m/Y') }}</span>
                        </div>

                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-clock mr-2"></i>
                            <span>{{ $aula->horario_inicio->format('H:i') }} - {{ $aula->horario_fim->format('H:i') }}</span>
                        </div>

                        @if($aula->professor)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-chalkboard-teacher mr-2"></i>
                                <span>{{ $aula->professor->membro->nome ?? $aula->professor->nome }}</span>
                            </div>
                        @endif

                        @if($aula->observacoes)
                            <p class="text-sm text-gray-600">{{ Str::limit($aula->observacoes, 100) }}</p>
                        @endif

                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <span>{{ $aula->duracao }}</span>
                            <span>{{ $aula->total_presencas }} presentes</span>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('member.ebd.aulas.show', $aula) }}" 
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
            <i class="fas fa-calendar-alt text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Nenhuma Aula Disponível</h3>
            <p class="text-gray-500">Não há aulas EBD agendadas no momento.</p>
        </div>
    @endif

    <!-- Filtros -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtros</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="agendada">Agendadas</option>
                    <option value="realizada">Realizadas</option>
                    <option value="cancelada">Canceladas</option>
                    <option value="adiada">Adiadas</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Turma</label>
                <select class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todas</option>
                    @foreach($aulas->pluck('turma')->unique() as $turma)
                        <option value="{{ $turma->id }}">{{ $turma->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Data Início</label>
                <input type="date" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Data Fim</label>
                <input type="date" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-calendar-alt text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Aulas</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $aulas->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Realizadas</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $aulas->where('status', 'realizada')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Agendadas</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $aulas->where('status', 'agendada')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-times-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Canceladas</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $aulas->where('status', 'cancelada')->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 