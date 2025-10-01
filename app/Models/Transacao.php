<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transacao extends Model
{
    use HasFactory;

    protected $table = 'transacoes';

    protected $fillable = [
        'membro_id',
        'campanha_id',
        'tipo',
        'valor',
        'descricao',
        'data',
        'status',
        'comprovante',
        'dados_extras',
        'categoria',
        'metodo_pagamento',
        'observacoes'
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data' => 'date',
        'dados_extras' => 'array',
    ];

    public function membro()
    {
        return $this->belongsTo(Membro::class);
    }

    public function campanha()
    {
        return $this->belongsTo(Campanha::class);
    }

    public function pagamentos()
    {
        return $this->hasMany(Pagamento::class);
    }

    public function documentoBaixa()
    {
        return $this->hasOne(DocumentoBaixa::class);
    }

    /**
     * Boot the model
     */
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
