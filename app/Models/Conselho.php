<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conselho extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descricao',
        'data_reuniao',
        'hora_inicio',
        'hora_fim',
        'local',
        'status', // agendada, em_andamento, finalizada, cancelada
        'tipo', // reuniao_ordinaria, reuniao_extraordinaria, votacao
        'quorum_minimo',
        'criado_por',
        'presidente_id',
        'secretario_id',
        'template_id',
        'observacoes',
        'ata_finalizada',
        'data_ata_finalizada'
    ];

    protected $casts = [
        'data_reuniao' => 'date',
        'hora_inicio' => 'datetime',
        'hora_fim' => 'datetime',
        'data_ata_finalizada' => 'datetime',
        'ata_finalizada' => 'boolean',
        'quorum_minimo' => 'integer'
    ];

    public function criadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'criado_por');
    }

    public function presidente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'presidente_id');
    }

    public function secretario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'secretario_id');
    }

    public function pautas(): HasMany
    {
        return $this->hasMany(PautaConselho::class);
    }

    public function votacoes(): HasMany
    {
        return $this->hasMany(VotacaoConselho::class);
    }

    public function participantes(): HasMany
    {
        return $this->hasMany(ParticipanteConselho::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(TemplatePauta::class, 'template_id');
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'agendada' => 'Agendada',
            'em_andamento' => 'Em Andamento',
            'finalizada' => 'Finalizada',
            'cancelada' => 'Cancelada',
            default => 'Desconhecido'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'agendada' => 'blue',
            'em_andamento' => 'yellow',
            'finalizada' => 'green',
            'cancelada' => 'red',
            default => 'gray'
        };
    }

    public function getTipoTextAttribute(): string
    {
        return match($this->tipo) {
            'reuniao_ordinaria' => 'Reunião Ordinária',
            'reuniao_extraordinaria' => 'Reunião Extraordinária',
            'votacao' => 'Votação',
            default => 'Desconhecido'
        };
    }

    public function scopeAtivas($query)
    {
        return $query->whereIn('status', ['agendada', 'em_andamento']);
    }

    public function scopeFinalizadas($query)
    {
        return $query->where('status', 'finalizada');
    }

    public function scopePorData($query, $data)
    {
        return $query->whereDate('data_reuniao', $data);
    }

    public function scopeProximas($query)
    {
        return $query->where('data_reuniao', '>=', now())->orderBy('data_reuniao');
    }

    public function getQuorumAtualAttribute(): int
    {
        return $this->participantes()->where('presente', true)->count();
    }

    public function getQuorumAtingidoAttribute(): bool
    {
        return $this->quorum_atual >= $this->quorum_minimo;
    }

    public function getProximoStatusAttribute(): string
    {
        return match($this->status) {
            'agendada' => 'em_andamento',
            'em_andamento' => 'finalizada',
            default => $this->status
        };
    }

    public function podeIniciar(): bool
    {
        return $this->status === 'agendada' && $this->data_reuniao <= now();
    }

    public function podeFinalizar(): bool
    {
        return $this->status === 'em_andamento';
    }

    public function podeCancelar(): bool
    {
        return in_array($this->status, ['agendada', 'em_andamento']);
    }
} 