<?php

namespace App\Services;

use App\Models\ChatRoom;
use App\Models\ChatParticipant;
use App\Models\ChatMessage;
use App\Models\ChatMessageRead;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Events\ChatMessageSent;

class ChatService
{
    /**
     * Obter salas disponíveis para o usuário
     */
    public function getAvailableRooms($userId): \Illuminate\Database\Eloquent\Collection
    {
        $user = User::find($userId);
        
        $query = ChatRoom::ativo()->with(['lastMessage', 'participants.user']);
        
        // Filtrar por tipo de usuário
        if ($user->hasRole('admin')) {
            // Admins podem ver todas as salas
            $query->whereIn('tipo', ['publico', 'privado', 'ministerio', 'admin']);
        } elseif ($user->hasRole('moderador')) {
            // Moderadores podem ver salas públicas, privadas e de ministério
            $query->whereIn('tipo', ['publico', 'privado', 'ministerio']);
        } else {
            // Usuários normais só podem ver salas públicas
            $query->where('tipo', 'publico');
        }
        
        return $query->get();
    }

    /**
     * Obter salas onde o usuário é participante
     */
    public function getUserRooms($userId): \Illuminate\Database\Eloquent\Collection
    {
        return ChatRoom::ativo()
                       ->whereHas('participants', function($query) use ($userId) {
                           $query->where('user_id', $userId)->where('ativo', true);
                       })
                       ->with(['lastMessage', 'participants.user'])
                       ->get();
    }

    /**
     * Entrar em uma sala
     */
    public function joinRoom($roomId, $userId): bool
    {
        $room = ChatRoom::find($roomId);
        
        if (!$room || !$room->canUserJoin($userId)) {
            return false;
        }
        
        // Verificar se já é participante
        $participant = ChatParticipant::where('chat_room_id', $roomId)
                                     ->where('user_id', $userId)
                                     ->first();
        
        if ($participant) {
            // Ativar participante se estava inativo
            if (!$participant->ativo) {
                $participant->update([
                    'ativo' => true,
                    'ultimo_acesso' => now()
                ]);
            }
            return true;
        }
        
        // Criar novo participante
        ChatParticipant::create([
            'chat_room_id' => $roomId,
            'user_id' => $userId,
            'tipo' => 'participante',
            'ativo' => true,
            'ultimo_acesso' => now()
        ]);
        
        // Enviar mensagem de sistema
        $user = User::find($userId);
        if ($user) {
            $this->sendSystemMessage($roomId, "{$user->name} entrou na sala");
        }
        
        return true;
    }

    /**
     * Sair de uma sala
     */
    public function leaveRoom($roomId, $userId): bool
    {
        $participant = ChatParticipant::where('chat_room_id', $roomId)
                                     ->where('user_id', $userId)
                                     ->first();
        
        if (!$participant) {
            return false;
        }
        
        $user = User::find($userId);
        
        // Desativar participante
        $participant->update(['ativo' => false]);
        
        // Enviar mensagem de sistema
        $this->sendSystemMessage($roomId, "{$user->name} saiu da sala");
        
        return true;
    }

    /**
     * Enviar mensagem
     */
    public function sendMessage($roomId, $userId, $message, $type = 'texto', $file = null): ?ChatMessage
    {
        // Verificar se usuário é participante
        $participant = ChatParticipant::where('chat_room_id', $roomId)
                                     ->where('user_id', $userId)
                                     ->where('ativo', true)
                                     ->first();
        
        if (!$participant || $participant->isMuted()) {
            return null;
        }
        
        $messageData = [
            'chat_room_id' => $roomId,
            'user_id' => $userId,
            'mensagem' => $message,
            'tipo' => $type
        ];
        
        // Processar arquivo se fornecido
        if ($file instanceof UploadedFile) {
            $fileData = $this->processFile($file);
            $messageData = array_merge($messageData, $fileData);
        }
        
        $chatMessage = ChatMessage::create($messageData);
        
        // Atualizar último acesso
        $participant->update(['ultimo_acesso' => now()]);
        
        // Disparar evento para notificações push
        $room = ChatRoom::find($roomId);
        event(new ChatMessageSent($chatMessage, $room));
        
        return $chatMessage;
    }

    /**
     * Enviar mensagem de sistema
     */
    public function sendSystemMessage($roomId, $message): ChatMessage
    {
        // Buscar primeiro usuário admin ou criar um usuário sistema
        $systemUser = User::whereHas('roles', function($query) {
            $query->where('name', 'admin');
        })->first();
        
        if (!$systemUser) {
            $systemUser = User::first();
        }
        
        return ChatMessage::create([
            'chat_room_id' => $roomId,
            'user_id' => $systemUser ? $systemUser->id : 1,
            'mensagem' => $message,
            'tipo' => 'sistema'
        ]);
    }

    /**
     * Processar arquivo enviado
     */
    private function processFile(UploadedFile $file): array
    {
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->store('chat-files', 'public');
        
        return [
            'arquivo_url' => Storage::url($filePath),
            'arquivo_nome' => $file->getClientOriginalName(),
            'arquivo_tipo' => $file->getMimeType(),
            'arquivo_tamanho' => $file->getSize()
        ];
    }

    /**
     * Marcar mensagem como lida
     */
    public function markMessageAsRead($messageId, $userId): bool
    {
        $message = ChatMessage::find($messageId);
        
        if (!$message) {
            return false;
        }
        
        // Verificar se usuário é participante da sala
        $participant = ChatParticipant::where('chat_room_id', $message->chat_room_id)
                                     ->where('user_id', $userId)
                                     ->where('ativo', true)
                                     ->first();
        
        if (!$participant) {
            return false;
        }
        
        // Criar ou atualizar registro de leitura
        ChatMessageRead::updateOrCreate(
            ['chat_message_id' => $messageId, 'user_id' => $userId],
            ['lido_em' => now()]
        );
        
        return true;
    }

    /**
     * Editar mensagem
     */
    public function editMessage($messageId, $userId, $newMessage): bool
    {
        $message = ChatMessage::find($messageId);
        
        if (!$message || !$message->canBeEditedBy($userId)) {
            return false;
        }
        
        $message->update([
            'mensagem' => $newMessage,
            'editado' => true,
            'editado_em' => now()
        ]);
        
        return true;
    }

    /**
     * Deletar mensagem
     */
    public function deleteMessage($messageId, $userId): bool
    {
        $message = ChatMessage::find($messageId);
        
        if (!$message || !$message->canBeDeletedBy($userId)) {
            return false;
        }
        
        $message->update([
            'deletado' => true,
            'deletado_em' => now(),
            'deletado_por' => $userId
        ]);
        
        return true;
    }

    /**
     * Adicionar reação
     */
    public function addReaction($messageId, $userId, $reaction): bool
    {
        $message = ChatMessage::find($messageId);
        
        if (!$message) {
            return false;
        }
        
        $message->addReaction($userId, $reaction);
        
        return true;
    }

    /**
     * Remover reação
     */
    public function removeReaction($messageId, $userId, $reaction): bool
    {
        $message = ChatMessage::find($messageId);
        
        if (!$message) {
            return false;
        }
        
        $message->removeReaction($userId, $reaction);
        
        return true;
    }

    /**
     * Obter mensagens de uma sala
     */
    public function getRoomMessages($roomId, $userId, $limit = 50, $offset = 0): \Illuminate\Database\Eloquent\Collection
    {
        // Verificar se usuário é participante
        $participant = ChatParticipant::where('chat_room_id', $roomId)
                                     ->where('user_id', $userId)
                                     ->where('ativo', true)
                                     ->first();
        
        if (!$participant) {
            return collect();
        }
        
        return ChatMessage::where('chat_room_id', $roomId)
                         ->naoDeletadas()
                         ->with(['user', 'reads'])
                         ->orderBy('created_at', 'desc')
                         ->limit($limit)
                         ->offset($offset)
                         ->get()
                         ->reverse();
    }

    /**
     * Obter participantes de uma sala
     */
    public function getRoomParticipants($roomId): \Illuminate\Database\Eloquent\Collection
    {
        return ChatParticipant::where('chat_room_id', $roomId)
                              ->where('ativo', true)
                              ->with('user')
                              ->get();
    }

    /**
     * Obter estatísticas do chat
     */
    public function getChatStats($userId): array
    {
        $userRooms = $this->getUserRooms($userId);
        
        $totalUnread = 0;
        foreach ($userRooms as $room) {
            $totalUnread += $room->unreadMessagesCount($userId);
        }
        
        return [
            'total_rooms' => $userRooms->count(),
            'total_unread' => $totalUnread,
            'active_rooms' => $userRooms->where('lastMessage.created_at', '>=', now()->subDays(7))->count()
        ];
    }
} 