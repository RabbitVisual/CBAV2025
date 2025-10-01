<?php

namespace App\Models\EBD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EbdRespostaGrupo extends Model
{
    use HasFactory;

    protected $table = 'ebd_respostas_grupo';

    protected $fillable = [
        'avaliacao_grupo_id',
        'questao_id',
        'resposta_final',
        'justificativa',
        'pontos_obtidos',
        'esta_correta',
        'tempo_resposta_segundos',
        'respondida_em'
    ];

    protected $casts = [
        'pontos_obtidos' => 'decimal:2',
        'esta_correta' => 'boolean',
        'tempo_resposta_segundos' => 'integer',
        'respondida_em' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relacionamento com a avaliação do grupo
     */
    public function avaliacaoGrupo()
    {
        return $this->belongsTo(EbdAvaliacaoGrupo::class, 'avaliacao_grupo_id');
    }

    /**
     * Relacionamento com a questão
     */
    public function questao()
    {
        return $this->belongsTo(EbdQuestao::class, 'questao_id');
    }

    /**
     * Relacionamento com as contribuições dos membros
     */
    public function contribuicoes()
    {
        return $this->hasMany(EbdContribuicaoResposta::class, 'resposta_grupo_id');
    }

    /**
     * Scope para respostas corretas
     */
    public function scopeCorretas($query)
    {
        return $query->where('esta_correta', true);
    }

    /**
     * Scope para respostas incorretas
     */
    public function scopeIncorretas($query)
    {
        return $query->where('esta_correta', false);
    }

    /**
     * Scope para respostas de uma avaliação específica
     */
    public function scopeDaAvaliacao($query, $avaliacaoGrupoId)
    {
        return $query->where('avaliacao_grupo_id', $avaliacaoGrupoId);
    }

    /**
     * Adiciona uma contribuição de um membro
     */
    public function adicionarContribuicao($alunoId, $respostaSugerida, $justificativa = null)
    {
        return EbdContribuicaoResposta::create([
            'resposta_grupo_id' => $this->id,
            'aluno_id' => $alunoId,
            'resposta_sugerida' => $respostaSugerida,
            'justificativa' => $justificativa,
            'contribuida_em' => now()
        ]);
    }

    /**
     * Finaliza a resposta do grupo
     */
    public function finalizar($respostaFinal, $justificativa = null)
    {
        // Verificar se a resposta está correta
        $estaCorreta = $this->verificarResposta($respostaFinal);
        
        // Calcular pontos obtidos
        $pontosObtidos = $estaCorreta ? $this->questao->pontos : 0;

        $this->update([
            'resposta_final' => $respostaFinal,
            'justificativa' => $justificativa,
            'pontos_obtidos' => $pontosObtidos,
            'esta_correta' => $estaCorreta,
            'respondida_em' => now()
        ]);

        return $this;
    }

    /**
     * Verifica se a resposta está correta
     */
    public function verificarResposta($resposta)
    {
        $questao = $this->questao;
        
        switch ($questao->tipo) {
            case 'multipla_escolha':
                return $resposta === $questao->resposta_correta;
            
            case 'verdadeiro_falso':
                return $resposta === $questao->resposta_correta;
            
            case 'dissertativa':
                // Para questões dissertativas, sempre retorna true
                // A correção deve ser feita manualmente
                return true;
            
            default:
                return false;
        }
    }

    /**
     * Calcula o tempo de resposta em segundos
     */
    public function calcularTempoResposta()
    {
        if (!$this->respondida_em || !$this->created_at) {
            return 0;
        }

        return $this->created_at->diffInSeconds($this->respondida_em);
    }

    /**
     * Atualiza o tempo de resposta
     */
    public function atualizarTempoResposta()
    {
        $tempo = $this->calcularTempoResposta();
        $this->update(['tempo_resposta_segundos' => $tempo]);
        return $tempo;
    }

    /**
     * Retorna estatísticas das contribuições
     */
    public function estatisticasContribuicoes()
    {
        $contribuicoes = $this->contribuicoes;
        $totalContribuicoes = $contribuicoes->count();
        
        if ($totalContribuicoes === 0) {
            return [
                'total_contribuicoes' => 0,
                'contribuicoes_corretas' => 0,
                'contribuicoes_incorretas' => 0,
                'percentual_corretas' => 0
            ];
        }

        $contribuicoesCorretas = $contribuicoes->filter(function ($contribuicao) {
            return $this->verificarResposta($contribuicao->resposta_sugerida);
        })->count();

        return [
            'total_contribuicoes' => $totalContribuicoes,
            'contribuicoes_corretas' => $contribuicoesCorretas,
            'contribuicoes_incorretas' => $totalContribuicoes - $contribuicoesCorretas,
            'percentual_corretas' => ($contribuicoesCorretas / $totalContribuicoes) * 100
        ];
    }

    /**
     * Verifica se todos os membros do grupo contribuíram
     */
    public function todosMembrosCcontribuiram()
    {
        $totalMembros = $this->avaliacaoGrupo->grupo->membrosAtivos()->count();
        $totalContribuicoes = $this->contribuicoes()->distinct('aluno_id')->count();
        
        return $totalMembros === $totalContribuicoes;
    }

    /**
     * Retorna os membros que ainda não contribuíram
     */
    public function membrosSemContribuicao()
    {
        $membrosGrupo = $this->avaliacaoGrupo->grupo->membrosAtivos()->pluck('aluno_id');
        $membrosQueContribuiram = $this->contribuicoes()->pluck('aluno_id');
        
        return $membrosGrupo->diff($membrosQueContribuiram);
    }

    /**
     * Accessor para tempo de resposta formatado
     */
    public function getTempoRespostaFormatadoAttribute()
    {
        $segundos = $this->tempo_resposta_segundos;
        
        if ($segundos < 60) {
            return $segundos . 's';
        } elseif ($segundos < 3600) {
            $minutos = floor($segundos / 60);
            $segundosRestantes = $segundos % 60;
            return $minutos . 'min ' . $segundosRestantes . 's';
        } else {
            $horas = floor($segundos / 3600);
            $minutosRestantes = floor(($segundos % 3600) / 60);
            return $horas . 'h ' . $minutosRestantes . 'min';
        }
    }

    /**
     * Accessor para status da resposta
     */
    public function getStatusRespostaAttribute()
    {
        if (!$this->respondida_em) {
            return 'Pendente';
        }
        
        return $this->esta_correta ? 'Correta' : 'Incorreta';
    }

    /**
     * Accessor para data de resposta formatada
     */
    public function getRespondidaEmFormatadaAttribute()
    {
        return $this->respondida_em ? $this->respondida_em->format('d/m/Y H:i:s') : null;
    }

    /**
     * Accessor para pontos formatados
     */
    public function getPontosFormatadosAttribute()
    {
        return number_format($this->pontos_obtidos, 1) . '/' . number_format($this->questao->pontos, 1);
    }
}