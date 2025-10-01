<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EbdRespostaGrupo extends Model
{
    use HasFactory;

    protected $table = 'ebd_respostas_grupo';

    protected $fillable = [
        'avaliacao_grupo_id',
        'questao_id',
        'resposta_consenso',
        'discussao',
        'correta',
        'pontuacao_obtida',
        'respondido_por',
        'tempo_inicio',
        'tempo_fim'
    ];

    protected $casts = [
        'discussao' => 'array',
        'correta' => 'boolean',
        'pontuacao_obtida' => 'integer',
        'tempo_inicio' => 'datetime',
        'tempo_fim' => 'datetime'
    ];

    /**
     * Relacionamento com a avaliação do grupo
     */
    public function avaliacaoGrupo(): BelongsTo
    {
        return $this->belongsTo(EbdAvaliacaoGrupo::class, 'avaliacao_grupo_id');
    }

    /**
     * Relacionamento com a questão
     */
    public function questao(): BelongsTo
    {
        return $this->belongsTo(EbdQuestao::class, 'questao_id');
    }

    /**
     * Relacionamento com quem submeteu a resposta
     */
    public function respondidoPor(): BelongsTo
    {
        return $this->belongsTo(EbdAluno::class, 'respondido_por');
    }

    /**
     * Relacionamento com as contribuições individuais
     */
    public function contribuicoes(): HasMany
    {
        return $this->hasMany(EbdContribuicaoResposta::class, 'resposta_grupo_id');
    }

    /**
     * Inicia o tempo de resposta
     */
    public function iniciarTempo(): void
    {
        if (!$this->tempo_inicio) {
            $this->update(['tempo_inicio' => now()]);
        }
    }

    /**
     * Finaliza o tempo de resposta
     */
    public function finalizarTempo(): void
    {
        $this->update(['tempo_fim' => now()]);
    }

    /**
     * Calcula o tempo gasto na resposta em segundos
     */
    public function tempoGastoSegundos(): ?int
    {
        if (!$this->tempo_inicio) {
            return null;
        }

        $fimTempo = $this->tempo_fim ?? now();
        return $this->tempo_inicio->diffInSeconds($fimTempo);
    }

    /**
     * Adiciona uma entrada na discussão
     */
    public function adicionarDiscussao(int $alunoId, string $mensagem, string $tipo = 'discussao'): void
    {
        $discussao = $this->discussao ?? [];
        
        $discussao[] = [
            'aluno_id' => $alunoId,
            'mensagem' => $mensagem,
            'tipo' => $tipo,
            'timestamp' => now()->toISOString()
        ];
        
        $this->update(['discussao' => $discussao]);
    }

    /**
     * Define a resposta final do grupo
     */
    public function definirRespostaFinal(string $resposta, int $respondidoPor): bool
    {
        // Verifica se a resposta está correta
        $correta = $this->verificarResposta($resposta);
        
        // Calcula a pontuação obtida
        $pontuacao = $correta ? $this->questao->pontuacao : 0;
        
        $this->update([
            'resposta_consenso' => $resposta,
            'correta' => $correta,
            'pontuacao_obtida' => $pontuacao,
            'respondido_por' => $respondidoPor
        ]);
        
        $this->finalizarTempo();
        
        // Adiciona na discussão quem submeteu a resposta final
        $this->adicionarDiscussao($respondidoPor, "Resposta final submetida: {$resposta}", 'submissao');
        
        return $correta;
    }

    /**
     * Verifica se a resposta está correta
     */
    private function verificarResposta(string $resposta): bool
    {
        $questao = $this->questao;
        
        switch ($questao->tipo) {
            case 'multipla_escolha':
            case 'verdadeiro_falso':
                return strtolower(trim($resposta)) === strtolower(trim($questao->resposta_correta));
                
            case 'dissertativa':
                // Para questões dissertativas, pode ser necessário avaliação manual
                // Por enquanto, retorna false para avaliação posterior
                return false;
                
            case 'correspondencia':
                // Implementar lógica específica para correspondência
                return $resposta === $questao->resposta_correta;
                
            default:
                return false;
        }
    }

    /**
     * Retorna o histórico da discussão formatado
     */
    public function historicoDiscussao(): array
    {
        $discussao = $this->discussao ?? [];
        $historico = [];
        
        foreach ($discussao as $entrada) {
            $aluno = EbdAluno::find($entrada['aluno_id']);
            
            $historico[] = [
                'aluno_nome' => $aluno ? $aluno->nome : 'Aluno não encontrado',
                'mensagem' => $entrada['mensagem'],
                'tipo' => $entrada['tipo'],
                'timestamp' => $entrada['timestamp'],
                'tempo_formatado' => \Carbon\Carbon::parse($entrada['timestamp'])->format('H:i:s')
            ];
        }
        
        return $historico;
    }

    /**
     * Conta quantos membros participaram da discussão
     */
    public function contarParticipantesDiscussao(): int
    {
        $discussao = $this->discussao ?? [];
        $participantes = [];
        
        foreach ($discussao as $entrada) {
            $participantes[$entrada['aluno_id']] = true;
        }
        
        return count($participantes);
    }

    /**
     * Verifica se um aluno específico participou da discussão
     */
    public function alunoParticipouDiscussao(int $alunoId): bool
    {
        $discussao = $this->discussao ?? [];
        
        foreach ($discussao as $entrada) {
            if ($entrada['aluno_id'] === $alunoId) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Retorna estatísticas da resposta
     */
    public function estatisticas(): array
    {
        return [
            'correta' => $this->correta,
            'pontuacao_obtida' => $this->pontuacao_obtida,
            'pontuacao_maxima' => $this->questao->pontuacao,
            'tempo_gasto_segundos' => $this->tempoGastoSegundos(),
            'participantes_discussao' => $this->contarParticipantesDiscussao(),
            'total_contribuicoes' => $this->contribuicoes()->count(),
            'contribuicoes_aceitas' => $this->contribuicoes()->where('aceita_pelo_grupo', true)->count(),
            'tem_resposta_final' => !empty($this->resposta_consenso),
            'respondido_por_nome' => $this->respondidoPor ? $this->respondidoPor->nome : null,
            'total_mensagens_discussao' => count($this->discussao ?? [])
        ];
    }

    /**
     * Scope para respostas corretas
     */
    public function scopeCorretas($query)
    {
        return $query->where('correta', true);
    }

    /**
     * Scope para respostas incorretas
     */
    public function scopeIncorretas($query)
    {
        return $query->where('correta', false);
    }

    /**
     * Scope para respostas com discussão ativa
     */
    public function scopeComDiscussao($query)
    {
        return $query->whereNotNull('discussao');
    }
}