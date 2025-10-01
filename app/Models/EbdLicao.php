<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EbdLicao extends Model
{
    use HasFactory;

    protected $table = 'ebd_licoes';

    protected $fillable = [
        'disciplina_id', 'titulo', 'descricao', 'objetivos', 'versiculo_chave',
        'conteudo', 'aplicacao_pratica', 'oracao', 'material_necessario',
        'duracao_minutos', 'dificuldade', 'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'duracao_minutos' => 'integer',
    ];

    // --- RELACIONAMENTOS ---

    /**
     * A disciplina a que esta lição pertence.
     */
    public function disciplina(): BelongsTo
    {
        return $this->belongsTo(Disciplina::class, 'disciplina_id');
    }

    /**
     * As aulas associadas a esta lição.
     */
    public function aulas(): HasMany
    {
        return $this->hasMany(EbdAula::class, 'licao_id');
    }

    /**
     * As atividades práticas associadas a esta lição.
     */
    public function atividades(): HasMany
    {
        return $this->hasMany(Atividade::class, 'licao_id');
    }

    // --- SCOPES ---

    public function scopeAtivas($query)
    {
        return $query->where('ativo', true);
    }

    public function scopePorDificuldade($query, string $dificuldade)
    {
        return $query->where('dificuldade', $dificuldade);
    }

    // --- ACCESSORS ---

    public function getDuracaoFormatadaAttribute(): string
    {
        if (!$this->duracao_minutos) return 'N/A';

        $horas = floor($this->duracao_minutos / 60);
        $minutos = $this->duracao_minutos % 60;
        
        if ($horas > 0) {
            return "{$horas}h {$minutos}min";
        }
        return "{$minutos}min";
    }

    public function getDificuldadeFormatadaAttribute(): string
    {
        return match($this->dificuldade) {
            'facil' => 'Fácil',
            'medio' => 'Médio',
            'dificil' => 'Difícil',
            default => 'Não definida'
        };
    }

    public function getCorDificuldadeAttribute(): string
    {
        return match($this->dificuldade) {
            'facil' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            'medio' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'dificil' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
        };
    }
}