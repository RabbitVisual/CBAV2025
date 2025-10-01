<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Devocional extends Model
{
    use HasFactory;

    protected $table = 'devocionais';

    protected $fillable = [
        'titulo',
        'texto',
        'versiculo',
        'texto_versiculo',
        'reflexao',
        'data',
        'tipo',
        'ativo',
        'ordem',
        'dados_extras'
    ];

    protected $casts = [
        'data' => 'date',
        'ativo' => 'boolean',
        'dados_extras' => 'array'
    ];

    /**
     * Scope para devocionais ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope para devocionais por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope para devocionais por data
     */
    public function scopePorData($query, $data)
    {
        return $query->where('data', $data);
    }

    /**
     * Scope para devocionais futuros
     */
    public function scopeFuturos($query)
    {
        return $query->where('data', '>=', Carbon::today());
    }

    /**
     * Scope para devocionais passados
     */
    public function scopePassados($query)
    {
        return $query->where('data', '<', Carbon::today());
    }

    /**
     * Criar devocional padrão para uma data
     */
    public static function criarPadrao($data, $tipo = 'devocional')
    {
        $devocionaisPadrao = [
            'devocional' => [
                'titulo' => 'Gratidão',
                'texto' => 'Em tudo dai graças, porque esta é a vontade de Deus em Cristo Jesus para convosco.',
                'versiculo' => '1 Tessalonicenses 5:18',
                'reflexao' => 'A gratidão transforma nossa perspectiva e nos ajuda a ver as bênçãos mesmo nas dificuldades.'
            ],
            'versiculo' => [
                'titulo' => 'Versículo do Dia',
                'texto' => 'Deus é o nosso refúgio e fortaleza, socorro bem presente na angústia.',
                'versiculo' => 'Salmos 46:1',
                'reflexao' => 'Deus está sempre presente para nos ajudar em momentos de dificuldade.'
            ],
            'oracao' => [
                'titulo' => 'Oração do Dia',
                'texto' => 'Pai, ensina-me a ser grato por todas as bênçãos que recebo diariamente e a compartilhar com os outros.',
                'versiculo' => '',
                'reflexao' => ''
            ]
        ];

        $padrao = $devocionaisPadrao[$tipo] ?? $devocionaisPadrao['devocional'];

        return static::create([
            'titulo' => $padrao['titulo'],
            'texto' => $padrao['texto'],
            'versiculo' => $padrao['versiculo'],
            'reflexao' => $padrao['reflexao'],
            'data' => $data,
            'tipo' => $tipo,
            'ativo' => true,
            'ordem' => 0
        ]);
    }

    /**
     * Obter estatísticas básicas
     */
    public static function getEstatisticasBasicas()
    {
        return [
            'total' => static::count(),
            'ativos' => static::where('ativo', true)->count(),
            'hoje' => static::where('data', Carbon::today())->count(),
            'tipos' => [
                'devocional' => static::where('tipo', 'devocional')->count(),
                'versiculo' => static::where('tipo', 'versiculo')->count(),
                'oracao' => static::where('tipo', 'oracao')->count()
            ]
        ];
    }
}
