<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateItemPauta extends Model
{
    protected $table = 'template_item_pautas';

    protected $fillable = [
        'template_id',
        'titulo',
        'descricao',
        'tipo',
        'prioridade',
        'ordem',
        'tempo_estimado',
        'responsavel_id',
        'observacoes',
        'configuracoes'
    ];

    protected $casts = [
        'configuracoes' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Template ao qual pertence
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(TemplatePauta::class, 'template_id');
    }

    /**
     * Responsável pelo item
     */
    public function responsavel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }

    /**
     * Texto do tipo
     */
    public function getTipoTextAttribute(): string
    {
        return match($this->tipo) {
            'informativo' => __('Informativo'),
            'deliberativo' => __('Deliberativo'),
            'votacao' => __('Votação'),
            'discussao' => __('Discussão'),
            'apresentacao' => __('Apresentação'),
            default => __('Outro')
        };
    }

    /**
     * Cor do tipo
     */
    public function getTipoColorAttribute(): string
    {
        return match($this->tipo) {
            'informativo' => 'bg-blue-100 text-blue-800',
            'deliberativo' => 'bg-green-100 text-green-800',
            'votacao' => 'bg-orange-100 text-orange-800',
            'discussao' => 'bg-purple-100 text-purple-800',
            'apresentacao' => 'bg-indigo-100 text-indigo-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Texto da prioridade
     */
    public function getPrioridadeTextAttribute(): string
    {
        return match($this->prioridade) {
            'baixa' => __('Baixa'),
            'media' => __('Média'),
            'alta' => __('Alta'),
            'urgente' => __('Urgente'),
            default => __('Não definida')
        };
    }

    /**
     * Cor da prioridade
     */
    public function getPrioridadeColorAttribute(): string
    {
        return match($this->prioridade) {
            'baixa' => 'bg-green-100 text-green-800',
            'media' => 'bg-yellow-100 text-yellow-800',
            'alta' => 'bg-orange-100 text-orange-800',
            'urgente' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Tempo estimado formatado
     */
    public function getTempoEstimadoFormatadoAttribute(): string
    {
        if (!$this->tempo_estimado) {
            return __('Não definido');
        }

        $horas = intval($this->tempo_estimado / 60);
        $minutos = $this->tempo_estimado % 60;

        if ($horas > 0) {
            return sprintf('%dh %02dm', $horas, $minutos);
        }

        return sprintf('%dm', $minutos);
    }

    /**
     * Scope para itens por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope para itens por prioridade
     */
    public function scopePorPrioridade($query, $prioridade)
    {
        return $query->where('prioridade', $prioridade);
    }

    /**
     * Scope para itens ordenados
     */
    public function scopeOrdenados($query)
    {
        return $query->orderBy('ordem')->orderBy('prioridade', 'desc');
    }
} 