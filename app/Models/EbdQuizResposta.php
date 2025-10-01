<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EbdQuizResposta extends Model
{
    use HasFactory;

    protected $table = 'ebd_quiz_respostas';

    protected $fillable = [
        'sessao_id',
        'pergunta_id',
        'resposta_dada',
        'correta',
        'pontuacao_obtida',
        'tempo_resposta'
    ];

    protected $casts = [
        'correta' => 'boolean',
    ];

    /**
     * Relacionamento com sessão
     */
    public function sessao(): BelongsTo
    {
        return $this->belongsTo(EbdQuizSessao::class, 'sessao_id');
    }

    /**
     * Relacionamento com pergunta
     */
    public function pergunta(): BelongsTo
    {
        return $this->belongsTo(EbdQuizPergunta::class, 'pergunta_id');
    }

    /**
     * Obter resposta formatada
     */
    public function getRespostaFormatadaAttribute()
    {
        return strtoupper($this->resposta_dada);
    }

    /**
     * Obter tempo formatado
     */
    public function getTempoFormatadoAttribute()
    {
        if (!$this->tempo_resposta) {
            return 'N/A';
        }
        
        $segundos = $this->tempo_resposta;
        $minutos = floor($segundos / 60);
        $segundos = $segundos % 60;
        
        return sprintf('%02d:%02d', $minutos, $segundos);
    }

    /**
     * Obter status da resposta
     */
    public function getStatusAttribute()
    {
        return $this->correta ? 'Correta' : 'Incorreta';
    }

    /**
     * Obter cor do status
     */
    public function getCorStatusAttribute()
    {
        return $this->correta ? 'green' : 'red';
    }
} 