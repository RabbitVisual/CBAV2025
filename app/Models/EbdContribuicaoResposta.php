<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EbdContribuicaoResposta extends Model
{
    use HasFactory;

    protected $table = 'ebd_contribuicoes_resposta';

    protected $fillable = [
        'resposta_grupo_id',
        'aluno_id',
        'contribuicao',
        'tipo',
        'aceita_pelo_grupo'
    ];

    protected $casts = [
        'aceita_pelo_grupo' => 'boolean'
    ];

    /**
     * Tipos de contribuição disponíveis
     */
    const TIPOS = [
        'sugestao' => 'Sugestão',
        'discussao' => 'Discussão',
        'voto' => 'Voto',
        'lideranca' => 'Liderança'
    ];

    /**
     * Relacionamento com a resposta do grupo
     */
    public function respostaGrupo(): BelongsTo
    {
        return $this->belongsTo(EbdRespostaGrupo::class, 'resposta_grupo_id');
    }

    /**
     * Relacionamento com o aluno que fez a contribuição
     */
    public function aluno(): BelongsTo
    {
        return $this->belongsTo(EbdAluno::class, 'aluno_id');
    }

    /**
     * Aceita a contribuição
     */
    public function aceitar(): void
    {
        $this->update(['aceita_pelo_grupo' => true]);
    }

    /**
     * Rejeita a contribuição
     */
    public function rejeitar(): void
    {
        $this->update(['aceita_pelo_grupo' => false]);
    }

    /**
     * Scope para contribuições aceitas
     */
    public function scopeAceitas($query)
    {
        return $query->where('aceita_pelo_grupo', true);
    }

    /**
     * Scope para contribuições rejeitadas
     */
    public function scopeRejeitadas($query)
    {
        return $query->where('aceita_pelo_grupo', false);
    }

    /**
     * Scope para contribuições pendentes
     */
    public function scopePendentes($query)
    {
        return $query->whereNull('aceita_pelo_grupo');
    }

    /**
     * Scope por tipo de contribuição
     */
    public function scopePorTipo($query, string $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope para sugestões
     */
    public function scopeSugestoes($query)
    {
        return $query->where('tipo', 'sugestao');
    }

    /**
     * Scope para discussões
     */
    public function scopeDiscussoes($query)
    {
        return $query->where('tipo', 'discussao');
    }

    /**
     * Scope para votos
     */
    public function scopeVotos($query)
    {
        return $query->where('tipo', 'voto');
    }

    /**
     * Scope para contribuições de liderança
     */
    public function scopeLideranca($query)
    {
        return $query->where('tipo', 'lideranca');
    }

    /**
     * Verifica se a contribuição é uma sugestão
     */
    public function ehSugestao(): bool
    {
        return $this->tipo === 'sugestao';
    }

    /**
     * Verifica se a contribuição é uma discussão
     */
    public function ehDiscussao(): bool
    {
        return $this->tipo === 'discussao';
    }

    /**
     * Verifica se a contribuição é um voto
     */
    public function ehVoto(): bool
    {
        return $this->tipo === 'voto';
    }

    /**
     * Verifica se a contribuição é de liderança
     */
    public function ehLideranca(): bool
    {
        return $this->tipo === 'lideranca';
    }

    /**
     * Retorna o nome do tipo formatado
     */
    public function tipoFormatado(): string
    {
        return self::TIPOS[$this->tipo] ?? $this->tipo;
    }

    /**
     * Verifica se o aluno é o líder do grupo
     */
    public function alunoEhLider(): bool
    {
        $grupo = $this->respostaGrupo->avaliacaoGrupo->grupo;
        return $grupo->lider_id === $this->aluno_id;
    }

    /**
     * Retorna informações sobre o status da contribuição
     */
    public function statusInfo(): array
    {
        if (is_null($this->aceita_pelo_grupo)) {
            return [
                'status' => 'pendente',
                'label' => 'Pendente',
                'cor' => 'yellow'
            ];
        }

        if ($this->aceita_pelo_grupo) {
            return [
                'status' => 'aceita',
                'label' => 'Aceita',
                'cor' => 'green'
            ];
        }

        return [
            'status' => 'rejeitada',
            'label' => 'Rejeitada',
            'cor' => 'red'
        ];
    }

    /**
     * Retorna estatísticas da contribuição
     */
    public function estatisticas(): array
    {
        return [
            'tipo' => $this->tipo,
            'tipo_formatado' => $this->tipoFormatado(),
            'aceita' => $this->aceita_pelo_grupo,
            'status_info' => $this->statusInfo(),
            'aluno_eh_lider' => $this->alunoEhLider(),
            'aluno_nome' => $this->aluno->nome,
            'data_contribuicao' => $this->created_at->format('d/m/Y H:i'),
            'tamanho_contribuicao' => strlen($this->contribuicao)
        ];
    }

    /**
     * Calcula pontuação da contribuição baseada no tipo e aceitação
     */
    public function calcularPontuacao(): int
    {
        if (!$this->aceita_pelo_grupo) {
            return 0;
        }

        $pontuacoes = [
            'sugestao' => 2,
            'discussao' => 1,
            'voto' => 1,
            'lideranca' => 3
        ];

        return $pontuacoes[$this->tipo] ?? 1;
    }

    /**
     * Verifica se a contribuição pode ser editada
     */
    public function podeSerEditada(): bool
    {
        // Não pode editar se já foi aceita ou rejeitada
        if (!is_null($this->aceita_pelo_grupo)) {
            return false;
        }

        // Não pode editar se a avaliação já foi concluída
        $avaliacaoGrupo = $this->respostaGrupo->avaliacaoGrupo;
        if ($avaliacaoGrupo->status === 'concluida') {
            return false;
        }

        // Pode editar apenas nas primeiras 5 minutos
        return $this->created_at->diffInMinutes(now()) <= 5;
    }

    /**
     * Verifica se a contribuição pode ser excluída
     */
    public function podeSerExcluida(): bool
    {
        // Mesmas regras da edição
        return $this->podeSerEditada();
    }
}