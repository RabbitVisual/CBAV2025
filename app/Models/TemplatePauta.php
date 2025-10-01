<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemplatePauta extends Model
{
    protected $table = 'template_pautas';

    protected $fillable = [
        'nome',
        'descricao',
        'categoria',
        'status',
        'criado_por',
        'itens_pauta',
        'configuracoes'
    ];

    protected $casts = [
        'itens_pauta' => 'array',
        'configuracoes' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Usuário que criou o template
     */
    public function criadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'criado_por');
    }

    /**
     * Itens de pauta do template
     */
    public function itens(): HasMany
    {
        return $this->hasMany(TemplateItemPauta::class, 'template_id');
    }

    /**
     * Conselhos que usaram este template
     */
    public function conselhos(): HasMany
    {
        return $this->hasMany(Conselho::class, 'template_id');
    }

    /**
     * Texto do status
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'ativo' => __('Ativo'),
            'inativo' => __('Inativo'),
            'rascunho' => __('Rascunho'),
            default => __('Desconhecido')
        };
    }

    /**
     * Cor do status
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'ativo' => 'bg-green-100 text-green-800',
            'inativo' => 'bg-red-100 text-red-800',
            'rascunho' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Texto da categoria
     */
    public function getCategoriaTextAttribute(): string
    {
        return match($this->categoria) {
            'reuniao_ordinaria' => __('Reunião Ordinária'),
            'reuniao_extraordinaria' => __('Reunião Extraordinária'),
            'votacao' => __('Votação'),
            'evento' => __('Evento'),
            'geral' => __('Geral'),
            default => __('Outro')
        };
    }

    /**
     * Cor da categoria
     */
    public function getCategoriaColorAttribute(): string
    {
        return match($this->categoria) {
            'reuniao_ordinaria' => 'bg-blue-100 text-blue-800',
            'reuniao_extraordinaria' => 'bg-purple-100 text-purple-800',
            'votacao' => 'bg-orange-100 text-orange-800',
            'evento' => 'bg-green-100 text-green-800',
            'geral' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Contar número de usos
     */
    public function getUsosCountAttribute(): int
    {
        return $this->conselhos()->count();
    }

    /**
     * Contar número de itens
     */
    public function getItensCountAttribute(): int
    {
        return $this->itens()->count();
    }

    /**
     * Scope para templates ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('status', 'ativo');
    }

    /**
     * Scope para templates por categoria
     */
    public function scopePorCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    /**
     * Scope para templates criados por usuário
     */
    public function scopePorUsuario($query, $userId)
    {
        return $query->where('criado_por', $userId);
    }

    /**
     * Verificar se pode ser editado
     */
    public function podeEditar(): bool
    {
        return $this->status !== 'inativo';
    }

    /**
     * Verificar se pode ser excluído
     */
    public function podeExcluir(): bool
    {
        return $this->usos_count === 0;
    }

    /**
     * Duplicar template
     */
    public function duplicar(): self
    {
        $novo = $this->replicate();
        $novo->nome = $this->nome . ' (Cópia)';
        $novo->status = 'rascunho';
        $novo->save();

        // Duplicar itens
        foreach ($this->itens as $item) {
            $novoItem = $item->replicate();
            $novoItem->template_id = $novo->id;
            $novoItem->save();
        }

        return $novo;
    }
} 