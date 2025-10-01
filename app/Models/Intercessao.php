<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Intercessao extends Model
{
    use HasFactory;

    protected $table = 'intercessor_oracaos';

    protected $fillable = [
        'pedido_id',
        'user_id',
        'data_oracao',
        'tipo_oracao',
        'tempo_oracao',
        'observacoes',
    ];

    protected $casts = [
        'data_oracao' => 'datetime',
        'tempo_oracao' => 'integer',
    ];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(PedidoOracao::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTipoOracaoTextAttribute(): string
    {
        return match($this->tipo_oracao) {
            'individual' => 'Individual',
            'grupo' => 'Grupo',
            'igreja' => 'Igreja',
            default => 'Não definido'
        };
    }

    public function getTempoOracaoFormatadoAttribute(): string
    {
        if ($this->tempo_oracao < 60) {
            return $this->tempo_oracao . ' min';
        }
        
        $horas = floor($this->tempo_oracao / 60);
        $minutos = $this->tempo_oracao % 60;
        
        if ($minutos == 0) {
            return $horas . 'h';
        }
        
        return $horas . 'h ' . $minutos . 'min';
    }
} 