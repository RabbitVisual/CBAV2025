<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campanha extends Model
{
    use HasFactory;

    protected $table = 'campanhas';

    protected $fillable = [
        'titulo',
        'descricao',
        'meta_valor',
        'valor_arrecadado',
        'data_inicio',
        'data_fim',
        'status',
        'tipo',
        'imagem',
        'qr_code_pix',
        'chave_pix',
        'ativo'
    ];

    // Alias para compatibilidade
    public function getMetaAttribute()
    {
        return $this->meta_valor;
    }

    protected $casts = [
        'meta_valor' => 'decimal:2',
        'valor_arrecadado' => 'decimal:2',
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'ativo' => 'boolean',
    ];

    public function transacoes()
    {
        return $this->hasMany(Transacao::class);
    }

    public function getProgressoAttribute()
    {
        if (!$this->meta_valor || $this->meta_valor == 0) {
            return 0;
        }
        return round(($this->valor_arrecadado / $this->meta_valor) * 100, 1);
    }

    public function getDiasRestantesAttribute()
    {
        if (!$this->data_fim) {
            return null;
        }
        $dias = now()->diffInDays($this->data_fim, false);
        return $dias > 0 ? round($dias) : 0;
    }
}
