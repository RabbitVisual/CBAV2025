<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EbdRespostaAluno extends Model
{
    use HasFactory;

    protected $table = 'ebd_respostas_alunos';

    protected $fillable = [
        'avaliacao_id',
        'aluno_id',
        'questao_id',
        'resposta',
        'correta',
        'pontuacao_obtida',
        'comentario_professor'
    ];

    protected $casts = [
        'correta' => 'boolean',
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
     * Relacionamento com questão
     */
    public function questao(): BelongsTo
    {
        return $this->belongsTo(EbdQuestao::class, 'questao_id');
    }

    /**
     * Obter status da resposta
     */
    public function getStatusAttribute()
    {
        return $this->correta ? 'Correta' : 'Incorreta';
    }

    /**
     * Obter cor do status
     */
    public function getCorStatusAttribute()
    {
        return match($this->status) {
            'correta' => 'bg-green-100 text-green-800',
            'incorreta' => 'bg-red-100 text-red-800',
            'parcial' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Scope para respostas corretas
     */
    public function scopeCorretas($query)
    {
        return $query->where('correta', true);
    }

    /**
     * Scope para respostas incorretas
     */
    public function scopeIncorretas($query)
    {
        return $query->where('correta', false);
    }

    /**
     * Scope para respostas por avaliação
     */
    public function scopePorAvaliacao($query, $avaliacaoId)
    {
        return $query->where('avaliacao_id', $avaliacaoId);
    }

    /**
     * Scope para respostas por aluno
     */
    public function scopePorAluno($query, $alunoId)
    {
        return $query->where('aluno_id', $alunoId);
    }

    /**
     * Scope para respostas por questão
     */
    public function scopePorQuestao($query, $questaoId)
    {
        return $query->where('questao_id', $questaoId);
    }
} 