<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EbdPresenca extends Model
{
    use HasFactory;

    protected $table = 'ebd_presencas';

    protected $fillable = [
        'aula_id',
        'aluno_id',
        'status',
        'observacoes'
    ];

    /**
     * Relacionamento com aula
     */
    public function aula(): BelongsTo
    {
        return $this->belongsTo(EbdAula::class, 'aula_id');
    }

    /**
     * Relacionamento com aluno
     */
    public function aluno(): BelongsTo
    {
        return $this->belongsTo(EbdAluno::class, 'aluno_id');
    }

    /**
     * Obter status formatado
     */
    public function getStatusFormatadoAttribute()
    {
        return match($this->status) {
            'presente' => 'Presente',
            'ausente' => 'Ausente',
            'justificado' => 'Justificado',
            'atrasado' => 'Atrasado',
            default => 'Presente'
        };
    }

    /**
     * Obter cor do status
     */
    public function getCorStatusAttribute()
    {
        return match($this->status) {
            'presente' => 'bg-green-100 text-green-800',
            'ausente' => 'bg-red-100 text-red-800',
            'justificada' => 'bg-yellow-100 text-yellow-800',
            'atrasado' => 'bg-orange-100 text-orange-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Scope para presenças
     */
    public function scopePresentes($query)
    {
        return $query->where('status', 'presente');
    }

    /**
     * Scope para ausências
     */
    public function scopeAusentes($query)
    {
        return $query->where('status', 'ausente');
    }

    /**
     * Scope para justificadas
     */
    public function scopeJustificadas($query)
    {
        return $query->where('status', 'justificado');
    }

    /**
     * Scope para atrasados
     */
    public function scopeAtrasados($query)
    {
        return $query->where('status', 'atrasado');
    }
} 