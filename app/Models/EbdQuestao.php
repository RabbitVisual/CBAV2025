<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EbdQuestao extends Model
{
    use HasFactory;

    protected $table = 'ebd_questoes';

    protected $fillable = [
        'avaliacao_id',
        'pergunta',
        'tipo',
        'opcoes',
        'resposta_correta',
        'pontuacao',
        'explicacao',
        'ordem'
    ];

    protected $casts = [
        'opcoes' => 'array',
    ];

    /**
     * Relacionamento com avaliação
     */
    public function avaliacao(): BelongsTo
    {
        return $this->belongsTo(EbdAvaliacao::class, 'avaliacao_id');
    }

    /**
     * Relacionamento com respostas
     */
    public function respostas(): HasMany
    {
        return $this->hasMany(EbdRespostaAluno::class, 'questao_id');
    }

    /**
     * Obter tipo formatado
     */
    public function getTipoFormatadoAttribute()
    {
        return match($this->tipo) {
            'multipla_escolha' => 'Múltipla Escolha',
            'verdadeiro_falso' => 'Verdadeiro/Falso',
            'dissertativa' => 'Dissertativa',
            'correspondencia' => 'Correspondência',
            default => 'Múltipla Escolha'
        };
    }

    /**
     * Obter cor do tipo
     */
    public function getCorTipoAttribute()
    {
        return match($this->tipo) {
            'multipla_escolha' => 'bg-blue-100 text-blue-800',
            'verdadeiro_falso' => 'bg-green-100 text-green-800',
            'dissertativa' => 'bg-purple-100 text-purple-800',
            'correspondencia' => 'bg-orange-100 text-orange-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Verificar se questão é de múltipla escolha
     */
    public function getEMultiplaEscolhaAttribute()
    {
        return $this->tipo === 'multipla_escolha';
    }

    /**
     * Verificar se questão é verdadeiro/falso
     */
    public function getEVerdadeiroFalsoAttribute()
    {
        return $this->tipo === 'verdadeiro_falso';
    }

    /**
     * Verificar se questão é dissertativa
     */
    public function getEDissertativaAttribute()
    {
        return $this->tipo === 'dissertativa';
    }

    /**
     * Verificar se questão é de correspondência
     */
    public function getECorrespondenciaAttribute()
    {
        return $this->tipo === 'correspondencia';
    }

    /**
     * Obter opções formatadas
     */
    public function getOpcoesFormatadasAttribute()
    {
        if (!$this->opcoes) {
            return [];
        }

        $opcoes = [];
        foreach ($this->opcoes as $key => $opcao) {
            $opcoes[] = [
                'valor' => $key,
                'texto' => $opcao
            ];
        }
        return $opcoes;
    }

    /**
     * Calcular total de respostas corretas
     */
    public function getTotalRespostasCorretasAttribute()
    {
        return $this->respostas()->where('correta', true)->count();
    }

    /**
     * Calcular total de respostas incorretas
     */
    public function getTotalRespostasIncorretasAttribute()
    {
        return $this->respostas()->where('correta', false)->count();
    }

    /**
     * Calcular percentual de acerto
     */
    public function getPercentualAcertoAttribute()
    {
        $total = $this->respostas()->count();
        if ($total === 0) {
            return 0;
        }
        return round(($this->total_respostas_corretas / $total) * 100, 2);
    }

    /**
     * Scope para questões por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope para questões ordenadas
     */
    public function scopeOrdenadas($query)
    {
        return $query->orderBy('ordem');
    }

    /**
     * Scope para questões de múltipla escolha
     */
    public function scopeMultiplaEscolha($query)
    {
        return $query->where('tipo', 'multipla_escolha');
    }

    /**
     * Scope para questões verdadeiro/falso
     */
    public function scopeVerdadeiroFalso($query)
    {
        return $query->where('tipo', 'verdadeiro_falso');
    }

    /**
     * Scope para questões dissertativas
     */
    public function scopeDissertativas($query)
    {
        return $query->where('tipo', 'dissertativa');
    }
} 