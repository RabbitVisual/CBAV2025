<?php

namespace App\Listeners;

use App\Events\ChatMessageSent;
use App\Events\NotificationCreated;
use App\Models\ChatParticipant;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SendChatNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'notifications';

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ChatMessageSent $event): void
    {
        $message = $event->message;
        $room = $event->room;
        
        // Buscar participantes da sala (exceto o remetente)
        $participants = ChatParticipant::where('chat_room_id', $room->id)
                                      ->where('user_id', '!=', $message->user_id)
                                      ->where('ativo', true)
                                      ->with('user')
                                      ->get();
        
        foreach ($participants as $participant) {
            // Não enviar notificação se o participante estiver com a sala mutada
            if ($participant->isMuted()) {
                continue;
            }

            // Criar a notificação no banco de dados
            $notification = Notification::create([
                'user_id' => $participant->user_id,
                'type' => 'App\Notifications\NewChatMessage', // Tipo para identificar a notificação
                'notifiable_type' => get_class($message),
                'notifiable_id' => $message->id,
                'data' => [
                    'room_id' => $room->id,
                    'room_name' => $room->nome,
                    'sender_id' => $message->user_id,
                    'sender_name' => $message->user->name,
                    'sender_photo' => $message->user->profile_photo_url,
                    'message_preview' => Str::limit($message->mensagem, 50),
                    'link' => route('member.chat.show', ['room' => $room->id]),
                ],
            ]);

            // Disparar um evento para notificar o frontend em tempo real
            // Este evento será capturado pelo Laravel Echo.
            event(new NotificationCreated($notification));
        }
    }
}