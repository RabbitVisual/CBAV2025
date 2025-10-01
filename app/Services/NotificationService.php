<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\GeneralPurposeNotification;
use Illuminate\Support\Facades\Notification as LaravelNotification;
use Illuminate\Support\Collection;

class NotificationService
{
    /**
     * Envia uma notificação para um usuário específico.
     *
     * @param User $user
     * @param string $title
     * @param string $message
     * @param string $type
     * @param string|null $actionUrl
     * @return void
     */
    public function sendToUser(User $user, string $title, string $message, string $type = 'info', ?string $actionUrl = null): void
    {
        $user->notify(new GeneralPurposeNotification($title, $message, $type, $actionUrl));
    }

    /**
     * Envia uma notificação para múltiplos usuários.
     *
     * @param Collection|array $users
     * @param string $title
     * @param string $message
     * @param string $type
     * @param string|null $actionUrl
     * @return void
     */
    public function sendToMultipleUsers($users, string $title, string $message, string $type = 'info', ?string $actionUrl = null): void
    {
        LaravelNotification::send($users, new GeneralPurposeNotification($title, $message, $type, $actionUrl));
    }

    /**
     * Envia uma notificação para todos os usuários do sistema.
     *
     * @param string $title
     * @param string $message
     * @param string $type
     * @param string|null $actionUrl
     * @return void
     */
    public function sendToAll(string $title, string $message, string $type = 'info', ?string $actionUrl = null): void
    {
        $users = User::where('ativo', true)->get();
        $this->sendToMultipleUsers($users, $title, $message, $type, $actionUrl);
    }

    /**
     * Busca as notificações de um usuário.
     *
     * @param User $user
     * @param int $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getUserNotifications(User $user, int $limit = 15)
    {
        return $user->notifications()->paginate($limit);
    }

    /**
     * Retorna a contagem de notificações não lidas de um usuário.
     *
     * @param User $user
     * @return int
     */
    public function getUnreadCount(User $user): int
    {
        return $user->unreadNotifications()->count();
    }

    /**
     * Marca uma notificação específica como lida.
     *
     * @param User $user
     * @param string $notificationId
     * @return bool
     */
    public function markAsRead(User $user, string $notificationId): bool
    {
        $notification = $user->notifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
            return true;
        }
        return false;
    }

    /**
     * Marca todas as notificações de um usuário como lidas.
     *
     * @param User $user
     * @return void
     */
    public function markAllAsRead(User $user): void
    {
        $user->unreadNotifications->markAsRead();
    }

    /**
     * Exclui uma notificação específica.
     *
     * @param User $user
     * @param string $notificationId
     * @return bool
     */
    public function deleteNotification(User $user, string $notificationId): bool
    {
        $notification = $user->notifications()->find($notificationId);
        if ($notification) {
            return $notification->delete();
        }
        return false;
    }
}