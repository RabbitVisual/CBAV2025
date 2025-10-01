<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EbdRelatorio extends Model
{
    use HasFactory;

    protected $table = 'ebd_relatorios';

    protected $fillable = [
        'turma_id',
        'titulo',
        'descricao',
        'tipo',
        'data_inicio',
        'data_fim',
        'dados'
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'dados' => 'array',
    ];

    /**
     * Relacionamento com turma
     */
    public function turma(): BelongsTo
    {
        return $this->belongsTo(EbdTurma::class, 'turma_id');
    }

    /**
     * Obter tipo formatado
     */
    public function getTipoFormatadoAttribute()
    {
        return match($this->tipo) {
            'presenca' => 'Presença',
            'notas' => 'Notas',
            'progresso' => 'Progresso',
            'geral' => 'Geral',
            default => 'Geral'
        };
    }

    /**
     * Obter cor do tipo
     */
    public function getCorTipoAttribute()
    {
        return match($this->tipo) {
            'presenca' => 'bg-blue-100 text-blue-800',
            'notas' => 'bg-green-100 text-green-800',
            'progresso' => 'bg-purple-100 text-purple-800',
            'geral' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Scope para relatórios por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope para relatórios por turma
     */
    public function scopePorTurma($query, $turmaId)
    {
        return $query->where('turma_id', $turmaId);
    }
} 