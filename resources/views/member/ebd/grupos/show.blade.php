@extends('layouts.member')

@section('title', 'Grupo de Estudo - ' . $grupo->nome)

@section('content')
<div class="container-fluid">
    <!-- Header do Grupo -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-2">
                                <div class="badge rounded-pill me-3" style="background-color: {{ $grupo->cor }}; width: 20px; height: 20px;"></div>
                                <h3 class="mb-0">{{ $grupo->nome }}</h3>
                                <span class="badge bg-{{ $grupo->status === 'ativo' ? 'success' : 'secondary' }} ms-2">
                                    {{ ucfirst($grupo->status) }}
                                </span>
                            </div>
                            <p class="text-muted mb-2">{{ $grupo->descricao }}</p>
                            <div class="d-flex flex-wrap gap-3">
                                <small class="text-muted">
                                    <i class="fas fa-users me-1"></i>
                                    {{ $grupo->membros_count }}/{{ $grupo->capacidade_maxima }} membros
                                </small>
                                <small class="text-muted">
                                    <i class="fas fa-graduation-cap me-1"></i>
                                    {{ $grupo->turma->nome }}
                                </small>
                                @if($grupo->lider)
                                <small class="text-muted">
                                    <i class="fas fa-crown me-1"></i>
                                    Líder: {{ $grupo->lider->nome }}
                                </small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            @if($isMember)
                                @if($isLeader)
                                    <span class="badge bg-warning text-dark fs-6 mb-2">
                                        <i class="fas fa-crown me-1"></i>Você é o líder
                                    </span>
                                @else
                                    <span class="badge bg-info fs-6 mb-2">
                                        <i class="fas fa-user-check me-1"></i>Você é membro
                                    </span>
                                @endif
                                <br>
                                @if(!$isLeader)
                                <form action="{{ route('member.ebd.grupos.sair', $grupo) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" 
                                            onclick="return confirm('Tem certeza que deseja sair do grupo?')">
                                        <i class="fas fa-sign-out-alt me-1"></i>Sair do Grupo
                                    </button>
                                </form>
                                @endif
                            @else
                                @if(!$grupo->isLotado())
                                <form action="{{ route('member.ebd.grupos.entrar', $grupo) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-user-plus me-1"></i>Entrar no Grupo
                                    </button>
                                </form>
                                @else
                                <span class="badge bg-secondary fs-6">
                                    <i class="fas fa-users me-1"></i>Grupo Lotado
                                </span>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Membros do Grupo -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-users text-primary me-2"></i>Membros do Grupo
                    </h5>
                </div>
                <div class="card-body">
                    @if($grupo->membros->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($grupo->membros as $membro)
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                        <span class="text-white fw-bold">
                                            {{ strtoupper(substr($membro->aluno->nome, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $membro->aluno->nome }}</h6>
                                        <small class="text-muted">
                                            Membro desde {{ $membro->data_entrada->format('d/m/Y') }}
                                        </small>
                                    </div>
                                    @if($membro->aluno_id === $grupo->lider_id)
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-crown me-1"></i>Líder
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users text-muted fa-3x mb-3"></i>
                            <p class="text-muted">Nenhum membro no grupo ainda.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Avaliações Recentes -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-clipboard-check text-success me-2"></i>Avaliações Recentes
                    </h5>
                </div>
                <div class="card-body">
                    @if($avaliacoesRecentes->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($avaliacoesRecentes as $avaliacao)
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $avaliacao->avaliacao->titulo }}</h6>
                                        <p class="mb-1 text-muted small">{{ $avaliacao->avaliacao->descricao }}</p>
                                        <small class="text-muted">
                                            {{ $avaliacao->data_inicio->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-{{ $avaliacao->status === 'concluida' ? 'success' : ($avaliacao->status === 'em_andamento' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst(str_replace('_', ' ', $avaliacao->status)) }}
                                        </span>
                                        @if($avaliacao->status === 'concluida')
                                        <br><small class="text-success">{{ $avaliacao->pontuacao_total ?? 0 }} pts</small>
                                        @endif
                                    </div>
                                </div>
                                @if($avaliacao->status === 'em_andamento' && $isMember)
                                <div class="mt-2">
                                    <a href="{{ route('member.ebd.avaliacoes.participar', $avaliacao) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-play me-1"></i>Continuar Avaliação
                                    </a>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-clipboard-check text-muted fa-3x mb-3"></i>
                            <p class="text-muted">Nenhuma avaliação realizada ainda.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Estatísticas do Grupo -->
    @if($isMember)
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar text-info me-2"></i>Estatísticas do Grupo
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="border-end">
                                <h3 class="text-primary mb-1">{{ $estatisticas['total_avaliacoes'] }}</h3>
                                <small class="text-muted">Avaliações Realizadas</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border-end">
                                <h3 class="text-success mb-1">{{ $estatisticas['avaliacoes_concluidas'] }}</h3>
                                <small class="text-muted">Avaliações Concluídas</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border-end">
                                <h3 class="text-warning mb-1">{{ number_format($estatisticas['media_pontuacao'], 1) }}</h3>
                                <small class="text-muted">Média de Pontuação</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <h3 class="text-info mb-1">{{ $estatisticas['tempo_medio_resposta'] }}</h3>
                            <small class="text-muted">Tempo Médio de Resposta</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
.avatar-sm {
    width: 40px;
    height: 40px;
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.list-group-item {
    transition: background-color 0.2s ease-in-out;
}

.list-group-item:hover {
    background-color: #f8f9fa;
}

.border-end {
    border-right: 1px solid #dee2e6;
}

@media (max-width: 768px) {
    .border-end {
        border-right: none;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 1rem;
        margin-bottom: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Confirmação para sair do grupo
    $('form[action*="sair"]').on('submit', function(e) {
        if (!confirm('Tem certeza que deseja sair do grupo? Esta ação não pode ser desfeita.')) {
            e.preventDefault();
        }
    });
    
    // Confirmação para entrar no grupo
    $('form[action*="entrar"]').on('submit', function(e) {
        if (!confirm('Deseja realmente entrar neste grupo de estudo?')) {
            e.preventDefault();
        }
    });
    
    // Tooltip para badges
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
@endpush
@endsection