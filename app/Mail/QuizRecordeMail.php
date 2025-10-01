<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\EbdQuizSessao;
use App\Models\User;

class QuizRecordeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sessao;
    public $tipoRecorde;
    public $valor;
    public $isRecordeGlobal;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(EbdQuizSessao $sessao, string $tipoRecorde, float $valor, bool $isRecordeGlobal = false)
    {
        $this->sessao = $sessao;
        $this->tipoRecorde = $tipoRecorde;
        $this->valor = $valor;
        $this->isRecordeGlobal = $isRecordeGlobal;
        $this->user = $sessao->user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $tipoTexto = $this->tipoRecorde === 'pontuacao' ? 'Pontuação Total' : 'Percentual de Acertos';
        $titulo = $this->isRecordeGlobal 
            ? "🏆 Novo Recorde Global no Quiz Bíblico!" 
            : "🎉 Parabéns! Novo Recorde Pessoal!";

        return new Envelope(
            subject: $titulo,
            tags: ['quiz', 'recorde', 'cbav'],
            metadata: [
                'sessao_id' => $this->sessao->id,
                'user_id' => $this->user->id,
                'tipo_recorde' => $this->tipoRecorde,
                'valor' => $this->valor,
                'is_global' => $this->isRecordeGlobal,
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $tipoTexto = $this->tipoRecorde === 'pontuacao' ? 'pontuação total' : 'percentual de acertos';
        $valorTexto = $this->tipoRecorde === 'pontuacao' ? $this->valor . ' pontos' : number_format($this->valor, 1) . '%';
        
        $nivelTexto = ucfirst($this->sessao->nivel);
        $categoriaTexto = str_replace('_', ' ', ucfirst($this->sessao->categoria));
        
        $detalhesSessao = [
            'Nível' => $nivelTexto,
            'Categoria' => $categoriaTexto,
            'Perguntas Respondidas' => $this->sessao->total_perguntas,
            'Acertos' => $this->sessao->acertos,
            'Tempo de Resposta' => $this->sessao->duracao_formatada,
            'Data da Sessão' => $this->sessao->created_at->format('d/m/Y H:i'),
        ];

        return new Content(
            view: 'emails.quiz.recorde',
            with: [
                'user' => $this->user,
                'sessao' => $this->sessao,
                'tipoRecorde' => $tipoTexto,
                'valorRecorde' => $valorTexto,
                'isRecordeGlobal' => $this->isRecordeGlobal,
                'detalhesSessao' => $detalhesSessao,
                'rankingPosition' => $this->getRankingPosition(),
                'proximoDesafio' => $this->getProximoDesafio(),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Obter posição no ranking
     */
    private function getRankingPosition(): ?int
    {
        if ($this->tipoRecorde === 'pontuacao') {
            $position = \App\Models\EbdQuizSessao::where('pontuacao_total', '>', $this->sessao->pontuacao_total)->count() + 1;
            return $position;
        }
        
        return null;
    }

    /**
     * Obter próximo desafio sugerido
     */
    private function getProximoDesafio(): array
    {
        $niveis = ['facil', 'medio', 'dificil'];
        $nivelAtual = array_search($this->sessao->nivel, $niveis);
        
        if ($nivelAtual < count($niveis) - 1) {
            $proximoNivel = $niveis[$nivelAtual + 1];
            return [
                'nivel' => ucfirst($proximoNivel),
                'descricao' => "Tente o nível {$proximoNivel} para um desafio ainda maior!",
                'url' => route('member.ebd.quiz-biblico.index')
            ];
        }
        
        return [
            'nivel' => 'Todos os Níveis',
            'descricao' => 'Você já dominou todos os níveis! Tente diferentes categorias.',
            'url' => route('member.ebd.quiz-biblico.index')
        ];
    }
} 