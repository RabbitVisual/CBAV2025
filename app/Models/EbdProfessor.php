<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EbdProfessor extends Model
{
    use HasFactory;

    protected $table = 'ebd_professores';

    protected $fillable = [
        'membro_id',
        'turma_id',
        'tipo',
        'data_inicio',
        'data_fim',
        'ativo'
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'ativo' => 'boolean',
    ];

    /**
     * Relacionamento com membro
     */
    public function membro(): BelongsTo
    {
        return $this->belongsTo(Membro::class, 'membro_id');
    }

    /**
     * Relacionamento com turma
     */
    public function turma(): BelongsTo
    {
        return $this->belongsTo(EbdTurma::class, 'turma_id');
    }

    /**
     * Relacionamento com aulas
     */
    public function aulas(): HasMany
    {
        return $this->hasMany(EbdAula::class, 'professor_id');
    }

    /**
     * Verificar se professor está ativo
     */
    public function getEstaAtivoAttribute()
    {
        return $this->ativo && (!$this->data_fim || $this->data_fim->isFuture());
    }

    /**
     * Obter nome do professor
     */
    public function getNomeAttribute()
    {
        return $this->membro ? $this->membro->nome : 'Professor não identificado';
    }

    /**
     * Obter email do professor
     */
    public function getEmailAttribute()
    {
        return $this->membro ? $this->membro->email : null;
    }

    /**
     * Obter telefone do professor
     */
    public function getTelefoneAttribute()
    {
        return $this->membro ? $this->membro->telefone : null;
    }

    /**
     * Calcular total de aulas ministradas
     */
    public function getTotalAulasAttribute()
    {
        return $this->aulas()->where('status', 'realizada')->count();
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
     * Scope para professores ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope para professores principais
     */
    public function scopePrincipais($query)
    {
        return $query->where('tipo', 'principal');
    }

    /**
     * Scope para professores auxiliares
     */
    public function scopeAuxiliares($query)
    {
        return $query->where('tipo', 'auxiliar');
    }

    /**
     * Scope para professores por turma
     */
    public function scopePorTurma($query, $turmaId)
    {
        return $query->where('turma_id', $turmaId);
    }
} 