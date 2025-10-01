<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParticipanteConselho extends Model
{
    use HasFactory;

    protected $fillable = [
        'conselho_id',
        'user_id',
        'funcao', // presidente, secretario, membro, convidado
        'presente',
        'hora_chegada',
        'hora_saida',
        'observacoes'
    ];

    protected $casts = [
        'presente' => 'boolean',
        'hora_chegada' => 'datetime',
        'hora_saida' => 'datetime'
    ];

    public function conselho(): BelongsTo
    {
        return $this->belongsTo(Conselho::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFuncaoTextAttribute(): string
    {
        return match($this->funcao) {
            'presidente' => 'Presidente',
            'secretario' => 'Secretário',
            'membro' => 'Membro',
            'convidado' => 'Convidado',
            default => 'Desconhecido'
        };
    }

    public function getStatusTextAttribute(): string
    {
        return $this->presente ? 'Presente' : 'Ausente';
    }

    public function getStatusColorAttribute(): string
    {
        return $this->presente ? 'green' : 'red';
    }

    public function getTempoPresencaAttribute(): ?int
    {
        if (!$this->presente || !$this->hora_chegada) {
            return null;
        }

        $horaSaida = $this->hora_saida ?? now();
        return $horaSaida->diffInMinutes($this->hora_chegada);
    }

    public function getTempoPresencaFormatadoAttribute(): string
    {
        $minutos = $this->tempo_presenca;
        
        if ($minutos === null) {
            return 'Não presente';
        }

        $horas = floor($minutos / 60);
        $minutosRestantes = $minutos % 60;

        if ($horas > 0) {
            return $horas . 'h ' . $minutosRestantes . 'min';
        }

        return $minutos . 'min';
    }
} 