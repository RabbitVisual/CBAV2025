<?php

namespace App\Models\EBD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\EbdTurma;
use App\Models\EbdAluno;

class EbdGrupoEstudo extends Model
{
    use HasFactory, SoftDeletes;

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
        'capacidade_maxima' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * Relacionamento com a turma
     */
    public function turma()
    {
        return $this->belongsTo(EbdTurma::class, 'turma_id');
    }

    /**
     * Relacionamento com o líder do grupo
     */
    public function lider()
    {
        return $this->belongsTo(EbdAluno::class, 'lider_id');
    }

    /**
     * Relacionamento com todos os membros (incluindo inativos)
     */
    public function membros()
    {
        return $this->hasMany(EbdGrupoMembro::class, 'grupo_id');
    }

    /**
     * Relacionamento com membros ativos apenas
     */
    public function membrosAtivos()
    {
        return $this->hasMany(EbdGrupoMembro::class, 'grupo_id')
            ->where('status', 'ativo')
            ->whereNull('data_saida');
    }

    /**
     * Relacionamento com avaliações do grupo
     */
    public function avaliacoes()
    {
        return $this->hasMany(EbdAvaliacaoGrupo::class, 'grupo_id');
    }

    /**
     * Relacionamento com avaliações concluídas
     */
    public function avaliacoesConcluidas()
    {
        return $this->hasMany(EbdAvaliacaoGrupo::class, 'grupo_id')
            ->where('status', 'concluida');
    }

    /**
     * Relacionamento com avaliações pendentes
     */
    public function avaliacoesPendentes()
    {
        return $this->hasMany(EbdAvaliacaoGrupo::class, 'grupo_id')
            ->where('status', 'pendente');
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
     * Verifica se o grupo está lotado
     */
    public function estaLotado()
    {
        return $this->membrosAtivos()->count() >= $this->capacidade_maxima;
    }

    /**
     * Verifica se um aluno pode ser adicionado ao grupo
     */
    public function podeAdicionarAluno(EbdAluno $aluno)
    {
        // Verificar se o grupo está ativo
        if (!$this->ativo) {
            return false;
        }

        // Verificar se o grupo não está lotado
        if ($this->estaLotado()) {
            return false;
        }

        // Verificar se o aluno pertence à turma do grupo
        if ($aluno->turma_id !== $this->turma_id) {
            return false;
        }

        // Verificar se o aluno já é membro ativo
        $jaEMembro = $this->membrosAtivos()
            ->where('aluno_id', $aluno->id)
            ->exists();

        return !$jaEMembro;
    }

    /**
     * Adiciona um aluno ao grupo
     */
    public function adicionarAluno(EbdAluno $aluno)
    {
        if (!$this->podeAdicionarAluno($aluno)) {
            return false;
        }

        // Verificar se já foi membro antes
        $membroAnterior = $this->membros()
            ->where('aluno_id', $aluno->id)
            ->first();

        if ($membroAnterior) {
            $membroAnterior->update([
                'status' => 'ativo',
                'data_saida' => null
            ]);
        } else {
            EbdGrupoMembro::create([
                'grupo_id' => $this->id,
                'aluno_id' => $aluno->id,
                'data_entrada' => now(),
                'status' => 'ativo'
            ]);
        }

        return true;
    }

    /**
     * Remove um aluno do grupo
     */
    public function removerAluno(EbdAluno $aluno)
    {
        $membro = $this->membrosAtivos()
            ->where('aluno_id', $aluno->id)
            ->first();

        if (!$membro) {
            return false;
        }

        // Se for o líder, remover a liderança
        if ($this->lider_id === $aluno->id) {
            $this->update(['lider_id' => null]);
        }

        $membro->update([
            'status' => 'removido',
            'data_saida' => now()
        ]);

        return true;
    }

    /**
     * Define um aluno como líder do grupo
     */
    public function definirLider(EbdAluno $aluno)
    {
        // Verificar se o aluno é membro ativo do grupo
        $eMembro = $this->membrosAtivos()
            ->where('aluno_id', $aluno->id)
            ->exists();

        if (!$eMembro) {
            // Adicionar como membro se não for
            if (!$this->adicionarAluno($aluno)) {
                return false;
            }
        }

        $this->update(['lider_id' => $aluno->id]);
        return true;
    }

    /**
     * Calcula a média de desempenho do grupo
     */
    public function mediaDesempenho()
    {
        $avaliacoesConcluidas = $this->avaliacoesConcluidas;
        
        if ($avaliacoesConcluidas->isEmpty()) {
            return 0;
        }

        return $avaliacoesConcluidas->avg('percentual');
    }

    /**
     * Retorna estatísticas do grupo
     */
    public function estatisticas()
    {
        return [
            'total_membros' => $this->membrosAtivos()->count(),
            'capacidade_maxima' => $this->capacidade_maxima,
            'percentual_ocupacao' => $this->capacidade_maxima > 0 
                ? ($this->membrosAtivos()->count() / $this->capacidade_maxima) * 100 
                : 0,
            'avaliacoes_realizadas' => $this->avaliacoesConcluidas()->count(),
            'avaliacoes_pendentes' => $this->avaliacoesPendentes()->count(),
            'media_desempenho' => $this->mediaDesempenho(),
            'esta_lotado' => $this->estaLotado()
        ];
    }

    /**
     * Accessor para cor com valor padrão
     */
    public function getCorAttribute($value)
    {
        return $value ?: '#3B82F6'; // Azul padrão
    }

    /**
     * Accessor para nome formatado
     */
    public function getNomeCompletoAttribute()
    {
        return $this->nome . ' (' . $this->turma->nome . ')';
    }

    /**
     * Accessor para status formatado
     */
    public function getStatusFormatadoAttribute()
    {
        return $this->ativo ? 'Ativo' : 'Inativo';
    }
}