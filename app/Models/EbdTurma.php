<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EbdTurma extends Model
{
    use HasFactory;

    protected $table = 'ebd_turmas';

    protected $fillable = [
        'nome',
        'descricao',
        'faixa_etaria',
        'cor',
        'capacidade_maxima',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    /**
     * Relacionamento com professores
     */
    public function professores(): HasMany
    {
        return $this->hasMany(EbdProfessor::class, 'turma_id');
    }

    /**
     * Relacionamento com alunos
     */
    public function alunos(): HasMany
    {
        return $this->hasMany(EbdAluno::class, 'turma_id');
    }

    /**
     * Relacionamento com aulas
     */
    public function aulas(): HasMany
    {
        return $this->hasMany(EbdAula::class, 'turma_id');
    }

    /**
     * Relacionamento com relatórios
     */
    public function relatorios(): HasMany
    {
        return $this->hasMany(EbdRelatorio::class, 'turma_id');
    }

    /**
     * Obter professores ativos
     */
    public function professoresAtivos()
    {
        return $this->professores()->where('ativo', true);
    }

    /**
     * Obter alunos ativos
     */
    public function alunosAtivos()
    {
        return $this->alunos()->where('status', 'ativo');
    }

    /**
     * Obter aulas agendadas
     */
    public function aulasAgendadas()
    {
        return $this->aulas()->where('status', 'agendada');
    }

    /**
     * Obter aulas realizadas
     */
    public function aulasRealizadas()
    {
        return $this->aulas()->where('status', 'realizada');
    }

    /**
     * Calcular total de alunos
     */
    public function getTotalAlunosAttribute()
    {
        return $this->alunosAtivos()->count();
    }

    /**
     * Calcular total de professores
     */
    public function getTotalProfessoresAttribute()
    {
        return $this->professoresAtivos()->count();
    }

    /**
     * Verificar se turma está cheia
     */
    public function getEstaCheiaAttribute()
    {
        if (!$this->capacidade_maxima) {
            return false;
        }
        return $this->total_alunos >= $this->capacidade_maxima;
    }

    /**
     * Obter percentual de ocupação
     */
    public function getPercentualOcupacaoAttribute()
    {
        if (!$this->capacidade_maxima) {
            return 0;
        }
        return round(($this->total_alunos / $this->capacidade_maxima) * 100, 2);
    }

    /**
     * Scope para turmas ativas
     */
    public function scopeAtivas($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope para buscar por faixa etária
     */
    public function scopePorFaixaEtaria($query, $faixa)
    {
        return $query->where('faixa_etaria', $faixa);
    }
} 