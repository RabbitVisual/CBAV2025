<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EbdNota extends Model
{
    use HasFactory;

    protected $table = 'ebd_notas';

    protected $fillable = [
        'avaliacao_id',
        'aluno_id',
        'nota',
        'pontuacao_maxima',
        'percentual',
        'observacoes'
    ];

    protected $casts = [
        'percentual' => 'decimal:2',
    ];

    /**
     * Relacionamento com avaliação
     */
    public function avaliacao(): BelongsTo
    {
        return $this->belongsTo(EbdAvaliacao::class, 'avaliacao_id');
    }

    /**
     * Relacionamento com aluno
     */
    public function aluno(): BelongsTo
    {
        return $this->belongsTo(EbdAluno::class, 'aluno_id');
    }

    /**
     * Obter conceito baseado no percentual
     */
    public function getConceitoAttribute()
    {
        return match(true) {
            $this->percentual >= 90 => 'A',
            $this->percentual >= 80 => 'B',
            $this->percentual >= 70 => 'C',
            $this->percentual >= 60 => 'D',
            default => 'F'
        };
    }

    /**
     * Obter cor do conceito
     */
    public function getCorConceitoAttribute()
    {
        return match($this->conceito) {
            'A' => 'bg-green-100 text-green-800',
            'B' => 'bg-blue-100 text-blue-800',
            'C' => 'bg-yellow-100 text-yellow-800',
            'D' => 'bg-red-100 text-red-800',
            'F' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Verificar se foi aprovado
     */
    public function getAprovadoAttribute()
    {
        return $this->percentual >= 60;
    }

    /**
     * Scope para notas aprovadas
     */
    public function scopeAprovadas($query)
    {
        return $query->where('percentual', '>=', 60);
    }

    /**
     * Scope para notas reprovadas
     */
    public function scopeReprovadas($query)
    {
        return $query->where('percentual', '<', 60);
    }

    /**
     * Scope para notas por avaliação
     */
    public function scopePorAvaliacao($query, $avaliacaoId)
    {
        return $query->where('avaliacao_id', $avaliacaoId);
    }

    /**
     * Scope para notas por aluno
     */
    public function scopePorAluno($query, $alunoId)
    {
        return $query->where('aluno_id', $alunoId);
    }
} 