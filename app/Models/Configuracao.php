<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Configuracao
 *
 * @property int $id
 * @property string $chave
 * @property mixed|null $valor
 * @property string $tipo
 * @property string|null $descricao
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\ConfiguracaoFactory factory($count = null, $state = [])
 * @method static Builder|Configuracao newModelQuery()
 * @method static Builder|Configuracao newQuery()
 * @method static Builder|Configuracao query()
 */
class Configuracao extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'configuracoes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'chave',
        'valor',
        'tipo',
        'descricao',
    ];

    /**
     * The cache key for storing settings.
     *
     * @var string
     */
    private const CACHE_KEY = 'app_configuracoes';

    /**
     * Retrieve a configuration value by its key.
     * Implements caching to reduce database queries.
     *
     * @param string $chave The configuration key.
     * @param mixed $default The default value to return if the key is not found.
     * @return mixed
     */
    public static function get(string $chave, mixed $default = null): mixed
    {
        $configuracoes = Cache::rememberForever(self::CACHE_KEY, function () {
            // Eager load all settings into a key-value array
            return self::all()->keyBy('chave');
        });

        $config = $configuracoes->get($chave);

        if (!$config) {
            return $default;
        }

        // Cast the value to its correct type
        return match ($config->tipo) {
            'boolean' => filter_var($config->valor, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $config->valor,
            'float'   => (float) $config->valor,
            'json'    => json_decode($config->valor, true),
            default   => $config->valor,
        };
    }

    /**
     * Set a configuration value.
     * Creates or updates the setting and clears the cache.
     *
     * @param string $chave The configuration key.
     * @param mixed $valor The value to set.
     * @param string $tipo The data type of the value.
     * @param string|null $descricao A description for the setting.
     * @return self
     */
    public static function set(string $chave, mixed $valor, string $tipo = 'string', ?string $descricao = null): self
    {
        // Automatically encode arrays to JSON
        if (is_array($valor)) {
            $valor = json_encode($valor);
            $tipo = 'json';
        }

        $config = static::updateOrCreate(
            ['chave' => $chave],
            [
                'valor' => $valor ?? '', // Ensure value is not null
                'tipo' => $tipo,
                'descricao' => $descricao,
            ]
        );

        // Bust the cache to reflect the change immediately
        self::clearCache();

        return $config;
    }

    /**
     * Clear the settings cache.
     *
     * @return void
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * The "booted" method of the model.
     * Clears the cache when a setting is saved or deleted.
     */
    protected static function booted(): void
    {
        static::saved(fn () => self::clearCache());
        static::deleted(fn () => self::clearCache());
    }
}
