<?php

namespace App\Models\EBD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EbdAluno;

class EbdContribuicaoResposta extends Model
{
    use HasFactory;

    protected $table = 'ebd_contribuicoes_resposta';

    protected $fillable = [
        'resposta_grupo_id',
        'aluno_id',
        'resposta_sugerida',
        'justificativa',
        'contribuida_em'
    ];

    protected $casts = [
        'contribuida_em' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relacionamento com a resposta do grupo
     */
    public function respostaGrupo()
    {
        return $this->belongsTo(EbdRespostaGrupo::class, 'resposta_grupo_id');
    }

    /**
     * Relacionamento com o aluno
     */
    public function aluno()
    {
        return $this->belongsTo(EbdAluno::class, 'aluno_id');
    }

    /**
     * Relacionamento com a questão através da resposta do grupo
     */
    public function questao()
    {
        return $this->hasOneThrough(
            EbdQuestao::class,
            EbdRespostaGrupo::class,
            'id',
            'id',
            'resposta_grupo_id',
            'questao_id'
        );
    }

    /**
     * Scope para contribuições de um aluno específico
     */
    public function scopeDoAluno($query, $alunoId)
    {
        return $query->where('aluno_id', $alunoId);
    }

    /**
     * Scope para contribuições de uma resposta específica
     */
    public function scopeDaResposta($query, $respostaGrupoId)
    {
        return $query->where('resposta_grupo_id', $respostaGrupoId);
    }

    /**
     * Scope para contribuições ordenadas por data
     */
    public function scopeOrdenadaPorData($query, $direcao = 'asc')
    {
        return $query->orderBy('contribuida_em', $direcao);
    }

    /**
     * Verifica se a contribuição está correta
     */
    public function estaCorreta()
    {
        return $this->respostaGrupo->verificarResposta($this->resposta_sugerida);
    }

    /**
     * Verifica se esta contribuição foi escolhida como resposta final
     */
    public function foiEscolhidaComoFinal()
    {
        return $this->respostaGrupo->resposta_final === $this->resposta_sugerida;
    }

    /**
     * Verifica se o aluno é o líder do grupo
     */
    public function autorEhLider()
    {
        $grupo = $this->respostaGrupo->avaliacaoGrupo->grupo;
        return $grupo->lider_id === $this->aluno_id;
    }

    /**
     * Calcula o tempo desde a criação da resposta até esta contribuição
     */
    public function tempoParaContribuir()
    {
        if (!$this->contribuida_em || !$this->respostaGrupo->created_at) {
            return 0;
        }

        return $this->respostaGrupo->created_at->diffInSeconds($this->contribuida_em);
    }

    /**
     * Retorna a posição desta contribuição (primeira, segunda, etc.)
     */
    public function posicaoContribuicao()
    {
        return $this->respostaGrupo->contribuicoes()
            ->where('contribuida_em', '<=', $this->contribuida_em)
            ->count();
    }

    /**
     * Verifica se foi a primeira contribuição para esta questão
     */
    public function foiPrimeiraContribuicao()
    {
        return $this->posicaoContribuicao() === 1;
    }

    /**
     * Retorna estatísticas da contribuição
     */
    public function estatisticas()
    {
        return [
            'esta_correta' => $this->estaCorreta(),
            'foi_escolhida_final' => $this->foiEscolhidaComoFinal(),
            'autor_eh_lider' => $this->autorEhLider(),
            'tempo_para_contribuir' => $this->tempoParaContribuir(),
            'posicao_contribuicao' => $this->posicaoContribuicao(),
            'foi_primeira' => $this->foiPrimeiraContribuicao()
        ];
    }

    /**
     * Retorna outras contribuições para a mesma questão
     */
    public function outrasContribuicoes()
    {
        return $this->respostaGrupo->contribuicoes()
            ->where('id', '!=', $this->id)
            ->orderBy('contribuida_em');
    }

    /**
     * Verifica se há conflito com outras contribuições
     */
    public function temConflito()
    {
        $outrasRespostas = $this->outrasContribuicoes()
            ->pluck('resposta_sugerida')
            ->unique();

        return $outrasRespostas->count() > 1 || 
               ($outrasRespostas->count() === 1 && !$outrasRespostas->contains($this->resposta_sugerida));
    }

    /**
     * Retorna o consenso das contribuições (se houver)
     */
    public function consensoGrupo()
    {
        $todasContribuicoes = $this->respostaGrupo->contribuicoes();
        $respostasMaisComuns = $todasContribuicoes
            ->groupBy('resposta_sugerida')
            ->map(function ($grupo) {
                return $grupo->count();
            })
            ->sortDesc();

        if ($respostasMaisComuns->isEmpty()) {
            return null;
        }

        $respostaMaisComum = $respostasMaisComuns->keys()->first();
        $quantidadeMaisComum = $respostasMaisComuns->first();
        $totalContribuicoes = $todasContribuicoes->count();

        return [
            'resposta_consenso' => $respostaMaisComum,
            'quantidade_votos' => $quantidadeMaisComum,
            'total_contribuicoes' => $totalContribuicoes,
            'percentual_consenso' => ($quantidadeMaisComum / $totalContribuicoes) * 100,
            'tem_consenso' => $quantidadeMaisComum > ($totalContribuicoes / 2)
        ];
    }

    /**
     * Accessor para tempo para contribuir formatado
     */
    public function getTempoParaContribuirFormatadoAttribute()
    {
        $segundos = $this->tempoParaContribuir();
        
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
     * Accessor para posição formatada
     */
    public function getPosicaoFormatadaAttribute()
    {
        $posicao = $this->posicaoContribuicao();
        
        $sufixos = [
            1 => 'ª',
            2 => 'ª',
            3 => 'ª'
        ];
        
        $sufixo = $sufixos[$posicao] ?? 'ª';
        return $posicao . $sufixo;
    }

    /**
     * Accessor para data de contribuição formatada
     */
    public function getContribuidaEmFormatadaAttribute()
    {
        return $this->contribuida_em ? $this->contribuida_em->format('d/m/Y H:i:s') : null;
    }

    /**
     * Accessor para status da contribuição
     */
    public function getStatusContribuicaoAttribute()
    {
        $status = [];
        
        if ($this->estaCorreta()) {
            $status[] = 'Correta';
        } else {
            $status[] = 'Incorreta';
        }
        
        if ($this->foiEscolhidaComoFinal()) {
            $status[] = 'Escolhida';
        }
        
        if ($this->autorEhLider()) {
            $status[] = 'Líder';
        }
        
        if ($this->foiPrimeiraContribuicao()) {
            $status[] = 'Primeira';
        }
        
        return implode(', ', $status);
    }
}