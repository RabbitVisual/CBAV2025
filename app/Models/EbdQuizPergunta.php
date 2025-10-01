<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EbdQuizPergunta extends Model
{
    use HasFactory;

    protected $table = 'ebd_quiz_perguntas';

    protected $fillable = [
        'pergunta',
        'opcao_a',
        'opcao_b',
        'opcao_c',
        'opcao_d',
        'resposta_correta',
        'explicacao',
        'referencia_biblica',
        'nivel',
        'categoria',
        'ativo',
        'pontuacao'
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    /**
     * Relacionamento com respostas
     */
    public function respostas(): HasMany
    {
        return $this->hasMany(EbdQuizResposta::class, 'pergunta_id');
    }

    /**
     * Escopo para perguntas ativas
     */
    public function scopeAtivas($query)
    {
        return $query->where('ativo', true);
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

    /**
     * Obter opção por letra
     */
    public function getOpcao($letra)
    {
        $letra = strtolower($letra);
        return $this->{"opcao_{$letra}"} ?? null;
    }

    /**
     * Verificar se resposta está correta
     */
    public function verificarResposta($resposta)
    {
        return strtolower($resposta) === $this->resposta_correta;
    }

    /**
     * Obter nível formatado
     */
    public function getNivelFormatadoAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->nivel));
    }

    /**
     * Obter categoria formatada
     */
    public function getCategoriaFormatadaAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->categoria));
    }

    /**
     * Obter cor do nível
     */
    public function getCorNivelAttribute()
    {
        return match($this->nivel) {
            'facil' => 'green',
            'medio' => 'yellow',
            'dificil' => 'red',
            default => 'gray'
        };
    }
} 