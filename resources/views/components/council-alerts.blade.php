@props(['conselho'])

@php
    $totalParticipantes = $conselho->participantes()->count();
    $presentesCount = $conselho->participantes()->where('presente', true)->count();
    $currentQuorum = $totalParticipantes > 0 ? round(($presentesCount / $totalParticipantes) * 100, 1) : 0;
    $requiredQuorum = $conselho->quorum_minimo ?? 50;
    
    $votacoesPendentes = $conselho->votacoes()->where('status', 'em_andamento')->count();
    $pautasPendentes = $conselho->pautas()->where('status', 'pendente')->count();
    
    $isLate = $conselho->status === 'agendada' && 
              $conselho->data_reuniao->isPast() && 
              now()->greaterThan($conselho->data_reuniao->setTimeFromTimeString($conselho->hora_inicio));
@endphp

<div class="space-y-4">
    {{-- Alerta de Reunião em Atraso --}}
    @if($isLate)
    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">{{ __('Reunião em Atraso') }}</h3>
                <p class="text-sm text-red-700">
                    {{ __('Esta reunião estava agendada para') }} {{ $conselho->data_reuniao->format('d/m/Y') }} 
                    {{ __('às') }} {{ $conselho->hora_inicio }} {{ __('e ainda não foi iniciada.') }}
                </p>
                <div class="mt-2">
                    <form action="{{ route('admin.council.iniciar', $conselho) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">
                            <i class="fas fa-play mr-1"></i>{{ __('Iniciar Agora') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Alerta de Quórum --}}
    @if($conselho->status === 'em_andamento' && $currentQuorum < $requiredQuorum)
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-users text-yellow-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">{{ __('Quórum Insuficiente') }}</h3>
                <p class="text-sm text-yellow-700">
                    {{ __('Presentes:') }} {{ $presentesCount }}/{{ $totalParticipantes }} 
                    ({{ $currentQuorum }}%)
                    • {{ __('Necessário:') }} {{ $requiredQuorum }}%
                </p>
                <div class="mt-2">
                    <a href="{{ route('admin.council.attendance.index', $conselho) }}" 
                       class="bg-yellow-600 text-white px-3 py-1 rounded text-sm hover:bg-yellow-700">
                        <i class="fas fa-check mr-1"></i>{{ __('Gerenciar Presença') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Alerta de Votações Pendentes --}}
    @if($votacoesPendentes > 0)
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-vote-yea text-blue-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">{{ __('Votações Pendentes') }}</h3>
                <p class="text-sm text-blue-700">
                    {{ __('Existem') }} {{ $votacoesPendentes }} {{ __('votação(ões) em andamento que precisam de atenção.') }}
                </p>
                <div class="mt-2">
                    <a href="{{ route('admin.council.voting.index', $conselho) }}" 
                       class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">
                        <i class="fas fa-eye mr-1"></i>{{ __('Ver Votações') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Informações de Status Positivo --}}
    @if($conselho->status === 'em_andamento' && $currentQuorum >= $requiredQuorum)
    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-green-800">{{ __('Reunião em Andamento') }}</h3>
                <p class="text-sm text-green-700">
                    {{ __('Quórum atingido:') }} {{ $presentesCount }}/{{ $totalParticipantes }} 
                    ({{ $currentQuorum }}%)
                    @if($pautasPendentes > 0)
                        • {{ $pautasPendentes }} {{ __('pauta(s) pendente(s)') }}
                    @endif
                </p>
            </div>
        </div>
    </div>
    @endif

    {{-- Status da Reunião Finalizada --}}
    @if($conselho->status === 'finalizada')
    <div class="bg-gray-50 border-l-4 border-gray-400 p-4 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-flag-checkered text-gray-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-gray-800">{{ __('Reunião Finalizada') }}</h3>
                <p class="text-sm text-gray-700">
                    {{ __('Reunião concluída em') }} {{ $conselho->hora_fim ? $conselho->hora_fim->format('d/m/Y H:i') : $conselho->updated_at->format('d/m/Y H:i') }}
                </p>
                <div class="mt-2">
                    <a href="{{ route('admin.council.relatorio', $conselho) }}" 
                       class="bg-gray-600 text-white px-3 py-1 rounded text-sm hover:bg-gray-700">
                        <i class="fas fa-file-alt mr-1"></i>{{ __('Ver Relatório') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- JavaScript para atualização automática de alertas --}}
@push('scripts')
<script>
// Atualizar alertas a cada 30 segundos
setInterval(function() {
    if ('{{ $conselho->status }}' === 'em_andamento') {
        fetch('{{ route("admin.council.status", $conselho) }}')
            .then(response => response.json())
            .then(data => {
                updateQuorumAlert(data.quorum);
                updateVotingAlerts(data.votacoes_pendentes);
            })
            .catch(error => console.log('Erro ao atualizar status:', error));
    }
}, 30000);

function updateQuorumAlert(quorumData) {
    const alertContainer = document.querySelector('[data-alert="quorum"]');
    if (alertContainer) {
        if (quorumData.current < quorumData.required) {
            alertContainer.style.display = 'block';
            alertContainer.querySelector('[data-quorum="current"]').textContent = quorumData.current + '%';
        } else {
            alertContainer.style.display = 'none';
        }
    }
}

function updateVotingAlerts(votacoesPendentes) {
    const alertContainer = document.querySelector('[data-alert="voting"]');
    if (alertContainer) {
        if (votacoesPendentes > 0) {
            alertContainer.style.display = 'block';
            alertContainer.querySelector('[data-voting="count"]').textContent = votacoesPendentes;
        } else {
            alertContainer.style.display = 'none';
        }
    }
}
</script>
@endpush 