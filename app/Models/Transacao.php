<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class Transacao extends Model
{
    use HasFactory;

    protected $table = 'transacoes';

    protected $fillable = [
        'membro_id', 'campanha_id', 'tipo', 'valor', 'descricao',
        'data', 'status', 'comprovante', 'dados_extras', 'categoria',
        'metodo_pagamento', 'observacoes'
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data' => 'date',
        'dados_extras' => 'array',
    ];

    // --- RELACIONAMENTOS ---

    public function membro(): BelongsTo
    {
        return $this->belongsTo(Membro::class);
    }

    public function campanha(): BelongsTo
    {
        return $this->belongsTo(Campanha::class);
    }

    public function pagamentos(): HasMany
    {
        return $this->hasMany(Pagamento::class);
    }

    public function documentoBaixa(): HasOne
    {
        return $this->hasOne(DocumentoBaixa::class);
    }

    // --- SCOPES ---

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmado');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pendente');
    }

    public function scopeIncome($query)
    {
        return $query->where('tipo', 'entrada');
    }

    public function scopeExpense($query)
    {
        return $query->where('tipo', 'saida');
    }

    // --- ACCESSORS ---

    public function getValorFormatadoAttribute(): string
    {
        return 'R$ ' . number_format($this->valor, 2, ',', '.');
    }

    public function getStatusFormatadoAttribute(): string
    {
        return match($this->status) {
            'confirmado' => 'Confirmado',
            'pendente' => 'Pendente',
            'cancelado' => 'Cancelado',
            default => ucfirst($this->status)
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'confirmado' => 'success',
            'pendente' => 'warning',
            'cancelado' => 'danger',
            default => 'info'
        };
    }

    // --- BOOT ---

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transacao) {
            if (empty($transacao->data)) {
                $transacao->data = now();
            }
        });
    }
}