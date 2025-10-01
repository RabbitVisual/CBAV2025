<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VotoConselho extends Model
{
    use HasFactory;

    protected $fillable = [
        'votacao_id',
        'user_id',
        'voto', // favoravel, contrario, abstenção, opcao_1, opcao_2, etc.
        'justificativa',
        'data_voto',
        'voto_anonimo'
    ];

    protected $casts = [
        'data_voto' => 'datetime',
        'voto_anonimo' => 'boolean'
    ];

    public function votacao(): BelongsTo
    {
        return $this->belongsTo(VotacaoConselho::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getVotoTextAttribute(): string
    {
        return match($this->voto) {
            'favoravel' => 'Favorável',
            'contrario' => 'Contrário',
            'abstencao' => 'Abstenção',
            default => $this->voto
        };
    }

    public function getVotoColorAttribute(): string
    {
        return match($this->voto) {
            'favoravel' => 'green',
            'contrario' => 'red',
            'abstencao' => 'yellow',
            default => 'gray'
        };
    }

    public function getVotoIconAttribute(): string
    {
        return match($this->voto) {
            'favoravel' => 'fas fa-thumbs-up',
            'contrario' => 'fas fa-thumbs-down',
            'abstencao' => 'fas fa-minus',
            default => 'fas fa-question'
        };
    }

    public function scopeFavoraveis($query)
    {
        return $query->where('voto', 'favoravel');
    }

    public function scopeContrarios($query)
    {
        return $query->where('voto', 'contrario');
    }

    public function scopeAbstencoes($query)
    {
        return $query->where('voto', 'abstencao');
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('voto', $tipo);
    }

    public function getNomeExibicaoAttribute(): string
    {
        if ($this->voto_anonimo) {
            return 'Voto Anônimo';
        }

        return $this->user->name ?? 'Usuário Desconhecido';
    }
} 