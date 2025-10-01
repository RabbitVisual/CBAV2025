<?php

namespace App\Observers;

use App\Models\EbdQuizSessao;
use App\Models\Notification;
use App\Services\QuizAlertService;
use App\Services\NotificationService;
use App\Helpers\LogHelper;
use Illuminate\Support\Facades\Log;

class EbdQuizSessaoObserver
{
    /**
     * Handle the EbdQuizSessao "created" event.
     */
    public function created(EbdQuizSessao $sessao): void
    {
        try {
            // Verificar se é um novo recorde (funcionalidade existente)
            $this->verificarNovoRecorde($sessao);
            
            // Log da atividade
            LogHelper::audit('quiz_sessao_criada', $sessao, [
                'user_id' => $sessao->user_id,
                'pontuacao' => $sessao->pontuacao_total,
                'percentual' => $sessao->percentual
            ]);

            // Nova funcionalidade: notificação de início
            if ($sessao->user_id) {
                NotificationService::createForUser($sessao->user_id, [
                    'title' => 'Quiz Iniciado! 📚',
                    'message' => 'Boa sorte no seu quiz bíblico! Que Deus ilumine sua mente.',
                    'type' => 'info',
                    'category' => 'quiz',
                    'priority' => 'low',
                    'icon' => 'fas fa-play-circle',
                    'data' => [
                        'quiz_session_id' => $sessao->id,
                        'action_type' => 'quiz_started'
                    ],
                    'expires_at' => now()->addMinutes(30)
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error in EbdQuizSessao created observer: ' . $e->getMessage());
        }
    }

    /**
     * Handle the EbdQuizSessao "updated" event.
     */
    public function updated(EbdQuizSessao $sessao): void
    {
        try {
            // Se a sessão foi finalizada, verificar recordes (funcionalidade existente)
            if ($sessao->wasChanged('finalizado_em') && $sessao->finalizado_em) {
                $this->verificarNovoRecorde($sessao);
                
                // Nova funcionalidade: processar alertas de quiz
                QuizAlertService::processQuizCompletion($sessao->id, $sessao->user_id);
            }

            // Verificar mudanças na pontuação
            if ($sessao->wasChanged('pontuacao_total') && $sessao->pontuacao_total > 0) {
                $this->handleScoreUpdate($sessao);
            }
        } catch (\Exception $e) {
            Log::error('Error in EbdQuizSessao updated observer: ' . $e->getMessage());
        }
    }

    /**
     * Verificar se é um novo recorde
     */
    private function verificarNovoRecorde(EbdQuizSessao $sessao): void
    {
        if (!$sessao->finalizado_em) {
            return;
        }

        // Verificar recorde de pontuação total
        $melhorPontuacao = EbdQuizSessao::where('user_id', '!=', $sessao->user_id)
                                        ->max('pontuacao_total') ?? 0;
        
        if ($sessao->pontuacao_total > $melhorPontuacao) {
            $this->notificarNovoRecorde($sessao, 'pontuacao', $sessao->pontuacao_total);
        }

        // Verificar recorde de percentual
        $melhorPercentual = EbdQuizSessao::where('user_id', '!=', $sessao->user_id)
                                        ->max('percentual') ?? 0;
        
        if ($sessao->percentual > $melhorPercentual) {
            $this->notificarNovoRecorde($sessao, 'percentual', $sessao->percentual);
        }

        // Verificar recorde pessoal
        $melhorPontuacaoPessoal = EbdQuizSessao::where('user_id', $sessao->user_id)
                                               ->where('id', '!=', $sessao->id)
                                               ->max('pontuacao_total') ?? 0;
        
        if ($sessao->pontuacao_total > $melhorPontuacaoPessoal) {
            $this->notificarRecordePessoal($sessao, 'pontuacao', $sessao->pontuacao_total);
        }
    }

    /**
     * Notificar novo recorde
     */
    private function notificarNovoRecorde(EbdQuizSessao $sessao, string $tipo, float $valor): void
    {
        if (!config('quiz.notificar_recordes', true)) {
            return;
        }

        $tipoTexto = $tipo === 'pontuacao' ? 'pontuação total' : 'percentual de acertos';
        $valorTexto = $tipo === 'pontuacao' ? $valor . ' pontos' : number_format($valor, 1) . '%';

        // Criar notificação no banco para admins
        $admins = \App\Models\User::whereHas('roles', function($query) {
            $query->whereIn('name', ['Super Admin', 'Admin', 'Pastor']);
        })->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'titulo' => '🏆 Novo Recorde Global no Quiz Bíblico!',
                'mensagem' => "O usuário {$sessao->user->name} estabeleceu um novo recorde de {$tipoTexto}: {$valorTexto}",
                'tipo' => 'quiz_recorde_global',
                'dados_extras' => [
                    'sessao_id' => $sessao->id,
                    'user_id' => $sessao->user_id,
                    'tipo_recorde' => $tipo,
                    'valor' => $valor,
                    'is_global' => true
                ],
                'lida' => false
            ]);
        }

        // Enviar e-mail de notificação
        try {
            \App\Jobs\EnviarNotificacaoQuizRecorde::dispatch($sessao, $tipo, $valor, true);
        } catch (\Exception $e) {
            LogHelper::error('Erro ao agendar envio de e-mail de recorde global', [
                'sessao_id' => $sessao->id,
                'error' => $e->getMessage()
            ]);
        }

        // Log do recorde
        LogHelper::audit('quiz_novo_recorde_global', $sessao, [
            'user_id' => $sessao->user_id,
            'tipo_recorde' => $tipo,
            'valor' => $valor,
            'email_enviado' => true
        ]);
    }

    /**
     * Notificar recorde pessoal
     */
    private function notificarRecordePessoal(EbdQuizSessao $sessao, string $tipo, float $valor): void
    {
        $tipoTexto = $tipo === 'pontuacao' ? 'pontuação total' : 'percentual de acertos';
        $valorTexto = $tipo === 'pontuacao' ? $valor . ' pontos' : number_format($valor, 1) . '%';

        // Criar notificação no banco para o usuário
        Notification::create([
            'user_id' => $sessao->user_id,
            'titulo' => '🎉 Parabéns! Novo Recorde Pessoal!',
            'mensagem' => "Você estabeleceu um novo recorde pessoal de {$tipoTexto}: {$valorTexto}",
            'tipo' => 'quiz_recorde_pessoal',
            'dados_extras' => [
                'sessao_id' => $sessao->id,
                'tipo_recorde' => $tipo,
                'valor' => $valor,
                'is_global' => false
            ],
            'lida' => false
        ]);

        // Enviar e-mail de notificação pessoal
        try {
            \App\Jobs\EnviarNotificacaoQuizRecorde::dispatch($sessao, $tipo, $valor, false);
        } catch (\Exception $e) {
            LogHelper::error('Erro ao agendar envio de e-mail de recorde pessoal', [
                'sessao_id' => $sessao->id,
                'user_id' => $sessao->user_id,
                'error' => $e->getMessage()
            ]);
        }

        // Log do recorde pessoal
        LogHelper::audit('quiz_novo_recorde_pessoal', $sessao, [
            'user_id' => $sessao->user_id,
            'tipo_recorde' => $tipo,
            'valor' => $valor,
            'email_enviado' => true
        ]);
    }

    /**
     * Processar atualização de pontuação (nova funcionalidade)
     */
    private function handleScoreUpdate(EbdQuizSessao $sessao): void
    {
        try {
            // Verificar marcos importantes durante o quiz
            $currentScore = $sessao->pontuacao_total;
            $previousScore = $sessao->getOriginal('pontuacao_total') ?? 0;

            // Marcos de pontuação (a cada 5 pontos)
            $milestones = [5, 10, 15, 20, 25, 30];
            
            foreach ($milestones as $milestone) {
                if ($currentScore >= $milestone && $previousScore < $milestone) {
                    $this->createMilestoneNotification($sessao, $milestone);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error handling score update: ' . $e->getMessage());
        }
    }

    /**
     * Criar notificação de marco (nova funcionalidade)
     */
    private function createMilestoneNotification(EbdQuizSessao $sessao, int $milestone): void
    {
        try {
            $messages = [
                5 => 'Ótimo começo! Continue assim! 👍',
                10 => 'Excelente! Você está indo muito bem! 🌟',
                15 => 'Impressionante! Seu conhecimento é notável! 🎯',
                20 => 'Fantástico! Você é um verdadeiro estudioso! 📚',
                25 => 'Extraordinário! Poucos chegam até aqui! 🏆',
                30 => 'Lendário! Você é um mestre da Palavra! 👑'
            ];

            $message = $messages[$milestone] ?? 'Parabéns pelo marco alcançado!';

            NotificationService::createForUser($sessao->user_id, [
                'title' => "Marco: {$milestone} Pontos! 🎉",
                'message' => $message,
                'type' => 'success',
                'category' => 'quiz',
                'priority' => 'normal',
                'icon' => 'fas fa-trophy',
                'data' => [
                    'quiz_session_id' => $sessao->id,
                    'milestone' => $milestone,
                    'action_type' => 'milestone_reached'
                ],
                'expires_at' => now()->addHours(2)
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating milestone notification: ' . $e->getMessage());
        }
    }
}