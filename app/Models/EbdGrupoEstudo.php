<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EbdGrupoEstudo extends Model
{
    use HasFactory;

    protected $table = 'ebd_grupos_estudo';

    protected $fillable = [
        'turma_id',
        'nome',
        'descricao',
        'cor',
        'capacidade_maxima',
        'lider_id',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'capacidade_maxima' => 'integer'
    ];

    /**
     * Relacionamento com a turma
     */
    public function turma(): BelongsTo
    {
        return $this->belongsTo(EbdTurma::class, 'turma_id');
    }

    /**
     * Relacionamento com o líder do grupo
     */
    public function lider(): BelongsTo
    {
        return $this->belongsTo(EbdAluno::class, 'lider_id');
    }

    /**
     * Relacionamento com os membros do grupo
     */
    public function membros(): BelongsToMany
    {
        return $this->belongsToMany(EbdAluno::class, 'ebd_grupo_membros', 'grupo_id', 'aluno_id')
                    ->withPivot(['data_entrada', 'data_saida', 'status'])
                    ->withTimestamps()
                    ->wherePivot('status', 'ativo');
    }

    /**
     * Relacionamento com todos os membros (incluindo inativos)
     */
    public function todosMembros(): BelongsToMany
    {
        return $this->belongsToMany(EbdAluno::class, 'ebd_grupo_membros', 'grupo_id', 'aluno_id')
                    ->withPivot(['data_entrada', 'data_saida', 'status'])
                    ->withTimestamps();
    }

    /**
     * Relacionamento com as avaliações do grupo
     */
    public function avaliacoes(): HasMany
    {
        return $this->hasMany(EbdAvaliacaoGrupo::class, 'grupo_id');
    }

    /**
     * Scope para grupos ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope para grupos de uma turma específica
     */
    public function scopeDaTurma($query, $turmaId)
    {
        return $query->where('turma_id', $turmaId);
    }

    /**
     * Verifica se o grupo está cheio
     */
    public function estaCheio(): bool
    {
        return $this->membros()->count() >= $this->capacidade_maxima;
    }

    /**
     * Conta membros ativos
     */
    public function contarMembrosAtivos(): int
    {
        return $this->membros()->count();
    }

    /**
     * Verifica se um aluno é membro do grupo
     */
    public function temMembro(int $alunoId): bool
    {
        return $this->membros()->where('ebd_alunos.id', $alunoId)->exists();
    }

    /**
     * Adiciona um membro ao grupo
     */
    public function adicionarMembro(int $alunoId, array $dados = []): bool
    {
        if ($this->estaCheio() || $this->temMembro($alunoId)) {
            return false;
        }

        $dadosDefault = [
            'data_entrada' => now()->toDateString(),
            'status' => 'ativo'
        ];

        $this->membros()->attach($alunoId, array_merge($dadosDefault, $dados));
        return true;
    }

    /**
     * Remove um membro do grupo
     */
    public function removerMembro(int $alunoId): bool
    {
        if (!$this->temMembro($alunoId)) {
            return false;
        }

        $this->membros()->updateExistingPivot($alunoId, [
            'data_saida' => now()->toDateString(),
            'status' => 'inativo'
        ]);

        return true;
    }

    /**
     * Define um novo líder para o grupo
     */
    public function definirLider(int $alunoId): bool
    {
        if (!$this->temMembro($alunoId)) {
            return false;
        }

        $this->update(['lider_id' => $alunoId]);
        return true;
    }

    /**
     * Calcula a média de desempenho do grupo
     */
    public function mediaDesempenho(): float
    {
        $avaliacoesConcluidas = $this->avaliacoes()->where('status', 'concluida')->get();
        
        if ($avaliacoesConcluidas->isEmpty()) {
            return 0;
        }

        return $avaliacoesConcluidas->avg('percentual');
    }

    /**
     * Retorna estatísticas do grupo
     */
    public function estatisticas(): array
    {
        return [
            'total_membros' => $this->contarMembrosAtivos(),
            'capacidade_maxima' => $this->capacidade_maxima,
            'vagas_disponiveis' => $this->capacidade_maxima - $this->contarMembrosAtivos(),
            'avaliacoes_concluidas' => $this->avaliacoes()->where('status', 'concluida')->count(),
            'avaliacoes_pendentes' => $this->avaliacoes()->where('status', 'pendente')->count(),
            'media_desempenho' => $this->mediaDesempenho(),
            'tem_lider' => !is_null($this->lider_id)
        ];
    }
}