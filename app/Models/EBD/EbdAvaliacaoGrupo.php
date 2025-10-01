<?php

namespace App\Models\EBD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'percentual'
    ];

    protected $casts = [
        'iniciada_em' => 'datetime',
        'concluida_em' => 'datetime',
        'pontuacao_total' => 'decimal:2',
        'percentual' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relacionamento com a avaliação
     */
    public function avaliacao()
    {
        return $this->belongsTo(EbdAvaliacao::class, 'avaliacao_id');
    }

    /**
     * Relacionamento com o grupo
     */
    public function grupo()
    {
        return $this->belongsTo(EbdGrupoEstudo::class, 'grupo_id');
    }

    /**
     * Relacionamento com as respostas do grupo
     */
    public function respostas()
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
     * Scope para avaliações de um grupo específico
     */
    public function scopeDoGrupo($query, $grupoId)
    {
        return $query->where('grupo_id', $grupoId);
    }

    /**
     * Verifica se a avaliação está pendente
     */
    public function estaPendente()
    {
        return $this->status === 'pendente';
    }

    /**
     * Verifica se a avaliação está em andamento
     */
    public function estaEmAndamento()
    {
        return $this->status === 'em_andamento';
    }

    /**
     * Verifica se a avaliação foi concluída
     */
    public function estaConcluida()
    {
        return $this->status === 'concluida';
    }

    /**
     * Inicia a avaliação
     */
    public function iniciar()
    {
        if (!$this->estaPendente()) {
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
    public function concluir()
    {
        if ($this->estaConcluida()) {
            return false;
        }

        // Calcular pontuação total e percentual
        $this->calcularPontuacao();

        $this->update([
            'status' => 'concluida',
            'concluida_em' => now()
        ]);

        return true;
    }

    /**
     * Calcula a pontuação total e percentual da avaliação
     */
    public function calcularPontuacao()
    {
        $respostas = $this->respostas()->with('questao')->get();
        $pontuacaoTotal = 0;
        $pontuacaoMaxima = 0;

        foreach ($respostas as $resposta) {
            $pontuacaoMaxima += $resposta->questao->pontos;
            $pontuacaoTotal += $resposta->pontos_obtidos;
        }

        $percentual = $pontuacaoMaxima > 0 ? ($pontuacaoTotal / $pontuacaoMaxima) * 100 : 0;

        $this->update([
            'pontuacao_total' => $pontuacaoTotal,
            'percentual' => $percentual
        ]);

        return [
            'pontuacao_total' => $pontuacaoTotal,
            'pontuacao_maxima' => $pontuacaoMaxima,
            'percentual' => $percentual
        ];
    }

    /**
     * Calcula o tempo gasto na avaliação
     */
    public function tempoGasto()
    {
        if (!$this->iniciada_em) {
            return 0;
        }

        $dataFim = $this->concluida_em ?: now();
        return $this->iniciada_em->diffInMinutes($dataFim);
    }

    /**
     * Verifica se a avaliação está dentro do tempo limite
     */
    public function dentroDoTempoLimite()
    {
        if (!$this->avaliacao->tempo_limite_minutos) {
            return true;
        }

        return $this->tempoGasto() <= $this->avaliacao->tempo_limite_minutos;
    }

    /**
     * Retorna o progresso da avaliação (0-100)
     */
    public function progresso()
    {
        $totalQuestoes = $this->avaliacao->questoes()->count();
        $questoesRespondidas = $this->respostas()->count();

        if ($totalQuestoes === 0) {
            return 100;
        }

        return ($questoesRespondidas / $totalQuestoes) * 100;
    }

    /**
     * Verifica se todas as questões foram respondidas
     */
    public function todasQuestoesRespondidas()
    {
        return $this->progresso() >= 100;
    }

    /**
     * Retorna estatísticas da avaliação
     */
    public function estatisticas()
    {
        $respostas = $this->respostas()->with('questao')->get();
        $totalQuestoes = $this->avaliacao->questoes()->count();
        $questoesRespondidas = $respostas->count();
        $questoesCorretas = $respostas->where('esta_correta', true)->count();

        return [
            'total_questoes' => $totalQuestoes,
            'questoes_respondidas' => $questoesRespondidas,
            'questoes_corretas' => $questoesCorretas,
            'questoes_incorretas' => $questoesRespondidas - $questoesCorretas,
            'progresso' => $this->progresso(),
            'tempo_gasto' => $this->tempoGasto(),
            'dentro_tempo_limite' => $this->dentroDoTempoLimite(),
            'pontuacao_total' => $this->pontuacao_total,
            'percentual' => $this->percentual
        ];
    }

    /**
     * Accessor para status formatado
     */
    public function getStatusFormatadoAttribute()
    {
        $status = [
            'pendente' => 'Pendente',
            'em_andamento' => 'Em Andamento',
            'concluida' => 'Concluída',
            'cancelada' => 'Cancelada'
        ];

        return $status[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Accessor para tempo gasto formatado
     */
    public function getTempoGastoFormatadoAttribute()
    {
        $minutos = $this->tempoGasto();
        
        if ($minutos < 60) {
            return $minutos . ' min';
        } else {
            $horas = floor($minutos / 60);
            $minutosRestantes = $minutos % 60;
            return $horas . 'h ' . $minutosRestantes . 'min';
        }
    }

    /**
     * Accessor para data de início formatada
     */
    public function getIniciadaEmFormatadaAttribute()
    {
        return $this->iniciada_em ? $this->iniciada_em->format('d/m/Y H:i') : null;
    }

    /**
     * Accessor para data de conclusão formatada
     */
    public function getConcluidaEmFormatadaAttribute()
    {
        return $this->concluida_em ? $this->concluida_em->format('d/m/Y H:i') : null;
    }

    /**
     * Accessor para percentual formatado
     */
    public function getPercentualFormatadoAttribute()
    {
        return number_format($this->percentual, 1) . '%';
    }
}