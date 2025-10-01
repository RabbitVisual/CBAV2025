<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntregaAtividade extends Model
{
    use HasFactory;

    protected $table = 'ebd_entrega_atividades';

    protected $fillable = [
        'atividade_id',
        'aluno_id',
        'resposta_texto',
        'arquivo_path',
        'nota',
        'feedback_professor',
        'data_entrega',
        'data_avaliacao',
        'status',
    ];

    /**
     * Relacionamento com a atividade a que pertence.
     */
    public function atividade()
    {
        return $this->belongsTo(Atividade::class, 'atividade_id');
    }

    /**
     * Relacionamento com o aluno que entregou.
     */
    public function aluno()
    {
        return $this->belongsTo(EbdAluno::class, 'aluno_id');
    }
}