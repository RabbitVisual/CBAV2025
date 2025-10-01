<?php

namespace App\Models\EBD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EbdQuestao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ebd_questoes';

    protected $fillable = [
        'avaliacao_id',
        'titulo',
        'descricao',
        'tipo',
        'ordem',
        'pontuacao',
        'tempo_limite',
        'obrigatoria',
        'ativo'
    ];

    protected $casts = [
        'obrigatoria' => 'boolean',
        'ativo' => 'boolean',
        'ordem' => 'integer',
        'pontuacao' => 'decimal:2',
        'tempo_limite' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * Relacionamento com a avaliação
     */
    public function avaliacao()
    {
        return $this->belongsTo(EbdAvaliacao::class, 'avaliacao_id');
    }

    /**
     * Relacionamento com as respostas
     */
    public function respostas()
    {
        return $this->hasMany(EbdRespostaGrupo::class, 'questao_id');
    }

    /**
     * Relacionamento com as contribuições
     */
    public function contribuicoes()
    {
        return $this->hasMany(EbdContribuicaoResposta::class, 'questao_id');
    }

    /**
     * Scope para questões ativas
     */
    public function scopeAtivas($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope para questões obrigatórias
     */
    public function scopeObrigatorias($query)
    {
        return $query->where('obrigatoria', true);
    }

    /**
     * Scope para ordenar por ordem
     */
    public function scopeOrdenadas($query)
    {
        return $query->orderBy('ordem');
    }

    /**
     * Verificar se a questão tem tempo limite
     */
    public function temTempoLimite()
    {
        return !is_null($this->tempo_limite) && $this->tempo_limite > 0;
    }

    /**
     * Obter tempo limite formatado
     */
    public function getTempoLimiteFormatado()
    {
        if (!$this->temTempoLimite()) {
            return 'Sem limite';
        }

        $minutos = floor($this->tempo_limite / 60);
        $segundos = $this->tempo_limite % 60;

        if ($minutos > 0) {
            return $segundos > 0 ? "{$minutos}min {$segundos}s" : "{$minutos}min";
        }

        return "{$segundos}s";
    }

    /**
     * Verificar se a questão foi respondida por um grupo
     */
    public function foiRespondidaPorGrupo($grupoId)
    {
        return $this->respostas()->where('grupo_id', $grupoId)->exists();
    }

    /**
     * Obter resposta de um grupo específico
     */
    public function getRespostaDoGrupo($grupoId)
    {
        return $this->respostas()->where('grupo_id', $grupoId)->first();
    }

    /**
     * Contar total de contribuições
     */
    public function getTotalContribuicoes()
    {
        return $this->contribuicoes()->count();
    }

    /**
     * Obter estatísticas da questão
     */
    public function getEstatisticas()
    {
        return [
            'total_respostas' => $this->respostas()->count(),
            'total_contribuicoes' => $this->getTotalContribuicoes(),
            'pontuacao_media' => $this->respostas()->avg('pontuacao') ?? 0,
            'tempo_medio_resposta' => $this->respostas()->avg('tempo_resposta') ?? 0
        ];
    }
}