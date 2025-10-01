<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * A notificação que foi criada.
     *
     * @var \App\Models\Notification
     */
    public $notification;

    /**
     * Create a new event instance.
     */
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Envia a notificação para um canal privado do usuário específico.
        // O frontend (Laravel Echo) precisará ouvir neste canal.
        return [
            new PrivateChannel('user.' . $this->notification->user_id),
        ];
    }

    /**
     * O nome do evento que será transmitido.
     */
    public function broadcastAs(): string
    {
        return 'notification.created';
    }

    /**
     * Os dados que serão transmitidos com o evento.
     */
    public function broadcastWith(): array
    {
        return [
            'notification' => [
                'id' => $this->notification->id,
                'type' => $this->notification->type,
                'data' => $this->notification->data,
                'created_at' => $this->notification->created_at->toIso8601String(),
            ],
        ];
    }
}