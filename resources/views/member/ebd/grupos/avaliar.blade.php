@extends('layouts.member')

@section('title', 'Avaliação em Grupo - ' . $avaliacao->avaliacao->titulo)

@section('content')
<div class="container-fluid">
    <!-- Header da Avaliação -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-2">
                                <div class="badge rounded-pill me-3" style="background-color: {{ $avaliacao->grupo->cor }}; width: 20px; height: 20px;"></div>
                                <h3 class="mb-0">{{ $avaliacao->avaliacao->titulo }}</h3>
                                <span class="badge bg-{{ $avaliacao->status === 'em_andamento' ? 'warning' : ($avaliacao->status === 'concluida' ? 'success' : 'secondary') }} ms-2">
                                    {{ ucfirst(str_replace('_', ' ', $avaliacao->status)) }}
                                </span>
                            </div>
                            <p class="text-muted mb-2">{{ $avaliacao->avaliacao->descricao }}</p>
                            <div class="d-flex flex-wrap gap-3">
                                <small class="text-muted">
                                    <i class="fas fa-users me-1"></i>
                                    Grupo: {{ $avaliacao->grupo->nome }}
                                </small>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    Iniciada em: {{ $avaliacao->data_inicio->format('d/m/Y H:i') }}
                                </small>
                                @if($avaliacao->data_conclusao)
                                <small class="text-muted">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Concluída em: {{ $avaliacao->data_conclusao->format('d/m/Y H:i') }}
                                </small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            @if($avaliacao->status === 'concluida')
                                <div class="text-center">
                                    <h4 class="text-success mb-1">{{ $avaliacao->pontuacao_total ?? 0 }}</h4>
                                    <small class="text-muted">Pontos Obtidos</small>
                                </div>
                            @else
                                <div class="text-center">
                                    <div class="progress mb-2" style="height: 8px;">
                                        <div class="progress-bar bg-warning" role="progressbar" 
                                             style="width: {{ $progresso }}%" 
                                             aria-valuenow="{{ $progresso }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted">{{ $progresso }}% concluído</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($avaliacao->status === 'em_andamento')
    <!-- Questão Atual -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-question-circle me-2"></i>
                            Questão {{ $questaoAtual->ordem }} de {{ $totalQuestoes }}
                        </h5>
                        <span class="badge bg-light text-dark">
                            {{ $questaoAtual->tipo === 'multipla_escolha' ? 'Múltipla Escolha' : 'Dissertativa' }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="mb-3">{{ $questaoAtual->pergunta }}</h4>
                            
                            @if($questaoAtual->tipo === 'multipla_escolha')
                                <form id="questaoForm" action="{{ route('member.ebd.avaliacoes.responder', [$avaliacao, $questaoAtual]) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        @foreach($questaoAtual->opcoes as $opcao)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" 
                                                   name="resposta" value="{{ $opcao->id }}" 
                                                   id="opcao{{ $opcao->id }}"
                                                   {{ $respostaAtual && $respostaAtual->resposta_final === $opcao->texto ? 'checked' : '' }}>
                                            <label class="form-check-label" for="opcao{{ $opcao->id }}">
                                                {{ $opcao->texto }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </form>
                            @else
                                <form id="questaoForm" action="{{ route('member.ebd.avaliacoes.responder', [$avaliacao, $questaoAtual]) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <textarea class="form-control" name="resposta" rows="5" 
                                                  placeholder="Digite sua resposta aqui...">{{ $respostaAtual->resposta_final ?? '' }}</textarea>
                                    </div>
                                </form>
                            @endif
                            
                            <div class="d-flex justify-content-between">
                                @if($questaoAtual->ordem > 1)
                                <a href="{{ route('member.ebd.avaliacoes.questao', [$avaliacao, $questaoAtual->ordem - 1]) }}" 
                                   class="btn btn-outline-secondary">
                                    <i class="fas fa-chevron-left me-1"></i>Anterior
                                </a>
                                @else
                                <div></div>
                                @endif
                                
                                <button type="submit" form="questaoForm" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Salvar Resposta
                                </button>
                                
                                @if($questaoAtual->ordem < $totalQuestoes)
                                <a href="{{ route('member.ebd.avaliacoes.questao', [$avaliacao, $questaoAtual->ordem + 1]) }}" 
                                   class="btn btn-outline-primary">
                                    Próxima<i class="fas fa-chevron-right ms-1"></i>
                                </a>
                                @else
                                <form action="{{ route('member.ebd.avaliacoes.finalizar', $avaliacao) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success" 
                                            onclick="return confirm('Tem certeza que deseja finalizar a avaliação?')">
                                        <i class="fas fa-check me-1"></i>Finalizar Avaliação
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Contribuições dos Membros -->
                        <div class="col-md-4">
                            <div class="card bg-light border-0">
                                <div class="card-header bg-transparent border-0">
                                    <h6 class="mb-0">
                                        <i class="fas fa-comments text-info me-2"></i>
                                        Contribuições do Grupo
                                    </h6>
                                </div>
                                <div class="card-body p-3">
                                    @if($contribuicoes->count() > 0)
                                        <div class="contribuicoes-list" style="max-height: 300px; overflow-y: auto;">
                                            @foreach($contribuicoes as $contribuicao)
                                            <div class="mb-3 p-2 bg-white rounded border-start border-3 
                                                        {{ $contribuicao->eh_resposta_final ? 'border-success' : 'border-secondary' }}">
                                                <div class="d-flex justify-content-between align-items-start mb-1">
                                                    <small class="fw-bold text-primary">
                                                        {{ $contribuicao->aluno->nome }}
                                                        @if($contribuicao->aluno_id === $avaliacao->grupo->lider_id)
                                                        <i class="fas fa-crown text-warning ms-1" title="Líder"></i>
                                                        @endif
                                                    </small>
                                                    <small class="text-muted">
                                                        {{ $contribuicao->created_at->format('H:i') }}
                                                    </small>
                                                </div>
                                                <p class="mb-1 small">{{ $contribuicao->contribuicao }}</p>
                                                @if($contribuicao->eh_resposta_final)
                                                <span class="badge bg-success badge-sm">
                                                    <i class="fas fa-check me-1"></i>Resposta Final
                                                </span>
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-3">
                                            <i class="fas fa-comments text-muted fa-2x mb-2"></i>
                                            <p class="text-muted small mb-0">Nenhuma contribuição ainda.</p>
                                        </div>
                                    @endif
                                    
                                    <!-- Formulário para Nova Contribuição -->
                                    <form action="{{ route('member.ebd.avaliacoes.contribuir', [$avaliacao, $questaoAtual]) }}" method="POST" class="mt-3">
                                        @csrf
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm" 
                                                   name="contribuicao" placeholder="Adicionar contribuição..." required>
                                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Resumo das Questões -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-list text-secondary me-2"></i>
                        Resumo das Questões
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($questoes as $questao)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card border {{ $questao->id === $questaoAtual->id ? 'border-primary' : '' }}">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-0">Questão {{ $questao->ordem }}</h6>
                                        @php
                                            $respostaQuestao = $avaliacao->respostas->where('questao_id', $questao->id)->first();
                                        @endphp
                                        @if($respostaQuestao && $respostaQuestao->resposta_final)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i>
                                        </span>
                                        @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-clock"></i>
                                        </span>
                                        @endif
                                    </div>
                                    <p class="small text-muted mb-2">{{ Str::limit($questao->pergunta, 60) }}</p>
                                    @if($avaliacao->status === 'em_andamento')
                                    <a href="{{ route('member.ebd.avaliacoes.questao', [$avaliacao, $questao->ordem]) }}" 
                                       class="btn btn-sm {{ $questao->id === $questaoAtual->id ? 'btn-primary' : 'btn-outline-primary' }}">
                                        {{ $questao->id === $questaoAtual->id ? 'Atual' : 'Ir para questão' }}
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.contribuicoes-list {
    scrollbar-width: thin;
    scrollbar-color: #6c757d #f8f9fa;
}

.contribuicoes-list::-webkit-scrollbar {
    width: 6px;
}

.contribuicoes-list::-webkit-scrollbar-track {
    background: #f8f9fa;
    border-radius: 3px;
}

.contribuicoes-list::-webkit-scrollbar-thumb {
    background: #6c757d;
    border-radius: 3px;
}

.contribuicoes-list::-webkit-scrollbar-thumb:hover {
    background: #495057;
}

.border-start {
    border-left: 3px solid;
}

.card {
    transition: all 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.badge-sm {
    font-size: 0.7em;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-save para questões dissertativas
    let autoSaveTimeout;
    $('textarea[name="resposta"]').on('input', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(function() {
            $('#questaoForm').submit();
        }, 3000); // Auto-save após 3 segundos de inatividade
    });
    
    // Scroll automático para a última contribuição
    $('.contribuicoes-list').scrollTop($('.contribuicoes-list')[0].scrollHeight);
    
    // Atualizar contribuições a cada 10 segundos
    setInterval(function() {
        if ($('.contribuicoes-list').length > 0) {
            $.get(window.location.href + '/contribuicoes', function(data) {
                $('.contribuicoes-list').html(data);
                $('.contribuicoes-list').scrollTop($('.contribuicoes-list')[0].scrollHeight);
            });
        }
    }, 10000);
    
    // Confirmação para finalizar avaliação
    $('form[action*="finalizar"]').on('submit', function(e) {
        if (!confirm('Tem certeza que deseja finalizar a avaliação? Esta ação não pode ser desfeita.')) {
            e.preventDefault();
        }
    });
    
    // Tooltip para badges
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
@endpush
@endsection