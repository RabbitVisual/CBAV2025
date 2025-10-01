<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VotacaoConselho extends Model
{
    use HasFactory;

    protected $fillable = [
        'conselho_id',
        'pauta_id',
        'titulo',
        'descricao',
        'tipo_votacao', // aprovacao_rejeicao, multipla_escolha, escala
        'opcoes_votacao', // JSON com opções
        'votos_favoraveis',
        'votos_contrarios',
        'votos_abstencao',
        'total_votos',
        'quorum_necessario',
        'status', // pendente, em_andamento, finalizada, cancelada
        'resultado', // aprovado, rejeitado, empate, sem_quorum
        'observacoes',
        'data_inicio',
        'data_fim',
        'tempo_limite',
        'voto_secreto'
    ];

    protected $casts = [
        'opcoes_votacao' => 'array',
        'votos_favoraveis' => 'integer',
        'votos_contrarios' => 'integer',
        'votos_abstencao' => 'integer',
        'total_votos' => 'integer',
        'quorum_necessario' => 'integer',
        'tempo_limite' => 'integer',
        'voto_secreto' => 'boolean',
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime'
    ];

    public function conselho(): BelongsTo
    {
        return $this->belongsTo(Conselho::class);
    }

    public function pauta(): BelongsTo
    {
        return $this->belongsTo(PautaConselho::class);
    }

    public function votos(): HasMany
    {
        return $this->hasMany(VotoConselho::class);
    }

    public function getTipoVotacaoTextAttribute(): string
    {
        return match($this->tipo_votacao) {
            'aprovacao_rejeicao' => 'Aprovação/Rejeição',
            'multipla_escolha' => 'Múltipla Escolha',
            'escala' => 'Escala',
            default => 'Desconhecido'
        };
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'pendente' => 'Pendente',
            'em_andamento' => 'Em Andamento',
            'finalizada' => 'Finalizada',
            'cancelada' => 'Cancelada',
            default => 'Desconhecido'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pendente' => 'gray',
            'em_andamento' => 'yellow',
            'finalizada' => 'green',
            'cancelada' => 'red',
            default => 'gray'
        };
    }

    public function getResultadoTextAttribute(): string
    {
        return match($this->resultado) {
            'aprovado' => 'Aprovado',
            'rejeitado' => 'Rejeitado',
            'empate' => 'Empate',
            'sem_quorum' => 'Sem Quórum',
            default => 'Pendente'
        };
    }

    public function getResultadoColorAttribute(): string
    {
        return match($this->resultado) {
            'aprovado' => 'green',
            'rejeitado' => 'red',
            'empate' => 'yellow',
            'sem_quorum' => 'orange',
            default => 'gray'
        };
    }

    public function scopePendentes($query)
    {
        return $query->where('status', 'pendente');
    }

    public function scopeEmAndamento($query)
    {
        return $query->where('status', 'em_andamento');
    }

    public function scopeFinalizadas($query)
    {
        return $query->where('status', 'finalizada');
    }

    public function scopePorResultado($query, $resultado)
    {
        return $query->where('resultado', $resultado);
    }

    public function getPercentualFavoraveisAttribute(): float
    {
        if ($this->total_votos === 0) {
            return 0;
        }
        return round(($this->votos_favoraveis / $this->total_votos) * 100, 2);
    }

    public function getPercentualContrariosAttribute(): float
    {
        if ($this->total_votos === 0) {
            return 0;
        }
        return round(($this->votos_contrarios / $this->total_votos) * 100, 2);
    }

    public function getPercentualAbstencaoAttribute(): float
    {
        if ($this->total_votos === 0) {
            return 0;
        }
        return round(($this->votos_abstencao / $this->total_votos) * 100, 2);
    }

    public function getQuorumAtingidoAttribute(): bool
    {
        return $this->total_votos >= $this->quorum_necessario;
    }

    public function getMaioriaAtingidaAttribute(): bool
    {
        if ($this->total_votos === 0) {
            return false;
        }
        
        $percentualFavoraveis = $this->percentual_favoraveis;
        return $percentualFavoraveis > 50;
    }

    public function podeIniciar(): bool
    {
        return $this->status === 'pendente';
    }

    public function podeFinalizar(): bool
    {
        return $this->status === 'em_andamento';
    }

    public function podeCancelar(): bool
    {
        return in_array($this->status, ['pendente', 'em_andamento']);
    }

    public function getTempoLimiteFormatadoAttribute(): string
    {
        if (!$this->tempo_limite) {
            return 'Sem limite';
        }

        $minutos = $this->tempo_limite;
        $horas = floor($minutos / 60);
        $minutosRestantes = $minutos % 60;

        if ($horas > 0) {
            return $horas . 'h ' . $minutosRestantes . 'min';
        }

        return $minutos . 'min';
    }

    public function calcularResultado(): void
    {
        if ($this->total_votos === 0) {
            $this->resultado = 'sem_quorum';
            return;
        }

        if (!$this->quorum_atingido) {
            $this->resultado = 'sem_quorum';
            return;
        }

        if ($this->votos_favoraveis > $this->votos_contrarios) {
            $this->resultado = 'aprovado';
        } elseif ($this->votos_contrarios > $this->votos_favoraveis) {
            $this->resultado = 'rejeitado';
        } else {
            $this->resultado = 'empate';
        }
    }
} 