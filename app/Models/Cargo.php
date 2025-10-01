<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'departamento_id',
        'ativo',
        'sistema' // Indica se é cargo para usuários do sistema
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'sistema' => 'boolean',
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function membros()
    {
        return $this->belongsToMany(Membro::class, 'membro_cargo')
                    ->withPivot('data_inicio', 'data_fim', 'ativo')
                    ->withTimestamps();
    }

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'user_cargo')
                    ->withTimestamps();
    }
}
