<?php

namespace App\Services;

use App\Models\ChatRoom;
use App\Models\ChatParticipant;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Events\ChatMessageSent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class ChatService
{
    // --- Métodos para Membros e Admin ---

    public function getUserRooms($userId): Collection
    {
        return ChatRoom::ativo()
            ->whereHas('participants', fn($q) => $q->where('user_id', $userId)->where('ativo', true))
            ->with(['lastMessage.user', 'participants.user'])
            ->get();
    }

    public function getRoomMessages($roomId, $userId, $limit = 50): Collection
    {
        if (!ChatParticipant::where('chat_room_id', $roomId)->where('user_id', $userId)->where('ativo', true)->exists()) {
            return collect();
        }
        return ChatMessage::where('chat_room_id', $roomId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->reverse();
    }

    public function sendMessage(int $roomId, int $userId, string $messageText, string $type = 'texto', ?UploadedFile $file = null): ?ChatMessage
    {
        $participant = ChatParticipant::where('chat_room_id', $roomId)->where('user_id', $userId)->where('ativo', true)->first();
        if (!$participant || $participant->isMuted()) {
            return null;
        }

        $messageData = ['chat_room_id' => $roomId, 'user_id' => $userId, 'mensagem' => $messageText, 'tipo' => $type];
        if ($file) {
            $fileData = $this->processFile($file);
            $messageData = array_merge($messageData, $fileData);
        }

        $chatMessage = ChatMessage::create($messageData);
        $participant->touch('ultimo_acesso');
        event(new ChatMessageSent($chatMessage->load('user'), ChatRoom::find($roomId)));
        return $chatMessage;
    }

    // --- Métodos Exclusivos de Admin ---

    public function getAdminDashboardData($userId): array
    {
        return [
            'userRooms' => $this->getUserRooms($userId),
            'availableRooms' => ChatRoom::ativo()->get(),
            'chatStats' => $this->getChatStats($userId),
            'totalRooms' => ChatRoom::count(),
            'activeRooms' => ChatRoom::ativo()->count(),
            'totalMessages' => ChatMessage::count(),
            'totalParticipants' => ChatParticipant::ativo()->count(),
            'rooms' => ChatRoom::with(['lastMessage', 'participants.user'])->get()
        ];
    }

    public function createRoom(array $data, int $creatorId): ChatRoom
    {
        $room = ChatRoom::create($data);
        if (isset($data['participantes'])) {
            foreach ($data['participantes'] as $userId) {
                ChatParticipant::create(['chat_room_id' => $room->id, 'user_id' => $userId, 'tipo' => 'participante']);
            }
        }
        ChatParticipant::create(['chat_room_id' => $room->id, 'user_id' => $creatorId, 'tipo' => 'admin']);
        return $room;
    }

    public function updateRoom(ChatRoom $room, array $data): bool
    {
        return $room->update($data);
    }

    public function deleteRoom(ChatRoom $room): ?bool
    {
        return $room->delete();
    }

    public function getParticipantsForManagement(ChatRoom $room): array
    {
        $participants = $room->participants()->with('user')->get();
        $participantUserIds = $participants->pluck('user_id')->toArray();
        $availableUsers = User::whereNotIn('id', $participantUserIds)->get();
        return compact('room', 'participants', 'availableUsers');
    }

    public function addParticipant(ChatRoom $room, int $userId, string $tipo): ChatParticipant
    {
        return ChatParticipant::updateOrCreate(
            ['chat_room_id' => $room->id, 'user_id' => $userId],
            ['tipo' => $tipo, 'ativo' => true, 'ultimo_acesso' => now()]
        );
    }

    public function removeParticipant(ChatParticipant $participant): bool
    {
        return $participant->update(['ativo' => false]);
    }

    public function toggleMute(ChatParticipant $participant): bool
    {
        return $participant->update(['mute_permanente' => !$participant->mute_permanente]);
    }

    public function getChatStatistics(): array
    {
        $messagesByDay = ChatMessage::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')->orderBy('date')->get();

        $last30Days = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dayData = $messagesByDay->firstWhere('date', $date);
            $last30Days->push(['date' => $date, 'total' => $dayData ? $dayData->total : 0]);
        }

        return [
            'totalRooms' => ChatRoom::count(),
            'activeRooms' => ChatRoom::ativo()->count(),
            'totalMessages' => ChatMessage::count(),
            'totalParticipants' => ChatParticipant::ativo()->count(),
            'participantsByType' => ChatParticipant::selectRaw('tipo, COUNT(*) as total')->where('ativo', true)->groupBy('tipo')->get(),
            'mutedUsers' => ChatParticipant::where('mute_permanente', true)->orWhere('mute_until', '>', now())->count(),
            'messagesByType' => ChatMessage::selectRaw('tipo, COUNT(*) as total')->groupBy('tipo')->get(),
            'roomsByType' => ChatRoom::selectRaw('tipo, COUNT(*) as total')->groupBy('tipo')->get(),
            'messagesByDay' => $last30Days,
            'topRooms' => ChatRoom::withCount('messages')->orderBy('messages_count', 'desc')->limit(5)->get(),
            'topUsers' => User::withCount('chatMessages')->orderBy('chat_messages_count', 'desc')->limit(5)->get(),
        ];
    }

    public function adminDeleteMessage(ChatMessage $message, User $admin): bool
    {
        $participant = $message->chatRoom->participants()->where('user_id', $admin->id)->where('ativo', true)->first();
        if (!$participant || !in_array($participant->tipo, ['admin', 'moderador'])) {
            return false;
        }
        Log::info("Mensagem excluída por admin", ['admin_id' => $admin->id, 'message_id' => $message->id]);
        return $message->delete();
    }

    public function adminClearChat(ChatRoom $room, User $admin): int
    {
        $participant = $room->participants()->where('user_id', $admin->id)->where('ativo', true)->first();
        if (!$participant || !in_array($participant->tipo, ['admin', 'moderador'])) {
            throw new \Exception("Usuário não autorizado a limpar o chat.");
        }
        $deletedCount = $room->messages()->delete();
        Log::info("Chat limpo por admin", ['admin_id' => $admin->id, 'room_id' => $room->id, 'deleted_count' => $deletedCount]);
        return $deletedCount;
    }

    private function processFile(UploadedFile $file): array
    {
        $filePath = $file->store('chat-files', 'public');
        return [
            'arquivo_url' => Storage::url($filePath),
            'arquivo_nome' => $file->getClientOriginalName(),
            'arquivo_tipo' => $file->getMimeType(),
            'arquivo_tamanho' => $file->getSize()
        ];
    }

    public function getChatStats($userId): array
    {
        $userRooms = $this->getUserRooms($userId);
        $totalUnread = 0;
        foreach ($userRooms as $room) {
            if (method_exists($room, 'unreadMessagesCount')) {
                $totalUnread += $room->unreadMessagesCount($userId);
            }
        }
        return ['total_rooms' => $userRooms->count(), 'total_unread' => $totalUnread];
    }
}