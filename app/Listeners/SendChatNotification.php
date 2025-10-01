<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ChatMessageSent;
use App\Models\ChatParticipant;
use Illuminate\Support\Facades\Log;

class SendChatNotification implements ShouldQueue
{
    use InteractsWithQueue;

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
            // Verificar se o participante não está mutado
            if (!$participant->isMuted()) {
                $this->sendNotification($participant->user, $message, $room);
            }
        }
    }
    
    private function sendNotification($user, $message, $room)
    {
        // Aqui você pode integrar com serviços de notificação push
        // como Firebase, OneSignal, etc.
        
        $notificationData = [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'message' => $message->mensagem,
            'sender' => $message->user->name,
            'room' => $room->nome,
            'timestamp' => now()->toISOString()
        ];
        
        // Log da notificação (para debug)
        Log::info('Notificação push enviada', $notificationData);
        
        // Exemplo de integração com Firebase (comentado)
        /*
        $firebase = new Firebase();
        $firebase->sendNotification([
            'to' => $user->fcm_token,
            'notification' => [
                'title' => "Nova mensagem em {$room->nome}",
                'body' => "{$message->user->name}: {$message->mensagem}",
                'icon' => '/icon.png',
                'click_action' => "/chat/{$room->id}"
            ],
            'data' => [
                'room_id' => $room->id,
                'message_id' => $message->id,
                'sender_id' => $message->user_id
            ]
        ]);
        */
    }
} 