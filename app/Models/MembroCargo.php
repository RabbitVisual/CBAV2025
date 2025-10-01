<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MembroCargo extends Model
{
    protected $table = 'membro_cargo';

    protected $fillable = [
        'membro_id',
        'cargo_id',
        'data_inicio',
        'data_fim',
        'ativo'
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'ativo' => 'boolean',
    ];

    /**
     * Relacionamento com Membro
     */
    public function membro(): BelongsTo
    {
        return $this->belongsTo(Membro::class);
    }

    /**
     * Relacionamento com Cargo
     */
    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }
} 