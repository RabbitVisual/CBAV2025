<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EbdAvaliacao extends Model
{
    use HasFactory;

    protected $table = 'ebd_avaliacoes';

    protected $fillable = [
        'aula_id',
        'titulo',
        'descricao',
        'tipo',
        'pontuacao_maxima',
        'obrigatoria',
        'ativo',
        'permite_grupos',
        'tempo_limite_minutos',
        'modo_avaliacao'
    ];

    protected $casts = [
        'obrigatoria' => 'boolean',
        'ativo' => 'boolean',
        'permite_grupos' => 'boolean',
        'tempo_limite_minutos' => 'integer'
    ];

    /**
     * Relacionamento com aula
     */
    public function aula(): BelongsTo
    {
        return $this->belongsTo(EbdAula::class, 'aula_id');
    }

    /**
     * Relacionamento com questões
     */
    public function questoes(): HasMany
    {
        return $this->hasMany(EbdQuestao::class, 'avaliacao_id');
    }

    /**
     * Relacionamento com notas
     */
    public function notas(): HasMany
    {
        return $this->hasMany(EbdNota::class, 'avaliacao_id');
    }

    /**
     * Relacionamento com respostas
     */
    public function respostas(): HasMany
    {
        return $this->hasMany(EbdRespostaAluno::class, 'avaliacao_id');
    }

    /**
     * Relacionamento com avaliações de grupos
     */
    public function avaliacoesGrupo(): HasMany
    {
        return $this->hasMany(EbdAvaliacaoGrupo::class, 'avaliacao_id');
    }

    /**
     * Calcular total de questões
     */
    public function getTotalQuestoesAttribute()
    {
        return $this->questoes()->count();
    }

    /**
     * Calcular total de alunos que fizeram a avaliação
     */
    public function getTotalAlunosAttribute()
    {
        return $this->notas()->count();
    }

    /**
     * Calcular média da avaliação
     */
    public function getMediaAttribute()
    {
        $notas = $this->notas();
        if ($notas->count() === 0) {
            return 0;
        }
        return round($notas->avg('percentual'), 2);
    }

    /**
     * Obter tipo formatado
     */
    public function getTipoFormatadoAttribute()
    {
        return match($this->tipo) {
            'quiz' => 'Quiz',
            'prova' => 'Prova',
            'trabalho' => 'Trabalho',
            'participacao' => 'Participação',
            default => 'Quiz'
        };
    }

    /**
     * Obter cor do tipo
     */
    public function getCorTipoAttribute()
    {
        return match($this->tipo) {
            'quiz' => 'bg-blue-100 text-blue-800',
            'prova' => 'bg-red-100 text-red-800',
            'trabalho' => 'bg-green-100 text-green-800',
            'participacao' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-blue-100 text-blue-800'
        };
    }

    /**
     * Verificar se avaliação foi aplicada
     */
    public function getFoiAplicadaAttribute()
    {
        return $this->notas()->count() > 0;
    }

    /**
     * Scope para avaliações obrigatórias
     */
    public function scopeObrigatorias($query)
    {
        return $query->where('obrigatoria', true);
    }

    /**
     * Scope para avaliações opcionais
     */
    public function scopeOpcionais($query)
    {
        return $query->where('obrigatoria', false);
    }

    /**
     * Scope para buscar por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope para buscar por título
     */
    public function scopePorTitulo($query, $titulo)
    {
        return $query->where('titulo', 'like', "%{$titulo}%");
    }

    /**
     * Scope para avaliações ativas
     */
    public function scopeAtivas($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope para avaliações inativas
     */
    public function scopeInativas($query)
    {
        return $query->where('ativo', false);
    }

    /**
     * Scope para avaliações que permitem grupos
     */
    public function scopePermiteGrupos($query)
    {
        return $query->where('permite_grupos', true);
    }

    /**
     * Scope para avaliações individuais
     */
    public function scopeIndividuais($query)
    {
        return $query->where('modo_avaliacao', 'individual');
    }

    /**
     * Scope para avaliações em grupo
     */
    public function scopeEmGrupo($query)
    {
        return $query->where('modo_avaliacao', 'grupo');
    }

    /**
     * Scope para avaliações que permitem ambos os modos
     */
    public function scopeAmbos($query)
    {
        return $query->where('modo_avaliacao', 'ambos');
    }

    /**
     * Verifica se a avaliação permite grupos
     */
    public function permiteGrupos(): bool
    {
        return $this->permite_grupos && in_array($this->modo_avaliacao, ['grupo', 'ambos']);
    }

    /**
     * Verifica se a avaliação permite modo individual
     */
    public function permiteIndividual(): bool
    {
        return in_array($this->modo_avaliacao, ['individual', 'ambos']);
    }

    /**
     * Verifica se a avaliação tem tempo limite
     */
    public function temTempoLimite(): bool
    {
        return !is_null($this->tempo_limite_minutos) && $this->tempo_limite_minutos > 0;
    }

    /**
     * Retorna o modo de avaliação formatado
     */
    public function getModoAvaliacaoFormatadoAttribute(): string
    {
        return match($this->modo_avaliacao) {
            'individual' => 'Individual',
            'grupo' => 'Em Grupo',
            'ambos' => 'Individual e Grupo',
            default => 'Individual'
        };
    }

    /**
     * Retorna estatísticas da avaliação incluindo grupos
     */
    public function estatisticasCompletas(): array
    {
        $estatisticas = [
            'total_questoes' => $this->total_questoes,
            'total_alunos_individual' => $this->total_alunos,
            'media_individual' => $this->media,
            'foi_aplicada' => $this->foi_aplicada,
            'permite_grupos' => $this->permite_grupos,
            'modo_avaliacao' => $this->modo_avaliacao,
            'tempo_limite_minutos' => $this->tempo_limite_minutos
        ];

        if ($this->permiteGrupos()) {
            $avaliacoesGrupo = $this->avaliacoesGrupo;
            $estatisticas['total_grupos'] = $avaliacoesGrupo->count();
            $estatisticas['grupos_concluidos'] = $avaliacoesGrupo->where('status', 'concluida')->count();
            $estatisticas['grupos_em_andamento'] = $avaliacoesGrupo->where('status', 'em_andamento')->count();
            $estatisticas['grupos_pendentes'] = $avaliacoesGrupo->where('status', 'pendente')->count();
            $estatisticas['media_grupos'] = $avaliacoesGrupo->where('status', 'concluida')->avg('percentual') ?? 0;
        }

        return $estatisticas;
    }
}