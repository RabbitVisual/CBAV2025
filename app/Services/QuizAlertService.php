<?php

namespace App\Services;

use App\Models\QuizAlert;
use App\Models\Notification;
use App\Models\User;
use App\Models\EbdQuizSessao;
use App\Models\EbdQuizResposta;
use App\Models\EbdQuizPergunta;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QuizAlertService
{
    /**
     * Processar resultado do quiz e criar alertas apropriados
     */
    public static function processQuizCompletion($quizSessionId, $userId): void
    {
        try {
            $session = EbdQuizSessao::find($quizSessionId);
            if (!$session) {
                Log::warning("Quiz session {$quizSessionId} not found");
                return;
            }

            $user = User::find($userId);
            if (!$user) {
                Log::warning("User {$userId} not found");
                return;
            }

            // Calcular estatísticas do quiz
            $stats = static::calculateQuizStats($session, $userId);
            
            // Verificar se é um novo recorde
            if (static::isNewRecord($userId, $stats['score'], $session->categoria ?? 'geral')) {
                static::createNewRecordAlert($userId, $quizSessionId, $stats);
            }

            // Criar alerta de conclusão
            static::createCompletionAlert($userId, $quizSessionId, $stats);

            // Verificar conquistas especiais
            static::checkAchievements($userId, $stats);

            // Verificar sequências (streaks)
            static::checkStreaks($userId);

            // Atualizar estatísticas do usuário
            static::updateUserStats($userId, $stats);

        } catch (\Exception $e) {
            Log::error('Error processing quiz completion: ' . $e->getMessage(), [
                'quiz_session_id' => $quizSessionId,
                'user_id' => $userId
            ]);
        }
    }

    /**
     * Criar alerta de novo recorde
     */
    public static function createNewRecordAlert($userId, $quizSessionId, $stats): void
    {
        try {
            $user = User::find($userId);
            $session = EbdQuizSessao::find($quizSessionId);
            
            // Criar alerta específico de quiz
            $alert = QuizAlert::createNewRecord(
                $userId,
                $quizSessionId,
                $stats['score'],
                $session->categoria ?? 'Geral'
            );

            // Criar notificação usando template
            NotificationService::createFromTemplate('quiz_new_record', [
                'user_name' => $user->name,
                'score' => $stats['score'],
                'category' => $session->categoria ?? 'Geral',
                'percentage' => $stats['percentage']
            ], [
                'recipient_type' => 'user',
                'recipient_id' => $userId,
                'data' => [
                    'quiz_session_id' => $quizSessionId,
                    'alert_id' => $alert->id,
                    'achievement_type' => 'new_record'
                ]
            ]);

            // Notificar administradores sobre o recorde
            static::notifyAdminsAboutRecord($user, $stats, $session);

        } catch (\Exception $e) {
            Log::error('Error creating new record alert: ' . $e->getMessage());
        }
    }

    /**
     * Criar alerta de conclusão do quiz
     */
    public static function createCompletionAlert($userId, $quizSessionId, $stats): void
    {
        try {
            $session = EbdQuizSessao::find($quizSessionId);
            
            // Criar alerta de resultado
            QuizAlert::createResult(
                $userId,
                $quizSessionId,
                $stats['score'],
                $stats['total_questions'],
                $stats['percentage']
            );

            // Criar notificação de conclusão
            $encouragement = static::getEncouragementMessage($stats['percentage']);
            
            NotificationService::createForUser($userId, [
                'title' => 'Quiz Concluído! 📚',
                'message' => "Você acertou {$stats['score']} de {$stats['total_questions']} questões ({$stats['percentage']}%). {$encouragement}",
                'type' => static::getCompletionType($stats['percentage']),
                'category' => 'quiz',
                'priority' => 'normal',
                'icon' => 'fas fa-check-circle',
                'data' => [
                    'quiz_session_id' => $quizSessionId,
                    'stats' => $stats,
                    'category' => $session->categoria ?? 'Geral'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating completion alert: ' . $e->getMessage());
        }
    }

    /**
     * Verificar e criar alertas de conquistas
     */
    public static function checkAchievements($userId, $stats): void
    {
        try {
            // Quiz perfeito (100%)
            if ($stats['percentage'] == 100) {
                static::createPerfectScoreAchievement($userId, $stats);
            }

            // Primeira vez acima de 90%
            if ($stats['percentage'] >= 90 && !static::hasAchievement($userId, 'first_90_percent')) {
                static::createFirstHighScoreAchievement($userId, $stats);
            }

            // Verificar total de quizzes completados
            $totalCompleted = static::getTotalQuizzesCompleted($userId);
            static::checkMilestoneAchievements($userId, $totalCompleted);

            // Verificar conquistas de categoria
            static::checkCategoryAchievements($userId, $stats);

        } catch (\Exception $e) {
            Log::error('Error checking achievements: ' . $e->getMessage());
        }
    }

    /**
     * Verificar sequências (streaks)
     */
    public static function checkStreaks($userId): void
    {
        try {
            // Sequência diária
            $dailyStreak = static::calculateDailyStreak($userId);
            if ($dailyStreak > 0 && $dailyStreak % 7 == 0) { // A cada 7 dias
                QuizAlert::createStreakAlert($userId, $dailyStreak, 'daily');
            }

            // Sequência de quizzes perfeitos
            $perfectStreak = static::calculatePerfectStreak($userId);
            if ($perfectStreak >= 3) {
                QuizAlert::createStreakAlert($userId, $perfectStreak, 'perfect');
            }

        } catch (\Exception $e) {
            Log::error('Error checking streaks: ' . $e->getMessage());
        }
    }

    /**
     * Criar lembrete para participar do quiz
     */
    public static function createParticipationReminder($userId, $reminderType = 'daily'): void
    {
        try {
            $user = User::find($userId);
            if (!$user) return;

            $lastQuiz = static::getLastQuizDate($userId);
            $daysSinceLastQuiz = $lastQuiz ? now()->diffInDays($lastQuiz) : null;

            $title = match($reminderType) {
                'daily' => 'Hora do Quiz Bíblico! 📖',
                'weekly' => 'Que tal um Quiz esta semana? 🤔',
                'comeback' => 'Sentimos sua falta! 💙',
                default => 'Quiz Bíblico Disponível! 📚'
            };

            $message = match($reminderType) {
                'daily' => 'Que tal testar seus conhecimentos bíblicos hoje? Novos quizzes estão disponíveis!',
                'weekly' => 'Você não participa dos quizzes há uma semana. Vamos estudar a Palavra juntos?',
                'comeback' => "Você não participa há {$daysSinceLastQuiz} dias. Que tal voltar a estudar conosco?",
                default => 'Novos quizzes bíblicos estão disponíveis. Venha testar seus conhecimentos!'
            };

            QuizAlert::createReminder($userId, $title, $message, [
                'reminder_type' => $reminderType,
                'days_since_last' => $daysSinceLastQuiz,
                'last_quiz_date' => $lastQuiz?->toISOString()
            ]);

            // Criar notificação também
            NotificationService::createForUser($userId, [
                'title' => $title,
                'message' => $message,
                'type' => 'info',
                'category' => 'quiz',
                'priority' => 'normal',
                'icon' => 'fas fa-book-open',
                'action_url' => route('member.ebd.quiz-biblico.index'),
                'action_text' => 'Participar do Quiz',
                'data' => [
                    'reminder_type' => $reminderType,
                    'auto_generated' => true
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating participation reminder: ' . $e->getMessage());
        }
    }

    /**
     * Processar lembretes automáticos
     */
    public static function processAutomaticReminders(): int
    {
        try {
            $processed = 0;
            
            // Usuários que não participaram hoje
            $inactiveToday = static::getUsersInactiveToday();
            foreach ($inactiveToday as $userId) {
                static::createParticipationReminder($userId, 'daily');
                $processed++;
            }

            // Usuários que não participaram há uma semana
            $inactiveWeek = static::getUsersInactiveForDays(7);
            foreach ($inactiveWeek as $userId) {
                static::createParticipationReminder($userId, 'weekly');
                $processed++;
            }

            // Usuários que não participaram há muito tempo
            $inactiveLong = static::getUsersInactiveForDays(30);
            foreach ($inactiveLong as $userId) {
                static::createParticipationReminder($userId, 'comeback');
                $processed++;
            }

            return $processed;
        } catch (\Exception $e) {
            Log::error('Error processing automatic reminders: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Obter estatísticas de alertas de quiz
     */
    public static function getQuizAlertStats($userId = null): array
    {
        try {
            $query = $userId ? QuizAlert::forUser($userId) : QuizAlert::query();

            return [
                'total' => $query->count(),
                'unread' => $query->unread()->count(),
                'by_type' => $query->select('alert_type', DB::raw('count(*) as count'))
                                  ->groupBy('alert_type')
                                  ->pluck('count', 'alert_type'),
                'recent' => $query->recent(7)->count(),
                'achievements' => $query->byType('achievement')->count(),
                'records' => $query->byType('new_record')->count()
            ];
        } catch (\Exception $e) {
            Log::error('Error getting quiz alert stats: ' . $e->getMessage());
            return [];
        }
    }

    // Métodos auxiliares privados
    private static function calculateQuizStats($session, $userId): array
    {
        $respostas = EbdQuizResposta::where('sessao_id', $session->id)
                                   ->where('user_id', $userId)
                                   ->get();

        $totalQuestions = $respostas->count();
        $correctAnswers = $respostas->where('correta', true)->count();
        $percentage = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100) : 0;

        return [
            'score' => $correctAnswers,
            'total_questions' => $totalQuestions,
            'percentage' => $percentage,
            'time_taken' => $session->tempo_gasto ?? 0,
            'category' => $session->categoria ?? 'geral'
        ];
    }

    private static function isNewRecord($userId, $score, $category): bool
    {
        $bestScore = EbdQuizSessao::where('user_id', $userId)
                                 ->where('categoria', $category)
                                 ->max('pontuacao');

        return $score > ($bestScore ?? 0);
    }

    private static function getEncouragementMessage($percentage): string
    {
        return match(true) {
            $percentage >= 95 => 'Perfeito! Você é um verdadeiro conhecedor da Palavra! 🌟',
            $percentage >= 90 => 'Excelente conhecimento da Palavra de Deus! 🙌',
            $percentage >= 80 => 'Muito bom! Continue estudando as Escrituras! 📖',
            $percentage >= 70 => 'Bom trabalho! A prática leva à perfeição! 💪',
            $percentage >= 60 => 'Continue se esforçando! Você está no caminho certo! 🙏',
            default => 'Não desista! Cada tentativa é um aprendizado na Palavra! ❤️'
        };
    }

    private static function getCompletionType($percentage): string
    {
        return match(true) {
            $percentage >= 90 => 'success',
            $percentage >= 70 => 'info',
            $percentage >= 50 => 'warning',
            default => 'error'
        };
    }

    private static function createPerfectScoreAchievement($userId, $stats): void
    {
        QuizAlert::createAchievement(
            $userId,
            'perfect_score',
            'Quiz Perfeito! 💎',
            'Parabéns! Você acertou todas as questões! Seu conhecimento da Palavra é exemplar!',
            $stats
        );
    }

    private static function createFirstHighScoreAchievement($userId, $stats): void
    {
        QuizAlert::createAchievement(
            $userId,
            'first_90_percent',
            'Primeira Nota Alta! ⭐',
            'Parabéns! Você alcançou mais de 90% pela primeira vez! Continue estudando!',
            $stats
        );
    }

    private static function hasAchievement($userId, $achievementType): bool
    {
        return QuizAlert::where('user_id', $userId)
                       ->where('alert_type', 'achievement')
                       ->whereJsonContains('quiz_data->achievement_type', $achievementType)
                       ->exists();
    }

    private static function getTotalQuizzesCompleted($userId): int
    {
        return EbdQuizSessao::where('user_id', $userId)
                           ->where('finalizada', true)
                           ->count();
    }

    private static function checkMilestoneAchievements($userId, $total): void
    {
        $milestones = [10, 25, 50, 100, 250, 500];
        
        foreach ($milestones as $milestone) {
            if ($total == $milestone && !static::hasAchievement($userId, "milestone_{$milestone}")) {
                QuizAlert::createAchievement(
                    $userId,
                    "milestone_{$milestone}",
                    "Marco Alcançado: {$milestone} Quizzes! 🏆",
                    "Incrível! Você completou {$milestone} quizzes bíblicos! Sua dedicação é inspiradora!",
                    ['milestone' => $milestone, 'total_completed' => $total]
                );
            }
        }
    }

    private static function checkCategoryAchievements($userId, $stats): void
    {
        // Implementar conquistas específicas por categoria
        $category = $stats['category'];
        $categoryCount = EbdQuizSessao::where('user_id', $userId)
                                     ->where('categoria', $category)
                                     ->count();

        if ($categoryCount == 10 && !static::hasAchievement($userId, "category_{$category}_10")) {
            QuizAlert::createAchievement(
                $userId,
                "category_{$category}_10",
                "Especialista em {$category}! 🎓",
                "Você completou 10 quizzes na categoria {$category}! Você está se tornando um especialista!",
                ['category' => $category, 'count' => $categoryCount]
            );
        }
    }

    private static function calculateDailyStreak($userId): int
    {
        $sessions = EbdQuizSessao::where('user_id', $userId)
                                ->where('finalizada', true)
                                ->orderBy('created_at', 'desc')
                                ->get();

        $streak = 0;
        $currentDate = now()->startOfDay();

        foreach ($sessions as $session) {
            $sessionDate = $session->created_at->startOfDay();
            
            if ($sessionDate->eq($currentDate)) {
                $streak++;
                $currentDate = $currentDate->subDay();
            } else {
                break;
            }
        }

        return $streak;
    }

    private static function calculatePerfectStreak($userId): int
    {
        $recentSessions = EbdQuizSessao::where('user_id', $userId)
                                      ->where('finalizada', true)
                                      ->orderBy('created_at', 'desc')
                                      ->limit(10)
                                      ->get();

        $streak = 0;
        foreach ($recentSessions as $session) {
            if ($session->pontuacao_percentual == 100) {
                $streak++;
            } else {
                break;
            }
        }

        return $streak;
    }

    private static function getLastQuizDate($userId): ?Carbon
    {
        $lastSession = EbdQuizSessao::where('user_id', $userId)
                                   ->where('finalizada', true)
                                   ->orderBy('created_at', 'desc')
                                   ->first();

        return $lastSession?->created_at;
    }

    private static function getUsersInactiveToday(): array
    {
        $activeToday = EbdQuizSessao::whereDate('created_at', today())
                                   ->pluck('user_id')
                                   ->unique()
                                   ->toArray();

        return User::whereNotIn('id', $activeToday)
                  ->where('active', true)
                  ->pluck('id')
                  ->toArray();
    }

    private static function getUsersInactiveForDays($days): array
    {
        $cutoffDate = now()->subDays($days);
        
        $activeRecently = EbdQuizSessao::where('created_at', '>=', $cutoffDate)
                                      ->pluck('user_id')
                                      ->unique()
                                      ->toArray();

        return User::whereNotIn('id', $activeRecently)
                  ->where('active', true)
                  ->pluck('id')
                  ->toArray();
    }

    private static function updateUserStats($userId, $stats): void
    {
        // Implementar atualização de estatísticas do usuário
        // Pode ser feito em uma tabela separada de estatísticas
    }

    private static function notifyAdminsAboutRecord($user, $stats, $session): void
    {
        try {
            $admins = User::role('admin')->get();
            
            foreach ($admins as $admin) {
                NotificationService::createForUser($admin->id, [
                    'title' => 'Novo Recorde no Quiz! 🏆',
                    'message' => "{$user->name} estabeleceu um novo recorde com {$stats['score']} pontos na categoria {$session->categoria}!",
                    'type' => 'success',
                    'category' => 'quiz',
                    'priority' => 'normal',
                    'icon' => 'fas fa-trophy',
                    'data' => [
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'score' => $stats['score'],
                        'category' => $session->categoria,
                        'record_type' => 'quiz_record'
                    ]
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error notifying admins about record: ' . $e->getMessage());
        }
    }
}