<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitacaoMinisterio extends Model
{
    use HasFactory;

    protected $table = 'solicitacoes_ministerio';

    protected $fillable = [
        'membro_id',
        'ministerio_id',
        'cargo_id',
        'motivo',
        'status',
        'resposta',
        'respondido_por',
        'data_resposta'
    ];

    protected $casts = [
        'data_resposta' => 'datetime',
    ];

    public function membro()
    {
        return $this->belongsTo(Membro::class);
    }

    public function ministerio()
    {
        return $this->belongsTo(Ministerio::class);
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

    public function respondidoPor()
    {
        return $this->belongsTo(User::class, 'respondido_por');
    }

    public function getStatusColorAttribute()
    {
        return [
            'pendente' => 'yellow',
            'aprovada' => 'green',
            'rejeitada' => 'red'
        ][$this->status] ?? 'gray';
    }

    public function getStatusTextAttribute()
    {
        return [
            'pendente' => 'Pendente',
            'aprovada' => 'Aprovada',
            'rejeitada' => 'Rejeitada'
        ][$this->status] ?? 'Desconhecido';
    }
} 