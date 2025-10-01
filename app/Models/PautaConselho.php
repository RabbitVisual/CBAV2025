<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PautaConselho extends Model
{
    use HasFactory;

    protected $fillable = [
        'conselho_id',
        'titulo',
        'descricao',
        'tipo', // informativo, deliberativo, votacao
        'prioridade', // baixa, media, alta, urgente
        'ordem',
        'tempo_estimado',
        'responsavel_id',
        'status', // pendente, em_discussao, aprovado, rejeitado, adiado
        'observacoes',
        'decisao_final',
        'data_decisao'
    ];

    protected $casts = [
        'data_decisao' => 'datetime',
        'tempo_estimado' => 'integer'
    ];

    public function conselho(): BelongsTo
    {
        return $this->belongsTo(Conselho::class);
    }

    public function responsavel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }

    public function getTipoTextAttribute(): string
    {
        return match($this->tipo) {
            'informativo' => 'Informativo',
            'deliberativo' => 'Deliberativo',
            'votacao' => 'Votação',
            default => 'Desconhecido'
        };
    }

    public function getPrioridadeTextAttribute(): string
    {
        return match($this->prioridade) {
            'baixa' => 'Baixa',
            'media' => 'Média',
            'alta' => 'Alta',
            'urgente' => 'Urgente',
            default => 'Desconhecida'
        };
    }

    public function getPrioridadeColorAttribute(): string
    {
        return match($this->prioridade) {
            'baixa' => 'green',
            'media' => 'yellow',
            'alta' => 'orange',
            'urgente' => 'red',
            default => 'gray'
        };
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'pendente' => 'Pendente',
            'em_discussao' => 'Em Discussão',
            'aprovado' => 'Aprovado',
            'rejeitado' => 'Rejeitado',
            'adiado' => 'Adiado',
            default => 'Desconhecido'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pendente' => 'gray',
            'em_discussao' => 'yellow',
            'aprovado' => 'green',
            'rejeitado' => 'red',
            'adiado' => 'blue',
            default => 'gray'
        };
    }

    public function scopePendentes($query)
    {
        return $query->where('status', 'pendente');
    }

    public function scopeEmDiscussao($query)
    {
        return $query->where('status', 'em_discussao');
    }

    public function scopeFinalizadas($query)
    {
        return $query->whereIn('status', ['aprovado', 'rejeitado', 'adiado']);
    }

    public function scopePorPrioridade($query, $prioridade)
    {
        return $query->where('prioridade', $prioridade);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function podeIniciarDiscussao(): bool
    {
        return $this->status === 'pendente';
    }

    public function podeFinalizar(): bool
    {
        return $this->status === 'em_discussao';
    }

    public function podeAdiar(): bool
    {
        return in_array($this->status, ['pendente', 'em_discussao']);
    }

    public function getTempoEstimadoFormatadoAttribute(): string
    {
        if (!$this->tempo_estimado) {
            return 'Não definido';
        }

        $minutos = $this->tempo_estimado;
        $horas = floor($minutos / 60);
        $minutosRestantes = $minutos % 60;

        if ($horas > 0) {
            return $horas . 'h ' . $minutosRestantes . 'min';
        }

        return $minutos . 'min';
    }
} 