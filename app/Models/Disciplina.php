<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    use HasFactory;

    protected $table = 'ebd_disciplinas';

    protected $fillable = [
        'nome',
        'descricao',
        'professor_responsavel_id',
        'carga_horaria',
        'codigo_disciplina',
        'ativo',
    ];

    /**
     * Relacionamento com o professor responsável.
     */
    public function professorResponsavel()
    {
        return $this->belongsTo(User::class, 'professor_responsavel_id');
    }

    /**
     * Relacionamento com as lições da disciplina.
     */
    public function licoes()
    {
        return $this->hasMany(EbdLicao::class, 'disciplina_id');
    }
}