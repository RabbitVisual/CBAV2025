<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracao extends Model
{
    use HasFactory;

    protected $table = 'configuracoes';

    protected $fillable = [
        'chave',
        'valor',
        'tipo',
        'descricao'
    ];

    public static function get($chave, $default = null)
    {
        $config = static::where('chave', $chave)->first();
        return $config ? $config->valor : $default;
    }

    public static function set($chave, $valor, $tipo = 'string', $descricao = null)
    {
        // Garantir que o valor não seja nulo
        if ($valor === null) {
            $valor = '';
        }
        
        return static::updateOrCreate(
            ['chave' => $chave],
            [
                'valor' => $valor,
                'tipo' => $tipo,
                'descricao' => $descricao
            ]
        );
    }
}
