<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EbdAluno extends Model
{
    use HasFactory;

    protected $table = 'ebd_alunos';

    protected $fillable = [
        'membro_id',
        'nome',
        'email',
        'telefone',
        'data_nascimento',
        'nome_responsavel',
        'telefone_responsavel',
        'turma_id',
        'data_matricula',
        'data_saida',
        'status',
        'observacoes'
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'data_matricula' => 'date',
        'data_saida' => 'date',
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
     * Relacionamento com presenças
     */
    public function presencas(): HasMany
    {
        return $this->hasMany(EbdPresenca::class, 'aluno_id');
    }

    /**
     * Relacionamento com notas
     */
    public function notas(): HasMany
    {
        return $this->hasMany(EbdNota::class, 'aluno_id');
    }

    /**
     * Relacionamento com respostas
     */
    public function respostas(): HasMany
    {
        return $this->hasMany(EbdRespostaAluno::class, 'aluno_id');
    }

    /**
     * Relacionamento com certificados
     */
    public function certificados(): HasMany
    {
        return $this->hasMany(EbdCertificado::class, 'aluno_id');
    }

    /**
     * Calcular idade do aluno
     */
    public function getIdadeAttribute()
    {
        if (!$this->data_nascimento) {
            return null;
        }
        return $this->data_nascimento->age;
    }

    /**
     * Calcular tempo de matrícula
     */
    public function getTempoMatriculaAttribute()
    {
        return $this->data_matricula->diffForHumans();
    }

    /**
     * Calcular total de presenças
     */
    public function getTotalPresencasAttribute()
    {
        return $this->presencas()->where('status', 'presente')->count();
    }

    /**
     * Calcular total de ausências
     */
    public function getTotalAusenciasAttribute()
    {
        return $this->presencas()->where('status', 'ausente')->count();
    }

    /**
     * Calcular percentual de presença
     */
    public function getPercentualPresencaAttribute()
    {
        $total = $this->presencas()->count();
        if ($total === 0) {
            return 0;
        }
        return round(($this->total_presencas / $total) * 100, 2);
    }

    /**
     * Calcular média geral
     */
    public function getMediaGeralAttribute()
    {
        $notas = $this->notas();
        if ($notas->count() === 0) {
            return 0;
        }
        return round($notas->avg('percentual'), 2);
    }

    /**
     * Verificar se aluno está ativo
     */
    public function getEstaAtivoAttribute()
    {
        return $this->status === 'ativo';
    }

    /**
     * Obter nome completo
     */
    public function getNomeCompletoAttribute()
    {
        return $this->nome;
    }

    /**
     * Obter contato principal
     */
    public function getContatoPrincipalAttribute()
    {
        return $this->telefone ?: $this->telefone_responsavel;
    }

    /**
     * Scope para alunos ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('status', 'ativo');
    }

    /**
     * Scope para alunos por turma
     */
    public function scopePorTurma($query, $turmaId)
    {
        return $query->where('turma_id', $turmaId);
    }

    /**
     * Scope para buscar por nome
     */
    public function scopePorNome($query, $nome)
    {
        return $query->where('nome', 'like', "%{$nome}%");
    }

    /**
     * Scope para alunos com membro
     */
    public function scopeComMembro($query)
    {
        return $query->whereNotNull('membro_id');
    }

    /**
     * Scope para alunos sem membro
     */
    public function scopeSemMembro($query)
    {
        return $query->whereNull('membro_id');
    }
} 