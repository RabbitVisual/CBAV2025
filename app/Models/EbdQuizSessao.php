<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EbdQuizSessao extends Model
{
    use HasFactory;

    protected $table = 'ebd_quiz_sessoes';

    protected $fillable = [
        'user_id',
        'nivel',
        'categoria',
        'total_perguntas',
        'acertos',
        'pontuacao_total',
        'percentual',
        'iniciado_em',
        'finalizado_em'
    ];

    protected $casts = [
        'iniciado_em' => 'datetime',
        'finalizado_em' => 'datetime',
        'percentual' => 'decimal:2'
    ];

    /**
     * Relacionamento com usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com respostas
     */
    public function respostas(): HasMany
    {
        return $this->hasMany(EbdQuizResposta::class, 'sessao_id');
    }

    /**
     * Verificar se sessão está finalizada
     */
    public function getEstaFinalizadaAttribute()
    {
        return !is_null($this->finalizado_em);
    }

    /**
     * Calcular duração da sessão
     */
    public function getDuracaoAttribute()
    {
        $fim = $this->finalizado_em ?? now();
        return $this->iniciado_em->diffInSeconds($fim);
    }

    /**
     * Obter duração formatada
     */
    public function getDuracaoFormatadaAttribute()
    {
        $segundos = $this->duracao;
        $minutos = floor($segundos / 60);
        $segundos = $segundos % 60;
        
        return sprintf('%02d:%02d', $minutos, $segundos);
    }

    /**
     * Obter conceito baseado no percentual
     */
    public function getConceitoAttribute()
    {
        return match(true) {
            $this->percentual >= 90 => 'Excelente',
            $this->percentual >= 80 => 'Muito Bom',
            $this->percentual >= 70 => 'Bom',
            $this->percentual >= 60 => 'Regular',
            default => 'Precisa Melhorar'
        };
    }

    /**
     * Obter cor do conceito
     */
    public function getCorConceitoAttribute()
    {
        return match($this->conceito) {
            'Excelente' => 'green',
            'Muito Bom' => 'blue',
            'Bom' => 'yellow',
            'Regular' => 'orange',
            default => 'red'
        };
    }

    /**
     * Escopo para sessões finalizadas
     */
    public function scopeFinalizadas($query)
    {
        return $query->whereNotNull('finalizado_em');
    }

    /**
     * Escopo por nível
     */
    public function scopePorNivel($query, $nivel)
    {
        return $query->where('nivel', $nivel);
    }

    /**
     * Escopo por categoria
     */
    public function scopePorCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }
} 