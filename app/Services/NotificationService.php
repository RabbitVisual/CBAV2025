<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\NotificationRead;
use App\Models\NotificationPreference;
use App\Models\NotificationTemplate;
use App\Models\QuizAlert;
use App\Models\User;
use App\Models\Membro;
use App\Models\Ministerio;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotificationService
{
    /**
     * Criar notificação para usuário específico
     */
    public static function createForUser($userId, array $data): ?Notification
    {
        try {
            $user = User::find($userId);
            if (!$user) {
                throw new \InvalidArgumentException("User with ID {$userId} not found");
            }

            // Verificar preferências do usuário
            $preferences = static::getUserPreferences($userId);
            if (!$preferences->shouldReceiveNotification($data['category'] ?? null, null, $data['priority'] ?? 'normal')) {
                Log::info("Notification skipped for user {$userId} due to preferences");
                return null;
            }

            // Ajustar canais baseado nas preferências
            $data['channels'] = $preferences->getPreferredChannels($data['category'] ?? null);

            $notification = Notification::createForUser($userId, $data);
            
            // Processar entrega nos canais especificados
            static::processDelivery($notification);

            return $notification;
        } catch (\Exception $e) {
            Log::error('Error creating notification for user: ' . $e->getMessage(), [
                'user_id' => $userId,
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Criar notificação para todos os usuários
     */
    public static function createForAll(array $data): Notification
    {
        try {
            $notification = Notification::createForAll($data);
            
            // Processar entrega para todos os usuários ativos
            static::processDelivery($notification);

            return $notification;
        } catch (\Exception $e) {
            Log::error('Error creating notification for all users: ' . $e->getMessage(), [
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Criar notificação usando template
     */
    public static function createFromTemplate(string $templateName, array $variables = [], array $options = []): Notification
    {
        $template = NotificationTemplate::where('name', $templateName)
                                       ->where('is_active', true)
                                       ->first();

        if (!$template) {
            throw new \InvalidArgumentException("Template '{$templateName}' not found or inactive");
        }

        return $template->createNotification($variables, $options);
    }

    /**
     * Agendar notificação
     */
    public static function schedule(array $data, Carbon $scheduledAt): Notification
    {
        return Notification::schedule($data, $scheduledAt);
    }

    /**
     * Buscar notificações do usuário
     */
    public static function getUserNotifications($userId, array $filters = []): Collection
    {
        $query = Notification::forUser($userId)
                            ->with(['reads' => function ($q) use ($userId) {
                                $q->where('user_id', $userId);
                            }])
                            ->active()
                            ->orderBy('created_at', 'desc');

        // Aplicar filtros
        if (isset($filters['type'])) {
            $query->byType($filters['type']);
        }

        if (isset($filters['category'])) {
            $query->byCategory($filters['category']);
        }

        if (isset($filters['priority'])) {
            $query->byPriority($filters['priority']);
        }

        if (isset($filters['unread_only']) && $filters['unread_only']) {
            $query->unreadForUser($userId);
        }

        if (isset($filters['limit'])) {
            return $query->limit($filters['limit'])->get();
        }

        return $query->get();
    }

    /**
     * Contar notificações não lidas do usuário
     */
    public static function getUnreadCount($userId): int
    {
        return Notification::forUser($userId)
                          ->unreadForUser($userId)
                          ->active()
                          ->count();
    }

    /**
     * Marcar notificação como lida
     */
    public static function markAsRead($notificationId, $userId): bool
    {
        try {
            $notification = Notification::find($notificationId);
            if (!$notification) {
                return false;
            }

            $notification->markAsReadBy($userId);
            return true;
        } catch (\Exception $e) {
            Log::error('Error marking notification as read: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Marcar todas as notificações como lidas
     */
    public static function markAllAsRead($userId): int
    {
        try {
            $notifications = Notification::forUser($userId)
                                       ->unreadForUser($userId)
                                       ->active()
                                       ->get();

            $count = 0;
            foreach ($notifications as $notification) {
                $notification->markAsReadBy($userId);
                $count++;
            }

            return $count;
        } catch (\Exception $e) {
            Log::error('Error marking all notifications as read: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Arquivar notificação
     */
    public static function archive($notificationId, $userId): bool
    {
        try {
            $notification = Notification::find($notificationId);
            if (!$notification) {
                return false;
            }

            $notification->archiveBy($userId);
            return true;
        } catch (\Exception $e) {
            Log::error('Error archiving notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Favoritar notificação
     */
    public static function star($notificationId, $userId): bool
    {
        try {
            $notification = Notification::find($notificationId);
            if (!$notification) {
                return false;
            }

            $notification->starBy($userId);
            return true;
        } catch (\Exception $e) {
            Log::error('Error starring notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Registrar clique em ação
     */
    public static function recordActionClick($notificationId, $userId, array $data = []): bool
    {
        try {
            $notification = Notification::find($notificationId);
            if (!$notification) {
                return false;
            }

            $notification->recordActionClick($userId, $data);
            return true;
        } catch (\Exception $e) {
            Log::error('Error recording action click: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obter preferências do usuário
     */
    public static function getUserPreferences($userId): NotificationPreference
    {
        return NotificationPreference::firstOrCreate(
            ['user_id' => $userId],
            NotificationPreference::getDefaultPreferences()
        );
    }

    /**
     * Atualizar preferências do usuário
     */
    public static function updateUserPreferences($userId, array $preferences): bool
    {
        try {
            $userPreferences = static::getUserPreferences($userId);
            $userPreferences->update($preferences);
            return true;
        } catch (\Exception $e) {
            Log::error('Error updating user preferences: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar notificação (método público para compatibilidade)
     */
    public static function send(Notification $notification): void
    {
        try {
            static::processDelivery($notification);
            $notification->update(['sent_at' => now()]);
            
            Log::info('Notification sent successfully', [
                'notification_id' => $notification->id,
                'channels' => $notification->channels ?? ['database']
            ]);
        } catch (\Exception $e) {
            Log::error('Error sending notification: ' . $e->getMessage(), [
                'notification_id' => $notification->id
            ]);
            throw $e;
        }
    }

    /**
     * Processar entrega da notificação
     */
    private static function processDelivery(Notification $notification): void
    {
        $channels = $notification->channels ?? ['database'];

        foreach ($channels as $channel) {
            try {
                switch ($channel) {
                    case 'database':
                        // Já está salva no banco
                        break;
                    case 'email':
                        static::sendEmail($notification);
                        break;
                    case 'push':
                        static::sendPush($notification);
                        break;
                    case 'sms':
                        static::sendSms($notification);
                        break;
                }
            } catch (\Exception $e) {
                Log::error("Error delivering notification via {$channel}: " . $e->getMessage());
            }
        }
    }

    /**
     * Enviar notificação por email
     */
    private static function sendEmail(Notification $notification): void
    {
        // Implementar envio por email
        Log::info("Email notification sent: {$notification->id}");
    }

    /**
     * Enviar notificação push
     */
    private static function sendPush(Notification $notification): void
    {
        // Implementar envio push
        Log::info("Push notification sent: {$notification->id}");
    }

    /**
     * Enviar notificação por SMS
     */
    private static function sendSms(Notification $notification): void
    {
        // Implementar envio por SMS
        Log::info("SMS notification sent: {$notification->id}");
    }

    /**
     * Processar notificações agendadas
     */
    public static function processScheduledNotifications(): int
    {
        $notifications = Notification::pending()->get();
        $processed = 0;

        foreach ($notifications as $notification) {
            try {
                static::processDelivery($notification);
                $notification->markAsSent();
                $processed++;
            } catch (\Exception $e) {
                $notification->markAsFailed($e->getMessage());
                Log::error("Error processing scheduled notification {$notification->id}: " . $e->getMessage());
            }
        }

        return $processed;
    }

    /**
     * Limpar notificações antigas
     */
    public static function cleanupOldNotifications(int $daysOld = 90): int
    {
        $cutoffDate = now()->subDays($daysOld);
        
        return Notification::where('created_at', '<', $cutoffDate)
                          ->where('is_persistent', false)
                          ->delete();
    }

    /**
     * Obter estatísticas de notificações
     */
    public static function getStatistics($userId = null): array
    {
        $query = $userId ? Notification::forUser($userId) : Notification::query();

        return [
            'total' => $query->count(),
            'unread' => $userId ? static::getUnreadCount($userId) : 0,
            'by_type' => $query->select('type', DB::raw('count(*) as count'))
                              ->groupBy('type')
                              ->pluck('count', 'type')
                              ->toArray(),
            'by_category' => $query->select('category', DB::raw('count(*) as count'))
                                  ->groupBy('category')
                                  ->pluck('count', 'category')
                                  ->toArray(),
            'recent' => $query->where('created_at', '>=', now()->subDays(7))->count()
        ];
    }

    // Métodos específicos para Quiz
    public static function createQuizRecord($userId, $quizSessionId, $score, $category = null): void
    {
        // Criar alerta de quiz
        QuizAlert::createNewRecord($userId, $quizSessionId, $score, $category);

        // Criar notificação usando template
        try {
            static::createFromTemplate('quiz_new_record', [
                'user_name' => User::find($userId)->name,
                'score' => $score,
                'category' => $category ?? 'Geral'
            ], [
                'recipient_type' => 'user',
                'recipient_id' => $userId
            ]);
        } catch (\Exception $e) {
            // Fallback para notificação simples
            static::createForUser($userId, [
                'title' => 'Novo Recorde no Quiz! 🏆',
                'message' => "Parabéns! Você estabeleceu um novo recorde com {$score} pontos!",
                'type' => 'success',
                'category' => 'quiz',
                'priority' => 'high',
                'icon' => 'fas fa-trophy'
            ]);
        }
    }

    public static function createQuizCompletion($userId, $quizSessionId, $score, $totalQuestions): void
    {
        $percentage = round(($score / $totalQuestions) * 100);
        
        // Criar alerta de resultado
        QuizAlert::createResult($userId, $quizSessionId, $score, $totalQuestions, $percentage);

        // Criar notificação
        $encouragement = match(true) {
            $percentage >= 90 => 'Excelente conhecimento da Palavra! 🌟',
            $percentage >= 80 => 'Muito bom! Continue estudando! 📖',
            $percentage >= 70 => 'Bom trabalho! A prática leva à perfeição! 💪',
            default => 'Continue se esforçando! Você está no caminho certo! 🙏'
        };

        static::createForUser($userId, [
            'title' => 'Quiz Concluído! 📚',
            'message' => "Você acertou {$score} de {$totalQuestions} questões ({$percentage}%). {$encouragement}",
            'type' => 'info',
            'category' => 'quiz',
            'priority' => 'normal',
            'icon' => 'fas fa-check-circle',
            'data' => [
                'quiz_session_id' => $quizSessionId,
                'score' => $score,
                'total_questions' => $totalQuestions,
                'percentage' => $percentage
            ]
        ]);
    }

    // Métodos para notificações do sistema
    public static function systemMaintenance($startTime, $duration, $additionalInfo = ''): Notification
    {
        return static::createForAll([
            'title' => 'Manutenção do Sistema 🔧',
            'message' => "O sistema entrará em manutenção {$startTime} e ficará indisponível por aproximadamente {$duration}. {$additionalInfo}",
            'type' => 'warning',
            'category' => 'system',
            'priority' => 'high',
            'icon' => 'fas fa-tools',
            'channels' => ['database', 'email', 'push']
        ]);
    }

    public static function newMember($memberName): Notification
    {
        return static::createForAll([
            'title' => 'Novo Membro na Igreja! 🙏',
            'message' => "Damos as boas-vindas ao novo membro {$memberName} que se juntou à nossa comunidade. Vamos recebê-lo com amor cristão!",
            'type' => 'success',
            'category' => 'ministry',
            'priority' => 'normal',
            'icon' => 'fas fa-user-plus'
        ]);
    }

    public static function urgentAlert($title, $message, $data = []): Notification
    {
        return static::createForAll([
            'title' => $title,
            'message' => $message,
            'type' => 'urgent',
            'category' => 'system',
            'priority' => 'urgent',
            'data' => $data,
            'channels' => ['database', 'push', 'email']
        ]);
    }
}