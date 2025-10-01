<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PedidoOracao extends Model
{
    use HasFactory;

    protected $fillable = [
        'membro_id',
        'titulo',
        'descricao',
        'categoria', // saude, familia, trabalho, espiritual, outros
        'prioridade', // baixa, media, alta, urgente
        'status', // pendente, em_oracao, atendido, arquivado
        'data_pedido',
        'data_atendimento',
        'observacoes',
        'anonimo',
        'pode_compartilhar'
    ];

    protected $casts = [
        'data_pedido' => 'datetime',
        'data_atendimento' => 'datetime',
        'anonimo' => 'boolean',
        'pode_compartilhar' => 'boolean'
    ];

    public function membro(): BelongsTo
    {
        return $this->belongsTo(Membro::class);
    }

    public function intercessores(): HasMany
    {
        return $this->hasMany(Intercessao::class, 'pedido_id');
    }

    public function getCategoriaTextAttribute(): string
    {
        return match($this->categoria) {
            'saude' => 'Saúde',
            'familia' => 'Família',
            'trabalho' => 'Trabalho',
            'espiritual' => 'Espiritual',
            'outros' => 'Outros',
            default => 'Não definido'
        };
    }

    public function getPrioridadeTextAttribute(): string
    {
        return match($this->prioridade) {
            'baixa' => 'Baixa',
            'media' => 'Média',
            'alta' => 'Alta',
            'urgente' => 'Urgente',
            default => 'Não definida'
        };
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'pendente' => 'Pendente',
            'em_oracao' => 'Em Oração',
            'atendido' => 'Atendido',
            'arquivado' => 'Arquivado',
            default => 'Desconhecido'
        };
    }

    public function getPrioridadeColorAttribute(): string
    {
        return match($this->prioridade) {
            'baixa' => 'green',
            'media' => 'yellow',
            'alta' => 'orange',
            'urgente' => 'red',
            default => 'gray'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pendente' => 'yellow',
            'em_oracao' => 'blue',
            'atendido' => 'green',
            'arquivado' => 'gray',
            default => 'gray'
        };
    }

    public function scopePendentes($query)
    {
        return $query->where('status', 'pendente');
    }

    public function scopeEmOracao($query)
    {
        return $query->where('status', 'em_oracao');
    }

    public function scopeAtendidos($query)
    {
        return $query->where('status', 'atendido');
    }

    public function scopePorCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    public function scopePorPrioridade($query, $prioridade)
    {
        return $query->where('prioridade', $prioridade);
    }

    public function scopeAnonimos($query)
    {
        return $query->where('anonimo', true);
    }

    public function scopeCompartilhados($query)
    {
        return $query->where('pode_compartilhar', true);
    }
} 