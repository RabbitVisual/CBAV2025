<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atividade extends Model
{
    use HasFactory;

    protected $table = 'ebd_atividades';

    protected $fillable = [
        'licao_id',
        'titulo',
        'descricao',
        'tipo',
        'pontuacao_maxima',
        'data_entrega',
        'ativo',
    ];

    /**
     * Relacionamento com a lição a que pertence.
     */
    public function licao()
    {
        return $this->belongsTo(EbdLicao::class, 'licao_id');
    }

    /**
     * Relacionamento com as entregas dos alunos.
     */
    public function entregas()
    {
        return $this->hasMany(EntregaAtividade::class, 'atividade_id');
    }
}