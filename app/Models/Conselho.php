<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Conselho extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'titulo', 'descricao', 'data_reuniao', 'hora_inicio', 'hora_fim', 'local',
        'status', 'tipo', 'quorum_minimo', 'criado_por', 'presidente_id',
        'secretario_id', 'template_id', 'observacoes', 'ata_finalizada', 'data_ata_finalizada'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data_reuniao' => 'date',
        'hora_inicio' => 'datetime',
        'hora_fim' => 'datetime',
        'data_ata_finalizada' => 'datetime',
        'ata_finalizada' => 'boolean',
        'quorum_minimo' => 'integer'
    ];

    // --- RELACIONAMENTOS ---

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

    // --- SCOPES ---

    public function scopeAtivas($query)
    {
        return $query->whereIn('status', ['agendada', 'em_andamento']);
    }

    public function scopeFinalizadas($query)
    {
        return $query->where('status', 'finalizada');
    }

    public function scopeProximas($query)
    {
        return $query->where('data_reuniao', '>=', now())->orderBy('data_reuniao');
    }

    // --- ACCESSORS ---

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'agendada' => 'Agendada', 'em_andamento' => 'Em Andamento',
            'finalizada' => 'Finalizada', 'cancelada' => 'Cancelada',
            default => 'Desconhecido'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'agendada' => 'blue', 'em_andamento' => 'yellow',
            'finalizada' => 'green', 'cancelada' => 'red',
            default => 'gray'
        };
    }

    public function getFormattedDataReuniaoAttribute(): string
    {
        return $this->data_reuniao ? Carbon::parse($this->data_reuniao)->format('d/m/Y') : 'N/A';
    }

    public function getFormattedHoraInicioAttribute(): string
    {
        return $this->hora_inicio ? Carbon::parse($this->hora_inicio)->format('H:i') : 'N/A';
    }

    public function getQuorumAtualAttribute(): int
    {
        return $this->participantes()->where('presente', true)->count();
    }

    public function getQuorumAtingidoAttribute(): bool
    {
        return $this->quorum_atual >= $this->quorum_minimo;
    }

    public function getIsFinishedAttribute(): bool
    {
        return $this->status === 'finalizada' || $this->status === 'cancelada';
    }

    // --- LÓGICA DE NEGÓCIO ---

    public function podeIniciar(): bool
    {
        return $this->status === 'agendada' && $this->data_reuniao->isToday() || $this->data_reuniao->isPast();
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