<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class EbdAvaliacaoGrupo extends Model
{
    use HasFactory;

    protected $table = 'ebd_avaliacoes_grupo';

    protected $fillable = [
        'avaliacao_id',
        'grupo_id',
        'status',
        'iniciada_em',
        'concluida_em',
        'pontuacao_total',
        'percentual',
        'observacoes'
    ];

    protected $casts = [
        'iniciada_em' => 'datetime',
        'concluida_em' => 'datetime',
        'pontuacao_total' => 'integer',
        'percentual' => 'decimal:2'
    ];

    /**
     * Relacionamento com a avaliação
     */
    public function avaliacao(): BelongsTo
    {
        return $this->belongsTo(EbdAvaliacao::class, 'avaliacao_id');
    }

    /**
     * Relacionamento com o grupo
     */
    public function grupo(): BelongsTo
    {
        return $this->belongsTo(EbdGrupoEstudo::class, 'grupo_id');
    }

    /**
     * Relacionamento com as respostas do grupo
     */
    public function respostas(): HasMany
    {
        return $this->hasMany(EbdRespostaGrupo::class, 'avaliacao_grupo_id');
    }

    /**
     * Scope para avaliações pendentes
     */
    public function scopePendentes($query)
    {
        return $query->where('status', 'pendente');
    }

    /**
     * Scope para avaliações em andamento
     */
    public function scopeEmAndamento($query)
    {
        return $query->where('status', 'em_andamento');
    }

    /**
     * Scope para avaliações concluídas
     */
    public function scopeConcluidas($query)
    {
        return $query->where('status', 'concluida');
    }

    /**
     * Inicia a avaliação
     */
    public function iniciar(): bool
    {
        if ($this->status !== 'pendente') {
            return false;
        }

        $this->update([
            'status' => 'em_andamento',
            'iniciada_em' => now()
        ]);

        return true;
    }

    /**
     * Conclui a avaliação
     */
    public function concluir(): bool
    {
        if ($this->status !== 'em_andamento') {
            return false;
        }

        $this->calcularPontuacao();
        
        $this->update([
            'status' => 'concluida',
            'concluida_em' => now()
        ]);

        return true;
    }

    /**
     * Cancela a avaliação
     */
    public function cancelar(string $motivo = null): bool
    {
        if (in_array($this->status, ['concluida', 'cancelada'])) {
            return false;
        }

        $observacoes = $this->observacoes;
        if ($motivo) {
            $observacoes .= "\nCancelada: " . $motivo;
        }

        $this->update([
            'status' => 'cancelada',
            'observacoes' => $observacoes
        ]);

        return true;
    }

    /**
     * Calcula a pontuação total da avaliação
     */
    public function calcularPontuacao(): void
    {
        $pontuacaoObtida = $this->respostas()->sum('pontuacao_obtida');
        $pontuacaoMaxima = $this->avaliacao->pontuacao_maxima;
        
        $percentual = $pontuacaoMaxima > 0 ? ($pontuacaoObtida / $pontuacaoMaxima) * 100 : 0;
        
        $this->update([
            'pontuacao_total' => $pontuacaoObtida,
            'percentual' => round($percentual, 2)
        ]);
    }

    /**
     * Verifica se a avaliação está dentro do tempo limite
     */
    public function dentroDoTempoLimite(): bool
    {
        if (!$this->avaliacao->tempo_limite_minutos || !$this->iniciada_em) {
            return true;
        }

        $tempoLimite = $this->iniciada_em->addMinutes($this->avaliacao->tempo_limite_minutos);
        return now()->lte($tempoLimite);
    }

    /**
     * Calcula o tempo restante em minutos
     */
    public function tempoRestanteMinutos(): ?int
    {
        if (!$this->avaliacao->tempo_limite_minutos || !$this->iniciada_em) {
            return null;
        }

        $tempoLimite = $this->iniciada_em->addMinutes($this->avaliacao->tempo_limite_minutos);
        $agora = now();
        
        if ($agora->gte($tempoLimite)) {
            return 0;
        }

        return $agora->diffInMinutes($tempoLimite);
    }

    /**
     * Calcula o tempo gasto na avaliação
     */
    public function tempoGastoMinutos(): ?int
    {
        if (!$this->iniciada_em) {
            return null;
        }

        $fimTempo = $this->concluida_em ?? now();
        return $this->iniciada_em->diffInMinutes($fimTempo);
    }

    /**
     * Verifica se todas as questões foram respondidas
     */
    public function todasQuestoesRespondidas(): bool
    {
        $totalQuestoes = $this->avaliacao->questoes()->count();
        $questoesRespondidas = $this->respostas()->count();
        
        return $totalQuestoes === $questoesRespondidas;
    }

    /**
     * Retorna o progresso da avaliação em percentual
     */
    public function progressoPercentual(): float
    {
        $totalQuestoes = $this->avaliacao->questoes()->count();
        
        if ($totalQuestoes === 0) {
            return 100;
        }
        
        $questoesRespondidas = $this->respostas()->count();
        return round(($questoesRespondidas / $totalQuestoes) * 100, 2);
    }

    /**
     * Retorna estatísticas da avaliação
     */
    public function estatisticas(): array
    {
        return [
            'status' => $this->status,
            'pontuacao_obtida' => $this->pontuacao_total,
            'pontuacao_maxima' => $this->avaliacao->pontuacao_maxima,
            'percentual' => $this->percentual,
            'progresso' => $this->progressoPercentual(),
            'tempo_gasto_minutos' => $this->tempoGastoMinutos(),
            'tempo_restante_minutos' => $this->tempoRestanteMinutos(),
            'dentro_tempo_limite' => $this->dentroDoTempoLimite(),
            'todas_questoes_respondidas' => $this->todasQuestoesRespondidas(),
            'total_questoes' => $this->avaliacao->questoes()->count(),
            'questoes_respondidas' => $this->respostas()->count(),
            'questoes_corretas' => $this->respostas()->where('correta', true)->count()
        ];
    }

    /**
     * Força a conclusão da avaliação por tempo esgotado
     */
    public function concluirPorTempoEsgotado(): bool
    {
        if ($this->status !== 'em_andamento') {
            return false;
        }

        $this->calcularPontuacao();
        
        $observacoes = $this->observacoes . "\nConcluída automaticamente por tempo esgotado.";
        
        $this->update([
            'status' => 'concluida',
            'concluida_em' => now(),
            'observacoes' => $observacoes
        ]);

        return true;
    }
}