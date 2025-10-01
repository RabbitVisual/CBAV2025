<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Cep extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uf',
        'regiao',
        'localidade',
        'localidade_sem_acentos',
        'faixa_de_cep',
        'cep_inicial',
        'cep_final',
        'situacao',
        'tipo_de_faixa',
        'latitude',
        'longitude',
        'cod_geografico_subdivisao',
        'cod_geografico_distrito',
        'cod_ibge',
        'microrregiao',
        'mesorregiao',
        'categoria',
        'altitude',
        'localizacao',
        'localizacao_sem_acentos',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'altitude' => 'integer',
    ];

    /**
     * Buscar CEP por código postal
     *
     * @param string $cep
     * @return Builder
     */
    public static function buscarPorCep(string $cep): Builder
    {
        $cep = preg_replace('/\D/', '', $cep); // Remove caracteres não numéricos
        
        return static::where('cep_inicial', '<=', $cep)
                    ->where('cep_final', '>=', $cep);
    }

    /**
     * Buscar por UF
     *
     * @param string $uf
     * @return Builder
     */
    public static function buscarPorUf(string $uf): Builder
    {
        return static::where('uf', strtoupper($uf));
    }

    /**
     * Buscar por cidade
     *
     * @param string $cidade
     * @return Builder
     */
    public static function buscarPorCidade(string $cidade): Builder
    {
        return static::where('localidade', 'like', '%' . $cidade . '%')
                    ->orWhere('localidade_sem_acentos', 'like', '%' . $cidade . '%');
    }

    /**
     * Buscar por código IBGE
     *
     * @param string $codIbge
     * @return Builder
     */
    public static function buscarPorCodigoIbge(string $codIbge): Builder
    {
        return static::where('cod_ibge', $codIbge);
    }

    /**
     * Accessor para CEP formatado
     *
     * @return string
     */
    public function getCepInicialFormatadoAttribute(): string
    {
        return substr($this->cep_inicial, 0, 5) . '-' . substr($this->cep_inicial, 5);
    }

    /**
     * Accessor para CEP final formatado
     *
     * @return string
     */
    public function getCepFinalFormatadoAttribute(): string
    {
        return substr($this->cep_final, 0, 5) . '-' . substr($this->cep_final, 5);
    }
}
