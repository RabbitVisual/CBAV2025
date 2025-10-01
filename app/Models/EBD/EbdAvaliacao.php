<?php

namespace App\Models\EBD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Models\EbdTurma;

class EbdAvaliacao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ebd_avaliacoes';

    protected $fillable = [
        'turma_id',
        'titulo',
        'descricao',
        'tipo',
        'data_inicio',
        'data_fim',
        'tempo_limite',
        'pontuacao_maxima',
        'nota_minima',
        'tentativas_permitidas',
        'mostrar_resultado',
        'embaralhar_questoes',
        'ativo',
        'criado_por'
    ];

    protected $casts = [
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
        'tempo_limite' => 'integer',
        'pontuacao_maxima' => 'decimal:2',
        'nota_minima' => 'decimal:2',
        'tentativas_permitidas' => 'integer',
        'mostrar_resultado' => 'boolean',
        'embaralhar_questoes' => 'boolean',
        'ativo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * Relacionamento com a turma
     */
    public function turma()
    {
        return $this->belongsTo(EbdTurma::class, 'turma_id');
    }

    /**
     * Relacionamento com o criador
     */
    public function criador()
    {
        return $this->belongsTo(\App\Models\User::class, 'criado_por');
    }

    /**
     * Relacionamento com as questões
     */
    public function questoes()
    {
        return $this->hasMany(EbdQuestao::class, 'avaliacao_id')->orderBy('ordem');
    }

    /**
     * Relacionamento com as avaliações de grupo
     */
    public function avaliacoesGrupo()
    {
        return $this->hasMany(EbdAvaliacaoGrupo::class, 'avaliacao_id');
    }

    /**
     * Relacionamento com as respostas
     */
    public function respostas()
    {
        return $this->hasManyThrough(
            EbdRespostaGrupo::class,
            EbdQuestao::class,
            'avaliacao_id',
            'questao_id'
        );
    }

    /**
     * Scope para avaliações ativas
     */
    public function scopeAtivas($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope para avaliações em andamento
     */
    public function scopeEmAndamento($query)
    {
        $now = now();
        return $query->where('data_inicio', '<=', $now)
                    ->where('data_fim', '>=', $now)
                    ->where('ativo', true);
    }

    /**
     * Scope para avaliações futuras
     */
    public function scopeFuturas($query)
    {
        return $query->where('data_inicio', '>', now())
                    ->where('ativo', true);
    }

    /**
     * Scope para avaliações finalizadas
     */
    public function scopeFinalizadas($query)
    {
        return $query->where('data_fim', '<', now());
    }

    /**
     * Verificar se a avaliação está ativa
     */
    public function estaAtiva()
    {
        return $this->ativo && 
               $this->data_inicio <= now() && 
               $this->data_fim >= now();
    }

    /**
     * Verificar se a avaliação já começou
     */
    public function jaComecou()
    {
        return $this->data_inicio <= now();
    }

    /**
     * Verificar se a avaliação já terminou
     */
    public function jaTerminou()
    {
        return $this->data_fim < now();
    }

    /**
     * Obter tempo restante em segundos
     */
    public function getTempoRestante()
    {
        if ($this->jaTerminou()) {
            return 0;
        }

        return max(0, $this->data_fim->diffInSeconds(now()));
    }

    /**
     * Obter tempo restante formatado
     */
    public function getTempoRestanteFormatado()
    {
        $segundos = $this->getTempoRestante();
        
        if ($segundos <= 0) {
            return 'Finalizada';
        }

        $horas = floor($segundos / 3600);
        $minutos = floor(($segundos % 3600) / 60);
        $segundosRestantes = $segundos % 60;

        if ($horas > 0) {
            return sprintf('%02d:%02d:%02d', $horas, $minutos, $segundosRestantes);
        }

        return sprintf('%02d:%02d', $minutos, $segundosRestantes);
    }

    /**
     * Obter status da avaliação
     */
    public function getStatus()
    {
        if (!$this->ativo) {
            return 'inativa';
        }

        if ($this->jaTerminou()) {
            return 'finalizada';
        }

        if ($this->jaComecou()) {
            return 'em_andamento';
        }

        return 'agendada';
    }

    /**
     * Obter estatísticas da avaliação
     */
    public function getEstatisticas()
    {
        $avaliacoesGrupo = $this->avaliacoesGrupo;
        
        return [
            'total_grupos' => $avaliacoesGrupo->count(),
            'grupos_iniciados' => $avaliacoesGrupo->whereNotNull('iniciada_em')->count(),
            'grupos_concluidos' => $avaliacoesGrupo->whereNotNull('concluida_em')->count(),
            'pontuacao_media' => $avaliacoesGrupo->whereNotNull('pontuacao_total')->avg('pontuacao_total') ?? 0,
            'percentual_medio' => $avaliacoesGrupo->whereNotNull('percentual')->avg('percentual') ?? 0,
            'total_questoes' => $this->questoes()->count(),
            'total_respostas' => $this->respostas()->count()
        ];
    }

    /**
     * Verificar se um grupo pode iniciar a avaliação
     */
    public function podeIniciar($grupoId = null)
    {
        if (!$this->estaAtiva()) {
            return false;
        }

        if ($grupoId) {
            $avaliacaoGrupo = $this->avaliacoesGrupo()->where('grupo_id', $grupoId)->first();
            
            if ($avaliacaoGrupo && $avaliacaoGrupo->concluida_em) {
                return false; // Já foi concluída
            }
        }

        return true;
    }

    /**
     * Criar avaliação para um grupo
     */
    public function criarParaGrupo($grupoId)
    {
        return $this->avaliacoesGrupo()->firstOrCreate(
            ['grupo_id' => $grupoId],
            ['status' => 'pendente']
        );
    }
}