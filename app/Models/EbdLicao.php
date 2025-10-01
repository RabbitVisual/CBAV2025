<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class EbdLicao extends Model
{
    use HasFactory;

    protected $table = 'ebd_licoes';

    protected $fillable = [
        'titulo',
        'descricao',
        'objetivos',
        'versiculo_chave',
        'conteudo',
        'aplicacao_pratica',
        'oracao',
        'material_necessario',
        'duracao_minutos',
        'dificuldade',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    /**
     * Relacionamento com aulas
     */
    public function aulas(): HasMany
    {
        return $this->hasMany(EbdAula::class, 'licao_id');
    }

    /**
     * Relacionamento com avaliações através das aulas
     */
    public function avaliacoes(): HasManyThrough
    {
        return $this->hasManyThrough(
            EbdAvaliacao::class,
            EbdAula::class,
            'licao_id', // Chave estrangeira em ebd_aulas
            'aula_id',   // Chave estrangeira em ebd_avaliacoes
            'id',        // Chave local em ebd_licoes
            'id'         // Chave local em ebd_aulas
        );
    }

    /**
     * Obter aulas agendadas
     */
    public function aulasAgendadas()
    {
        return $this->aulas()->where('status', 'agendada');
    }

    /**
     * Obter aulas realizadas
     */
    public function aulasRealizadas()
    {
        return $this->aulas()->where('status', 'realizada');
    }

    /**
     * Calcular total de aulas
     */
    public function getTotalAulasAttribute()
    {
        return $this->aulas()->count();
    }

    /**
     * Calcular total de avaliações
     */
    public function getTotalAvaliacoesAttribute()
    {
        return $this->avaliacoes()->count();
    }

    /**
     * Obter duração formatada
     */
    public function getDuracaoFormatadaAttribute()
    {
        $horas = floor($this->duracao_minutos / 60);
        $minutos = $this->duracao_minutos % 60;
        
        if ($horas > 0) {
            return "{$horas}h {$minutos}min";
        }
        return "{$minutos}min";
    }

    /**
     * Obter dificuldade formatada
     */
    public function getDificuldadeFormatadaAttribute()
    {
        return match($this->dificuldade) {
            'facil' => 'Fácil',
            'medio' => 'Médio',
            'dificil' => 'Difícil',
            default => 'Médio'
        };
    }

    /**
     * Obter cor da dificuldade
     */
    public function getCorDificuldadeAttribute()
    {
        return match($this->dificuldade) {
            'facil' => 'bg-green-100 text-green-800',
            'medio' => 'bg-yellow-100 text-yellow-800',
            'dificil' => 'bg-red-100 text-red-800',
            default => 'bg-yellow-100 text-yellow-800'
        };
    }

    /**
     * Scope para lições ativas
     */
    public function scopeAtivas($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope para buscar por título
     */
    public function scopePorTitulo($query, $titulo)
    {
        return $query->where('titulo', 'like', "%{$titulo}%");
    }

    /**
     * Scope para buscar por dificuldade
     */
    public function scopePorDificuldade($query, $dificuldade)
    {
        return $query->where('dificuldade', $dificuldade);
    }

    /**
     * Scope para lições fáceis
     */
    public function scopeFaceis($query)
    {
        return $query->where('dificuldade', 'facil');
    }

    /**
     * Scope para lições médias
     */
    public function scopeMedias($query)
    {
        return $query->where('dificuldade', 'medio');
    }

    /**
     * Scope para lições difíceis
     */
    public function scopeDificeis($query)
    {
        return $query->where('dificuldade', 'dificil');
    }
} 